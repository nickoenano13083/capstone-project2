@props([
    'user' => null,
    'size' => 'md',
    'showOnlineStatus' => true,
    'selected' => false
])

@php
    $sizeClasses = [
        'xs' => 'w-6 h-6 text-xs',
        'sm' => 'w-8 h-8 text-sm',
        'md' => 'w-12 h-12 text-lg',
        'lg' => 'w-14 h-14 text-2xl',
        'xl' => 'w-16 h-16 text-3xl'
    ];
    
    $statusSize = [
        'xs' => 'w-2 h-2',
        'sm' => 'w-2.5 h-2.5',
        'md' => 'w-3 h-3',
        'lg' => 'w-4 h-4',
        'xl' => 'w-5 h-5'
    ];
    
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
    $statusSizeClass = $statusSize[$size] ?? $statusSize['md'];
    
    $hasProfileImage = $user && $user->member && $user->member->profile_image;
    $userName = $user ? $user->name : '?';
    $isOnline = $user ? ($user->online ?? false) : false;
    $userType = $user ? ($user->type ?? 'user') : 'user';
@endphp

<div class="relative" role="img" aria-label="{{ $userName }}'s avatar">
    @if($hasProfileImage)
        {{-- Profile Image Avatar --}}
        <div class="{{ $sizeClass }} rounded-full p-0.5 {{ $selected ? 'bg-gradient-to-br from-amber-400 to-yellow-500 ring-2 ring-amber-300' : 'bg-gradient-to-br from-sky-400 to-lavender-500' }} shadow-lg">
            <img 
                src="{{ asset('storage/' . $user->member->profile_image) }}" 
                alt="{{ $userName }}'s avatar" 
                class="w-full h-full rounded-full object-cover border-2 border-white"
            >
        </div>
    @else
        {{-- Initials Avatar with Background --}}
        <div class="{{ $sizeClass }} rounded-full p-0.5 {{ $selected ? 'bg-gradient-to-br from-amber-400 to-yellow-500 ring-2 ring-amber-300' : 'bg-gradient-to-br from-sky-400 to-lavender-500' }} shadow-lg">
            <div class="w-full h-full rounded-full {{ $selected ? 'bg-gradient-to-br from-amber-300 to-yellow-400' : 'bg-gradient-to-br from-sky-300 to-lavender-400' }} flex items-center justify-center font-bold text-white border-2 border-white">
                {{ strtoupper(substr($userName, 0, 1)) }}
            </div>
        </div>
    @endif
    
    @if($showOnlineStatus)
        {{-- Online Status Indicator --}}
        <div 
            class="absolute -bottom-1 -right-1 {{ $statusSizeClass }} rounded-full border-2 border-white shadow-sm transition-all duration-300 {{ $isOnline ? 'bg-green-400 shadow-green-400/50' : 'bg-slate-400' }}"
            role="status"
            aria-label="{{ $isOnline ? 'Online' : 'Offline' }}"
        ></div>
        
        {{-- Member Type Badge for Members without User Accounts --}}
        @if($userType === 'member')
            <div 
                class="absolute -top-1 -right-1 px-1.5 py-0.5 bg-gradient-to-r from-yellow-100 to-amber-100 text-amber-700 text-xs rounded-full font-medium border border-yellow-200 shadow-sm"
                role="status"
                aria-label="Member without user account"
                title="Member without user account"
            >
                M
            </div>
        @endif
    @endif
    
    {{-- Selected State Glow Effect --}}
    @if($selected)
        <div class="absolute inset-0 rounded-full bg-gradient-to-r from-amber-400/20 to-yellow-400/20 animate-pulse"></div>
    @endif
</div>
