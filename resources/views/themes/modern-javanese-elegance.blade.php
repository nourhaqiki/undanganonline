<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ $invitation->title ?: 'The Wedding of Tasya & Zun' }} — Modern Javanese Elegance</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Cormorant:ital,wght@0,400;0,600;0,700;1,400&family=Great+Vibes&family=Marcellus&family=Old+Standard+TT:ital,wght@0,400;0,700;1,400&family=Poppins:wght@400;500;600&family=Tenor+Sans&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        :root {
            --jv-gold: #E8B227;
            --jv-gold-soft: #F8BB63;
            --jv-brown: #4D290F;
            --jv-brown-mid: #89612C;
            --jv-dark: #401809;
            --jv-maroon: #34000C;
            --jv-cover-top: #661605;
            --jv-cover-bot: #341300;
            --jv-cream: #FFF7E4;
            --jv-cream-2: #FFF5E8;
            --jv-blush: #FFF7F3;
            --jv-mint: #F4F9F8;
        }

        html, body {
            margin: 0; padding: 0;
            background: var(--jv-brown); color: #fff;
            font-family: 'Poppins', system-ui, sans-serif;
            overflow-x: clip;
        }
        body.jv-locked { overflow: hidden !important; height: 100dvh; }

        .f-serif { font-family: Georgia, 'Old Standard TT', serif; }
        .f-roman { font-family: 'Times New Roman', 'Old Standard TT', Georgia, serif; }
        .f-script { font-family: 'Great Vibes', cursive; }
        .f-tenor { font-family: 'Tenor Sans', serif; }
        .f-cormorant { font-family: 'Cormorant', Georgia, serif; }
        .f-marcellus { font-family: 'Marcellus', serif; }
        .f-abril { font-family: 'Abril Fatface', Georgia, serif; }

        /* Responsive Split Shell: Desktop 70/30, Tablet Adaptive, Mobile 100% */
        .jv-shell { display: flex; min-height: 100dvh; width: 100%; }
        #sideleft {
            display: none; position: sticky; top: 0; height: 100dvh; flex: 1; min-width: 0;
            align-items: center; justify-content: center; text-align: center;
            overflow: hidden; background: var(--jv-brown);
        }
        #sideleft::before, #sideleft::after { content: ''; position: absolute; inset: 0; pointer-events: none; }
        #sideleft::before { background: url("{{ asset('images/themes/modern-javanese/Luxee-Jogja-12.png') }}") center/cover no-repeat; }
        #sideleft::after { background: linear-gradient(180deg, rgba(137,97,44,.83) 46%, rgba(77,41,15,.92) 100%); }
        #sideright {
            position: relative; width: 100%; min-height: 100dvh;
            background: var(--jv-cover-bot); box-shadow: -8px 0 40px rgba(0,0,0,.45);
            overflow-x: clip;
        }
        @media (min-width: 768px) {
            #sideleft { display: flex; }
            #sideright { width: clamp(380px, 32vw, 460px); flex-shrink: 0; }
        }

        /* Section 1: Cover Screen (Safe on Short Screens & Landscape Phones) */
        #section-cover {
            position: relative; min-height: 100dvh; width: 100%;
            display: flex; flex-direction: column; justify-content: space-between; align-items: center;
            padding: clamp(14px, 3vh, 38px) 20px clamp(20px, 4vh, 44px);
            background: linear-gradient(180deg, var(--jv-cover-top) 0%, var(--jv-cover-bot) 100%);
            overflow-y: auto; overflow-x: hidden; z-index: 40;
            transition: opacity .8s cubic-bezier(.22,1,.36,1), transform .8s cubic-bezier(.22,1,.36,1);
        }
        #section-cover.is-leaving { position: absolute; top: 0; left: 0; right: 0; opacity: 0; transform: scale(1.04); pointer-events: none; }
        .jv-bg-layer { position: absolute; inset: 0; background-position: center; background-size: cover; pointer-events: none; z-index: 0; }
        .jv-arch-photo {
            width: min(52%, 215px); max-height: 36dvh; aspect-ratio: 2/3;
            border: 3px groove var(--jv-gold-soft); border-radius: 250px 250px 25px 25px;
            overflow: hidden; margin: 0 auto; box-shadow: 0 18px 40px rgba(0,0,0,.45);
            animation: jvCoverBreath 8s ease-in-out infinite;
        }

        /* Section 2A: Opening Motion Video Stage */
        #section-konten { position: relative; opacity: 0; transition: opacity 1s ease; }
        #section-konten.is-visible { opacity: 1; }
        #motion-video {
            position: relative; width: 100%; height: 100dvh; min-height: 540px;
            display: flex; flex-direction: column; justify-content: space-between; align-items: center;
            overflow: hidden;
            background: url("{{ asset('images/themes/modern-javanese/UCL00064.jpg') }}") center/cover no-repeat;
        }
        #motion-video-player {
            position: absolute; inset: 0; width: 100%; height: 100%;
            object-fit: cover; transform: scale(1.05); z-index: 0; pointer-events: none;
            transition: opacity .8s ease;
        }
        #motion-video-player.is-ended { opacity: 0; }

        /* Staggered Formal Line Reveal Inside Gebyok Frame (~7.5s into Video) */
        #overlay-content { opacity: 0; transform: scale(.86); transition: opacity 1.3s cubic-bezier(.16,1,.3,1), transform 1.4s cubic-bezier(.16,1,.3,1); }
        #overlay-content.is-revealed { opacity: 1; transform: scale(1); }
        #overlay-content .ov-line { opacity: 0; transform: translate3d(0,18px,0); transition: opacity 1s ease, transform 1.1s cubic-bezier(.22,1,.36,1); }
        #overlay-content.is-revealed .ov-line { opacity: 1; transform: translate3d(0,0,0); }
        #overlay-content.is-revealed .ov-line:nth-child(2) { transition-delay: .18s; }
        #overlay-content.is-revealed .ov-line:nth-child(3) { transition-delay: .32s; }
        #overlay-content.is-revealed .ov-line:nth-child(4) { transition-delay: .46s; }
        #overlay-content.is-revealed .ov-line:nth-child(5) { transition-delay: .64s; }

        /* Natural Animations & Motion */
        @keyframes jvCoverBreath { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.022); } }
        .goyangan { animation: jvGoyangan 4s cubic-bezier(.37,0,.63,1) infinite; transform-origin: bottom center; will-change: transform; }
        @keyframes jvGoyangan { 0%, 100% { transform: rotate(0deg); } 25% { transform: rotate(-12deg); } 75% { transform: rotate(12deg); } }
        .atas-bawah { animation: jvAtasBawah 2s cubic-bezier(.45,0,.55,1) infinite; will-change: transform; }
        @keyframes jvAtasBawah { 0%, 100% { transform: translate3d(0,0,0); } 50% { transform: translate3d(0,14px,0); } }
        .putar-kiri { animation: jvPutarKiri 18s linear infinite; }
        .putar-kiri.is-paused { animation-play-state: paused; }
        @keyframes jvPutarKiri { from { transform: rotate(0deg); } to { transform: rotate(-360deg); } }
        @keyframes jvSlideUp { from { opacity: 0; transform: translate3d(0,48px,0); } to { opacity: .45; transform: translate3d(0,0,0); } }
        .cover-ornament-in { animation: jvSlideUp 1.3s cubic-bezier(.22,1,.36,1) forwards; }

        /* Scroll Reveals */
        .muncul, .muncul-kiri, .muncul-kanan, .zoom {
            opacity: 0; will-change: opacity, transform;
            transition: opacity .85s cubic-bezier(.22,1,.36,1), transform 1.05s cubic-bezier(.22,1,.36,1);
        }
        .muncul { transform: translate3d(0,38px,0) scale(.96); }
        .muncul-kiri { transform: translate3d(-40px,0,0) scale(.97); }
        .muncul-kanan { transform: translate3d(40px,0,0) scale(.97); }
        .zoom { transform: scale3d(.86,.86,1); }
        .muncul.active, .muncul-kiri.active, .muncul-kanan.active, .zoom.active { opacity: 1; transform: translate3d(0,0,0) scale(1); }

        /* Reference Sections */
        .jv-quote-sec { position: relative; background: var(--jv-cream); border-bottom: 5px solid var(--jv-maroon); padding: clamp(72px, 18%, 112px) 0; overflow: hidden; }
        .jv-couple-sec { position: relative; padding: 56px 24px 68px; overflow-x: clip; background: linear-gradient(180deg, var(--jv-brown-mid) 0%, var(--jv-brown) 100%); }
        .jv-acara-sec { position: relative; background: var(--jv-cream-2); padding: 48px 20px 64px; overflow-x: clip; }
        .jv-event-card { position: relative; background: var(--jv-dark); border-radius: 35px; padding: 32px 24px; overflow: hidden; box-shadow: 0 20px 35px rgba(0,0,0,.22); }
        .jv-galeri-sec { position: relative; background: var(--jv-blush); padding: 56px 24px 72px; overflow-x: clip; }
        .jv-wishes-wrap { position: relative; background: linear-gradient(180deg, var(--jv-brown-mid) 0%, var(--jv-brown) 100%); padding-top: 84px; padding-bottom: 24px; overflow-x: clip; }
        .jv-rsvp-card { position: relative; background: var(--jv-dark); border-radius: 50px 50px 0 0; padding: 0 24px 78px; }
        .jv-batik-overlay::before {
            content: ''; position: absolute; inset: 0; pointer-events: none; opacity: .04;
            background: url("{{ asset('images/themes/modern-javanese/Luxee-Jogja-13.png') }}") center/contain repeat;
        }

        /* Buttons */
        .jv-btn-gold {
            display: inline-flex; align-items: center; justify-content: center; gap: 10px;
            background: var(--jv-gold); color: var(--jv-dark); fill: var(--jv-dark);
            font-family: Georgia, serif; font-size: 14px; font-weight: 600;
            padding: 12px 24px; border-radius: 5px; min-height: 44px;
            box-shadow: 0 20px 10px -15px rgba(0,0,0,.5); transition: all .25s ease; cursor: pointer;
        }
        .jv-btn-gold:hover, .jv-btn-gold:focus-visible { background: #fff; color: var(--jv-dark); transform: translateY(-1px); }
        .jv-btn-ig {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            color: #fff; font-family: Georgia, serif; font-size: 14px; font-weight: 600;
            padding: 8px 18px; border-bottom: 3px solid var(--jv-gold); border-radius: 5px;
            min-height: 44px; transition: all .25s ease;
        }
        .jv-btn-ig:hover { background: #fff; color: var(--jv-dark); }
        .jv-btn-copy {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            background: #603A2D; color: #fff; border: 1px solid rgba(34,34,34,.35);
            font-family: 'Tenor Sans', serif; font-size: 11px; letter-spacing: .04em;
            padding: 8px 14px; border-radius: 4px; min-height: 38px; transition: background .25s ease; cursor: pointer;
        }
        .jv-btn-copy:hover { background: #945A46; }

        /* Sticky Bottom Menu: Always Centered Inside #sideright Without Scrollbar Offset */
        #menunav {
            position: sticky; bottom: 12px; width: min(90%, 380px); margin: 0 auto 14px; z-index: 45;
            background: var(--jv-brown); border: 1px solid rgba(232,178,39,.28); border-radius: 10px;
            padding: 8px; display: flex; justify-content: space-evenly; align-items: center;
            box-shadow: 0 12px 30px rgba(0,0,0,.45);
        }
        #menunav a {
            display: flex; align-items: center; justify-content: center;
            width: 40px; height: 40px; border-radius: 8px; color: #fff;
            transition: transform .2s ease, color .2s ease, background .2s ease;
        }
        #menunav a:hover, #menunav a.is-active { transform: scale(1.12); color: var(--jv-gold); background: rgba(232,178,39,.14); }

        #audio-container {
            position: fixed; top: 16px; right: 16px; z-index: 48;
            width: 42px; height: 42px; border-radius: 9999px;
            background: rgba(77,41,15,.85); border: 1px solid rgba(248,187,99,.45);
            display: flex; align-items: center; justify-content: center;
            color: var(--jv-gold-soft); cursor: pointer; box-shadow: 0 6px 18px rgba(0,0,0,.4);
            backdrop-filter: blur(6px);
        }

        .guestbook-scroll::-webkit-scrollbar { width: 5px; }
        .guestbook-scroll::-webkit-scrollbar-thumb { background: rgba(232,178,39,.45); border-radius: 999px; }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: .01ms !important; transition-duration: .01ms !important; }
        }
    </style>
</head>

@php
    $ed = $invitation->event_data ?? [];
    $isDemo = str_starts_with($invitation->slug ?? '', 'demo-');

    $primaryNick      = $ed['primary_name'] ?? 'Tasya';
    $secondaryNick    = $ed['secondary_name'] ?? 'Zun';
    $primaryParents   = $ed['primary_title_parents'] ?? 'Putri Pertama dari Bapak Moh Isa Ismail & Ibu Mudholifah';
    $secondaryParents = $ed['secondary_title_parents'] ?? 'Putra Pertama dari Bapak Imam Tohari & Ibu Dwi Mariam';
    $primaryIg        = ltrim($ed['primary_instagram'] ?? 'svatasy', '@');
    $secondaryIg      = ltrim($ed['secondary_instagram'] ?? 'zun_afif', '@');
    $greeting         = $ed['greeting'] ?? 'Dengan Ridho dan Rahmat Allah SWT, kami bermaksud memberitahukan pernikahan kami :';
    $quote            = $ed['quote'] ?? '“Dan diantara tanda-tanda kekuasaanNya ialah Dia menciptakan untukmu isteri-isteri dari jenismu sendiri, supaya kamu cenderung dan merasa tenteram kepadanya, dan dijadikanNya diantaramu rasa kasih dan sayang.”';

    $role1 = str_contains(strtolower($primaryParents), 'putra') ? 'Groom' : 'Bride';
    $role2 = $role1 === 'Groom' ? 'Bride' : 'Groom';
    $primaryFull   = $isDemo ? 'Sava Tasya Idelia, S.Si.' : $primaryNick;
    $secondaryFull = $isDemo ? 'Muhamad Zun Afif Amruloh, S.Kom' : $secondaryNick;

    $s1 = $ed['session_1'] ?? [];
    $s2 = $ed['session_2'] ?? [];
    $s1DateRaw = !empty($s1['date']) ? $s1['date'] : '2026-11-15';
    $s2DateRaw = !empty($s2['date']) ? $s2['date'] : $s1DateRaw;
    $s1DateObj = \Carbon\Carbon::parse($s1DateRaw)->locale('id');
    $s2DateObj = \Carbon\Carbon::parse($s2DateRaw)->locale('id');
    $s1DateText = $s1DateObj->translatedFormat('l, d F Y');
    $s2DateText = $s2DateObj->translatedFormat('l, d F Y');
    $shortDate = $s1DateObj->format('d . m . Y');

    $s1Time    = !empty($s1['time']) ? $s1['time'] : '08.00 WIB';
    $s1Venue   = !empty($s1['venue']) ? $s1['venue'] : 'Hotel Ibis Budget Diponegoro';
    $s1Address = !empty($s1['address']) ? $s1['address'] : 'Jl. Diponegoro No.183, Darmo, Kec. Wonokromo, Surabaya, Jawa Timur';
    $s1Maps    = !empty($s1['maps_url']) ? $s1['maps_url'] : 'https://maps.app.goo.gl/TjDmC6suq9vaU2hY6?g_st=ic';

    $showResepsi = $isDemo || !empty($s2['enabled']) || !empty($s2['title']);
    $s2Time      = !empty($s2['time']) ? $s2['time'] : "10.00 - 11.00 WIB\n11.00 - 12.00 WIB";
    $s2Venue     = !empty($s2['venue']) ? $s2['venue'] : $s1Venue;
    $s2Address   = !empty($s2['address']) ? $s2['address'] : $s1Address;
    $s2Maps      = !empty($s2['maps_url']) ? $s2['maps_url'] : $s1Maps;

    $coverUrl = !empty($ed['cover_image']) ? asset('storage/' . $ed['cover_image']) : asset('images/themes/modern-javanese/couple-cover.jpg');
    $groomUpload = !empty($ed['groom_photo']) ? asset('storage/' . $ed['groom_photo']) : (\Illuminate\Support\Facades\Storage::exists('public/mempelai/groom.jpg') ? asset('storage/mempelai/groom.jpg') : null);
    $brideUpload = !empty($ed['bride_photo']) ? asset('storage/' . $ed['bride_photo']) : (\Illuminate\Support\Facades\Storage::exists('public/mempelai/bride.jpg') ? asset('storage/mempelai/bride.jpg') : null);
    $photo1Upload = $role1 === 'Groom' ? $groomUpload : $brideUpload;
    $photo2Upload = $role2 === 'Groom' ? $groomUpload : $brideUpload;
    $photo1IsDefault = empty($photo1Upload) && empty($ed['cover_image']);
    $photo2IsDefault = empty($photo2Upload);
    $photo1Url = $photo1Upload ?: (!empty($ed['cover_image']) ? $coverUrl : asset('images/themes/modern-javanese/' . ($role1 === 'Groom' ? 'couple-groom.png' : 'couple-bride.png')));
    $photo2Url = $photo2Upload ?: asset('images/themes/modern-javanese/' . ($role2 === 'Groom' ? 'couple-groom.png' : 'couple-bride.png'));

    $userGallery = !empty($ed['gallery_images']) ? array_map(fn($p) => asset('storage/' . $p), $ed['gallery_images']) : [];
    $demoDuo = [asset('images/themes/modern-javanese/moment-1.jpg'), asset('images/themes/modern-javanese/moment-2.jpg')];
    $demoGrid = array_map(fn($i) => asset("images/themes/modern-javanese/gallery-{$i}.jpg"), range(1, 8));
    $duoPhotos  = count($userGallery) >= 2 ? array_slice($userGallery, 0, 2) : $demoDuo;
    $gridPhotos = count($userGallery) > 0 ? $userGallery : $demoGrid;
    $storyPhoto = asset('images/themes/modern-javanese/story-photo.jpg');

    $gifts = !empty($ed['gift_accounts']) ? $ed['gift_accounts'] : [
        ['bank' => 'SeaBank', 'account_number' => '901913412097', 'account_name' => 'Sava Tasya Idelia', 'logo' => asset('images/themes/modern-javanese/bank-seabank.png')],
        ['bank' => 'Bank Mandiri', 'account_number' => '1410021825401', 'account_name' => 'Muhamad Zun Afif Amr', 'logo' => asset('images/themes/modern-javanese/bank-mandiri.png')],
    ];

    $demoWishes = [
        ['id' => 'd1', 'guest_name' => 'Salsa', 'attendance' => 'hadir', 'message' => 'Happy wedding tasyaaa, akhirnya yaa setelah perjalanan panjang. Semoga sakinah mawaddah warahmah, bahagia selalu.', 'time_ago' => 'Baru saja'],
        ['id' => 'd2', 'guest_name' => 'Waz', 'attendance' => 'hadir', 'message' => 'Barakallahu laka wa baraka alaika wa jama\'a bainakuma fii khair. Semoga Allah berkahi pernikahan kalian.', 'time_ago' => '1 jam lalu'],
        ['id' => 'd3', 'guest_name' => 'Lail', 'attendance' => 'hadir', 'message' => 'Selamat Tasya...... Semoga menjadi keluarga yang sakinah mawaddah dan rahmah.', 'time_ago' => '3 jam lalu'],
        ['id' => 'd4', 'guest_name' => 'Tejo', 'attendance' => 'hadir', 'message' => 'Selamat ya, Mas Zun. Semoga Langgeng. Sakinah Mawadah Warohmah.', 'time_ago' => '5 jam lalu'],
    ];
    $initialWishesList = !empty($wishesData) ? $wishesData : ($isDemo ? $demoWishes : []);

    $displayGuest = !empty($guestName) ? $guestName : 'Nour Haqiki';
    $countdownIso = $isDemo ? '2026-11-15T08:00:00+07:00' : ($targetDate ?? ($s1DateRaw . 'T08:00:00+07:00'));
    $musicMode = $ed['music_mode'] ?? 'custom';
    $musicSrc  = !empty($ed['music_file']) ? asset('storage/' . $ed['music_file']) : asset('images/themes/modern-javanese/bg-music.mp3');
    $calUrl    = 'https://calendar.google.com/calendar/render?action=TEMPLATE&text=' . urlencode("The Wedding of {$primaryNick} & {$secondaryNick}") . '&dates=' . str_replace('-', '', $s1DateRaw) . 'T010000Z/' . str_replace('-', '', $s1DateRaw) . 'T060000Z&location=' . urlencode("{$s1Venue}, {$s1Address}");
@endphp

<body class="jv-locked antialiased selection:bg-[#E8B227] selection:text-[#401809]"
      x-data="javaneseTheme({
          isDemo: {{ $isDemo ? 'true' : 'false' }},
          wishUrl: '{{ $isDemo ? '' : route('invitation.wish', $invitation->slug) }}',
          musicMode: '{{ $musicMode }}',
          musicSrc: '{{ $musicSrc }}',
          targetDate: '{{ $countdownIso }}',
          guestName: {{ json_encode($displayGuest) }},
          initialWishes: {{ json_encode($initialWishesList) }}
      })">

    {{-- Reusable SVG Icon Definitions --}}
    <svg class="hidden" aria-hidden="true">
        <symbol id="ico-ig" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></symbol>
        <symbol id="ico-cal" viewBox="0 0 448 512"><path d="M148 288h-40c-6.6 0-12-5.4-12-12v-40c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12zm108-12v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm96 0v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm96-164v352c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V112c0-26.5 21.5-48 48-48h48V12c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v52h128V12c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v52h48c26.5 0 48 21.5 48 48zm-48 346V160H48v298c0 3.3 2.7 6 6 6h340c3.3 0 6-2.7 6-6z"/></symbol>
        <symbol id="ico-map" viewBox="0 0 576 512"><path d="M288 0c-69.59 0-126 56.41-126 126 0 56.26 82.35 158.8 113.9 196.02 6.39 7.54 17.82 7.54 24.2 0C331.65 284.8 414 182.26 414 126 414 56.41 357.59 0 288 0zm0 168c-23.2 0-42-18.8-42-42s18.8-42 42-42 42 18.8 42 42-18.8 42-42 42zM20.12 215.95A32.006 32.006 0 0 0 0 245.66v250.32c0 11.32 11.43 19.06 21.94 14.86L160 448V214.92c-8.84-15.98-16.07-31.54-21.25-46.42L20.12 215.95zM288 359.67c-14.07 0-27.38-6.18-36.51-16.96-19.66-23.2-40.57-49.62-59.49-76.72v182l192 64V266c-18.92 27.09-39.82 53.52-59.49 76.72-9.13 10.77-22.44 16.95-36.51 16.95zm266.06-198.51L416 224v288l139.88-55.95A31.996 31.996 0 0 0 576 426.34V176.02c0-11.32-11.43-19.06-21.94-14.86z"/></symbol>
        <symbol id="ico-pin" viewBox="0 0 384 512"><path d="M172.268 501.67C26.97 291.031 0 269.413 0 192 0 85.961 85.961 0 192 0s192 85.961 192 192c0 77.413-26.97 99.031-172.268 309.67-9.535 13.774-29.93 13.773-39.464 0zM192 272c44.183 0 80-35.817 80-80s-35.817-80-80-80-80 35.817-80 80 35.817 80 80 80z"/></symbol>
        <symbol id="ico-copy" viewBox="0 0 448 512"><path d="M433.941 65.941l-51.882-51.882A48 48 0 0 0 348.118 0H176c-26.51 0-48 21.49-48 48v48H48c-26.51 0-48 21.49-48 48v320c0 26.51 21.49 48 48 48h224c26.51 0 48-21.49 48-48v-48h80c26.51 0 48-21.49 48-48V99.882a48 48 0 0 0-14.059-33.941zM266 464H54a6 6 0 0 1-6-6V150a6 6 0 0 1 6-6h74v224c0 26.51 21.49 48 48 48h96v42a6 6 0 0 1-6 6zm128-96H182a6 6 0 0 1-6-6V54a6 6 0 0 1 6-6h106v88c0 13.255 10.745 24 24 24h88v202a6 6 0 0 1-6 6z"/></symbol>
    </svg>

    <div class="jv-shell">
        {{-- DESKTOP STICKY LEFT PANEL (#sideleft) --}}
        <aside id="sideleft">
            <div class="relative z-10 max-w-lg px-8 space-y-4">
                <p class="f-serif text-[14px] font-light uppercase tracking-[0.15em] text-white">Wedding Invitation</p>
                <div class="w-[27%] h-px bg-white mx-auto my-2.5"></div>
                <h2 class="f-serif text-[25px] tracking-[0.05em] text-[#E8B227]">You Are Invited!</h2>
                <p class="f-serif text-[14px] font-light uppercase tracking-[0.15em] text-white pt-1">Dear :</p>
                <p id="namatamu-desktop" class="f-script text-[38px] tracking-[0.05em] text-[#E8B227] leading-tight">{{ $displayGuest }}</p>
                <p class="f-tenor text-[14px] font-medium uppercase tracking-[0.15em] text-white pt-1">Please be a part of our happiest moment</p>
            </div>
        </aside>

        {{-- INVITATION COLUMN (#sideright) --}}
        <main id="sideright">
            <button id="audio-container" x-cloak x-show="isOpen && musicMode !== 'none'" @click="toggleMusic()" type="button" aria-label="Putar atau jeda musik">
                <svg class="w-6 h-6 putar-kiri" :class="!musicPlaying ? 'is-paused' : ''" viewBox="0 0 496 512" fill="currentColor">
                    <path d="M248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zM88 256H56c0-105.9 86.1-192 192-192v32c-88.2 0-160 71.8-160 160zm160 96c-53 0-96-43-96-96s43-96 96-96 96 43 96 96-43 96-96 96zm0-128c-17.7 0-32 14.3-32 32s14.3 32 32 32 32-14.3 32-32-14.3-32-32-32z"/>
                </svg>
            </button>

            {{-- 1. COVER SCREEN --}}
            <section id="section-cover" x-show="!coverDismissed" :class="isLeaving ? 'is-leaving' : ''">
                <div class="jv-bg-layer opacity-15" style="background-image:url('{{ asset('images/themes/modern-javanese/Luxee-Jogja-14.png') }}');"></div>

                <div class="relative z-10 flex flex-col items-center w-full">
                    <div class="goyangan w-32 sm:w-40 mx-auto">
                        <img src="{{ asset('images/themes/modern-javanese/Luxee-Jogja-11.png') }}" alt="Gunungan Jogja" class="w-full h-auto drop-shadow-md">
                    </div>
                    <h2 class="f-serif text-[14px] sm:text-[15px] font-medium uppercase tracking-[0.1em] text-[#F8BB63] mt-1">
                        The Wedding Of
                    </h2>
                </div>

                <div class="relative z-10 w-full text-center my-auto py-2">
                    <div class="jv-arch-photo">
                        <img src="{{ $coverUrl }}" alt="{{ $primaryNick }} & {{ $secondaryNick }}" class="w-full h-full object-cover">
                    </div>
                    <h1 class="f-script text-[36px] sm:text-[42px] font-semibold tracking-[0.05em] text-[#E8B227] mt-3 drop-shadow">
                        {{ $primaryNick }} &amp; {{ $secondaryNick }}
                    </h1>
                </div>

                <div class="relative z-10 w-full text-center space-y-1.5">
                    <p class="f-serif text-[13px] sm:text-[14px] tracking-[0.1em] text-white">Kepada YTH,</p>
                    <p id="namatamu" class="f-serif text-[18px] sm:text-[20px] tracking-[0.05em] text-[#E8B227]">{{ $displayGuest }}</p>
                    @if(!empty($guestAddress))
                        <p class="text-[11px] text-white/75">di {{ $guestAddress }}</p>
                    @endif
                    <div class="pt-2">
                        <button id="tombol-buka" @click="openInvitation()" type="button" class="jv-btn-gold">
                            <span>Buka Undangan</span>
                            <svg class="w-4 h-4" viewBox="0 0 512 512" fill="currentColor">
                                <path d="M512 464c0 26.51-21.49 48-48 48H48c-26.51 0-48-21.49-48-48V200.724a48 48 0 0 1 18.387-37.776c24.913-19.529 45.501-35.365 164.2-121.511C199.412 29.17 232.797-.347 256 .003c23.198-.354 56.596 29.172 73.413 41.433 118.687 86.137 139.303 101.995 164.2 121.512A48 48 0 0 1 512 200.724V464zm-65.666-196.605c-2.563-3.728-7.7-4.595-11.339-1.907-22.845 16.873-55.462 40.705-105.582 77.079-16.825 12.266-50.21 41.781-73.413 41.43-23.211.344-56.559-29.143-73.413-41.43-50.114-36.37-82.734-60.204-105.582-77.079-3.639-2.688-8.776-1.821-11.339 1.907l-9.072 13.196a7.998 7.998 0 0 0 1.839 10.967c22.887 16.899 55.454 40.69 105.303 76.868 20.274 14.781 56.524 47.813 92.264 47.573 35.724.242 71.961-32.771 92.263-47.573 49.85-36.179 82.418-59.97 105.303-76.868a7.998 7.998 0 0 0 1.839-10.967l-9.071-13.196z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <img src="{{ asset('images/themes/modern-javanese/Luxee-Jogja-12.png') }}" alt=""
                     class="cover-ornament-in absolute bottom-0 left-0 w-full pointer-events-none select-none z-[1]">
            </section>

            {{-- 2. OPENED INVITATION CONTENT --}}
            <div id="section-konten" x-show="isOpen" :class="contentVisible ? 'is-visible' : ''">

                {{-- 2A. OPENING MOTION VIDEO --}}
                <section id="motion-video">
                    <video id="motion-video-player" playsinline muted preload="auto"
                           poster="{{ asset('images/themes/modern-javanese/UCL00064.jpg') }}"
                           :class="videoEnded ? 'is-ended' : ''">
                        <source src="{{ asset('images/themes/modern-javanese/HD-LUXEE-JOGJA-1.mp4') }}" type="video/mp4">
                    </video>
                    <div class="absolute inset-0 bg-gradient-to-b from-black/20 via-black/10 to-black/35 pointer-events-none z-[1]"></div>

                    <div class="pt-4"></div>

                    <div id="overlay-content" :class="overlayShown ? 'is-revealed' : ''" class="relative z-10 w-[80%] mx-auto text-center mt-14 px-3">
                        <p class="ov-line f-serif text-[14px] sm:text-[15px] font-medium uppercase tracking-[0.1em] text-white mb-3 drop-shadow">The Wedding of</p>
                        <p class="ov-line f-script text-[40px] sm:text-[44px] font-semibold tracking-[0.05em] text-[#E8B227] leading-tight drop-shadow">{{ $primaryNick }}</p>
                        <p class="ov-line f-script text-[36px] sm:text-[40px] font-semibold text-[#E8B227] my-1 drop-shadow">&amp;</p>
                        <p class="ov-line f-script text-[40px] sm:text-[44px] font-semibold tracking-[0.05em] text-[#E8B227] leading-tight drop-shadow">{{ $secondaryNick }}</p>
                        <p class="ov-line f-roman text-[15px] font-medium tracking-[0.12em] text-white mt-3 drop-shadow">{{ $shortDate }}</p>
                    </div>

                    <div class="relative z-10 pb-8 flex flex-col items-center">
                        <button id="btnscroll" @click="enterLetter()" type="button" aria-label="Masuk ke surat undangan"
                                class="atas-bawah inline-flex flex-col items-center gap-1.5 text-white/90 hover:text-[#E8B227] transition cursor-pointer">
                            <svg class="w-11 h-11" viewBox="0 0 50 50" fill="currentColor">
                                <path d="m25 45a14 14 0 0 0 14-14v-12a14 14 0 0 0 -28 0v12a14 14 0 0 0 14 14zm-12-26a12 12 0 0 1 24 0v12a12 12 0 0 1 -24 0z"/>
                                <path d="m24 24.5h.09l.2.21a1 1 0 0 0 1.42 0l.2-.21h.09v-.09l5.71-5.7-1.42-1.42-4.29 4.3v-10.59h-2v10.59l-4.29-4.3-1.42 1.42 5.71 5.7z"/>
                            </svg>
                            <span x-show="!scrollUnlocked" class="text-[10px] f-tenor tracking-[0.2em] uppercase bg-black/50 px-3 py-1 rounded-full border border-white/25">
                                Buka Surat
                            </span>
                        </button>
                    </div>
                </section>

                {{-- 2B. AYAT AR-RUM 21 IN CARVED GEBYOK FRAME --}}
                <section id="opening" class="jv-quote-sec">
                    <div class="jv-bg-layer opacity-5" style="background-image:url('{{ asset('images/themes/modern-javanese/Luxee-Jogja-13.png') }}'); background-size:contain;"></div>
                    <img src="{{ asset('images/themes/modern-javanese/Luxee-Jogja-9.png') }}" alt=""
                         class="absolute inset-0 w-full h-full object-fill pointer-events-none z-[1]">

                    <div class="muncul relative z-10 w-[58%] sm:w-[60%] mx-auto min-h-[320px] flex flex-col justify-center items-center text-center px-2 py-8">
                        <img src="{{ asset('images/themes/modern-javanese/Luxee-Jogja-8.png') }}" alt="Gunungan" class="zoom w-24 sm:w-28 h-auto mb-4">
                        <p class="f-roman text-[13px] sm:text-[15px] leading-[1.55] text-[#34000C]">{{ $quote }}</p>
                        @if(!str_contains($quote, 'Ar-Rum'))
                            <p class="f-roman text-[14px] font-semibold tracking-[0.05em] text-[#34000C] mt-3">Q.S. Ar-Rum: 21</p>
                        @endif
                    </div>
                </section>

                {{-- 2C. KEDUA MEMPELAI --}}
                <section id="mempelai" class="jv-couple-sec">
                    <div class="jv-bg-layer opacity-15" style="background-image:url('{{ asset('images/themes/modern-javanese/Luxee-Jogja-1.png') }}');"></div>
                    <div class="relative z-10 text-center space-y-12 max-w-md mx-auto">
                        <div class="space-y-3 muncul">
                            <h2 class="f-script text-[36px] sm:text-[42px] font-semibold tracking-[0.05em] text-[#E8B227] drop-shadow">Kedua Mempelai</h2>
                            <p class="f-serif text-[14px] sm:text-[15px] leading-relaxed text-white">{{ $greeting }}</p>
                        </div>

                        @foreach([
                            ['role' => $role1, 'full' => $primaryFull, 'parents' => $primaryParents, 'ig' => $primaryIg, 'url' => $photo1Url, 'isDef' => $photo1IsDefault, 'anim' => 'muncul-kanan'],
                            ['role' => $role2, 'full' => $secondaryFull, 'parents' => $secondaryParents, 'ig' => $secondaryIg, 'url' => $photo2Url, 'isDef' => $photo2IsDefault, 'anim' => 'muncul-kiri', 'skip' => empty($secondaryNick)],
                        ] as $person)
                            @if(empty($person['skip']))
                            <div class="{{ $person['anim'] }} space-y-3 px-4">
                                <p class="f-script text-[34px] sm:text-[40px] font-semibold tracking-[0.05em] text-white drop-shadow">{{ $person['role'] }}</p>
                                @if($person['isDef'])
                                    <img src="{{ $person['url'] }}" alt="{{ $person['full'] }}" class="w-full max-w-[290px] mx-auto h-auto">
                                @else
                                    <div class="w-56 h-72 mx-auto rounded-full border-4 border-[#E8B227] overflow-hidden shadow-2xl p-1 bg-[#4D290F]">
                                        <img src="{{ $person['url'] }}" alt="{{ $person['full'] }}" class="w-full h-full object-cover rounded-full">
                                    </div>
                                @endif
                                <h3 class="f-serif text-[25px] sm:text-[28px] font-medium tracking-[0.04em] text-[#E8B227] pt-1">{{ $person['full'] }}</h3>
                                @if(!empty($person['parents']))
                                    <p class="f-serif text-[14px] sm:text-[15px] leading-relaxed text-white">{{ $person['parents'] }}</p>
                                @endif
                                @if(!empty($person['ig']))
                                    <div class="pt-1">
                                        <a href="https://www.instagram.com/{{ $person['ig'] }}/" target="_blank" rel="noopener" class="jv-btn-ig">
                                            <svg class="w-3.5 h-3.5"><use href="#ico-ig"/></svg>
                                            <span>{{ $person['ig'] }}</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                            @endif
                        @endforeach
                    </div>
                </section>

                {{-- 2D. WEDDING DAY (#Acara) --}}
                <section id="Acara" class="jv-acara-sec">
                    <div class="jv-bg-layer opacity-50" style="background-image:url('{{ asset('images/themes/modern-javanese/Luxee-Jogja-2.png') }}');"></div>

                    <div class="relative z-10 text-center space-y-6 max-w-md mx-auto">
                        <div class="goyangan w-36 sm:w-44 mx-auto">
                            <img src="{{ asset('images/themes/modern-javanese/Luxee-Jogja-11.png') }}" alt="" class="w-full h-auto">
                        </div>

                        <h2 class="f-script text-[36px] sm:text-[42px] font-semibold tracking-[0.05em] text-[#4D290F] drop-shadow">Wedding Day</h2>

                        <div class="grid grid-cols-4 gap-2 sm:gap-3">
                            <template x-for="(label, key) in {days:'Days', hours:'Hours', minutes:'Minutes', seconds:'Seconds'}" :key="key">
                                <div class="bg-[#E8B227] rounded-[10px] py-3 px-1.5 shadow-md">
                                    <span class="block f-serif text-[26px] sm:text-[32px] font-medium text-[#4D290F] leading-none" x-text="countdown[key]">00</span>
                                    <span class="block text-[9px] sm:text-[10px] font-semibold text-[#4D290F] tracking-[0.5px] mt-1" x-text="label"></span>
                                </div>
                            </template>
                        </div>

                        <p class="f-roman text-[14px] leading-relaxed text-[#34000C] px-2">
                            Dengan mengucap puji dan syukur kepada Allah SWT, kami bermaksud mengundang Anda untuk dapat menghadiri acara pernikahan kami yang akan dilaksanakan pada:
                        </p>

                        <a href="{{ $calUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-xs font-semibold text-[#4D290F] underline hover:text-[#661605]">
                            Simpan Tanggal ke Google Calendar
                        </a>

                        <div class="muncul space-y-6 pt-2">
                            @foreach([
                                ['title' => $s1['title'] ?? 'Akad Nikah', 'date' => $s1DateText, 'time' => $s1Time, 'venue' => $s1Venue, 'addr' => $s1Address, 'maps' => $s1Maps, 'anim' => 'muncul-kanan', 'isResepsi' => false, 'show' => true],
                                ['title' => $s2['title'] ?? 'Resepsi', 'date' => $s2DateText, 'time' => $s2Time, 'venue' => $s2Venue, 'addr' => $s2Address, 'maps' => $s2Maps, 'anim' => 'muncul-kiri', 'isResepsi' => true, 'show' => $showResepsi],
                            ] as $ev)
                                @if($ev['show'])
                                    @if($ev['isResepsi'])<div class="border-t border-dashed border-[#4D290F]/35 mx-4"></div>@endif
                                    <div class="jv-event-card jv-batik-overlay {{ $ev['anim'] }} text-center">
                                        <div class="relative z-10 space-y-4">
                                            <h3 class="f-script text-[35px] text-[#E8B227]">{{ $ev['title'] }}</h3>
                                            <div class="flex items-center gap-3 text-[#E8B227]">
                                                <span class="h-px flex-1 bg-[#E8B227]"></span>
                                                <svg class="w-6 h-6 shrink-0"><use href="#ico-cal"/></svg>
                                                <span class="h-px flex-1 bg-[#E8B227]"></span>
                                            </div>
                                            <div>
                                                <p class="f-cormorant text-[14px] text-white">Hari dan Tanggal</p>
                                                <p class="f-cormorant text-[20px] sm:text-[22px] font-semibold text-[#E8B227]">{{ $ev['date'] }}</p>
                                            </div>
                                            @if($ev['isResepsi'] && $isDemo)
                                                <div>
                                                    <p class="f-cormorant text-[14px] text-white">Sesi 1</p>
                                                    <p class="f-cormorant text-[20px] sm:text-[22px] font-semibold text-[#E8B227]">10.00 - 11.00 WIB</p>
                                                </div>
                                                <div>
                                                    <p class="f-cormorant text-[14px] text-white">Sesi 2</p>
                                                    <p class="f-cormorant text-[20px] sm:text-[22px] font-semibold text-[#E8B227]">11.00 - 12.00 WIB</p>
                                                </div>
                                            @else
                                                <div>
                                                    <p class="f-cormorant text-[14px] text-white">{{ $ev['isResepsi'] ? 'Waktu Resepsi' : 'Waktu' }}</p>
                                                    <p class="f-cormorant text-[20px] sm:text-[22px] font-semibold text-[#E8B227]">{!! nl2br(e($ev['time'])) !!}</p>
                                                </div>
                                            @endif
                                            <div class="flex items-center gap-3 text-[#E8B227] pt-1">
                                                <span class="h-px flex-1 bg-[#E8B227]"></span>
                                                <svg class="w-6 h-6 shrink-0"><use href="#ico-map"/></svg>
                                                <span class="h-px flex-1 bg-[#E8B227]"></span>
                                            </div>
                                            <div>
                                                <p class="f-cormorant text-[14px] text-white">Lokasi</p>
                                                <p class="f-cormorant text-[20px] sm:text-[22px] font-semibold text-[#E8B227]">{{ $ev['venue'] }}</p>
                                                <p class="f-roman text-[14px] text-white/90 mt-1">{{ $ev['addr'] }}</p>
                                            </div>
                                            <div class="pt-1">
                                                <a href="{{ $ev['maps'] }}" target="_blank" rel="noopener" class="jv-btn-gold">
                                                    <svg class="w-4 h-4"><use href="#ico-pin"/></svg>
                                                    <span>Lihat Lokasi</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </section>

                {{-- 2E. OUR MOMENTS & LOVE STORY (#galeri) --}}
                <section id="galeri" class="jv-galeri-sec">
                    <div class="jv-bg-layer opacity-15" style="background-image:url('{{ asset('images/themes/modern-javanese/Luxee-Jogja-14.png') }}');"></div>

                    <div class="relative z-10 max-w-md mx-auto space-y-6">
                        <div class="text-center space-y-2">
                            <h2 class="f-script text-[36px] sm:text-[42px] font-semibold tracking-[0.05em] text-[#4D290F] drop-shadow">Our Moments</h2>
                            <p class="f-roman text-[14px] text-[#34000C] leading-relaxed">
                                "A happy marriage is a long conversation<br>which always seems too short."
                            </p>
                        </div>

                        <div class="py-2">
                            <div class="text-left">
                                <img @click="openLightbox('{{ $duoPhotos[0] }}')" src="{{ $duoPhotos[0] }}" alt="Moment 1"
                                     class="muncul-kiri w-[62%] aspect-[2/3] object-cover border-2 border-[#4D290F] shadow-xl cursor-pointer inline-block">
                            </div>
                            <div class="text-right -mt-28 sm:-mt-36 relative z-10">
                                <img @click="openLightbox('{{ $duoPhotos[1] }}')" src="{{ $duoPhotos[1] }}" alt="Moment 2"
                                     class="muncul-kanan w-[62%] aspect-[2/3] object-cover border-2 border-[#4D290F] shadow-xl cursor-pointer inline-block">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 border-2 border-[#4D290F] bg-[#4D290F]">
                            @foreach($gridPhotos as $photo)
                                <button @click="openLightbox('{{ $photo }}')" type="button" class="overflow-hidden aspect-[2/3] group relative cursor-pointer">
                                    <img src="{{ $photo }}" alt="Galeri" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/35 transition duration-500"></div>
                                </button>
                            @endforeach
                        </div>

                        <div class="muncul rounded-[10px] bg-[#F4F9F8] p-6 text-center space-y-3 shadow-lg mt-8">
                            <h3 class="f-script text-[36px] sm:text-[42px] font-semibold text-[#4D290F] drop-shadow">Love Story</h3>
                            <p class="f-roman text-[16px] sm:text-[18px] tracking-[0.05em] text-[#34000C]">Cerita Kita</p>
                            <p class="f-roman text-[14px] leading-relaxed text-black">
                                {{ $ed['love_story'] ?? 'Di antara ribuan wajah, aku menemukan tatapanmu. Tatapan yang sederhana, namun mampu membuat dunia terasa tenang. Perjalanan kita mungkin tak selalu mudah, tapi di setiap langkah, aku menemukan alasan untuk tetap bertahan. Kini, dengan penuh cinta, kita memilih untuk saling menggenggam, menjadikan cinta ini abadi dalam pernikahan.' }}
                            </p>
                            <img src="{{ $storyPhoto }}" alt="Cerita Kita" class="w-full rounded-[5px] object-cover mt-2">
                            <div class="w-[69%] h-[2px] bg-[#4D290F] mx-auto mt-4"></div>
                        </div>
                    </div>
                </section>

                {{-- 2F. RSVP (#Wishes) + DOA & UCAPAN + KIRIM HADIAH + TERIMA KASIH --}}
                <div class="jv-wishes-wrap">
                    <section id="Wishes" class="px-5">
                        <div class="jv-rsvp-card max-w-md mx-auto">
                            <div class="relative -top-16 sm:-top-20 mb-[-54px] text-center">
                                <div class="goyangan w-40 sm:w-48 mx-auto">
                                    <img src="{{ asset('images/themes/modern-javanese/Luxee-Jogja-11.png') }}" alt="" class="w-full h-auto">
                                </div>
                            </div>

                            <h2 class="f-roman text-[28px] sm:text-[35px] tracking-[0.05em] text-[#E8B227] text-center mb-5 relative z-10">RSVP</h2>

                            <form @submit.prevent="submitWish()" class="relative z-10 space-y-3">
                                <div x-show="rsvpFeedback" x-cloak class="p-2.5 rounded bg-emerald-900/70 border border-emerald-400/50 text-emerald-200 text-xs text-center" x-text="rsvpFeedback"></div>
                                <div x-show="rsvpError" x-cloak class="p-2.5 rounded bg-red-900/70 border border-red-400/50 text-red-200 text-xs text-center" x-text="rsvpError"></div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block f-marcellus text-[12px] text-[#E8B227] mb-1">Nama Lengkap</label>
                                        <input type="text" x-model="rsvpForm.guest_name" required placeholder="Isikan Nama Anda"
                                               class="w-full px-3 py-2 bg-white text-[#401809] text-xs rounded focus:outline-none focus:ring-2 focus:ring-[#E8B227]">
                                    </div>
                                    <div>
                                        <label class="block f-marcellus text-[12px] text-[#E8B227] mb-1">Konfirmasi Kehadiran</label>
                                        <select x-model="rsvpForm.attendance"
                                                class="w-full px-3 py-2 bg-white text-[#401809] text-xs rounded focus:outline-none focus:ring-2 focus:ring-[#E8B227]">
                                            <option value="hadir">Akan Hadir</option>
                                            <option value="tidak_hadir">Tidak Bisa Hadir</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block f-marcellus text-[12px] text-[#E8B227] mb-1">Jumlah</label>
                                    <select x-model="rsvpForm.pax"
                                            class="w-full px-3 py-2 bg-white text-[#401809] text-xs rounded focus:outline-none focus:ring-2 focus:ring-[#E8B227]">
                                        <option value="-">-</option>
                                        <option value="1 Orang">1 Orang</option>
                                        <option value="2 Orang">2 Orang</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block f-marcellus text-[12px] text-[#E8B227] mb-1">Doa &amp; Ucapan</label>
                                    <textarea x-model="rsvpForm.message" rows="3" required placeholder="Berikan Ucapan Dan Doa Restu"
                                              class="w-full px-3 py-2 bg-white text-[#401809] text-xs rounded focus:outline-none focus:ring-2 focus:ring-[#E8B227]"></textarea>
                                </div>

                                <button type="submit" :disabled="rsvpSubmitting"
                                        class="w-full py-2.5 rounded bg-[#E8B227] hover:bg-white text-[#66051C] font-semibold f-marcellus text-[12px] tracking-[0.15em] uppercase transition cursor-pointer disabled:opacity-60">
                                    <span x-show="!rsvpSubmitting">Kirim Ucapan</span>
                                    <span x-show="rsvpSubmitting" x-cloak>Mengirim...</span>
                                </button>
                            </form>

                            <img src="{{ asset('images/themes/modern-javanese/Luxee-Jogja-12.png') }}" alt=""
                                 class="muncul absolute bottom-4 left-0 w-full pointer-events-none opacity-85 z-[1]">
                        </div>
                    </section>

                    <section class="px-6 py-10 max-w-md mx-auto space-y-5">
                        <h2 class="f-script text-[35px] sm:text-[38px] text-[#E8B227] text-center">Doa &amp; Ucapan</h2>

                        <div class="guestbook-scroll max-h-[280px] overflow-y-auto space-y-2.5 pr-1">
                            <template x-for="wish in wishes" :key="wish.id">
                                <div class="p-3.5 rounded-lg bg-[#401809]/75 border border-[#E8B227]/25 text-xs space-y-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="font-semibold text-[#E8B227]" x-text="wish.guest_name"></span>
                                        <span class="text-[10px] px-2 py-0.5 rounded bg-[#E8B227]/20 text-[#F8BB63]"
                                              x-text="wish.attendance === 'hadir' ? 'Hadir' : 'Tidak Hadir'"></span>
                                    </div>
                                    <p class="text-white/90 leading-relaxed" x-text="wish.message"></p>
                                </div>
                            </template>
                            <p x-show="wishes.length === 0" class="text-center text-xs text-white/75 italic py-4">
                                Belum ada ucapan. Jadilah yang pertama mendoakan mempelai!
                            </p>
                        </div>

                        <div class="jv-event-card jv-batik-overlay muncul text-center space-y-5 mt-6">
                            <div class="relative z-10 space-y-5">
                                <h2 class="f-script text-[35px] sm:text-[38px] text-[#E8B227]">Kirim Hadiah</h2>

                                <div class="space-y-2 text-white">
                                    <div class="w-12 h-12 mx-auto rounded-lg bg-[#E8B227] flex items-center justify-center text-white">
                                        <svg class="w-6 h-6" viewBox="0 0 640 512" fill="currentColor"><path d="M240.6 194.1c1.9-30.8 17.3-61.2 44-79.8C279.4 103.5 268.7 96 256 96h-29.4l30.7-22c7.2-5.1 8.9-15.1 3.7-22.3l-9.3-13c-5.1-7.2-15.1-8.9-22.3-3.7l-32 22.9 11.5-30.6c3.1-8.3-1.1-17.5-9.4-20.6l-15-5.6c-8.3-3.1-17.5 1.1-20.6 9.4l-19.9 53-19.9-53.1C121 2.1 111.8-2.1 103.5 1l-15 5.6C80.2 9.7 76 19 79.2 27.2l11.5 30.6L58.6 35c-7.2-5.1-17.2-3.5-22.3 3.7l-9.3 13c-5.1 7.2-3.5 17.2 3.7 22.3l30.7 22H32c-17.7 0-32 14.3-32 32v352c0 17.7 14.3 32 32 32h168.9c-5.5-9.5-8.9-20.3-8.9-32V256c0-29.9 20.8-55 48.6-61.9zM224 480c0 17.7 14.3 32 32 32h160V384H224v96zm224 32h160c17.7 0 32-14.3 32-32v-96H448v128zm160-288h-20.4c2.6-7.6 4.4-15.5 4.4-23.8 0-35.5-27-72.2-72.1-72.2-48.1 0-75.9 47.7-87.9 75.3-12.1-27.6-39.9-75.3-87.9-75.3-45.1 0-72.1 36.7-72.1 72.2 0 8.3 1.7 16.2 4.4 23.8H256c-17.7 0-32 14.3-32 32v96h192V224h15.3l.7-.2.7.2H448v128h192v-96c0-17.7-14.3-32-32-32z"/></svg>
                                    </div>
                                    @if($isDemo)
                                        <p class="f-serif text-[14px] font-medium">Penerima Kado : a.n {{ $primaryNick }}</p>
                                        <p class="f-serif text-[13px] leading-relaxed text-white/90">
                                            Alamat : Perumahan Griya Candramas 1 BA-16 RT 03 RW 14 Kecamatan Sedati, Sidoarjo (Kodepos 61253)<br>
                                            No. Whatsapp : 089665453877
                                        </p>
                                    @endif
                                </div>

                                <div class="space-y-3">
                                    @foreach($gifts as $idx => $acc)
                                    <div class="bg-white rounded-[10px] p-4 text-[#401809] shadow-md space-y-1.5">
                                        @if(!empty($acc['logo']))
                                            <img src="{{ $acc['logo'] }}" alt="{{ $acc['bank'] }}" class="max-w-[38%] h-auto mx-auto mb-1">
                                        @else
                                            <p class="f-tenor text-xs uppercase tracking-widest font-bold text-[#89612C]">{{ $acc['bank'] }}</p>
                                        @endif
                                        <p class="f-serif text-[14px]">a.n {{ $acc['account_name'] }}</p>
                                        <p class="f-abril text-[17px] tracking-wide text-[#401809]">{{ $acc['account_number'] }}</p>
                                        <div class="pt-1">
                                            <button @click="copyAccount('{{ $acc['account_number'] }}', {{ $idx }})" type="button" class="jv-btn-copy">
                                                <svg class="w-3 h-3"><use href="#ico-copy"/></svg>
                                                <span x-text="copiedIndex === {{ $idx }} ? 'Berhasil disalin' : 'Salin Rekening'">Salin Rekening</span>
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="muncul rounded-[10px] bg-[#F4F9F8] p-6 text-center space-y-4 shadow-lg">
                            <p class="f-roman text-[14px] text-black">Terima Kasih</p>
                            <h3 class="f-script text-[26px] font-semibold tracking-[0.05em] text-[#4D290F]">Keluarga &amp; Sahabat</h3>
                            <div class="w-[2px] h-16 bg-[#4D290F] mx-auto my-2"></div>
                            <p class="f-roman text-[14px] leading-relaxed text-black">
                                Merupakan suatu kebahagiaan dan kehormatan bagi kami, apabila Bapak/Ibu/Saudara/i, berkenan hadir dan memberikan do’a restu kepada Kami.
                            </p>
                            <p class="f-roman text-[14px] text-black pt-1">Kami yang berbahagia,</p>
                            <h4 class="f-script text-[27px] font-semibold tracking-[0.05em] text-[#4D290F]">{{ $primaryNick }} &amp; {{ $secondaryNick }}</h4>
                        </div>
                    </section>

                    {{-- Sticky Bottom Menu inside #sideright (zero scrollbar/viewport drift) --}}
                    <nav id="menunav" x-cloak x-show="scrollUnlocked" aria-label="Menu Navigasi">
                        <a href="#section-cover" @click.prevent="scrollToCover()" aria-label="Cover">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                        </a>
                        <a href="#mempelai" aria-label="Mempelai">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        </a>
                        <a href="#Acara" aria-label="Acara">
                            <svg class="w-5 h-5"><use href="#ico-cal"/></svg>
                        </a>
                        <a href="#galeri" aria-label="Galeri">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M21 19V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2zM8.5 13.5l2.5 3 3.5-4.5 4.5 6H5l3.5-4.5z"/></svg>
                        </a>
                        <a href="#Wishes" aria-label="RSVP">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4a2 2 0 0 0-2 2v18l4-4h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z"/></svg>
                        </a>
                    </nav>
                </div>
            </div>
        </main>
    </div>

    {{-- Lightbox Modal --}}
    <div x-cloak x-show="lightboxOpen" @keydown.escape.window="lightboxOpen = false" @click="lightboxOpen = false"
         class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4" role="dialog" aria-modal="true">
        <button @click="lightboxOpen = false" type="button" class="absolute top-4 right-4 px-3 py-1.5 rounded-full bg-white/15 text-white text-xs">Tutup</button>
        <img :src="activePhoto" @click.stop alt="Preview Foto" class="max-h-[88vh] max-w-full rounded-lg object-contain">
    </div>

    {{-- Toast Notification --}}
    <div x-cloak x-show="toastMsg" x-transition
         class="fixed top-4 left-1/2 -translate-x-1/2 z-[60] px-5 py-2.5 rounded-full bg-[#401809] border border-[#E8B227] text-[#E8B227] text-xs font-semibold shadow-2xl"
         x-text="toastMsg"></div>

    <script>
        function javaneseTheme(cfg) {
            return {
                isOpen: false, isLeaving: false, coverDismissed: false, contentVisible: false,
                overlayShown: false, scrollUnlocked: false, videoEnded: false, musicPlaying: false,
                musicMode: cfg.musicMode, audioElem: null, lightboxOpen: false, activePhoto: '',
                copiedIndex: null, toastMsg: '', wishes: cfg.initialWishes || [],
                rsvpForm: { guest_name: cfg.guestName || '', attendance: 'hadir', pax: '-', message: '' },
                rsvpSubmitting: false, rsvpFeedback: '', rsvpError: '',
                countdown: { days: '00', hours: '00', minutes: '00', seconds: '00' },

                init() {
                    if (this.musicMode !== 'none' && cfg.musicSrc) {
                        this.audioElem = new Audio(cfg.musicSrc);
                        this.audioElem.loop = true;
                        document.addEventListener('visibilitychange', () => {
                            if (!this.audioElem) return;
                            if (document.hidden && !this.audioElem.paused) this.audioElem.pause();
                            else if (!document.hidden && this.musicPlaying && this.audioElem.paused) this.audioElem.play().catch(() => {});
                        });
                    }
                    this.tickCountdown();
                    setInterval(() => this.tickCountdown(), 1000);
                },

                openInvitation() {
                    this.isOpen = true;
                    this.isLeaving = true;
                    this.contentVisible = true;
                    this.playMusic();

                    const video = document.getElementById('motion-video-player');
                    let videoActive = false;

                    if (video) {
                        video.currentTime = 0;
                        video.play().then(() => { videoActive = true; }).catch(() => {
                            this.overlayShown = true;
                            this.unlockScroll();
                        });
                        video.ontimeupdate = () => {
                            if (video.currentTime >= 7.5) this.overlayShown = true;
                        };
                        video.onended = () => {
                            this.videoEnded = true;
                            this.overlayShown = true;
                            this.unlockScroll();
                        };
                    }

                    setTimeout(() => { this.coverDismissed = true; }, 750);
                    setTimeout(() => { if (!videoActive) this.overlayShown = true; }, 8200);
                    setTimeout(() => { this.unlockScroll(); }, 20800);

                    this.$nextTick(() => this.initScrollReveals());
                },

                enterLetter() {
                    this.overlayShown = true;
                    this.unlockScroll();
                    this.$nextTick(() => document.getElementById('opening')?.scrollIntoView({ behavior: 'smooth' }));
                },

                unlockScroll() {
                    this.scrollUnlocked = true;
                    document.body.classList.remove('jv-locked');
                },

                scrollToCover() {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                initScrollReveals() {
                    const targets = document.querySelectorAll('.muncul, .muncul-kiri, .muncul-kanan, .zoom');
                    const obs = new IntersectionObserver((entries) => {
                        entries.forEach((entry) => {
                            if (entry.isIntersecting) {
                                entry.target.classList.add('active');
                                obs.unobserve(entry.target);
                            }
                        });
                    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
                    targets.forEach(el => obs.observe(el));

                    const navLinks = [...document.querySelectorAll('#menunav a')];
                    const secIds = ['motion-video', 'mempelai', 'Acara', 'galeri', 'Wishes'];
                    const navObs = new IntersectionObserver((entries) => {
                        entries.forEach((entry) => {
                            if (!entry.isIntersecting) return;
                            const href = entry.target.id === 'motion-video' ? '#section-cover' : `#${entry.target.id}`;
                            navLinks.forEach(a => a.classList.toggle('is-active', a.getAttribute('href') === href));
                        });
                    }, { rootMargin: '-35% 0px -45% 0px' });
                    secIds.forEach(id => { const el = document.getElementById(id); if (el) navObs.observe(el); });
                },

                playMusic() {
                    if (!this.audioElem) return;
                    this.audioElem.volume = 0.55;
                    this.audioElem.play().then(() => { this.musicPlaying = true; }).catch(() => {});
                },

                toggleMusic() {
                    if (!this.audioElem) return;
                    if (this.musicPlaying) { this.audioElem.pause(); this.musicPlaying = false; }
                    else { this.playMusic(); }
                },

                tickCountdown() {
                    const diff = new Date(cfg.targetDate).getTime() - Date.now();
                    if (isNaN(diff) || diff <= 0) {
                        this.countdown = { days: '00', hours: '00', minutes: '00', seconds: '00' };
                        return;
                    }
                    this.countdown = {
                        days: String(Math.floor(diff / 86400000)).padStart(2, '0'),
                        hours: String(Math.floor((diff % 86400000) / 3600000)).padStart(2, '0'),
                        minutes: String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0'),
                        seconds: String(Math.floor((diff % 60000) / 1000)).padStart(2, '0'),
                    };
                },

                openLightbox(src) { this.activePhoto = src; this.lightboxOpen = true; },

                showToast(msg) {
                    this.toastMsg = msg;
                    setTimeout(() => { this.toastMsg = ''; }, 2400);
                },

                copyAccount(num, idx) {
                    navigator.clipboard?.writeText(num).catch(() => {});
                    this.copiedIndex = idx;
                    this.showToast('Nomor rekening berhasil disalin.');
                    setTimeout(() => { this.copiedIndex = null; }, 2200);
                },

                submitWish() {
                    if (!this.rsvpForm.guest_name.trim() || !this.rsvpForm.message.trim()) {
                        this.rsvpError = 'Mohon isi nama dan ucapan Anda.';
                        return;
                    }
                    this.rsvpSubmitting = true; this.rsvpError = ''; this.rsvpFeedback = '';

                    if (cfg.isDemo || !cfg.wishUrl) {
                        this.wishes.unshift({
                            id: 'demo-' + Date.now(), guest_name: this.rsvpForm.guest_name,
                            attendance: this.rsvpForm.attendance, message: this.rsvpForm.message, time_ago: 'Baru saja'
                        });
                        this.rsvpForm.message = '';
                        this.rsvpFeedback = 'Ucapan & konfirmasi Anda berhasil tercatat!';
                        this.rsvpSubmitting = false;
                        return;
                    }

                    fetch(cfg.wishUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            guest_name: this.rsvpForm.guest_name,
                            attendance: this.rsvpForm.attendance,
                            message: this.rsvpForm.message
                        })
                    })
                    .then(r => { if (!r.ok) throw new Error(); return r.json(); })
                    .then(d => { this.wishes.unshift(d.wish); this.rsvpForm.message = ''; this.rsvpFeedback = d.message || 'Ucapan berhasil dikirim!'; })
                    .catch(() => { this.rsvpError = 'Gagal mengirim ucapan. Silakan coba lagi.'; })
                    .finally(() => { this.rsvpSubmitting = false; });
                }
            };
        }
    </script>
</body>
</html>
