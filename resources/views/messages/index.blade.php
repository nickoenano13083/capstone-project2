@php
    // NOTE: This helper function remains unchanged.
    function getFirstLetterAvatar($name) {
        $firstLetter = strtoupper(substr(trim($name), 0, 1));
        $colors = [
            'A' => 'bg-red-500', 'B' => 'bg-blue-500', 'C' => 'bg-green-500', 'D' => 'bg-yellow-500',
            'E' => 'bg-purple-500', 'F' => 'bg-pink-500', 'G' => 'bg-indigo-500', 'H' => 'bg-teal-500',
            'I' => 'bg-orange-500', 'J' => 'bg-red-600', 'K' => 'bg-blue-600', 'L' => 'bg-green-600',
            'M' => 'bg-yellow-600', 'N' => 'bg-purple-600', 'O' => 'bg-pink-600', 'P' => 'bg-indigo-600',
            'Q' => 'bg-teal-600', 'R' => 'bg-orange-600', 'S' => 'bg-red-400', 'T' => 'bg-blue-400',
            'U' => 'bg-green-400', 'V' => 'bg-yellow-400', 'W' => 'bg-purple-400', 'X' => 'bg-pink-400',
            'Y' => 'bg-indigo-400', 'Z' => 'bg-teal-400'
        ];
        $colorClass = $colors[$firstLetter] ?? 'bg-gray-500';
        return (object)[
            'letter' => $firstLetter,
            'color' => $colorClass
        ];
    }
@endphp

@extends('layouts.app')

@section('content')
{{-- ENHANCEMENT: Changed main background to a lighter, cleaner slate color. --}}
<div class="messaging-container flex h-screen bg-slate-50 font-sans">
    
    {{-- Mobile Header: Functionality is unchanged. --}}
    <div class="mobile-header fixed top-0 left-0 right-0 bg-gradient-to-r from-blue-500 to-indigo-600 text-white z-30 md:hidden">
        <div class="flex items-center justify-between p-4">
            <button id="mobile-sidebar-toggle" class="text-white hover:bg-white/20 p-2 rounded-lg transition-colors">
                <i class="fas fa-bars text-lg"></i>
            </button>
            <h1 class="text-lg font-semibold">Messages</h1>
            <button id="mobile-compose-btn" class="text-white hover:bg-white/20 p-2 rounded-lg transition-colors">
                <i class="fas fa-plus text-lg"></i>
            </button>
        </div>
        <div id="mobile-chat-header" class="mobile-chat-header hidden border-t border-white/20 p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button id="mobile-back-btn" class="text-white hover:bg-white/20 p-1 rounded-lg transition-colors">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <img id="mobile-user-avatar" src="" alt="" class="w-8 h-8 rounded-full object-cover border-2 border-white/30">
                    <div>
                        <h3 id="mobile-user-name" class="font-medium text-sm"></h3>
                        <small id="mobile-user-status" class="text-xs text-white/80"></small>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button class="mobile-call-btn text-white hover:bg-white/20 p-2 rounded-lg transition-colors">
                        <i class="fas fa-phone text-sm"></i>
                    </button>
                    <button class="mobile-video-btn text-white hover:bg-white/20 p-2 rounded-lg transition-colors">
                        <i class="fas fa-video text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="mobile-overlay" class="mobile-overlay fixed inset-0 bg-black/50 z-20 hidden md:hidden"></div>

    {{-- Sidebar --}}
    {{-- ENHANCEMENT: Widened the sidebar slightly for better spacing on larger screens. --}}
    <div class="w-full md:w-1/3 lg:w-96 bg-white border-r border-slate-200 flex flex-col">
        {{-- Header --}}
        {{-- ENHANCEMENT: Cleaner header with better spacing and a subtle shadow. --}}
        <div class="p-4 border-b border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-slate-800">Messages</h2>
                <div class="flex items-center gap-2">
                    <span class="text-xs bg-blue-100 text-blue-700 font-semibold px-2 py-1 rounded-full">
                        {{ $onlineUsersCount ?? 0 }} online
                    </span>
                    <button id="compose-btn" class="hidden md:flex items-center justify-center w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors" title="Start new conversation">
                        <i class="fas fa-plus text-sm"></i>
                    </button>
                </div>
            </div>
            
            {{-- ENHANCEMENT: Refined search and filter controls for a cleaner look. --}}
            <div class="space-y-3">
                <div class="relative">
                    <input type="text" id="search-users" placeholder="Search users..." 
                           class="w-full pl-9 pr-3 py-2 bg-slate-100 border border-slate-200 rounded-lg text-slate-700 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400 text-sm"></i>
                </div>
                
                @if(auth()->user()->isAdmin())
                <form id="chapter-filter-form" method="GET" action="{{ route('messages.index') }}">
                    <select id="chapter-filter" name="chapter_id" class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-lg text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <option value="">All Chapters</option>
                        @foreach($chapters as $chapter)
                            <option value="{{ $chapter->id }}" {{ request('chapter_id') == $chapter->id ? 'selected' : '' }}>
                                {{ $chapter->name }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                </form>
                @endif
            </div>
        </div>

        {{-- Users List --}}
        <div class="flex-1 overflow-y-auto" id="users-list">
            @forelse($chatUsers as $user)
            {{-- ENHANCEMENT: Improved padding, added transitions, and made the selected state more prominent. --}}
            <div class="user-item flex items-center gap-4 p-4 border-b border-slate-100 hover:bg-slate-50 cursor-pointer transition-colors duration-200 {{ request('user_id') == $user->id ? 'bg-blue-50' : '' }}" 
                 data-user-id="{{ $user->id }}" 
                 data-user-name="{{ $user->name }}"
                 data-user-chapter="{{ $user->preferred_chapter_id }}">
                <div class="relative flex-shrink-0">
                    @if($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full object-cover">
                    @else
                        @php $avatar = getFirstLetterAvatar($user->name) @endphp
                        <div class="w-12 h-12 rounded-full flex justify-center items-center {{ $avatar->color }}">
                            <span class="text-white text-xl font-bold">{{ $avatar->letter }}</span>
                        </div>
                    @endif
                    <span class="absolute bottom-0 right-0 w-3.5 h-3.5 rounded-full border-2 border-white {{ $user->isOnline() ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-baseline justify-between">
                        <h6 class="font-semibold text-slate-800 truncate">{{ $user->name }}</h6>
                        @if(($user->unread_message_count ?? 0) > 0)
                            <span class="bg-blue-600 text-white text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full ml-2 flex-shrink-0">
                                {{ $user->unread_message_count }}
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-slate-500 truncate mt-1">
                        @if($user->isOnline())
                            <span class="text-green-600">Online</span>
                        @else
                            <span>Offline</span>
                        @endif
                    </p>
                </div>
            </div>
            @empty
            <div class="p-4 text-center text-slate-500">
                <p>No conversations found</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Chat Area --}}
    <div class="flex-1 flex flex-col bg-white">
        {{-- Chat Header --}}
        {{-- ENHANCEMENT: Cleaner header design. --}}
        <div class="p-4 border-b border-slate-200 bg-white flex items-center justify-between" id="chat-header">
            @if(request('user_id'))
                @php $selectedUser = $chatUsers->firstWhere('id', request('user_id')) @endphp
                @if($selectedUser)
                <div class="flex items-center gap-4">
                    @if($selectedUser->avatar_url)
                        <img src="{{ $selectedUser->avatar_url }}" alt="{{ $selectedUser->name }}" class="w-10 h-10 rounded-full object-cover">
                    @else
                        @php $avatar = getFirstLetterAvatar($selectedUser->name) @endphp
                        <div class="w-10 h-10 rounded-full flex justify-center items-center {{ $avatar->color }}">
                            <span class="text-white text-lg font-bold">{{ $avatar->letter }}</span>
                        </div>
                    @endif
                    <div>
                        <h3 class="font-semibold text-slate-800">{{ $selectedUser->name }}</h3>
                        <p class="text-sm text-slate-500">
                            {{ $selectedUser->isOnline() ? 'Online' : 'Offline' }}
                        </p>
                    </div>
                </div>
                @endif
            @else
            <div class="text-center text-slate-500 w-full">
                <p>Select a conversation to start messaging</p>
            </div>
            @endif
        </div>

        {{-- Messages Container --}}
        {{-- ENHANCEMENT: Changed background to match the main container. --}}
        <div class="flex-1 overflow-y-auto p-6 bg-slate-50" id="messages-container">
            @if(request('user_id'))
                <div class="text-center text-slate-500 py-8">
                    <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                    <p>Loading messages...</p>
                </div>
            @else
            <div class="flex items-center justify-center h-full text-slate-400">
                <div class="text-center">
                    <i class="fas fa-comments text-5xl mb-4"></i>
                    <p class="text-lg">Choose a user to start chatting</p>
                </div>
            </div>
            @endif
        </div>

        {{-- Message Input --}}
        {{-- ENHANCEMENT: Cleaner input area with better focus states. --}}
        <div class="p-4 border-t border-slate-200 bg-white" id="message-input-container" style="{{ !request('user_id') ? 'display: none;' : '' }}">
            <form id="message-form" class="flex items-center gap-3">
                @csrf
                <input type="hidden" id="receiver_id" value="{{ request('user_id') }}">
                <input type="text" id="message-content" placeholder="Type a message..." class="flex-1 px-4 py-2 border border-slate-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                <button type="submit" id="send-btn" class="px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-transform hover:scale-110">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Compose Modal --}}
<div id="compose-modal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md max-h-[80vh] flex flex-col">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-800">Start New Conversation</h3>
            <button id="close-compose-modal" class="text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Search Input -->
        <div class="p-4 border-b border-slate-200">
            <div class="relative">
                <input type="text" 
                       id="compose-search-users" 
                       placeholder="Search for users..." 
                       class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
            </div>
        </div>
        
        <!-- Search Results -->
        <div id="compose-search-results" class="flex-1 overflow-y-auto">
            <div class="p-4 text-center text-slate-500">
                <i class="fas fa-search text-2xl mb-2"></i>
                <p>Search for users to start a conversation</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
{{-- NOTE: No changes were made to the JavaScript to preserve functionality. --}}
<script src="https://js.pusher.com/8.0/pusher.min.js"></script>
<script>
class SimpleMessagingApp {
    constructor() {
        this.selectedUserId = {{ request('user_id') ? request('user_id') : 'null' }};
        this.authUserId = {{ auth()->id() }};
        this.pusher = null;
        this.channel = null;
        this.debounceTimeout = null;
    
        this.init();
    }

    init() {
        this.setupPusher();
        this.bindEvents();
        this.initComposeFeatures();
        if (this.selectedUserId) {
            this.loadMessages();
            this.markMessagesAsRead(this.selectedUserId);
        }
    }

    setupPusher() {
        this.pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
            cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
            encrypted: true
        });

        this.channel = this.pusher.subscribe('private-user.' + this.authUserId + '.messages');
        this.channel.bind('App\\Events\\NewMessage', (data) => {
            this.handleNewMessage(data.message);
        });

        this.channel.bind('App\\Events\\UserStatusChanged', (data) => {
            this.updateUserStatus(data.userId, data.isOnline);
        });
    }

    bindEvents() {
        document.querySelectorAll('.user-item').forEach(item => {
            item.addEventListener('click', () => {
                this.selectUser(item.dataset.userId, item.dataset.userName);
            });
        });

        document.getElementById('message-form')?.addEventListener('submit', (e) => {
            e.preventDefault();
            this.sendMessage();
        });

        document.getElementById('search-users')?.addEventListener('input', (e) => {
            this.filterUsers(e.target.value);
        });

        document.getElementById('chapter-filter')?.addEventListener('change', (e) => {
            this.filterUsers();
        });

        document.getElementById('mobile-sidebar-toggle')?.addEventListener('click', () => {
            this.toggleMobileSidebar();
        });

        document.getElementById('mobile-compose-btn')?.addEventListener('click', () => {
            this.openComposeModal();
        });

        document.getElementById('close-compose-modal')?.addEventListener('click', () => {
            this.closeComposeModal();
        });

        document.getElementById('compose-search-users')?.addEventListener('input', (e) => {
            this.debounceSearch(e.target.value);
        });
    }

    initComposeFeatures() {
        // Desktop compose button
        const composeBtn = document.getElementById('compose-btn');
        const mobileComposeBtn = document.getElementById('mobile-compose-btn');
        const closeComposeModal = document.getElementById('close-compose-modal');
        const composeModal = document.getElementById('compose-modal');
        const composeSearchInput = document.getElementById('compose-search-users');

        if (composeBtn) {
            composeBtn.addEventListener('click', () => this.openComposeModal());
        }

        if (mobileComposeBtn) {
            mobileComposeBtn.addEventListener('click', () => {
                this.openComposeModal();
                // Close mobile sidebar if open
                this.closeMobileSidebar();
            });
        }

        if (closeComposeModal) {
            closeComposeModal.addEventListener('click', () => this.closeComposeModal());
        }

        if (composeModal) {
            composeModal.addEventListener('click', (e) => {
                if (e.target === composeModal) {
                    this.closeComposeModal();
                }
            });
        }

        if (composeSearchInput) {
            composeSearchInput.addEventListener('input', (e) => {
                this.debounceSearch(e.target.value);
            });
        }
    }

    openComposeModal() {
        const composeModal = document.getElementById('compose-modal');
        composeModal.classList.remove('hidden');
        composeModal.classList.add('flex');
    }

    closeComposeModal() {
        const composeModal = document.getElementById('compose-modal');
        composeModal.classList.remove('flex');
        composeModal.classList.add('hidden');
        
        // Clear search results
        this.clearSearchResults();
        
        // Clear search input
        const composeSearchInput = document.getElementById('compose-search-users');
        if (composeSearchInput) {
            composeSearchInput.value = '';
        }
    }

    async searchUsers(searchTerm) {
        console.log('Searching for users with term:', searchTerm);
        
        if (!searchTerm.trim()) {
            this.clearSearchResults();
            return;
        }

        try {
            const response = await fetch(`/messages/search-users?q=${encodeURIComponent(searchTerm)}`);
            console.log('Response status:', response.status);
            const responseJson = await response.json();
            console.log('Response JSON:', responseJson);
            this.displaySearchResults(responseJson);
        } catch (error) {
            console.error('Error searching users:', error);
        }
    }

    debounceSearch(searchTerm) {
        clearTimeout(this.debounceTimeout);
        this.debounceTimeout = setTimeout(() => {
            this.searchUsers(searchTerm);
        }, 300);
    }

    displaySearchResults(response) {
        const searchResults = document.getElementById('compose-search-results');
        
        // Handle the new response format
        const users = response.users || [];
        const searchTerm = response.search_term || '';
        const userCount = response.user_count || 0;
        
        console.log('Displaying results:', { searchTerm, userCount, users });
        
        if (users.length === 0) {
            searchResults.innerHTML = `
                <div class="p-4 text-center text-slate-500">
                    <i class="fas fa-user-slash text-2xl mb-2"></i>
                    <p>No users found for "${searchTerm}"</p>
                    <p class="text-sm mt-2">Searched ${userCount} users</p>
                </div>
            `;
            return;
        }

        searchResults.innerHTML = '';
        users.forEach(user => {
            const userItem = this.createComposeUserItem(user);
            searchResults.appendChild(userItem);
        });
    }

    createComposeUserItem(user) {
        const div = document.createElement('div');
        div.className = 'flex items-center gap-3 p-3 hover:bg-slate-50 cursor-pointer transition-colors border-b border-slate-100 last:border-b-0';
        div.dataset.userId = user.id;
        
        const avatarColor = this.getAvatarColor(user.name);
        const firstLetter = this.getFirstLetter(user.name);
        
        div.innerHTML = `
            <div class="relative">
                ${user.avatar 
                    ? `<img src="${user.avatar}" alt="${user.name}" class="w-12 h-12 rounded-full object-cover">`
                    : `<div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-semibold text-lg" style="background-color: ${avatarColor}">
                         ${firstLetter}
                       </div>`
                }
                <div class="absolute bottom-0 right-0 w-3.5 h-3.5 rounded-full border-2 border-white ${user.is_online ? 'bg-green-500' : 'bg-gray-400'}"></div>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <h4 class="font-semibold text-slate-800 truncate">${user.name}</h4>
                    <span class="text-xs text-slate-500">${user.is_online ? 'Online' : 'Offline'}</span>
                </div>
                ${user.chapter ? `<p class="text-sm text-slate-500 truncate">${user.chapter}</p>` : ''}
            </div>
        `;

        div.addEventListener('click', () => this.selectUserFromCompose(user));
        
        return div;
    }

    getAvatarColor(name) {
        const colors = [
            '#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6',
            '#EC4899', '#14B8A6', '#F97316', '#6366F1', '#84CC16'
        ];
        let hash = 0;
        for (let i = 0; i < name.length; i++) {
            hash = name.charCodeAt(i) + ((hash << 5) - hash);
        }
        return colors[Math.abs(hash) % colors.length];
    }

    getFirstLetter(name) {
        return name.charAt(0).toUpperCase();
    }

    selectUserFromCompose(user) {
        this.closeComposeModal();
        this.addUserToConversationList(user);
        this.selectUser(user.id, user.name);
        
        // On mobile, switch to chat view
        if (window.innerWidth < 768) {
            this.showMobileChat();
        }
    }

    addUserToConversationList(user) {
        const userItemsContainer = document.querySelector('.user-items');
        const existingUser = document.querySelector(`[data-user-id="${user.id}"]`);
        
        if (existingUser) {
            // User already exists, just highlight them
            existingUser.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        const userItem = document.createElement('div');
        userItem.className = 'user-item flex items-center gap-3 p-3 hover:bg-slate-50 cursor-pointer transition-colors border-b border-slate-100 last:border-b-0';
        userItem.dataset.userId = user.id;
        userItem.dataset.userName = user.name;
        userItem.dataset.userChapter = user.chapter || '';
        
        const avatarColor = this.getAvatarColor(user.name);
        const firstLetter = this.getFirstLetter(user.name);
        
        userItem.innerHTML = `
            <div class="relative">
                ${user.avatar 
                    ? `<img src="${user.avatar}" alt="${user.name}" class="w-12 h-12 rounded-full object-cover">`
                    : `<div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-semibold text-lg" style="background-color: ${avatarColor}">
                         ${firstLetter}
                       </div>`
                }
                <div class="absolute bottom-0 right-0 w-3.5 h-3.5 rounded-full border-2 border-white ${user.is_online ? 'bg-green-500' : 'bg-gray-400'}"></div>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <h4 class="font-semibold text-slate-800 truncate">${user.name}</h4>
                    <span class="text-xs text-slate-500">${user.is_online ? 'Online' : 'Offline'}</span>
                </div>
                ${user.chapter ? `<p class="text-sm text-slate-500 truncate">${user.chapter}</p>` : ''}
            </div>
        `;

        userItem.addEventListener('click', () => this.selectUser(user.id, user.name));
        
        // Insert at the beginning of the list
        userItemsContainer.insertBefore(userItem, userItemsContainer.firstChild);
        
        // Highlight the newly added user
        userItem.classList.add('bg-blue-50');
        
        // Scroll to the new user
        userItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    clearSearchResults() {
        const searchResults = document.getElementById('compose-search-results');
        searchResults.innerHTML = `
            <div class="p-4 text-center text-slate-500">
                <i class="fas fa-search text-2xl mb-2"></i>
                <p>Search for users to start a conversation</p>
            </div>
        `;
    }

    showMobileChat() {
        const mobileSidebar = document.getElementById('mobile-sidebar');
        const mobileChatArea = document.getElementById('mobile-chat-area');
        
        mobileSidebar.classList.add('-translate-x-full');
        mobileChatArea.classList.remove('translate-x-full');
    }

    closeMobileSidebar() {
        const mobileSidebar = document.getElementById('mobile-sidebar');
        mobileSidebar.classList.remove('translate-x-0');
        mobileSidebar.classList.add('-translate-x-full');
    }

    async loadMessages() {
        if (!this.selectedUserId) return;

        try {
            const response = await fetch(`/messages/${this.selectedUserId}`);
            const messages = await response.json();
            this.displayMessages(messages);
        } catch (error) {
            console.error('Error loading messages:', error);
        }
    }

    displayMessages(messages) {
        const container = document.getElementById('messages-container');
        container.innerHTML = '';

        if (messages.length === 0) {
            container.innerHTML = `
                <div class="text-center text-slate-500 py-8">
                    <p>No messages yet. Start a conversation!</p>
                </div>
            `;
            return;
        }

        messages.forEach(message => {
            const messageEl = this.createMessageElement(message);
            container.appendChild(messageEl);
        });

        container.scrollTop = container.scrollHeight;
    }

    createMessageElement(message) {
        const div = document.createElement('div');
        const isSent = message.sender_id === this.authUserId;
    
        div.className = `flex ${isSent ? 'justify-end' : 'justify-start'} mb-4`;
        div.innerHTML = `
            <div class="max-w-xs lg:max-w-md">
                <div class="${isSent ? 'bg-blue-600 text-white rounded-br-none' : 'bg-white text-slate-800 border border-slate-200 rounded-bl-none'} rounded-xl px-4 py-2 shadow-sm">
                    <p class="text-sm">${message.content}</p>
                </div>
                <p class="text-xs text-slate-500 mt-1 px-1 ${isSent ? 'text-right' : 'text-left'}">
                    ${new Date(message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                </p>
            </div>
      `;
    
        return div;
    }

    async sendMessage() {
        const content = document.getElementById('message-content').value.trim();
        if (!content || !this.selectedUserId) return;

        const sendBtn = document.getElementById('send-btn');
        const originalContent = sendBtn.innerHTML;
    
        sendBtn.disabled = true;
        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        const tempMessage = {
            id: 'temp-' + Date.now(),
            content: content,
            sender_id: this.authUserId,
            receiver_id: this.selectedUserId,
            sender: { id: this.authUserId, name: 'You' },
            created_at: new Date().toISOString(),
            updated_at: new Date().toISOString(),
            is_temp: true
        };

        this.displayTempMessage(tempMessage);

        try {
            const response = await fetch('/messages/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    receiver_id: this.selectedUserId,
                    content: content
                })
            });

            if (response.ok) {
                const serverMessage = await response.json();
                this.replaceTempMessage(tempMessage.id, serverMessage);
                document.getElementById('message-content').value = '';
                document.getElementById('message-content').focus();
            } else {
                this.removeTempMessage(tempMessage.id);
                alert('Failed to send message');
            }
        } catch (error) {
            console.error('Error sending message:', error);
            this.removeTempMessage(tempMessage.id);
            alert('Failed to send message');
        } finally {
            sendBtn.disabled = false;
            sendBtn.innerHTML = originalContent;
        }
    }

    handleNewMessage(message) {
        if (message.sender_id === this.selectedUserId || message.receiver_id === this.selectedUserId) {
            const container = document.getElementById('messages-container');
            const messageEl = this.createMessageElement(message);
            container.appendChild(messageEl);
            container.scrollTop = container.scrollHeight;
        }

        if (message.sender_id !== this.selectedUserId) {
            const userItem = document.querySelector(`[data-user-id="${message.sender_id}"]`);
            if (userItem) {
                const badge = userItem.querySelector('.bg-blue-600');
                if (badge) {
                    const count = parseInt(badge.textContent) + 1;
                    badge.textContent = count;
                } else {
                    const unreadBadge = document.createElement('span');
                    unreadBadge.className = 'bg-blue-600 text-white text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full ml-2 flex-shrink-0';
                    unreadBadge.textContent = '1';
                    userItem.querySelector('.flex-1 .flex').appendChild(unreadBadge);
                }
            }
        }
    }

    updateUserStatus(userId, isOnline) {
        const userItem = document.querySelector(`[data-user-id="${userId}"]`);
        if (userItem) {
            const statusIndicator = userItem.querySelector('.absolute.w-3\\.5');
            const statusText = userItem.querySelector('.text-sm.text-slate-500 span');
        
            if (statusIndicator) {
                statusIndicator.className = `absolute bottom-0 right-0 w-3.5 h-3.5 rounded-full border-2 border-white ${isOnline ? 'bg-green-500' : 'bg-gray-400'}`;
            }
        
            if (statusText) {
                statusText.innerHTML = isOnline ? '<span class="text-green-600">Online</span>' : '<span>Offline</span>';
            }
        }
    }

    updateChatHeader(userId, userName) {
        const header = document.getElementById('chat-header');
        const userItem = document.querySelector(`[data-user-id="${userId}"]`);
    
        if (userItem) {
            const imgElement = userItem.querySelector('img');
            const letterAvatarElement = userItem.querySelector('.rounded-full:not(img)');
            let avatarHtml = '';
        
            if (imgElement) {
                avatarHtml = `<img src="${imgElement.src}" alt="${userName}" class="w-10 h-10 rounded-full object-cover">`;
            } else if (letterAvatarElement) {
                avatarHtml = letterAvatarElement.outerHTML.replace('w-12', 'w-10').replace('h-12', 'h-10');
            }
        
            const isOnline = userItem.querySelector('.bg-green-500') !== null;
        
            header.innerHTML = `
                <div class="flex items-center gap-4">
                    ${avatarHtml}
                    <div>
                        <h3 class="font-semibold text-slate-800">${userName}</h3>
                        <p class="text-sm text-slate-500">${isOnline ? 'Online' : 'Offline'}</p>
                    </div>
                </div>
      `;
        }
    }

    filterUsers(searchTerm = '') {
        const userItems = document.querySelectorAll('.user-item');
        const chapterFilter = document.getElementById('chapter-filter');
        const selectedChapter = chapterFilter ? chapterFilter.value : '';
    
        userItems.forEach(item => {
            const userName = item.getAttribute('data-user-name')?.toLowerCase() || '';
            const userChapter = item.getAttribute('data-user-chapter') || '';
            const matchesSearch = searchTerm === '' || userName.includes(searchTerm.toLowerCase());
            const matchesChapter = selectedChapter === '' || userChapter === selectedChapter;
        
            item.style.display = (matchesSearch && matchesChapter) ? 'flex' : 'none';
        });
    }

    async markMessagesAsRead(userId) {
        try {
            const response = await fetch(`/messages/mark-read/${userId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (response.ok) {
                // Remove the unread message badge from the user item
                const userItem = document.querySelector(`[data-user-id="${userId}"]`);
                if (userItem) {
                    const badge = userItem.querySelector('.bg-blue-600');
                    if (badge) {
                        badge.remove();
                    }
                }
            }
        } catch (error) {
            console.error('Error marking messages as read:', error);
        }
    }

    displayTempMessage(message) {
        const container = document.getElementById('messages-container');
        const messageEl = this.createMessageElement(message);
        messageEl.querySelector('.max-w-xs > div').classList.add('opacity-75', 'animate-pulse');
        container.appendChild(messageEl);
        container.scrollTop = container.scrollHeight;
    }

    replaceTempMessage(tempId, serverMessage) {
        const container = document.getElementById('messages-container');
        const tempMessageEl = container.querySelector(`[data-temp-id="${tempId}"]`);
        if (tempMessageEl) {
            const serverMessageEl = this.createMessageElement(serverMessage);
            tempMessageEl.parentNode.replaceChild(serverMessageEl, tempMessageEl);
        }
    }

    removeTempMessage(tempId) {
        const container = document.getElementById('messages-container');
        const tempMessageEl = container.querySelector(`[data-temp-id="${tempId}"]`);
        if (tempMessageEl) {
            tempMessageEl.remove();
        }
    }

    toggleMobileSidebar() {
        const mobileSidebar = document.getElementById('mobile-sidebar');
        mobileSidebar.classList.toggle('translate-x-0');
        mobileSidebar.classList.toggle('-translate-x-full');
    }

    selectUser(userId, userName) {
        this.selectedUserId = userId;
    
        const url = new URL(window.location);
        url.searchParams.set('user_id', userId);
        window.history.pushState({}, '', url);

        document.getElementById('receiver_id').value = userId;
        document.getElementById('message-input-container').style.display = 'flex';
    
        document.querySelectorAll('.user-item').forEach(item => {
            item.classList.remove('bg-blue-50');
        });
        document.querySelector(`[data-user-id="${userId}"]`)?.classList.add('bg-blue-50');

        this.loadMessages();
        this.updateChatHeader(userId, userName);
        this.markMessagesAsRead(userId);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    window.messagingApp = new SimpleMessagingApp();
});
</script>
@endpush
@endsection