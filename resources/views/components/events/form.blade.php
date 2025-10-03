@props(['event' => null, 'chapters' => [], 'autoSelectChapter' => null, 'hideChapterSelection' => false])

<div class="w-full">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl w-full border border-gray-100">
        <div class="p-8 sm:p-10 bg-gradient-to-br from-white to-gray-50 w-full">
            <div class="mb-10">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900">
                            {{ $event ? 'Update Event' : 'Create New Event' }}
                        </h2>
                        <p class="text-gray-600 mt-1 text-lg">
                            {{ $event ? 'Edit the event details below' : 'Fill in the event information to get started' }}
                        </p>
                    </div>
                </div>
            </div>

            <form method="POST" 
                  action="{{ $event ? route('events.update', $event) : route('events.store') }}" 
                  class="space-y-6 w-full"
                  enctype="multipart/form-data"
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
                <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 w-full hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center mb-6">
                        <div class="p-2 bg-indigo-100 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">Event Details</h3>
                    </div>
                    <div class="space-y-8">
                        <!-- Title -->
                        <div class="space-y-2">
                            <x-input-label for="title" :value="__('Event Title')" class="text-sm font-medium text-gray-700" />
                            <div class="relative">
                                <x-text-input 
                                    id="title" 
                                    name="title" 
                                    type="text" 
                                    class="w-full pl-4 pr-4 py-3 text-lg border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-200" 
                                    :value="old('title', $event?->title)" 
                                    placeholder="Enter a compelling event title..."
                                    required 
                                    autofocus 
                                />
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                            </div>
                            <x-input-error class="mt-1" :messages="$errors->get('title')" />
                        </div>

                        <!-- Description -->
                        <div class="space-y-2">
                            <x-input-label for="description" :value="__('Description')" class="text-sm font-medium text-gray-700" />
                            <div class="relative">
                                <textarea 
                                    id="description" 
                                    name="description" 
                                    class="w-full pl-4 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-200 resize-none" 
                                    rows="5" 
                                    placeholder="Provide a detailed description of your event, including what attendees can expect..."
                                    required
                                >{{ old('description', $event?->description) }}</textarea>
                            </div>
                            <x-input-error class="mt-1" :messages="$errors->get('description')" />
                        </div>

                        <!-- Event Image -->
                        <div class="space-y-2">
                            <x-input-label for="image" :value="__('Event Image')" class="text-sm font-medium text-gray-700" />
                            <div class="relative">
                                <input 
                                    id="image" 
                                    name="image" 
                                    type="file" 
                                    accept="image/*"
                                    class="w-full pl-4 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" 
                                />
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            @if($event && $event->image && file_exists(storage_path('app/public/' . $event->image)))
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600 mb-2">Current image:</p>
                                    <img src="{{ asset('storage/' . $event->image) }}" alt="Current event image" class="w-32 h-32 object-cover rounded-lg border border-gray-200">
                                </div>
                            @endif
                            <p class="text-xs text-gray-500">Upload an image for this event (JPEG, PNG, JPG, GIF - Max 5MB)</p>
                            <x-input-error class="mt-1" :messages="$errors->get('image')" />
                        </div>
                    </div>
                </div>

                <!-- Date & Time -->
                <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 w-full hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center mb-6">
                        <div class="p-2 bg-green-100 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">When & Where</h3>
                    </div>
                    <div class="grid md:grid-cols-2 gap-8">
                        <!-- Date -->
                        <div class="space-y-2">
                            <x-input-label for="date" :value="__('Event Date')" class="text-sm font-medium text-gray-700" />
                            <div class="relative">
                                <x-text-input 
                                    id="date" 
                                    name="date" 
                                    type="date" 
                                    class="w-full pl-4 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-200" 
                                    :value="old('date', $event?->date?->format('Y-m-d'))" 
                                    :min="now()->format('Y-m-d')"
                                    required 
                                />
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <x-input-error class="mt-1" :messages="$errors->get('date')" />
                        </div>

                        <!-- Time -->
                        <div class="space-y-2">
                            <x-input-label for="time" :value="__('Event Time')" class="text-sm font-medium text-gray-700" />
                            <div class="relative">
                                <x-text-input 
                                    id="time" 
                                    name="time" 
                                    type="time" 
                                    class="w-full pl-4 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-200" 
                                    :value="old('time', $event?->time)" 
                                    required 
                                />
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <x-input-error class="mt-1" :messages="$errors->get('time')" />
                        </div>

                        <!-- End Time -->
                        <div class="space-y-2">
                            <x-input-label for="end_time" :value="__('End Time')" class="text-sm font-medium text-gray-700" />
                            <div class="relative">
                                <x-text-input 
                                    id="end_time" 
                                    name="end_time" 
                                    type="time" 
                                    class="w-full pl-4 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-200" 
                                    :value="old('end_time', $event?->end_time)" 
                                />
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500">Leave empty for all-day events.</p>
                            <x-input-error class="mt-1" :messages="$errors->get('end_time')" />
                        </div>

                        <!-- Location -->
                        <div class="md:col-span-2 space-y-2">
                            <x-input-label for="location" :value="__('Event Location')" class="text-sm font-medium text-gray-700" />
                            <div class="relative">
                                <x-text-input 
                                    id="location" 
                                    name="location" 
                                    type="text" 
                                    class="w-full pl-4 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-200" 
                                    :value="old('location', $event?->location)" 
                                    placeholder="Enter the venue or location for this event..."
                                    required 
                                />
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <x-input-error class="mt-1" :messages="$errors->get('location')" />
                        </div>

                        @if(!$hideChapterSelection && $autoSelectChapter)
                            <input type="hidden" name="chapter_id" value="{{ $autoSelectChapter }}">
                        @endif

                        <!-- Status -->
                        <div class="md:col-span-2 space-y-2">
                            <x-input-label for="status" :value="__('Event Status')" class="text-sm font-medium text-gray-700" />
                            <div class="relative">
                                <select 
                                    id="status" 
                                    name="status" 
                                    class="w-full pl-4 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-200 appearance-none bg-white"
                                >
                                    <option value="upcoming" {{ old('status', $event?->status) === 'upcoming' ? 'selected' : '' }}>⏳ Upcoming</option>
                                    <option value="ongoing" {{ old('status', $event?->status) === 'ongoing' ? 'selected' : '' }}>▶️ Ongoing</option>
                                    <option value="completed" {{ old('status', $event?->status) === 'completed' ? 'selected' : '' }}>✅ Completed</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                            <x-input-error class="mt-1" :messages="$errors->get('status')" />
                        </div>
                    </div>
                </div>

                @if(!$hideChapterSelection && !$autoSelectChapter)
                    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 w-full hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center mb-6">
                            <div class="p-2 bg-purple-100 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900">Chapter Selection</h3>
                        </div>
                        <div class="space-y-2">
                            <x-input-label for="chapter_id" :value="__('Select Chapter')" class="text-sm font-medium text-gray-700" />
                            <div class="relative">
                                <select 
                                    id="chapter_id" 
                                    name="chapter_id" 
                                    class="w-full pl-4 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-200 appearance-none bg-white"
                                    required
                                >
                                    <option value="" class="text-gray-400 py-2">Choose a chapter for this event...</option>
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
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                            <x-input-error class="mt-1" :messages="$errors->get('chapter_id')" />
                        </div>
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row justify-end gap-4 pt-8 border-t border-gray-200">
                    <a 
                        href="{{ route('events.index') }}" 
                        class="px-6 py-3 text-center text-sm font-medium text-gray-700 bg-white border-2 border-gray-200 rounded-xl hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:ring-4 focus:ring-gray-100 transition-all duration-200 flex items-center justify-center"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </a>
                    <button 
                        type="submit" 
                        class="px-8 py-3 text-center text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-indigo-100 flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5"
                        :disabled="isSubmitting"
                        :class="{'opacity-75 cursor-not-allowed transform-none': isSubmitting}"
                    >
                        <span x-show="!isSubmitting" class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ $event ? 'Update Event' : 'Create Event' }}
                        </span>
                        <span x-show="isSubmitting" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ $event ? 'Saving...' : 'Creating...' }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
