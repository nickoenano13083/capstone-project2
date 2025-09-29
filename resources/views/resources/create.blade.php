<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Upload Resource') }}
            </h2>
            <a href="{{ route('resources.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Resources
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('resources.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Resource Title *</label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter resource title">
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="description" name="description" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Describe the resource">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Type and Category -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                                <select id="type" name="type" required 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Type</option>
                                    <option value="document" {{ old('type') == 'document' ? 'selected' : '' }}>Document</option>
                                    <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>Video</option>
                                    <option value="link" {{ old('type') == 'link' ? 'selected' : '' }}>Link</option>
                                    <option value="image" {{ old('type') == 'image' ? 'selected' : '' }}>Image</option>
                                    <option value="audio" {{ old('type') == 'audio' ? 'selected' : '' }}>Audio</option>
                                    <option value="pdf" {{ old('type') == 'pdf' ? 'selected' : '' }}>PDF</option>
                                    <option value="presentation" {{ old('type') == 'presentation' ? 'selected' : '' }}>Presentation</option>
                                </select>
                                @error('type')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                                <select id="category" name="category" required 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Category</option>
                                    <option value="sermons" {{ old('category') == 'sermons' ? 'selected' : '' }}>Sermons</option>
                                    <option value="bible-study" {{ old('category') == 'bible-study' ? 'selected' : '' }}>Bible Study</option>
                                    <option value="worship" {{ old('category') == 'worship' ? 'selected' : '' }}>Worship</option>
                                    <option value="youth" {{ old('category') == 'youth' ? 'selected' : '' }}>Youth Ministry</option>
                                    <option value="children" {{ old('category') == 'children' ? 'selected' : '' }}>Children's Ministry</option>
                                    <option value="outreach" {{ old('category') == 'outreach' ? 'selected' : '' }}>Outreach</option>
                                    <option value="administration" {{ old('category') == 'administration' ? 'selected' : '' }}>Administration</option>
                                    <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>General</option>
                                </select>
                                @error('category')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Chapter -->
                        <div class="mb-4">
                            <label for="chapter_id" class="block text-sm font-medium text-gray-700 mb-1">Chapter *</label>
                            <select id="chapter_id" name="chapter_id" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Chapter</option>
                                @foreach(($chapters ?? []) as $chapter)
                                    <option value="{{ $chapter->id }}" {{ old('chapter_id') == $chapter->id ? 'selected' : '' }}>{{ $chapter->name }}</option>
                                @endforeach
                            </select>
                            @error('chapter_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- File Upload -->
                        <div class="mb-4">
                            <label for="images" class="block text-sm font-medium text-gray-700 mb-1">Upload Images (up to 10, Max 10MB each)</label>
                            <input type="file" id="images" name="images[]" multiple accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Supported: JPG, PNG, GIF, JPEG, WEBP. You can select up to 10 images.</p>
                            @error('images')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            @error('images.*')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <!-- Multiple image preview -->
                            <div id="multiImagePreviewContainer" class="mt-2 flex flex-wrap gap-2"></div>
                        </div>

                        <!-- URL -->
                        <div class="mb-4">
                            <label for="url" class="block text-sm font-medium text-gray-700 mb-1">External URL (Optional)</label>
                            <input type="url" id="url" name="url" value="{{ old('url') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="https://example.com/resource">
                            <p class="text-xs text-gray-500 mt-1">Add an external link if the resource is hosted elsewhere</p>
                            @error('url')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Public/Private -->
                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" id="is_public" name="is_public" value="1" {{ old('is_public', true) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Make this resource public (visible to all users)</span>
                            </label>
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                            <button type="button" onclick="window.history.back()" 
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Upload Resource
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
document.getElementById('images').addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    const previewContainer = document.getElementById('multiImagePreviewContainer');
    previewContainer.innerHTML = '';
    if (files.length > 10) {
        alert('You can only upload up to 10 images.');
        e.target.value = '';
        return;
    }
    files.forEach(file => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                const img = document.createElement('img');
                img.src = ev.target.result;
                img.className = 'h-24 w-auto rounded border border-gray-300 shadow';
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    });
});
</script> 