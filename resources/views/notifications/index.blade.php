<x-app-layout>
    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Notifications</h2>
                            <p class="text-gray-600">Stay updated with the latest events and announcements</p>
                        </div>
                        @if($unreadCount > 0)
                            <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                                    <i class="fas fa-check-double mr-2"></i>
                                    Mark All as Read
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="p-6">
                    @if($notifications->count() > 0)
                        <div class="space-y-4" id="notifications-list">
                            @foreach($notifications as $notification)
                                <div class="notification-item border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200 {{ !$notification->is_read ? 'bg-blue-50 border-blue-200' : '' }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2">
                                                @if(!$notification->is_read)
                                                    <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                                                @endif
                                                <h3 class="text-lg font-semibold text-gray-900">{{ $notification->title }}</h3>
                                                <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                                    {{ ucfirst(str_replace('_', ' ', $notification->type)) }}
                                                </span>
                                            </div>
                                            <p class="text-gray-600 mt-1">{{ $notification->message }}</p>
                                            
                                            @if($notification->data)
                                                <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                                    @if(isset($notification->data['event_title']))
                                                        <div class="text-sm">
                                                            <strong>Event:</strong> {{ $notification->data['event_title'] }}
                                                        </div>
                                                    @endif
                                                    @if(isset($notification->data['event_date']))
                                                        <div class="text-sm">
                                                            <strong>Date:</strong> 
                                                            @php
                                                                try {
                                                                    // Try to parse as Y-m-d format first (new format)
                                                                    $date = \Carbon\Carbon::createFromFormat('Y-m-d', $notification->data['event_date']);
                                                                } catch (\Exception $e) {
                                                                    // Fallback to parse as ISO datetime (old format)
                                                                    $date = \Carbon\Carbon::parse($notification->data['event_date'])->setTimezone('Asia/Manila');
                                                                }
                                                            @endphp
                                                            {{ $date->format('M j, Y') }}
                                                        </div>
                                                    @endif
                                                    @if(isset($notification->data['event_time']))
                                                        <div class="text-sm">
                                                            <strong>Time:</strong> {{ \Carbon\Carbon::parse($notification->data['event_time'])->format('g:i A') }}
                                                        </div>
                                                    @endif
                                                    @if(isset($notification->data['event_location']))
                                                        <div class="text-sm">
                                                            <strong>Location:</strong> {{ $notification->data['event_location'] }}
                                                        </div>
                                                    @endif
                                                    @if(isset($notification->data['admin_name']))
                                                        <div class="text-sm">
                                                            @if($notification->type === 'prayer_request_approved')
                                                                <strong>Approved by:</strong> {{ $notification->data['admin_name'] }}
                                                            @else
                                                                <strong>Created by:</strong> {{ $notification->data['admin_name'] }}
                                                            @endif
                                                        </div>
                                                    @endif
                                                    
                                                    @if(($notification->type === 'event_created' || $notification->type === 'event_updated') && isset($notification->data['event_id']))
                                                        <div class="mt-3">
                                                            <a href="{{ route('events.show', $notification->data['event_id']) }}" 
                                                               class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                                </svg>
                                                                View Event
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                            
                                            <div class="mt-2 text-xs text-gray-500">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                        
                                        @if(!$notification->is_read)
                                            <form action="{{ route('notifications.mark-read', $notification) }}" method="POST" class="ml-4">
                                                @csrf
                                                <button type="submit" 
                                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                    Mark as Read
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($notifications->hasPages())
                            <div class="mt-6">
                                {{ $notifications->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <div class="mx-auto w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                <i class="fas fa-bell text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-1">No notifications</h3>
                            <p class="text-gray-500">You'll receive notifications when new events are created for your chapter.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-refresh unread count every 30 seconds
        setInterval(function() {
            fetch('{{ route("notifications.unread-count") }}')
                .then(response => response.json())
                .then(data => {
                    // Update notification badge if it exists
                    const badge = document.querySelector('.notification-badge');
                    if (badge) {
                        badge.textContent = data.count;
                        badge.style.display = data.count > 0 ? 'inline' : 'none';
                    }
                })
                .catch(error => console.log('Error fetching notification count:', error));
        }, 30000);
    </script>
    @endpush
</x-app-layout>
