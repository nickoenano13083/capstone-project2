<x-app-layout>
    <x-slot name="header">
        <div class="text-left">
            <h2 class="text-2xl font-semibold text-gray-800">
                {{ __('Create Event') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <x-events.form :chapters="$chapters" />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
