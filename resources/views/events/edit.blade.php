<x-app-layout>
    <div class="py-12">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <x-events.form :event="$event" :chapters="$chapters" />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>