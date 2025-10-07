<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Chapter Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('chapters.edit', $chapter) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Chapter
                </a>
                <a href="{{ route('chapters.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="dashboard-main-content p-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    <!-- Chapter Header -->
                    <div class="mb-8">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h1 class="text-2xl font-bold text-gray-900">{{ $chapter->name }}</h1>
                                <p class="text-gray-600">{{ $chapter->location }}</p>
                            </div>
                            <div class="ml-auto">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $chapter->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    @if($chapter->status === 'active')
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                    {{ ucfirst($chapter->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-700">{{ $chapter->description }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Chapter Information -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Chapter Information
                            </h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Location</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $chapter->location }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Created</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $chapter->created_at->format('F j, Y \a\t g:i A') }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $chapter->updated_at->format('F j, Y \a\t g:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chapter Admins -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Chapter Admins
                            </h3>
                            
                            @php
                                // Get all admins associated with this chapter
                                $chapterAdmins = \App\Models\User::where('role', 'Admin')
                                    ->where(function($query) use ($chapter) {
                                        $query->whereHas('member', function($memberQuery) use ($chapter) {
                                            $memberQuery->where('chapter_id', $chapter->id);
                                        })->orWhere('preferred_chapter_id', $chapter->id);
                                    })
                                    ->get();
                            @endphp
                            
                            @if($chapterAdmins->count() > 0)
                                <div class="space-y-4">
                                    @foreach($chapterAdmins as $admin)
                                        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-200 rounded-lg p-4">
                                            <div class="flex items-center mb-3">
                                                <div class="flex-shrink-0">
                                                    @if(!empty($admin->profile_photo_url))
                                                        <img src="{{ $admin->profile_photo_url }}" alt="{{ $admin->name }}" class="h-10 w-10 rounded-full object-cover">
                                                    @else
                                                        <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                                            <span class="text-sm font-medium text-purple-800">{{ strtoupper(substr($admin->name, 0, 1)) }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-3 flex-1">
                                                    <h4 class="text-sm font-medium text-gray-900">{{ $admin->name }}</h4>
                                                    <p class="text-xs text-gray-600">{{ $admin->email }}</p>
                                                </div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    <i class="fas fa-crown mr-1"></i>Admin
                                                </span>
                                            </div>
                                            
                                            <div class="flex items-center justify-between text-xs text-gray-500">
                                               
                                                <a href="{{ route('admin.users.show', $admin->id) }}" class="text-purple-600 hover:text-purple-800 font-medium">
                                                    View Details →
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                                    <div class="flex items-center mb-4">
                                        <svg class="w-8 h-8 text-yellow-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <h4 class="text-lg font-medium text-yellow-800">No Admins Assigned</h4>
                                            <p class="text-sm text-yellow-600">This chapter needs admins to be assigned.</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Assign Admins →
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
    </div>
</x-app-layout> 