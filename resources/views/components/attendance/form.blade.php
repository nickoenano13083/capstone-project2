@props(['attendanceRecord' => null, 'members' => [], 'events' => []])

<form method="POST" action="{{ $attendanceRecord ? route('attendance.update', $attendanceRecord) : route('attendance.store') }}" class="space-y-6">
    @csrf
    @if($attendanceRecord)
        @method('PUT')
    @endif

    <div>
        <x-input-label for="member_id" :value="__('Member')" />
        <select id="member_id" name="member_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
            <option value="">Select a member</option>
            @foreach($members as $member)
                <option value="{{ $member->id }}" {{ old('member_id', $attendanceRecord?->member_id) == $member->id ? 'selected' : '' }}>
                    {{ $member->name }}
                </option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('member_id')" />
    </div>

    <div>
        <x-input-label for="event_id" :value="__('Event')" />
        <select id="event_id" name="event_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
            <option value="">Select an event</option>
            @foreach($events as $event)
                <option value="{{ $event->id }}" {{ old('event_id', $attendanceRecord?->event_id) == $event->id ? 'selected' : '' }}>
                    {{ $event->title }} ({{ $event->date->format('M d, Y') }})
                </option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('event_id')" />
    </div>

    <div>
        <x-input-label for="status" :value="__('Status')" />
        <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="present" {{ old('status', $attendanceRecord?->status) === 'present' ? 'selected' : '' }}>Present</option>
            <option value="absent" {{ old('status', 'absent') === 'absent' ? 'selected' : '' }}>Absent</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('status')" />
    </div>

    <div class="flex items-center gap-4">
        <x-primary-button>{{ $attendanceRecord ? __('Update Attendance') : __('Create Attendance') }}</x-primary-button>
        <a href="{{ route('attendance.index') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
    </div>
</form> 