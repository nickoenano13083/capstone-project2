<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Chapter') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('chapters.update', $chapter) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Chapter Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $chapter->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="location" :value="__('Location')" />
                            <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location', $chapter->location)" required />
                            <x-input-error :messages="$errors->get('location')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('description', $chapter->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="leader_type" :value="__('Chapter Leader')" />
                            <div class="mt-2 space-y-4">
                                <!-- Member Leaders -->
                                <div>
                                    <label class="text-sm font-medium text-gray-700 mb-2 block">Members</label>
                                    @if($members->count() > 0)
                                        @foreach($members as $member)
                                            <label class="flex items-center mb-2">
                                                <input type="radio" name="leader_type" value="member" class="mr-2" 
                                                    {{ (old('leader_type', $chapter->leader_type === 'App\Models\Member' ? 'member' : '')) == 'member' && (old('leader_id', $chapter->leader_id)) == $member->id ? 'checked' : '' }}>
                                                <input type="radio" name="leader_id" value="{{ $member->id }}" class="mr-2" 
                                                    {{ (old('leader_type', $chapter->leader_type === 'App\Models\Member' ? 'member' : '')) == 'member' && (old('leader_id', $chapter->leader_id)) == $member->id ? 'checked' : '' }}>
                                                <span class="text-sm text-gray-900">{{ $member->name }} ({{ $member->role }})</span>
                                            </label>
                                        @endforeach
                                    @else
                                        <p class="text-sm text-gray-500 italic">No members available</p>
                                    @endif
                                </div>

                                <!-- User Leaders -->
                                <div>
                                    <label class="text-sm font-medium text-gray-700 mb-2 block">Registered Users</label>
                                    @if($users->count() > 0)
                                        @foreach($users as $user)
                                            <label class="flex items-center mb-2">
                                                <input type="radio" name="leader_type" value="user" class="mr-2" 
                                                    {{ (old('leader_type', $chapter->leader_type === 'App\Models\User' ? 'user' : '')) == 'user' && (old('leader_id', $chapter->leader_id)) == $user->id ? 'checked' : '' }}>
                                                <input type="radio" name="leader_id" value="{{ $user->id }}" class="mr-2" 
                                                    {{ (old('leader_type', $chapter->leader_type === 'App\Models\User' ? 'user' : '')) == 'user' && (old('leader_id', $chapter->leader_id)) == $user->id ? 'checked' : '' }}>
                                                <span class="text-sm text-gray-900">{{ $user->name }} ({{ $user->role }})</span>
                                            </label>
                                        @endforeach
                                    @else
                                        <p class="text-sm text-gray-500 italic">No users available</p>
                                    @endif
                                </div>

                                <!-- No Leader Option -->
                                <div>
                                    <label class="flex items-center">
                                        <input type="radio" name="leader_type" value="" class="mr-2" 
                                            {{ old('leader_type', $chapter->leader_type ? '' : 'checked') == '' ? 'checked' : '' }}>
                                        <span class="text-sm text-gray-500">No leader assigned</span>
                                    </label>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('leader_id')" class="mt-2" />
                            <x-input-error :messages="$errors->get('leader_type')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="active" {{ old('status', $chapter->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $chapter->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-secondary-button type="button" onclick="window.history.back()" class="mr-3">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Update Chapter') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 