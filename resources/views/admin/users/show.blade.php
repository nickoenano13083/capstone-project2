<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Admin Details') }}
            </h2>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i>Back to Users
            </a>
        </div>
    </x-slot>
    
    <div class="dashboard-main-content p-6">
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
        @endif

        <div class="w-full">
            <!-- Member Profile Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-16 w-16">
                            @if($user->profile_photo_path)
                                <img class="h-16 w-16 rounded-full object-cover" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                            @else
                                <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-blue-600 font-medium text-2xl">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="ml-6">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                            <p class="text-gray-600">{{ $user->email }}</p>
                            <div class="mt-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-user mr-1"></i>{{ $user->role }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->email }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email Verified</dt>
                                    <dd class="text-sm text-gray-900">
                                        @if($user->email_verified_at)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check mr-1"></i>Verified
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times mr-1"></i>Unverified
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->phone ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Address</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->address ?? 'Not provided' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Chapter Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Chapter Information</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Current Chapter</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->member?->chapter?->name ?? 'No chapter assigned' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Preferred Chapter</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->preferredChapter?->name ?? 'No preference set' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Member Status</dt>
                                    <dd class="text-sm text-gray-900">
                                        @if($user->member)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check mr-1"></i>{{ $user->member->status ?? 'Active' }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <i class="fas fa-question mr-1"></i>No member record
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Join Date</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->member?->join_date ? \Carbon\Carbon::parse($user->member->join_date)->format('M d, Y') : 'Not available' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Account Information -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Account Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Account Created</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y \a\t g:i A') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->updated_at->format('M d, Y \a\t g:i A') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Last Seen</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->last_seen ? $user->last_seen->diffForHumans() : 'Never' }}</dd>
                                </div>
                            </dl>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">User ID</dt>
                                    <dd class="text-sm text-gray-900 font-mono">{{ $user->id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Member ID</dt>
                                    <dd class="text-sm text-gray-900 font-mono">{{ $user->member?->id ?? 'N/A' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Actions -->
                    @if($user->role === 'Member')
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
                            <div class="flex flex-wrap gap-4">
                                <button onclick="confirmPromote('{{ $user->name }}', {{ $user->id }})" 
                                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <i class="fas fa-arrow-up mr-2"></i>Promote to Admin
                                </button>
                                
                                <a href="mailto:{{ $user->email }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <i class="fas fa-envelope mr-2"></i>Send Email
                                </a>
                                
                                @if($user->member)
                                    <a href="{{ route('members.show', $user->member->id) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                        <i class="fas fa-user mr-2"></i>View Member Profile
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
    </div>

    <!-- Hidden Form for Promotion -->
    <form id="promoteForm" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="role" value="Admin">
    </form>

    <script>
        function confirmPromote(name, userId) {
            if (confirm(`Are you sure you want to promote ${name} to Admin? This will give them full system access.`)) {
                const form = document.getElementById('promoteForm');
                form.action = `/admin/users/${userId}/role?t=${Date.now()}`;
                form.submit();
            }
        }
    </script>
</x-app-layout>
