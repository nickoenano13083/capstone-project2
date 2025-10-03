<x-app-layout>
    <x-slot name="header">
        <div class="text-left">
            <h2 class="text-2xl font-semibold text-gray-800">
                {{ __('Create Event') }}
            </h2>
        </div>
    </x-slot>

    <div class="min-h-screen py-8" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <x-events.form :chapters="$chapters" />
            </div>
        </div>
    </div>
</x-app-layout>
