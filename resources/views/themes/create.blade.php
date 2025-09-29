<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upload Monthly Theme Image') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="mb-4 font-medium text-green-600">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('themes.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="image" class="block text-sm font-medium text-gray-700">Theme Image</label>
                            <input type="file" name="image" id="image" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required accept="image/*">
                            @error('image')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 