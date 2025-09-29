<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Resource') }}
            </h2>
            <a href="{{ route('resources.show', $resource) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Resource
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('resources.update', $resource) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-6">
                            <x-input-label for="title" :value="__('Resource Title')" class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Resource Title
                            </x-input-label>
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $resource->title)" required autofocus placeholder="Enter resource title..." />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="description" :value="__('Description')" class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                </svg>
                                Description
                            </x-input-label>
                            <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Describe the resource...">{{ old('description', $resource->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="type" :value="__('Resource Type')" class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    Resource Type
                                </x-input-label>
                                <select id="type" name="type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Select Type</option>
                                    <option value="document" {{ old('type', $resource->type) == 'document' ? 'selected' : '' }}>Document</option>
                                    <option value="video" {{ old('type', $resource->type) == 'video' ? 'selected' : '' }}>Video</option>
                                    <option value="link" {{ old('type', $resource->type) == 'link' ? 'selected' : '' }}>Link</option>
                                    <option value="image" {{ old('type', $resource->type) == 'image' ? 'selected' : '' }}>Image</option>
                                    <option value="audio" {{ old('type', $resource->type) == 'audio' ? 'selected' : '' }}>Audio</option>
                                    <option value="pdf" {{ old('type', $resource->type) == 'pdf' ? 'selected' : '' }}>PDF</option>
                                    <option value="presentation" {{ old('type', $resource->type) == 'presentation' ? 'selected' : '' }}>Presentation</option>
                                </select>
                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="category" :value="__('Category')" class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    Category
                                </x-input-label>
                                <select id="category" name="category" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Select Category</option>
                                    <option value="sermons" {{ old('category', $resource->category) == 'sermons' ? 'selected' : '' }}>Sermons</option>
                                    <option value="bible-study" {{ old('category', $resource->category) == 'bible-study' ? 'selected' : '' }}>Bible Study</option>
                                    <option value="worship" {{ old('category', $resource->category) == 'worship' ? 'selected' : '' }}>Worship</option>
                                    <option value="youth" {{ old('category', $resource->category) == 'youth' ? 'selected' : '' }}>Youth Ministry</option>
                                    <option value="children" {{ old('category', $resource->category) == 'children' ? 'selected' : '' }}>Children's Ministry</option>
                                    <option value="outreach" {{ old('category', $resource->category) == 'outreach' ? 'selected' : '' }}>Outreach</option>
                                    <option value="administration" {{ old('category', $resource->category) == 'administration' ? 'selected' : '' }}>Administration</option>
                                    <option value="general" {{ old('category', $resource->category) == 'general' ? 'selected' : '' }}>General</option>
                                </select>
                                <x-input-error :messages="$errors->get('category')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Chapter -->
                        <div class="mb-6">
                            <x-input-label for="chapter_id" :value="__('Chapter')" class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7l9-4 9 4-9 4-9-4zm0 6l9 4 9-4"></path>
                                </svg>
                                Chapter
                            </x-input-label>
                            <select id="chapter_id" name="chapter_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select Chapter</option>
                                @foreach(($chapters ?? []) as $chapter)
                                    <option value="{{ $chapter->id }}" {{ old('chapter_id', $resource->chapter_id) == $chapter->id ? 'selected' : '' }}>{{ $chapter->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('chapter_id')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="status" :value="__('Status')" class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Status
                                </x-input-label>
                                <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="active" {{ old('status', $resource->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $resource->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="archived" {{ old('status', $resource->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="url" :value="__('External URL (Optional)')" class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                    External URL
                                </x-input-label>
                                <x-text-input id="url" class="block mt-1 w-full" type="url" name="url" :value="old('url', $resource->url)" placeholder="https://example.com/resource" />
                                <p class="mt-1 text-sm text-gray-500">Add an external link if the resource is hosted elsewhere</p>
                                <x-input-error :messages="$errors->get('url')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Current File Information -->
                        @if($resource->file_path)
                            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Current File
                                </h4>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="{{ $resource->type_icon }} {{ $resource->type_color }} text-2xl mr-3"></i>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ basename($resource->file_path) }}</p>
                                            <p class="text-xs text-gray-500">{{ $resource->file_size_formatted }} • {{ $resource->file_type }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('resources.download', $resource) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Download
                                    </a>
                                </div>
                            </div>
                        @endif

                        <!-- File Upload Section -->
                        <div class="mb-6">
                            <x-input-label for="file" :value="__('Replace File (Optional)')" class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                Replace File (Max 10MB)
                            </x-input-label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition duration-200">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Upload a new file</span>
                                            <input id="file" name="file" type="file" class="sr-only" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif,.mp4,.mp3,.wav,.ppt,.pptx,.xls,.xlsx">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        Leave empty to keep current file
                                    </p>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('file')" class="mt-2" />
                        </div>

                        <!-- Privacy Settings -->
                        <div class="mb-6">
                            <div class="flex items-center">
                                <input id="is_public" name="is_public" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ old('is_public', $resource->is_public) ? 'checked' : '' }}>
                                <label for="is_public" class="ml-2 block text-sm text-gray-900">
                                    Make this resource public (visible to all users)
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Uncheck to make this resource private (only visible to admins and leaders)</p>
                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-200">
                            <x-secondary-button type="button" onclick="window.history.back()" class="mr-4">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                            <x-primary-button class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ __('Update Resource') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 