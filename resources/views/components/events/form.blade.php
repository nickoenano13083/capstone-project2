@props(['event' => null, 'chapters' => [], 'autoSelectChapter' => null, 'hideChapterSelection' => false])

<div class="w-full">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg w-full">
        <div class="p-6 sm:p-8 bg-white border-b border-gray-200 w-full">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800">
                    {{ $event ? 'Update Event' : 'Create New Event' }}
                </h2>
                <p class="text-gray-500 mt-1">
                    {{ $event ? 'Edit the event details' : 'Fill in the event information' }}
                </p>
            </div>

            <form method="POST" 
                  action="{{ $event ? route('events.update', $event) : route('events.store') }}" 
                  class="space-y-6 w-full"
                  x-data="{ 
                      isSubmitting: false,
                      submitForm() {
                          this.isSubmitting = true;
                          this.$el.submit();
                      }
                  }"
                  @submit.prevent="submitForm">
                @csrf
                @if($event) @method('PUT') @endif

                <!-- Event Details -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 w-full">
                    <div class="space-y-6">
                        <!-- Title -->
                        <div>
                            <x-input-label for="title" :value="__('Event Title')" class="mb-1" />
                            <x-text-input 
                                id="title" 
                                name="title" 
                                type="text" 
                                class="w-full" 
                                :value="old('title', $event?->title)" 
                                placeholder="Enter event title"
                                required 
                                autofocus 
                            />
                            <x-input-error class="mt-1" :messages="$errors->get('title')" />
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Description')" class="mb-1" />
                            <textarea 
                                id="description" 
                                name="description" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                rows="4" 
                                placeholder="Briefly describe the event..."
                                required
                            >{{ old('description', $event?->description) }}</textarea>
                            <x-input-error class="mt-1" :messages="$errors->get('description')" />
                        </div>
                    </div>
                </div>

                <!-- Date & Time -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 w-full">
                    <h3 class="font-medium text-gray-800 mb-4">When & Where</h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Date -->
                        <div>
                            <x-input-label for="date" :value="__('Date')" class="mb-1" />
                            <x-text-input 
                                id="date" 
                                name="date" 
                                type="date" 
                                class="w-full" 
                                :value="old('date', $event?->date?->format('Y-m-d'))" 
                                :min="now()->format('Y-m-d')"
                                required 
                            />
                            <x-input-error class="mt-1" :messages="$errors->get('date')" />
                        </div>

                        <!-- Time -->
                        <div>
                            <x-input-label for="time" :value="__('Time')" class="mb-1" />
                            <x-text-input 
                                id="time" 
                                name="time" 
                                type="time" 
                                class="w-full" 
                                :value="old('time', $event?->time)" 
                                required 
                            />
                            <x-input-error class="mt-1" :messages="$errors->get('time')" />
                        </div>

                        <!-- Location -->
                        <div class="md:col-span-2">
                            <x-input-label for="location" :value="__('Location')" class="mb-1" />
                            <x-text-input 
                                id="location" 
                                name="location" 
                                type="text" 
                                class="w-full" 
                                :value="old('location', $event?->location)" 
                                placeholder="Where will this event take place?"
                                required 
                            />
                            <x-input-error class="mt-1" :messages="$errors->get('location')" />
                        </div>

                        @if(!$hideChapterSelection && $autoSelectChapter)
                            <input type="hidden" name="chapter_id" value="{{ $autoSelectChapter }}">
                        @endif

                        <!-- Status -->
                        <div class="md:col-span-2">
                            <x-input-label for="status" :value="__('Status')" class="mb-1" />
                            <select 
                                id="status" 
                                name="status" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="upcoming" {{ old('status', $event?->status) === 'upcoming' ? 'selected' : '' }}>⏳ Upcoming</option>
                                <option value="ongoing" {{ old('status', $event?->status) === 'ongoing' ? 'selected' : '' }}>▶️ Ongoing</option>
                                <option value="completed" {{ old('status', $event?->status) === 'completed' ? 'selected' : '' }}>✅ Completed</option>
                            </select>
                            <x-input-error class="mt-1" :messages="$errors->get('status')" />
                        </div>
                    </div>
                </div>

                @if(!$hideChapterSelection && !$autoSelectChapter)
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 w-full">
                        <h3 class="font-medium text-gray-800 mb-4">Chapter</h3>
                        <div>
                            <x-input-label for="chapter_id" :value="__('Chapter')" class="mb-1" />
                            <select 
                                id="chapter_id" 
                                name="chapter_id" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required
                            >
                                <option value="" class="text-gray-400">Select a chapter</option>
                                @foreach($chapters as $chapter)
                                    <option 
                                        value="{{ $chapter->id }}" 
                                        {{ old('chapter_id', $event?->chapter_id) == $chapter->id ? 'selected' : '' }}
                                        class="py-3 text-gray-700"
                                    >
                                        {{ $chapter->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-1" :messages="$errors->get('chapter_id')" />
                        </div>
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-2">
                    <a 
                        href="{{ route('events.index') }}" 
                        class="px-4 py-2 text-center text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                    >
                        Cancel
                    </a>
                    <button 
                        type="submit" 
                        class="px-6 py-2 text-center text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 flex items-center justify-center"
                        :disabled="isSubmitting"
                        :class="{'opacity-75 cursor-not-allowed': isSubmitting}"
                    >
                        <span x-show="!isSubmitting">
                            {{ $event ? 'Update Event' : 'Create Event' }}
                        </span>
                        <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-show="isSubmitting">
                            {{ $event ? 'Saving...' : 'Creating...' }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
