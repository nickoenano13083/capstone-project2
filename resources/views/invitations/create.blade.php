@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                <!-- Header -->
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">
                        <i class="fas fa-key mr-2 text-blue-600"></i>
                        {{ __('Generate Invitation Code') }}
                    </h2>
                    <div class="mt-4 md:mt-0">
                        <a href="{{ route('invitations.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-arrow-left mr-2"></i>
                            {{ __('Back to Invitations') }}
                        </a>
                    </div>
                </div>

                <!-- Form Card -->
                <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                    <div class="p-6">
                        <form method="POST" action="{{ route('invitations.store') }}" class="space-y-6">
                            @csrf

                            <!-- Email Field -->
                            <div class="space-y-2">
                                <label for="email" class="block text-sm font-medium text-gray-700">
                                    {{ __('Email Address') }}
                                    <span class="text-gray-400 text-sm ml-1">({{ __('Optional') }})</span>
                                </label>
                                <div class="mt-1">
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}"
                                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border"
                                           placeholder="user@example.com"
                                           autocomplete="off">
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-600">
                                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ __('Leave blank to generate a general use code') }}
                                </p>
                            </div>

                            <!-- Expiration Field -->
                            <div class="space-y-2">
                                <label for="expires_in_days" class="block text-sm font-medium text-gray-700">
                                    {{ __('Expiration Period') }}
                                </label>
                                <div class="mt-1">
                                    <select id="expires_in_days" 
                                            name="expires_in_days" 
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border">
                                        <option value="1">{{ __('1 Day') }}</option>
                                        <option value="7" selected>{{ __('1 Week') }}</option>
                                        <option value="30">{{ __('1 Month') }}</option>
                                        <option value="90">{{ __('3 Months') }}</option>
                                        <option value="365">{{ __('1 Year') }}</option>
                                    </select>
                                    @error('expires_in_days')
                                        <p class="mt-2 text-sm text-red-600">
                                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Number of Codes -->
                            <div class="space-y-2">
                                <label for="count" class="block text-sm font-medium text-gray-700">
                                    {{ __('Number of Codes') }}
                                </label>
                                <div class="mt-1">
                                    <input type="number" 
                                           id="count" 
                                           name="count" 
                                           min="1" 
                                           max="20" 
                                           value="1" 
                                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border"
                                           required>
                                    @error('count')
                                        <p class="mt-2 text-sm text-red-600">
                                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ __('You can generate up to 20 codes at once.') }}
                                </p>
                            </div>

                            <!-- Form Actions -->
                            <div class="flex justify-end pt-6 pb-2">
                                <button type="submit" style="background-color: #2563eb !important;" class="inline-flex items-center px-6 py-2.5 border border-transparent text-base font-medium rounded-md shadow-md text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 relative z-10">
                                    <i class="fas fa-key mr-2"></i>
                                    {{ __('Generate Code') }}
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Footer Note -->
                    <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                            {{ __('Generated codes will be available in the invitations list.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Match the gradient and shadow styles from other pages */
    .bg-gradient-to-r {
        background-image: linear-gradient(to right, var(--tw-gradient-stops));
    }
    
    .from-gray-50 {
        --tw-gradient-from: #f9fafb;
        --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(249, 250, 251, 0));
    }
    
    .to-gray-100 {
        --tw-gradient-to: #f3f4f6;
    }
    
    /* Ensure consistent form control styling */
    input[type="email"],
    input[type="number"],
    select {
        transition: all 0.2s ease;
    }
    
    input[type="email"]:focus,
    input[type="number"]:focus,
    select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Button hover effect */
    .hover\:bg-blue-700:hover {
        background-color: #1d4ed8;
    }
</style>
@endpush

@push('scripts')
<script>
    // Add any necessary JavaScript here
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation can be added here if needed
    });
</script>
@endpush
@endsection
