@props(['eventType' => 'wedding', 'themeSlug' => ''])

@if (in_array($eventType, ['wedding', 'engagement'], true) && in_array($themeSlug, ['jawa', 'sunda', 'palembang'], true))
    @include('themes.partials.butterflies')
@endif
