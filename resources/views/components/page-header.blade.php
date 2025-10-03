<div class="analytics-header mb-6" style="background-color: rgb(35, 58, 101); border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); padding: 1.25rem 1.5rem;">
    <div class="page-header-flex" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; flex-wrap: wrap;">
        <div style="flex: 1 1 auto; min-width: 220px;">
            <h1 style="font-size: 1.25rem; font-weight: 700; color: rgb(212, 220, 237); display: flex; align-items: center; gap: 10px; margin: 0;">
                @if(!empty($icon))
                    <i class="{{ $icon }}" style="color: rgb(206, 205, 239); font-size: 1.2em;"></i>
                @endif
                {{ $title ?? '' }}
            </h1>
            @if(!empty($subtitle))
                <p style="font-size: 0.9rem; color: rgb(197, 213, 234); margin: 0;">{{ $subtitle }}</p>
            @endif
        </div>
        @if(isset($slot) && !empty(trim($slot)))
            <div class="page-header-actions" style="flex: 1 1 100%; display: block; margin-top: 0.75rem;">
                {{ $slot }}
            </div>
        @endif
    </div>
</div>

<style>
@media (max-width: 640px) {
    .page-header-flex {
        flex-direction: column;
        align-items: stretch !important;
        gap: 0.75rem !important;
    }
    .page-header-actions {
        width: 100%;
        justify-content: stretch !important;
    }
    .page-header-actions > * {
        width: 100% !important;
        display: inline-flex !important;
        justify-content: center !important;
    }
}
</style>

