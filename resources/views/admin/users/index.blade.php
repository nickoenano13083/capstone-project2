<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Admin & Member Management') }}
            </h2>
            <a href="{{ route('chapters.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <i class="fas fa-arrow-left mr-2"></i>Back to Chapters
            </a>
        </div>
    </x-slot>
    
    <div class="w-full px-4 py-8">
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8" data-refresh="{{ time() }}">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                <i class="fas fa-crown text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">System Admins</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $admins->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <i class="fas fa-users text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Members</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $totalMembers }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <i class="fas fa-project-diagram text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Chapters</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $chapters->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
                <div class="flex-1 min-w-64">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search Members</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="Search by name or email...">
                </div>
                <div class="min-w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Chapter</label>
                    <select name="chapter_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Chapters</option>
                        @foreach($chapters as $chapter)
                            <option value="{{ $chapter->id }}" {{ ($filterChapterId ?? '') == $chapter->id ? 'selected' : '' }}>
                                {{ $chapter->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <i class="fas fa-search mr-2"></i>Search
                    </button>
                </div>
            </form>
        </div>

        <!-- Current Admins Section -->
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="fas fa-crown text-purple-500 mr-2"></i>
                    Current System Admins
                </h3>
            </div>
            <div class="overflow-x-auto">
                @if($admins->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chapter</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Active</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($admins as $admin)
                                <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if($admin->profile_photo_path)
                                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ $admin->profile_photo_url }}" alt="{{ $admin->name }}">
                                @else
                                                    <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                                        <span class="text-purple-600 font-medium text-sm">{{ strtoupper(substr($admin->name, 0, 1)) }}</span>
                                                    </div>
                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $admin->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $admin->email }}</div>
                                            </div>
                                        </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-900">{{ $admin->member?->chapter?->name ?? 'No Chapter' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                        @if($admin->id === auth()->id())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-user mr-1"></i>You
                                            </span>
                                @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <i class="fas fa-crown mr-1"></i>Admin
                                            </span>
                                @endif
                            </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $admin->last_seen ? $admin->last_seen->diffForHumans() : 'Never' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if($admin->id !== auth()->id())
                                            <button onclick="confirmDemote('{{ $admin->name }}', {{ $admin->id }})" 
                                                    class="text-orange-600 hover:text-orange-900 mr-3">
                                                <i class="fas fa-arrow-down mr-1"></i>Demote to Member
                                            </button>
                                                        @else
                                            <span class="text-gray-400">Cannot modify self</span>
                                                        @endif
                                    </td>
                                </tr>
                                                    @endforeach
                        </tbody>
                    </table>
                                                @else
                    <div class="text-center py-12">
                        <i class="fas fa-crown text-gray-300 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Admins Found</h3>
                        <p class="text-gray-500">There are currently no system administrators.</p>
                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        
        <!-- Members Section -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="fas fa-users text-blue-500 mr-2"></i>
                    Members ({{ $totalMembers }} total)
                </h3>
                <p class="text-sm text-gray-500 mt-1">Select members to promote to admin role</p>
                                                            </div>
            <div class="overflow-x-auto">
                @if($members->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chapter</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email Verified</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($members as $member)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if($member->profile_photo_path)
                                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ $member->profile_photo_url }}" alt="{{ $member->name }}">
                                            @else
                                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                        <span class="text-blue-600 font-medium text-sm">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                                                    </div>
                                        @endif
                                                </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $member->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-900">{{ $member->member?->chapter?->name ?? 'No Chapter' }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($member->email_verified_at)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check mr-1"></i>Verified
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times mr-1"></i>Unverified
                                                </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $member->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="confirmPromote('{{ $member->name }}', {{ $member->id }})" 
                                                class="text-green-600 hover:text-green-900 mr-3">
                                            <i class="fas fa-arrow-up mr-1"></i>Promote to Admin
                                        </button>
                                        <a href="{{ route('admin.users.show', $member->id) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye mr-1"></i>View Details
                                        </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-users text-gray-300 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Members Found</h3>
                        <p class="text-gray-500">No members match your current search criteria.</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Pagination -->
        @if($members->hasPages())
            <div class="mt-6">
                {{ $members->withQueryString()->links() }}
        </div>
        @endif
    </div>

    <!-- Hidden Forms for Actions -->
    <form id="promoteForm" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="role" value="Admin">
    </form>

    <form id="demoteForm" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="role" value="Member">
    </form>

    <script>
        function confirmPromote(name, userId) {
            if (confirm(`Are you sure you want to promote ${name} to Admin? This will give them full system access.`)) {
                const form = document.getElementById('promoteForm');
                form.action = `/admin/users/${userId}/role?t=${Date.now()}`;
                form.submit();
            }
        }

        function confirmDemote(name, userId) {
            if (confirm(`Are you sure you want to demote ${name} from Admin to Member? This will remove their admin privileges.`)) {
                const form = document.getElementById('demoteForm');
                form.action = `/admin/users/${userId}/role?t=${Date.now()}`;
                form.submit();
            }
        }
    </script>
</x-app-layout>