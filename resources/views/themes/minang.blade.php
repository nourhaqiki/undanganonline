<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ $invitation->title ?? 'The Wedding of Fadhil & Nisa | Baralek Gadang' }} — Minangkabau Royal</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Playfair Display, Marcellus, Figtree & Cormorant Garamond -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=Figtree:wght@400;500;600;700&family=Marcellus&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
      .font-display   { font-family: 'Playfair Display', Georgia, serif; }
      .font-caps      { font-family: 'Marcellus', Georgia, serif; }
      .font-body      { font-family: 'Figtree', system-ui, sans-serif; }
      .font-cormorant { font-family: 'Cormorant Garamond', Georgia, serif; }
      :root {
        --mk-paper: #FBF3E4;
        --mk-paper-2: #F3E6CF;
        --mk-paper-3: #E4D2B0;
        --mk-red: #9E1B22;
        --mk-red-2: #7A1218;
        --mk-black: #1E1612;
        --mk-wood: #2E1912;
        --mk-gold: #C9A227;
        --mk-gold-2: #9A7514;
        --mk-gold-3: #F1D98A;
        --mk-ink: #2A1D17;
        --mk-ink-2: #6B5446;
      }

      /* Tekstur Kertas Berpola Songket Halus */
      .mk-paper {
        background-color: var(--mk-paper);
        background-image:
          radial-gradient(circle at 20% 30%, rgba(158, 27, 34, 0.035) 0 2px, transparent 3px),
          radial-gradient(circle at 70% 60%, rgba(201, 162, 39, 0.05) 0 2px, transparent 3px);
        background-size: 23px 23px, 31px 31px;
      }

      .mk-paper-2 {
        background-color: var(--mk-paper-2);
        background-image: radial-gradient(circle at 30% 40%, rgba(158, 27, 34, 0.035) 0 2px, transparent 3px);
        background-size: 27px 27px;
      }

      .mk-songket {
        background-color: var(--mk-red);
        background-image: url('{{ asset('images/themes/minang/royal/songket-balapak.webp') }}');
        background-size: 64px 64px;
      }

      .mk-songket-faint {
        background-image: url('{{ asset('images/themes/minang/royal/songket-balapak.webp') }}');
        background-size: 90px 90px;
        opacity: 0.06;
      }

      /* Teks Emas Metalik Berkilau (Shimmer) */
      .mk-gold-text {
        background: linear-gradient(100deg, #9A7514 0%, #E9C95A 30%, #FFF1B8 45%, #C9A227 60%, #9A7514 100%);
        background-size: 220% 100%;
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        animation: mk-shimmer 6s ease-in-out infinite;
      }

      @keyframes mk-shimmer {
        0%, 100% { background-position: 100% 0; }
        50% { background-position: 0 0; }
      }

      .mk-mist {
        animation: mk-mist var(--dur, 40s) ease-in-out infinite;
        animation-delay: var(--delay, 0s);
      }
      @keyframes mk-mist {
        0%, 100% { transform: translate3d(0, 0, 0); }
        50% { transform: translate3d(var(--dx, 40px), 0, 0); }
      }

      /* Bendera Marawa Minangkabau Berkibar (Physics Wave) */
      .mk-flag {
        transform-box: fill-box;
        transform-origin: 0 0;
        animation: mk-flag 3.2s ease-in-out infinite;
        animation-delay: var(--delay, 0s);
      }
      @keyframes mk-flag {
        0%, 100% { transform: skewY(0deg) scaleX(1) rotate(0deg); }
        30% { transform: skewY(5deg) scaleX(0.9) rotate(3deg); }
        65% { transform: skewY(-3deg) scaleX(1.04) rotate(-1.5deg); }
      }

      /* Burung Elang Melayang di Langit Ranah Minang */
      .mk-eagle {
        left: -20%;
        animation: mk-eagle var(--dur, 46s) linear infinite;
        animation-delay: var(--delay, 0s);
      }
      @keyframes mk-eagle {
        0% { left: -20%; transform: translate3d(0, 0, 0); }
        50% { transform: translate3d(0, -14px, 0); }
        100% { left: 110%; transform: translate3d(0, 0, 0); }
      }

      .mk-glide-wing {
        transform-box: fill-box;
        transform-origin: 50% 60%;
        animation: mk-wing 5s ease-in-out infinite;
      }
      @keyframes mk-wing {
        0%, 100% { transform: scaleY(1); }
        8% { transform: scaleY(0.55); }
        16% { transform: scaleY(1); }
      }

      /* Kilau Bintang Emas pada Ujung Gonjong & Suntiang (Glint Star) */
      .mk-glint {
        animation: mk-glint var(--dur, 3s) ease-in-out infinite;
        animation-delay: var(--delay, 0s);
      }
      @keyframes mk-glint {
        0%, 100% { opacity: 0; transform: scale(0.2) rotate(0deg); }
        45% { opacity: 1; transform: scale(1) rotate(45deg); }
        60% { opacity: 0.2; transform: scale(0.6) rotate(60deg); }
      }

      /* Guguran Kelopak Bunga Melati Ranah Minang */
      .mk-petal {
        animation: mk-petal var(--dur, 14s) linear infinite;
        animation-delay: var(--delay, 0s);
      }
      @keyframes mk-petal {
        0% { transform: translate3d(0, 0, 0) rotate(0deg); opacity: 0; }
        10% { opacity: 0.85; }
        90% { opacity: 0.85; }
        100% { transform: translate3d(var(--dx, 30px), 105vh, 0) rotate(360deg); opacity: 0; }
      }

      /* Ayunan Lembut (Organic Floating & Sway) */
      .mk-float {
        animation: mk-float 6s ease-in-out infinite;
      }
      @keyframes mk-float {
        0%, 100% { transform: translate3d(0, 0, 0); }
        50% { transform: translate3d(0, -7px, 0); }
      }

      .mk-sway {
        transform-origin: 50% 100%;
        animation: mk-sway 7.5s ease-in-out infinite;
      }
      @keyframes mk-sway {
        0%, 100% { transform: rotate(-1deg); }
        50% { transform: rotate(1deg); }
      }

      .mk-bob {
        animation: mk-bob 2.2s ease-in-out infinite;
      }
      @keyframes mk-bob {
        0%, 100% { transform: translate3d(0, 0, 0); }
        50% { transform: translate3d(0, 6px, 0); }
      }

      .mk-spin-slow {
        animation: mk-spin 9s linear infinite;
      }
      @keyframes mk-spin {
        to { transform: rotate(360deg); }
      }

      /* ===================================================================
         CINEMATIC OPENING & VIDEO PEMBUKAAN MOTION PHYSICS
      ==================================================================== */
      /* 1. Cover Gate Exit */
      #cover-stage {
        transition: transform 1.6s cubic-bezier(0.5, 0, 0.75, 0.4), opacity 0.55s cubic-bezier(0.5, 0, 0.75, 0.4) 0.95s;
      }
      #cover-stage.descended {
        transform: translate3d(0, -62%, 0) scale(1.1);
        opacity: 0;
        pointer-events: none;
      }

      /* 2. Cinematic Mist Surge Waves */
      .surge-mist-1 { animation: mk-surge 1.7s cubic-bezier(0.4, 0, 0.6, 1) 0s forwards; }
      .surge-mist-2 { animation: mk-surge 1.9s cubic-bezier(0.4, 0, 0.6, 1) 0.2s forwards; }
      .surge-mist-3 { animation: mk-surge 2.1s cubic-bezier(0.4, 0, 0.6, 1) 0.4s forwards; }
      .surge-light-wash { animation: mk-wash 1.9s ease-in-out 0.15s forwards; }

      @keyframes mk-surge {
        0% { transform: translate3d(0, 110vh, 0); opacity: 0; }
        15% { opacity: 0.95; }
        75% { opacity: 0.95; }
        100% { transform: translate3d(0, -150%, 0); opacity: 0; }
      }

      @keyframes mk-wash {
        0% { opacity: 0; }
        35% { opacity: 0.85; }
        70% { opacity: 0.85; }
        100% { opacity: 0; }
      }

      /* 3. Landed Scene Zoom & Reveal */
      #landed-canvas {
        transform: scale(1.26) translate3d(0, -5%, 0);
        transform-origin: 50% 55%;
        transition: transform 2.2s cubic-bezier(0.16, 1, 0.3, 1);
      }
      #landed-canvas.landed {
        transform: scale(1) translate3d(0, 0%, 0);
      }

      /* Songket banner unroll */
      .songket-banner-wrap {
        clip-path: inset(0% 100% 0% 0%);
        opacity: 0.6;
        transition: clip-path 1.4s cubic-bezier(0.55, 0, 0.25, 1) 0.9s, opacity 1s ease 0.9s;
      }
      .landed .songket-banner-wrap {
        clip-path: inset(0% 0% 0% 0%);
        opacity: 1;
      }

      /* Suntiang float down */
      .suntiang-hero {
        opacity: 0;
        transform: translate3d(0, 16px, 0) scale(0.9);
        transition: opacity 1.2s cubic-bezier(0.16, 1, 0.3, 1) 1.3s, transform 1.2s cubic-bezier(0.16, 1, 0.3, 1) 1.3s;
      }
      .landed .suntiang-hero {
        opacity: 1;
        transform: translate3d(0, 0, 0) scale(1);
      }

      /* Couple Names De-blur and Glow */
      .names-hero {
        opacity: 0;
        filter: blur(6px);
        transform: translate3d(0, 8px, 0);
        transition: opacity 1.2s cubic-bezier(0.16, 1, 0.3, 1) 1.6s, filter 1.2s ease 1.6s, transform 1.2s cubic-bezier(0.16, 1, 0.3, 1) 1.6s;
      }
      .landed .names-hero {
        opacity: 1;
        filter: blur(0px);
        transform: translate3d(0, 0, 0);
      }

      /* Pengantin Front rise */
      .pengantin-hero {
        opacity: 0;
        transform: translate3d(0, 36px, 0);
        transition: opacity 1.3s cubic-bezier(0.16, 1, 0.3, 1) 1.7s, transform 1.3s cubic-bezier(0.16, 1, 0.3, 1) 1.7s;
      }
      .landed .pengantin-hero {
        opacity: 1;
        transform: translate3d(0, 0, 0);
      }

      /* Date badge & Scroll cue */
      .date-hero {
        opacity: 0;
        transform: translate3d(0, -10px, 0);
        transition: opacity 1.1s ease 1.8s, transform 1.1s ease 1.8s;
      }
      .landed .date-hero {
        opacity: 1;
        transform: translate3d(0, 0, 0);
      }

      .scroll-cue {
        opacity: 0;
        transition: opacity 0.9s ease 2.1s;
      }
      .landed .scroll-cue {
        opacity: 1;
      }

      /* Scroll Reveal Animasi */
      .reveal {
        opacity: 0;
        transform: translate3d(0, 22px, 0);
        transition: opacity 0.75s cubic-bezier(0.22, 1, 0.36, 1), transform 0.75s cubic-bezier(0.22, 1, 0.36, 1);
      }
      .reveal.active {
        opacity: 1;
        transform: none;
      }

      @media (prefers-reduced-motion: reduce) {
        .mk-mist, .mk-flag, .mk-eagle, .mk-glide-wing, .mk-glint, .mk-petal, .mk-float, .mk-sway, .mk-bob, .mk-spin-slow { animation: none !important; }
        #cover-stage, #landed-canvas, .songket-banner-wrap, .suntiang-hero, .names-hero, .pengantin-hero, .date-hero, .scroll-cue, .reveal { transition-duration: 1ms !important; opacity: 1; transform: none; filter: none; }
      }

      /* Custom Scrollbar */
      ::-webkit-scrollbar { width: 5px; }
      ::-webkit-scrollbar-track { background: #1E1612; }
      ::-webkit-scrollbar-thumb { background: #C9A227; border-radius: 9999px; }
    </style>
</head>

@php
    $evData          = $invitation->event_data ?? [];
    $primaryName     = !empty($evData['primary_name']) ? $evData['primary_name'] : 'Fadhil Rahman Syahputra, S.T.';
    $secondaryName   = !empty($evData['secondary_name']) ? $evData['secondary_name'] : 'Annisa Rahmadani, S.Ked.';
    $primaryNick     = Str::before($primaryName, ' ');
    $secondaryNick   = Str::before($secondaryName, ' ');
    
    $primaryParents  = !empty($evData['primary_title_parents']) ? $evData['primary_title_parents'] : 'Bapak Ir. Syafril Malin Kayo & Ibu Yusnimar';
    $secondaryParents= !empty($evData['secondary_title_parents']) ? $evData['secondary_title_parents'] : 'Bapak H. Zulkifli Dt. Rajo Bandaro & Ibu Hj. Nurhayati';
    $primaryIg       = !empty($evData['primary_instagram']) ? $evData['primary_instagram'] : 'fadhilrahman';
    $secondaryIg     = !empty($evData['secondary_instagram']) ? $evData['secondary_instagram'] : 'annisarahmadani';

    $s1 = $evData['session_1'] ?? [];
    $s2 = $evData['session_2'] ?? [];

    $s1Title   = !empty($s1['title']) ? $s1['title'] : 'Akad Nikah';
    $s1Date    = !empty($s1['date']) ? $s1['date'] : '2026-12-19';
    $s1Time    = !empty($s1['time']) ? $s1['time'] : '08.00 - 10.00 WIB';
    $s1Venue   = !empty($s1['venue']) ? $s1['venue'] : 'Masjid Raya Bukittinggi';
    $s1Address = !empty($s1['address']) ? $s1['address'] : 'Jl. Sudirman No. 12, Bukittinggi, Sumatera Barat';
    $s1Maps    = !empty($s1['maps_url']) ? $s1['maps_url'] : 'https://maps.google.com/?q=Bukittinggi';

    $s2Enabled = isset($s2['enabled']) ? $s2['enabled'] : true;
    $s2Title   = !empty($s2['title']) ? $s2['title'] : 'Baralek Gadang';
    $s2Date    = !empty($s2['date']) ? $s2['date'] : '2026-12-19';
    $s2Time    = !empty($s2['time']) ? $s2['time'] : '11.00 - 16.00 WIB';
    $s2Venue   = !empty($s2['venue']) ? $s2['venue'] : 'Rumah Gadang Kampuang Koto';
    $s2Address = !empty($s2['address']) ? $s2['address'] : 'Jorong Koto Tuo, Kec. IV Koto, Kabupaten Agam, Sumatera Barat';
    $s2Maps    = !empty($s2['maps_url']) ? $s2['maps_url'] : 'https://maps.google.com/?q=Kabupaten+Agam';

    $dateCarbon = \Carbon\Carbon::parse($s1Date);
    $dateShort  = $dateCarbon->format('d · m · Y');
    $dateLong   = $dateCarbon->locale('id')->isoFormat('dddd, D MMMM Y') ?: 'Sabtu, 19 Desember 2026';
    $targetTime = !empty($targetDate) ? $targetDate : ($s1Date . 'T08:00:00+07:00');

    $galleryImages = !empty($evData['gallery_images']) && is_array($evData['gallery_images']) ? $evData['gallery_images'] : [];
    $giftAccounts  = !empty($evData['gift_accounts']) && is_array($evData['gift_accounts']) ? $evData['gift_accounts'] : [
        ['bank' => 'BSI', 'account_number' => '7123456789', 'account_name' => 'Fadhil Rahman Syahputra'],
        ['bank' => 'Bank Nagari', 'account_number' => '1000987654321', 'account_name' => 'Annisa Rahmadani']
    ];

    $customMusicUrl = !empty($evData['music_file']) ? asset('storage/' . $evData['music_file']) : asset('images/themes/minang/royal/minang.m4a');
@endphp

<body class="font-body antialiased bg-mk-black text-mk-ink selection:bg-mk-gold selection:text-mk-black overflow-x-hidden min-h-screen">

  <!-- =====================================================================
       DESKTOP BACKGROUND WRAPPER (STANDAR JANIVA MINANGKABAU ROYAL)
  ====================================================================== -->
  <div class="pointer-events-none fixed inset-0 hidden overflow-hidden md:block" aria-hidden="true">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_50%_20%,#5a1418_0%,#2a0d0c_45%,#140a07_100%)]"></div>
    <div class="mk-songket absolute inset-0 opacity-[0.08]"></div>
  </div>

  <!-- =====================================================================
       CONTAINER UTAMA (MOBILE CANVAS 480PX SENTRAL)
  ====================================================================== -->
  <div class="relative mx-auto w-full max-w-[480px] bg-mk-paper shadow-[0_0_90px_rgba(0,0,0,0.7)] min-h-screen overflow-x-hidden">

    <!-- PARTIKEL KELOPAK MELATI (AMBIENT PETALS) -->
    <div id="petals-container" class="pointer-events-none fixed inset-0 z-30 overflow-hidden" aria-hidden="true"></div>

    <!-- TOAST NOTIFICATION -->
    <div id="toast-box" role="status" aria-live="polite"
         class="fixed top-6 left-1/2 -translate-x-1/2 z-[80] pointer-events-none opacity-0 -translate-y-6 transition-all duration-300">
      <div class="flex items-center gap-2.5 rounded-full bg-mk-red-2 text-mk-gold-3 border border-mk-gold/60 px-5 py-2.5 text-xs font-semibold shadow-2xl backdrop-blur-md">
        <svg class="w-4 h-4 text-mk-gold" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        <span id="toast-message">Berhasil disalin</span>
      </div>
    </div>

    <!-- ===================================================================
         CINEMATIC MIST SURGE OVERLAY (SEPERTI VIDEO PEMBUKAAN JANIVA)
    ==================================================================== -->
    <div id="mist-surge-stage" class="pointer-events-none fixed inset-0 z-[48] hidden overflow-hidden" aria-hidden="true">
      <!-- Mist Wave 1 -->
      <img src="{{ asset('images/themes/minang/royal/kabut-1.webp') }}"
           class="absolute top-0 h-auto w-[240%] max-w-none -left-[70%] surge-mist-1" alt="" />
      <!-- Mist Wave 2 -->
      <img src="{{ asset('images/themes/minang/royal/kabut-2.webp') }}"
           class="absolute top-0 h-auto w-[260%] max-w-none -left-[90%] surge-mist-2" alt="" />
      <!-- Mist Wave 3 -->
      <img src="{{ asset('images/themes/minang/royal/kabut-3.webp') }}"
           class="absolute top-0 h-auto w-[230%] max-w-none -left-[60%] surge-mist-3" alt="" />
      <!-- Ivory Fog Light Wash -->
      <div class="absolute inset-0 bg-[#F6F1E6] surge-light-wash"></div>
    </div>

    <!-- ===================================================================
         HERO SECTION (100SVH): COVER PEMBUKA + LANDED RANAH MINANG SCENE
    ==================================================================== -->
    <section id="hero" class="relative h-[100dvh] min-h-[540px] w-full overflow-hidden bg-[#cfe0ea]">

      <!-- ===============================================================
           A. THE LANDED SCENE (LEMBAH RANAH MINANG & RUMAH GADANG)
      ================================================================ -->
      <div id="landed-canvas" class="absolute inset-0">
        
        <!-- Sawah Plate & Panorama -->
        <img src="{{ asset('images/themes/minang/royal/sawah-plate.webp') }}"
             alt="Lembah Sawah Minangkabau"
             class="absolute inset-[-3%] h-full w-full object-cover"
             style="object-position: 50% 62%;" />

        <!-- Ambient Mist Floating across valley -->
        <div class="mk-mist absolute bottom-[34%] -left-[40%] w-[180%] pointer-events-none opacity-75"
             style="--dur: 46s; --dx: 60px;">
          <img src="{{ asset('images/themes/minang/royal/kabut-3.webp') }}" alt="" class="w-full h-auto" />
        </div>

        <!-- Rumah Gadang Day & Gonjongs -->
        <div class="absolute bottom-[36.5%] left-[14%] w-[72%]">
          <div class="relative">
            <img src="{{ asset('images/themes/minang/royal/rumahgadang-day.webp') }}"
                 alt="Rumah Gadang Adat Minangkabau"
                 class="h-auto w-full drop-shadow-[0_10px_14px_rgba(40,40,20,0.35)]" />

            <!-- Roof mist on top -->
            <img src="{{ asset('images/themes/minang/royal/kabut-1.webp') }}"
                 alt="" class="absolute -inset-x-[12%] -top-[18%] h-[70%] w-full object-fill opacity-40" />

            <!-- 6 Gonjong Horn Glints & Shimmers (K Coordinates: 5.1%, 16.9%, 27.5%, 72.7%, 83.1%, 94.9%) -->
            <div class="absolute h-6 w-6 -translate-x-1/2 -translate-y-1/2 left-[5.1%] top-[2.7%]">
              <span class="mk-glint block w-full h-full text-mk-gold-3" style="--dur: 2.8s; --delay: 0.1s;">✦</span>
            </div>
            <div class="absolute h-6 w-6 -translate-x-1/2 -translate-y-1/2 left-[16.9%] top-[8.1%]">
              <span class="mk-glint block w-full h-full text-mk-gold-3" style="--dur: 3.2s; --delay: 0.5s;">✦</span>
            </div>
            <div class="absolute h-6 w-6 -translate-x-1/2 -translate-y-1/2 left-[27.5%] top-[13%]">
              <span class="mk-glint block w-full h-full text-mk-gold-3" style="--dur: 3.0s; --delay: 0.9s;">✦</span>
            </div>
            <div class="absolute h-6 w-6 -translate-x-1/2 -translate-y-1/2 left-[72.7%] top-[13.8%]">
              <span class="mk-glint block w-full h-full text-mk-gold-3" style="--dur: 2.9s; --delay: 0.8s;">✦</span>
            </div>
            <div class="absolute h-6 w-6 -translate-x-1/2 -translate-y-1/2 left-[83.1%] top-[9%]">
              <span class="mk-glint block w-full h-full text-mk-gold-3" style="--dur: 3.1s; --delay: 0.4s;">✦</span>
            </div>
            <div class="absolute h-6 w-6 -translate-x-1/2 -translate-y-1/2 left-[94.9%] top-[2.8%]">
              <span class="mk-glint block w-full h-full text-mk-gold-3" style="--dur: 2.7s; --delay: 0.2s;">✦</span>
            </div>
          </div>
        </div>

        <!-- 2 Marawa Flags Fluttering (Kiri & Kanan) -->
        <div class="absolute bottom-[31%] left-[2%] h-[22%] pointer-events-none">
          <svg viewBox="0 0 60 240" class="mk-flag h-full w-auto">
            <line x1="12" y1="2" x2="12" y2="238" stroke="#5a3825" stroke-width="3" stroke-linecap="round"/>
            <path d="M12 18 C30 24 45 14 56 26 C44 48 30 52 12 56 Z" fill="#E8B024"/>
            <path d="M12 56 C32 62 46 54 58 66 C46 88 32 92 12 96 Z" fill="#A81D24"/>
            <path d="M12 96 C30 102 44 94 56 106 C44 128 28 132 12 136 Z" fill="#1A1412"/>
          </svg>
        </div>
        <div class="absolute bottom-[31%] right-[2%] h-[22%] pointer-events-none -scale-x-100">
          <svg viewBox="0 0 60 240" class="mk-flag h-full w-auto" style="--delay: -1.3s;">
            <line x1="12" y1="2" x2="12" y2="238" stroke="#5a3825" stroke-width="3" stroke-linecap="round"/>
            <path d="M12 18 C30 24 45 14 56 26 C44 48 30 52 12 56 Z" fill="#E8B024"/>
            <path d="M12 56 C32 62 46 54 58 66 C46 88 32 92 12 96 Z" fill="#A81D24"/>
            <path d="M12 96 C30 102 44 94 56 106 C44 128 28 132 12 136 Z" fill="#1A1412"/>
          </svg>
        </div>

        <!-- Elang Melayang di Langit Ranah Minang -->
        <div class="mk-eagle absolute top-[28%] pointer-events-none z-10" style="--dur: 46s;">
          <svg viewBox="0 0 80 30" class="h-6 w-16 text-[#4a3528]" fill="currentColor">
            <path class="mk-glide-wing" d="M40 18 C28 6 12 2 0 6 C8 14 22 18 36 20 Z" />
            <path class="mk-glide-wing" d="M40 18 C52 6 68 2 80 6 C72 14 58 18 44 20 Z" />
            <ellipse cx="40" cy="18" rx="4" ry="7" />
          </svg>
        </div>

        <!-- Pengantin Tradisional di Depan Rumah Gadang (pengantin-front.webp) -->
        <div class="pengantin-hero absolute bottom-[6%] left-[38.5%] w-[23%] z-10 pointer-events-none">
          <img src="{{ asset('images/themes/minang/royal/pengantin-front.webp') }}"
               alt="Pengantin Minang"
               class="h-auto w-full drop-shadow-[0_12px_14px_rgba(30,30,10,0.45)]" />
        </div>

        <!-- Bottom Ground Mist -->
        <div class="mk-mist absolute -bottom-[2%] -left-[30%] w-[160%] pointer-events-none opacity-50 z-10"
             style="--dur: 38s; --dx: -50px;">
          <img src="{{ asset('images/themes/minang/royal/kabut-2.webp') }}" alt="" class="w-full h-auto" />
        </div>

        <!-- Top Suntiang & Songket Bentang Header -->
        <div class="absolute inset-x-0 top-[6%] z-10 flex flex-col items-center px-3 text-center">
          
          <!-- Suntiang Mahkota Emas Berkilau -->
          <div class="suntiang-hero relative z-10 -mb-5 w-[30%]">
            <img src="{{ asset('images/themes/minang/royal/suntiang.webp') }}"
                 alt="Suntiang Emas Minangkabau"
                 class="h-auto w-full drop-shadow-[0_6px_10px_rgba(80,40,0,0.45)]" />
            <span class="mk-glint absolute -top-1 left-1/2 -translate-x-1/2 text-sm text-mk-gold-3" style="--dur: 2.5s;">✦</span>
            <span class="mk-glint absolute top-4 left-2 text-xs text-mk-gold-3" style="--dur: 3.1s; --delay: 0.7s;">✦</span>
            <span class="mk-glint absolute top-4 right-2 text-xs text-mk-gold-3" style="--dur: 2.8s; --delay: 1.2s;">✦</span>
          </div>

          <!-- Songket Bentang Banner Unrolling -->
          <div class="songket-banner-wrap relative w-full">
            <img src="{{ asset('images/themes/minang/royal/songket-bentang.webp') }}"
                 alt="Songket Tenun Minang"
                 class="h-auto w-full drop-shadow-[0_14px_18px_rgba(60,10,10,0.45)]" />
            
            <div class="absolute inset-x-[20%] inset-y-[14%] flex flex-col items-center justify-center">
              <p class="font-caps text-[9.5px] uppercase tracking-[0.42em] text-mk-gold-3">Baralek Gadang</p>
              
              <!-- Nama Pasangan Shimmering Gold -->
              <h1 class="names-hero font-display mt-0.5 whitespace-nowrap text-[clamp(1.5rem,7.4vw,2.15rem)] font-semibold leading-none text-mk-paper [text-shadow:0_2px_10px_rgba(40,0,0,0.55)]">
                {{ $primaryNick }}
                <span class="mx-1.5 align-middle text-[0.8em] font-normal italic text-mk-gold-3">&amp;</span>
                {{ $secondaryNick }}
              </h1>
            </div>
          </div>

          <!-- Wedding Date Pill Badge -->
          <div class="date-hero mt-2">
            <p class="font-caps rounded-full bg-[rgba(30,22,18,0.55)] px-4 py-1 text-[12px] tracking-[0.32em] text-mk-paper shadow-md border border-mk-gold/30">
              {{ $dateShort }}
            </p>
          </div>

        </div>

        <!-- Bouncing "Gulir" Scroll Cue -->
        <div class="scroll-cue absolute inset-x-0 bottom-3 z-10 flex flex-col items-center text-mk-paper pointer-events-none">
          <span class="font-caps text-[9px] uppercase tracking-[0.4em] [text-shadow:0_1px_6px_rgba(0,0,0,0.6)]">Gulir</span>
          <svg class="mk-bob h-5 w-5 drop-shadow text-mk-gold" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
          </svg>
        </div>

      </div>

      <!-- ===============================================================
           B. THE COVER STAGE (PUNCAK MARAPI & AMPLOP UNDANGAN)
      ================================================================ -->
      <div id="cover-stage" class="absolute inset-0 z-30 flex flex-col justify-between overflow-hidden bg-[#241312]">
        
        <!-- Puncak Plate Background with Subtle Ken Burns Zoom -->
        <div class="absolute inset-0 z-0">
          <img src="{{ asset('images/themes/minang/royal/puncak-plate.webp') }}"
               alt="Puncak Gunung Marapi"
               class="h-full w-full object-cover"
               style="object-position: 50% 6%;" />
          
          <!-- Glowing Sun Aura -->
          <div class="mk-float absolute left-[calc(50%-5rem)] top-[calc(50%-5rem)] h-40 w-40 rounded-full bg-[radial-gradient(closest-side,rgba(255,236,170,0.7),rgba(255,214,140,0)_100%)] pointer-events-none"></div>

          <!-- Drifting Summit Mists -->
          <div class="mk-mist absolute -bottom-[6%] -left-[35%] w-[170%] opacity-85 pointer-events-none" style="--dur: 34s; --dx: 50px;">
            <img src="{{ asset('images/themes/minang/royal/kabut-2.webp') }}" alt="" class="w-full h-auto" />
          </div>
          <div class="mk-mist absolute -bottom-[12%] -left-[30%] w-[160%] opacity-95 pointer-events-none" style="--dur: 42s; --dx: -46px;">
            <img src="{{ asset('images/themes/minang/royal/kabut-1.webp') }}" alt="" class="w-full h-auto" />
          </div>

          <!-- Dark Gradient Shade -->
          <div class="absolute inset-0 bg-gradient-to-b from-[#140A07]/35 via-transparent to-[#1E1612]/90"></div>
        </div>

        <!-- Cover Header Text -->
        <div class="relative z-10 pt-5 sm:pt-[7%] px-4 sm:px-6 text-center">
          <p class="font-caps text-[10px] sm:text-[11px] uppercase tracking-[0.45em] text-mk-red-2 font-bold drop-shadow">
            Pulang Baralek
          </p>
          <h1 class="font-display mt-2 sm:mt-3 text-3xl sm:text-[2.75rem] font-semibold leading-[0.95] text-mk-ink [text-shadow:0_2px_18px_rgba(255,246,220,0.9)]">
            {{ $primaryNick }}
            <span class="font-display mx-2 align-middle text-2xl sm:text-[2rem] font-normal italic text-mk-red">&amp;</span>
            {{ $secondaryNick }}
          </h1>
          <p class="font-caps mt-2 sm:mt-3 text-[11px] sm:text-[12px] tracking-[0.32em] text-mk-ink font-semibold">
            {{ $dateShort }}
          </p>
        </div>

        <!-- Cover Couple Photo in Arched Frame -->
        <div class="relative z-10 flex justify-center h-[28%] sm:h-[36%] px-6 my-auto">
          <div class="h-full aspect-[4/5] rounded-t-full rounded-b-xl overflow-hidden border-2 border-mk-gold shadow-2xl p-1 bg-mk-paper/30 backdrop-blur-sm">
            <img src="{{ asset('images/themes/minang/royal/couple-bride.webp') }}"
                 alt="Foto Pengantin"
                 class="h-full w-full object-cover rounded-t-full rounded-b-lg" />
          </div>
        </div>

        <!-- Cover Envelope Card (Tamu & Tombol Buka Undangan) -->
        <div class="relative z-10 px-4 sm:px-8 pb-5 sm:pb-[8%] flex justify-center">
          <div class="w-full max-w-[310px] overflow-hidden rounded-[16px] bg-[rgba(251,243,228,0.96)] text-center shadow-[0_18px_30px_-12px_rgba(60,30,10,0.55)] border border-mk-gold/40 backdrop-blur-md">
            
            <!-- Songket Top Border -->
            <div class="mk-songket h-3.5 w-full border-b border-mk-gold"></div>

            <div class="px-6 pb-5 pt-3.5">
              <p class="font-caps text-[9.5px] uppercase tracking-[0.3em] text-mk-ink-2 font-semibold">Kepada Yth. Bapak/Ibu/Saudara/i:</p>
              <p class="font-display mt-1 text-[20px] font-bold leading-tight text-mk-ink line-clamp-2">
                {{ !empty($guestName) ? $guestName : 'Tamu Undangan' }}
              </p>
              @if(!empty($guestAddress))
                <p class="text-[11px] text-mk-ink-2 italic mt-0.5">di {{ $guestAddress }}</p>
              @endif

              <!-- Tombol Buka Undangan -->
              <button id="open-btn" type="button"
                class="group mt-3.5 inline-flex min-h-[44px] items-center gap-2 rounded-full bg-mk-red hover:bg-mk-red-2 text-mk-paper px-6 text-[12.5px] font-bold shadow-[0_8px_18px_-8px_rgba(122,18,24,0.8)] ring-1 ring-mk-gold transition-all duration-300 hover:scale-105 active:scale-95">
                <svg class="h-4 w-4 text-mk-gold-3 transition group-hover:-rotate-12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>Buka Undangan</span>
              </button>
            </div>

          </div>
        </div>

      </div>

    </section>

    <!-- ===================================================================
         1. AYAT & SALAMAIK DATANG (NGARAI SIANOK & CARANO PARALLAX)
    ==================================================================== -->
    <section id="salam" class="relative min-h-[140svh] overflow-x-clip bg-mk-paper-2">
      
      <!-- Ngarai Sianok Plate Background -->
      <div class="absolute inset-0 pointer-events-none">
        <img src="{{ asset('images/themes/minang/royal/ngarai-plate.webp') }}"
             alt="Ngarai Sianok" loading="lazy" decoding="async"
             class="h-full w-full object-cover opacity-80"
             style="object-position: 50% 40%;" />
        <div class="absolute inset-0 bg-gradient-to-b from-[#FBF3E4]/90 via-[#FBF3E4]/40 to-[#FBF3E4]"></div>

        <!-- Mists in Canyon -->
        <div class="mk-mist absolute top-[48%] -left-[40%] w-[180%] opacity-80" style="--dur: 40s; --dx: 60px;">
          <img src="{{ asset('images/themes/minang/royal/kabut-3.webp') }}" alt="" loading="lazy" decoding="async" class="w-full h-auto" />
        </div>
        <div class="mk-mist absolute top-[62%] -left-[30%] w-[160%] opacity-60" style="--dur: 52s; --dx: -50px;">
          <img src="{{ asset('images/themes/minang/royal/kabut-2.webp') }}" alt="" loading="lazy" decoding="async" class="w-full h-auto" />
        </div>

        <!-- Parallax Canyon Rim -->
        <div id="ngarai-rim" class="absolute inset-x-[-12%] bottom-[-4%] transition-transform duration-75">
          <img src="{{ asset('images/themes/minang/royal/ngarai-rim.webp') }}" alt="" loading="lazy" decoding="async" class="h-auto w-full" />
        </div>
      </div>

      <!-- Salam & Ayat Card Content -->
      <div class="relative z-10 px-6 pt-[16svh] pb-24 text-center">
        
        <!-- Carano Adat Vessel -->
        <div class="reveal mx-auto mb-4 w-16">
          <img src="{{ asset('images/themes/minang/royal/carano.webp') }}"
               alt="Carano Adat Minangkabau" loading="lazy" decoding="async"
               class="w-full h-auto drop-shadow-md mx-auto" />
        </div>

        <p class="reveal font-caps text-[11px] uppercase tracking-[0.45em] text-mk-red font-bold">
          Assalamualaikum Warahmatullahi Wabarakatuh
        </p>

        <h2 class="reveal font-display mt-2 text-[2.75rem] font-semibold italic text-mk-ink">
          Salamaik Datang
        </h2>

        <!-- Ornate Kaluak Divider -->
        <div class="reveal flex justify-center my-4 text-mk-gold">
          <svg viewBox="0 0 240 24" class="w-48 h-auto">
            <path d="M10 12 Q60 6 120 12 T230 12" stroke="#C9A227" stroke-width="1.2" fill="none"/>
            <circle cx="120" cy="12" r="4" fill="#9E1B22" stroke="#C9A227" stroke-width="1"/>
            <circle cx="90" cy="12" r="2" fill="#C9A227"/>
            <circle cx="150" cy="12" r="2" fill="#C9A227"/>
          </svg>
        </div>

        <p class="reveal text-xs text-mk-ink leading-relaxed max-w-sm mx-auto mb-6">
          Barek samo dipikua, ringan samo dijinjiang. Dengan memohon rahmat dan ridho Allah SWT, kami bermaksud menyelenggarakan syukuran pernikahan kami:
        </p>

        <!-- Ayat Ar-Rum 21 Card -->
        <div class="reveal rounded-2xl bg-mk-paper/95 p-6 border border-mk-gold/50 shadow-xl max-w-sm mx-auto backdrop-blur-sm relative">
          <!-- Kaluak Corner Accents -->
          <img src="{{ asset('images/themes/minang/royal/kaluak-corner.webp') }}" class="absolute top-1 left-1 w-6 h-6 opacity-70" alt="" />
          <img src="{{ asset('images/themes/minang/royal/kaluak-corner.webp') }}" class="absolute top-1 right-1 w-6 h-6 opacity-70 -scale-x-100" alt="" />
          <img src="{{ asset('images/themes/minang/royal/kaluak-corner.webp') }}" class="absolute bottom-1 left-1 w-6 h-6 opacity-70 -scale-y-100" alt="" />
          <img src="{{ asset('images/themes/minang/royal/kaluak-corner.webp') }}" class="absolute bottom-1 right-1 w-6 h-6 opacity-70 -scale-100" alt="" />

          <p class="font-cormorant italic text-sm md:text-base text-mk-ink leading-relaxed text-center">
            &ldquo;Dan di antara tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu pasangan hidup dari jenismu sendiri, supaya kamu merasa tenteram kepadanya, dan dijadikan-Nya di antaramu rasa kasih dan sayang.&rdquo;
          </p>
          <p class="font-caps text-[10px] tracking-[0.25em] text-mk-gold-2 font-bold uppercase mt-3">
            QS. Ar-Rum : 21
          </p>
        </div>

      </div>

    </section>

    <!-- ===================================================================
         2. PROFIL MEMPELAI (ANAK DARO & MARAPULAI DENGAN BINGKAI KALUAK)
    ==================================================================== -->
    <section id="mempelai" class="py-20 px-6 mk-paper relative border-t border-mk-gold/30">
      <div class="max-w-md mx-auto text-center">
        
        <div class="reveal mb-12">
          <p class="font-caps text-[10px] uppercase tracking-[0.35em] text-mk-red font-bold mb-1">Mempelai Minangkabau</p>
          <h2 class="font-display text-3xl font-bold text-mk-ink">Anak Daro &amp; Marapulai</h2>
          <div class="h-0.5 w-16 bg-mk-gold mx-auto mt-3"></div>
        </div>

        <div class="space-y-12">
          
          <!-- Pengantin Wanita (Anak Daro) -->
          <div class="reveal relative flex flex-col items-center">
            <!-- Arched Photo Frame -->
            <div class="relative w-44 h-56 rounded-t-full rounded-b-2xl overflow-hidden border-2 border-mk-gold shadow-2xl p-1 bg-mk-paper-2">
              <img src="{{ !empty($evData['bride_photo']) ? asset('storage/' . $evData['bride_photo']) : asset('images/themes/minang/royal/couple-bride.webp') }}"
                   alt="{{ $secondaryName }}" loading="lazy" decoding="async"
                   class="h-full w-full object-cover rounded-t-full rounded-b-xl" />
            </div>

            <!-- Kaluak Crest Badge -->
            <img src="{{ asset('images/themes/minang/royal/kaluak-crest.webp') }}" loading="lazy" decoding="async" class="w-12 h-auto my-3" alt="Kaluak" />

            <span class="font-caps text-[10px] uppercase tracking-[0.25em] text-mk-gold-2 font-bold">Anak Daro (Mempelai Wanita)</span>
            <h3 class="font-display text-2xl font-bold text-mk-ink mt-1">{{ $secondaryName }}</h3>
            <p class="text-xs text-mk-ink-2 mt-1">Putri tercinta dari:</p>
            <p class="text-xs font-semibold text-mk-ink">{{ $secondaryParents }}</p>
            
            <a href="https://instagram.com/{{ $secondaryIg }}" target="_blank"
               class="mt-3 inline-flex items-center gap-1.5 text-xs text-mk-red font-semibold hover:text-mk-gold transition">
              <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
              <span>{{ '@' . $secondaryIg }}</span>
            </a>
          </div>

          <!-- Tanda Sambung & Simbol Cinta -->
          <div class="reveal flex items-center justify-center gap-4 text-mk-red">
            <div class="h-px w-12 bg-mk-gold/60"></div>
            <span class="font-display text-3xl italic text-mk-red">&amp;</span>
            <div class="h-px w-12 bg-mk-gold/60"></div>
          </div>

          <!-- Pengantin Pria (Marapulai) -->
          <div class="reveal relative flex flex-col items-center">
            <!-- Arched Photo Frame -->
            <div class="relative w-44 h-56 rounded-t-full rounded-b-2xl overflow-hidden border-2 border-mk-gold shadow-2xl p-1 bg-mk-paper-2">
              <img src="{{ !empty($evData['groom_photo']) ? asset('storage/' . $evData['groom_photo']) : asset('images/themes/minang/royal/couple-groom.webp') }}"
                   alt="{{ $primaryName }}" loading="lazy" decoding="async"
                   class="h-full w-full object-cover rounded-t-full rounded-b-xl" />
            </div>

            <!-- Kaluak Crest Badge -->
            <img src="{{ asset('images/themes/minang/royal/kaluak-crest.webp') }}" loading="lazy" decoding="async" class="w-12 h-auto my-3" alt="Kaluak" />

            <span class="font-caps text-[10px] uppercase tracking-[0.25em] text-mk-gold-2 font-bold">Marapulai (Mempelai Pria)</span>
            <h3 class="font-display text-2xl font-bold text-mk-ink mt-1">{{ $primaryName }}</h3>
            <p class="text-xs text-mk-ink-2 mt-1">Putra tercinta dari:</p>
            <p class="text-xs font-semibold text-mk-ink">{{ $primaryParents }}</p>

            <a href="https://instagram.com/{{ $primaryIg }}" target="_blank"
               class="mt-3 inline-flex items-center gap-1.5 text-xs text-mk-red font-semibold hover:text-mk-gold transition">
              <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
              <span>{{ '@' . $primaryIg }}</span>
            </a>
          </div>

        </div>

      </div>
    </section>

    <!-- ===================================================================
         3. HITUNG MUNDUR WAKTU (COUNTDOWN & GOOGLE CALENDAR)
    ==================================================================== -->
    <section class="py-16 px-6 mk-paper-2 border-t border-mk-gold/30 relative text-center">
      <div class="max-w-md mx-auto">
        
        <div class="reveal mb-8">
          <p class="font-caps text-[10px] uppercase tracking-[0.3em] text-mk-red font-bold mb-1">Mambilang Hari</p>
          <h2 class="font-display text-3xl font-bold text-mk-ink">Menuju Hari Bahagia</h2>
          <p class="text-xs text-mk-ink-2 mt-1">{{ $dateLong }}</p>
        </div>

        <!-- Kartu Digital Countdown -->
        <div class="reveal grid grid-cols-4 gap-2.5 max-w-xs mx-auto mb-6">
          <div class="rounded-xl bg-mk-paper p-3 border border-mk-gold/50 shadow-md">
            <span id="cd-days" class="font-display text-2xl font-bold text-mk-red">00</span>
            <span class="block font-caps text-[9px] uppercase tracking-wider text-mk-ink-2 mt-0.5">Hari</span>
          </div>
          <div class="rounded-xl bg-mk-paper p-3 border border-mk-gold/50 shadow-md">
            <span id="cd-hours" class="font-display text-2xl font-bold text-mk-red">00</span>
            <span class="block font-caps text-[9px] uppercase tracking-wider text-mk-ink-2 mt-0.5">Jam</span>
          </div>
          <div class="rounded-xl bg-mk-paper p-3 border border-mk-gold/50 shadow-md">
            <span id="cd-minutes" class="font-display text-2xl font-bold text-mk-red">00</span>
            <span class="block font-caps text-[9px] uppercase tracking-wider text-mk-ink-2 mt-0.5">Menit</span>
          </div>
          <div class="rounded-xl bg-mk-paper p-3 border border-mk-gold/50 shadow-md">
            <span id="cd-seconds" class="font-display text-2xl font-bold text-mk-red">00</span>
            <span class="block font-caps text-[9px] uppercase tracking-wider text-mk-ink-2 mt-0.5">Detik</span>
          </div>
        </div>

        <!-- Tombol Google Calendar -->
        <div class="reveal">
          @php $mkCalDate = str_replace('-', '', $s1Date); @endphp
          <a href="https://calendar.google.com/calendar/render?action=TEMPLATE&text={{ urlencode('Pernikahan ' . $primaryNick . ' & ' . $secondaryNick) }}&dates={{ $mkCalDate }}T010000Z/{{ $mkCalDate }}T090000Z&details={{ urlencode('Baralek Gadang Adat Minangkabau ' . $primaryName . ' & ' . $secondaryName) }}&location={{ urlencode($s1Venue . ', ' . $s1Address) }}"
             target="_blank"
             class="inline-flex items-center gap-2 rounded-full bg-mk-paper border border-mk-gold px-6 py-2.5 text-xs font-semibold text-mk-ink shadow-sm hover:bg-mk-gold hover:text-mk-black transition">
            <svg class="w-4 h-4 text-mk-red" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span>Simpan ka Google Calendar</span>
          </a>
        </div>

      </div>
    </section>

    <!-- ===================================================================
         4. RANGKAIAN ACARA (AKAD NIKAH & BARALEK GADANG)
    ==================================================================== -->
    <section id="acara" class="py-20 px-6 mk-paper relative border-t border-mk-gold/30">
      
      <!-- Tabir Swag Valance Drape -->
      <div class="absolute top-0 inset-x-0 pointer-events-none">
        <img src="{{ asset('images/themes/minang/royal/tabir-swag.webp') }}" alt="" class="w-full h-auto drop-shadow-md" />
      </div>

      <div class="max-w-md mx-auto text-center relative z-10 pt-8">
        
        <div class="reveal mb-12">
          <p class="font-caps text-[10px] uppercase tracking-[0.35em] text-mk-red font-bold mb-1">Rundown Adat</p>
          <h2 class="font-display text-3xl font-bold text-mk-ink">Rangkaian Acara</h2>
          <div class="h-0.5 w-16 bg-mk-gold mx-auto mt-3"></div>
        </div>

        <div class="space-y-8">
          
          <!-- Sesi 1: Akad Nikah -->
          <div class="reveal rounded-2xl bg-mk-paper-2 p-6 border border-mk-gold/60 shadow-xl relative text-center overflow-hidden">
            <div class="mk-songket h-2 w-full absolute top-0 left-0"></div>
            
            <span class="font-caps text-[10px] uppercase tracking-[0.3em] text-mk-red font-bold">Prosesi Ijab Qabul</span>
            <h3 class="font-display text-2xl font-bold text-mk-ink mt-1 mb-3">{{ $s1Title }}</h3>
            
            <div class="my-4 inline-block px-4 py-1.5 rounded-full bg-mk-paper border border-mk-gold/40 text-xs font-semibold text-mk-ink">
              {{ $dateLong }}
            </div>
            
            <p class="font-mono text-sm font-bold text-mk-red mb-3">{{ $s1Time }}</p>
            <p class="font-display font-semibold text-base text-mk-ink">{{ $s1Venue }}</p>
            <p class="text-xs text-mk-ink-2 max-w-xs mx-auto mt-1 leading-relaxed">{{ $s1Address }}</p>
            
            <a href="{{ $s1Maps }}" target="_blank"
               class="mt-5 inline-flex items-center gap-1.5 rounded-full bg-mk-red text-mk-paper px-5 py-2 text-xs font-semibold hover:bg-mk-red-2 transition shadow">
              <svg class="w-3.5 h-3.5 text-mk-gold-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              <span>Petunjuk Lokasi (Google Maps)</span>
            </a>
          </div>

          <!-- Sesi 2: Baralek Gadang -->
          @if($s2Enabled)
          <div class="reveal rounded-2xl bg-mk-paper-2 p-6 border border-mk-gold/60 shadow-xl relative text-center overflow-hidden">
            <div class="mk-songket h-2 w-full absolute top-0 left-0"></div>
            
            <span class="font-caps text-[10px] uppercase tracking-[0.3em] text-mk-red font-bold">Resepsi Pernikahan</span>
            <h3 class="font-display text-2xl font-bold text-mk-ink mt-1 mb-3">{{ $s2Title }}</h3>
            
            <div class="my-4 inline-block px-4 py-1.5 rounded-full bg-mk-paper border border-mk-gold/40 text-xs font-semibold text-mk-ink">
              {{ $dateLong }}
            </div>
            
            <p class="font-mono text-sm font-bold text-mk-red mb-3">{{ $s2Time }}</p>
            <p class="font-display font-semibold text-base text-mk-ink">{{ $s2Venue }}</p>
            <p class="text-xs text-mk-ink-2 max-w-xs mx-auto mt-1 leading-relaxed">{{ $s2Address }}</p>
            
            <a href="{{ $s2Maps }}" target="_blank"
               class="mt-5 inline-flex items-center gap-1.5 rounded-full bg-mk-red text-mk-paper px-5 py-2 text-xs font-semibold hover:bg-mk-red-2 transition shadow">
              <svg class="w-3.5 h-3.5 text-mk-gold-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              <span>Petunjuk Lokasi (Google Maps)</span>
            </a>
          </div>
          @endif

        </div>

      </div>
    </section>

    <!-- ===================================================================
         5. KISAH KASIH (TAMBO KASIAH / LOVE STORY TIMELINE)
    ==================================================================== -->
    <section class="py-20 px-6 mk-paper-2 border-t border-mk-gold/30 relative">
      <div class="max-w-md mx-auto text-center">
        
        <div class="reveal mb-12">
          <p class="font-caps text-[10px] uppercase tracking-[0.35em] text-mk-red font-bold mb-1">Tambo Kasiah</p>
          <h2 class="font-display text-3xl font-bold text-mk-ink">Kisah Kasih Kami</h2>
          <div class="h-0.5 w-16 bg-mk-gold mx-auto mt-3"></div>
        </div>

        <!-- Ilustrasi Pengantin Menatap Lembah (pengantin-back.webp) -->
        <div class="reveal mx-auto mb-8 w-28">
          <img src="{{ asset('images/themes/minang/royal/pengantin-back.webp') }}"
               alt="Kisah Kasih"
               class="w-full h-auto drop-shadow-md mx-auto" />
        </div>

        <div class="relative border-l-2 border-mk-gold/60 ml-4 md:ml-8 text-left space-y-8">
          
          <div class="reveal relative pl-6">
            <span class="absolute -left-[9px] top-1 h-4 w-4 rounded-full bg-mk-red border-2 border-mk-gold"></span>
            <span class="font-caps text-[10px] tracking-widest text-mk-red font-bold">2019 — Perkenalan (Marantau)</span>
            <h4 class="font-display text-base font-bold text-mk-ink mt-0.5">Awal Bersua di Ranah Rantau</h4>
            <p class="text-xs text-mk-ink-2 mt-1 leading-relaxed">
              Pertemuan pertama di perantauan saat menempuh pendidikan. Berbagi cerita tentang kampung halaman dan menguatkan asa di negeri orang.
            </p>
          </div>

          <div class="reveal relative pl-6">
            <span class="absolute -left-[9px] top-1 h-4 w-4 rounded-full bg-mk-red border-2 border-mk-gold"></span>
            <span class="font-caps text-[10px] tracking-widest text-mk-red font-bold">2023 — Manjapuik Tando</span>
            <h4 class="font-display text-base font-bold text-mk-ink mt-0.5">Pertemuan Dua Keluarga Besar</h4>
            <p class="text-xs text-mk-ink-2 mt-1 leading-relaxed">
              Keluarga besar marapulai bertandang menjalin silaturahmi adat, mambao sirih di dalam carano, serta meneguhkan niat suci melangkah ke jenjang pelaminan.
            </p>
          </div>

          <div class="reveal relative pl-6">
            <span class="absolute -left-[9px] top-1 h-4 w-4 rounded-full bg-mk-red border-2 border-mk-gold"></span>
            <span class="font-caps text-[10px] tracking-widest text-mk-red font-bold">2026 — Pulang Baralek</span>
            <h4 class="font-display text-base font-bold text-mk-ink mt-0.5">Janji Suci di Rumah Gadang</h4>
            <p class="text-xs text-mk-ink-2 mt-1 leading-relaxed">
              Pulang basamo ka Ranah Minang, mengikat janji suci disaksikan sanak saudara dan kerabat tercinta dalam suasana Baralek Gadang yang syahdu.
            </p>
          </div>

        </div>

      </div>
    </section>

    <!-- ===================================================================
         6. GALERI FOTO ADAT & LIGHTBOX
    ==================================================================== -->
    <section id="galeri" class="py-20 px-6 mk-paper border-t border-mk-gold/30 relative">
      <div class="max-w-md mx-auto text-center">
        
        <div class="reveal mb-12">
          <p class="font-caps text-[10px] uppercase tracking-[0.35em] text-mk-red font-bold mb-1">Momen Bahagia</p>
          <h2 class="font-display text-3xl font-bold text-mk-ink">Galeri Kenangan</h2>
          <div class="h-0.5 w-16 bg-mk-gold mx-auto mt-3"></div>
        </div>

        <div class="grid grid-cols-2 gap-3.5">
          @php
            $defaultGallery = [
              ['url' => asset('images/themes/minang/royal/couple-bride.webp'), 'caption' => 'Anak Daro memakai Suntiang Emas'],
              ['url' => asset('images/themes/minang/royal/couple-groom.webp'), 'caption' => 'Marapulai memakai Deta & Saluak Adat'],
              ['url' => asset('images/themes/minang/royal/sawah-plate.webp'), 'caption' => 'Lanskap Sawah Ranah Minang'],
              ['url' => asset('images/themes/minang/royal/danau-plate.webp'), 'caption' => 'Pesona Danau Singkarak']
            ];
            $displayGallery = !empty($galleryImages) ? array_map(fn($u) => ['url' => asset('storage/' . $u), 'caption' => 'Momen Bahagia'], $galleryImages) : $defaultGallery;
          @endphp

          @foreach($displayGallery as $idx => $gItem)
          <div class="reveal group relative aspect-[4/5] rounded-xl overflow-hidden border border-mk-gold/50 shadow-md cursor-pointer bg-mk-black"
               onclick="openLightbox('{{ $gItem['url'] }}', '{{ $gItem['caption'] }}')">
            <img src="{{ $gItem['url'] }}" alt="{{ $gItem['caption'] }}" loading="lazy" decoding="async"
                 class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500" />
            <div class="absolute inset-0 bg-gradient-to-t from-mk-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-2.5">
              <span class="text-[10px] text-mk-paper font-cormorant italic line-clamp-1">{{ $gItem['caption'] }}</span>
            </div>
          </div>
          @endforeach
        </div>

      </div>
    </section>

    <!-- Lightbox Modal -->
    <div id="lightbox" onclick="closeLightbox()"
      class="fixed inset-0 z-[75] hidden items-center justify-center bg-black/92 p-4 backdrop-blur-md">
      <div onclick="event.stopPropagation()" class="relative max-w-sm w-full rounded-2xl overflow-hidden border border-mk-gold bg-mk-black shadow-2xl">
        <button type="button" onclick="closeLightbox()"
          class="absolute top-3 right-3 z-10 h-8 w-8 rounded-full bg-mk-red text-mk-paper flex items-center justify-center font-bold">✕</button>
        <img id="lightbox-img" src="" alt="Foto" class="w-full max-h-[70vh] object-contain bg-black" />
        <p id="lightbox-caption" class="p-3 text-center font-cormorant italic text-sm text-mk-gold-3 border-t border-mk-gold/30"></p>
      </div>
    </div>

    <!-- ===================================================================
         7. PELAMINAN ADAT & KEHORMATAN (pelaminan-plate.webp)
    ==================================================================== -->
    <section class="relative min-h-[50svh] overflow-hidden border-t border-mk-gold/30">
      <img src="{{ asset('images/themes/minang/royal/pelaminan-plate.webp') }}"
           alt="Pelaminan Baralek Gadang" loading="lazy" decoding="async"
           class="absolute inset-0 h-full w-full object-cover" />
      <div class="absolute inset-0 bg-gradient-to-t from-mk-black via-mk-black/60 to-transparent"></div>
      
      <div class="relative z-10 px-6 py-16 text-center text-mk-paper flex flex-col justify-end min-h-[50svh]">
        <p class="font-caps text-[10px] uppercase tracking-[0.35em] text-mk-gold font-bold mb-1">Adat Basandi Syarak</p>
        <h3 class="font-display text-2xl font-bold">Syarak Basandi Kitabullah</h3>
        <p class="text-xs text-mk-paper/80 mt-2 max-w-xs mx-auto leading-relaxed">
          &ldquo;Nan kuriek kundi, nan merah sago. Nan baiak budi, nan indah baso.&rdquo;
        </p>
      </div>
    </section>

    <!-- ===================================================================
         8. TANDA KASIH & AMPLOP DIGITAL
    ==================================================================== -->
    <section class="py-16 px-6 mk-paper relative border-t border-mk-gold/30">
      <div class="max-w-md mx-auto text-center">
        
        <div class="reveal mb-10">
          <p class="font-caps text-[10px] uppercase tracking-[0.3em] text-mk-red font-bold mb-1">Ungkapan Kasih</p>
          <h2 class="font-display text-3xl font-bold text-mk-ink">Amplop Digital</h2>
          <p class="text-xs text-mk-ink-2 mt-2 leading-relaxed">
            Doa restu Anda adalah karunia terindah bagi kami. Namun apabila ingin memberi tanda kasih, dapat disalurkan melalui rekening berikut:
          </p>
        </div>

        <div class="space-y-4 reveal">
          @foreach($giftAccounts as $acc)
          <div class="rounded-2xl bg-mk-paper-2 p-5 border border-mk-gold/50 shadow-md text-left relative overflow-hidden">
            <div class="flex items-center justify-between mb-2">
              <span class="font-display text-base font-bold text-mk-ink">{{ $acc['bank'] }}</span>
              <span class="font-caps text-[9px] uppercase px-2 py-0.5 rounded bg-mk-red text-mk-paper font-semibold">Tanda Kasih</span>
            </div>
            <p class="font-mono text-base font-bold tracking-wider text-mk-red">{{ $acc['account_number'] }}</p>
            <p class="text-xs text-mk-ink-2 mb-3">a.n. {{ $acc['account_name'] }}</p>
            <button type="button" onclick="copyText('{{ $acc['account_number'] }}', 'Nomor rekening {{ $acc['bank'] }} berhasil disalin')"
              class="w-full rounded-lg bg-mk-paper border border-mk-gold/60 py-2 text-xs font-semibold text-mk-ink hover:bg-mk-gold hover:text-mk-black transition">
              Salin Nomor Rekening
            </button>
          </div>
          @endforeach
        </div>

      </div>
    </section>

    <!-- ===================================================================
         9. RSVP & SALAMAIK BARALEK (BUKU TAMU / DOA RESTU)
    ==================================================================== -->
    <section id="rsvp" class="py-16 px-6 bg-mk-paper-2 border-t border-mk-gold/30 relative">
      <div class="max-w-md mx-auto">
        <div class="text-center mb-8 reveal">
          <p class="font-caps text-[10px] uppercase tracking-[0.3em] text-mk-red font-bold mb-1">Salamaik Baralek</p>
          <h2 class="font-display text-3xl font-bold text-mk-ink">Konfirmasi &amp; Doa</h2>
        </div>

        <form id="rsvp-form" class="reveal space-y-4 rounded-2xl bg-mk-paper p-5 border border-mk-gold/40 shadow-lg">
          <div>
            <label class="block text-xs font-caps uppercase tracking-wider text-mk-ink font-semibold mb-1">Nama Anda</label>
            <input type="text" id="input-name" required value="{{ !empty($guestName) ? $guestName : '' }}" placeholder="Tuliskan nama Anda"
              class="w-full rounded-xl bg-mk-paper-2 border border-mk-gold/40 px-3.5 py-2.5 text-xs text-mk-ink focus:outline-none focus:border-mk-red" />
          </div>

          <div>
            <label class="block text-xs font-caps uppercase tracking-wider text-mk-ink font-semibold mb-1">Kehadiran</label>
            <select id="input-attendance"
              class="w-full rounded-xl bg-mk-paper-2 border border-mk-gold/40 px-3.5 py-2.5 text-xs text-mk-ink focus:outline-none focus:border-mk-red">
              <option value="Hadir">InsyaAllah Hadir</option>
              <option value="Ragu">Masih Ragu</option>
              <option value="Berhalangan">Maaf, Berhalangan Hadir</option>
            </select>
          </div>

          <div>
            <label class="block text-xs font-caps uppercase tracking-wider text-mk-ink font-semibold mb-1">Ucapan &amp; Doa Restu</label>
            <textarea id="input-msg" rows="3" required placeholder="Tuliskan doa restu untuk kedua mempelai..."
              class="w-full rounded-xl bg-mk-paper-2 border border-mk-gold/40 px-3.5 py-2.5 text-xs text-mk-ink focus:outline-none focus:border-mk-red"></textarea>
          </div>

          <button type="submit"
            class="w-full rounded-full bg-mk-red hover:bg-mk-red-2 text-mk-paper py-3 text-xs font-bold uppercase tracking-widest shadow-md transition">
            Kirim Ucapan &amp; Konfirmasi
          </button>
        </form>

        <!-- Daftar Doa & Ucapan Tamu -->
        <div id="wishes-box" class="mt-8 space-y-3 max-h-72 overflow-y-auto pr-1">
          <div class="rounded-xl bg-mk-paper p-4 border border-mk-gold/30 shadow-sm">
            <div class="flex items-center justify-between mb-1">
              <span class="font-bold text-xs text-mk-ink">Rizky Pratama, S.Kom.</span>
              <span class="text-[9px] px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-semibold">Hadir</span>
            </div>
            <p class="text-xs text-mk-ink-2 leading-relaxed">Barakallahu lakuma wa baraka alaikuma wa jama'a bainakuma fii khoir. Selamat menempuh hidup baru Fadhil &amp; Nisa!</p>
          </div>

          <div class="rounded-xl bg-mk-paper p-4 border border-mk-gold/30 shadow-sm">
            <div class="flex items-center justify-between mb-1">
              <span class="font-bold text-xs text-mk-ink">Siti Aisyah, M.Pd.</span>
              <span class="text-[9px] px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-semibold">Hadir</span>
            </div>
            <p class="text-xs text-mk-ink-2 leading-relaxed">Salamaik baralek gadang! Semoga selalu sakinah, mawaddah, warahmah sampai akhir hayat.</p>
          </div>
        </div>

      </div>
    </section>

    <!-- ===================================================================
         10. FOOTER PENUTUP DENGAN DANAU SINGKARAK & RUMAH GADANG LIT
    ==================================================================== -->
    <footer class="relative min-h-[60svh] overflow-hidden bg-[#1E1612] text-center text-mk-paper pb-28 pt-16">
      
      <!-- Danau Singkarak Plate & Rumah Gadang Lit -->
      <div class="absolute inset-0 pointer-events-none opacity-60">
        <img src="{{ asset('images/themes/minang/royal/danau-plate.webp') }}" alt="Danau Singkarak" loading="lazy" decoding="async" class="h-full w-full object-cover" />
        <div class="absolute inset-0 bg-gradient-to-t from-[#140A07] via-transparent to-[#140A07]/80"></div>
        <img src="{{ asset('images/themes/minang/royal/rumahgadang-lit.webp') }}" alt="Rumah Gadang Malam" loading="lazy" decoding="async" class="absolute bottom-[2%] left-[10%] w-[80%] h-auto drop-shadow-2xl opacity-75" />
      </div>

      <div class="relative z-10 px-6">
        <p class="font-caps text-[11px] uppercase tracking-[0.35em] text-mk-gold font-semibold mb-1">Pulang Baralek</p>
        <h2 class="font-display text-3xl font-bold text-mk-paper">{{ $primaryNick }} &amp; {{ $secondaryNick }}</h2>
        <p class="font-caps text-xs tracking-[0.3em] text-mk-gold-3 mt-1 mb-4">{{ $dateShort }}</p>
        <p class="text-xs text-mk-paper/80 max-w-xs mx-auto leading-relaxed mb-6">
          Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir memberikan doa restu.
        </p>
        <p class="font-cormorant italic text-sm text-mk-gold-3">
          Wassalamualaikum Warahmatullahi Wabarakatuh
        </p>
      </div>

    </footer>

    <!-- ===================================================================
         FLOATING BOTTOM DOCK NAVIGATION
    ==================================================================== -->
    <nav id="dock-nav"
         class="fixed bottom-[max(0.75rem,env(safe-area-inset-bottom))] left-1/2 -translate-x-1/2 z-40 hidden w-[92%] max-w-md items-center justify-around gap-1 rounded-full bg-mk-black/85 backdrop-blur-md px-2 py-2 border border-mk-gold/50 shadow-2xl transition-all duration-500">
      <a href="#hero" class="px-2 py-1 text-[11px] font-caps tracking-wider text-mk-paper hover:text-mk-gold transition">Sampul</a>
      <a href="#mempelai" class="px-2 py-1 text-[11px] font-caps tracking-wider text-mk-paper hover:text-mk-gold transition">Mempelai</a>
      <a href="#acara" class="px-2 py-1 text-[11px] font-caps tracking-wider text-mk-paper hover:text-mk-gold transition">Acara</a>
      <a href="#galeri" class="px-2 py-1 text-[11px] font-caps tracking-wider text-mk-paper hover:text-mk-gold transition">Galeri</a>
      <a href="#rsvp" class="px-2 py-1 text-[11px] font-caps tracking-wider text-mk-paper hover:text-mk-gold transition">Ucapan</a>
    </nav>

    <!-- FLOATING VINYL AUDIO PLAYER -->
    <button id="music-toggle" type="button" aria-label="Toggle Musik"
            class="fixed bottom-[calc(4.5rem+env(safe-area-inset-bottom))] right-4 z-40 hidden h-11 w-11 items-center justify-center rounded-full bg-mk-red text-mk-gold-3 border border-mk-gold shadow-2xl transition hover:scale-110 active:scale-95">
      <div id="icon-disc" class="mk-spin-slow">
        <svg class="h-6 w-6 text-mk-gold" fill="currentColor" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" fill="none"/>
          <circle cx="12" cy="12" r="3" fill="currentColor"/>
          <circle cx="12" cy="12" r="7" stroke="currentColor" stroke-width="0.8" stroke-dasharray="2 2" fill="none"/>
        </svg>
      </div>
      <div id="icon-mute" class="hidden">
        <svg class="h-5 w-5 text-mk-paper" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
          <path stroke-linecap="round" stroke-linejoin="round" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
        </svg>
      </div>
    </button>

    <audio id="bg-audio" loop preload="none">
      <source src="{{ $customMusicUrl }}" type="audio/mp4">
      <source src="{{ $customMusicUrl }}" type="audio/mpeg">
    </audio>

  </div>

  <!-- =====================================================================
       LOGIKA JAVASCRIPT: CINEMATIC OPENING, AUDIO, COUNTDOWN, PARALLAX
  ====================================================================== -->
  <script>
    // 1. GUGURAN KELOPAK MELATI ALAMI
    function spawnPetals() {
      if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
      const container = document.getElementById('petals-container');
      if (!container) return;
      const count = window.innerWidth < 640 ? 5 : 7;
      for (let i = 0; i < count; i++) {
        const p = document.createElement('div');
        p.className = 'mk-petal absolute';
        const startX = Math.random() * 100 + 'vw';
        const dx = (Math.random() * 60 - 30) + 'px';
        const dur = (12 + Math.random() * 8) + 's';
        const delay = -(Math.random() * 14) + 's';
        p.style.left = startX;
        p.style.top = '-5vh';
        p.style.setProperty('--dur', dur);
        p.style.setProperty('--delay', delay);
        p.style.setProperty('--dx', dx);
        p.innerHTML = `
          <svg viewBox="0 0 24 24" class="w-4 h-4 opacity-70" fill="none">
            <path d="M12 2 C18 2 22 8 22 14 C22 20 16 22 12 22 C8 22 2 20 2 14 C2 8 6 2 12 2 Z" fill="#FAF3E4"/>
            <path d="M12 4 C14 8 14 16 12 20" stroke="#C9A227" stroke-width="0.8" opacity="0.5"/>
          </svg>
        `;
        container.appendChild(p);
      }
    }
    spawnPetals();

    // 2. MUSIK LATAR MINANG (MP3/M4A DENGAN WEB AUDIO API FALLBACK RINGAN)
    const audio = document.getElementById('bg-audio');
    const musicBtn = document.getElementById('music-toggle');
    const iconDisc = document.getElementById('icon-disc');
    const iconMute = document.getElementById('icon-mute');
    let isPlaying = false, synthCtx = null, synthTimer = null;

    function startAmbientFallback() {
      const AC = window.AudioContext || window.webkitAudioContext;
      if (!AC) return;
      if (!synthCtx) synthCtx = new AC();
      if (synthCtx.state === 'suspended') synthCtx.resume();
      const notes = [261.63, 329.63, 392.00, 440.00, 293.66, 392.00, 329.63, 293.66];
      let step = 0;
      const playNote = () => {
        if (!isPlaying || !synthCtx) return;
        const t = synthCtx.currentTime, osc = synthCtx.createOscillator(), g = synthCtx.createGain();
        osc.type = 'sine';
        osc.frequency.setValueAtTime(notes[step++ % notes.length], t);
        g.gain.setValueAtTime(0.0001, t);
        g.gain.exponentialRampToValueAtTime(0.035, t + 0.15);
        g.gain.exponentialRampToValueAtTime(0.0001, t + 1.4);
        osc.connect(g); g.connect(synthCtx.destination);
        osc.start(t); osc.stop(t + 1.5);
      };
      playNote();
      if (synthTimer) clearInterval(synthTimer);
      synthTimer = setInterval(playNote, 1200);
    }

    async function playMusic() {
      if (audio && audio.src) {
        try {
          audio.volume = 0.6;
          await audio.play();
          isPlaying = true;
          updateIcons();
          return;
        } catch(e) {}
      }
      isPlaying = true;
      updateIcons();
      startAmbientFallback();
    }

    function toggleMusic() {
      if (audio && audio.src && !synthCtx) {
        if (isPlaying) { audio.pause(); isPlaying = false; }
        else { audio.play().catch(() => {}); isPlaying = true; }
        updateIcons();
        return;
      }
      if (isPlaying) {
        isPlaying = false;
        synthCtx?.suspend();
        if (synthTimer) clearInterval(synthTimer);
      } else {
        isPlaying = true;
        startAmbientFallback();
      }
      updateIcons();
    }

    document.addEventListener('visibilitychange', () => {
      if (document.hidden) { audio?.pause(); synthCtx?.suspend(); }
      else if (isPlaying) { audio?.play().catch(() => {}); synthCtx?.resume(); }
    });

    function updateIcons() {
      if (isPlaying) {
        iconDisc?.classList.remove('hidden');
        iconMute?.classList.add('hidden');
      } else {
        iconDisc?.classList.add('hidden');
        iconMute?.classList.remove('hidden');
      }
    }

    musicBtn?.addEventListener('click', toggleMusic);

    // 3. CINEMATIC OPENING MOTION
    const coverStage = document.getElementById('cover-stage');
    const mistSurgeStage = document.getElementById('mist-surge-stage');
    const landedCanvas = document.getElementById('landed-canvas');
    const openBtn = document.getElementById('open-btn');
    const dockNav = document.getElementById('dock-nav');

    document.body.style.overflow = 'hidden';

    let isOpening = false;
    openBtn?.addEventListener('click', () => {
      if (isOpening) return;
      isOpening = true;

      playMusic();
      coverStage?.classList.add('descended');
      mistSurgeStage?.classList.remove('hidden');

      setTimeout(() => { landedCanvas?.classList.add('landed'); }, 1100);
      setTimeout(() => {
        document.body.style.overflow = '';
        if (coverStage) coverStage.style.display = 'none';
        musicBtn?.classList.remove('hidden');
        musicBtn?.classList.add('flex');
        dockNav?.classList.remove('hidden');
        dockNav?.classList.add('flex');
      }, 1750);
      setTimeout(() => { mistSurgeStage?.classList.add('hidden'); }, 2500);
    });

    // 4. PARALLAX SCROLL EFFECT PADA NGARAI SIANOK
    const ngaraiRim = document.getElementById('ngarai-rim');
    const salamSec = document.getElementById('salam');
    window.addEventListener('scroll', () => {
      if (!salamSec || !ngaraiRim) return;
      const rect = salamSec.getBoundingClientRect();
      const winH = window.innerHeight;
      if (rect.top < winH && rect.bottom > 0) {
        const progress = (winH - rect.top) / (winH + rect.height);
        const yOffset = 90 - (progress * 130);
        ngaraiRim.style.transform = `translate3d(0, ${yOffset}px, 0)`;
      }
    }, { passive: true });

    // 5. COUNTDOWN TIMER REAL-TIME
    const weddingDate = new Date('{{ $targetTime }}').getTime();
    function runCountdown() {
      const dist = weddingDate - Date.now();
      if (dist <= 0) return;
      const d = Math.floor(dist / (1000 * 60 * 60 * 24));
      const h = Math.floor((dist % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const m = Math.floor((dist % (1000 * 60 * 60)) / (1000 * 60));
      const s = Math.floor((dist % (1000 * 60)) / 1000);
      document.getElementById('cd-days').textContent = String(d).padStart(2, '0');
      document.getElementById('cd-hours').textContent = String(h).padStart(2, '0');
      document.getElementById('cd-minutes').textContent = String(m).padStart(2, '0');
      document.getElementById('cd-seconds').textContent = String(s).padStart(2, '0');
    }
    setInterval(runCountdown, 1000);
    runCountdown();

    // 6. SCROLL REVEAL OBSERVER
    const obs = new IntersectionObserver((entries) => {
      entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('active'); });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal').forEach(el => obs.observe(el));

    // 7. LIGHTBOX MODAL
    function openLightbox(src, caption) {
      const box = document.getElementById('lightbox');
      const img = document.getElementById('lightbox-img');
      const cap = document.getElementById('lightbox-caption');
      if (box && img && cap) {
        img.src = src;
        cap.textContent = caption;
        box.classList.remove('hidden');
        box.classList.add('flex');
      }
    }
    function closeLightbox() {
      const box = document.getElementById('lightbox');
      box?.classList.add('hidden');
      box?.classList.remove('flex');
    }

    // 8. SALIN REKENING + TOAST
    let toastTimer = null;
    function showToast(msg) {
      const box = document.getElementById('toast-box');
      const text = document.getElementById('toast-message');
      if (!box || !text) return;
      text.textContent = msg;
      box.classList.remove('opacity-0', '-translate-y-6');
      box.classList.add('opacity-100', 'translate-y-0');
      clearTimeout(toastTimer);
      toastTimer = setTimeout(() => {
        box.classList.add('opacity-0', '-translate-y-6');
        box.classList.remove('opacity-100', 'translate-y-0');
      }, 2600);
    }

    function copyText(val, successMsg) {
      if (navigator.clipboard) {
        navigator.clipboard.writeText(val).then(() => showToast(successMsg)).catch(() => showToast(val));
      } else {
        showToast(val);
      }
    }

    // 9. FORM RSVP INTERAKTIF
    document.getElementById('rsvp-form')?.addEventListener('submit', function(e) {
      e.preventDefault();
      const name = document.getElementById('input-name').value.trim();
      const att = document.getElementById('input-attendance').value;
      const msg = document.getElementById('input-msg').value.trim();
      if (!name || !msg) return;

      const card = document.createElement('div');
      card.className = 'rounded-xl bg-mk-paper p-4 border border-mk-gold/30 shadow-sm';
      card.innerHTML = `
        <div class="flex items-center justify-between mb-1">
          <span class="font-bold text-xs text-mk-ink">${name}</span>
          <span class="text-[9px] px-2 py-0.5 rounded-full ${att === 'Hadir' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'} font-semibold">${att}</span>
        </div>
        <p class="text-xs text-mk-ink-2 leading-relaxed">${msg}</p>
      `;
      document.getElementById('wishes-box')?.prepend(card);
      document.getElementById('input-msg').value = '';
      showToast('Salamaik! Ucapan & konfirmasi Anda telah tersimpan.');

      @if(isset($invitation) && !empty($invitation->slug))
      try {
        fetch("{{ route('invitation.wish', $invitation->slug) }}", {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
          body: JSON.stringify({ guest_name: name, attendance: att === 'Hadir' ? 'hadir' : 'tidak_hadir', message: msg })
        });
      } catch(e) {}
      @endif
    });
  </script>
</body>
</html>
