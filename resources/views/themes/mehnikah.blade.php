<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $invitation->title }} | The Wedding of {{ $invitation->event_data['primary_name'] ?? 'Rozi' }} &amp; {{ $invitation->event_data['secondary_name'] ?? 'Nabila' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Playfair Display, Allura, Plus Jakarta Sans, Jura -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Allura&family=Jura:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS v4 & Alpine.js CDN -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }

        :root {
            --mn-bg: #FAF4EF;
            --mn-card: #FFFFFF;
            --mn-primary: #9D583D;
            --mn-secondary: #B67358;
            --mn-accent: #FFD8C8;
            --mn-dark: #1E1E1E;
            --mn-muted: #7A6658;
            --mn-border: rgba(157, 88, 61, 0.2);
        }

        .font-serif-playfair { font-family: 'Playfair Display', Georgia, serif; }
        .font-script-allura   { font-family: 'Allura', cursive; }
        .font-sans-jura       { font-family: 'Jura', sans-serif; }
        .font-sans-body       { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }

        /* Arch Frame Khas Mehnikah */
        .arch-frame {
            border-top-left-radius: 9999px;
            border-top-right-radius: 9999px;
            border-bottom-left-radius: 24px;
            border-bottom-right-radius: 24px;
            overflow: hidden;
        }

        /* Cover Screen Animation */
        #section-cover {
            transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1), transform 0.8s ease;
        }
        #section-cover.is-hidden {
            opacity: 0;
            transform: translateY(-100%);
            pointer-events: none;
            display: none !important;
        }

        /* Subtle Float & Sway */
        @keyframes gentleSway {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50%      { transform: translateY(-8px) rotate(2deg); }
        }
        .anim-sway {
            animation: gentleSway 4.5s ease-in-out infinite;
        }

        @keyframes pulseSoft {
            0%, 100% { transform: scale(1); }
            50%      { transform: scale(1.03); }
        }
        .anim-pulse {
            animation: pulseSoft 3s ease-in-out infinite;
        }

        /* Scroll Reveal */
        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity 0.75s cubic-bezier(0.16, 1, 0.3, 1), transform 0.75s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal.revealed {
            opacity: 1;
            transform: translateY(0);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #FAF4EF; }
        ::-webkit-scrollbar-thumb { background: #D5B4A5; border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: #9D583D; }
    </style>
</head>

@php
    $eventType = match ($invitation->category) {
        'pernikahan' => 'wedding',
        'tunangan'   => 'engagement',
        default      => $invitation->category
    };

    $primaryName     = $invitation->event_data['primary_name'] ?? 'Rozi';
    $secondaryName   = $invitation->event_data['secondary_name'] ?? 'Nabila';
    $s1              = $invitation->event_data['session_1'] ?? [];
    $s2              = $invitation->event_data['session_2'] ?? [];
    $gallery         = $invitation->event_data['gallery_images'] ?? [];
    $gifts           = $invitation->event_data['gift_accounts'] ?? [];
    $greeting        = $invitation->event_data['greeting'] ?? 'Pernikahan Kami';
    $quote           = $invitation->event_data['quote'] ?? 'Dan di antara tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu isteri-isteri dari jenismu sendiri, supaya kamu cenderung dan merasa tenteram kepadanya, dan dijadikan-Nya diantaramu rasa kasih dan sayang. (Q.S. Ar-Rum: 21)';
    $coverImg        = $invitation->event_data['cover_image'] ?? '';
    $primaryParents  = $invitation->event_data['primary_title_parents'] ?? 'Putra Terakhir dari Bapak Zainul Ihsan & Ibu Uswatun';
    $secondaryParents= $invitation->event_data['secondary_title_parents'] ?? 'Putri Pertama dari Bapak Samsul Amin & Ibu Syamsiah';
    $primaryIg       = $invitation->event_data['primary_instagram'] ?? 'ainul_rozi';
    $secondaryIg     = $invitation->event_data['secondary_instagram'] ?? 'ninda_nabila';

    // Tanggal Acara
    $eventDateObj    = !empty($s1['date']) ? \Carbon\Carbon::parse($s1['date']) : now();
    $eventDateStr    = $eventDateObj->isoFormat('dddd, D MMMM Y');
    $eventDateShort  = $eventDateObj->format('d . m . Y');

    // Google Calendar Link
    $calDate  = str_replace('-', '', $s1['date'] ?? date('Y-m-d'));
    $calTimes = explode(' - ', $s1['time'] ?? '09:00 - 12:00');
    $calStart = str_pad(str_replace([':', ' ', 'WIB'], '', $calTimes[0] ?? '0900'), 4, '0');
    $calEnd   = isset($calTimes[1]) ? str_pad(str_replace([':', ' ', 'WIB'], '', $calTimes[1]), 4, '0') : '1200';
    $calUrl   = 'https://calendar.google.com/calendar/render?action=TEMPLATE'
        . '&text='     . urlencode($invitation->title)
        . '&dates='    . $calDate . 'T' . $calStart . '00Z/' . $calDate . 'T' . $calEnd . '00Z'
        . '&location=' . urlencode(($s1['venue'] ?? '') . ', ' . ($s1['address'] ?? ''));

    // Default Love Story entries jika belum diset dinamis
    $stories = !empty($invitation->event_data['rundown']) && is_array($invitation->event_data['rundown']) && count($invitation->event_data['rundown']) > 0
        ? $invitation->event_data['rundown']
        : [
            [
                'date'  => 'Tahun 2019',
                'title' => 'Awal Pertemuan dan Rasa',
                'desc'  => 'Takdir mempertemukan kami di tahun 2019 melalui tatap sederhana yang berkesan. Di tahun yang sama, hangatnya rasa menyatukan langkah kami untuk mulai mengikat hati dan menjalin kasih.',
                'photo' => 'https://inv.mehnikah.com/wp-content/uploads/2026/09/LKZD8TB6f6KNxhIft1CTyK9BvbdH0zshz4rxneVW.webp'
            ],
            [
                'date'  => '2019 - 2025',
                'title' => 'Tumbuh dalam Kepercayaan',
                'desc'  => 'Bertahun-tahun merawat cinta menjadikan ikatan kami semakin matang dan penuh percaya. Tanpa perlu proses perantara, ketetapan hati ini makin bulat untuk saling menjaga selamanya.',
                'photo' => 'https://inv.mehnikah.com/wp-content/uploads/2026/09/npM0XpMe1tFYNuPFFzOJKA9IogJ602vvrEG5lhFr.webp'
            ],
            [
                'date'  => 'Tahun 2026',
                'title' => 'Bermuara pada Pernikahan',
                'desc'  => 'Tahun 2026 menjadi saksi saat kami memutuskan melangkah langsung menuju ikatan pernikahan. Sebuah janji suci sehidup semati untuk mengarungi sisa masa depan bersama.',
                'photo' => 'https://inv.mehnikah.com/wp-content/uploads/2026/09/yuYcN0ZvJLqaKMIo9ONzoP6hLq4w0iv4UgWpUy47.webp'
            ]
        ];
@endphp

<body data-event-type="{{ $eventType }}"
      class="font-sans-body bg-[#FAF4EF] text-[#1E1E1E] antialiased selection:bg-[#9D583D] selection:text-white overflow-x-hidden min-h-screen"
      x-data="invitationEngine({
          musicMode: '{{ $invitation->event_data['music_mode'] ?? 'custom' }}',
          musicUrl: '{{ !empty($invitation->event_data['music_file']) ? asset('storage/' . $invitation->event_data['music_file']) : 'https://inv.mehnikah.com/wp-content/uploads/user/7/323945/323945kohptw7e.mp3' }}',
          guestName: {{ json_encode($guestName ?? 'Qiki') }},
          targetDate: '{{ $targetDate ?? $eventDateObj->format('Y-m-d H:i:s') }}',
          initialWishes: {{ json_encode($wishesData ?? []) }},
          initialCounts: {{ json_encode($initialCounts ?? ['total' => 0, 'hadir' => 0, 'tidak_hadir' => 0]) }}
      })"
      x-init="
          document.body.classList.add('overflow-hidden');
          $nextTick(() => {
              const obs = new IntersectionObserver((entries) => {
                  entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('revealed'); });
              }, { threshold: 0.12 });
              document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
          });
      ">

    {{-- =========================================================
         1. COVER SCREEN TERKUNCI (#section-cover)
         Aesthetic Terracotta & Warm Linen khas Mehnikah
         ========================================================= --}}
    <div id="section-cover"
         :class="isOpen ? 'is-hidden' : ''"
         class="fixed inset-0 z-50 h-dvh w-full overflow-hidden flex flex-col justify-between items-center text-center p-6 bg-gradient-to-b from-[#FAF4EF] via-[#F4E8DF] to-[#EEDCCF] border-b border-[#9D583D]/20">

        {{-- Daun Botanical Atas --}}
        <div class="pt-2 flex flex-col items-center">
            <div class="anim-sway w-28 h-16 flex items-center justify-center opacity-85">
                <svg viewBox="0 0 120 70" fill="none" class="w-full h-full text-[#9D583D]" xmlns="http://www.w3.org/2000/svg">
                    <path d="M60 65 Q50 35 15 25 Q40 28 60 45 Q80 28 105 25 Q70 35 60 65Z" fill="#9D583D" fill-opacity="0.25"/>
                    <path d="M60 65 Q58 30 60 5" stroke="#9D583D" stroke-width="1.2"/>
                    <path d="M60 45 Q40 38 25 35" stroke="#9D583D" stroke-width="0.9"/>
                    <path d="M60 30 Q45 22 30 18" stroke="#9D583D" stroke-width="0.9"/>
                    <path d="M60 45 Q80 38 95 35" stroke="#9D583D" stroke-width="0.9"/>
                    <path d="M60 30 Q75 22 90 18" stroke="#9D583D" stroke-width="0.9"/>
                </svg>
            </div>
            <p class="font-sans-jura text-[11px] tracking-[0.35em] uppercase text-[#9D583D] font-semibold mt-1">
                Pernikahan Kami
            </p>
        </div>

        {{-- Foto Pasangan dalam Arch Frame Terracotta --}}
        <div class="my-auto w-full max-w-sm flex flex-col items-center space-y-4">
            <div class="arch-frame w-44 h-60 p-1.5 bg-gradient-to-b from-[#9D583D] via-[#B67358] to-[#FFD8C8] shadow-[0_12px_35px_rgba(157,88,61,0.25)] relative">
                <div class="w-full h-full arch-frame overflow-hidden bg-white">
                    @if(!empty($coverImg))
                        <img src="{{ asset('storage/' . $coverImg) }}" alt="{{ $invitation->title }}" class="w-full h-full object-cover">
                    @else
                        <img src="https://inv.mehnikah.com/wp-content/uploads/2026/09/ygbiz9Yn5f1f6jmu1Myws1Obh2yaD0yZ2FloIyho.webp" alt="{{ $invitation->title }}" class="w-full h-full object-cover">
                    @endif
                </div>
            </div>

            {{-- Nama Mempelai --}}
            <div class="space-y-0.5">
                <h1 class="font-serif-playfair text-3xl sm:text-4xl font-bold tracking-tight text-[#1E1E1E]">
                    {{ $primaryName }}
                    <span class="font-script-allura text-4xl text-[#9D583D] mx-1.5 font-normal">&amp;</span>
                    {{ $secondaryName }}
                </h1>
                <p class="font-sans-jura text-xs tracking-[0.25em] text-[#9D583D] font-medium pt-1">
                    {{ $eventDateStr }}
                </p>
            </div>

            {{-- Kotak Tamu Undangan (?to=) --}}
            <div class="w-full max-w-[290px] p-4 rounded-2xl bg-white/80 border border-[#9D583D]/20 shadow-lg backdrop-blur-md text-center">
                <p class="font-sans-jura text-[10px] tracking-widest uppercase text-[#7A6658]">Kepada Yth:</p>
                <p class="font-serif-playfair text-lg font-bold text-[#9D583D] mt-0.5 truncate">
                    {{ !empty($guestName) ? $guestName : 'Qiki' }}
                </p>
                @if(!empty($guestAddress))
                    <p class="font-sans-jura text-xs text-[#7A6658] mt-0.5 truncate font-medium">
                        di {{ $guestAddress }}
                    </p>
                @endif
                <p class="text-[9px] text-[#7A6658]/80 leading-tight mt-1.5 italic">
                    Mohon maaf apabila ada kesalahan penulisan nama atau gelar.
                </p>
            </div>
        </div>

        {{-- Tombol Buka Undangan (#tombol-buka) --}}
        <div class="pb-6 w-full max-w-[280px]">
            <button id="tombol-buka"
                    @click="openInvitation()"
                    type="button"
                    class="w-full min-h-[48px] px-6 py-3 rounded-full font-sans-body font-semibold text-xs tracking-widest uppercase bg-[#9D583D] hover:bg-[#854730] text-white shadow-[0_6px_20px_rgba(157,88,61,0.35)] active:scale-95 transition flex items-center justify-center gap-2.5">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>Buka Undangan</span>
            </button>
        </div>
    </div>

    {{-- =========================================================
         MAIN CONTENT CONTAINER (100% Mobile-first Responsive)
         ========================================================= --}}
    <main class="relative max-w-lg mx-auto bg-[#FAF4EF] shadow-2xl min-h-screen pb-28 border-x border-[#9D583D]/10 overflow-x-hidden">

        {{-- =========================================================
             2. HERO SECTION (#cover)
             ========================================================= --}}
        <section id="cover" class="relative text-center px-6 pt-16 pb-12 overflow-hidden bg-gradient-to-b from-[#FAF4EF] via-[#F6ECE4] to-[#FAF4EF]">
            {{-- Foto Pasangan Arch --}}
            <div class="arch-frame w-44 h-56 mx-auto p-1.5 bg-gradient-to-b from-[#9D583D] to-[#FFD8C8] shadow-xl mb-6">
                <div class="w-full h-full arch-frame overflow-hidden bg-white">
                    @if(!empty($coverImg))
                        <img src="{{ asset('storage/' . $coverImg) }}" alt="{{ $invitation->title }}" class="w-full h-full object-cover">
                    @else
                        <img src="https://inv.mehnikah.com/wp-content/uploads/2026/09/ygbiz9Yn5f1f6jmu1Myws1Obh2yaD0yZ2FloIyho.webp" alt="{{ $invitation->title }}" class="w-full h-full object-cover">
                    @endif
                </div>
            </div>

            <p class="font-sans-jura text-[11px] tracking-[0.3em] uppercase text-[#9D583D] font-semibold mb-2">The Wedding Of</p>
            <h1 class="font-serif-playfair text-4xl sm:text-5xl font-bold text-[#1E1E1E] leading-tight">
                {{ $primaryName }}
                <span class="font-script-allura text-4xl text-[#9D583D] mx-1.5 font-normal">&amp;</span>
                {{ $secondaryName }}
            </h1>

            <p class="font-sans-body text-xs text-[#7A6658] font-medium mt-3">
                {{ $eventDateStr }}
            </p>

            {{-- Countdown Timer --}}
            <div class="mt-8 p-5 rounded-2xl bg-white border border-[#9D583D]/20 shadow-[0_8px_25px_rgba(157,88,61,0.08)] max-w-xs mx-auto">
                <p class="font-sans-jura text-[10px] uppercase tracking-widest text-[#9D583D] mb-3 font-semibold">Hitung Mundur Acara</p>
                <div class="grid grid-cols-4 gap-2 text-center">
                    <div class="bg-[#FAF4EF] rounded-xl p-2.5 border border-[#9D583D]/15">
                        <span class="block text-xl font-bold text-[#9D583D] font-serif-playfair" x-text="countdown.days">00</span>
                        <span class="text-[9px] uppercase text-[#7A6658] font-sans-jura">Hari</span>
                    </div>
                    <div class="bg-[#FAF4EF] rounded-xl p-2.5 border border-[#9D583D]/15">
                        <span class="block text-xl font-bold text-[#9D583D] font-serif-playfair" x-text="countdown.hours">00</span>
                        <span class="text-[9px] uppercase text-[#7A6658] font-sans-jura">Jam</span>
                    </div>
                    <div class="bg-[#FAF4EF] rounded-xl p-2.5 border border-[#9D583D]/15">
                        <span class="block text-xl font-bold text-[#9D583D] font-serif-playfair" x-text="countdown.minutes">00</span>
                        <span class="text-[9px] uppercase text-[#7A6658] font-sans-jura">Menit</span>
                    </div>
                    <div class="bg-[#FAF4EF] rounded-xl p-2.5 border border-[#9D583D]/15">
                        <span class="block text-xl font-bold text-[#9D583D] font-serif-playfair" x-text="countdown.seconds">00</span>
                        <span class="text-[9px] uppercase text-[#7A6658] font-sans-jura">Detik</span>
                    </div>
                </div>

                {{-- Simpan ke Kalender --}}
                <div class="mt-4 pt-3 border-t border-[#9D583D]/10">
                    <a href="{{ $calUrl }}" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-semibold bg-[#9D583D]/10 hover:bg-[#9D583D]/20 text-[#9D583D] border border-[#9D583D]/25 transition active:scale-95">
                        <svg class="w-3.5 h-3.5 text-[#9D583D]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Simpan ke Kalender</span>
                    </a>
                </div>
            </div>
        </section>

        {{-- Divider Gelombang --}}
        <div class="w-full overflow-hidden leading-none text-[#FAF4EF]">
            <svg viewBox="0 0 1200 60" preserveAspectRatio="none" class="w-full h-6 text-white fill-current">
                <path d="M0,0 C300,50 600,0 900,45 L1200,10 L1200,60 L0,60 Z"></path>
            </svg>
        </div>

        {{-- =========================================================
             3. AYAT SUCI & DOA
             ========================================================= --}}
        <section class="px-6 py-10 bg-white text-center reveal">
            <div class="max-w-md mx-auto space-y-3">
                <div class="w-12 h-0.5 bg-[#9D583D]/30 mx-auto"></div>
                <p class="font-serif-playfair text-sm italic text-[#1E1E1E] leading-relaxed px-4">
                    "{{ $quote }}"
                </p>
                <div class="w-12 h-0.5 bg-[#9D583D]/30 mx-auto"></div>
            </div>
        </section>

        {{-- =========================================================
             4. KEDUA MEMPELAI (#mempelai)
             ========================================================= --}}
        <section id="mempelai" class="px-6 py-14 bg-white border-t border-[#9D583D]/10 reveal">
            <div class="text-center mb-10 space-y-1">
                <span class="text-[10px] font-sans-jura tracking-[0.3em] uppercase text-[#9D583D] font-semibold">Acara Pernikahan</span>
                <h2 class="font-serif-playfair text-3xl font-bold text-[#1E1E1E]">Kedua Mempelai</h2>
                <p class="text-xs text-[#7A6658] leading-relaxed max-w-xs mx-auto pt-1">
                    Dengan memohon rahmat dan ridho Allah SWT, kami bermaksud menyelenggarakan pernikahan kami:
                </p>
            </div>

            <div class="space-y-8">
                {{-- The Groom --}}
                <div class="p-6 rounded-3xl bg-[#FAF4EF] border border-[#9D583D]/15 shadow-sm text-center">
                    <div class="arch-frame w-32 h-44 mx-auto p-1 bg-gradient-to-b from-[#9D583D] to-[#FFD8C8] shadow-md mb-4">
                        <div class="w-full h-full arch-frame overflow-hidden bg-stone-200">
                            <img src="{{ !empty($invitation->event_data['groom_photo']) ? asset('storage/' . $invitation->event_data['groom_photo']) : 'https://inv.mehnikah.com/wp-content/uploads/2026/09/XagQECOxF1zI9tdTz4yIIvoX7ogk3X1ksv8cRfB8.webp' }}" alt="{{ $primaryName }}" class="w-full h-full object-cover">
                        </div>
                    </div>
                    <span class="text-[10px] font-sans-jura tracking-widest uppercase text-[#9D583D] font-bold block mb-1">The Groom</span>
                    <h3 class="font-serif-playfair text-2xl font-bold text-[#1E1E1E]">{{ $primaryName }}</h3>
                    @if(!empty($primaryParents))
                        <p class="text-xs text-[#7A6658] leading-relaxed mt-1 px-4">{{ $primaryParents }}</p>
                    @endif
                    @if(!empty($primaryIg))
                        <a href="https://instagram.com/{{ ltrim($primaryIg, '@') }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1.5 mt-3 text-xs text-[#9D583D] font-medium hover:underline">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            <span>@ {{ ltrim($primaryIg, '@') }}</span>
                        </a>
                    @endif
                </div>

                {{-- Ampersand separator --}}
                <div class="text-center">
                    <span class="font-script-allura text-4xl text-[#9D583D]">&amp;</span>
                </div>

                {{-- The Bride --}}
                <div class="p-6 rounded-3xl bg-[#FAF4EF] border border-[#9D583D]/15 shadow-sm text-center">
                    <div class="arch-frame w-32 h-44 mx-auto p-1 bg-gradient-to-b from-[#9D583D] to-[#FFD8C8] shadow-md mb-4">
                        <div class="w-full h-full arch-frame overflow-hidden bg-stone-200">
                            <img src="{{ !empty($invitation->event_data['bride_photo']) ? asset('storage/' . $invitation->event_data['bride_photo']) : 'https://inv.mehnikah.com/wp-content/uploads/2026/09/xfozkVzJRL2rccH4GGOsLoEIK0ihq1sxliQUifPV.webp' }}" alt="{{ $secondaryName }}" class="w-full h-full object-cover">
                        </div>
                    </div>
                    <span class="text-[10px] font-sans-jura tracking-widest uppercase text-[#9D583D] font-bold block mb-1">The Bride</span>
                    <h3 class="font-serif-playfair text-2xl font-bold text-[#1E1E1E]">{{ $secondaryName }}</h3>
                    @if(!empty($secondaryParents))
                        <p class="text-xs text-[#7A6658] leading-relaxed mt-1 px-4">{{ $secondaryParents }}</p>
                    @endif
                    @if(!empty($secondaryIg))
                        <a href="https://instagram.com/{{ ltrim($secondaryIg, '@') }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1.5 mt-3 text-xs text-[#9D583D] font-medium hover:underline">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            <span>@ {{ ltrim($secondaryIg, '@') }}</span>
                        </a>
                    @endif
                </div>
            </div>
        </section>

        {{-- =========================================================
             5. ACARA PERNIKAHAN (#acara)
             ========================================================= --}}
        <section id="acara" class="px-6 py-14 bg-[#FAF4EF] border-t border-[#9D583D]/10 reveal">
            <div class="text-center mb-10 space-y-1">
                <span class="text-[10px] font-sans-jura tracking-[0.3em] uppercase text-[#9D583D] font-semibold">Waktu &amp; Lokasi</span>
                <h2 class="font-serif-playfair text-3xl font-bold text-[#1E1E1E]">Acara Pernikahan</h2>
            </div>

            <div class="space-y-6">
                {{-- Akad Nikah --}}
                <div class="p-6 rounded-3xl bg-white border border-[#9D583D]/20 shadow-md">
                    <span class="inline-block px-3 py-1 rounded-full text-[9px] font-sans-jura tracking-widest uppercase bg-[#9D583D]/15 text-[#9D583D] font-bold mb-3">
                        Akad Nikah
                    </span>
                    <h3 class="font-serif-playfair text-2xl font-bold text-[#1E1E1E] mb-3">{{ $s1['title'] ?? 'Akad Nikah' }}</h3>
                    <div class="space-y-2 text-xs text-[#7A6658]">
                        <p class="font-semibold text-[#1E1E1E]">{{ \Carbon\Carbon::parse($s1['date'] ?? now())->isoFormat('dddd, D MMMM Y') }}</p>
                        <p>{{ $s1['time'] ?? '09.00 WIB - Selesai' }}</p>
                        <p class="pt-1">
                            <strong class="text-[#1E1E1E] block">{{ $s1['venue'] ?? 'Kediaman Mempelai' }}</strong>
                            {{ $s1['address'] ?? 'Perumahan Kedungturi Permai 1, Sidoarjo' }}
                        </p>
                    </div>

                    @if(!empty($s1['maps_url']))
                        <div class="mt-5 pt-4 border-t border-stone-100">
                            <a href="{{ $s1['maps_url'] }}" target="_blank" rel="noopener noreferrer"
                               class="w-full min-h-[44px] inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold bg-[#9D583D] hover:bg-[#854730] text-white shadow-sm transition active:scale-95">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Petunjuk Lokasi (Google Maps)</span>
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Resepsi Nikah --}}
                @if(!empty($s2['title']) || !empty($s2['enabled']))
                <div class="p-6 rounded-3xl bg-white border border-[#9D583D]/20 shadow-md">
                    <span class="inline-block px-3 py-1 rounded-full text-[9px] font-sans-jura tracking-widest uppercase bg-[#FAF4EF] text-[#9D583D] border border-[#9D583D]/20 font-bold mb-3">
                        Resepsi
                    </span>
                    <h3 class="font-serif-playfair text-2xl font-bold text-[#1E1E1E] mb-3">{{ $s2['title'] ?? 'Resepsi Nikah' }}</h3>
                    <div class="space-y-2 text-xs text-[#7A6658]">
                        <p class="font-semibold text-[#1E1E1E]">{{ \Carbon\Carbon::parse($s2['date'] ?? now())->isoFormat('dddd, D MMMM Y') }}</p>
                        <p>{{ $s2['time'] ?? '18.00 - 21.00 WIB' }}</p>
                        <p class="pt-1">
                            <strong class="text-[#1E1E1E] block">{{ $s2['venue'] ?? 'Kediaman Mempelai' }}</strong>
                            {{ $s2['address'] ?? 'Perumahan Kedungturi Permai 1, Sidoarjo' }}
                        </p>
                    </div>

                    @if(!empty($s2['maps_url']))
                        <div class="mt-5 pt-4 border-t border-stone-100">
                            <a href="{{ $s2['maps_url'] }}" target="_blank" rel="noopener noreferrer"
                               class="w-full min-h-[44px] inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold bg-[#9D583D] hover:bg-[#854730] text-white shadow-sm transition active:scale-95">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Petunjuk Lokasi (Google Maps)</span>
                            </a>
                        </div>
                    @endif
                </div>
                @endif
            </div>
        </section>

        {{-- =========================================================
             6. LOVE STORY SECTION (#story)
             FITUR KHUSUS: DILENGKAPI FOTO & TANGGAL SESUAI REQUEST
             ========================================================= --}}
        <section id="story" class="px-6 py-14 bg-white border-t border-[#9D583D]/10 reveal">
            <div class="text-center mb-10 space-y-1">
                <span class="text-[10px] font-sans-jura tracking-[0.3em] uppercase text-[#9D583D] font-semibold">Kisah Kasih</span>
                <h2 class="font-serif-playfair text-3xl font-bold text-[#1E1E1E]">Love Story</h2>
                <p class="text-xs text-[#7A6658] leading-relaxed max-w-xs mx-auto pt-1">
                    Setiap langkah perjalanan cinta kami yang bermuara dalam janji suci pernikahan:
                </p>
            </div>

            {{-- Vertical Timeline List --}}
            <div class="relative pl-6 sm:pl-8 space-y-10 border-l-2 border-[#9D583D]/30 ml-3 sm:ml-4">
                @foreach($stories as $idx => $st)
                <div class="relative group">
                    {{-- Timeline Dot / Icon --}}
                    <div class="absolute -left-[31px] sm:-left-[39px] top-1.5 w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-[#9D583D] text-white flex items-center justify-center shadow-md border-2 border-white">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>

                    {{-- Timeline Card --}}
                    <div class="p-5 rounded-2xl bg-[#FAF4EF] border border-[#9D583D]/20 shadow-[0_6px_20px_rgba(157,88,61,0.06)] space-y-3">
                        {{-- Foto Milestone Cerita --}}
                        @if(!empty($st['photo']))
                        <div class="w-full h-48 sm:h-52 rounded-xl overflow-hidden bg-stone-200 shadow-inner">
                            <img src="{{ $st['photo'] }}" alt="{{ $st['title'] ?? 'Love Story' }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        </div>
                        @endif

                        {{-- Tanggal / Meta --}}
                        <div class="flex items-center gap-2">
                            <span class="inline-block px-3 py-1 rounded-full text-[10px] font-sans-jura tracking-wider uppercase bg-[#9D583D] text-white font-semibold">
                                {{ $st['date'] ?? 'Milestone' }}
                            </span>
                        </div>

                        {{-- Judul Cerita --}}
                        <h4 class="font-serif-playfair text-xl font-bold text-[#1E1E1E]">
                            {{ $st['title'] ?? 'Kisah Kami' }}
                        </h4>

                        {{-- Narasi Cerita --}}
                        <p class="text-xs text-[#7A6658] leading-relaxed">
                            {{ $st['desc'] ?? ($st['story'] ?? '') }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        {{-- =========================================================
             7. GALLERY SECTION (#galeri)
             ========================================================= --}}
        @if(!empty($gallery) && count($gallery) > 0)
        <section id="galeri" class="px-6 py-14 bg-[#FAF4EF] border-t border-[#9D583D]/10 reveal">
            <div class="text-center mb-8 space-y-1">
                <span class="text-[10px] font-sans-jura tracking-[0.3em] uppercase text-[#9D583D] font-semibold">Momen Indah</span>
                <h2 class="font-serif-playfair text-3xl font-bold text-[#1E1E1E]">Gallery</h2>
            </div>

            <div class="grid grid-cols-2 gap-3">
                @foreach($gallery as $photo)
                    <div class="arch-frame aspect-[3/4] overflow-hidden bg-stone-200 border border-[#9D583D]/20 shadow-md cursor-pointer group"
                         @click="openLightbox('{{ asset('storage/' . $photo) }}')">
                        <img src="{{ asset('storage/' . $photo) }}" alt="Foto Galeri" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- =========================================================
             8. RSVP & UCAPAN (#wishes)
             ========================================================= --}}
        <section id="wishes" class="px-6 py-14 bg-white border-t border-[#9D583D]/10 reveal">
            <div class="text-center mb-8 space-y-1">
                <span class="text-[10px] font-sans-jura tracking-[0.3em] uppercase text-[#9D583D] font-semibold">Buku Tamu</span>
                <h2 class="font-serif-playfair text-3xl font-bold text-[#1E1E1E]">RSVP &amp; Doa Restu</h2>
                <div class="flex items-center justify-center gap-3 pt-2 text-xs">
                    <span class="px-3 py-1 rounded-full bg-[#9D583D]/10 text-[#9D583D] font-semibold">
                        <strong x-text="counts.hadir">0</strong> Hadir
                    </span>
                    <span class="px-3 py-1 rounded-full bg-stone-100 text-[#7A6658]">
                        <strong x-text="counts.total">0</strong> Total Pesan
                    </span>
                </div>
            </div>

            {{-- Form RSVP AJAX --}}
            <form @submit.prevent="submitRsvp('{{ route('invitation.wish', $invitation->slug) }}')" class="p-6 rounded-3xl bg-[#FAF4EF] border border-[#9D583D]/20 shadow-sm mb-8 space-y-4">
                <div x-show="rsvpSuccessMsg" x-cloak class="p-3 rounded-xl bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs text-center font-medium">
                    <span x-text="rsvpSuccessMsg"></span>
                </div>
                <div x-show="rsvpErrorMsg" x-cloak class="p-3 rounded-xl bg-red-100 border border-red-300 text-red-800 text-xs text-center font-medium">
                    <span x-text="rsvpErrorMsg"></span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#1E1E1E] mb-1">Nama Lengkap</label>
                    <input type="text" x-model="rsvpForm.guest_name" required placeholder="Nama Anda"
                           class="w-full px-4 py-2.5 rounded-xl bg-white border border-[#9D583D]/20 text-[#1E1E1E] text-xs focus:ring-2 focus:ring-[#9D583D] focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#1E1E1E] mb-1">Konfirmasi Kehadiran</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl border text-xs font-medium cursor-pointer transition"
                               :class="rsvpForm.attendance === 'hadir' ? 'bg-[#9D583D] text-white border-[#9D583D]' : 'bg-white text-[#7A6658] border-stone-200'">
                            <input type="radio" value="hadir" x-model="rsvpForm.attendance" class="sr-only">
                            <span>✓ Akan Hadir</span>
                        </label>
                        <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl border text-xs font-medium cursor-pointer transition"
                               :class="rsvpForm.attendance === 'tidak_hadir' ? 'bg-[#9D583D] text-white border-[#9D583D]' : 'bg-white text-[#7A6658] border-stone-200'">
                            <input type="radio" value="tidak_hadir" x-model="rsvpForm.attendance" class="sr-only">
                            <span>✗ Berhalangan</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#1E1E1E] mb-1">Doa &amp; Ucapan</label>
                    <textarea x-model="rsvpForm.message" rows="3" required placeholder="Tuliskan doa restu untuk kedua mempelai..."
                              class="w-full px-4 py-2.5 rounded-xl bg-white border border-[#9D583D]/20 text-[#1E1E1E] text-xs focus:ring-2 focus:ring-[#9D583D] focus:outline-none"></textarea>
                </div>

                <button type="submit"
                        :disabled="rsvpSubmitting"
                        class="w-full min-h-[44px] py-3 rounded-xl font-sans-body font-semibold text-xs tracking-wider uppercase bg-[#9D583D] hover:bg-[#854730] text-white shadow-md active:scale-95 transition disabled:opacity-50 flex items-center justify-center gap-2">
                    <span x-show="!rsvpSubmitting">Kirimkan Ucapan</span>
                    <span x-show="rsvpSubmitting" x-cloak>Mengirim...</span>
                </button>
            </form>

            {{-- Daftar Doa & Ucapan Live --}}
            <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
                <template x-for="wish in wishes" :key="wish.id">
                    <div class="p-4 rounded-2xl bg-[#FAF4EF] border border-[#9D583D]/15 text-xs">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="font-bold text-[#1E1E1E] text-sm" x-text="wish.guest_name"></span>
                            <span :class="wish.attendance === 'hadir' ? 'bg-[#9D583D]/15 text-[#9D583D]' : 'bg-stone-200 text-stone-600'"
                                  class="px-2 py-0.5 rounded-full text-[10px] font-semibold"
                                  x-text="wish.attendance === 'hadir' ? 'Hadir' : 'Berhalangan'"></span>
                        </div>
                        <p class="text-[#7A6658] leading-relaxed" x-text="wish.message"></p>
                        <span class="block text-[10px] text-stone-400 mt-2" x-text="wish.time_ago"></span>
                    </div>
                </template>
                <div x-show="wishes.length === 0" class="text-center py-6 text-xs text-stone-400 italic">
                    Belum ada ucapan yang masuk. Jadilah yang pertama memberikan doa!
                </div>
            </div>
        </section>

        {{-- =========================================================
             9. WEDDING GIFT (#gift)
             ========================================================= --}}
        @if(!empty($gifts) && count($gifts) > 0)
        <section id="gift" class="px-6 py-14 bg-[#FAF4EF] border-t border-[#9D583D]/10 reveal">
            <div class="text-center mb-8 space-y-1">
                <span class="text-[10px] font-sans-jura tracking-[0.3em] uppercase text-[#9D583D] font-semibold">Tanda Kasih</span>
                <h2 class="font-serif-playfair text-3xl font-bold text-[#1E1E1E]">Wedding Gift</h2>
                <p class="text-xs text-[#7A6658] leading-relaxed max-w-xs mx-auto pt-1">
                    Tiada yang dapat kami ungkapkan selain rasa terimakasih dari hati yang tulus apabila Bapak/Ibu/Saudara/i berkenan memberikan tanda kasih:
                </p>
            </div>

            <div class="space-y-4">
                @foreach($gifts as $index => $acc)
                <div class="p-5 rounded-3xl bg-white border border-[#9D583D]/20 shadow-sm relative overflow-hidden">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold tracking-wider text-[#9D583D] uppercase font-sans-jura">{{ $acc['bank'] }}</span>
                        <svg class="w-5 h-5 text-[#9D583D]/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <div class="font-mono text-xl font-bold text-[#1E1E1E] tracking-widest my-1">
                        {{ $acc['account_number'] }}
                    </div>
                    <p class="text-xs text-[#7A6658] mb-4">a.n {{ $acc['account_name'] }}</p>

                    <button type="button"
                            @click="copyText('{{ $acc['account_number'] }}', {{ $index }})"
                            class="w-full min-h-[44px] px-4 py-2 rounded-xl text-xs font-semibold bg-[#FAF4EF] hover:bg-[#F2E5DC] text-[#9D583D] border border-[#9D583D]/30 transition active:scale-95 flex items-center justify-center gap-2">
                        <svg class="w-3.5 h-3.5 text-[#9D583D]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
             10. FOOTER
             ========================================================= --}}
        <footer class="px-6 py-12 text-center bg-white border-t border-[#9D583D]/10">
            <h3 class="font-serif-playfair text-2xl font-bold text-[#1E1E1E] mb-2">
                {{ $primaryName }} &amp; {{ $secondaryName }}
            </h3>
            <p class="text-xs text-[#7A6658] leading-relaxed max-w-xs mx-auto mb-6">
                Terima kasih atas segala doa restu dan kehadiran Anda yang melengkapi kebahagiaan kami.
            </p>
            <div class="border-t border-stone-100 pt-6 text-[10px] text-stone-400 font-sans-jura tracking-widest uppercase">
                <p>{{ $invitation->title }}</p>
                <p class="mt-1">Mehnikah Eco-Digital Invitation</p>
            </div>
        </footer>

    </main>

    {{-- Include Shared Invitation Engine --}}
    @include('themes.partials.engine')

</body>
</html>
