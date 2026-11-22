<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $invitation->title }} | The Wedding of {{ $invitation->event_data['primary_name'] ?? 'Tasya' }} &amp; {{ $invitation->event_data['secondary_name'] ?? 'Zun' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Cormorant Garamond, Great Vibes, Poppins, Tenor Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Great+Vibes&family=Poppins:wght@300;400;500;600;700&family=Tenor+Sans&display=swap" rel="stylesheet">

    <!-- Tailwind CSS v4 & Alpine.js CDN -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }

        :root {
            --inv-bg: #0C0B0A;
            --inv-card: #151311;
            --inv-card-hover: #1C1916;
            --inv-gold: #D4AF37;
            --inv-gold-light: #F0D58A;
            --inv-gold-dark: #8F7220;
            --inv-text: #F6EFE6;
            --inv-muted: #A89C8A;
            --inv-border: rgba(212, 175, 55, 0.22);
        }

        .font-serif-cormorant { font-family: 'Cormorant Garamond', Georgia, serif; }
        .font-script-vibes    { font-family: 'Great Vibes', cursive; }
        .font-sans-poppins    { font-family: 'Poppins', system-ui, sans-serif; }
        .font-tenor           { font-family: 'Tenor Sans', serif; }

        /* ---- Custom Keyframes Modern Javanese Elegance ---- */
        @keyframes goyangan {
            0%   { transform: rotate(0deg); }
            28%  { transform: rotate(-14deg); }
            50%  { transform: rotate(0deg); }
            78%  { transform: rotate(14deg); }
            100% { transform: rotate(0deg); }
        }
        .anim-goyangan {
            animation: goyangan 3s linear infinite;
            transform-origin: bottom center;
        }

        @keyframes naikturun {
            0%   { transform: translateY(0px); }
            50%  { transform: translateY(-12px); }
            100% { transform: translateY(0px); }
        }
        .anim-naikturun {
            animation: naikturun 3s ease-in-out infinite;
        }

        @keyframes zoomSlow {
            0%   { transform: scale(0.97); }
            50%  { transform: scale(1.02); }
            100% { transform: scale(0.97); }
        }
        .anim-zoom {
            animation: zoomSlow 5s ease-in-out infinite;
        }

        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 15px rgba(212, 175, 55, 0.2); }
            50%      { box-shadow: 0 0 30px rgba(212, 175, 55, 0.45); }
        }
        .anim-glow {
            animation: pulseGlow 3.5s ease-in-out infinite;
        }

        /* Arch Frame Khas Wedding */
        .arch-frame {
            border-top-left-radius: 9999px;
            border-top-right-radius: 9999px;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
            overflow: hidden;
        }

        /* Cover & Content Fade Transition */
        #section-cover {
            transition: opacity 0.75s cubic-bezier(0.4, 0, 0.2, 1), transform 0.75s ease;
        }
        #section-cover.is-hidden {
            opacity: 0;
            transform: scale(1.03);
            pointer-events: none;
            display: none !important;
        }

        #section-konten {
            opacity: 0;
            transition: opacity 0.85s ease;
        }
        #section-konten.is-visible {
            opacity: 1;
        }

        /* Scroll reveal */
        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1), transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal.revealed {
            opacity: 1;
            transform: translateY(0);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #0C0B0A; }
        ::-webkit-scrollbar-thumb { background: #352F25; border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: #D4AF37; }
    </style>
</head>

@php
    $eventType = match ($invitation->category) {
        'pernikahan' => 'wedding',
        'tunangan'   => 'engagement',
        default      => $invitation->category
    };

    $primaryName     = $invitation->event_data['primary_name'] ?? 'Tasya';
    $secondaryName   = $invitation->event_data['secondary_name'] ?? 'Zun';
    $s1              = $invitation->event_data['session_1'] ?? [];
    $s2              = $invitation->event_data['session_2'] ?? [];
    $gallery         = $invitation->event_data['gallery_images'] ?? [];
    $gifts           = $invitation->event_data['gift_accounts'] ?? [];
    $greeting        = $invitation->event_data['greeting'] ?? 'Dengan Ridho dan Rahmat Allah SWT, kami bermaksud memberitahukan pernikahan kami :';
    $quote           = $invitation->event_data['quote'] ?? 'Dan di antara tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu isteri-isteri dari jenismu sendiri, supaya kamu cenderung dan merasa tenteram kepadanya, dan dijadikan-Nya diantaramu rasa kasih dan sayang. (Q.S. Ar-Rum: 21)';
    $coverImg        = $invitation->event_data['cover_image'] ?? '';
    $primaryParents  = $invitation->event_data['primary_title_parents'] ?? 'Putri Pertama dari Bapak Moh Isa Ismail & Ibu Mudholifah';
    $secondaryParents= $invitation->event_data['secondary_title_parents'] ?? 'Putra Pertama dari Bapak Imam Tohari & Ibu Dwi Mariam';
    $primaryIg       = $invitation->event_data['primary_instagram'] ?? 'svatasy';
    $secondaryIg     = $invitation->event_data['secondary_instagram'] ?? 'zun_afif';

    // Formatted event date
    $eventDateObj    = !empty($s1['date']) ? \Carbon\Carbon::parse($s1['date']) : now();
    $eventDateStr    = $eventDateObj->isoFormat('dddd, D MMMM Y');
    $eventDateShort  = $eventDateObj->format('d . m . Y');

    // Calendar Link
    $calDate  = str_replace('-', '', $s1['date'] ?? date('Y-m-d'));
    $calTimes = explode(' - ', $s1['time'] ?? '08:00 - 12:00');
    $calStart = str_pad(str_replace([':', ' ', 'WIB'], '', $calTimes[0] ?? '0800'), 4, '0');
    $calEnd   = isset($calTimes[1]) ? str_pad(str_replace([':', ' ', 'WIB'], '', $calTimes[1]), 4, '0') : '1200';
    $calUrl   = 'https://calendar.google.com/calendar/render?action=TEMPLATE'
        . '&text='     . urlencode($invitation->title)
        . '&dates='    . $calDate . 'T' . $calStart . '00Z/' . $calDate . 'T' . $calEnd . '00Z'
        . '&location=' . urlencode(($s1['venue'] ?? '') . ', ' . ($s1['address'] ?? ''));
@endphp

<body data-event-type="{{ $eventType }}"
      class="font-sans-poppins bg-[#080706] text-[#F6EFE6] antialiased selection:bg-[#D4AF37] selection:text-[#0C0B0A] overflow-x-hidden min-h-screen"
      x-data="{
          ...invitationEngine({
              musicMode: '{{ $invitation->event_data['music_mode'] ?? 'custom' }}',
              musicUrl: '{{ !empty($invitation->event_data['music_file']) ? asset('storage/' . $invitation->event_data['music_file']) : 'http://invitos.net/wp-content/uploads/2026/05/Feby-Putri-Bernaung-Lyrics.mp3' }}',
              guestName: {{ json_encode($guestName ?? 'Nour Haqiki') }},
              targetDate: '{{ $targetDate ?? $eventDateObj->format('Y-m-d H:i:s') }}',
              initialWishes: {{ json_encode($wishesData ?? []) }},
              initialCounts: {{ json_encode($initialCounts ?? ['total' => 0, 'hadir' => 0, 'tidak_hadir' => 0]) }}
          }),
          openingVideoEnded: false,
          skipOpening() {
              this.openingVideoEnded = true;
              document.getElementById('mempelai')?.scrollIntoView({ behavior: 'smooth' });
          },
          startOpeningVideo() {
              const video = document.getElementById('motion-video-player');
              if (video) {
                  video.currentTime = 0;
                  video.play().catch(() => {});
                  video.onended = () => {
                      this.openingVideoEnded = true;
                  };
              }
          }
      }"
      x-init="
          document.body.classList.add('overflow-hidden');
          $nextTick(() => {
              const obs = new IntersectionObserver((entries) => {
                  entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('revealed'); });
              }, { threshold: 0.15 });
              document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
          });
      ">

    {{-- =========================================================
         DESKTOP SPLIT WRAPPER:
         - Sisi Kiri: Sticky Fullscreen Hero Presentation (Desktop Only)
         - Sisi Kanan: Mobile Smartphone Frame Viewport
         ========================================================= --}}
    <div class="lg:flex lg:flex-row w-full min-h-screen">

        {{-- Sisi Kiri: Sticky Cover Sideleft (Khusus Layar Desktop) --}}
        <aside id="sideleft" class="hidden lg:flex lg:w-7/12 xl:w-8/12 min-h-screen sticky top-0 relative flex-col justify-between p-12 xl:p-16 overflow-hidden bg-cover bg-center border-r border-[#D4AF37]/20"
               style="background-image: url('{{ !empty($coverImg) ? asset('storage/' . $coverImg) : 'https://invitos.net/wp-content/uploads/2026/11/WhatsApp-Image-2026-11-05-at-11.11.25-1.jpeg' }}');">
            {{-- Dark Luxury Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/75 to-black/60 backdrop-blur-[2px]"></div>

            {{-- Top Tagline --}}
            <div class="relative z-10 space-y-2">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-[11px] font-tenor tracking-[0.3em] uppercase bg-[#D4AF37]/15 text-[#F0D58A] border border-[#D4AF37]/40">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37] animate-ping"></span>
                    Wedding Invitation
                </span>
                <p class="font-tenor text-xs tracking-[0.25em] uppercase text-stone-400 pt-2">The Official Wedding Of</p>
            </div>

            {{-- Center Names --}}
            <div class="relative z-10 max-w-xl space-y-4 my-auto">
                <h1 class="font-serif-cormorant text-6xl xl:text-7xl font-bold tracking-wide text-[#F6EFE6] leading-tight">
                    {{ $primaryName }}
                    <span class="font-script-vibes text-5xl xl:text-6xl text-[#D4AF37] block my-1 font-normal">&amp;</span>
                    {{ $secondaryName }}
                </h1>
                <p class="font-tenor text-sm tracking-[0.25em] text-[#D4AF37] uppercase">
                    {{ $eventDateStr }}
                </p>
                <p class="font-sans-poppins text-xs text-stone-300/80 leading-relaxed max-w-md pt-2">
                    "Dan di antara tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu isteri-isteri dari jenismu sendiri..." (Q.S. Ar-Rum: 21)
                </p>
            </div>

            {{-- Bottom Credit --}}
            <div class="relative z-10 flex items-center justify-between text-xs text-stone-400 font-tenor border-t border-white/10 pt-4">
                <span>Modern Javanese Elegance</span>
                <span>Exclusive Digital Experience</span>
            </div>
        </aside>

        {{-- Sisi Kanan: Mobile Invitation Container (Scrollable) --}}
        <div id="sideright" class="flex-1 min-h-screen bg-[#070706] flex justify-center items-start overflow-y-auto">

            <div class="w-full max-w-[430px] min-h-screen bg-[#0E0D0B] shadow-[0_0_60px_rgba(0,0,0,0.9)] border-x border-[#D4AF37]/15 relative overflow-x-hidden">

                {{-- =========================================================
                     1. COVER SCREEN TERKUNCI
                     ========================================================= --}}
                <div id="section-cover"
                     :class="isOpen ? 'is-hidden' : ''"
                     class="fixed lg:absolute inset-0 z-50 h-dvh w-full max-w-[430px] mx-auto overflow-hidden flex flex-col justify-between items-center text-center p-6 bg-gradient-to-b from-[#14120E] via-[#0E0D0B] to-[#080706] border-b border-[#D4AF37]/30">

                    {{-- Ornamen Gunungan Jogja Bagian Atas dengan Animasi Goyangan --}}
                    <div class="pt-4 flex flex-col items-center">
                        <div class="anim-goyangan w-24 h-20 flex items-center justify-center">
                            {{-- Gunungan Gold Ornament SVG / PNG --}}
                            <img src="https://invitos.net/wp-content/uploads/2026/02/Luxee-Jogja-11.png"
                                 alt="Gunungan Jogja"
                                 class="w-full h-full object-contain filter drop-shadow-[0_2px_8px_rgba(212,175,55,0.4)]"
                                 onerror="this.style.display='none'; document.getElementById('svg-gunungan').style.display='block';">
                            <svg id="svg-gunungan" class="w-16 h-16 text-[#D4AF37] hidden" viewBox="0 0 100 100" fill="currentColor">
                                <path d="M50 5 L88 85 L65 85 L50 48 L35 85 L12 85 Z"/>
                                <circle cx="50" cy="30" r="4" fill="#F6EFE6"/>
                            </svg>
                        </div>
                        <p class="font-tenor text-[11px] tracking-[0.35em] uppercase text-[#D4AF37] mt-1 font-semibold">
                            The Wedding Of
                        </p>
                    </div>

                    {{-- Foto Mempelai dalam Arch Frame --}}
                    <div class="my-auto w-full flex flex-col items-center space-y-4">
                        <div class="arch-frame w-48 h-64 p-1.5 bg-gradient-to-b from-[#D4AF37] via-[#8F7220] to-[#D4AF37]/30 shadow-[0_10px_30px_rgba(0,0,0,0.8)] relative">
                            <div class="w-full h-full arch-frame overflow-hidden bg-stone-900">
                                @if(!empty($coverImg))
                                    <img src="{{ asset('storage/' . $coverImg) }}" alt="{{ $invitation->title }}" class="w-full h-full object-cover">
                                @else
                                    <img src="https://invitos.net/wp-content/uploads/2026/11/WhatsApp-Image-2026-11-05-at-11.11.25-1.jpeg" alt="{{ $invitation->title }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            {{-- Sudut Ornamen Emas --}}
                            <div class="absolute -top-1.5 -left-1.5 w-6 h-6 border-t-2 border-l-2 border-[#D4AF37] rounded-tl-lg pointer-events-none"></div>
                            <div class="absolute -top-1.5 -right-1.5 w-6 h-6 border-t-2 border-r-2 border-[#D4AF37] rounded-tr-lg pointer-events-none"></div>
                        </div>

                        {{-- Nama Mempelai --}}
                        <div class="space-y-0.5">
                            <h2 class="font-serif-cormorant text-3xl font-bold tracking-wide text-[#F6EFE6]">
                                {{ $primaryName }}
                                <span class="font-script-vibes text-2xl text-[#D4AF37] mx-1 font-normal">&amp;</span>
                                {{ $secondaryName }}
                            </h2>
                        </div>

                        {{-- Kotak Penerima Undangan (Kepada YTH, ...) --}}
                        <div class="w-full max-w-[280px] p-3.5 rounded-2xl bg-white/[0.04] border border-[#D4AF37]/30 shadow-xl backdrop-blur-md text-center">
                            <p class="font-sans-poppins text-[10px] tracking-widest uppercase text-stone-400">Kepada Yth,</p>
                            <p class="font-serif-cormorant text-base font-bold text-[#F0D58A] mt-0.5 truncate">
                                {{ !empty($guestName) ? $guestName : 'Nour Haqiki' }}
                            </p>
                            @if(!empty($guestAddress))
                                <p class="font-sans-poppins text-[10px] text-stone-300/80 mt-1 truncate">
                                    di {{ $guestAddress }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Tombol Buka Undangan (ID: tombol-buka Sesuai Referensi Asli) --}}
                    <div class="pb-6 w-full max-w-[280px]">
                        <button id="tombol-buka"
                                @click="
                                    openInvitation();
                                    startOpeningVideo();
                                "
                                type="button"
                                class="w-full min-h-[48px] px-6 py-3 rounded-full font-sans-poppins font-semibold text-xs tracking-widest uppercase bg-gradient-to-r from-[#D4AF37] via-[#F0D58A] to-[#C9A35C] text-[#0C0B0A] shadow-[0_4px_20px_rgba(212,175,55,0.35)] hover:brightness-110 active:scale-95 transition flex items-center justify-center gap-2.5">
                            <svg class="w-4 h-4 text-[#0C0B0A]" viewBox="0 0 512 512" fill="currentColor">
                                <path d="M512 464c0 26.51-21.49 48-48 48H48c-26.51 0-48-21.49-48-48V200.724a48 48 0 0 1 18.387-37.776c24.913-19.529 45.501-35.365 164.2-121.511C199.412 29.17 232.797-.347 256 .003c23.198-.354 56.596 29.172 73.413 41.433 118.687 86.137 139.303 101.995 164.2 121.512A48 48 0 0 1 512 200.724V464zm-65.666-196.605c-2.563-3.728-7.7-4.595-11.428-2.033L256 389.308 79.094 265.362c-3.728-2.562-8.865-1.695-11.428 2.033l-19.554 28.455c-2.562 3.728-1.695 8.865 2.033 11.428L240.16 438.16a24.004 24.004 0 0 0 27.68 0l190.015-130.882c3.728-2.563 4.595-7.7 2.033-11.428l-19.554-28.455z"/>
                            </svg>
                            <span>Buka Undangan</span>
                        </button>
                    </div>
                </div>

                {{-- =========================================================
                     2. SECTION KONTEN (TERBUKA SETELAH TOMBOL BUKA DIKLIK)
                     ========================================================= --}}
                <div id="section-konten" :class="isOpen ? 'is-visible' : ''" class="relative w-full pb-28">

                    {{-- =========================================================
                         ANIMASI PEMBUKAAN: MOTION VIDEO HD LUXEE JOGJA
                         (Video Tirai Emas & Gerbang Jogja Pembuka)
                         ========================================================= --}}
                    <section id="motion-video" class="relative w-full h-dvh overflow-hidden flex flex-col justify-between items-center text-center p-6 bg-black">
                        {{-- Background Video Container --}}
                        <div class="absolute inset-0 w-full h-full z-0 overflow-hidden">
                            <video id="motion-video-player"
                                   playsinline
                                   muted
                                   loop
                                   preload="auto"
                                   class="w-full h-full object-cover scale-105 filter brightness-90">
                                <source src="https://invitos.id/wp-content/uploads/2026/06/HD-LUXEE-JOGJA-1.mp4" type="video/mp4">
                            </video>
                            {{-- Vignette Overlay Gelap Elegan --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-black/70"></div>
                        </div>

                        {{-- Overlay Content (Muncul Zoom-In Mengikuti Animasi Video) --}}
                        <div id="overlay-content" class="relative z-10 w-full max-w-sm my-auto space-y-5 px-4 animate-[zoomIn_1.2s_ease-out]">
                            {{-- Ornamen Atas --}}
                            <div class="anim-goyangan w-16 h-14 mx-auto">
                                <img src="https://invitos.net/wp-content/uploads/2026/02/Luxee-Jogja-11.png" alt="Ornamen" class="w-full h-full object-contain filter drop-shadow-[0_2px_10px_rgba(212,175,55,0.5)]">
                            </div>

                            <p class="font-tenor text-xs tracking-[0.35em] uppercase text-[#D4AF37]">The Wedding of</p>

                            <div class="space-y-1">
                                <h1 class="font-serif-cormorant text-5xl font-bold tracking-wide text-white drop-shadow-[0_2px_12px_rgba(0,0,0,0.8)]">
                                    {{ $primaryName }}
                                </h1>
                                <p class="font-script-vibes text-4xl text-[#F0D58A] my-1 font-normal">&amp;</p>
                                <h1 class="font-serif-cormorant text-5xl font-bold tracking-wide text-white drop-shadow-[0_2px_12px_rgba(0,0,0,0.8)]">
                                    {{ $secondaryName }}
                                </h1>
                            </div>

                            <p class="font-tenor text-sm tracking-[0.3em] uppercase text-[#F0D58A] font-semibold pt-2">
                                {{ $eventDateShort }}
                            </p>

                            {{-- Ayat Suci Ar-Rum 21 --}}
                            <div class="pt-4 px-2">
                                <p class="font-serif-cormorant text-xs italic text-stone-200 leading-relaxed max-w-xs mx-auto drop-shadow-[0_1px_4px_rgba(0,0,0,0.9)]">
                                    "Dan diantara tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu isteri-isteri dari jenismu sendiri, supaya kamu cenderung dan merasa tenteram kepadanya, dan dijadikan-Nya diantaramu rasa kasih dan sayang."
                                </p>
                                <p class="font-tenor text-[10px] tracking-widest uppercase text-[#D4AF37] mt-2 font-semibold">
                                    Q.S. Ar-Rum: 21
                                </p>
                            </div>
                        </div>

                        {{-- Tombol Scroll Turun / Lewati Video --}}
                        <div class="relative z-10 pb-6">
                            <button @click="skipOpening()"
                                    type="button"
                                    class="anim-naikturun inline-flex flex-col items-center gap-1 text-white/80 hover:text-[#D4AF37] transition">
                                <span class="text-[9px] font-tenor tracking-[0.25em] uppercase">Gulir ke Bawah</span>
                                <svg class="w-5 h-5 text-[#D4AF37]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                </svg>
                            </button>
                        </div>
                    </section>

                    {{-- =========================================================
                         3. SECTION KEDUA MEMPELAI (#mempelai)
                         ========================================================= --}}
                    <section id="mempelai" class="px-6 py-16 relative border-t border-[#D4AF37]/20 bg-[#0E0D0B] reveal">
                        <div class="text-center mb-12 space-y-2">
                            <span class="inline-block px-3.5 py-1 rounded-full text-[10px] font-tenor tracking-[0.25em] uppercase text-[#D4AF37] bg-[#D4AF37]/10 border border-[#D4AF37]/30">
                                Pasangan Pengantin
                            </span>
                            <h2 class="font-serif-cormorant text-3xl font-bold text-[#F6EFE6]">Kedua Mempelai</h2>
                            <p class="font-sans-poppins text-xs text-stone-400 max-w-xs mx-auto leading-relaxed pt-1">
                                {{ $greeting }}
                            </p>
                        </div>

                        {{-- Mempelai 1 (Bride) --}}
                        <div class="space-y-10">
                            <div class="p-6 rounded-3xl bg-gradient-to-b from-[#181512] to-[#110F0D] border border-[#D4AF37]/25 shadow-2xl text-center relative overflow-hidden">
                                {{-- Arch Frame Foto Mempelai 1 --}}
                                <div class="arch-frame w-36 h-48 mx-auto p-1 bg-gradient-to-b from-[#D4AF37] to-[#D4AF37]/20 shadow-xl mb-4">
                                    <div class="w-full h-full arch-frame overflow-hidden bg-stone-900">
                                        @if(!empty($coverImg))
                                            <img src="{{ asset('storage/' . $coverImg) }}" alt="{{ $primaryName }}" class="w-full h-full object-cover">
                                        @else
                                            <img src="https://invitos.net/wp-content/uploads/2026/11/WhatsApp-Image-2026-11-05-at-11.11.25-1.jpeg" alt="{{ $primaryName }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                </div>

                                <span class="text-[10px] font-tenor tracking-[0.3em] uppercase text-[#D4AF37] font-semibold block mb-1">
                                    The Bride
                                </span>
                                <h3 class="font-serif-cormorant text-2xl font-bold text-white mb-2">
                                    {{ $primaryName }}
                                </h3>
                                @if(!empty($primaryParents))
                                    <p class="font-sans-poppins text-xs text-stone-400 leading-relaxed px-4">
                                        {{ $primaryParents }}
                                    </p>
                                @endif

                                @if(!empty($primaryIg))
                                    <div class="mt-4 pt-4 border-t border-white/5">
                                        <a href="https://instagram.com/{{ ltrim($primaryIg, '@') }}" target="_blank" rel="noopener noreferrer"
                                           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs text-[#D4AF37] bg-[#D4AF37]/10 hover:bg-[#D4AF37]/20 border border-[#D4AF37]/30 transition">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                            <span>@ {{ ltrim($primaryIg, '@') }}</span>
                                        </a>
                                    </div>
                                @endif
                            </div>

                            {{-- Ampersand Center Separator --}}
                            @if(!empty($secondaryName))
                                <div class="text-center py-1">
                                    <span class="font-script-vibes text-4xl text-[#D4AF37]">&amp;</span>
                                </div>

                                {{-- Mempelai 2 (Groom) --}}
                                <div class="p-6 rounded-3xl bg-gradient-to-b from-[#181512] to-[#110F0D] border border-[#D4AF37]/25 shadow-2xl text-center relative overflow-hidden">
                                    {{-- Arch Frame Foto Mempelai 2 --}}
                                    <div class="arch-frame w-36 h-48 mx-auto p-1 bg-gradient-to-b from-[#D4AF37] to-[#D4AF37]/20 shadow-xl mb-4">
                                        <div class="w-full h-full arch-frame overflow-hidden bg-stone-900">
                                            <img src="https://invitos.net/wp-content/uploads/2026/11/WhatsApp-Image-2026-11-05-at-11.11.25-8.jpeg" alt="{{ $secondaryName }}" class="w-full h-full object-cover">
                                        </div>
                                    </div>

                                    <span class="text-[10px] font-tenor tracking-[0.3em] uppercase text-[#D4AF37] font-semibold block mb-1">
                                        The Groom
                                    </span>
                                    <h3 class="font-serif-cormorant text-2xl font-bold text-white mb-2">
                                        {{ $secondaryName }}
                                    </h3>
                                    @if(!empty($secondaryParents))
                                        <p class="font-sans-poppins text-xs text-stone-400 leading-relaxed px-4">
                                            {{ $secondaryParents }}
                                        </p>
                                    @endif

                                    @if(!empty($secondaryIg))
                                        <div class="mt-4 pt-4 border-t border-white/5">
                                            <a href="https://instagram.com/{{ ltrim($secondaryIg, '@') }}" target="_blank" rel="noopener noreferrer"
                                               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs text-[#D4AF37] bg-[#D4AF37]/10 hover:bg-[#D4AF37]/20 border border-[#D4AF37]/30 transition">
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                                <span>@ {{ ltrim($secondaryIg, '@') }}</span>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </section>

                    {{-- =========================================================
                         4. SECTION ACARA (#Acara) — WEDDING DAY
                         ========================================================= --}}
                    <section id="Acara" class="px-6 py-16 relative border-t border-[#D4AF37]/20 bg-[#0C0B0A] reveal">
                        <div class="text-center mb-10 space-y-2">
                            <span class="inline-block px-3.5 py-1 rounded-full text-[10px] font-tenor tracking-[0.25em] uppercase text-[#D4AF37] bg-[#D4AF37]/10 border border-[#D4AF37]/30">
                                Jadwal Pernikahan
                            </span>
                            <h2 class="font-serif-cormorant text-3xl font-bold text-[#F6EFE6]">Wedding Day</h2>
                            <p class="font-sans-poppins text-xs text-stone-400 max-w-xs mx-auto leading-relaxed pt-1">
                                Dengan mengucap puji dan syukur kepada Allah SWT, kami mengundang Anda untuk dapat menghadiri hari bahagia kami:
                            </p>
                        </div>

                        {{-- Countdown Timer --}}
                        <div class="p-5 rounded-2xl bg-[#14120F] border border-[#D4AF37]/30 shadow-2xl mb-10 text-center">
                            <p class="font-tenor text-[10px] tracking-[0.25em] uppercase text-[#D4AF37] mb-3 font-semibold">Hitung Mundur Acara</p>
                            <div class="grid grid-cols-4 gap-2">
                                <div class="bg-[#0A0908] rounded-xl p-2.5 border border-[#D4AF37]/20">
                                    <span class="block text-2xl font-bold font-serif-cormorant text-[#F0D58A]" x-text="countdown.days">00</span>
                                    <span class="text-[9px] uppercase tracking-wider text-stone-400">Hari</span>
                                </div>
                                <div class="bg-[#0A0908] rounded-xl p-2.5 border border-[#D4AF37]/20">
                                    <span class="block text-2xl font-bold font-serif-cormorant text-[#F0D58A]" x-text="countdown.hours">00</span>
                                    <span class="text-[9px] uppercase tracking-wider text-stone-400">Jam</span>
                                </div>
                                <div class="bg-[#0A0908] rounded-xl p-2.5 border border-[#D4AF37]/20">
                                    <span class="block text-2xl font-bold font-serif-cormorant text-[#F0D58A]" x-text="countdown.minutes">00</span>
                                    <span class="text-[9px] uppercase tracking-wider text-stone-400">Menit</span>
                                </div>
                                <div class="bg-[#0A0908] rounded-xl p-2.5 border border-[#D4AF37]/20">
                                    <span class="block text-2xl font-bold font-serif-cormorant text-[#F0D58A]" x-text="countdown.seconds">00</span>
                                    <span class="text-[9px] uppercase tracking-wider text-stone-400">Detik</span>
                                </div>
                            </div>

                            {{-- Tombol Simpan Kalender --}}
                            <div class="mt-4 pt-3 border-t border-white/5">
                                <a href="{{ $calUrl }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-medium bg-[#D4AF37]/15 hover:bg-[#D4AF37]/25 text-[#F0D58A] border border-[#D4AF37]/30 transition active:scale-95">
                                    <svg class="w-3.5 h-3.5 text-[#D4AF37]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Simpan ke Google Calendar</span>
                                </a>
                            </div>
                        </div>

                        {{-- Kartu Acara: Akad & Resepsi --}}
                        <div class="space-y-6">
                            {{-- Sesi 1: Akad Nikah --}}
                            <div class="p-6 rounded-3xl bg-gradient-to-b from-[#181512] to-[#100E0C] border border-[#D4AF37]/25 shadow-xl relative overflow-hidden">
                                <span class="inline-block px-3 py-1 rounded-full text-[9px] font-tenor tracking-widest uppercase bg-[#D4AF37]/20 text-[#D4AF37] font-semibold mb-3">
                                    Agenda Utama
                                </span>
                                <h3 class="font-serif-cormorant text-2xl font-bold text-white mb-4">
                                    {{ $s1['title'] ?? 'Akad Nikah' }}
                                </h3>
                                <div class="space-y-3 text-xs text-stone-300">
                                    <div>
                                        <p class="text-[10px] font-tenor uppercase tracking-widest text-[#D4AF37]">Hari &amp; Tanggal</p>
                                        <p class="font-semibold text-white mt-0.5">{{ \Carbon\Carbon::parse($s1['date'] ?? now())->isoFormat('dddd, D MMMM Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-tenor uppercase tracking-widest text-[#D4AF37]">Waktu</p>
                                        <p class="font-semibold text-white mt-0.5">{{ $s1['time'] ?? '08.00 WIB' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-tenor uppercase tracking-widest text-[#D4AF37]">Lokasi</p>
                                        <p class="font-semibold text-white mt-0.5">{{ $s1['venue'] ?? 'Hotel Ibis Budget Diponegoro' }}</p>
                                        <p class="text-stone-400 mt-0.5 leading-relaxed">{{ $s1['address'] ?? 'Jl. Diponegoro No.183, Darmo, Wonokromo, Surabaya' }}</p>
                                    </div>
                                </div>

                                @if(!empty($s1['maps_url']))
                                    <div class="mt-5 pt-4 border-t border-white/10">
                                        <a href="{{ $s1['maps_url'] }}" target="_blank" rel="noopener noreferrer"
                                           class="w-full min-h-[44px] inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold bg-[#D4AF37]/15 hover:bg-[#D4AF37]/25 text-[#F0D58A] border border-[#D4AF37]/35 transition active:scale-95">
                                            <svg class="w-4 h-4 text-[#D4AF37]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <span>Buka di Google Maps</span>
                                        </a>
                                    </div>
                                @endif
                            </div>

                            {{-- Sesi 2: Resepsi --}}
                            @if(!empty($s2['title']) || !empty($s2['enabled']))
                            <div class="p-6 rounded-3xl bg-gradient-to-b from-[#181512] to-[#100E0C] border border-[#D4AF37]/25 shadow-xl relative overflow-hidden">
                                <span class="inline-block px-3 py-1 rounded-full text-[9px] font-tenor tracking-widest uppercase bg-white/10 text-stone-300 font-semibold mb-3">
                                    Perayaan &amp; Resepsi
                                </span>
                                <h3 class="font-serif-cormorant text-2xl font-bold text-white mb-4">
                                    {{ $s2['title'] ?? 'Resepsi Pernikahan' }}
                                </h3>
                                <div class="space-y-3 text-xs text-stone-300">
                                    <div>
                                        <p class="text-[10px] font-tenor uppercase tracking-widest text-[#D4AF37]">Hari &amp; Tanggal</p>
                                        <p class="font-semibold text-white mt-0.5">{{ \Carbon\Carbon::parse($s2['date'] ?? now())->isoFormat('dddd, D MMMM Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-tenor uppercase tracking-widest text-[#D4AF37]">Waktu Sesi</p>
                                        <p class="font-semibold text-white mt-0.5">{{ $s2['time'] ?? '10.00 - 12.00 WIB' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-tenor uppercase tracking-widest text-[#D4AF37]">Lokasi</p>
                                        <p class="font-semibold text-white mt-0.5">{{ $s2['venue'] ?? 'Hotel Ibis Budget Diponegoro' }}</p>
                                        <p class="text-stone-400 mt-0.5 leading-relaxed">{{ $s2['address'] ?? 'Jl. Diponegoro No.183, Darmo, Wonokromo, Surabaya' }}</p>
                                    </div>
                                </div>

                                @if(!empty($s2['maps_url']))
                                    <div class="mt-5 pt-4 border-t border-white/10">
                                        <a href="{{ $s2['maps_url'] }}" target="_blank" rel="noopener noreferrer"
                                           class="w-full min-h-[44px] inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold bg-[#D4AF37]/15 hover:bg-[#D4AF37]/25 text-[#F0D58A] border border-[#D4AF37]/35 transition active:scale-95">
                                            <svg class="w-4 h-4 text-[#D4AF37]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <span>Buka di Google Maps</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                            @endif
                        </div>
                    </section>

                    {{-- =========================================================
                         5. SECTION GALERI (#galeri) — OUR MOMENTS
                         ========================================================= --}}
                    @if(!empty($gallery) && count($gallery) > 0)
                    <section id="galeri" class="px-6 py-16 relative border-t border-[#D4AF37]/20 bg-[#0E0D0B] reveal">
                        <div class="text-center mb-10 space-y-2">
                            <span class="inline-block px-3.5 py-1 rounded-full text-[10px] font-tenor tracking-[0.25em] uppercase text-[#D4AF37] bg-[#D4AF37]/10 border border-[#D4AF37]/30">
                                Dokumentasi
                            </span>
                            <h2 class="font-serif-cormorant text-3xl font-bold text-[#F6EFE6]">Our Moments</h2>
                            <p class="font-serif-cormorant text-xs italic text-stone-400 max-w-xs mx-auto leading-relaxed pt-1">
                                "A happy marriage is a long conversation which always seems too short."
                            </p>
                        </div>

                        {{-- Galeri Foto Grid Responsive dengan Lightbox --}}
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($gallery as $photo)
                                <div class="arch-frame aspect-[3/4] overflow-hidden bg-stone-900 border border-[#D4AF37]/30 shadow-lg cursor-pointer group"
                                     @click="openLightbox('{{ asset('storage/' . $photo) }}')">
                                    <img src="{{ asset('storage/' . $photo) }}" alt="Foto Momen" class="w-full h-full object-cover group-hover:scale-108 transition duration-700">
                                </div>
                            @endforeach
                        </div>
                    </section>
                    @endif

                    {{-- =========================================================
                         6. CERITA KITA (LOVE STORY)
                         ========================================================= --}}
                    <section id="cerita" class="px-6 py-14 relative border-t border-[#D4AF37]/20 bg-[#0C0B0A] reveal">
                        <div class="p-6 rounded-3xl bg-gradient-to-b from-[#181512] to-[#110F0D] border border-[#D4AF37]/25 shadow-xl text-center space-y-3">
                            <span class="text-[10px] font-tenor tracking-[0.3em] uppercase text-[#D4AF37] font-semibold">Love Story</span>
                            <h3 class="font-serif-cormorant text-2xl font-bold text-white">Cerita Kita</h3>
                            <div class="w-12 h-0.5 bg-[#D4AF37]/40 mx-auto"></div>
                            <p class="font-sans-poppins text-xs text-stone-300 leading-relaxed italic pt-2">
                                "Di antara ribuan wajah, aku menemukan tatapanmu. Tatapan yang sederhana, namun mampu membuat dunia terasa tenang. Perjalanan kita mungkin tak selalu mudah, tapi di setiap langkah, aku menemukan alasan untuk tetap bertahan. Kini, dengan penuh cinta, kita memilih untuk saling menggenggam, menjadikan cinta ini abadi dalam pernikahan."
                            </p>
                        </div>
                    </section>

                    {{-- =========================================================
                         7. SECTION RSVP & BUKU TAMU (#Wishes)
                         ========================================================= --}}
                    <section id="Wishes" class="px-6 py-16 relative border-t border-[#D4AF37]/20 bg-[#0E0D0B] reveal">
                        <div class="text-center mb-8 space-y-2">
                            <span class="inline-block px-3.5 py-1 rounded-full text-[10px] font-tenor tracking-[0.25em] uppercase text-[#D4AF37] bg-[#D4AF37]/10 border border-[#D4AF37]/30">
                                Konfirmasi Kehadiran
                            </span>
                            <h2 class="font-serif-cormorant text-3xl font-bold text-[#F6EFE6]">RSVP &amp; Doa Restu</h2>
                            <div class="flex items-center justify-center gap-3 pt-2 text-xs">
                                <span class="px-3 py-1 rounded-full bg-[#D4AF37]/15 text-[#F0D58A] border border-[#D4AF37]/30 font-medium">
                                    <strong x-text="counts.hadir">0</strong> Hadir
                                </span>
                                <span class="px-3 py-1 rounded-full bg-white/5 text-stone-400 border border-white/10 font-medium">
                                    <strong x-text="counts.total">0</strong> Total Pesan
                                </span>
                            </div>
                        </div>

                        {{-- Form RSVP AJAX --}}
                        <form @submit.prevent="submitRsvp('{{ route('invitation.wish', $invitation->slug) }}')" class="p-6 rounded-3xl bg-[#151310] border border-[#D4AF37]/25 shadow-xl mb-10 space-y-4">
                            <div x-show="rsvpSuccessMsg" x-cloak class="p-3 rounded-xl bg-emerald-950/70 border border-emerald-500/40 text-emerald-300 text-xs text-center font-medium">
                                <span x-text="rsvpSuccessMsg"></span>
                            </div>
                            <div x-show="rsvpErrorMsg" x-cloak class="p-3 rounded-xl bg-red-950/70 border border-red-500/40 text-red-300 text-xs text-center font-medium">
                                <span x-text="rsvpErrorMsg"></span>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-stone-300 mb-1">Nama Lengkap</label>
                                <input type="text" x-model="rsvpForm.guest_name" required placeholder="Nama Anda"
                                       class="w-full px-4 py-2.5 rounded-xl bg-[#0A0908] border border-stone-800 text-white text-xs focus:ring-2 focus:ring-[#D4AF37] focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-stone-300 mb-1">Konfirmasi Kehadiran</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl border border-stone-800 text-xs font-medium cursor-pointer transition"
                                           :class="rsvpForm.attendance === 'hadir' ? 'bg-[#D4AF37]/20 border-[#D4AF37] text-[#F0D58A]' : 'bg-[#0A0908] text-stone-400'">
                                        <input type="radio" value="hadir" x-model="rsvpForm.attendance" class="sr-only">
                                        <span>✓ Akan Hadir</span>
                                    </label>
                                    <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl border border-stone-800 text-xs font-medium cursor-pointer transition"
                                           :class="rsvpForm.attendance === 'tidak_hadir' ? 'bg-[#D4AF37]/20 border-[#D4AF37] text-[#F0D58A]' : 'bg-[#0A0908] text-stone-400'">
                                        <input type="radio" value="tidak_hadir" x-model="rsvpForm.attendance" class="sr-only">
                                        <span>✗ Tidak Bisa Hadir</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-stone-300 mb-1">Doa &amp; Ucapan</label>
                                <textarea x-model="rsvpForm.message" rows="3" required placeholder="Tuliskan ucapan dan doa terbaik Anda untuk kedua mempelai..."
                                          class="w-full px-4 py-2.5 rounded-xl bg-[#0A0908] border border-stone-800 text-white text-xs focus:ring-2 focus:ring-[#D4AF37] focus:outline-none"></textarea>
                            </div>

                            <button type="submit"
                                    :disabled="rsvpSubmitting"
                                    class="w-full min-h-[46px] py-3 rounded-xl font-sans-poppins font-semibold text-xs tracking-wider uppercase bg-gradient-to-r from-[#D4AF37] to-[#B38F22] text-[#0C0B0A] hover:brightness-110 active:scale-95 transition disabled:opacity-50 flex items-center justify-center gap-2">
                                <span x-show="!rsvpSubmitting">Kirimkan Ucapan</span>
                                <span x-show="rsvpSubmitting" x-cloak>Mengirim...</span>
                            </button>
                        </form>

                        {{-- Daftar Pesan Live --}}
                        <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
                            <template x-for="wish in wishes" :key="wish.id">
                                <div class="p-4 rounded-2xl bg-[#14120F] border border-stone-800/80 text-xs">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="font-bold text-white text-sm" x-text="wish.guest_name"></span>
                                        <span :class="wish.attendance === 'hadir' ? 'bg-[#D4AF37]/20 text-[#F0D58A] border border-[#D4AF37]/30' : 'bg-stone-800 text-stone-400'"
                                              class="px-2 py-0.5 rounded-full text-[10px] font-semibold"
                                              x-text="wish.attendance === 'hadir' ? 'Hadir' : 'Berhalangan'"></span>
                                    </div>
                                    <p class="text-stone-300 leading-relaxed" x-text="wish.message"></p>
                                    <span class="block text-[10px] text-stone-500 mt-2" x-text="wish.time_ago"></span>
                                </div>
                            </template>
                            <div x-show="wishes.length === 0" class="text-center py-6 text-xs text-stone-500 italic">
                                Belum ada doa &amp; ucapan yang tercatat. Jadilah yang pertama!
                            </div>
                        </div>
                    </section>

                    {{-- =========================================================
                         8. SECTION KIRIM HADIAH (#gift)
                         ========================================================= --}}
                    @if(!empty($gifts) && count($gifts) > 0)
                    <section id="gift" class="px-6 py-16 relative border-t border-[#D4AF37]/20 bg-[#0C0B0A] reveal">
                        <div class="text-center mb-10 space-y-2">
                            <span class="inline-block px-3.5 py-1 rounded-full text-[10px] font-tenor tracking-[0.25em] uppercase text-[#D4AF37] bg-[#D4AF37]/10 border border-[#D4AF37]/30">
                                Tanda Kasih
                            </span>
                            <h2 class="font-serif-cormorant text-3xl font-bold text-[#F6EFE6]">Kirim Hadiah</h2>
                            <p class="font-sans-poppins text-xs text-stone-400 max-w-xs mx-auto leading-relaxed pt-1">
                                Doa restu Anda merupakan karunia terindah bagi kami. Namun apabila Anda berkenan memberikan tanda kasih secara digital, silakan transfer melalui:
                            </p>
                        </div>

                        <div class="space-y-4">
                            @foreach($gifts as $index => $acc)
                            <div class="p-5 rounded-2xl bg-[#14120F] border border-[#D4AF37]/25 shadow-lg relative overflow-hidden">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold tracking-wider text-[#D4AF37] uppercase font-tenor">{{ $acc['bank'] }}</span>
                                    <svg class="w-5 h-5 text-[#D4AF37]/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </div>
                                <div class="font-mono text-xl font-bold text-white tracking-widest my-1">
                                    {{ $acc['account_number'] }}
                                </div>
                                <p class="text-xs text-stone-400 mb-4">a.n {{ $acc['account_name'] }}</p>

                                <button type="button"
                                        @click="copyText('{{ $acc['account_number'] }}', {{ $index }})"
                                        class="w-full min-h-[44px] px-4 py-2 rounded-xl text-xs font-semibold bg-[#D4AF37]/15 hover:bg-[#D4AF37]/25 text-[#F0D58A] border border-[#D4AF37]/30 transition active:scale-95 flex items-center justify-center gap-2">
                                    <svg class="w-3.5 h-3.5 text-[#D4AF37]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                    </svg>
                                    <span x-text="copiedIndex === {{ $index }} ? '✓ Nomor Rekening Tersalin!' : 'Salin Nomor Rekening'">Salin Nomor Rekening</span>
                                </button>
                            </div>
                            @endforeach
                        </div>
                    </section>
                    @endif

                    {{-- =========================================================
                         9. SECTION PENUTUP & TERIMA KASIH (FOOTER)
                         ========================================================= --}}
                    <footer class="px-6 pt-16 pb-12 text-center border-t border-[#D4AF37]/20 bg-gradient-to-b from-[#0E0D0B] to-[#070706] relative overflow-hidden">
                        {{-- Ornamen Gunungan Bawah --}}
                        <div class="anim-goyangan w-20 h-16 mx-auto mb-4">
                            <img src="https://invitos.net/wp-content/uploads/2026/02/Luxee-Jogja-11.png" alt="Gunungan" class="w-full h-full object-contain filter drop-shadow-[0_2px_8px_rgba(212,175,55,0.4)]">
                        </div>

                        <span class="text-[10px] font-tenor tracking-[0.3em] uppercase text-[#D4AF37] font-semibold block mb-1">
                            Keluarga &amp; Sahabat
                        </span>
                        <h2 class="font-serif-cormorant text-3xl font-bold text-white mb-3">Terima Kasih</h2>

                        <p class="font-sans-poppins text-xs text-stone-300 leading-relaxed max-w-xs mx-auto mb-6">
                            Merupakan suatu kebahagiaan dan kehormatan bagi kami, apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan do’a restu kepada kami.
                        </p>

                        <div class="space-y-1 mb-8">
                            <p class="text-[11px] font-tenor tracking-widest uppercase text-stone-400">Kami yang berbahagia,</p>
                            <h3 class="font-serif-cormorant text-2xl font-bold text-[#F0D58A]">
                                {{ $primaryName }} &amp; {{ $secondaryName }}
                            </h3>
                        </div>

                        <div class="border-t border-white/10 pt-6 text-[10px] text-stone-500 font-tenor tracking-widest uppercase space-y-1">
                            <p>{{ $invitation->title }}</p>
                            <p class="text-stone-600">Modern Javanese Elegance Wedding System</p>
                        </div>
                    </footer>

                </div> {{-- End of #section-konten --}}

            </div> {{-- End of Mobile Frame --}}

        </div> {{-- End of #sideright --}}

    </div> {{-- End of Desktop Split Wrapper --}}

    {{-- Include Shared Invitation Engine (Music Player, Bottom Nav, Toast, Lightbox) --}}
    @include('themes.partials.engine')

</body>
</html>
