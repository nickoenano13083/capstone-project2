@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <x-page-header :icon="'fas fa-clipboard-list'" title="Activity Log" subtitle="Audit trail across chapters with filters and details">
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.activity-log') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            <i class="fas fa-rotate-right mr-2"></i>
                            Refresh
                        </a>
                        <button type="button" onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <i class="fas fa-print mr-2"></i>
                            Print
                        </button>
                    </div>
                </x-page-header>

                <!-- Summary / Count -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
                    <p class="text-sm text-gray-600 mb-2 sm:mb-0">
                        Showing <span class="font-medium">{{ $logs->firstItem() ?? 0 }}</span> to
                        <span class="font-medium">{{ $logs->lastItem() ?? 0 }}</span> of
                        <span class="font-medium">{{ $logs->total() }}</span> log entries
                    </p>
                    <div class="text-xs text-slate-500">
                        Quick dates:
                        <button type="button" class="ml-1 px-2 py-1 rounded border hover:bg-gray-50" onclick="quickDate('today')">Today</button>
                        <button type="button" class="ml-1 px-2 py-1 rounded border hover:bg-gray-50" onclick="quickDate('this_week')">This Week</button>
                        <button type="button" class="ml-1 px-2 py-1 rounded border hover:bg-gray-50" onclick="quickDate('this_month')">This Month</button>
                        <button type="button" class="ml-1 px-2 py-1 rounded border hover:bg-gray-50" onclick="quickDate('last_7')">Last 7d</button>
                        <button type="button" class="ml-1 px-2 py-1 rounded border hover:bg-gray-50" onclick="quickDate('last_30')">Last 30d</button>
                    </div>
                </div>

                <!-- Active Filters Chips -->
                @php $active = array_filter($filters ?? []); @endphp
                @if(!empty($active))
                <div class="mb-4">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-sm font-medium text-gray-700">Active Filters:</span>
                        @foreach($active as $key => $value)
                            @if(strlen((string)$value))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst(str_replace('_',' ', $key)) }}: {{ is_array($value) ? implode(',', $value) : $value }}
                                    <a href="{{ route('admin.activity-log', array_diff_key($filters, [$key => null])) }}" class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full bg-blue-200 text-blue-800 hover:bg-blue-300">
                                        <span class="sr-only">Remove filter</span>
                                        <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 8 8"><path fill-rule="evenodd" d="M4 3.293l2.146-2.147a.5.5 0 011.708.708L4.707 4l2.147 2.146a.5.5 0 01-.708.708L4 4.707l-2.146 2.147a.5.5 0 01-.708-.708L3.293 4 1.146 1.854a.5.5 0 01.708-.708L4 3.293z" clip-rule="evenodd"/></svg>
                                    </a>
                                </span>
                            @endif
                        @endforeach
                        <a href="{{ route('admin.activity-log') }}" class="ml-1 text-sm text-blue-600 hover:text-blue-800 hover:underline">Clear all</a>
                    </div>
                </div>
                @endif

                <!-- Filters Toggle -->
                <div class="mb-4">
                    <button id="filtersToggle" class="flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 focus:outline-none">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        <span>Filters</span>
                        <svg id="filtersChevron" class="w-4 h-4 ml-1 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                </div>

                <!-- Filters Panel (copied style) -->
                <div id="filtersPanel" class="hidden mb-6 p-4 bg-gray-200 rounded-lg">
                    <form action="{{ route('admin.activity-log') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Action</label>
                            <select name="action" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All</option>
                                @foreach($availableActions as $action)
                                    <option value="{{ $action }}" {{ ($filters['action'] ?? '') === $action ? 'selected' : '' }}>{{ $action }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">User</label>
                            <select name="user_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ ($filters['user_id'] ?? '') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Chapter</label>
                            <select name="chapter_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All</option>
                                @foreach($chapters as $chapter)
                                    <option value="{{ $chapter->id }}" {{ ($filters['chapter_id'] ?? '') == $chapter->id ? 'selected' : '' }}>{{ $chapter->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">From</label>
                            <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">To</label>
                            <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        </div>
                        <div class="flex items-end space-x-3 md:col-span-2 pt-1">
                            <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-blue-600 text-white font-medium text-sm rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <i class="fas fa-filter mr-2"></i>
                                Apply Filters
                            </button>
                            <a href="{{ route('admin.activity-log') }}" class="inline-flex items-center px-4 py-2.5 bg-gray-100 text-gray-700 font-medium text-sm rounded-lg shadow-sm hover:bg-gray-50">
                                <i class="fas fa-rotate-left mr-2"></i>
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="flex flex-col h-[calc(100vh-350px)]">
                    <div class="overflow-x-auto flex-1">
                        <div class="min-w-full inline-block align-middle">
                            <div class="overflow-hidden border border-gray-200 rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50 sticky top-0 z-10">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">When</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chapter</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP</th>
                                            <th class="px-6 py-3"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100">
                                        @forelse($logs as $log)
                                            @php
                                                $action = $log->action;
                                                $actionColor = match(true) {
                                                    str_starts_with($action, 'login_') => 'bg-emerald-100 text-emerald-700',
                                                    str_starts_with($action, 'event_') => 'bg-indigo-100 text-indigo-700',
                                                    str_starts_with($action, 'member_') => 'bg-blue-100 text-blue-700',
                                                    str_starts_with($action, 'prayer_request_') => 'bg-teal-100 text-teal-700',
                                                    str_starts_with($action, 'bible_verse_') => 'bg-amber-100 text-amber-800',
                                                    default => 'bg-slate-100 text-slate-700',
                                                };
                                                $userName = $log->user->name ?? '—';
                                                $initials = collect(explode(' ', $userName))->map(fn($p) => mb_substr($p, 0, 1))->join('');
                                            @endphp
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    <div class="flex items-center gap-3">
                                                        <div class="h-9 w-9 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center text-xs font-semibold">{{ $initials }}</div>
                                                        <div class="leading-tight">
                                                            <div class="font-medium">{{ $userName }}</div>
                                                            <div class="text-xs text-slate-500">ID: {{ $log->user_id ?? '—' }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-sm">
                                                    @php($chapterName = $log->metadata['chapter_name'] ?? null)
                                                    @php($chapterId = $log->metadata['chapter_id'] ?? null)
                                                    @if($chapterName || $chapterId)
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs bg-slate-100 text-slate-700">
                                                            <i class="fas fa-church text-slate-500"></i>
                                                            {{ $chapterName ?? ('Chapter #' . $chapterId) }}
                                                        </span>
                                                    @else
                                                        <span class="text-slate-400">—</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-sm">
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $actionColor }}">{{ $action }}</span>
                                                </td>
                                                <td class="px-6 py-4 text-sm max-w-[28rem]">
                                                    <div class="text-slate-800 truncate" title="{{ $log->description }}">{{ $log->description }}</div>
                                                    @if(!empty($log->metadata))
                                                        <button type="button" class="mt-1 text-xs text-blue-600 hover:text-blue-800" onclick="showLogDetails({{ $log->id }})">View details</button>
                                                        <pre id="log-metadata-{{ $log->id }}" class="hidden">{!! htmlentities(json_encode($log->metadata, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES), ENT_QUOTES, 'UTF-8') !!}</pre>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-sm">
                                                    <span class="text-slate-700" title="{{ $log->user_agent }}">{{ $log->ip_address ?? '—' }}</span>
                                                </td>
                                                <td class="px-6 py-4 text-right text-sm">
                                                    <button type="button" class="px-2.5 py-1.5 rounded-md border border-gray-200 text-slate-600 hover:bg-gray-50" onclick="copyRow({{ $log->id }})" title="Copy row JSON">
                                                        <i class="fas fa-copy text-xs"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="px-6 py-12 text-center">
                                                    <div class="flex flex-col items-center">
                                                        <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                                            <i class="fas fa-file-circle-xmark"></i>
                                                        </div>
                                                        <p class="mt-3 text-slate-600 font-medium">No activity found</p>
                                                        <p class="text-sm text-slate-500">Try adjusting filters or reset to see all records.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 sticky bottom-0 bg-white pt-2 border-t border-gray-200">
                        <div class="pagination-container">
                            {{ $logs->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div id="logDetailsModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center p-4 z-50">
    <div class="bg-white w-full max-w-2xl rounded-lg shadow-xl overflow-hidden">
        <div class="px-5 py-3 border-b flex items-center justify-between">
            <h3 class="font-semibold text-slate-800">Log Details</h3>
            <button class="text-slate-500 hover:text-slate-700" onclick="closeDetails()"><i class="fas fa-times"></i></button>
        </div>
        <div class="p-5 space-y-4">
            <div>
                <div class="text-xs uppercase tracking-wide text-slate-400">Description</div>
                <div id="detailsDescription" class="mt-1 text-slate-800 text-sm break-words"></div>
            </div>
            <div>
                <div class="text-xs uppercase tracking-wide text-slate-400">Metadata</div>
                <pre id="detailsMetadata" class="mt-2 bg-slate-50 rounded-md p-3 text-xs text-slate-800 overflow-auto max-h-80"></pre>
            </div>
        </div>
        <div class="px-5 py-3 border-t flex items-center justify-end gap-2">
            <button class="px-3 py-1.5 rounded-md border border-gray-200 text-slate-700 hover:bg-gray-50" onclick="copyDetails()"><i class="fas fa-copy mr-1"></i>Copy</button>
            <button class="px-3 py-1.5 rounded-md bg-blue-600 text-white hover:bg-blue-700" onclick="closeDetails()">Close</button>
        </div>
    </div>
</div>

<script>
    // Filters panel toggle behavior copied from members design
    document.addEventListener('DOMContentLoaded', function() {
        const filtersToggle = document.getElementById('filtersToggle');
        const filtersPanel = document.getElementById('filtersPanel');
        const filtersChevron = document.getElementById('filtersChevron');
        const state = localStorage.getItem('activityLogFiltersState');
        if (state === 'expanded') {
            filtersPanel.classList.remove('hidden');
            filtersChevron.classList.add('rotate-180');
        }
        filtersToggle.addEventListener('click', function() {
            filtersPanel.classList.toggle('hidden');
            filtersChevron.classList.toggle('rotate-180');
            localStorage.setItem('activityLogFiltersState', filtersPanel.classList.contains('hidden') ? 'collapsed' : 'expanded');
        });
    });

    function pad(n){return n<10? '0'+n:n}
    function quickDate(preset){
        const from = document.querySelector('input[name="date_from"]');
        const to = document.querySelector('input[name="date_to"]');
        const now = new Date();
        let start = new Date(now);
        let end = new Date(now);
        if (preset === 'today') {
            // start and end are today
        } else if (preset === 'this_week') {
            const day = now.getDay();
            const diffToMonday = (day === 0 ? 6 : day - 1);
            start.setDate(now.getDate() - diffToMonday);
            end = new Date(start); end.setDate(start.getDate() + 6);
        } else if (preset === 'this_month') {
            start = new Date(now.getFullYear(), now.getMonth(), 1);
            end = new Date(now.getFullYear(), now.getMonth() + 1, 0);
        } else if (preset === 'last_7') {
            start.setDate(now.getDate() - 6);
        } else if (preset === 'last_30') {
            start.setDate(now.getDate() - 29);
        }
        const fmt = (d) => `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`;
        from.value = fmt(start);
        to.value = fmt(end);
    }

    function showLogDetails(id){
        const modal = document.getElementById('logDetailsModal');
        const metaEl = document.getElementById(`log-metadata-${id}`);
        const row = metaEl?.closest('tr');
        const desc = row?.querySelector('td:nth-child(5) .text-slate-800')?.textContent?.trim() || '';
        document.getElementById('detailsDescription').textContent = desc;
        document.getElementById('detailsMetadata').textContent = metaEl ? metaEl.textContent : '{}';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDetails(){
        const modal = document.getElementById('logDetailsModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function copyDetails(){
        const text = document.getElementById('detailsMetadata').textContent;
        navigator.clipboard.writeText(text);
    }

    function copyRow(id){
        const metaEl = document.getElementById(`log-metadata-${id}`);
        const row = metaEl?.closest('tr');
        const cells = row?.querySelectorAll('td');
        const payload = {
            when: cells?.[0]?.innerText?.trim(),
            user: cells?.[1]?.innerText?.split('\n')[0]?.trim(),
            chapter: cells?.[2]?.innerText?.trim(),
            action: cells?.[3]?.innerText?.trim(),
            description: row?.querySelector('td:nth-child(5) .text-slate-800')?.textContent?.trim(),
            ip: cells?.[5]?.innerText?.trim(),
            metadata: metaEl ? JSON.parse(metaEl.textContent) : {}
        };
        navigator.clipboard.writeText(JSON.stringify(payload, null, 2));
    }
</script>
@endsection
