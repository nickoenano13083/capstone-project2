<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Check In Member') }}
            </h2>
            <a href="{{ route('members.show', $member) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Member
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="md:flex md:items-start">
                        <div class="md:flex-shrink-0">
                            <div class="h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center text-2xl font-bold text-gray-600">
                                {{ substr($member->first_name, 0, 1) }}{{ substr($member->last_name, 0, 1) }}
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0 md:ml-6">
                            <h3 class="text-lg font-medium text-gray-900">{{ $member->first_name }} {{ $member->last_name }}</h3>
                            <p class="text-sm text-gray-600">{{ $member->email }}</p>
                            @if($member->phone)
                                <p class="text-sm text-gray-600">{{ $member->phone }}</p>
                            @endif
                        </div>
                    </div>

                    <form method="POST" action="{{ route('members.check-in.submit', $member) }}" class="mt-8 space-y-6">
                        @csrf
                        
                        <div class="space-y-4">
                            <!-- Event Selection -->
                            <div>
                                <label for="event_id" class="block text-sm font-medium text-gray-700">Select Event</label>
                                <select id="event_id" name="event_id" required 
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                    <option value="">-- Select an event --</option>
                                    @foreach($events as $event)
                                        <option value="{{ $event->id }}">
                                            {{ $event->title }} - {{ $event->date->format('M d, Y') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('event_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <div class="flex space-x-4">
                                    <div class="flex items-center">
                                        <input id="status-present" name="status" type="radio" value="present" 
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" checked>
                                        <label for="status-present" class="ml-2 block text-sm text-gray-700">
                                            Present
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="status-absent" name="status" type="radio" value="absent" 
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <label for="status-absent" class="ml-2 block text-sm text-gray-700">
                                            Absent
                                        </label>
                                    </div>
                                </div>
                                @error('status')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                                <div class="mt-1">
                                    <textarea id="notes" name="notes" rows="3" 
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border border-gray-300 rounded-md"
                                        placeholder="Any additional notes about this check-in"></textarea>
                                </div>
                                @error('notes')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4 pt-4">
                            <a href="{{ route('members.show', $member) }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Check In Member
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
