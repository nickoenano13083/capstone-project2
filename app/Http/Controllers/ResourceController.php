<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');
        $type = $request->input('type');

        $query = Resource::with(['uploader', 'images'])
            ->where('status', 'active');
        // Leader scope: only show resources in their chapters
        if (auth()->check() && auth()->user()->role === 'Leader') {
            $leaderChapterIds = auth()->user()->ledChapters()->pluck('id');
            $query->whereIn('chapter_id', $leaderChapterIds);
        }

        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply category filter
        if ($category) {
            $query->where('category', $category);
        }

        // Apply type filter
        if ($type) {
            $query->where('type', $type);
        }

        $resources = $query->orderBy('created_at', 'desc')->paginate(12);

        // Get unique categories and types for filters
        $categories = Resource::distinct()->pluck('category')->filter();
        $types = Resource::distinct()->pluck('type')->filter();

        return view('resources.index', compact('resources', 'search', 'category', 'type', 'categories', 'types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['Admin', 'Leader'])) {
            abort(403, 'Unauthorized');
        }
        $chaptersQuery = \App\Models\Chapter::orderBy('name');
        if (auth()->check() && auth()->user()->role === 'Leader') {
            $leaderChapterIds = auth()->user()->ledChapters()->pluck('id');
            $chaptersQuery->whereIn('id', $leaderChapterIds);
        }
        $chapters = $chaptersQuery->get();
        return view('resources.create', compact('chapters'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['Admin', 'Leader'])) {
            abort(403, 'Unauthorized');
        }
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:document,video,link,image,audio,pdf,presentation',
            'category' => 'required|string|max:100',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB per image
            'url' => 'nullable|url',
            'is_public' => 'boolean',
            'chapter_id' => 'required|exists:chapters,id',
        ]);

        $data = $request->all();
        $data['uploaded_by'] = Auth::id();
        $data['is_public'] = $request->has('is_public');

        if (auth()->check() && auth()->user()->role === 'Leader') {
            $leaderChapterIds = auth()->user()->ledChapters()->pluck('id')->toArray();
            if (!in_array($data['chapter_id'], $leaderChapterIds)) {
                abort(403, 'You can only create resources for your chapters.');
            }
        }

        // Create the resource first
        $resource = Resource::create($data);

        // Handle multiple image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $filePath = $image->storeAs('resources', $fileName, 'public');
                    $resource->images()->create([
                        'file_path' => $filePath,
                        'file_type' => $image->getMimeType(),
                        'file_size' => $image->getSize(),
                    ]);
                }
            }
        }

        return redirect()->route('resources.index')
            ->with('success', 'Resource and images uploaded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Resource $resource)
    {
        $resource->load('images');
        return view('resources.show', compact('resource'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Resource $resource)
    {
        if (auth()->check() && auth()->user()->role === 'Leader') {
            $leaderChapterIds = auth()->user()->ledChapters()->pluck('id');
            if (!$leaderChapterIds->contains($resource->chapter_id)) {
                abort(403, 'Access denied.');
            }
        }

        $chaptersQuery = \App\Models\Chapter::orderBy('name');
        if (auth()->check() && auth()->user()->role === 'Leader') {
            $leaderChapterIds = auth()->user()->ledChapters()->pluck('id');
            $chaptersQuery->whereIn('id', $leaderChapterIds);
        }
        $chapters = $chaptersQuery->get();

        return view('resources.edit', compact('resource', 'chapters'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Resource $resource)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:document,video,link,image,audio,pdf,presentation',
            'category' => 'required|string|max:100',
            'file' => 'nullable|file|max:10240',
            'url' => 'nullable|url',
            'is_public' => 'boolean',
            'status' => 'required|in:active,inactive,archived',
            'chapter_id' => 'required|exists:chapters,id',
        ]);

        $data = $request->all();
        $data['is_public'] = $request->has('is_public');

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($resource->file_path) {
                Storage::disk('public')->delete($resource->file_path);
            }

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('resources', $fileName, 'public');
            
            $data['file_path'] = $filePath;
            $data['file_size'] = $file->getSize();
            $data['file_type'] = $file->getMimeType();
        }

        if (auth()->check() && auth()->user()->role === 'Leader') {
            $leaderChapterIds = auth()->user()->ledChapters()->pluck('id')->toArray();
            if (!in_array($data['chapter_id'], $leaderChapterIds)) {
                abort(403, 'You can only update resources for your chapters.');
            }
        }

        $resource->update($data);

        return redirect()->route('resources.index')
            ->with('success', 'Resource updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Resource $resource)
    {
        if (auth()->check() && auth()->user()->role === 'Leader') {
            $leaderChapterIds = auth()->user()->ledChapters()->pluck('id');
            if (!$leaderChapterIds->contains($resource->chapter_id)) {
                abort(403, 'Access denied.');
            }
        }
        // Delete file if exists
        if ($resource->file_path) {
            Storage::disk('public')->delete($resource->file_path);
        }

        $resource->delete();

        return redirect()->route('resources.index')
            ->with('success', 'Resource deleted successfully.');
    }

    /**
     * Download the specified resource.
     */
    public function download(Resource $resource)
    {
        if (!$resource->file_path) {
            return redirect()->back()->with('error', 'No file available for download.');
        }

        // Check if file exists
        if (!Storage::disk('public')->exists($resource->file_path)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        // Increment download count
        $resource->increment('download_count');

        return Storage::disk('public')->download($resource->file_path);
    }

    /**
     * Test file upload functionality
     */
    public function testUpload(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            return response()->json([
                'success' => true,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'file_type' => $file->getMimeType(),
                'is_valid' => $file->isValid(),
                'error' => $file->getError(),
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'No file uploaded',
            'request_data' => $request->all(),
            'files' => $request->allFiles(),
        ]);
    }
}
