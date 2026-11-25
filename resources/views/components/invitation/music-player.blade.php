<div data-floating-player class="fixed right-3 sm:right-4 z-50 translate-y-5 opacity-0" x-cloak x-show="isOpen && musicMode !== 'none'">
    <button @click="toggleMusic()" type="button" aria-label="Putar atau jeda musik" class="h-11 w-11 rounded-full border border-white/40 bg-white/30 text-amber-200 shadow-lg backdrop-blur-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-300">
        <svg data-music-disc class="mx-auto h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12 0c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
        </svg>
    </button>
</div>
