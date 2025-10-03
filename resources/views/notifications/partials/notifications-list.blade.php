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
                        
                        @if(isset($notification->data['prayer_request']))
                            <div class="text-sm">
                                <strong>Prayer Request:</strong> 
                                <div class="mt-1 p-2 bg-white rounded border text-gray-700">
                                    {{ Str::limit($notification->data['prayer_request'], 150) }}
                                </div>
                            </div>
                        @endif
                        
                        @if(isset($notification->data['response']))
                            <div class="text-sm">
                                <strong>Response:</strong> 
                                <div class="mt-1 p-2 bg-green-50 rounded border text-gray-700">
                                    {{ Str::limit($notification->data['response'], 150) }}
                                </div>
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
                        
                        @if($notification->type === 'prayer_request_approved' && isset($notification->data['prayer_request_id']))
                            <div class="mt-3">
                                <a href="{{ route('prayer-requests.show', $notification->data['prayer_request_id']) }}" 
                                   class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    View Prayer Request
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
