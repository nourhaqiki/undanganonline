<div data-bottom-nav class="fixed left-1/2 z-50 w-[94%] max-w-md -translate-x-1/2 translate-y-5 opacity-0" x-cloak x-show="isOpen">
    <nav aria-label="Navigasi undangan" class="flex items-center justify-around rounded-full border border-white/50 bg-white/80 px-1.5 sm:px-2 py-1.5 shadow-xl backdrop-blur-lg">
        <button type="button" data-nav-target="hero" class="invitation-nav-item"><svg aria-hidden="true" viewBox="0 0 24 24"><path d="m3 11 9-8 9 8v10H3Z"/></svg><span>Cover</span></button>
        <button type="button" data-nav-target="profil" class="invitation-nav-item"><svg aria-hidden="true" viewBox="0 0 24 24"><circle cx="8" cy="9" r="3"/><circle cx="16" cy="9" r="3"/><path d="M3 20c1-4 9-4 10 0m-2 0c1-4 9-4 10 0"/></svg><span>Couple</span></button>
        <button type="button" data-nav-target="acara" class="invitation-nav-item"><svg aria-hidden="true" viewBox="0 0 24 24"><rect x="4" y="5" width="16" height="15" rx="1"/><path d="M8 3v4m8-4v4M7 10h10"/></svg><span>Events</span></button>
        @if (!empty($invitation->event_data['gallery_images']))
            <button type="button" data-nav-target="galeri" class="invitation-nav-item"><svg aria-hidden="true" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="1"/><circle cx="9" cy="10" r="2"/><path d="m5 18 5-5 3 3 3-3 3 5"/></svg><span>Gallery</span></button>
        @endif
        <button type="button" data-nav-target="ucapan" class="invitation-nav-item"><svg aria-hidden="true" viewBox="0 0 24 24"><path d="M4 5h16v11H9l-5 4Z"/><path d="M8 9h8m-8 3h5"/></svg><span>RSVP</span></button>
    </nav>
</div>
