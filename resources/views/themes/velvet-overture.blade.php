<!DOCTYPE html>
<html lang="id" class="scroll-smooth h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $invitation->title }} | The Wedding of {{ $invitation->event_data['primary_name'] ?? 'Arya' }} &amp; {{ $invitation->event_data['secondary_name'] ?? 'Laras' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Cinzel, Cormorant Garamond, Playfair Display & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700;800;900&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Alex+Brush&display=swap" rel="stylesheet">

    <!-- Tailwind CSS v4 & Alpine.js CDN -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }

        :root {
            --vo-velvet: #6B1D2F;
            --vo-velvet-dark: #3D0D17;
            --vo-velvet-deep: #22050B;
            --vo-gold: #C5A059;
            --vo-gold-light: #E8CF9B;
            --vo-gold-dark: #8E6D30;
            --vo-cream: #FAF5EE;
            --vo-parchment: #F4EBE0;
            --vo-text-dark: #2B181B;
            --vo-text-muted: #786366;
            --vo-olive: #52633E;
        }

        .font-cinzel    { font-family: 'Cinzel', serif; }
        .font-cormorant { font-family: 'Cormorant Garamond', Georgia, serif; }
        .font-playfair  { font-family: 'Playfair Display', Georgia, serif; }
        .font-script    { font-family: 'Alex Brush', cursive; }
        .font-sans      { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }

        /* ==========================================================
           THEATRICAL VELVET CURTAIN OPENING ANIMATIONS
           ========================================================== */
        #section-cover {
            transition: opacity 0.7s cubic-bezier(0.4, 0, 0.2, 1),
                        visibility 0.7s;
            will-change: opacity;
        }
        #section-cover.is-hidden {
            opacity: 0 !important;
            pointer-events: none !important;
            visibility: hidden !important;
            display: none !important;
        }

        /* Curtain Wings (Tirai Kiri & Kanan Beludru Penuh) */
        .curtain-wing-left {
            transform: translateX(0) scaleX(1);
            transform-origin: left center;
            transition: transform 1.5s cubic-bezier(0.77, 0, 0.175, 1);
            will-change: transform;
        }
        .curtain-wing-right {
            transform: translateX(0) scaleX(1);
            transform-origin: right center;
            transition: transform 1.5s cubic-bezier(0.77, 0, 0.175, 1);
            will-change: transform;
        }
        .curtains-opened .curtain-wing-left {
            transform: translateX(-104%) scaleX(0.75);
        }
        .curtains-opened .curtain-wing-right {
            transform: translateX(104%) scaleX(0.75);
        }

        /* Pelmet / Swag Curtain Atas */
        .curtain-swag-top {
            transform: translateY(0);
            transition: transform 1.25s cubic-bezier(0.77, 0, 0.175, 1);
            will-change: transform;
        }
        .curtains-opened .curtain-swag-top {
            transform: translateY(-110%);
        }

        /* Chandelier Ascending Upward */
        .curtain-chandelier {
            transform: translateY(0) scale(1);
            opacity: 1;
            transition: transform 1.35s cubic-bezier(0.4, 0, 0.2, 1),
                        opacity 0.95s ease;
            will-change: transform, opacity;
        }
        .curtains-opened .curtain-chandelier {
            transform: translateY(-160px) scale(0.85);
            opacity: 0;
            filter: drop-shadow(0 0 35px #E8CF9B);
        }

        /* Center Content Fade & Scale Dissolve */
        .cover-content {
            transform: scale(1) translateY(0);
            opacity: 1;
            filter: blur(0px);
            transition: transform 0.9s cubic-bezier(0.4, 0, 0.2, 1),
                        opacity 0.75s ease,
                        filter 0.75s ease;
            will-change: transform, opacity, filter;
        }
        .curtains-opened .cover-content {
            transform: scale(0.92) translateY(-25px);
            opacity: 0;
            filter: blur(6px);
            pointer-events: none;
        }

        /* Center Stage Light Bloom (Sorotan Lampu Panggung Emas Megah) */
        .stage-gold-glow {
            transform: scale(0.7);
            opacity: 0;
            transition: transform 1.4s cubic-bezier(0.16, 1, 0.3, 1),
                        opacity 1.1s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: transform, opacity;
        }
        .curtains-opened .stage-gold-glow {
            transform: scale(1.9);
            opacity: 0.95;
        }

        /* Main Invitation Entry Reveal */
        main#section-top {
            transform-origin: top center;
            transition: transform 1.3s cubic-bezier(0.16, 1, 0.3, 1),
                        opacity 1s cubic-bezier(0.16, 1, 0.3, 1);
        }
        body:not(.invitation-open) main#section-top {
            opacity: 0.35;
            transform: scale(0.96);
        }
        body.invitation-open main#section-top {
            opacity: 1;
            transform: scale(1);
        }

        /* Natural Scroll Reveal */
        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }
        .reveal.revealed {
            opacity: 1;
            transform: translateY(0);
        }

        /* Spinning Vinyl Record */
        @keyframes spinSlow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .animate-spin-slow {
            animation: spinSlow 12s linear infinite;
        }

        /* Chandelier & Candle Light Shimmer */
        @keyframes candleGlow {
            0%, 100% { opacity: 0.85; filter: drop-shadow(0 0 10px rgba(232, 207, 155, 0.45)); }
            50%      { opacity: 1; filter: drop-shadow(0 0 22px rgba(232, 207, 155, 0.8)); }
        }
        .animate-candle {
            animation: candleGlow 3.5s ease-in-out infinite;
        }

        /* Pulse Gold Ring */
        @keyframes pulseGold {
            0%, 100% { box-shadow: 0 0 0 0 rgba(197, 160, 89, 0.4); }
            50%      { box-shadow: 0 0 0 12px rgba(197, 160, 89, 0); }
        }
        .pulse-gold {
            animation: pulseGold 2.5s infinite;
        }

        /* Plaque with indented corners */
        .vintage-plaque {
            background-color: #FAF5EE;
            border: 2px solid #C5A059;
            box-shadow: 0 15px 35px -5px rgba(61, 13, 23, 0.12), inset 0 0 0 3px #FAF5EE, inset 0 0 0 4.5px #D4B67D;
            border-radius: 12px;
            position: relative;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #22050B; }
        ::-webkit-scrollbar-thumb { background: #6B1D2F; border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: #C5A059; }
    </style>
</head>

@php
    $eventType = match ($invitation->category) {
        'pernikahan' => 'wedding',
        'tunangan'   => 'engagement',
        default      => $invitation->category
    };

    $primaryName       = $invitation->event_data['primary_name'] ?? 'Arya';
    $secondaryName     = $invitation->event_data['secondary_name'] ?? 'Laras';
    $primaryFullName   = $invitation->event_data['primary_full_name'] ?? 'Arya Daniswara, S.T.';
    $secondaryFullName = $invitation->event_data['secondary_full_name'] ?? 'Laras Sekar Wangi, S.Ds.';
    $s1                = $invitation->event_data['session_1'] ?? [];
    $s2                = $invitation->event_data['session_2'] ?? [];
    $gallery           = $invitation->event_data['gallery_images'] ?? [];
    $gifts             = $invitation->event_data['gift_accounts'] ?? [];
    $greeting          = $invitation->event_data['greeting'] ?? 'Dengan memohon rahmat dan ridho Allah SWT, kami bermaksud menyelenggarakan acara pernikahan putra-putri kami:';
    $quote             = $invitation->event_data['quote'] ?? 'Dan di antara tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu isteri-isteri dari jenismu sendiri, supaya kamu cenderung dan merasa tenteram kepadanya, dan dijadikan-Nya diantaramu rasa kasih dan sayang. (Q.S. Ar-Rum: 21)';
    $coverImg          = $invitation->event_data['cover_image'] ?? '';
    $primaryParents    = $invitation->event_data['primary_title_parents'] ?? 'Putra pertama dari Bpk. Ir. Bambang Hermanto & Ibu Sri Wahyuni';
    $secondaryParents  = $invitation->event_data['secondary_title_parents'] ?? 'Putri kedua dari Bpk. Dr. H. Suryo Prabowo & Ibu Hj. Ratna Juwita';
    $primaryIg         = $invitation->event_data['primary_instagram'] ?? 'aryadanis';
    $secondaryIg       = $invitation->event_data['secondary_instagram'] ?? 'larassekar';

    // Tanggal Acara
    $eventDateObj      = !empty($s1['date']) ? \Carbon\Carbon::parse($s1['date']) : now()->addMonths(2);
    $eventDateFormatted= $eventDateObj->isoFormat('dddd, D MMMM Y');
    $eventDateNumerics = $eventDateObj->format('d / m / Y');
    $eventMonthYear    = $eventDateObj->isoFormat('MMMM Y');
    $eventDayNumber    = (int) $eventDateObj->format('d');
    $daysInMonth       = $eventDateObj->daysInMonth;
    $firstDayOfWeek    = $eventDateObj->copy()->startOfMonth()->dayOfWeekIso; // 1 (Mon) - 7 (Sun)

    // Google Calendar Link
    $calDate  = str_replace('-', '', $s1['date'] ?? date('Y-m-d'));
    $calTimes = explode(' - ', $s1['time'] ?? '15:00 - 20:00');
    $calStart = str_pad(str_replace([':', ' ', 'WIB'], '', $calTimes[0] ?? '1500'), 4, '0');
    $calEnd   = isset($calTimes[1]) ? str_pad(str_replace([':', ' ', 'WIB'], '', $calTimes[1]), 4, '0') : '2000';
    $calUrl   = 'https://calendar.google.com/calendar/render?action=TEMPLATE'
        . '&text='     . urlencode('The Wedding of ' . $primaryName . ' & ' . $secondaryName)
        . '&dates='    . $calDate . 'T' . $calStart . '00Z/' . $calDate . 'T' . $calEnd . '00Z'
        . '&location=' . urlencode(($s1['venue'] ?? 'Gedung Pertemuan') . ', ' . ($s1['address'] ?? ''));

    // Timeline Itinerary
    $timelineItems = [
        [
            'time'  => !empty($s1['time']) ? explode(' - ', $s1['time'])[0] : '15:00',
            'title' => 'Kedatangan Tamu',
            'desc'  => 'Penyambutan tamu undangan & pengisian buku tamu',
            'type'  => 'hourglass'
        ],
        [
            'time'  => !empty($s1['time']) ? $s1['time'] : '16:00',
            'title' => $s1['name'] ?? 'Akad Nikah',
            'desc'  => 'Prosesi ijab qabul & doa keberkahan mempelai',
            'type'  => 'chest'
        ],
        [
            'time'  => !empty($s2['time']) ? $s2['time'] : '17:30 - 20:00',
            'title' => $s2['name'] ?? 'Resepsi Pernikahan',
            'desc'  => 'Ramah tamah, santap malam & perayaan bersama',
            'type'  => 'flute'
        ],
        [
            'time'  => '20:00',
            'title' => 'Penutupan Acara',
            'desc'  => 'Sesi foto bersama keluarga & kepulangan tamu',
            'type'  => 'fan'
        ]
    ];
@endphp

<body data-event-type="{{ $eventType }}"
      class="font-sans bg-[#1B0509] text-[#2B181B] min-h-screen overflow-x-hidden antialiased selection:bg-[#6B1D2F] selection:text-[#FAF5EE]"
      x-data="{
          ...invitationEngine({
              musicMode: '{{ $invitation->event_data['music_mode'] ?? 'ambient' }}',
              musicUrl: '{{ !empty($invitation->event_data['music_file']) ? asset('storage/' . $invitation->event_data['music_file']) : '' }}',
              guestName: {{ json_encode($guestName ?? 'Tamu Terhormat') }},
              guestAddress: {{ json_encode($guestAddress ?? '') }},
              targetDate: '{{ $targetDate ?? $eventDateObj->format('Y-m-d H:i:s') }}',
              initialWishes: {{ json_encode($wishesData ?? []) }},
              initialCounts: {{ json_encode($initialCounts ?? ['total' => 0, 'hadir' => 0, 'tidak_hadir' => 0]) }}
          }),
          showMusicToast: false,
          activeTab: 'mempelai',
          curtainOpened: false,
          isOpening: false,
          openWithToast() {
              if (this.isOpening) return;
              this.isOpening = true;
              this.playMusic(0);

              // 1. Pemicu terbukanya tirai teater beludru megah
              this.curtainOpened = true;

              // 2. Waktu animasi tirai tersibak (1.35 detik) lalu panggung utama terbuka mulus
              setTimeout(() => {
                  this.openInvitation();
                  this.showMusicToast = true;
                  setTimeout(() => { this.showMusicToast = false; }, 6000);
              }, 1350);
          }
      }"
      x-init="
          document.body.classList.add('overflow-hidden');
          $nextTick(() => {
              const obs = new IntersectionObserver((entries) => {
                  entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('revealed'); });
              }, { threshold: 0.12 });
              document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
          });
      ">

    {{-- DESKTOP AMBIENT BACKDROP (LUXURY THEATER EFFECT) --}}
    <div class="fixed inset-0 pointer-events-none opacity-40 hidden md:block" style="background: radial-gradient(circle at 50% 30%, #521422 0%, #1A0408 85%);"></div>

    {{-- =========================================================
         1. COVER SCREEN TERKUNCI (#section-cover)
         Desain Tirai Beludru Merah (Velvet Drapes) & Crystal Chandelier
         Sesuai Layar 1 Referensi Velvet Overture
         ========================================================= --}}
    <div id="section-cover"
         :class="{ 'curtains-opened': curtainOpened, 'is-hidden': isOpen }"
         class="fixed inset-0 z-50 h-dvh w-full overflow-hidden flex flex-col justify-between items-center text-center bg-[#23060C] text-[#FAF5EE]">

        {{-- Background Deep Velvet Lighting --}}
        <div class="absolute inset-0 bg-gradient-to-b from-[#3D0B16]/95 via-[#22050B] to-[#120205] z-0"></div>

        {{-- Golden Stage Light Flare (Bersinar lembut saat tirai tersibak) --}}
        <div class="stage-gold-glow absolute inset-0 z-10 pointer-events-none flex items-center justify-center">
            <div class="w-96 h-96 sm:w-[540px] sm:h-[540px] rounded-full bg-radial from-[#F4DFB0]/35 via-[#C5A059]/15 to-transparent blur-3xl"></div>
        </div>

        {{-- Tirai Kiri Beludru Penuh (Curtain Left Wing) --}}
        <div class="curtain-wing-left absolute inset-y-0 left-0 w-[55%] z-20 pointer-events-none overflow-hidden shadow-[8px_0_30px_rgba(0,0,0,0.7)]"
             style="background: linear-gradient(90deg, #1A0408 0%, #350811 18%, #5A1624 40%, #3D0B16 62%, #6B1D2F 82%, #26050C 100%);">
            <svg class="h-full w-full object-cover opacity-50 mix-blend-overlay" viewBox="0 0 240 800" preserveAspectRatio="none" fill="none">
                <path d="M0,0 C60,0 90,60 70,250 C50,450 110,650 60,800 L0,800 Z" fill="#8C2B3F"/>
                <path d="M80,0 C140,0 170,80 150,300 C130,500 190,680 130,800 L80,800 Z" fill="#20040A" opacity="0.8"/>
                <path d="M160,0 C220,0 240,100 220,350 C200,550 240,700 200,800 L160,800 Z" fill="#8C2B3F" opacity="0.6"/>
            </svg>
            {{-- Rumbai & Lis Emas Tepi Pertemuan Tirai --}}
            <div class="absolute inset-y-0 right-0 w-3.5 bg-gradient-to-r from-transparent via-[#C5A059]/40 to-[#E8CF9B] border-r-2 border-[#E8CF9B] shadow-[2px_0_12px_rgba(232,207,155,0.7)] flex flex-col justify-around py-8 opacity-90">
                <div class="w-1.5 h-8 rounded-full bg-[#E8CF9B] mx-auto shadow-sm"></div>
                <div class="w-2 h-14 rounded-full bg-[#C5A059] mx-auto shadow-sm"></div>
                <div class="w-1.5 h-8 rounded-full bg-[#E8CF9B] mx-auto shadow-sm"></div>
            </div>
        </div>

        {{-- Tirai Kanan Beludru Penuh (Curtain Right Wing) --}}
        <div class="curtain-wing-right absolute inset-y-0 right-0 w-[55%] z-20 pointer-events-none overflow-hidden shadow-[-8px_0_30px_rgba(0,0,0,0.7)]"
             style="background: linear-gradient(270deg, #1A0408 0%, #350811 18%, #5A1624 40%, #3D0B16 62%, #6B1D2F 82%, #26050C 100%);">
            <svg class="h-full w-full object-cover opacity-50 mix-blend-overlay scale-x-[-1]" viewBox="0 0 240 800" preserveAspectRatio="none" fill="none">
                <path d="M0,0 C60,0 90,60 70,250 C50,450 110,650 60,800 L0,800 Z" fill="#8C2B3F"/>
                <path d="M80,0 C140,0 170,80 150,300 C130,500 190,680 130,800 L80,800 Z" fill="#20040A" opacity="0.8"/>
                <path d="M160,0 C220,0 240,100 220,350 C200,550 240,700 200,800 L160,800 Z" fill="#8C2B3F" opacity="0.6"/>
            </svg>
            {{-- Rumbai & Lis Emas Tepi Pertemuan Tirai --}}
            <div class="absolute inset-y-0 left-0 w-3.5 bg-gradient-to-l from-transparent via-[#C5A059]/40 to-[#E8CF9B] border-l-2 border-[#E8CF9B] shadow-[-2px_0_12px_rgba(232,207,155,0.7)] flex flex-col justify-around py-8 opacity-90">
                <div class="w-1.5 h-8 rounded-full bg-[#E8CF9B] mx-auto shadow-sm"></div>
                <div class="w-2 h-14 rounded-full bg-[#C5A059] mx-auto shadow-sm"></div>
                <div class="w-1.5 h-8 rounded-full bg-[#E8CF9B] mx-auto shadow-sm"></div>
            </div>
        </div>

        {{-- Top Valance Pelmet Drapery --}}
        <div class="curtain-swag-top absolute top-0 inset-x-0 h-16 sm:h-20 z-30 pointer-events-none overflow-hidden">
            <svg viewBox="0 0 800 100" preserveAspectRatio="none" class="w-full h-full text-[#4B0F1C]" fill="currentColor">
                <defs>
                    <linearGradient id="valanceGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#24040A"/>
                        <stop offset="60%" stop-color="#551221"/>
                        <stop offset="100%" stop-color="#6B1D2F"/>
                    </linearGradient>
                </defs>
                <path d="M0,0 L800,0 L800,35 Q700,85 600,35 Q500,85 400,35 Q300,85 200,35 Q100,85 0,35 Z" fill="url(#valanceGrad)"/>
                <path d="M0,35 Q100,85 200,35 Q300,85 400,35 Q500,85 600,35 Q700,85 800,35" stroke="#C5A059" stroke-width="2.5" fill="none"/>
                <circle cx="200" cy="37" r="3.5" fill="#E8CF9B"/>
                <circle cx="400" cy="37" r="3.5" fill="#E8CF9B"/>
                <circle cx="600" cy="37" r="3.5" fill="#E8CF9B"/>
            </svg>
        </div>

        {{-- Crystal Chandelier (Naik ke atas plafon teater saat tirai terbuka) --}}
        <div class="curtain-chandelier absolute top-1 inset-x-0 mx-auto z-35 flex flex-col items-center pointer-events-none">
            <div class="animate-candle -mt-2 w-44 sm:w-52 h-36 flex items-center justify-center">
                <svg viewBox="0 0 160 120" class="w-full h-full text-[#E8CF9B]" fill="currentColor">
                    <!-- Chain & Top Ring -->
                    <line x1="80" y1="0" x2="80" y2="28" stroke="#C5A059" stroke-width="2"/>
                    <circle cx="80" cy="30" r="4" fill="#C5A059"/>
                    <!-- Chandelier Arms -->
                    <path d="M80,45 C55,45 35,60 30,75 C25,90 40,95 50,85 C60,75 75,70 80,70 C85,70 100,75 110,85 C120,95 135,90 130,75 C125,60 105,45 80,45 Z" fill="none" stroke="#C5A059" stroke-width="2"/>
                    <path d="M80,35 C65,35 50,45 45,55 C65,65 95,65 115,55 C110,45 95,35 80,35 Z" fill="none" stroke="#E8CF9B" stroke-width="1.5"/>
                    <!-- Candles -->
                    <rect x="28" y="65" width="4" height="12" fill="#FAF5EE"/>
                    <rect x="48" y="58" width="4" height="12" fill="#FAF5EE"/>
                    <rect x="78" y="54" width="4" height="12" fill="#FAF5EE"/>
                    <rect x="108" y="58" width="4" height="12" fill="#FAF5EE"/>
                    <rect x="128" y="65" width="4" height="12" fill="#FAF5EE"/>
                    <!-- Flame Drops -->
                    <path d="M30,62 Q32,57 30,55 Q28,57 30,62" fill="#FFD166"/>
                    <path d="M50,55 Q52,50 50,48 Q48,50 50,55" fill="#FFD166"/>
                    <path d="M80,51 Q82,46 80,44 Q78,46 80,51" fill="#FFD166"/>
                    <path d="M110,55 Q112,50 110,48 Q108,50 110,55" fill="#FFD166"/>
                    <path d="M130,62 Q132,57 130,55 Q128,57 130,62" fill="#FFD166"/>
                    <!-- Crystal Prisms & Pendants -->
                    <polygon points="80,72 83,86 80,95 77,86" fill="#F4EBE0" opacity="0.9"/>
                    <polygon points="50,88 52,98 50,105 48,98" fill="#F4EBE0" opacity="0.8"/>
                    <polygon points="110,88 112,98 110,105 108,98" fill="#F4EBE0" opacity="0.8"/>
                    <polygon points="30,78 32,88 30,94 28,88" fill="#F4EBE0" opacity="0.7"/>
                    <polygon points="130,78 132,88 130,94 128,88" fill="#F4EBE0" opacity="0.7"/>
                    <circle cx="80" cy="100" r="3" fill="#C5A059"/>
                </svg>
            </div>
        </div>

        {{-- Center Content: Title, Names, Date & Guest Card --}}
        <div class="cover-content relative z-25 my-auto w-full max-w-sm px-6 flex flex-col items-center space-y-5">
            {{-- Wedding Label --}}
            <div class="flex items-center gap-3">
                <span class="w-8 h-[1px] bg-gradient-to-r from-transparent to-[#C5A059]"></span>
                <span class="font-cinzel text-[11px] tracking-[0.35em] uppercase text-[#E8CF9B] font-semibold">
                    The Wedding Of
                </span>
                <span class="w-8 h-[1px] bg-gradient-to-l from-transparent to-[#C5A059]"></span>
            </div>

            {{-- Couple Names in Serif --}}
            <div class="space-y-1">
                <h1 class="font-cinzel text-4xl sm:text-5xl font-bold tracking-tight text-[#FAF5EE] drop-shadow-md">
                    {{ $primaryName }}
                </h1>
                <div class="font-script text-3xl sm:text-4xl text-[#E8CF9B] my-1 font-normal">&amp;</div>
                <h1 class="font-cinzel text-4xl sm:text-5xl font-bold tracking-tight text-[#FAF5EE] drop-shadow-md">
                    {{ $secondaryName }}
                </h1>
            </div>

            {{-- Wedding Date --}}
            <div class="pt-1">
                <p class="font-cormorant text-sm sm:text-base tracking-[0.3em] uppercase text-[#E8CF9B] font-medium border-y border-[#C5A059]/30 py-1 px-4 inline-block">
                    {{ $eventDateNumerics }}
                </p>
            </div>

            {{-- Guest Card Box (Parchment & Gold Trim) --}}
            <div class="w-full max-w-[290px] p-4.5 rounded-2xl bg-[#1A0409]/85 border border-[#C5A059]/40 shadow-[0_12px_40px_rgba(0,0,0,0.6)] backdrop-blur-md text-center space-y-1.5 relative overflow-hidden">
                <div class="absolute top-0 inset-x-0 h-[2px] bg-gradient-to-r from-transparent via-[#C5A059] to-transparent"></div>
                <p class="text-[9px] uppercase tracking-[0.25em] text-[#C5A059] font-medium">Kepada Yth. Bapak/Ibu/Saudara/i</p>
                <p class="font-playfair text-xl font-bold text-[#FAF5EE] truncate pt-0.5">
                    {{ !empty($guestName) ? $guestName : 'Tamu Undangan' }}
                </p>
                @if(!empty($guestAddress))
                    <p class="font-sans text-xs text-[#E8CF9B]/90 truncate">
                        di {{ $guestAddress }}
                    </p>
                @endif
                <p class="text-[8.5px] text-[#FAF5EE]/60 italic pt-1 leading-tight">
                    *Mohon maaf jika ada kesalahan penulisan nama atau gelar
                </p>
            </div>
        </div>

        {{-- Bottom Table Decor & Buka Undangan Button --}}
        <div class="cover-content relative z-25 w-full pb-8 pt-4 flex flex-col items-center space-y-4">
            {{-- Button Buka Undangan --}}
            <button @click="openWithToast()"
                    type="button"
                    class="group relative inline-flex items-center gap-2.5 px-7 py-3 rounded-full text-xs uppercase tracking-[0.2em] font-cinzel font-semibold bg-gradient-to-r from-[#FAF5EE] via-[#F4EBE0] to-[#E8CF9B] text-[#3D0D17] shadow-[0_8px_30px_rgba(197,160,89,0.45)] border border-[#C5A059] hover:scale-105 active:scale-95 transition-all duration-300 pulse-gold">
                <svg class="w-4 h-4 text-[#6B1D2F] transition-transform duration-300 group-hover:rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>Buka Undangan</span>
            </button>

            {{-- Decorative Floral & Candle Banquet Silhouette at Bottom --}}
            <div class="w-full max-w-sm px-6 pt-2 flex items-center justify-center opacity-70">
                <div class="h-[1px] flex-1 bg-gradient-to-r from-transparent to-[#C5A059]/40"></div>
                <div class="px-3 flex items-center gap-1.5 text-[#C5A059] text-xs">
                    <span>❦</span>
                    <span class="text-[9px] tracking-widest font-cinzel uppercase text-[#E8CF9B]/80">Velvet Overture</span>
                    <span>❦</span>
                </div>
                <div class="h-[1px] flex-1 bg-gradient-to-l from-transparent to-[#C5A059]/40"></div>
            </div>
        </div>
    </div>


    {{-- =========================================================
         2. MAIN INVITATION CONTENT
         Mobile-First Container styled like luxury editorial layout
         Matching Velvet Overture Visual Palette & Components
         ========================================================= --}}
    <main id="section-top"
          class="relative w-full max-w-lg mx-auto bg-[#FAF5EE] text-[#2B181B] min-h-screen shadow-[0_0_60px_rgba(0,0,0,0.45)] pb-28">

        {{-- Toast Audio Started --}}
        <div x-show="showMusicToast"
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-3"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-2"
             class="fixed top-5 inset-x-0 mx-auto max-w-xs z-50 px-4 py-2.5 rounded-full bg-[#3D0D17]/95 border border-[#C5A059] text-[#FAF5EE] text-xs shadow-2xl flex items-center justify-between gap-3 backdrop-blur-md">
            <div class="flex items-center gap-2 truncate">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                <span class="truncate">♫ Musik latar romantis diputar</span>
            </div>
            <button @click="showMusicToast = false" class="text-[#E8CF9B] hover:text-white text-sm font-bold">&times;</button>
        </div>


        {{-- =========================================================
             SECTION: HERO & TOP FLOWER ORNAMENT
             ========================================================= --}}
        <section class="relative pt-12 pb-6 px-6 text-center overflow-hidden">
            {{-- Cascading Velvet Rose Floral Top Header --}}
            <div class="relative w-full flex flex-col items-center mb-6">
                {{-- Decorative Vintage Arch Ribbon --}}
                <div class="flex items-center gap-3 mb-2">
                    <span class="w-12 h-[1px] bg-[#C5A059]"></span>
                    <span class="font-cinzel text-[10px] tracking-[0.3em] uppercase text-[#6B1D2F] font-semibold">
                        The Wedding Celebration
                    </span>
                    <span class="w-12 h-[1px] bg-[#C5A059]"></span>
                </div>

                {{-- Names in Burgundy Serif --}}
                <h2 class="font-cinzel text-4xl sm:text-5xl font-bold text-[#6B1D2F] tracking-tight">
                    {{ $primaryName }}
                </h2>
                <div class="font-script text-3xl sm:text-4xl text-[#C5A059] my-0.5">&amp;</div>
                <h2 class="font-cinzel text-4xl sm:text-5xl font-bold text-[#6B1D2F] tracking-tight">
                    {{ $secondaryName }}
                </h2>

                <p class="font-cormorant text-sm tracking-[0.25em] text-[#786366] uppercase mt-2">
                    {{ $eventDateFormatted }}
                </p>
            </div>

            {{-- =====================================================
                 RETRO VELVET MUSIC PLAYER CARD (Sesuai Layar 2)
                 Deep Burgundy Velvet Card, Album Frame, Progress Bar & Vinyl
                 ===================================================== --}}
            <div class="reveal mt-4 p-5 rounded-3xl bg-gradient-to-br from-[#5A1624] via-[#48101C] to-[#300811] text-[#FAF5EE] shadow-[0_14px_35px_rgba(61,13,23,0.35)] border border-[#C5A059]/40 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-radial from-[#C5A059]/15 to-transparent rounded-full pointer-events-none"></div>

                <div class="flex items-center gap-4">
                    {{-- Mini Album Art in Rounded Frame --}}
                    <div class="relative w-16 h-16 sm:w-20 sm:h-20 rounded-2xl overflow-hidden shrink-0 border border-[#C5A059]/50 shadow-inner bg-[#22050B]">
                        @if(!empty($coverImg))
                            <img src="{{ asset('storage/' . $coverImg) }}" alt="Cover" class="w-full h-full object-cover">
                        @else
                            <img src="https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=400&auto=format&fit=crop" alt="Album" class="w-full h-full object-cover">
                        @endif
                        {{-- Small Vinyl overlay badge --}}
                        <div :class="musicPlaying ? 'animate-spin-slow' : ''"
                             class="absolute inset-0 m-auto w-6 h-6 rounded-full border border-amber-300/40 bg-black/40 flex items-center justify-center">
                            <span class="w-2 h-2 rounded-full bg-[#E8CF9B]"></span>
                        </div>
                    </div>

                    {{-- Track Info & Controls --}}
                    <div class="flex-1 text-left min-w-0 space-y-1">
                        <p class="text-[9px] uppercase tracking-[0.25em] text-[#E8CF9B] font-semibold">Wedding Soundtrack</p>
                        <h4 class="font-playfair text-base sm:text-lg font-bold text-white truncate">
                            Velvet Overture Symphony
                        </h4>
                        <p class="text-[11px] text-[#FAF5EE]/70 truncate">
                            {{ $primaryName }} &amp; {{ $secondaryName }} Special Moment
                        </p>

                        {{-- Waveform / Equalizer Animation --}}
                        <div class="flex items-end gap-1 h-3 pt-1">
                            <span class="w-1 bg-[#C5A059] rounded-full" :class="musicPlaying ? 'h-3 animate-pulse' : 'h-1'"></span>
                            <span class="w-1 bg-[#E8CF9B] rounded-full" :class="musicPlaying ? 'h-2 animate-bounce' : 'h-1'"></span>
                            <span class="w-1 bg-[#C5A059] rounded-full" :class="musicPlaying ? 'h-3.5 animate-pulse' : 'h-1'"></span>
                            <span class="w-1 bg-[#E8CF9B] rounded-full" :class="musicPlaying ? 'h-2.5 animate-bounce' : 'h-1'"></span>
                            <span class="w-1 bg-[#C5A059] rounded-full" :class="musicPlaying ? 'h-3 animate-pulse' : 'h-1'"></span>
                            <span class="text-[9px] text-[#E8CF9B] ml-2 font-mono" x-text="musicPlaying ? 'Memutar...' : 'Jeda'"></span>
                        </div>
                    </div>
                </div>

                {{-- Player Interactive Buttons --}}
                <div class="mt-4 pt-3 border-t border-[#C5A059]/20 flex items-center justify-center gap-6">
                    <button type="button" @click="toggleMusic()" class="text-[#E8CF9B] hover:text-white transition active:scale-90" title="Previous">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M6 6h2v12H6zm3.5 6l8.5 6V6z"/></svg>
                    </button>

                    <button type="button"
                            @click="toggleMusic()"
                            class="w-11 h-11 rounded-full bg-[#FAF5EE] text-[#3D0D17] flex items-center justify-center shadow-lg hover:scale-105 active:scale-95 transition border border-[#C5A059]"
                            :title="musicPlaying ? 'Jeda Musik' : 'Putar Musik'">
                        <template x-if="musicPlaying">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                        </template>
                        <template x-if="!musicPlaying">
                            <svg class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </template>
                    </button>

                    <button type="button" @click="toggleMusic()" class="text-[#E8CF9B] hover:text-white transition active:scale-90" title="Next">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M6 18l8.5-6L6 6v12zM16 6v12h2V6h-2z"/></svg>
                    </button>
                </div>
            </div>
        </section>


        {{-- =========================================================
             SECTION: GREETING & SURAH / LOVE QUOTE (Sesuai Layar 3)
             Vintage Stationery Plaque beside Velvet Box & Lace
             ========================================================= --}}
        <section class="reveal py-8 px-6">
            <div class="vintage-plaque p-6 sm:p-8 text-center space-y-4">
                {{-- Plaque Top Emblem --}}
                <div class="w-8 h-8 mx-auto rounded-full border border-[#C5A059] flex items-center justify-center text-[#6B1D2F] font-serif text-sm">
                    ❦
                </div>

                <h3 class="font-cinzel text-xl sm:text-2xl font-bold text-[#6B1D2F]">
                    Keluarga dan Sahabat Terkasih!
                </h3>

                <p class="font-cormorant text-base sm:text-lg text-[#2B181B] leading-relaxed italic">
                    "{{ $greeting }}"
                </p>

                <div class="w-16 h-[1px] bg-[#C5A059] mx-auto my-3"></div>

                <p class="font-sans text-xs text-[#786366] leading-relaxed">
                    {{ $quote }}
                </p>
            </div>
        </section>


        {{-- =========================================================
             SECTION: MEMPELAI / THE COUPLE (Sesuai Layar 2)
             Victorian Baroque Gold Carved Oval Mirror Frames
             ========================================================= --}}
        <section id="section-mempelai" class="reveal py-10 px-6 text-center space-y-8">
            <div class="space-y-1">
                <span class="font-cinzel text-[10px] tracking-[0.3em] uppercase text-[#C5A059] font-bold block">
                    Sang Mempelai
                </span>
                <h3 class="font-cinzel text-3xl font-bold text-[#6B1D2F]">
                    Mempelai Bahagia
                </h3>
                <div class="w-12 h-[1px] bg-[#C5A059] mx-auto pt-1"></div>
            </div>

            <div class="grid grid-cols-1 gap-10 max-w-sm mx-auto">
                {{-- MEMPELAI PRIA --}}
                <div class="space-y-4 flex flex-col items-center">
                    {{-- Baroque Gold Carved Oval Frame Container --}}
                    <div class="relative w-48 h-64 flex items-center justify-center">
                        {{-- Baroque Carved Gold Oval Frame SVG --}}
                        <svg class="absolute inset-0 w-full h-full pointer-events-none z-10 drop-shadow-[0_10px_20px_rgba(61,13,23,0.25)]" viewBox="0 0 200 270" fill="none">
                            <!-- Outer Ornate Filigree -->
                            <ellipse cx="100" cy="135" rx="88" ry="118" stroke="#C5A059" stroke-width="4"/>
                            <ellipse cx="100" cy="135" rx="83" ry="113" stroke="#8E6D30" stroke-width="1.5"/>
                            <ellipse cx="100" cy="135" rx="77" ry="107" stroke="#FAF5EE" stroke-width="2"/>
                            <ellipse cx="100" cy="135" rx="74" ry="104" stroke="#C5A059" stroke-width="1"/>
                            <!-- Top Crest Flourish -->
                            <path d="M100,5 C88,18 78,12 70,22 C82,24 92,20 100,24 C108,20 118,24 130,22 C122,12 112,18 100,5 Z" fill="#C5A059"/>
                            <circle cx="100" cy="15" r="3" fill="#FAF5EE"/>
                            <!-- Bottom Crest Flourish -->
                            <path d="M100,265 C88,252 78,258 70,248 C82,246 92,250 100,246 C108,250 118,246 130,248 C122,258 112,252 100,265 Z" fill="#C5A059"/>
                            <circle cx="100" cy="255" r="3" fill="#FAF5EE"/>
                            <!-- Side Accents -->
                            <circle cx="12" cy="135" r="3.5" fill="#C5A059"/>
                            <circle cx="188" cy="135" r="3.5" fill="#C5A059"/>
                        </svg>

                        {{-- Photo Masked in Oval --}}
                        <div class="w-[148px] h-[208px] rounded-[50%] overflow-hidden z-0 bg-[#FAF5EE] shadow-inner">
                            @if(!empty($coverImg))
                                <img src="{{ asset('storage/' . $coverImg) }}" alt="{{ $primaryFullName }}" class="w-full h-full object-cover">
                            @else
                                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=400&auto=format&fit=crop" alt="{{ $primaryFullName }}" class="w-full h-full object-cover filter contrast-[1.05]">
                            @endif
                        </div>
                    </div>

                    {{-- Groom Bio --}}
                    <div class="space-y-1.5 px-2">
                        <h4 class="font-cinzel text-xl font-bold text-[#6B1D2F]">
                            {{ $primaryFullName }}
                        </h4>
                        <p class="font-sans text-xs text-[#786366] max-w-xs leading-relaxed">
                            {{ $primaryParents }}
                        </p>
                        @if(!empty($primaryIg))
                            <a href="https://instagram.com/{{ ltrim($primaryIg, '@') }}" target="_blank" rel="noopener"
                               class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-medium text-[#6B1D2F] bg-[#FAF5EE] border border-[#C5A059]/60 hover:bg-[#6B1D2F] hover:text-white transition shadow-sm mt-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                <span>{{ '@' . ltrim($primaryIg, '@') }}</span>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Ampersand Divider --}}
                <div class="flex items-center justify-center gap-3 py-1">
                    <span class="w-10 h-[1px] bg-[#C5A059]"></span>
                    <span class="font-script text-4xl text-[#6B1D2F]">&amp;</span>
                    <span class="w-10 h-[1px] bg-[#C5A059]"></span>
                </div>

                {{-- MEMPELAI WANITA --}}
                <div class="space-y-4 flex flex-col items-center">
                    {{-- Baroque Gold Carved Oval Frame Container --}}
                    <div class="relative w-48 h-64 flex items-center justify-center">
                        <svg class="absolute inset-0 w-full h-full pointer-events-none z-10 drop-shadow-[0_10px_20px_rgba(61,13,23,0.25)]" viewBox="0 0 200 270" fill="none">
                            <ellipse cx="100" cy="135" rx="88" ry="118" stroke="#C5A059" stroke-width="4"/>
                            <ellipse cx="100" cy="135" rx="83" ry="113" stroke="#8E6D30" stroke-width="1.5"/>
                            <ellipse cx="100" cy="135" rx="77" ry="107" stroke="#FAF5EE" stroke-width="2"/>
                            <ellipse cx="100" cy="135" rx="74" ry="104" stroke="#C5A059" stroke-width="1"/>
                            <path d="M100,5 C88,18 78,12 70,22 C82,24 92,20 100,24 C108,20 118,24 130,22 C122,12 112,18 100,5 Z" fill="#C5A059"/>
                            <circle cx="100" cy="15" r="3" fill="#FAF5EE"/>
                            <path d="M100,265 C88,252 78,258 70,248 C82,246 92,250 100,246 C108,250 118,246 130,248 C122,258 112,252 100,265 Z" fill="#C5A059"/>
                            <circle cx="100" cy="255" r="3" fill="#FAF5EE"/>
                            <circle cx="12" cy="135" r="3.5" fill="#C5A059"/>
                            <circle cx="188" cy="135" r="3.5" fill="#C5A059"/>
                        </svg>

                        <div class="w-[148px] h-[208px] rounded-[50%] overflow-hidden z-0 bg-[#FAF5EE] shadow-inner">
                            @if(!empty($gallery[0]))
                                <img src="{{ asset('storage/' . $gallery[0]) }}" alt="{{ $secondaryFullName }}" class="w-full h-full object-cover">
                            @else
                                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=400&auto=format&fit=crop" alt="{{ $secondaryFullName }}" class="w-full h-full object-cover filter contrast-[1.05]">
                            @endif
                        </div>
                    </div>

                    {{-- Bride Bio --}}
                    <div class="space-y-1.5 px-2">
                        <h4 class="font-cinzel text-xl font-bold text-[#6B1D2F]">
                            {{ $secondaryFullName }}
                        </h4>
                        <p class="font-sans text-xs text-[#786366] max-w-xs leading-relaxed">
                            {{ $secondaryParents }}
                        </p>
                        @if(!empty($secondaryIg))
                            <a href="https://instagram.com/{{ ltrim($secondaryIg, '@') }}" target="_blank" rel="noopener"
                               class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-medium text-[#6B1D2F] bg-[#FAF5EE] border border-[#C5A059]/60 hover:bg-[#6B1D2F] hover:text-white transition shadow-sm mt-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                <span>{{ '@' . ltrim($secondaryIg, '@') }}</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </section>


        {{-- =========================================================
             SECTION: CALENDAR & COUNTDOWN (Sesuai Layar 3)
             "Kami Menanti Anda" - Deep Velvet Card with Calendar Grid
             ========================================================= --}}
        <section class="reveal py-10 px-6">
            <div class="p-6 rounded-3xl bg-gradient-to-b from-[#5C1625] to-[#380C15] text-[#FAF5EE] shadow-[0_16px_40px_rgba(61,13,23,0.35)] border border-[#C5A059]/40 text-center space-y-6 relative overflow-hidden">
                <div class="space-y-1">
                    <span class="font-cinzel text-[10px] tracking-[0.3em] uppercase text-[#E8CF9B] font-bold block">
                        Save The Date
                    </span>
                    <h3 class="font-cinzel text-2xl sm:text-3xl font-bold text-white">
                        Kami Menanti Anda
                    </h3>
                    <p class="font-cormorant text-sm text-[#FAF5EE]/80 italic">
                        Menghitung hari menuju lembaran baru kisah cinta kami
                    </p>
                </div>

                {{-- Mini Monthly Calendar Card --}}
                <div class="p-4 rounded-2xl bg-[#22060C]/70 border border-[#C5A059]/30 max-w-xs mx-auto">
                    <div class="font-cinzel text-xs font-bold tracking-widest text-[#E8CF9B] uppercase mb-3 border-b border-[#C5A059]/20 pb-2">
                        {{ $eventMonthYear }}
                    </div>

                    {{-- Day Names --}}
                    <div class="grid grid-cols-7 gap-1 text-[9px] font-semibold text-[#FAF5EE]/60 mb-2">
                        <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span class="text-amber-400">Min</span>
                    </div>

                    {{-- Days Numbers Grid --}}
                    <div class="grid grid-cols-7 gap-1 text-[11px] font-mono">
                        {{-- Empty slots before first day --}}
                        @for($i = 1; $i < $firstDayOfWeek; $i++)
                            <span class="p-1"></span>
                        @endfor

                        @for($d = 1; $d <= $daysInMonth; $d++)
                            @if($d === $eventDayNumber)
                                <span class="p-1 rounded-full bg-[#C5A059] text-[#22050B] font-bold shadow-md ring-2 ring-amber-300 flex items-center justify-center">
                                    {{ $d }}
                                </span>
                            @else
                                <span class="p-1 rounded-full text-[#FAF5EE]/80 hover:bg-white/10 flex items-center justify-center">
                                    {{ $d }}
                                </span>
                            @endif
                        @endfor
                    </div>
                </div>

                {{-- Countdown Timer Boxes --}}
                <div class="grid grid-cols-4 gap-2 sm:gap-3 max-w-xs mx-auto pt-1">
                    <div class="p-3 rounded-2xl bg-[#22060C]/90 border border-[#C5A059]/40 shadow-inner">
                        <span class="font-cinzel text-2xl font-bold text-[#E8CF9B] block" x-text="countdown.days">00</span>
                        <span class="text-[9px] uppercase tracking-wider text-[#FAF5EE]/60 block font-sans">Hari</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-[#22060C]/90 border border-[#C5A059]/40 shadow-inner">
                        <span class="font-cinzel text-2xl font-bold text-[#E8CF9B] block" x-text="countdown.hours">00</span>
                        <span class="text-[9px] uppercase tracking-wider text-[#FAF5EE]/60 block font-sans">Jam</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-[#22060C]/90 border border-[#C5A059]/40 shadow-inner">
                        <span class="font-cinzel text-2xl font-bold text-[#E8CF9B] block" x-text="countdown.minutes">00</span>
                        <span class="text-[9px] uppercase tracking-wider text-[#FAF5EE]/60 block font-sans">Menit</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-[#22060C]/90 border border-[#C5A059]/40 shadow-inner">
                        <span class="font-cinzel text-2xl font-bold text-[#E8CF9B] block" x-text="countdown.seconds">00</span>
                        <span class="text-[9px] uppercase tracking-wider text-[#FAF5EE]/60 block font-sans">Detik</span>
                    </div>
                </div>

                {{-- Add to Google Calendar Button --}}
                <div class="pt-2">
                    <a href="{{ $calUrl }}" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-xs font-semibold bg-[#FAF5EE] text-[#3D0D17] hover:bg-[#E8CF9B] transition border border-[#C5A059] shadow-md active:scale-95">
                        <svg class="w-4 h-4 text-[#6B1D2F]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Simpan ke Google Calendar</span>
                    </a>
                </div>
            </div>
        </section>


        {{-- =========================================================
             SECTION: RANGKAIAN ACARA & TIMELINE (Sesuai Layar 4)
             Vintage Parchment Plaque with Olive Ribbon Bow & Antique Icons
             ========================================================= --}}
        <section id="section-acara" class="reveal py-10 px-6 space-y-8">
            <div class="text-center space-y-1">
                <span class="font-cinzel text-[10px] tracking-[0.3em] uppercase text-[#C5A059] font-bold block">
                    Agenda Acara
                </span>
                <h3 class="font-cinzel text-3xl font-bold text-[#6B1D2F]">
                    Rangkaian Acara
                </h3>
                <div class="w-12 h-[1px] bg-[#C5A059] mx-auto pt-1"></div>
            </div>

            {{-- Vintage Plaque Card (Sesuai Referensi Gambar) --}}
            <div class="vintage-plaque p-6 sm:p-8 relative">
                {{-- Olive Ribbon Bow pinned at Top-Left Corner --}}
                <div class="absolute -top-3.5 -left-3.5 z-20 w-10 h-10 flex items-center justify-center pointer-events-none">
                    <svg viewBox="0 0 40 40" class="w-full h-full text-[#4A5D23] drop-shadow-md" fill="currentColor">
                        <!-- Bow Center Knot -->
                        <circle cx="20" cy="20" r="5" fill="#3D4D1C"/>
                        <!-- Bow Loops -->
                        <path d="M20,20 C10,12 4,18 10,24 C14,28 18,22 20,20 Z" fill="#556B2F"/>
                        <path d="M20,20 C12,10 18,4 24,10 C28,14 22,18 20,20 Z" fill="#556B2F"/>
                        <!-- Ribbon Tails -->
                        <path d="M20,22 L14,36 L18,34 L22,38 Z" fill="#4A5D23"/>
                        <path d="M22,20 L36,26 L34,22 L38,18 Z" fill="#4A5D23"/>
                    </svg>
                </div>

                {{-- Floral Bouquet pinned at Bottom-Right Corner --}}
                <div class="absolute -bottom-3.5 -right-3.5 z-20 w-12 h-12 flex items-center justify-center pointer-events-none opacity-80">
                    <span class="text-[#6B1D2F] text-2xl">🌹</span>
                </div>

                {{-- Timeline Items with Antique Icons (Hourglass, Chest, Flute, Fan) --}}
                <div class="space-y-6 pt-2">
                    @foreach($timelineItems as $item)
                        <div class="flex items-start gap-4 pb-4 border-b border-[#C5A059]/20 last:border-b-0 last:pb-0">
                            {{-- Antique Icon Container --}}
                            <div class="w-12 h-12 rounded-2xl bg-[#FAF5EE] border border-[#C5A059] shadow-sm flex items-center justify-center shrink-0 text-[#6B1D2F]">
                                @if($item['type'] === 'hourglass')
                                    {{-- Antique Hourglass --}}
                                    <svg class="w-6 h-6 text-[#C5A059]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @elseif($item['type'] === 'chest')
                                    {{-- Treasure Chest / Rings --}}
                                    <svg class="w-6 h-6 text-[#C5A059]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                    </svg>
                                @elseif($item['type'] === 'flute')
                                    {{-- Champagne Coupe / Celebration --}}
                                    <svg class="w-6 h-6 text-[#C5A059]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @else
                                    {{-- Victorian Hand Fan / Sparkler --}}
                                    <svg class="w-6 h-6 text-[#C5A059]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                    </svg>
                                @endif
                            </div>

                            {{-- Time & Detail --}}
                            <div class="flex-1 text-left min-w-0">
                                <span class="font-cinzel text-xs font-bold text-[#6B1D2F] tracking-widest uppercase block">
                                    {{ $item['time'] }} WIB
                                </span>
                                <h4 class="font-playfair text-base font-bold text-[#2B181B] pt-0.5">
                                    {{ $item['title'] }}
                                </h4>
                                <p class="text-xs text-[#786366] leading-relaxed pt-0.5">
                                    {{ $item['desc'] }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- DUAL SESSION CARDS: AKAD & RESEPSI WITH GOOGLE MAPS --}}
            <div class="space-y-5 pt-2">
                {{-- Sesi 1: Akad Nikah --}}
                <div class="p-6 rounded-3xl bg-white border border-[#C5A059]/40 shadow-sm text-center space-y-3">
                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-cinzel font-bold uppercase tracking-wider bg-[#6B1D2F]/10 text-[#6B1D2F] border border-[#6B1D2F]/20">
                        {{ $s1['name'] ?? 'Akad Nikah' }}
                    </span>
                    <h4 class="font-cinzel text-xl font-bold text-[#2B181B]">
                        {{ $eventDateFormatted }}
                    </h4>
                    <p class="font-mono text-sm text-[#6B1D2F] font-semibold">
                        Pukul {{ $s1['time'] ?? '15:00 - 17:00' }} WIB
                    </p>
                    <div class="pt-1">
                        <p class="font-playfair font-bold text-sm text-[#2B181B]">{{ $s1['venue'] ?? 'Kediaman Mempelai Wanita' }}</p>
                        <p class="text-xs text-[#786366] mt-0.5">{{ $s1['address'] ?? 'Jl. Melati Indah No. 12, Jakarta Selatan' }}</p>
                    </div>
                    @if(!empty($s1['maps_url']))
                        <div class="pt-2">
                            <a href="{{ $s1['maps_url'] }}" target="_blank" rel="noopener"
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold bg-[#6B1D2F] text-white hover:bg-[#521422] transition shadow-sm active:scale-95">
                                <svg class="w-3.5 h-3.5 text-[#E8CF9B]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>Petunjuk Lokasi (Google Maps)</span>
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Sesi 2: Resepsi --}}
                @if(!empty($s2['name']) || !empty($s2['time']))
                <div class="p-6 rounded-3xl bg-white border border-[#C5A059]/40 shadow-sm text-center space-y-3">
                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-cinzel font-bold uppercase tracking-wider bg-[#6B1D2F]/10 text-[#6B1D2F] border border-[#6B1D2F]/20">
                        {{ $s2['name'] ?? 'Resepsi Pernikahan' }}
                    </span>
                    <h4 class="font-cinzel text-xl font-bold text-[#2B181B]">
                        {{ !empty($s2['date']) ? \Carbon\Carbon::parse($s2['date'])->isoFormat('dddd, D MMMM Y') : $eventDateFormatted }}
                    </h4>
                    <p class="font-mono text-sm text-[#6B1D2F] font-semibold">
                        Pukul {{ $s2['time'] ?? '18:30 - 21:00' }} WIB
                    </p>
                    <div class="pt-1">
                        <p class="font-playfair font-bold text-sm text-[#2B181B]">{{ $s2['venue'] ?? 'Grand Ballroom The Ritz' }}</p>
                        <p class="text-xs text-[#786366] mt-0.5">{{ $s2['address'] ?? 'Kawasan Mega Kuningan, Jakarta Selatan' }}</p>
                    </div>
                    @if(!empty($s2['maps_url']))
                        <div class="pt-2">
                            <a href="{{ $s2['maps_url'] }}" target="_blank" rel="noopener"
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold bg-[#6B1D2F] text-white hover:bg-[#521422] transition shadow-sm active:scale-95">
                                <svg class="w-3.5 h-3.5 text-[#E8CF9B]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>Petunjuk Lokasi (Google Maps)</span>
                            </a>
                        </div>
                    @endif
                </div>
                @endif
            </div>
        </section>


        {{-- =========================================================
             SECTION: GALERI KENANGAN / PHOTO GALLERY (Sesuai Layar 4)
             Vintage Polaroid & Gold Frame Grid with Modal Lightbox
             ========================================================= --}}
        <section id="section-galeri" class="reveal py-10 px-6 space-y-6">
            <div class="text-center space-y-1">
                <span class="font-cinzel text-[10px] tracking-[0.3em] uppercase text-[#C5A059] font-bold block">
                    Dokumentasi
                </span>
                <h3 class="font-cinzel text-3xl font-bold text-[#6B1D2F]">
                    Galeri Kenangan
                </h3>
                <p class="font-cormorant text-sm text-[#786366] italic">
                    Setiap detik bersama adalah nada indah dalam harmoni kisah cinta kami
                </p>
                <div class="w-12 h-[1px] bg-[#C5A059] mx-auto pt-1"></div>
            </div>

            @php
                $samplePhotos = [
                    'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=800&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=800&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1522673607200-164d1b6ce486?q=80&w=800&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1465495976277-4387d4b0b4c6?q=80&w=800&auto=format&fit=crop',
                ];
                $displayPhotos = !empty($gallery) && count($gallery) > 0 ? array_map(fn($g) => asset('storage/' . $g), $gallery) : $samplePhotos;
            @endphp

            <div class="grid grid-cols-2 gap-3.5">
                @foreach($displayPhotos as $idx => $photo)
                    <div @click="openLightbox('{{ $photo }}')"
                         class="group relative aspect-[3/4] rounded-2xl overflow-hidden cursor-pointer shadow-md border-2 border-[#C5A059]/40 bg-[#FAF5EE] hover:scale-[1.02] transition-all duration-300">
                        <img src="{{ $photo }}" alt="Foto Galeri {{ $idx + 1 }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#3D0D17]/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-center p-3">
                            <span class="text-[10px] font-cinzel text-[#E8CF9B] uppercase tracking-widest">Lihat Foto</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>


        {{-- =========================================================
             SECTION: AMPLOP DIGITAL / TANDA KASIH
             Velvet Card with EMV Chip & 1-Click Clipboard Copy
             ========================================================= --}}
        <section id="section-kado" class="reveal py-10 px-6 space-y-6">
            <div class="text-center space-y-1">
                <span class="font-cinzel text-[10px] tracking-[0.3em] uppercase text-[#C5A059] font-bold block">
                    Kado Online
                </span>
                <h3 class="font-cinzel text-3xl font-bold text-[#6B1D2F]">
                    Tanda Kasih
                </h3>
                <p class="font-cormorant text-sm text-[#786366] italic max-w-xs mx-auto">
                    Doa restu Anda merupakan karunia terindah bagi kami. Bagi Bapak/Ibu yang ingin memberikan tanda kasih secara digital:
                </p>
                <div class="w-12 h-[1px] bg-[#C5A059] mx-auto pt-1"></div>
            </div>

            @php
                $giftAccounts = !empty($gifts) && count($gifts) > 0 ? $gifts : [
                    ['bank' => 'BCA', 'account_number' => '8290123849', 'account_name' => $primaryFullName],
                    ['bank' => 'Mandiri', 'account_number' => '1370019283741', 'account_name' => $secondaryFullName]
                ];
            @endphp

            <div class="space-y-4 max-w-sm mx-auto">
                @foreach($giftAccounts as $index => $gift)
                    <div class="p-6 rounded-3xl bg-gradient-to-br from-[#4A101C] via-[#380C15] to-[#22050B] text-[#FAF5EE] border border-[#C5A059]/50 shadow-[0_12px_30px_rgba(61,13,23,0.3)] relative overflow-hidden space-y-4">
                        {{-- Top Bank & EMV Chip --}}
                        <div class="flex items-center justify-between">
                            {{-- EMV Chip Graphic --}}
                            <div class="w-10 h-7 rounded-md bg-gradient-to-tr from-[#D4AF37] to-[#FAF5EE] border border-[#AA7C11] shadow-inner relative flex items-center justify-center">
                                <div class="w-8 h-5 border border-[#8E6D30]/60 rounded-sm"></div>
                            </div>
                            <span class="font-cinzel text-base font-bold text-[#E8CF9B] tracking-wider uppercase">
                                {{ $gift['bank'] ?? 'Bank Transfer' }}
                            </span>
                        </div>

                        {{-- Account Number & Copy Button --}}
                        <div class="space-y-1">
                            <span class="text-[9px] uppercase tracking-widest text-[#FAF5EE]/60 block">Nomor Rekening</span>
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-mono text-xl sm:text-2xl font-bold tracking-wider text-white">
                                    {{ $gift['account_number'] ?? '0000 0000 00' }}
                                </span>
                                <button type="button"
                                        @click="copyText('{{ $gift['account_number'] ?? '' }}', {{ $index }})"
                                        class="px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-[#FAF5EE] text-[#3D0D17] hover:bg-[#E8CF9B] transition active:scale-95 shadow-md flex items-center gap-1.5">
                                    <template x-if="copiedIndex === {{ $index }}">
                                        <span class="text-emerald-700 font-bold">✓ Tersalin</span>
                                    </template>
                                    <template x-if="copiedIndex !== {{ $index }}">
                                        <span>Salin</span>
                                    </template>
                                </button>
                            </div>
                        </div>

                        {{-- Account Holder Name --}}
                        <div class="pt-1 border-t border-[#C5A059]/20 flex items-center justify-between text-xs">
                            <span class="text-[#FAF5EE]/70 font-sans">Atas Nama:</span>
                            <span class="font-cinzel font-semibold text-[#E8CF9B] truncate max-w-[180px]">
                                {{ $gift['account_name'] ?? ($primaryName . ' / ' . $secondaryName) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>


        {{-- =========================================================
             SECTION: RSVP & BUKU TAMU / DOA & UCAPAN
             Live Wishes Board & Real-Time Form Submission
             ========================================================= --}}
        <section id="section-rsvp" class="reveal py-10 px-6 space-y-6">
            <div class="text-center space-y-1">
                <span class="font-cinzel text-[10px] tracking-[0.3em] uppercase text-[#C5A059] font-bold block">
                    Buku Tamu
                </span>
                <h3 class="font-cinzel text-3xl font-bold text-[#6B1D2F]">
                    Doa &amp; Ucapan
                </h3>
                <p class="font-cormorant text-sm text-[#786366] italic">
                    Untaian doa dan restu dari Anda adalah hadiah paling berharga
                </p>
                <div class="w-12 h-[1px] bg-[#C5A059] mx-auto pt-1"></div>
            </div>

            {{-- RSVP Form Card --}}
            <div class="p-6 rounded-3xl bg-white border border-[#C5A059]/40 shadow-sm space-y-4">
                <form @submit.prevent="submitRsvp('{{ route('invitation.wish', $invitation->slug) }}')" class="space-y-4">
                    {{-- Guest Name Input --}}
                    <div>
                        <label class="block text-xs font-cinzel font-bold text-[#2B181B] mb-1">Nama Anda</label>
                        <input type="text"
                               x-model="rsvpForm.guest_name"
                               placeholder="Tuliskan nama lengkap Anda..."
                               required
                               class="w-full px-4 py-2.5 rounded-xl border border-[#C5A059]/40 bg-[#FAF5EE] text-xs text-[#2B181B] focus:outline-none focus:ring-2 focus:ring-[#6B1D2F] transition">
                    </div>

                    {{-- Attendance Status Pills --}}
                    <div>
                        <label class="block text-xs font-cinzel font-bold text-[#2B181B] mb-1.5">Konfirmasi Kehadiran</label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="cursor-pointer">
                                <input type="radio" x-model="rsvpForm.attendance" value="hadir" class="sr-only">
                                <div :class="rsvpForm.attendance === 'hadir' ? 'bg-[#6B1D2F] text-white border-[#6B1D2F]' : 'bg-[#FAF5EE] text-[#786366] border-[#C5A059]/30'"
                                     class="py-2 text-center rounded-xl border text-[11px] font-semibold transition active:scale-95">
                                    ✓ Hadir
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" x-model="rsvpForm.attendance" value="tidak_hadir" class="sr-only">
                                <div :class="rsvpForm.attendance === 'tidak_hadir' ? 'bg-[#6B1D2F] text-white border-[#6B1D2F]' : 'bg-[#FAF5EE] text-[#786366] border-[#C5A059]/30'"
                                     class="py-2 text-center rounded-xl border text-[11px] font-semibold transition active:scale-95">
                                    ✕ Berhalangan
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" x-model="rsvpForm.attendance" value="ragu" class="sr-only">
                                <div :class="rsvpForm.attendance === 'ragu' ? 'bg-[#6B1D2F] text-white border-[#6B1D2F]' : 'bg-[#FAF5EE] text-[#786366] border-[#C5A059]/30'"
                                     class="py-2 text-center rounded-xl border text-[11px] font-semibold transition active:scale-95">
                                    ? Masih Ragu
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Wishes Message Input --}}
                    <div>
                        <label class="block text-xs font-cinzel font-bold text-[#2B181B] mb-1">Untaian Doa &amp; Ucapan</label>
                        <textarea x-model="rsvpForm.message"
                                  rows="3"
                                  placeholder="Tuliskan ucapan selamat &amp; doa restu untuk kedua mempelai..."
                                  required
                                  class="w-full px-4 py-2.5 rounded-xl border border-[#C5A059]/40 bg-[#FAF5EE] text-xs text-[#2B181B] focus:outline-none focus:ring-2 focus:ring-[#6B1D2F] transition"></textarea>
                    </div>

                    {{-- Submit Button & Notifications --}}
                    <div class="space-y-2">
                        <button type="submit"
                                :disabled="rsvpSubmitting"
                                class="w-full py-3 rounded-xl font-cinzel text-xs font-bold uppercase tracking-wider bg-[#6B1D2F] text-white hover:bg-[#521422] transition active:scale-98 shadow-md flex items-center justify-center gap-2">
                            <span x-show="!rsvpSubmitting">Kirim Doa &amp; Restu</span>
                            <span x-show="rsvpSubmitting" x-cloak>Mengirimkan ucapan...</span>
                        </button>

                        <div x-show="rsvpSuccessMsg" x-cloak class="p-3 rounded-xl bg-emerald-50 border border-emerald-300 text-emerald-800 text-xs">
                            <span x-text="rsvpSuccessMsg"></span>
                        </div>
                        <div x-show="rsvpErrorMsg" x-cloak class="p-3 rounded-xl bg-red-50 border border-red-300 text-red-800 text-xs">
                            <span x-text="rsvpErrorMsg"></span>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Live Feed of Guest Wishes --}}
            <div class="space-y-3">
                <div class="flex items-center justify-between text-xs font-cinzel text-[#786366] px-1">
                    <span class="font-bold" x-text="wishes.length + ' Doa &amp; Ucapan'"></span>
                    <span class="text-[11px]" x-text="counts.hadir + ' Tamu Hadir'"></span>
                </div>

                <div class="max-h-80 overflow-y-auto space-y-2.5 pr-1">
                    <template x-for="(w, idx) in wishes" :key="idx">
                        <div class="p-4 rounded-2xl bg-white border border-[#C5A059]/30 shadow-sm space-y-1.5 text-left">
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-cinzel text-xs font-bold text-[#6B1D2F] truncate" x-text="w.guest_name"></span>
                                <span :class="w.attendance === 'hadir' ? 'bg-emerald-100 text-emerald-800' : (w.attendance === 'tidak_hadir' ? 'bg-stone-100 text-stone-600' : 'bg-amber-100 text-amber-800')"
                                      class="px-2 py-0.5 rounded-full text-[9px] font-semibold shrink-0 uppercase"
                                      x-text="w.attendance === 'hadir' ? 'Hadir' : (w.attendance === 'tidak_hadir' ? 'Tidak Hadir' : 'Ragu')">
                                </span>
                            </div>
                            <p class="font-sans text-xs text-[#2B181B] leading-relaxed" x-text="w.message"></p>
                            <span class="text-[9px] text-[#786366]/70 block pt-0.5" x-text="w.created_at_human || 'Baru saja'"></span>
                        </div>
                    </template>
                </div>
            </div>
        </section>


        {{-- =========================================================
             SECTION: PENUTUP / CLOSING
             ========================================================= --}}
        <section class="reveal py-12 px-6 text-center space-y-4">
            <div class="w-12 h-12 mx-auto rounded-full border border-[#C5A059] flex items-center justify-center text-[#6B1D2F] text-lg">
                ❦
            </div>

            <p class="font-cormorant text-base sm:text-lg text-[#2B181B] leading-relaxed max-w-sm mx-auto italic">
                Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu kepada kami.
            </p>

            <div class="pt-4 space-y-1">
                <p class="font-cinzel text-xs uppercase tracking-widest text-[#786366]">Kami yang berbahagia,</p>
                <h4 class="font-script text-4xl sm:text-5xl text-[#6B1D2F]">
                    {{ $primaryName }} &amp; {{ $secondaryName }}
                </h4>
                <p class="text-xs text-[#786366] pt-1">Beserta Keluarga Besar Kedua Mempelai</p>
            </div>
        </section>
    </main>


    {{-- =========================================================
         FLOATING BOTTOM NAVIGATION DOCK & MUSIC CONTROLLER
         ========================================================= --}}
    <div x-cloak x-show="isOpen"
         class="fixed bottom-4 inset-x-0 mx-auto max-w-sm z-40 px-4">
        <div class="px-3 py-2 rounded-full bg-[#2A070F]/90 border border-[#C5A059]/60 shadow-[0_10px_35px_rgba(0,0,0,0.5)] backdrop-blur-md flex items-center justify-between text-[#FAF5EE]">
            {{-- Quick Links --}}
            <a href="#section-top" class="p-2 rounded-full hover:bg-white/10 transition text-center" title="Sampul">
                <svg class="w-4 h-4 text-[#E8CF9B]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            </a>
            <a href="#section-mempelai" class="p-2 rounded-full hover:bg-white/10 transition text-center" title="Mempelai">
                <svg class="w-4 h-4 text-[#E8CF9B]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </a>
            <a href="#section-acara" class="p-2 rounded-full hover:bg-white/10 transition text-center" title="Acara">
                <svg class="w-4 h-4 text-[#E8CF9B]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </a>
            <a href="#section-galeri" class="p-2 rounded-full hover:bg-white/10 transition text-center" title="Galeri">
                <svg class="w-4 h-4 text-[#E8CF9B]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </a>
            <a href="#section-kado" class="p-2 rounded-full hover:bg-white/10 transition text-center" title="Kado">
                <svg class="w-4 h-4 text-[#E8CF9B]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4m-14 0v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
            </a>
            <a href="#section-rsvp" class="p-2 rounded-full hover:bg-white/10 transition text-center" title="Doa & Ucapan">
                <svg class="w-4 h-4 text-[#E8CF9B]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
            </a>

            {{-- Floating Vinyl Music Toggle --}}
            <button type="button"
                    @click="toggleMusic()"
                    class="ml-1 w-9 h-9 rounded-full bg-[#C5A059] text-[#22050B] flex items-center justify-center shadow-lg active:scale-95 transition"
                    :class="musicPlaying ? 'animate-spin-slow ring-2 ring-amber-300' : ''"
                    title="Musik Latar">
                <template x-if="musicPlaying">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/></svg>
                </template>
                <template x-if="!musicPlaying">
                    <svg class="w-4 h-4 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                </template>
            </button>
        </div>
    </div>


    {{-- LIGHTBOX MODAL FOR GALLERY ZOOM --}}
    <div x-cloak x-show="lightboxOpen"
         @keydown.escape.window="closeLightbox()"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4"
         role="dialog" aria-modal="true">
        <button @click="closeLightbox()"
                type="button"
                aria-label="Tutup foto"
                class="absolute right-5 top-5 rounded-full bg-white/20 p-2.5 text-white hover:bg-white/30 transition">
            ✕
        </button>
        <div class="max-h-[90vh] max-w-3xl overflow-hidden rounded-2xl shadow-2xl border border-[#C5A059]"
             @click.away="closeLightbox()">
            <img :src="activePhoto" alt="Preview foto" class="max-h-[85vh] w-full object-contain">
        </div>
    </div>

    {{-- REFINED ENGINE INCLUSION --}}
    @include('themes.partials.engine')
</body>
</html>