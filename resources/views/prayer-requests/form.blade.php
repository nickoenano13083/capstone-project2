@props(['prayerRequest' => null])

<form method="POST" action="{{ $prayerRequest ? route('prayer-requests.update', $prayerRequest) : route('prayer-requests.store') }}" class="space-y-6">
    @csrf
    @if($prayerRequest)
        @method('PUT')
    @endif

    <div>
        <x-input-label for="member_id" :value="__('Member')" />
        <select id="member_id" name="member_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
            <option value="">Select a member</option>
            @foreach($members as $member)
                <option value="{{ $member->id }}" {{ old('member_id', $prayerRequest?->member_id) == $member->id ? 'selected' : '' }}>
                    {{ $member->name }}
                </option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('member_id')" />
    </div>

    <div>
        <x-input-label for="title" :value="__('Title')" />
        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $prayerRequest?->title)" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('title')" />
    </div>

    <div>
        <x-input-label for="description" :value="__('Description')" />
        <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4" required>{{ old('description', $prayerRequest?->description) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('description')" />
    </div>

    <div>
        <x-input-label for="status" :value="__('Status')" />
        <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="pending" {{ old('status', $prayerRequest?->status) === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="in_progress" {{ old('status', $prayerRequest?->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
            <option value="completed" {{ old('status', $prayerRequest?->status) === 'completed' ? 'selected' : '' }}>Completed</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('status')" />
    </div>

    <div class="flex items-center gap-4">
        <x-primary-button>{{ $prayerRequest ? __('Update Prayer Request') : __('Create Prayer Request') }}</x-primary-button>
        <a href="{{ route('prayer-requests.index') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
    </div>
</form> 