@props(['eventType'])

@if (in_array($eventType, ['wedding', 'engagement'], true))
    <div data-butterfly-container aria-hidden="true"></div>
@endif
