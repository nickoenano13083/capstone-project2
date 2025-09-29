<div class="flex h-screen bg-gray-50" style="font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    <!-- Left Sidebar - Message List -->
    <div class="w-80 bg-white shadow-xl border-r border-gray-200 flex flex-col transform transition-all duration-300 ease-in-out">
        <!-- Header with Gradient -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-6 text-white relative overflow-hidden">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-xl font-bold flex items-center">
                        <i class="fas fa-comments mr-2"></i>
                        Messages
                    </h2>
                    <button class="p-2 rounded-full hover:bg-white/10 transition-colors">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
                
                <!-- Enhanced Search Bar -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-blue-200"></i>
                    </div>
                    <input 
                        wire:model.live.debounce.300ms="searchQuery"
                        wire:keyup="searchUsers"
                        type="text" 
                        placeholder="Search conversations..." 
                        class="w-full pl-10 pr-4 py-2.5 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 text-white placeholder-white/70 transition-all duration-200"
                    >
                    @if($searchQuery)
                        <button 
                            wire:click="$set('searchQuery', '')" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-white/70 hover:text-white transition-colors"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                    @endif
                </div>
            </div>
            <!-- Decorative Elements -->
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full"></div>
            <div class="absolute -bottom-12 -left-8 w-40 h-40 bg-white/5 rounded-full"></div>
        </div>

        <!-- Conversation List -->
        <div class="flex-1 overflow-y-auto custom-scrollbar">
            @if($searchQuery)
                <div class="px-4 py-2 bg-blue-50 text-sm text-blue-700 border-b border-blue-100">
                    <i class="fas fa-search mr-2"></i> Showing results for "{{ $searchQuery }}"
                </div>
            @endif

            @forelse($users as $user)
                <div 
                    wire:click="selectUser({{ $user->id }})"
                    class="group relative flex items-center p-4 hover:bg-gray-50 cursor-pointer border-b border-gray-100 transition-all duration-200 {{ $selectedUserId == $user->id ? 'bg-blue-50 border-l-4 border-l-blue-500' : 'hover:border-l-4 hover:border-l-gray-200' }}"
                >
                    <!-- User Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="text-sm font-semibold text-gray-900 truncate group-hover:text-blue-600 transition-colors">
                                {{ $user->name }}
                                @if($user->online)
                                    <span class="inline-block w-2 h-2 bg-green-500 rounded-full ml-1.5"></span>
                                @endif
                            </h3>
                            @if($user->last_message)
                                <span class="text-xs text-gray-400 ml-2 whitespace-nowrap">
                                    {{ $user->last_message->created_at->diffForHumans() }}
                                </span>
                            @endif
                        </div>
                        
                        <!-- Last Message Preview -->
                        <div class="flex items-center">
                            <p class="text-sm text-gray-500 truncate flex-1">
                                @if($user->last_message)
                                    @if($user->last_message->message_type === 'image')
                                        <i class="fas fa-image mr-1 text-blue-400"></i> Photo
                                    @elseif($user->last_message->message_type === 'file')
                                        <i class="fas fa-paperclip mr-1 text-gray-400"></i> File
                                    @else
                                        {{ Str::limit($user->last_message->content, 35) }}
                                    @endif
                                @else
                                    <span class="text-gray-400">Start a conversation</span>
                                @endif
                            </p>
                            @if($user->unread_count > 0)
                                <span class="ml-2 bg-blue-500 text-white text-xs font-medium rounded-full px-2 py-0.5 min-w-[20px] text-center">
                                    {{ $user->unread_count }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-user-friends text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No conversations found</h3>
                    <p class="text-sm text-gray-500">
                        {{ $searchQuery ? 'Try a different search' : 'Start a new conversation' }}
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Main Chat Area -->
    <div class="flex-1 flex flex-col bg-gray-50">
        @if($selectedUserId)
            <!-- Chat Header -->
            <div class="bg-white shadow-sm border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <x-chat-avatar 
                        :user="$selectedUser" 
                        size="lg" 
                        :showOnlineStatus="true"
                    />
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $selectedUser->name }}</h3>
                        <div class="flex items-center">
                            <span class="inline-block w-2 h-2 rounded-full {{ $selectedUser->online ? 'bg-green-500' : 'bg-gray-400' }} mr-1.5"></span>
                            <span class="text-sm text-gray-500">
                                {{ $selectedUser->online ? 'Online' : 'Offline' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <button class="p-2 text-gray-500 hover:text-blue-600 rounded-full hover:bg-gray-100 transition-colors">
                        <i class="fas fa-phone"></i>
                    </button>
                    <button class="p-2 text-gray-500 hover:text-blue-600 rounded-full hover:bg-gray-100 transition-colors">
                        <i class="fas fa-video"></i>
                    </button>
                    <button class="p-2 text-gray-500 hover:text-blue-600 rounded-full hover:bg-gray-100 transition-colors">
                        <i class="fas fa-info-circle"></i>
                    </button>
                </div>
            </div>

            <!-- Messages Container -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6" id="messages-container">
                @if(count($messages) > 0)
                    @foreach($messages as $message)
                        @php
                            $isOwnMessage = $message->sender_id == auth()->id();
                            $previousMessage = $loop->index > 0 ? $messages[$loop->index - 1] : null;
                            $showDate = $message->shouldShowDateSeparator($previousMessage);
                            $showAvatar = !$isOwnMessage && ($loop->first || $previousMessage->sender_id != $message->sender_id || $showDate);
                        @endphp

                        <!-- Date Separator -->
                        @if($showDate)
                            <div class="flex items-center my-6">
                                <div class="flex-1 border-t border-gray-200"></div>
                                <div class="px-4 py-1 mx-4 text-xs font-medium text-gray-500 bg-gray-100 rounded-full">
                                    {{ $message->created_at->format('F j, Y') }}
                                </div>
                                <div class="flex-1 border-t border-gray-200"></div>
                            </div>
                        @endif

                        <div class="flex {{ $isOwnMessage ? 'justify-end' : 'justify-start' }} items-end space-x-2 group" x-data="{ showActions: false }" @mouseenter="showActions = true" @mouseleave="showActions = false">
                            @if(!$isOwnMessage && $showAvatar)
                                <div class="flex-shrink-0">
                                    <x-chat-avatar 
                                        :user="$message->sender" 
                                        size="sm"
                                        :showOnlineStatus="false"
                                    />
                                </div>
                            @elseif(!$isOwnMessage)
                                <div class="w-8"></div> <!-- Spacer for alignment -->
                            @endif

                            <div class="max-w-xs lg:max-w-md xl:max-w-lg 2xl:max-w-2xl">
                                @if(!$isOwnMessage && $showAvatar)
                                    <div class="text-xs text-gray-500 mb-1 ml-1">
                                        {{ $message->sender->name }}
                                    </div>
                                @endif

                                <div class="relative group">
                                    <!-- Message Bubble -->
                                    <div class="rounded-2xl px-4 py-3 shadow-sm border {{ $isOwnMessage ? 'bg-gradient-to-br from-blue-500 to-blue-600 text-white border-blue-600 rounded-br-md' : 'bg-white text-gray-900 border-gray-200 rounded-bl-md' }} message-bubble" 
                                         style="{{ $isOwnMessage ? 'background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2);' : 'box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.1);' }}">
                                        
                                        @if($message->message_type === 'image')
                                            <div class="mb-2 rounded-lg overflow-hidden border border-gray-200">
                                                <img src="{{ asset('storage/' . $message->attachment_path) }}" 
                                                     alt="{{ $message->attachment_name }}" 
                                                     class="w-full h-auto max-h-64 object-cover cursor-pointer hover:opacity-90 transition-opacity"
                                                     @click="window.open('{{ asset('storage/' . $message->attachment_path) }}', '_blank')">
                                            </div>
                                        @elseif($message->message_type === 'file')
                                            <div class="flex items-center p-3 bg-white/10 rounded-lg mb-2">
                                                <div class="p-2 bg-white/20 rounded-lg mr-3">
                                                    <i class="fas fa-file-alt text-lg"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium truncate">{{ $message->attachment_name }}</p>
                                                    <p class="text-xs opacity-80">{{ $message->getFileSize() }}</p>
                                                </div>
                                                <a href="{{ asset('storage/' . $message->attachment_path) }}" 
                                                   download 
                                                   class="ml-3 p-2 text-white/70 hover:text-white transition-colors">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </div>
                                        @else
                                            <p class="whitespace-pre-wrap break-words">{{ $message->content }}</p>
                                        @endif

                                        <!-- Message Status and Time -->
                                        <div class="flex items-center justify-end mt-1.5 space-x-1.5">
                                            <span class="text-xs {{ $isOwnMessage ? 'text-blue-200' : 'text-gray-400' }} font-medium">
                                                {{ $message->created_at->format('h:i A') }}
                                            </span>
                                            @if($isOwnMessage)
                                                @if($message->read_at)
                                                    <i class="fas fa-check-double text-blue-200 text-xs" title="Read"></i>
                                                @else
                                                    <i class="fas fa-check text-blue-200 text-xs" title="Sent"></i>
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Message Actions -->
                                    <div class="absolute -top-8 right-0 flex items-center bg-white rounded-full shadow-lg border border-gray-200 p-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        <button class="p-1.5 text-gray-500 hover:text-blue-600 rounded-full hover:bg-gray-100 transition-colors"
                                                title="Reply">
                                            <i class="fas fa-reply text-xs"></i>
                                        </button>
                                        <button class="p-1.5 text-gray-500 hover:text-blue-600 rounded-full hover:bg-gray-100 transition-colors"
                                                title="React"
                                                @click="">
                                            <i class="far fa-smile text-xs"></i>
                                        </button>
                                        @if($isOwnMessage)
                                            <button class="p-1.5 text-gray-500 hover:text-red-600 rounded-full hover:bg-red-50 transition-colors"
                                                    title="Delete"
                                                    wire:click="deleteMessage({{ $message->id }})">
                                                <i class="far fa-trash-alt text-xs"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="h-full flex flex-col items-center justify-center text-center p-8">
                        <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-comments text-3xl text-blue-400"></i>
                        </div>
                        <h3 class="text-xl font-medium text-gray-900 mb-2">No messages yet</h3>
                        <p class="text-gray-500 max-w-md">
                            Start the conversation with {{ $selectedUser->name }} by sending your first message.
                        </p>
                    </div>
                @endif
            </div>

            <!-- Typing Indicator -->
            @if($isTyping)
                <div class="px-6 py-2">
                    <div class="flex items-center space-x-1">
                        <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                        <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0.3s"></div>
                        <span class="ml-2 text-sm text-gray-500">{{ $selectedUser->name }} is typing...</span>
                    </div>
                </div>
            @endif

            <!-- Message Input -->
            <div class="bg-white border-t border-gray-200 p-4">
                <!-- File Upload Preview -->
                @if($attachment)
                    <div class="mb-3 p-3 bg-blue-50 rounded-xl border border-blue-100 transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                @if(str_starts_with($attachment->getMimeType(), 'image/'))
                                    <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-blue-500">
                                        <i class="fas fa-image text-xl"></i>
                                    </div>
                                @else
                                    <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-blue-500">
                                        <i class="fas fa-file-alt text-xl"></i>
                                    </div>
                                @endif
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900 truncate max-w-xs">{{ $attachment->getClientOriginalName() }}</p>
                                    <p class="text-xs text-gray-500">{{ number_format($attachment->getSize() / 1024, 1) }} KB</p>
                                </div>
                            </div>
                            <button wire:click="removeAttachment" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif

                <div class="flex items-end space-x-2">
                    <!-- File Upload Button -->
                    <div class="relative">
                        <input 
                            wire:model="attachment" 
                            type="file" 
                            id="file-upload" 
                            class="hidden"
                            wire:change="$refresh">
                        <label for="file-upload" class="flex items-center justify-center w-10 h-10 rounded-full text-gray-500 hover:text-blue-600 hover:bg-blue-50 transition-colors cursor-pointer">
                            <i class="fas fa-paperclip"></i>
                        </label>
                    </div>

                    <!-- Emoji Picker -->
                    <div class="relative">
                        <button type="button" 
                                class="flex items-center justify-center w-10 h-10 rounded-full text-gray-500 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                            <i class="far fa-smile"></i>
                        </button>
                    </div>

                    <!-- Message Input -->
                    <div class="flex-1 relative">
                        <div class="relative">
                            <textarea 
                                wire:model="message" 
                                wire:keydown.enter.prevent="sendMessage"
                                placeholder="Type a message..." 
                                rows="1"
                                class="w-full px-4 py-2.5 pr-12 bg-gray-50 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none overflow-hidden"
                                style="min-height: 44px; max-height: 120px;"
                                x-data="{ resize() { $el.style.height = '44px'; $el.style.height = $el.scrollHeight + 'px'; } }"
                                x-init="resize()"
                                @input="resize()">
                            </textarea>
                            <button 
                                type="button" 
                                class="absolute right-2 bottom-2 p-1.5 text-gray-400 hover:text-blue-600 rounded-full hover:bg-blue-50 transition-colors"
                                wire:click="sendMessage">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- No Chat Selected -->
            <div class="flex-1 flex flex-col items-center justify-center p-8 text-center">
                <div class="w-32 h-32 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-comments text-4xl text-blue-400"></i>
                </div>
                <h3 class="text-2xl font-medium text-gray-900 mb-2">Welcome to Messages</h3>
                <p class="text-gray-500 max-w-md mb-6">
                    Select a conversation or start a new one to begin messaging.
                </p>
                <button class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-full hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-plus mr-2"></i> New Message
                </button>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }

    /* Message Animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .message-enter {
        animation: fadeIn 0.3s ease-out forwards;
    }

    /* Typing Animation */
    @keyframes bounce {
        0%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-6px); }
    }

    .animate-bounce {
        animation: bounce 1.4s infinite ease-in-out;
    }

    /* Message Bubble Triangle */
    .message-bubble::after {
        content: '';
        position: absolute;
        bottom: 0;
        width: 0;
        height: 0;
        border: 8px solid transparent;
    }

    .message-bubble.own::after {
        right: -8px;
        border-left-color: #3b82f6;
        border-right: 0;
        border-bottom: 0;
        margin-bottom: 5px;
    }

    .message-bubble.other::after {
        left: -8px;
        border-right-color: #fff;
        border-left: 0;
        border-bottom: 0;
        margin-bottom: 5px;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-scroll to bottom of messages
    function scrollToBottom() {
        const container = document.getElementById('messages-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        scrollToBottom();
        
        // Listen for new messages
        Livewire.on('messageAdded', () => {
            scrollToBottom();
        });
        
        // Handle file upload preview
        const fileInput = document.getElementById('file-upload');
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Show loading state
                    const submitButton = document.querySelector('button[wire\:click="sendMessage"]');
                    if (submitButton) {
                        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                        submitButton.disabled = true;
                    }
                }
            });
        }
    });
</script>
@endpush
