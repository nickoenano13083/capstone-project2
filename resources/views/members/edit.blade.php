<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ $member->exists ? 'Edit Member' : 'Add New Member' }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $member->exists ? 'Update member information' : 'Fill in the details below to add a new member' }}
                </p>
            </div>
            <div class="w-full sm:w-auto">
                <a href="{{ route('members.show', $member) }}" class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-3 sm:py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 min-h-[48px]">
                    <svg class="w-4 h-4 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span class="sm:hidden">Back</span>
                    <span class="hidden sm:inline">Back to Profile</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-blue-400 to-indigo-100 shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        {{ $member->exists ? 'Edit Member Information' : 'Add New Member' }}
                    </h3>
                </div>
                <div class="px-6 py-5">
                    @include('members.form', ['member' => $member, 'chapters' => $chapters, 'statuses' => $statuses, 'roles' => $roles, 'genders' => $genders])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>