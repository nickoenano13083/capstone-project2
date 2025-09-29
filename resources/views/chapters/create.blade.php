<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create Chapter') }}
            </h2>
            <a href="{{ route('chapters.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Chapters
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('chapters.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-6">
                            <x-input-label for="name" :value="__('Chapter Name')" class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Chapter Name
                            </x-input-label>
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus placeholder="Enter chapter name..." />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="location" :value="__('Location')" class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Location
                            </x-input-label>
                            <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location')" required placeholder="Enter chapter location..." />
                            <x-input-error :messages="$errors->get('location')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="description" :value="__('Description')" class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Description
                            </x-input-label>
                            <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required placeholder="Describe the chapter's purpose and activities...">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="leader_type" :value="__('Chapter Leader')" class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Chapter Leader
                            </x-input-label>
                            <div class="mt-2 space-y-4">
                                <!-- Member Leaders -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <label class="text-sm font-medium text-gray-700 mb-3 block flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        Church Members
                                    </label>
                                    @if($members->count() > 0)
                                        @foreach($members as $member)
                                            <label class="flex items-center mb-2 p-2 hover:bg-gray-100 rounded cursor-pointer">
                                                <input type="radio" name="leader_type" value="member" class="mr-3" 
                                                    {{ old('leader_type') == 'member' && old('leader_id') == $member->id ? 'checked' : '' }}>
                                                <input type="radio" name="leader_id" value="{{ $member->id }}" class="mr-3" 
                                                    {{ old('leader_type') == 'member' && old('leader_id') == $member->id ? 'checked' : '' }}>
                                                <div>
                                                    <span class="text-sm font-medium text-gray-900">{{ $member->name }}</span>
                                                    <span class="text-xs text-gray-500 ml-2">({{ $member->role }})</span>
                                                </div>
                                            </label>
                                        @endforeach
                                    @else
                                        <p class="text-sm text-gray-500 italic">No members available</p>
                                    @endif
                                </div>

                                <!-- User Leaders -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <label class="text-sm font-medium text-gray-700 mb-3 block flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                        </svg>
                                        Registered Users
                                    </label>
                                    @if($users->count() > 0)
                                        @foreach($users as $user)
                                            <label class="flex items-center mb-2 p-2 hover:bg-gray-100 rounded cursor-pointer">
                                                <input type="radio" name="leader_type" value="user" class="mr-3" 
                                                    {{ old('leader_type') == 'user' && old('leader_id') == $user->id ? 'checked' : '' }}>
                                                <input type="radio" name="leader_id" value="{{ $user->id }}" class="mr-3" 
                                                    {{ old('leader_type') == 'user' && old('leader_id') == $user->id ? 'checked' : '' }}>
                                                <div>
                                                    <span class="text-sm font-medium text-gray-900">{{ $user->name }}</span>
                                                    <span class="text-xs text-gray-500 ml-2">({{ $user->role }})</span>
                                                </div>
                                            </label>
                                        @endforeach
                                    @else
                                        <p class="text-sm text-gray-500 italic">No users available</p>
                                    @endif
                                </div>

                                <!-- No Leader Option -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <label class="flex items-center p-2 hover:bg-gray-100 rounded cursor-pointer">
                                        <input type="radio" name="leader_type" value="" class="mr-3" 
                                            {{ old('leader_type') == '' ? 'checked' : '' }}>
                                        <span class="text-sm text-gray-500">No leader assigned</span>
                                    </label>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('leader_id')" class="mt-2" />
                            <x-input-error :messages="$errors->get('leader_type')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="status" :value="__('Status')" class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Status
                            </x-input-label>
                            <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-200">
                            <x-secondary-button type="button" onclick="window.history.back()" class="mr-4">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                            <x-primary-button class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                {{ __('Create Chapter') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 