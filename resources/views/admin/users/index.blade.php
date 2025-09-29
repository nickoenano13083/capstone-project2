<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registered Users') }}
        </h2>
    </x-slot>
    <div class="w-full px-4 py-8">
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
        @endif

        @if(in_array(auth()->user()->role, ['Admin','Leader']))
        <!-- Create Member User -->
        <div class="mb-6 bg-white shadow rounded-lg p-4">
            <h3 class="text-lg font-medium text-gray-800 mb-3">Create Member User</h3>
            <form action="{{ route('admin.users.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Name</label>
                    <input type="text" name="name" class="w-full rounded border-gray-300" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Email</label>
                    <input type="email" name="email" class="w-full rounded border-gray-300" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Password</label>
                    <input type="password" name="password" class="w-full rounded border-gray-300" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Chapter</label>
                    <select name="preferred_chapter_id" class="w-full rounded border-gray-300" @if(auth()->user()->role==='Leader') required @endif>
                        <option value="">— Select —</option>
                        @foreach($chapters as $chapter)
                            @if(auth()->user()->role==='Admin' || (auth()->user()->role==='Leader' && auth()->user()->ledChapters->pluck('id')->contains($chapter->id)))
                                <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Phone (optional)</label>
                    <input type="text" name="phone" class="w-full rounded border-gray-300">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Address (optional)</label>
                    <input type="text" name="address" class="w-full rounded border-gray-300">
                </div>
                <div class="md:col-span-2 flex items-center gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Create Member</button>
                    <span class="text-xs text-gray-500">A corresponding record will be created in Members table.</span>
                </div>
            </form>
            @if ($errors->any())
                <div class="mt-3 p-3 bg-red-50 text-red-700 rounded text-sm">
                    <ul class="list-disc ml-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        @endif

        <!-- Filters -->
        <form action="{{ route('admin.users.index') }}" method="GET" class="mb-4 flex items-center gap-2">
            <label class="text-sm text-gray-600">Filter by Chapter:</label>
            <select name="chapter_id" class="rounded border-gray-300">
                <option value="">All</option>
                @foreach($chapters as $chapter)
                    <option value="{{ $chapter->id }}" {{ ($filterChapterId ?? '') == $chapter->id ? 'selected' : '' }}>{{ $chapter->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-3 py-2 bg-gray-800 text-white rounded">Apply</button>
        </form>

        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chapter</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email Verified</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered At</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(isset($chapters) && $chapters->count() && $user->member)
                                    <form action="{{ route('admin.users.updateChapter', $user->id) }}" method="POST">
                                        @csrf
                                        <select name="chapter_id" class="rounded border-gray-300" onchange="this.form.submit()">
                                            <option value="">—</option>
                                            @foreach($chapters as $chapter)
                                                <option value="{{ $chapter->id }}" @if($user->member?->chapter_id === $chapter->id) selected @endif>{{ $chapter->name }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                @else
                                    {{ $user->member?->chapter?->name ?? '—' }}
                                @endif
                                
                                @if($user->role === 'Leader')
                                    @php
                                        $ledChapters = \App\Models\Chapter::where('leader_id', $user->id)->where('leader_type', 'App\\Models\\User')->get();
                                    @endphp
                                    @if($ledChapters->count() > 0)
                                        <div class="mt-1 text-xs text-blue-600">
                                            <strong>Leads:</strong> 
                                            @foreach($ledChapters as $chapter)
                                                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded mr-1 mb-1">
                                                    {{ $chapter->name }}
                                                    <form action="{{ route('admin.users.removeLeader', $user->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="chapter_id" value="{{ $chapter->id }}">
                                                        <button type="submit" class="ml-1 text-red-600 hover:text-red-800" onclick="return confirm('Remove leadership of {{ $chapter->name }}?')">×</button>
                                                    </form>
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    <select name="role" class="rounded border-gray-300" @if(auth()->id() === $user->id) disabled @endif onchange="this.form.submit()">
                                        <option value="Admin" @if($user->role === 'Admin') selected @endif>Admin</option>
                                        <option value="Leader" @if($user->role === 'Leader') selected @endif>Leader</option>
                                        <option value="Member" @if($user->role === 'Member') selected @endif>Member</option>
                                    </select>
                                    @if(auth()->id() === $user->id)
                                        <span class="text-xs text-gray-400">(You)</span>
                                    @endif
                                </form>
                                
                                @if($user->role === 'Leader')
                                    <div class="mt-1 text-xs text-green-600">
                                        <strong>✓ Leader assigned</strong>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->email_verified_at)
                                    <span class="text-green-600 font-semibold">Yes</span>
                                @else
                                    <span class="text-red-600 font-semibold">No</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        <tr>
                            <td colspan="7" class="px-6 pb-6">
                                @if(auth()->user()->role === 'Admin')
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <h4 class="text-sm font-medium text-gray-700 mb-3">Leader Management</h4>
                                        
                                        <!-- Quick Leader Assignment -->
                                        <div class="flex flex-wrap items-center gap-4 mb-3">
                                            <form action="{{ route('admin.users.assignLeaderAndRole', $user->id) }}" method="POST" class="flex items-center gap-2">
                                                @csrf
                                                <label class="text-xs text-gray-600 font-medium">Make User Leader of:</label>
                                                <select name="leader_chapter_id" class="rounded border-gray-300 text-sm" required>
                                                    <option value="">Select Chapter</option>
                                                    @foreach($chapters as $chapter)
                                                        @if(!$chapter->leader_id || $chapter->leader_type !== 'App\\Models\\User')
                                                            <option value="{{ $chapter->id }}">{{ $chapter->name }} (Available)</option>
                                                        @else
                                                            <option value="{{ $chapter->id }}" disabled>{{ $chapter->name }} (Has Leader)</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700 font-medium">
                                                    Promote to Leader
                                                </button>
                                            </form>
                                            
                                            <div class="flex items-center gap-2">
                                                @if($user->role === 'Leader')
                                                    <span class="text-xs text-green-600 font-medium">
                                                        ✓ User is a Leader
                                                    </span>
                                                @elseif($user->role === 'Admin')
                                                    <span class="text-xs text-purple-600 font-medium">
                                                        👑 User is an Admin
                                                    </span>
                                                @else
                                                    <span class="text-xs text-gray-500">
                                                        User is a Member
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Current Leadership Status -->
                                        @if($user->role === 'Leader')
                                            @php
                                                $ledChapters = \App\Models\Chapter::where('leader_id', $user->id)->where('leader_type', 'App\\Models\\User')->get();
                                            @endphp
                                            @if($ledChapters->count() > 0)
                                                <div class="border-t pt-3">
                                                    <div class="text-xs text-gray-600 font-medium mb-2">Currently Leading:</div>
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($ledChapters as $chapter)
                                                            <div class="inline-flex items-center bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs">
                                                                <span class="mr-2">{{ $chapter->name }}</span>
                                                                <form action="{{ route('admin.users.removeLeader', $user->id) }}" method="POST" class="inline">
                                                                    @csrf
                                                                    <input type="hidden" name="chapter_id" value="{{ $chapter->id }}">
                                                                    <button type="submit" class="text-red-600 hover:text-red-800 font-bold" 
                                                                            onclick="return confirm('Remove {{ $user->name }} as leader of {{ $chapter->name }}?')">
                                                                        ×
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @else
                                                <div class="border-t pt-3">
                                                    <div class="text-xs text-orange-600 font-medium">
                                                        ⚠️ Leader but not assigned to any chapter
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                        
                                        <!-- Available Chapters Info -->
                                        @php
                                            $availableChapters = $chapters->filter(function($chapter) {
                                                return !$chapter->leader_id || $chapter->leader_type !== 'App\\Models\\User';
                                            });
                                        @endphp
                                        @if($availableChapters->count() > 0)
                                            <div class="border-t pt-3 mt-3">
                                                <div class="text-xs text-gray-600 font-medium mb-2">Available Chapters:</div>
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($availableChapters as $chapter)
                                                        <span class="inline-block bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">
                                                            {{ $chapter->name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @else
                                            <div class="border-t pt-3 mt-3">
                                                <div class="text-xs text-red-600 font-medium">
                                                    ⚠️ No available chapters for leadership assignment
                                                </div>
                                            </div>
                                        <!-- Quick Actions -->
                                        <div class="flex flex-wrap items-center gap-2 mt-3">
                                            @if($user->role === 'Leader')
                                                <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="role" value="Member">
                                                    <button type="submit" class="px-2 py-1 bg-orange-600 text-white rounded text-xs hover:bg-orange-700" 
                                                            onclick="return confirm('Demote {{ $user->name }} from Leader to Member?')">
                                                        Demote to Member
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            @if($user->role === 'Member')
                                                <span class="text-xs text-gray-400">
                                                    Use "Promote to Leader" above to make this user a leader
                                                </span>
                                                                                         @endif
                                         </div>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>