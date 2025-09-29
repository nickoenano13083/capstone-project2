<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Resource Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('resources.edit', $resource) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Resource
                </a>
                <a href="{{ route('resources.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Resources
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    <!-- Resource Header -->
                    <div class="mb-8">
                        @if($resource->images->count() > 0)
                            <div class="mb-4 flex flex-wrap gap-4 justify-center">
                                @foreach($resource->images as $img)
                                    <div class="flex flex-col items-center">
                                        <a href="{{ asset('storage/' . $img->file_path) }}" target="_blank" title="Click to view full size">
                                            <img src="{{ asset('storage/' . $img->file_path) }}" alt="{{ $resource->title }}" class="max-h-60 rounded shadow border border-gray-200 hover:scale-105 transition-transform duration-200" onerror="this.onerror=null;this.src='{{ asset('images/junetheme.png') }}';">
                                        </a>
                                        <a href="{{ asset('storage/' . $img->file_path) }}" download class="mt-2 inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded shadow transition">
                                            <i class="fas fa-download mr-1"></i> Download
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @elseif($resource->type === 'image')
                            <div class="mb-4 flex flex-col items-center">
                                <div class="w-full flex justify-center">
                                    <div class="bg-gray-100 border border-gray-300 rounded flex items-center justify-center" style="height: 240px; width: 100%; max-width: 400px;">
                                        <span class="text-gray-400 text-6xl">
                                            <i class="fas fa-image"></i>
                                        </span>
                                    </div>
                                </div>
                                <span class="mt-2 text-gray-500 text-sm">Image not available</span>
                            </div>
                        @endif
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0">
                                <div class="h-16 w-16 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <i class="{{ $resource->type_icon }} {{ $resource->type_color }} text-3xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h1 class="text-2xl font-bold text-gray-900">{{ $resource->title }}</h1>
                                <div class="flex items-center mt-2 space-x-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($resource->type) }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ ucfirst($resource->category) }}
                                    </span>
                                    @if(!$resource->is_public)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Private
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($resource->description)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-700">{{ $resource->description }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Resource Information -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Resource Information
                            </h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Uploaded By</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $resource->uploader->name }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Upload Date</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $resource->created_at->format('F j, Y \a\t g:i A') }}</p>
                                    </div>
                                </div>

                                @if($resource->file_size)
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">File Size</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ $resource->file_size_formatted }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if($resource->file_type)
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">File Type</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ $resource->file_type }}</p>
                                        </div>
                                    </div>
                                @endif

                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Downloads</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $resource->download_count }} times</p>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $resource->updated_at->format('F j, Y \a\t g:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Actions
                            </h3>
                            
                            <div class="space-y-4">
                                @if($resource->file_path)
                                    <a href="{{ route('resources.download', $resource) }}" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Download Resource
                                    </a>
                                @endif

                                @if($resource->url)
                                    <a href="{{ $resource->url }}" target="_blank" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                        Visit External Link
                                    </a>
                                @endif

                                @if(!$resource->file_path && !$resource->url)
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-sm text-yellow-800">No file or external link available</span>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Admin Actions -->
                            @if(in_array(auth()->user()->role, ['Admin', 'Leader']))
                                <div class="border-t pt-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-3">Admin Actions</h4>
                                    <div class="space-y-2">
                                        <a href="{{ route('resources.edit', $resource) }}" class="w-full bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded-md transition duration-200 flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit Resource
                                        </a>
                                        <form action="{{ route('resources.destroy', $resource) }}" method="POST" class="inline w-full">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md transition duration-200 flex items-center justify-center" onclick="return confirm('Are you sure you want to delete this resource?')">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Delete Resource
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 