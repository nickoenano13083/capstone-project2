@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-50">

    <!-- Sidebar -->
    <div class="w-1/4 bg-white border-r flex flex-col">
        <!-- Search -->
        <div class="p-4 border-b">
            <input type="text" placeholder="Search"
                class="w-full px-3 py-2 border rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
        </div>

        <!-- Chat List -->
        <div class="flex-1 overflow-y-auto">
            @foreach($contacts as $contact)
                <div class="flex items-center p-4 hover:bg-gray-100 cursor-pointer border-b transition">
                    <img src="{{ $contact->avatar }}" alt="{{ $contact->name }}"
                        class="w-10 h-10 rounded-full mr-3">
                    <div class="flex-1">
                        <div class="flex justify-between">
                            <h4 class="font-semibold text-sm">{{ $contact->name }}</h4>
                            <span class="text-xs text-gray-500">{{ $contact->last_message_time }}</span>
                        </div>
                        <p class="text-xs text-gray-500 truncate">{{ $contact->last_message }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Chat Window -->
    <div class="flex-1 flex flex-col">
        <!-- Header -->
        <div class="flex items-center p-4 border-b bg-white shadow-sm">
            <img src="{{ $activeContact->avatar }}" alt="{{ $activeContact->name }}"
                class="w-10 h-10 rounded-full mr-3">
            <div>
                <h4 class="font-semibold">{{ $activeContact->name }}</h4>
                <span class="text-sm text-green-500">Active now</span>
            </div>
        </div>

        <!-- Messages -->
        <div id="chatMessages" class="flex-1 p-6 overflow-y-auto space-y-4">
            @foreach($messages as $message)
                @if($message->sender_id === auth()->id())
                    <!-- My Message -->
                    <div class="flex justify-end">
                        <div class="bg-blue-500 text-white px-4 py-2 rounded-2xl max-w-xs shadow">
                            <p>{{ $message->text }}</p>
                            <span class="block text-xs text-blue-100 mt-1 text-right">{{ $message->created_at->format('g:i A') }}</span>
                        </div>
                    </div>
                @else
                    <!-- Other's Message -->
                    <div class="flex items-start">
                        <img src="{{ $message->sender->avatar }}" class="w-8 h-8 rounded-full mr-2">
                        <div class="bg-gray-100 px-4 py-2 rounded-2xl max-w-xs shadow">
                            <p>{{ $message->text }}</p>
                            <span class="block text-xs text-gray-500 mt-1">{{ $message->created_at->format('g:i A') }}</span>
                        </div>
                    </div>
                @endif

                <!-- File Message -->
                @if($message->file)
                    <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : '' }}">
                        <div class="flex items-center bg-white border px-4 py-2 rounded-lg shadow">
                            <span class="bg-orange-500 text-white px-2 py-1 rounded mr-2 text-xs">
                                {{ strtoupper(pathinfo($message->file, PATHINFO_EXTENSION)) }}
                            </span>
                            <a href="{{ asset('storage/'.$message->file) }}" download class="text-blue-600 underline text-sm">
                                {{ basename($message->file) }}
                            </a>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Input -->
        <div class="p-4 border-t bg-white flex items-center space-x-2">
            <button class="text-gray-500 hover:text-gray-700 text-xl">😊</button>
            <label class="cursor-pointer text-xl">
                📎
                <input type="file" class="hidden" name="file">
            </label>
            <input type="text" placeholder="Type a message"
                class="flex-1 px-4 py-2 border rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
            <button class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-full">
                Send
            </button>
        </div>
    </div>

</div>

<script>
    // Auto-scroll to bottom
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
</script>
@endsection
