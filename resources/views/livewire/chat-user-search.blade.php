<div>
    <input
        type="text"
        wire:model.debounce.300ms="search"
        placeholder="Search users..."
        class="w-full border rounded px-3 py-2"
    />

    @if(strlen($search) > 0)
        <ul class="mt-2">
            @forelse($users as $user)
                <li class="py-1 text-sm text-gray-700">
                    {{ $user->name }}

                    <br>
                    <small class="text-xs text-gray-500">
                        @if($user->isOnline())
                            Online
                        @elseif($user->last_seen)
                            Last seen {{ optional($user->last_seen)->diffForHumans() }}
                        @else
                            Last seen: unknown
                        @endif
                    </small>
                </li>
            @empty
                <li class="py-1 text-sm text-gray-400">No users found.</li>
            @endforelse
        </ul>
    @endif
</div>
