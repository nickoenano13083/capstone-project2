@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ __('Invitation Code Details') }}
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="mb-3">{{ __('Code') }}</h5>
                        <div class="input-group mb-3">
                            <input type="text" 
                                   class="form-control font-monospace" 
                                   value="{{ $invitation->code }}" 
                                   id="invitationCode" 
                                   readonly>
                            <button class="btn btn-outline-secondary" 
                                    type="button" 
                                    onclick="copyToClipboard('invitationCode')">
                                <i class="fas fa-copy"></i> {{ __('Copy') }}
                            </button>
                        </div>
                        <div class="form-text">
                            {{ __('Share this code with the person you want to invite.') }}
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>{{ __('Status') }}</h5>
                            @if($invitation->used_at)
                                <span class="badge bg-secondary">{{ __('Used') }}</span>
                                <p class="mt-2 mb-0">
                                    <small class="text-muted">
                                        {{ __('Used by:') }} {{ $invitation->user->name ?? 'N/A' }}<br>
                                        {{ $invitation->used_at->format('M d, Y H:i') }}
                                    </small>
                                </p>
                            @elseif($invitation->expires_at && $invitation->expires_at->isPast())
                                <span class="badge bg-danger">{{ __('Expired') }}</span>
                                <p class="mt-2 mb-0">
                                    <small class="text-muted">
                                        {{ __('Expired on:') }} {{ $invitation->expires_at->format('M d, Y') }}
                                    </small>
                                </p>
                            @else
                                <span class="badge bg-success">{{ __('Active') }}</span>
                                <p class="mt-2 mb-0">
                                    <small class="text-muted">
                                        {{ __('Expires:') }} {{ $invitation->expires_at?->diffForHumans() ?? 'Never' }}
                                    </small>
                                </p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h5>{{ __('Details') }}</h5>
                            <p class="mb-1">
                                <strong>{{ __('Created by:') }}</strong> {{ $invitation->creator->name ?? 'N/A' }}
                            </p>
                            <p class="mb-1">
                                <strong>{{ __('Created on:') }}</strong> {{ $invitation->created_at->format('M d, Y') }}
                            </p>
                            @if($invitation->email)
                                <p class="mb-1">
                                    <strong>{{ __('Restricted to:') }}</strong> {{ $invitation->email }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i> {{ __('How to use this code') }}</h6>
                        <ol class="mb-0">
                            <li>{{ __('Share the code with the intended recipient') }}</li>
                            <li>{{ __('They will need this code during registration') }}</li>
                            <li>{{ __('The code can only be used once') }}</li>
                            @if($invitation->email)
                                <li>{{ __('This code can only be used with the email address shown above') }}</li>
                            @endif
                        </ol>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('invitations.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> {{ __('Back to List') }}
                        </a>
                        
                        @can('delete', $invitation)
                            @if(!$invitation->used_at)
                                <form action="{{ route('invitations.revoke', $invitation) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('{{ __('Are you sure you want to revoke this invitation code?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-ban me-1"></i> {{ __('Revoke Code') }}
                                    </button>
                                </form>
                            @endif
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function copyToClipboard(elementId) {
        const copyText = document.getElementById(elementId);
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
        
        // Show tooltip or alert
        const originalText = event.target.innerHTML;
        event.target.innerHTML = '<i class="fas fa-check"></i> {{ __("Copied!") }}';
        event.target.classList.add('btn-success');
        
        setTimeout(function() {
            event.target.innerHTML = originalText;
            event.target.classList.remove('btn-success');
        }, 2000);
    }
</script>
@endpush
@endsection
