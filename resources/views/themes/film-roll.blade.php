<!DOCTYPE html>
<html lang="id" class="scroll-smooth h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ $invitation->title }} | The Premiere of {{ $invitation->event_data['primary_name'] ?? 'Mempelai 1' }} &amp; {{ $invitation->event_data['secondary_name'] ?? 'Mempelai 2' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Marcellus (Cinematic Titles), Space Grotesk, Space Mono, Urbanist, Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Space+Grotesk:wght@400;500;600;700&family=Space+Mono:ital,wght@0,400;0,700;1,400&family=Urbanist:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS v4 & Alpine.js CDN -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }

        :root {
            --fr-bg: #090909;
            --fr-paper: #FAF7EF;
            --fr-paper-dark: #ECE7DB;
            --fr-border-dashed: rgba(250, 247, 239, 0.45);
            --fr-film-strip: #161616;
            --fr-gold: #C5A059;
            --fr-gold-light: #E8CF9B;
            --fr-amber: #D4AF37;
            --fr-text-muted: #8E8A82;
            --fr-ink: #111111;
        }

        .font-cinema-title { font-family: 'Marcellus', 'Cormorant Garamond', Georgia, serif; }
        .font-grotesk     { font-family: 'Space Grotesk', system-ui, sans-serif; }
        .font-mono-film   { font-family: 'Space Mono', monospace; }
        .font-urban       { font-family: 'Urbanist', system-ui, sans-serif; }

        /* Film Grain Noise Background */
        .bg-film-grain {
            background-color: #090909;
            background-image: radial-gradient(rgba(250, 247, 239, 0.04) 1px, transparent 0);
            background-size: 24px 24px;
        }

        /* Vintage Vignette Effect */
        .vintage-vignette {
            background: radial-gradient(ellipse at 50% 50%, transparent 40%, rgba(0,0,0,0.85) 100%);
        }

        /* 35mm Sprocket Hole Border Patterns */
        .sprocket-track-horizontal {
            background-image: radial-gradient(circle 3px, #090909 90%, transparent 100%);
            background-size: 16px 16px;
            background-repeat: repeat-x;
            background-position: center;
        }
        .sprocket-track-vertical {
            background-image: radial-gradient(circle 3px, #090909 90%, transparent 100%);
            background-size: 16px 16px;
            background-repeat: repeat-y;
            background-position: center;
        }

        /* Scalloped Notched Cinema Ticket Mask */
        .ticket-stub-mask {
            background-color: #FAF7EF;
            color: #111111;
            -webkit-mask: radial-gradient(circle 8px at 0 0, transparent 96%, #000 100%) 0 0/51% 51% no-repeat,
                          radial-gradient(circle 8px at 100% 0, transparent 96%, #000 100%) 100% 0/51% 51% no-repeat,
                          radial-gradient(circle 8px at 0 100%, transparent 96%, #000 100%) 0 100%/51% 51% no-repeat,
                          radial-gradient(circle 8px at 100% 100%, transparent 96%, #000 100%) 100% 100%/51% 51% no-repeat;
            mask: radial-gradient(circle 8px at 0 0, transparent 96%, #000 100%) 0 0/51% 51% no-repeat,
                  radial-gradient(circle 8px at 100% 0, transparent 96%, #000 100%) 100% 0/51% 51% no-repeat,
                  radial-gradient(circle 8px at 0 100%, transparent 96%, #000 100%) 0 100%/51% 51% no-repeat,
                  radial-gradient(circle 8px at 100% 100%, transparent 96%, #000 100%) 100% 100%/51% 51% no-repeat;
        }

        .ticket-button-mask {
            -webkit-mask: radial-gradient(circle 5px at 0 50%, transparent 96%, #000 100%),
                          radial-gradient(circle 5px at 100% 50%, transparent 96%, #000 100%);
            -webkit-mask-composite: source-in;
            mask-composite: intersect;
        }

        /* The short scan and staged reveals guide attention through the film-roll story. */
        @keyframes reelSpin {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }

        @keyframes cameraFlash {
            0%   { opacity: 0; }
            20%  { opacity: 0.95; }
            100% { opacity: 0; }
        }

        @keyframes filmLeadIn {
            from { opacity: 0; transform: translate3d(0, 16px, 0); }
            to   { opacity: 1; transform: translate3d(0, 0, 0); }
        }

        @keyframes filmLightLeak {
            0%   { opacity: 0; transform: translate3d(-110%, 0, 0) skewX(-16deg); }
            24%  { opacity: 0.7; }
            100% { opacity: 0; transform: translate3d(110%, 0, 0) skewX(-16deg); }
        }

        @media (prefers-reduced-motion: no-preference) {
            .animate-reel-spin { animation: reelSpin 6s linear infinite; }
            .shutter-flash { animation: cameraFlash 0.65s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards; }

            #section-cover .cover-intro,
            #section-cover .cover-copy > *,
            #section-cover .cover-action {
                opacity: 0;
                animation: filmLeadIn 800ms cubic-bezier(0.22, 1, 0.36, 1) forwards;
            }
            #section-cover .cover-copy > :nth-child(1) { animation-delay: 120ms; }
            #section-cover .cover-copy > :nth-child(2) { animation-delay: 240ms; }
            #section-cover .cover-copy > :nth-child(3) { animation-delay: 360ms; }
            #section-cover .cover-copy > :nth-child(4) { animation-delay: 480ms; }
            #section-cover .cover-action { animation-delay: 600ms; }
        }

        /* Cover Screen Handover */
        #section-cover {
            transition: transform 900ms cubic-bezier(0.4, 0, 0.2, 1),
                        opacity 700ms cubic-bezier(0.4, 0, 0.2, 1);
        }
        #section-cover.is-leaving {
            transform: translateY(-100%);
            opacity: 0;
            pointer-events: none;
        }

        /* Reveal on scroll */
        .reveal-item {
            opacity: 0;
            transform: translate3d(0, 22px, 0);
            transition: opacity 850ms cubic-bezier(0.22, 1, 0.36, 1), transform 850ms cubic-bezier(0.22, 1, 0.36, 1);
            transition-delay: var(--reveal-delay, 0ms);
        }
        .reveal-item.revealed {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
        .reveal-item[data-reveal="zoom-soft"] {
            transform: translate3d(0, 16px, 0) scale(0.96);
        }
        .reveal-item.revealed[data-reveal="zoom-soft"] {
            transform: translate3d(0, 0, 0) scale(1);
        }
        .film-poster-frame::after {
            content: "";
            position: absolute;
            inset: 0;
            z-index: 1;
            pointer-events: none;
            opacity: 0;
            background: linear-gradient(110deg, transparent 38%, rgba(232, 207, 155, 0.08) 47%, rgba(232, 207, 155, 0.28) 51%, transparent 62%);
        }
        .film-poster-frame.revealed::after {
            animation: filmLightLeak 1.1s 250ms cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        .film-gallery-frame {
            text-align: left;
        }
        .film-gallery-frame:focus-visible,
        #section-cover button:focus-visible,
        #section-top button:focus-visible,
        #section-top a:focus-visible {
            outline: 2px solid var(--fr-gold-light);
            outline-offset: 3px;
        }

        @media (prefers-reduced-motion: reduce) {
            html.scroll-smooth { scroll-behavior: auto; }
            #section-cover { transition: none; }
            #section-cover.is-leaving { transform: translateY(-100%); }
            .reveal-item,
            .reveal-item[data-reveal="zoom-soft"],
            .reveal-item.revealed,
            .reveal-item.revealed[data-reveal="zoom-soft"] {
                opacity: 1;
                transform: none;
                transition: none;
            }
            .animate-reel-spin,
            .shutter-flash,
            .film-poster-frame.revealed::after {
                animation: none;
            }
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #0A0A0A; }
        ::-webkit-scrollbar-thumb { background: #262626; border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: #FAF7EF; }
    </style>
</head>

@php
    $eventType = match ($invitation->category) {
        'pernikahan' => 'wedding',
        'tunangan'   => 'engagement',
        default      => $invitation->category
    };

    $primaryName       = $invitation->event_data['primary_name'] ?? 'Mempelai 1';
    $secondaryName     = $invitation->event_data['secondary_name'] ?? 'Mempelai 2';
    $primaryParents    = $invitation->event_data['primary_title_parents'] ?? 'Putra pertama dari Bpk. Bambang & Ibu Sri';
    $secondaryParents  = $invitation->event_data['secondary_title_parents'] ?? 'Putri kedua dari Bpk. Suryo & Ibu Ratna';
    $primaryIg         = $invitation->event_data['primary_instagram'] ?? '';
    $secondaryIg       = $invitation->event_data['secondary_instagram'] ?? '';
    $s1                = $invitation->event_data['session_1'] ?? [];
    $s2                = $invitation->event_data['session_2'] ?? [];
    $gallery           = $invitation->event_data['gallery_images'] ?? [];
    $gifts             = $invitation->event_data['gift_accounts'] ?? [];
    $greeting          = $invitation->event_data['greeting'] ?? 'Dengan memohon rahmat dan ridho Tuhan Yang Maha Esa, kami mempersembahkan penayangan perdana perayaan cinta kami:';
    $quote             = $invitation->event_data['quote'] ?? 'Setiap kisah cinta memiliki sinematografi takdirnya sendiri. Dan di sinilah babak terindah kami dimulai untuk selamanya.';
    $coverImg          = $invitation->event_data['cover_image'] ?? '';

    // Tanggal Acara
    $eventDateObj      = !empty($s1['date']) ? \Carbon\Carbon::parse($s1['date']) : now()->addMonths(2);
    $eventDateFormatted= $eventDateObj->isoFormat('dddd, D MMMM Y');
    $eventDateNumerics = $eventDateObj->format('d / m / Y');
    $eventYear         = $eventDateObj->format('Y');

    // Google Calendar Link
    $calDate  = str_replace('-', '', $s1['date'] ?? date('Y-m-d'));
    $calTimes = explode(' - ', $s1['time'] ?? '09:00 - 12:00');
    $calStart = str_pad(str_replace([':', ' ', 'WIB'], '', $calTimes[0] ?? '0900'), 4, '0');
    $calEnd   = isset($calTimes[1]) ? str_pad(str_replace([':', ' ', 'WIB'], '', $calTimes[1]), 4, '0') : '1200';
    $calUrl   = 'https://calendar.google.com/calendar/render?action=TEMPLATE'
        . '&text='     . urlencode('The Premiere of ' . $primaryName . ' & ' . $secondaryName)
        . '&dates='    . $calDate . 'T' . $calStart . '00Z/' . $calDate . 'T' . $calEnd . '00Z'
        . '&location=' . urlencode(($s1['venue'] ?? 'Gedung Pertemuan') . ', ' . ($s1['address'] ?? ''));
@endphp

<body data-event-type="{{ $eventType }}"
      class="font-urban bg-[#090909] text-[#FAF7EF] min-h-screen overflow-x-hidden antialiased selection:bg-[#FAF7EF] selection:text-[#090909]"
      x-data="{
          ...invitationEngine({
              musicMode: '{{ $invitation->event_data['music_mode'] ?? 'ambient' }}',
              musicUrl: '{{ !empty($invitation->event_data['music_file']) ? asset('storage/' . $invitation->event_data['music_file']) : '' }}',
              guestName: {{ json_encode($guestName ?? 'Tamu Undangan') }},
              guestAddress: {{ json_encode($guestAddress ?? '') }},
              targetDate: '{{ $targetDate ?? $eventDateObj->format('Y-m-d H:i:s') }}',
              initialWishes: {{ json_encode($wishesData ?? []) }},
              initialCounts: {{ json_encode($initialCounts ?? ['total' => 0, 'hadir' => 0, 'tidak_hadir' => 0]) }}
          }),
          isShutterFlash: false,
          isGateLeaving: false,
          activeGalleryPhoto: '',
          galleryModal: false,
          openPremiere() {
              if (this.isGateLeaving || this.isOpen) return;
              this.isShutterFlash = true;
              this.playMusic(0);

              setTimeout(() => {
                  this.isGateLeaving = true;
              }, 260);

              setTimeout(() => {
                  this.isOpen = true;
                  document.body.classList.remove('overflow-hidden', 'h-screen', 'h-dvh');
                  document.body.classList.add('invitation-open');
                  this.fadeMusicIn();
                  this.isShutterFlash = false;
                  const gate = document.getElementById('section-cover');
                  if (gate) gate.style.display = 'none';
              }, 1150);
          },
          openFilmPhoto(src) {
              this.activeGalleryPhoto = src;
              this.galleryModal = true;
          }
      }"
      x-init="
          document.body.classList.add('overflow-hidden');
          $nextTick(() => {
              const revealItems = document.querySelectorAll('.reveal-item');
              if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || !('IntersectionObserver' in window)) {
                  revealItems.forEach(el => el.classList.add('revealed'));
                  return;
              }

              document.querySelectorAll('[data-reveal-stagger]').forEach(group => {
                  Array.from(group.children)
                      .filter(el => el.classList.contains('reveal-item'))
                      .forEach((el, index) => el.style.setProperty('--reveal-delay', `${index * 110}ms`));
              });

              const obs = new IntersectionObserver((entries) => {
                  entries.forEach(entry => {
                      if (entry.isIntersecting) {
                          entry.target.classList.add('revealed');
                          obs.unobserve(entry.target);
                      }
                  });
              }, { threshold: 0.14 });
              revealItems.forEach(el => obs.observe(el));
          });
      ">

    {{-- White Camera Shutter Flash Overlay --}}
    <div x-show="isShutterFlash" x-cloak class="fixed inset-0 z-[70] bg-[#FAF7EF] pointer-events-none shutter-flash"></div>

    {{-- =========================================================
         1. COVER SCREEN TERKUNCI (VINTAGE CINEMA PREMIERE GATE)
         Sesuai Layar 1 Referensi Film Roll Our-Wedding.Link
         ========================================================= --}}
    <div id="section-cover"
         :class="{ 'is-leaving': isGateLeaving || isOpen }"
         :inert="isGateLeaving || isOpen"
         class="fixed inset-0 z-50 h-dvh w-full overflow-y-auto overflow-x-hidden flex flex-col justify-between items-center text-center bg-[#090909] text-[#FAF7EF] p-4 sm:p-6 bg-film-grain select-none">

        {{-- Vignette & Ambient Radial Glow --}}
        <div class="absolute inset-0 vintage-vignette pointer-events-none z-0"></div>
        <div class="absolute inset-0 bg-radial from-[#FAF7EF]/5 via-transparent to-transparent pointer-events-none z-0"></div>

        {{-- 35mm Perforated Film Strip Left & Right Borders --}}
        <div class="absolute inset-y-0 left-0 w-5 bg-[#141414] border-r border-white/10 sprocket-track-vertical opacity-60 z-10 hidden sm:block"></div>
        <div class="absolute inset-y-0 right-0 w-5 bg-[#141414] border-l border-white/10 sprocket-track-vertical opacity-60 z-10 hidden sm:block"></div>

        {{-- Top Header: Laurel Leaf Award Wreath --}}
        <div class="cover-intro relative z-20 pt-4 flex flex-col items-center">
            <div class="flex items-center gap-2 text-[#FAF7EF]/80">
                <!-- Laurel Leaf Left (SVG) -->
                <svg class="w-6 h-6 text-[#C5A059]" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12c0 3.09 1.41 5.86 3.63 7.7.27-.47.6-.9 1-1.28C4.88 16.83 4 14.53 4 12c0-4.41 3.59-8 8-8s8 3.59 8 8c0 2.53-.88 4.83-2.63 6.42.4.38.73.81 1 1.28C20.59 17.86 22 15.09 22 12c0-5.52-4.48-10-10-10z"/>
                    <path d="M7 11.5c0-1.38 1.12-2.5 2.5-2.5S12 10.12 12 11.5 10.88 14 9.5 14 7 12.88 7 11.5z" opacity="0.6"/>
                </svg>
                <div class="flex flex-col items-center">
                    <span class="font-grotesk text-[9.5px] uppercase tracking-[0.35em] text-[#C5A059] font-bold">
                        ★ Official Selection {{ $eventYear }} ★
                    </span>
                    <span class="font-cinema-title text-[11px] tracking-[0.25em] text-[#FAF7EF]/90 uppercase">
                        A Love Story Premiere
                    </span>
                </div>
                <!-- Laurel Leaf Right (SVG) -->
                <svg class="w-6 h-6 text-[#C5A059] scale-x-[-1]" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12c0 3.09 1.41 5.86 3.63 7.7.27-.47.6-.9 1-1.28C4.88 16.83 4 14.53 4 12c0-4.41 3.59-8 8-8s8 3.59 8 8c0 2.53-.88 4.83-2.63 6.42.4.38.73.81 1 1.28C20.59 17.86 22 15.09 22 12c0-5.52-4.48-10-10-10z"/>
                    <path d="M7 11.5c0-1.38 1.12-2.5 2.5-2.5S12 10.12 12 11.5 10.88 14 9.5 14 7 12.88 7 11.5z" opacity="0.6"/>
                </svg>
            </div>
        </div>

        {{-- Center Content: Movie Title & Scalloped Guest Ticket --}}
        <div class="cover-copy relative z-20 my-auto w-full max-w-sm flex flex-col items-center space-y-4 sm:space-y-6">
            {{-- Stamped Film Frame Label --}}
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded border border-[#FAF7EF]/25 bg-black/40 font-mono-film text-[10px] tracking-widest text-[#FAF7EF]/75">
                <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                <span>ROLL 35mm • FRAME #01</span>
            </div>

            {{-- Couple Names in Dramatic Cinema Title Typography --}}
            <div class="space-y-1">
                <h1 class="font-cinema-title text-4xl sm:text-5xl font-bold tracking-tight text-[#FAF7EF] uppercase drop-shadow-lg">
                    {{ $primaryName }}
                </h1>
                <div class="font-cinema-title text-2xl sm:text-3xl text-[#C5A059] my-0.5 italic">
                    &amp;
                </div>
                <h1 class="font-cinema-title text-4xl sm:text-5xl font-bold tracking-tight text-[#FAF7EF] uppercase drop-shadow-lg">
                    {{ $secondaryName }}
                </h1>
            </div>

            <p class="font-mono-film text-xs uppercase tracking-[0.3em] text-[#C5A059] border-y border-[#FAF7EF]/15 py-1.5 px-6">
                {{ $eventDateNumerics }}
            </p>

            {{-- Vintage Cinema Admission Ticket Guest Card (Ticket Stub Mask) --}}
            <div class="ticket-stub-mask w-full max-w-[310px] p-5 shadow-[0_15px_35px_rgba(0,0,0,0.8)] text-center space-y-2 relative">
                <div class="flex items-center justify-between border-b border-black/15 pb-2 text-[9px] font-mono-film tracking-wider text-black/60 uppercase">
                    <span>VIP ADMISSION</span>
                    <span>NO. {{ str_pad(substr(abs(crc32($guestName ?? '0')), 0, 5), 5, '0', STR_PAD_LEFT) }}</span>
                </div>

                <div class="pt-1 space-y-0.5">
                    <p class="text-[9px] uppercase font-grotesk tracking-widest text-black/60 font-semibold">Special Invitation For:</p>
                    <h2 class="font-cinema-title text-xl font-bold text-black truncate">
                        {{ !empty($guestName) ? $guestName : 'Tamu Undangan' }}
                    </h2>
                    @if(!empty($guestAddress))
                        <p class="font-urban text-xs text-black/75 truncate font-medium">
                            di {{ $guestAddress }}
                        </p>
                    @endif
                </div>

                <div class="pt-2 border-t border-dashed border-black/25 flex items-center justify-between text-[8.5px] font-mono-film text-black/50">
                    <span>KODAK ANALOG 2026</span>
                    <span>ONE SEAT RESERVED</span>
                </div>
            </div>
        </div>

        {{-- Bottom Action: Cinema Ticket "Buka Undangan" Button --}}
        <div class="cover-action relative z-20 w-full pb-6 pt-2 flex flex-col items-center space-y-3">
            <button @click="openPremiere()"
                    type="button"
                    class="group relative inline-flex items-center overflow-hidden rounded-full font-grotesk font-bold text-xs uppercase tracking-[0.2em] bg-[#FAF7EF] text-[#090909] hover:bg-white active:scale-95 transition-all shadow-[0_0_30px_rgba(250,247,239,0.35)]">
                {{-- Ticket Left Side --}}
                <span class="py-3.5 pl-6 pr-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#C5A059] group-hover:rotate-45 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18 4l2 4h-3l-2-4h-2l2 4h-3l-2-4H8l2 4H7L5 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4h-4zM4 18V8h16v10H4z"/>
                    </svg>
                    <span>Buka Undangan</span>
                </span>
                {{-- Ticket Stub Right Side (Perforated) --}}
                <span class="py-3.5 pr-5 pl-3 border-l border-dashed border-black/30 bg-[#FAF7EF]/90 font-mono-film text-[10px] text-black/60 group-hover:text-black">
                    ADMIT ONE ↗
                </span>
            </button>

            <p class="font-mono-film text-[9px] uppercase tracking-widest text-[#FAF7EF]/40">
                Tap to unroll film reel &amp; play soundtrack
            </p>
        </div>
    </div>


    {{-- =========================================================
         2. MAIN CINEMATIC INVITATION CONTAINER (FILM ROLL THEME)
         ========================================================= --}}
    <main id="section-top"
          class="relative w-full max-w-lg mx-auto bg-[#0E0E0E] text-[#FAF7EF] min-h-screen border-x border-white/10 shadow-[0_0_60px_rgba(0,0,0,0.9)] pb-28">

        {{-- Continuous 35mm Film Sprocket Ribbon Header --}}
        <div class="w-full h-8 bg-[#181818] border-b border-white/10 flex items-center justify-between px-3 text-[10px] font-mono-film text-[#FAF7EF]/50 overflow-hidden">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-sm bg-[#0E0E0E] border border-white/20"></span>
                <span class="w-2.5 h-2.5 rounded-sm bg-[#0E0E0E] border border-white/20"></span>
                <span class="w-2.5 h-2.5 rounded-sm bg-[#0E0E0E] border border-white/20"></span>
                <span>KODAK SAFETY FILM 35mm</span>
            </div>
            <div class="flex items-center gap-2">
                <span>ISO 400</span>
                <span class="w-2.5 h-2.5 rounded-sm bg-[#0E0E0E] border border-white/20"></span>
                <span class="w-2.5 h-2.5 rounded-sm bg-[#0E0E0E] border border-white/20"></span>
            </div>
        </div>

        {{-- =====================================================
             HERO: MOVIE POSTER & FESTIVAL LAURELS
             ===================================================== --}}
        <section data-reveal-stagger class="relative pt-10 pb-8 px-6 text-center">
            {{-- Laurel Festival Badge --}}
            <div class="reveal-item inline-flex items-center gap-3 border border-white/15 px-4 py-1.5 rounded-full bg-black/60 backdrop-blur-md mb-6">
                <span class="text-[#C5A059] text-xs">❦</span>
                <span class="font-grotesk text-[10px] uppercase tracking-[0.25em] text-[#FAF7EF]/90 font-semibold">
                    The Wedding Celebration
                </span>
                <span class="text-[#C5A059] text-xs">❦</span>
            </div>

            {{-- Main Movie Title --}}
            <div class="reveal-item space-y-1 mb-6">
                <h2 class="font-cinema-title text-4xl sm:text-5xl font-bold tracking-tight text-[#FAF7EF] uppercase leading-tight">
                    {{ $primaryName }}
                </h2>
                <div class="font-cinema-title text-2xl text-[#C5A059] italic my-0.5">&amp;</div>
                <h2 class="font-cinema-title text-4xl sm:text-5xl font-bold tracking-tight text-[#FAF7EF] uppercase leading-tight">
                    {{ $secondaryName }}
                </h2>
                <p class="font-mono-film text-xs text-[#FAF7EF]/60 tracking-[0.2em] uppercase mt-2">
                    {{ $eventDateFormatted }}
                </p>
            </div>

            {{-- 35mm Film Photo Poster Frame --}}
            <div data-reveal="zoom-soft" class="reveal-item film-poster-frame my-6 relative p-3 rounded-2xl bg-[#181818] border border-white/15 shadow-2xl overflow-hidden">
                {{-- Top Sprocket Holes on Poster --}}
                <div class="w-full h-4 sprocket-track-horizontal mb-2 opacity-80"></div>

                <div class="relative w-full aspect-[4/5] rounded-xl overflow-hidden bg-black border border-white/10">
                    @if(!empty($coverImg))
                        <img src="{{ asset('storage/' . $coverImg) }}" alt="Foto sampul undangan" fetchpriority="high" class="w-full h-full object-cover filter contrast-[1.08] saturate-[1.05]">
                    @else
                        <img src="https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=800&auto=format&fit=crop" alt="Foto sampul undangan" fetchpriority="high" class="w-full h-full object-cover filter contrast-[1.08]">
                    @endif

                    {{-- Film Grain & Frame Number Badge --}}
                    <div class="absolute bottom-3 left-3 px-2 py-0.5 rounded bg-black/75 backdrop-blur-sm border border-white/20 font-mono-film text-[9px] text-[#C5A059]">
                        EXP {{ $eventYear }} • 36 EXPOSURES
                    </div>
                </div>

                {{-- Bottom Sprocket Holes on Poster --}}
                <div class="w-full h-4 sprocket-track-horizontal mt-2 opacity-80"></div>
            </div>

            {{-- 5-Star Reviews Callout (Film Premiere Style) --}}
            <div class="reveal-item mt-6 p-4 rounded-xl bg-black/50 border border-white/10 space-y-1.5 text-left">
                <div class="flex items-center justify-between">
                    <div class="flex text-[#C5A059] text-xs tracking-wider">
                        ★★★★★
                    </div>
                    <span class="font-mono-film text-[9px] uppercase text-[#FAF7EF]/50 tracking-wider">CRITICS REVIEW</span>
                </div>
                <p class="font-cinema-title text-sm italic text-[#FAF7EF]/90">
                    "Dua insan yang ditakdirkan bersama, kini bersatu mengukir babak kehidupan paling bercahaya."
                </p>
                <p class="font-grotesk text-[10px] text-[#C5A059] uppercase tracking-wider font-semibold">
                    — The Cinematic Wedding Journal
                </p>
            </div>

            {{-- Countdown Timer Box (Film Reel Counter) --}}
            <div class="reveal-item mt-6 p-4 rounded-2xl bg-[#141414] border border-white/15 text-center">
                <span class="font-mono-film text-[10px] uppercase tracking-[0.25em] text-[#C5A059] block mb-3 font-semibold">
                    ⏱ COUNTDOWN TO PREMIERE
                </span>
                <div class="grid grid-cols-4 gap-2">
                    <div class="p-2 rounded-xl bg-black/80 border border-white/10">
                        <span class="font-mono-film text-2xl font-bold text-white block" x-text="countdown.days">00</span>
                        <span class="text-[9px] uppercase font-grotesk tracking-widest text-[#FAF7EF]/50">HARI</span>
                    </div>
                    <div class="p-2 rounded-xl bg-black/80 border border-white/10">
                        <span class="font-mono-film text-2xl font-bold text-white block" x-text="countdown.hours">00</span>
                        <span class="text-[9px] uppercase font-grotesk tracking-widest text-[#FAF7EF]/50">JAM</span>
                    </div>
                    <div class="p-2 rounded-xl bg-black/80 border border-white/10">
                        <span class="font-mono-film text-2xl font-bold text-white block" x-text="countdown.minutes">00</span>
                        <span class="text-[9px] uppercase font-grotesk tracking-widest text-[#FAF7EF]/50">MENIT</span>
                    </div>
                    <div class="p-2 rounded-xl bg-black/80 border border-white/10">
                        <span class="font-mono-film text-2xl font-bold text-[#C5A059] block" x-text="countdown.seconds">00</span>
                        <span class="text-[9px] uppercase font-grotesk tracking-widest text-[#C5A059]">DETIK</span>
                    </div>
                </div>
            </div>
        </section>


        {{-- =====================================================
             SECTION: RETRO FILM SOUNDTRACK CARD
             Styled as Analog 35mm Cassette / Film Reel
             ===================================================== --}}
        <section class="reveal-item px-6 py-4">
            <div class="p-4 rounded-2xl bg-gradient-to-r from-[#181818] via-[#222222] to-[#181818] border border-white/15 shadow-xl flex items-center justify-between gap-4">
                {{-- Dual Spinning Reels --}}
                <div class="flex items-center gap-2 shrink-0">
                    <div :class="musicPlaying ? 'animate-reel-spin' : ''" class="w-10 h-10 rounded-full border-2 border-white/30 flex items-center justify-center bg-black/80">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#C5A059]"></span>
                    </div>
                    <div class="w-4 h-[2px] bg-white/20"></div>
                    <div :class="musicPlaying ? 'animate-reel-spin' : ''" class="w-10 h-10 rounded-full border-2 border-white/30 flex items-center justify-center bg-black/80">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#C5A059]"></span>
                    </div>
                </div>

                {{-- Track Info --}}
                <div class="flex-1 min-w-0">
                    <span class="font-mono-film text-[9px] uppercase tracking-wider text-[#C5A059] font-bold block">
                        ORIGINAL SOUNDTRACK
                    </span>
                    <h3 class="font-grotesk text-sm font-bold text-white truncate">
                        A Love Story in 35mm
                    </h3>
                    <p class="font-mono-film text-[10px] text-[#FAF7EF]/60 truncate" x-text="musicPlaying ? 'Memutar Pita Suara...' : 'Pita Suara Dijeda'">
                        Memutar Pita Suara...
                    </p>
                </div>

                {{-- Play/Pause Button --}}
                <button type="button" @click="toggleMusic()"
                        class="w-9 h-9 rounded-full bg-[#FAF7EF] text-black hover:bg-white flex items-center justify-center shrink-0 active:scale-95 transition">
                    <template x-if="!musicPlaying">
                        <svg class="w-4 h-4 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </template>
                    <template x-if="musicPlaying">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                    </template>
                </button>
            </div>
        </section>


        {{-- =====================================================
             SECTION: THE CAST & CHARACTERS (PROFIL MEMPELAI)
             ===================================================== --}}
        <section id="profil" class="reveal-item py-10 px-6 border-t border-white/10 space-y-8">
            <div class="text-center space-y-1">
                <span class="font-mono-film text-[10px] tracking-[0.3em] uppercase text-[#C5A059] font-bold">
                    FEATURING THE LEADING CAST
                </span>
                <h3 class="font-cinema-title text-3xl font-bold uppercase text-white">
                    Pemeran Utama
                </h3>
            </div>

            {{-- Opening Monologue Quote --}}
            <div class="p-4 rounded-xl bg-black/40 border-l-2 border-[#C5A059] font-cinema-title text-xs italic text-[#FAF7EF]/85 leading-relaxed">
                "{{ $quote }}"
            </div>

            {{-- Groom / Leading Man --}}
            <div class="p-4 rounded-2xl bg-[#141414] border border-white/15 space-y-3">
                <div class="flex items-center justify-between border-b border-white/10 pb-2 text-[9px] font-mono-film text-[#FAF7EF]/50">
                    <span>CAST NO. 01 • THE GROOM</span>
                    <span>35mm KODAK 400</span>
                </div>
                @if(!empty($invitation->event_data['groom_photo']))
                <div class="w-40 h-52 mx-auto rounded-xl overflow-hidden border border-[#C5A059]/60 shadow-lg">
                    <img src="{{ asset('storage/' . $invitation->event_data['groom_photo']) }}" alt="{{ $primaryName }}" loading="lazy" decoding="async" class="w-full h-full object-cover">
                </div>
                @endif
                <div>
                    <h4 class="font-cinema-title text-2xl font-bold text-white uppercase">{{ $primaryName }}</h4>
                    <p class="font-urban text-xs text-[#FAF7EF]/75 mt-0.5 leading-relaxed">{{ $primaryParents }}</p>
                </div>
                @if(!empty($primaryIg))
                <a href="https://instagram.com/{{ ltrim($primaryIg, '@') }}" target="_blank"
                   class="inline-flex items-center gap-1.5 font-mono-film text-[11px] text-[#C5A059] hover:underline">
                    <span>📷 {{ '@' . ltrim($primaryIg, '@') }}</span>
                </a>
                @endif
            </div>

            {{-- Bride / Leading Lady --}}
            <div class="p-4 rounded-2xl bg-[#141414] border border-white/15 space-y-3">
                <div class="flex items-center justify-between border-b border-white/10 pb-2 text-[9px] font-mono-film text-[#FAF7EF]/50">
                    <span>CAST NO. 02 • THE BRIDE</span>
                    <span>35mm FUJIFILM 400</span>
                </div>
                @if(!empty($invitation->event_data['bride_photo']))
                <div class="w-40 h-52 mx-auto rounded-xl overflow-hidden border border-[#C5A059]/60 shadow-lg">
                    <img src="{{ asset('storage/' . $invitation->event_data['bride_photo']) }}" alt="{{ $secondaryName }}" loading="lazy" decoding="async" class="w-full h-full object-cover">
                </div>
                @endif
                <div>
                    <h4 class="font-cinema-title text-2xl font-bold text-white uppercase">{{ $secondaryName }}</h4>
                    <p class="font-urban text-xs text-[#FAF7EF]/75 mt-0.5 leading-relaxed">{{ $secondaryParents }}</p>
                </div>
                @if(!empty($secondaryIg))
                <a href="https://instagram.com/{{ ltrim($secondaryIg, '@') }}" target="_blank"
                   class="inline-flex items-center gap-1.5 font-mono-film text-[11px] text-[#C5A059] hover:underline">
                    <span>📷 {{ '@' . ltrim($secondaryIg, '@') }}</span>
                </a>
                @endif
            </div>
        </section>


        {{-- =====================================================
             SECTION: PREMIERE ITINERARY & ADMISSION TICKETS
             ===================================================== --}}
        <section id="acara" class="reveal-item py-10 px-6 border-t border-white/10 space-y-6">
            <div class="text-center space-y-1">
                <span class="font-mono-film text-[10px] tracking-[0.3em] uppercase text-[#C5A059] font-bold">
                    EVENT SCREENING PASS
                </span>
                <h3 class="font-cinema-title text-3xl font-bold uppercase text-white">
                    Agenda &amp; Lokasi
                </h3>
            </div>

            {{-- Ticket 1: Sesi 1 --}}
            <div class="ticket-stub-mask p-5 shadow-2xl space-y-3 text-left">
                <div class="flex items-center justify-between border-b border-black/15 pb-2 text-[9px] font-mono-film text-black/60 uppercase">
                    <span>ADMISSION TICKET • SCENE 1</span>
                    <span>PREMIERE PASS</span>
                </div>
                <div>
                    <h4 class="font-cinema-title text-2xl font-bold text-black uppercase">
                        {{ $s1['title'] ?? 'Akad Nikah' }}
                    </h4>
                    <p class="font-mono-film text-xs text-[#8E6D30] font-bold mt-0.5">
                        {{ \Carbon\Carbon::parse($s1['date'] ?? now())->isoFormat('dddd, D MMMM Y') }}
                    </p>
                    <p class="font-grotesk text-xs text-black/80 font-medium">
                        Waktu: {{ $s1['time'] ?? '09:00 - 11:00 WIB' }}
                    </p>
                </div>
                <div class="pt-2 border-t border-dashed border-black/20 space-y-1">
                    <p class="font-grotesk text-xs font-bold text-black">
                        {{ $s1['venue'] ?? 'Lokasi Acara' }}
                    </p>
                    <p class="font-urban text-xs text-black/75 leading-relaxed">
                        {{ $s1['address'] ?? 'Alamat lengkap lokasi acara' }}
                    </p>
                </div>
                @if(!empty($s1['maps_url']))
                <div class="pt-2">
                    <a href="{{ $s1['maps_url'] }}" target="_blank"
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-black text-[#FAF7EF] text-xs font-grotesk uppercase tracking-wider font-semibold hover:bg-neutral-800 transition">
                        <span>Buka Google Maps</span>
                        <span>↗</span>
                    </a>
                </div>
                @endif
            </div>

            {{-- Ticket 2: Sesi 2 (Jika Ada) --}}
            @if(!empty($s2['enabled']) || !empty($s2['title']))
            <div class="ticket-stub-mask p-5 shadow-2xl space-y-3 text-left">
                <div class="flex items-center justify-between border-b border-black/15 pb-2 text-[9px] font-mono-film text-black/60 uppercase">
                    <span>ADMISSION TICKET • SCENE 2</span>
                    <span>AFTER-PARTY / RECEPTION</span>
                </div>
                <div>
                    <h4 class="font-cinema-title text-2xl font-bold text-black uppercase">
                        {{ $s2['title'] ?? 'Resepsi Pernikahan' }}
                    </h4>
                    <p class="font-mono-film text-xs text-[#8E6D30] font-bold mt-0.5">
                        {{ \Carbon\Carbon::parse($s2['date'] ?? ($s1['date'] ?? now()))->isoFormat('dddd, D MMMM Y') }}
                    </p>
                    <p class="font-grotesk text-xs text-black/80 font-medium">
                        Waktu: {{ $s2['time'] ?? '11:00 - Selesai' }}
                    </p>
                </div>
                <div class="pt-2 border-t border-dashed border-black/20 space-y-1">
                    <p class="font-grotesk text-xs font-bold text-black">
                        {{ $s2['venue'] ?? ($s1['venue'] ?? 'Lokasi Acara') }}
                    </p>
                    <p class="font-urban text-xs text-black/75 leading-relaxed">
                        {{ $s2['address'] ?? ($s1['address'] ?? 'Alamat lokasi resepsi') }}
                    </p>
                </div>
                @if(!empty($s2['maps_url']) || !empty($s1['maps_url']))
                <div class="pt-2">
                    <a href="{{ $s2['maps_url'] ?? $s1['maps_url'] }}" target="_blank"
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-black text-[#FAF7EF] text-xs font-grotesk uppercase tracking-wider font-semibold hover:bg-neutral-800 transition">
                        <span>Buka Google Maps</span>
                        <span>↗</span>
                    </a>
                </div>
                @endif
            </div>
            @endif

            {{-- Google Calendar Button --}}
            <div class="text-center pt-2">
                <a href="{{ $calUrl }}" target="_blank"
                   class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full border border-white/20 bg-black/60 font-grotesk text-xs text-[#C5A059] uppercase tracking-wider font-semibold hover:bg-white/10 transition">
                    <span>📅 Tambahkan ke Google Calendar</span>
                    <span>↗</span>
                </a>
            </div>
        </section>


        {{-- =====================================================
             SECTION: 35mm FILM STRIP REEL PHOTO GALLERY
             ===================================================== --}}
        @if(!empty($gallery) && count($gallery) > 0)
        <section id="galeri" class="reveal-item py-10 px-4 border-t border-white/10 space-y-6">
            <div class="text-center space-y-1 px-2">
                <span class="font-mono-film text-[10px] tracking-[0.3em] uppercase text-[#C5A059] font-bold">
                    NEGATIVE ROLL EXPOSURES
                </span>
                <h3 class="font-cinema-title text-3xl font-bold uppercase text-white">
                    Galeri Potret Film
                </h3>
            </div>

            {{-- Authentic Horizontal/Vertical Film Strip Grid --}}
            <div class="p-3 rounded-2xl bg-[#141414] border border-white/15 space-y-3">
                {{-- Top Sprocket Holes Ribbon --}}
                <div class="w-full h-5 sprocket-track-horizontal opacity-75"></div>

                {{-- Photo Grid with Film Frame Number Annotations --}}
                <div class="grid grid-cols-2 gap-3">
                    @foreach($gallery as $idx => $photo)
                    <button type="button"
                            aria-label="Buka foto galeri {{ $idx + 1 }}"
                            class="film-gallery-frame group relative rounded-xl overflow-hidden bg-black border border-white/15 cursor-pointer focus-visible:outline"
                            @click="openFilmPhoto('{{ asset('storage/' . $photo) }}')">
                        <div class="aspect-[4/5] w-full overflow-hidden">
                            <img src="{{ asset('storage/' . $photo) }}" alt="Potret pernikahan" loading="lazy"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 filter contrast-[1.05]">
                        </div>
                        <div class="absolute bottom-2 inset-x-2 flex items-center justify-between px-2 py-1 rounded bg-black/80 backdrop-blur-sm text-[8px] font-mono-film text-[#C5A059]">
                            <span>▶ 0{{ $idx + 1 }}</span>
                            <span>35mm EXP</span>
                        </div>
                    </button>
                    @endforeach
                </div>

                {{-- Bottom Sprocket Holes Ribbon --}}
                <div class="w-full h-5 sprocket-track-horizontal opacity-75"></div>
            </div>
        </section>
        @endif


        {{-- =====================================================
             SECTION: CALL SHEET RSVP FORM
             Styled as Production Call Sheet on Paper
             ===================================================== --}}
        <section id="ucapan" class="reveal-item py-10 px-6 border-t border-white/10 space-y-6">
            <div class="text-center space-y-1">
                <span class="font-mono-film text-[10px] tracking-[0.3em] uppercase text-[#C5A059] font-bold">
                    CALL SHEET CONFIRMATION
                </span>
                <h3 class="font-cinema-title text-3xl font-bold uppercase text-white">
                    RSVP &amp; Doa Restu
                </h3>
            </div>

            <div class="ticket-stub-mask p-5 shadow-2xl text-left space-y-4">
                <div class="flex items-center justify-between border-b border-black/15 pb-2 text-[9px] font-mono-film text-black/60 uppercase">
                    <span>CALL SHEET PROTOCOL</span>
                    <span>ATTENDANCE STATUS</span>
                </div>

                <form @submit.prevent="submitRsvp('{{ route('invitation.wish', $invitation->slug) }}')" class="space-y-3">
                    <div x-show="rsvpSuccessMsg" x-cloak class="p-2.5 rounded-lg bg-emerald-900/10 border border-emerald-600/40 text-emerald-800 text-xs font-semibold text-center">
                        <span x-text="rsvpSuccessMsg"></span>
                    </div>
                    <div x-show="rsvpErrorMsg" x-cloak class="p-2.5 rounded-lg bg-red-900/10 border border-red-600/40 text-red-800 text-xs font-semibold text-center">
                        <span x-text="rsvpErrorMsg"></span>
                    </div>

                    <div>
                        <label class="block text-[10px] font-mono-film uppercase tracking-wider text-black/70 mb-1 font-bold">Nama Tamu Undangan</label>
                        <input type="text" x-model="rsvpForm.guest_name" required
                               placeholder="Masukkan nama lengkap Anda..."
                               class="w-full px-3 py-2 rounded-lg bg-white border border-black/20 text-xs text-black focus:ring-1 focus:ring-black focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-[10px] font-mono-film uppercase tracking-wider text-black/70 mb-1 font-bold">Konfirmasi Kehadiran</label>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <label class="flex items-center justify-center p-2 rounded-lg border cursor-pointer font-grotesk font-semibold uppercase text-[11px] transition"
                                   :class="rsvpForm.attendance === 'hadir' ? 'bg-black text-[#FAF7EF] border-black' : 'bg-white text-black/70 border-black/20'">
                                <input type="radio" value="hadir" x-model="rsvpForm.attendance" class="sr-only">
                                <span>✓ Siap Hadir</span>
                            </label>
                            <label class="flex items-center justify-center p-2 rounded-lg border cursor-pointer font-grotesk font-semibold uppercase text-[11px] transition"
                                   :class="rsvpForm.attendance === 'tidak_hadir' ? 'bg-black text-[#FAF7EF] border-black' : 'bg-white text-black/70 border-black/20'">
                                <input type="radio" value="tidak_hadir" x-model="rsvpForm.attendance" class="sr-only">
                                <span>✗ Berhalangan</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-mono-film uppercase tracking-wider text-black/70 mb-1 font-bold">Doa &amp; Pesan untuk Mempelai</label>
                        <textarea x-model="rsvpForm.message" rows="3" required
                                  placeholder="Tuliskan ucapan dan doa hangat Anda di sini..."
                                  class="w-full px-3 py-2 rounded-lg bg-white border border-black/20 text-xs text-black focus:ring-1 focus:ring-black focus:outline-none"></textarea>
                    </div>

                    <button type="submit"
                            :disabled="rsvpSubmitting"
                            class="w-full py-2.5 px-4 rounded-lg bg-black text-[#FAF7EF] font-grotesk text-xs uppercase tracking-wider font-bold hover:bg-neutral-800 active:scale-95 transition disabled:opacity-50">
                        <span x-show="!rsvpSubmitting">Kirim Konfirmasi &amp; Doa</span>
                        <span x-show="rsvpSubmitting" x-cloak>Menyimpan data...</span>
                    </button>
                </form>
            </div>

            {{-- Guest Wishes Feed --}}
            <div class="space-y-3 pt-4">
                <div class="flex items-center justify-between text-xs font-mono-film text-[#FAF7EF]/60 border-b border-white/10 pb-2">
                    <span class="uppercase">FAN &amp; AUDIENCE REVIEWS</span>
                    <span x-text="counts.total + ' UCAPAN'"></span>
                </div>

                <div class="space-y-2.5 max-h-80 overflow-y-auto pr-1">
                    <template x-for="wish in wishes" :key="wish.id">
                        <div class="p-3.5 rounded-xl bg-[#141414] border border-white/10 space-y-1 text-left">
                            <div class="flex items-center justify-between">
                                <span class="font-grotesk font-bold text-white text-xs" x-text="wish.guest_name"></span>
                                <span :class="wish.attendance === 'hadir' ? 'text-emerald-400 border-emerald-500/30' : 'text-red-400 border-red-500/30'"
                                      class="px-2 py-0.5 rounded text-[9px] font-mono-film uppercase border"
                                      x-text="wish.attendance === 'hadir' ? 'Hadir' : 'Absen'"></span>
                            </div>
                            <p class="font-urban text-xs text-[#FAF7EF]/85 leading-relaxed" x-text="wish.message"></p>
                            <span class="block text-[9px] font-mono-film text-[#FAF7EF]/40 pt-1" x-text="wish.time_ago"></span>
                        </div>
                    </template>
                    <div x-show="wishes.length === 0" class="text-center py-6 text-xs font-mono-film text-[#FAF7EF]/40 italic">
                        Belum ada ulasan penonton. Jadilah yang pertama memberikan doa restu!
                    </div>
                </div>
            </div>
        </section>


        {{-- =====================================================
             SECTION: BOX OFFICE & DIGITAL GIFT
             ===================================================== --}}
        @if(!empty($gifts) && count($gifts) > 0)
        <section class="reveal-item py-10 px-6 border-t border-white/10 space-y-6">
            <div class="text-center space-y-1">
                <span class="font-mono-film text-[10px] tracking-[0.3em] uppercase text-[#C5A059] font-bold">
                    BOX OFFICE CONTRIBUTION
                </span>
                <h3 class="font-cinema-title text-3xl font-bold uppercase text-white">
                    Tanda Kasih Digital
                </h3>
            </div>

            <div class="space-y-3">
                @foreach($gifts as $idx => $g)
                <div class="p-5 rounded-2xl bg-gradient-to-br from-[#1E1E1E] via-[#161616] to-[#0E0E0E] border border-white/15 shadow-xl space-y-3 relative overflow-hidden text-left">
                    <div class="flex items-center justify-between border-b border-white/10 pb-2">
                        <span class="font-mono-film text-[10px] text-[#C5A059] uppercase tracking-wider font-bold">
                            {{ $g['bank'] ?? 'TRANSFER BANK' }}
                        </span>
                        <span class="font-mono-film text-[9px] text-[#FAF7EF]/40">VIP DIGITAL PASS</span>
                    </div>

                    <div>
                        <span class="font-mono-film text-[9px] text-[#FAF7EF]/50 uppercase tracking-widest block">NOMOR REKENING</span>
                        <span class="font-mono-film text-base sm:text-xl font-bold text-white tracking-widest block pt-0.5 break-all" id="bank-acc-{{ $idx }}">
                            {{ $g['account_number'] ?? '' }}
                        </span>
                        <p class="font-grotesk text-xs text-[#FAF7EF]/75 mt-0.5">
                            a.n. {{ $g['account_name'] ?? '' }}
                        </p>
                    </div>

                    <button type="button" @click="copyText('{{ $g['account_number'] ?? '' }}', {{ $idx }})"
                            class="min-h-[36px] w-full py-2 px-4 rounded-lg bg-[#FAF7EF] text-black font-grotesk text-xs uppercase tracking-wider font-bold hover:bg-white active:scale-95 transition flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                        </svg>
                        <span x-text="copiedIndex === {{ $idx }} ? 'Nomor Tersalin!' : 'Salin Nomor Rekening'">Salin Nomor Rekening</span>
                    </button>
                </div>
                @endforeach
            </div>
        </section>
        @endif


        {{-- =====================================================
             SECTION: END CREDITS (CREDITS ROLL)
             ===================================================== --}}
        <section class="reveal-item py-12 px-6 border-t border-white/10 text-center space-y-4">
            <span class="font-mono-film text-[10px] uppercase tracking-[0.35em] text-[#C5A059] font-bold">
                END CREDITS
            </span>

            <div class="space-y-3 font-grotesk text-xs text-[#FAF7EF]/70 max-w-xs mx-auto">
                <div>
                    <span class="font-mono-film text-[9px] uppercase tracking-wider text-[#FAF7EF]/40 block">DIRECTED BY</span>
                    <span class="text-white font-bold uppercase">LOVE &amp; DESTINY</span>
                </div>
                <div>
                    <span class="font-mono-film text-[9px] uppercase tracking-wider text-[#FAF7EF]/40 block">STARRING</span>
                    <span class="text-white font-bold uppercase">{{ $primaryName }} &amp; {{ $secondaryName }}</span>
                </div>
                <div>
                    <span class="font-mono-film text-[9px] uppercase tracking-wider text-[#FAF7EF]/40 block">SPECIAL THANKS</span>
                    <span>Seluruh Keluarga, Sahabat &amp; Para Tamu Terhormat</span>
                </div>
            </div>

            <div class="pt-6 border-t border-white/5">
                <p class="font-cinema-title text-base font-bold uppercase text-white">{{ $invitation->title }}</p>
                <p class="font-mono-film text-[10px] text-[#FAF7EF]/40 mt-1">© {{ $eventYear }} 35mm Film Roll Cinema Edition</p>
            </div>
        </section>
    </main>


    {{-- =====================================================
         FLOATING CINEMA DOCK CONTROLS (NAVIGASI MELAYANG)
         ===================================================== --}}
    <div class="fixed bottom-[max(0.75rem,env(safe-area-inset-bottom))] inset-x-0 mx-auto w-[92%] max-w-xs z-40 px-3 py-2 rounded-full bg-black/85 border border-white/20 backdrop-blur-md shadow-2xl flex items-center justify-around text-xs font-grotesk text-[#FAF7EF]/80"
         x-show="isOpen" x-cloak>
        <a href="#section-top" class="hover:text-white px-2 py-1">Top</a>
        <a href="#profil" class="hover:text-white px-2 py-1">Cast</a>
        <a href="#acara" class="hover:text-white px-2 py-1">Event</a>
        @if(!empty($gallery) && count($gallery) > 0)
        <a href="#galeri" class="hover:text-white px-2 py-1">Roll</a>
        @endif
        <a href="#ucapan" class="hover:text-white px-2 py-1">RSVP</a>
        <button type="button" @click="toggleMusic()" class="text-[#C5A059] font-mono-film font-bold px-2 py-1 flex items-center gap-1">
            <span :class="musicPlaying ? 'animate-reel-spin' : ''">♫</span>
            <span x-text="musicPlaying ? 'ON' : 'OFF'"></span>
        </button>
    </div>

    {{-- Lightbox Modal for Film Gallery --}}
    <div x-show="galleryModal" x-cloak
         role="dialog" aria-modal="true" aria-label="Pratinjau foto galeri"
         class="fixed inset-0 z-50 bg-black/95 flex items-center justify-center p-4"
         @click="galleryModal = false"
         @keydown.escape.window="galleryModal = false">
        <div class="max-w-xl max-h-[85vh] rounded-2xl overflow-hidden border border-white/20 relative" @click.stop>
            <img :src="activeGalleryPhoto" alt="Pratinjau foto galeri" class="w-full h-full object-contain">
            <button type="button" @click="galleryModal = false"
                    aria-label="Tutup pratinjau foto"
                    class="absolute top-3 right-3 w-11 h-11 rounded-full bg-black/75 text-white flex items-center justify-center border border-white/20 font-bold focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">&times;</button>
        </div>
    </div>

    @include('themes.partials.engine')
</body>
</html>
