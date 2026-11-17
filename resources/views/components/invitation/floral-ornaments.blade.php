@props(['eventType'])

@if (in_array($eventType, ['wedding', 'engagement'], true))
    <div class="floral-ornament floral-ornament--top pointer-events-none select-none" aria-hidden="true">
        <svg viewBox="0 0 120 120" fill="none">
            <g class="leaf-back"><path d="M12 112C30 73 55 46 102 18" stroke="currentColor" stroke-width="2"/><path d="M38 73c-19-4-25-18-19-27 16 2 23 13 19 27Z" fill="currentColor"/></g>
            <g class="flower-main"><circle cx="77" cy="42" r="12" fill="currentColor" opacity=".8"/><circle cx="77" cy="42" r="4" fill="currentColor"/></g>
            <g class="leaf-front"><path d="M55 61c17 1 26 13 22 26-16-1-25-12-22-26Z" fill="currentColor" opacity=".9"/></g>
        </svg>
    </div>
    <div class="floral-ornament floral-ornament--bottom pointer-events-none select-none" aria-hidden="true">
        <svg viewBox="0 0 120 120" fill="none">
            <g class="leaf-back"><path d="M108 8C91 49 64 75 18 104" stroke="currentColor" stroke-width="2"/><path d="M82 48c19 3 26 16 21 26-16-1-25-12-21-26Z" fill="currentColor"/></g>
            <g class="flower-main"><circle cx="47" cy="78" r="12" fill="currentColor" opacity=".8"/><circle cx="47" cy="78" r="4" fill="currentColor"/></g>
            <g class="leaf-front"><path d="M65 72c-17-2-26-14-21-26 16 1 25 12 21 26Z" fill="currentColor" opacity=".9"/></g>
        </svg>
    </div>
@endif
