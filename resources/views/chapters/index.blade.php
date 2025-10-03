@extends('layouts.app')

@section('content')
    <style>
        .stat-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 12px;
            background: var(--widget-bg-color, #fff);
            color: var(--text-primary, #2d3748);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-left: 4px solid #4f46e5;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            background: rgba(79, 70, 229, 0.1);
            color: #4f46e5;
        }
        .table-hover tbody tr {
            transition: all 0.2s ease;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(79, 70, 229, 0.05) !important;
        }
        .action-btn {
            transition: all 0.2s;
            border-radius: 6px;
            padding: 0.35rem 0.75rem;
            font-size: 0.875rem;
        }
        .action-btn i {
            margin-right: 0.25rem;
        }
        .main-content-card {
            border: none;
            border-radius: 12px;
            background: var(--widget-bg-color, #fff);
            color: var(--text-primary, #2d3748);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .card-header {
            background-color: var(--widget-bg-color, #fff);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.25rem 1.5rem;
        }
        .card-header h5 {
            font-weight: 600;
            color: var(--text-primary, #2d3748);
            margin: 0;
        }
        .search-box {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            transition: all 0.2s;
        }
        .search-box:focus {
            border-color: #a5b4fc;
            box-shadow: 0 0 0 3px rgba(165, 180, 252, 0.2);
        }
        .chapter-leader-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: white;
            background: #4f46e5;
            font-size: 0.875rem;
        }
        .empty-state {
            padding: 3rem 1.5rem;
            text-align: center;
        }
        .empty-state i {
            font-size: 3.5rem;
            color: #a0aec0;
            margin-bottom: 1rem;
            opacity: 0.7;
        }
        .pagination .page-link {
            color: #4f46e5;
            border: 1px solid #e2e8f0;
            margin: 0 3px;
            border-radius: 6px;
        }
        .pagination .page-item.active .page-link {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }
        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
            border-radius: 6px;
        }
    </style>

    <div class="dashboard-main-content p-6">
        <!-- Header Section -->
        <x-page-header :icon="'fas fa-project-diagram'" title="Chapters" subtitle="Manage and organize your organization's chapters">
            <div class="flex items-center justify-between gap-4 flex-wrap">
                <a href="{{ route('chapters.create') }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg text-sm transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i> Add New Chapter
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
                <div class="stat-card p-4">
                    <div class="flex items-center">
                        <div class="stat-icon mr-3">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Chapters</p>
                            <h3 class="text-2xl font-bold">{{ $chapters->total() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="stat-card p-4">
                    <div class="flex items-center">
                        <div class="stat-icon mr-3">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Active Chapters</p>
                            <h3 class="text-2xl font-bold">{{ $chapters->where('status', 'active')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="stat-card p-4">
                    <div class="flex items-center">
                        <div class="stat-icon mr-3">
                            <i class="fas fa-person-check"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Admins</p>
                            <h3 class="text-2xl font-bold">{{ $chapters->whereNotNull('leader_id')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="stat-card p-4">
                    <div class="flex items-center">
                        <div class="stat-icon mr-3">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Need Leaders</p>
                            <h3 class="text-2xl font-bold">{{ $chapters->whereNull('leader_id')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </x-page-header>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button type="button" class="text-green-500 hover:text-green-700" data-dismiss="alert">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Statistics moved into header above -->

        <div class="main-content-card overflow-hidden">
            <div class="card-header">
                <div class="flex justify-between items-center">
                    <h5>Chapter Management</h5>
                    <form action="{{ route('chapters.index') }}" method="GET" class="w-64">
                        <div class="relative">
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ request('search') }}" 
                                class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="Search chapters...">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chapter</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Members</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($chapters as $chapter)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold">
                                            {{ strtoupper(substr($chapter->name, 0, 2)) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $chapter->name }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($chapter->description, 40) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $chapter->location ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $adminUser = null;
                                        if ($chapter->leader) {
                                            // If leader is a User
                                            if ($chapter->leader instanceof \App\Models\User) {
                                                $adminUser = $chapter->leader;
                                            } elseif (method_exists($chapter->leader, 'user')) {
                                                // If leader is a Member with an associated User
                                                $adminUser = $chapter->leader->user;
                                            }
                                        }
                                    @endphp
                                    @if($adminUser)
                                        <div class="flex items-center">
                                            @if(!empty($adminUser->profile_photo_url))
                                                <img src="{{ $adminUser->profile_photo_url }}" alt="{{ $adminUser->name }}" class="w-9 h-9 rounded-full object-cover mr-3">
                                            @else
                                                <div class="chapter-leader-avatar mr-3">
                                                    {{ strtoupper(substr($adminUser->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $adminUser->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $adminUser->email }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-500">No admin assigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                        {{ $chapter->members_count ?? 0 }} Members
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Active
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('chapters.show', $chapter) }}" class="action-btn text-indigo-600 hover:text-indigo-900" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox text-4xl mb-4"></i>
                                        <h3 class="text-lg font-medium text-gray-900 mb-1">No chapters found</h3>
                                        <p class="text-gray-500 mb-4">Get started by adding a new chapter to your organization</p>
                                        <a href="{{ route('chapters.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <i class="fas fa-plus mr-2"></i> Add New Chapter
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($chapters->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $chapters->withQueryString()->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection