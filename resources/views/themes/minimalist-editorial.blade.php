<!DOCTYPE html>
<html lang="id" class="scroll-smooth h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $invitation->title }} | The Wedding of {{ $invitation->event_data['primary_name'] ?? '' }} &amp; {{ $invitation->event_data['secondary_name'] ?? '' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Cormorant Garamond & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS v4 & Alpine.js CDN -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }

        :root {
            --ub-bg: #FAF8F5;
            --ub-card: #FFFFFF;
            --ub-dark: #1A1512;
            --ub-mocha: #8C7355;
            --ub-mocha-light: #BCA385;
            --ub-muted: #6B7280;
            --ub-border: rgba(140, 115, 85, 0.18);
        }

        .font-serif-cormorant { font-family: 'Cormorant Garamond', Georgia, serif; }
        .font-sans-inter      { font-family: 'Inter', system-ui, sans-serif; }

        /* Motion Cover Opening Slide & Fade */
        #section-cover {
            transition: opacity 0.85s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 0.85s cubic-bezier(0.16, 1, 0.3, 1);
        }
        #section-cover.is-hidden {
            opacity: 0;
            transform: translateY(-100%);
            pointer-events: none;
            display: none !important;
        }

        /* Natural Scroll Reveal */
        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }
        .reveal.revealed {
            opacity: 1;
            transform: translateY(0);
        }

        /* Floating music toast notification */
        @keyframes toastFade {
            0%   { opacity: 0; transform: translateY(20px); }
            15%  { opacity: 1; transform: translateY(0); }
            85%  { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(-10px); }
        }
        .animate-toast {
            animation: toastFade 6s ease-in-out forwards;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #FAF8F5; }
        ::-webkit-scrollbar-thumb { background: #D6CBC1; border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: #8C7355; }
    </style>
</head>

@php
    $eventType = match ($invitation->category) {
        'pernikahan' => 'wedding',
        'tunangan'   => 'engagement',
        default      => $invitation->category
    };

    $primaryName     = $invitation->event_data['primary_name'] ?? '';
    $secondaryName   = $invitation->event_data['secondary_name'] ?? '';
    $primaryFullName = $invitation->event_data['primary_full_name'] ?? 'M. Yahya Ubaidillah, S.Kom, M.Kom';
    $secondaryFullName = $invitation->event_data['secondary_full_name'] ?? 'Khofifah Mamluatur Riskiyah, S.M';
    $s1              = $invitation->event_data['session_1'] ?? [];
    $s2              = $invitation->event_data['session_2'] ?? [];
    $gallery         = $invitation->event_data['gallery_images'] ?? [];
    $gifts           = $invitation->event_data['gift_accounts'] ?? [];
    $greeting        = $invitation->event_data['greeting'] ?? 'Dengan penuh kebahagiaan kami mengundang Anda untuk hadir di hari istimewa kami';
    $quote           = $invitation->event_data['quote'] ?? 'Dan di antara tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu isteri-isteri dari jenismu sendiri, supaya kamu cenderung dan merasa tenteram kepadanya, dan dijadikan-Nya diantaramu rasa kasih dan sayang. (Q.S. Ar-Rum: 21)';
    $coverImg        = $invitation->event_data['cover_image'] ?? '';
    $primaryParents  = $invitation->event_data['primary_title_parents'] ?? 'Putra dari Bapak Salamet & Ibu Raudatul Jannah';
    $secondaryParents= $invitation->event_data['secondary_title_parents'] ?? 'Putri dari Bapak Akh. Hidayat MZ & Ibu Sitti Juhairiyah';
    $primaryIg       = $invitation->event_data['primary_instagram'] ?? '';
    $secondaryIg     = $invitation->event_data['secondary_instagram'] ?? '';

    // Tanggal Acara
    $eventDateObj    = !empty($s1['date']) ? \Carbon\Carbon::parse($s1['date']) : now();
    $eventDateStr    = $eventDateObj->isoFormat('dddd, D MMMM Y');
    $eventDateShort  = $eventDateObj->format('d . m . Y');

    // Google Calendar Link
    $calDate  = str_replace('-', '', $s1['date'] ?? date('Y-m-d'));
    $calTimes = explode(' - ', $s1['time'] ?? '10:00 - 14:00');
    $calStart = str_pad(str_replace([':', ' ', 'WIB'], '', $calTimes[0] ?? '1000'), 4, '0');
    $calEnd   = isset($calTimes[1]) ? str_pad(str_replace([':', ' ', 'WIB'], '', $calTimes[1]), 4, '0') : '1400';
    $calUrl   = 'https://calendar.google.com/calendar/render?action=TEMPLATE'
        . '&text='     . urlencode($invitation->title)
        . '&dates='    . $calDate . 'T' . $calStart . '00Z/' . $calDate . 'T' . $calEnd . '00Z'
        . '&location=' . urlencode(($s1['venue'] ?? '') . ', ' . ($s1['address'] ?? ''));

    // Story Timeline Data
    $stories = !empty($invitation->event_data['rundown']) && is_array($invitation->event_data['rundown']) && count($invitation->event_data['rundown']) > 0
        ? $invitation->event_data['rundown']
        : [
            [
                'year'  => 'Maret 2024',
                'title' => 'First Meet',
                'desc'  => 'Pertama kali kami bertemu dan mulai mengenal satu sama lain di sebuah acara silaturahmi saat momen Lebaran yang hangat dan berkesan.',
                'photo' => 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=800&auto=format&fit=crop'
            ],
            [
                'year'  => 'Agustus 2024',
                'title' => 'Relationship',
                'desc'  => 'Seiring berjalannya waktu, rasa saling percaya tumbuh dan kami memutuskan untuk melangkah bersama dalam komitmen yang lebih dalam.',
                'photo' => 'https://images.unsplash.com/photo-1522673607200-164d1b6ce486?q=80&w=800&auto=format&fit=crop'
            ],
            [
                'year'  => 'April 2025',
                'title' => 'The Engagement',
                'desc'  => 'Di hadapan kedua keluarga besar tercinta, kami mengikat janji pertunangan untuk bersiap melangkah menuju kehidupan baru bersama.',
                'photo' => 'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=800&auto=format&fit=crop'
            ],
            [
                'year'  => 'Oktober 2026',
                'title' => 'The Wedding Day',
                'desc'  => 'Hari bahagia di mana dua insan dipersatukan dalam ikatan suci pernikahan seumur hidup, membangun keluarga yang sakinah dan penuh berkah.',
                'photo' => 'https://images.unsplash.com/photo-1465495976277-4387d4b0b4c6?q=80&w=800&auto=format&fit=crop'
            ]
        ];
@endphp

<body data-event-type="{{ $eventType }}"
      class="font-sans-inter bg-[#FAF8F5] text-[#1A1512] min-h-screen overflow-x-hidden antialiased selection:bg-[#8C7355] selection:text-white"
      x-data="{
          ...invitationEngine({
              musicMode: '{{ $invitation->event_data['music_mode'] ?? 'ambient' }}',
              musicUrl: '{{ !empty($invitation->event_data['music_file']) ? asset('storage/' . $invitation->event_data['music_file']) : '' }}',
              guestName: {{ json_encode($guestName ?? 'Kiki Al Gojo') }},
              targetDate: '{{ $targetDate ?? $eventDateObj->format('Y-m-d H:i:s') }}',
              initialWishes: {{ json_encode($wishesData ?? []) }},
              initialCounts: {{ json_encode($initialCounts ?? ['total' => 0, 'hadir' => 0, 'tidak_hadir' => 0]) }}
          }),
          showMusicToast: false,
          openWithToast() {
              this.openInvitation();
              this.showMusicToast = true;
              setTimeout(() => { this.showMusicToast = false; }, 6000);
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

    {{-- =========================================================
         1. COVER SCREEN TERKUNCI (#section-cover)
         Clean Editorial Minimalist Layout
         ========================================================= --}}
    <div id="section-cover"
         :class="isOpen ? 'is-hidden' : ''"
         class="fixed inset-0 z-50 h-dvh w-full overflow-hidden flex flex-col justify-between items-center text-center p-8 bg-[#FAF8F5] border-b border-[#8C7355]/20">

        {{-- Top Label --}}
        <div class="pt-4 space-y-1">
            <p class="font-sans-inter text-[11px] tracking-[0.3em] uppercase text-[#8C7355] font-semibold">
                The Wedding Of
            </p>
        </div>

        {{-- Center Content: Names & Guest Card --}}
        <div class="my-auto w-full max-w-sm flex flex-col items-center space-y-6">
            {{-- Big Names --}}
            <div class="space-y-1">
                <h1 class="font-serif-cormorant text-5xl sm:text-6xl font-bold tracking-tight text-[#1A1512] leading-tight">
                    {{ $primaryName }}
                    <span class="text-3xl sm:text-4xl text-[#8C7355] block my-0.5 font-normal font-serif-cormorant italic">&amp;</span>
                    {{ $secondaryName }}
                </h1>
                <p class="font-sans-inter text-xs tracking-[0.25em] text-[#8C7355] font-medium pt-2">
                    {{ $eventDateShort }}
                </p>
            </div>

            {{-- Guest Card Box --}}
            <div class="w-full max-w-[310px] p-6 rounded-3xl bg-white/90 border border-neutral-200/80 shadow-[0_10px_35px_rgba(0,0,0,0.04)] backdrop-blur-sm text-center space-y-2">
                <p class="text-[10px] uppercase tracking-widest text-neutral-400 font-medium">Kepada Yth.</p>
                <p class="text-[11px] text-neutral-500 font-normal">Bapak/Ibu/Saudara/i</p>
                <p class="font-serif-cormorant text-2xl font-bold text-[#1A1512] pt-1 truncate">
                    {{ !empty($guestName) ? $guestName : 'Kiki Al Gojo' }}
                </p>
                @if(!empty($guestAddress))
                    <p class="font-sans-inter text-xs text-neutral-500 truncate">
                        di {{ $guestAddress }}
                    </p>
                @endif
                <p class="text-[9px] text-neutral-400 italic pt-1 leading-relaxed">
                    Mohon maaf apabila ada kesalahan penulisan nama atau gelar
                </p>
            </div>
        </div>

        {{-- Open Invitation Button --}}
        <div class="pb-6 w-full max-w-[280px]">
            <button id="tombol-buka"
                    @click="openWithToast()"
                    type="button"
                    class="w-full min-h-[50px] px-8 py-3.5 rounded-full font-sans-inter font-medium text-xs tracking-widest uppercase bg-black hover:bg-neutral-800 text-white shadow-lg active:scale-95 transition-all duration-300 flex items-center justify-center gap-3">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>Open Invitation</span>
            </button>
        </div>
    </div>

    {{-- =========================================================
         FLOATING SONG NOTIFICATION TOAST
         ========================================================= --}}
    <div x-cloak x-show="showMusicToast"
         class="fixed bottom-24 right-6 z-[60] bg-black/85 text-white px-5 py-3 rounded-full backdrop-blur-md shadow-2xl text-xs font-sans-inter flex items-center gap-2 animate-toast pointer-events-none">
        <span class="animate-pulse">🎵</span>
        <span>Elvis Presley - Can't Help Falling in Love</span>
    </div>

    {{-- =========================================================
         MAIN INVITATION CONTENT
         ========================================================= --}}
    <main class="relative max-w-5xl mx-auto min-h-screen pb-32 overflow-x-hidden">

        {{-- =========================================================
             2. HERO SECTION (#hero)
             ========================================================= --}}
        <section id="hero" class="relative text-center px-6 pt-16 md:pt-24 pb-20 overflow-hidden reveal">
            {{-- Couple Photo Banner --}}
            <div class="w-full max-w-2xl mx-auto aspect-[16/10] sm:aspect-[16/9] rounded-3xl overflow-hidden shadow-2xl mb-12 border border-black/5 bg-neutral-100">
                @if(!empty($coverImg))
                    <img src="{{ asset('storage/' . $coverImg) }}" alt="{{ $invitation->title }}" class="w-full h-full object-cover">
                @else
                    <img src="https://images.unsplash.com/photo-1583939003579-730e3918a45a?q=80&w=1200&auto=format&fit=crop" alt="{{ $invitation->title }}" class="w-full h-full object-cover">
                @endif
            </div>

            <p class="font-sans-inter text-xs tracking-[0.3em] uppercase text-[#8C7355] font-semibold mb-3">
                The Wedding Of
            </p>
            <h1 class="font-serif-cormorant text-5xl md:text-7xl font-bold text-[#1A1512] tracking-tight leading-tight">
                {{ $primaryName }} &amp; {{ $secondaryName }}
            </h1>
            <p class="font-sans-inter text-sm text-neutral-500 font-medium mt-3 tracking-wider">
                {{ $eventDateStr }}
            </p>

            {{-- Minimalist Countdown Timer --}}
            <div class="mt-12 p-6 rounded-3xl bg-white border border-neutral-200/70 shadow-lg max-w-md mx-auto">
                <p class="text-[10px] font-sans-inter uppercase tracking-[0.25em] text-[#8C7355] font-semibold mb-4 text-center">
                    Counting Down to the Big Day
                </p>
                <div class="grid grid-cols-4 gap-3 text-center">
                    <div class="bg-[#FAF8F5] rounded-2xl p-3 border border-neutral-200/60">
                        <span class="block text-3xl font-bold font-serif-cormorant text-[#1A1512]" x-text="countdown.days">00</span>
                        <span class="text-[10px] uppercase text-neutral-400 font-sans-inter">Days</span>
                    </div>
                    <div class="bg-[#FAF8F5] rounded-2xl p-3 border border-neutral-200/60">
                        <span class="block text-3xl font-bold font-serif-cormorant text-[#1A1512]" x-text="countdown.hours">00</span>
                        <span class="text-[10px] uppercase text-neutral-400 font-sans-inter">Hours</span>
                    </div>
                    <div class="bg-[#FAF8F5] rounded-2xl p-3 border border-neutral-200/60">
                        <span class="block text-3xl font-bold font-serif-cormorant text-[#1A1512]" x-text="countdown.minutes">00</span>
                        <span class="text-[10px] uppercase text-neutral-400 font-sans-inter">Mins</span>
                    </div>
                    <div class="bg-[#FAF8F5] rounded-2xl p-3 border border-neutral-200/60">
                        <span class="block text-3xl font-bold font-serif-cormorant text-[#1A1512]" x-text="countdown.seconds">00</span>
                        <span class="text-[10px] uppercase text-neutral-400 font-sans-inter">Secs</span>
                    </div>
                </div>

                {{-- Add to Google Calendar Button --}}
                <div class="mt-6 text-center">
                    <a href="{{ $calUrl }}" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full text-xs font-semibold bg-neutral-900 hover:bg-neutral-800 text-white transition active:scale-95 shadow-sm">
                        <svg class="w-3.5 h-3.5 text-[#BCA385]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Save The Date</span>
                    </a>
                </div>
            </div>
        </section>

        {{-- =========================================================
             3. QUOTE SECTION
             ========================================================= --}}
        <section class="px-6 py-16 text-center reveal">
            <div class="max-w-xl mx-auto space-y-4">
                <span class="text-2xl text-[#8C7355] font-serif-cormorant italic font-bold">“</span>
                <p class="font-serif-cormorant text-lg md:text-xl italic text-neutral-700 leading-relaxed px-4">
                    {{ $quote }}
                </p>
                <div class="w-16 h-0.5 bg-[#8C7355]/30 mx-auto mt-4"></div>
            </div>
        </section>

        {{-- =========================================================
             4. COUPLE SECTION (#couple) — THE GROOM & THE BRIDE
             ========================================================= --}}
        <section id="couple" class="px-6 py-20 reveal">
            <div class="text-center mb-16 space-y-2">
                <span class="text-xs font-sans-inter tracking-[0.3em] uppercase text-[#8C7355] font-semibold">
                    The Happy Couple
                </span>
                <h2 class="font-serif-cormorant text-4xl md:text-5xl font-bold text-[#1A1512]">
                    Groom &amp; Bride
                </h2>
                <p class="text-xs text-neutral-500 max-w-md mx-auto leading-relaxed pt-1">
                    {{ $greeting }}
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-10 max-w-4xl mx-auto">
                {{-- The Groom --}}
                <div class="p-8 md:p-10 rounded-3xl bg-white border border-neutral-200/70 shadow-xl text-center space-y-4 hover:-translate-y-1.5 transition-all duration-300">
                    <div class="w-48 h-64 mx-auto rounded-2xl overflow-hidden shadow-md bg-neutral-100">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=800&auto=format&fit=crop" alt="{{ $primaryName }}" class="w-full h-full object-cover">
                    </div>
                    <div class="space-y-1">
                        <span class="text-[11px] font-sans-inter tracking-[0.25em] uppercase text-[#8C7355] font-semibold">The Groom</span>
                        <h3 class="font-serif-cormorant text-3xl font-bold text-[#1A1512]">{{ $primaryFullName }}</h3>
                        @if(!empty($primaryParents))
                            <p class="text-xs text-neutral-500 leading-relaxed px-4 pt-1">{{ $primaryParents }}</p>
                        @endif
                    </div>
                    @if(!empty($primaryIg))
                        <div class="pt-2">
                            <a href="https://instagram.com/{{ ltrim($primaryIg, '@') }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs text-neutral-700 bg-neutral-100 hover:bg-neutral-200 transition font-medium">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                <span>@ {{ ltrim($primaryIg, '@') }}</span>
                            </a>
                        </div>
                    @endif
                </div>

                {{-- The Bride --}}
                <div class="p-8 md:p-10 rounded-3xl bg-white border border-neutral-200/70 shadow-xl text-center space-y-4 hover:-translate-y-1.5 transition-all duration-300">
                    <div class="w-48 h-64 mx-auto rounded-2xl overflow-hidden shadow-md bg-neutral-100">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=800&auto=format&fit=crop" alt="{{ $secondaryName }}" class="w-full h-full object-cover">
                    </div>
                    <div class="space-y-1">
                        <span class="text-[11px] font-sans-inter tracking-[0.25em] uppercase text-[#8C7355] font-semibold">The Bride</span>
                        <h3 class="font-serif-cormorant text-3xl font-bold text-[#1A1512]">{{ $secondaryFullName }}</h3>
                        @if(!empty($secondaryParents))
                            <p class="text-xs text-neutral-500 leading-relaxed px-4 pt-1">{{ $secondaryParents }}</p>
                        @endif
                    </div>
                    @if(!empty($secondaryIg))
                        <div class="pt-2">
                            <a href="https://instagram.com/{{ ltrim($secondaryIg, '@') }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs text-neutral-700 bg-neutral-100 hover:bg-neutral-200 transition font-medium">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                <span>@ {{ ltrim($secondaryIg, '@') }}</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        {{-- =========================================================
             5. WEDDING DAY SECTION (#event)
             ========================================================= --}}
        <section id="event" class="px-6 py-20 reveal">
            <div class="text-center mb-16 space-y-2">
                <span class="text-xs font-sans-inter tracking-[0.3em] uppercase text-[#8C7355] font-semibold">
                    The Schedule
                </span>
                <h2 class="font-serif-cormorant text-4xl md:text-5xl font-bold text-[#1A1512]">
                    The Wedding Day
                </h2>
                <p class="text-xs text-neutral-500 max-w-md mx-auto leading-relaxed pt-1">
                    Dengan penuh kebahagiaan kami mengundang Anda untuk hadir di hari istimewa kami
                </p>
            </div>

            <div class="flex justify-center gap-8 flex-wrap max-w-4xl mx-auto">
                {{-- Card Akad Nikah --}}
                <div class="w-full md:w-[400px] bg-white rounded-3xl p-8 md:p-10 shadow-xl border border-neutral-200/70 text-center space-y-6 hover:-translate-y-1.5 transition-all duration-300">
                    <span class="inline-block px-3.5 py-1 rounded-full text-[10px] font-sans-inter tracking-widest uppercase bg-neutral-100 text-[#8C7355] font-semibold">
                        Akad
                    </span>
                    <h3 class="font-serif-cormorant text-3xl font-bold text-[#1A1512]">
                        {{ $s1['title'] ?? 'Akad Nikah' }}
                    </h3>

                    <div class="space-y-4 text-xs text-neutral-600">
                        <p class="flex items-center justify-center gap-2.5 font-medium">
                            <svg class="w-4 h-4 text-[#8C7355]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>{{ \Carbon\Carbon::parse($s1['date'] ?? now())->isoFormat('dddd, D MMMM Y') }}</span>
                        </p>
                        <p class="flex items-center justify-center gap-2.5 font-medium">
                            <svg class="w-4 h-4 text-[#8C7355]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ $s1['time'] ?? '10.00 WIB' }}</span>
                        </p>
                        <div class="pt-2">
                            <p class="font-bold text-[#1A1512]">{{ $s1['venue'] ?? 'Rumah Mempelai Wanita' }}</p>
                            <p class="text-neutral-500 mt-1 leading-relaxed">{{ $s1['address'] ?? 'Sumenep, Madura, Jawa Timur' }}</p>
                        </div>
                    </div>

                    @if(!empty($s1['maps_url']))
                        <div class="pt-4 border-t border-neutral-100">
                            <a href="{{ $s1['maps_url'] }}" target="_blank" rel="noopener noreferrer"
                               class="inline-block rounded-full px-8 py-3 bg-black hover:bg-neutral-800 text-white text-xs font-semibold tracking-wider uppercase hover:scale-105 transition active:scale-95 shadow-md">
                                Open Maps
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Card Resepsi --}}
                @if(!empty($s2['title']) || !empty($s2['enabled']))
                <div class="w-full md:w-[400px] bg-white rounded-3xl p-8 md:p-10 shadow-xl border border-neutral-200/70 text-center space-y-6 hover:-translate-y-1.5 transition-all duration-300">
                    <span class="inline-block px-3.5 py-1 rounded-full text-[10px] font-sans-inter tracking-widest uppercase bg-neutral-100 text-[#8C7355] font-semibold">
                        Resepsi
                    </span>
                    <h3 class="font-serif-cormorant text-3xl font-bold text-[#1A1512]">
                        {{ $s2['title'] ?? 'Resepsi Nikah' }}
                    </h3>

                    <div class="space-y-4 text-xs text-neutral-600">
                        <p class="flex items-center justify-center gap-2.5 font-medium">
                            <svg class="w-4 h-4 text-[#8C7355]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>{{ \Carbon\Carbon::parse($s2['date'] ?? now())->isoFormat('dddd, D MMMM Y') }}</span>
                        </p>
                        <p class="flex items-center justify-center gap-2.5 font-medium">
                            <svg class="w-4 h-4 text-[#8C7355]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ $s2['time'] ?? '13.00 - Selesai' }}</span>
                        </p>
                        <div class="pt-2">
                            <p class="font-bold text-[#1A1512]">{{ $s2['venue'] ?? 'Rumah Mempelai Wanita' }}</p>
                            <p class="text-neutral-500 mt-1 leading-relaxed">{{ $s2['address'] ?? 'Sumenep, Madura, Jawa Timur' }}</p>
                        </div>
                    </div>

                    @if(!empty($s2['maps_url']))
                        <div class="pt-4 border-t border-neutral-100">
                            <a href="{{ $s2['maps_url'] }}" target="_blank" rel="noopener noreferrer"
                               class="inline-block rounded-full px-8 py-3 bg-black hover:bg-neutral-800 text-white text-xs font-semibold tracking-wider uppercase hover:scale-105 transition active:scale-95 shadow-md">
                                Open Maps
                            </a>
                        </div>
                    @endif
                </div>
                @endif
            </div>
        </section>

        {{-- =========================================================
             6. OUR STORY SECTION (#story)
             FITUR KHUSUS: DILENGKAPI FOTO & TANGGAL SESUAI REQUEST
             ========================================================= --}}
        <section id="story" class="px-6 py-24 reveal">
            <div class="text-center mb-20 space-y-2">
                <span class="text-xs font-sans-inter tracking-[0.3em] uppercase text-[#8C7355] font-semibold">
                    The Journey
                </span>
                <h2 class="font-serif-cormorant text-4xl md:text-5xl font-bold text-[#1A1512]">
                    Our Story
                </h2>
                <p class="text-xs text-neutral-500 max-w-md mx-auto leading-relaxed pt-1">
                    Setiap momen indah dan tonggak sejarah yang mengantarkan kami menuju ikatan suci
                </p>
            </div>

            {{-- Alternating Modern Timeline with Central Line --}}
            <div class="relative max-w-4xl mx-auto">
                {{-- Central Connecting Line --}}
                <div class="absolute left-4 md:left-1/2 top-0 bottom-0 w-[2px] bg-neutral-300 md:-translate-x-1/2"></div>

                <div class="space-y-16">
                    @foreach($stories as $index => $item)
                    @php $isEven = ($index % 2 === 0); @endphp
                    <div class="relative flex flex-col md:flex-row items-start md:items-center {{ $isEven ? 'md:justify-start' : 'md:justify-end' }}">
                        {{-- Timeline Milestone Point Dot --}}
                        <div class="absolute left-[9px] md:left-1/2 md:-translate-x-1/2 top-6 md:top-1/2 md:-translate-y-1/2 w-4 h-4 rounded-full bg-neutral-800 border-4 border-[#FAF8F5] z-10"></div>

                        {{-- Card Content --}}
                        <div class="ml-10 md:ml-0 md:w-[44%] bg-white rounded-3xl p-6 md:p-8 shadow-xl border border-neutral-200/70 hover:-translate-y-1.5 transition-all duration-300 space-y-4">
                            {{-- Foto Milestone (User Request) --}}
                            @if(!empty($item['photo']))
                                <div class="w-full h-48 md:h-52 rounded-2xl overflow-hidden bg-neutral-100 shadow-inner">
                                    <img src="{{ $item['photo'] }}" alt="{{ $item['title'] ?? 'Story Photo' }}" class="w-full h-full object-cover hover:scale-105 transition duration-500">
                                </div>
                            @endif

                            {{-- Tanggal / Tahun --}}
                            <div class="flex items-center justify-between">
                                <h3 class="font-serif-cormorant text-2xl md:text-3xl font-bold text-[#1A1512]">
                                    {{ $item['year'] ?? ($item['date'] ?? 'Our Milestone') }}
                                </h3>
                                <span class="px-3 py-1 rounded-full text-[10px] font-sans-inter font-semibold uppercase bg-neutral-100 text-[#8C7355]">
                                    Chapter {{ $index + 1 }}
                                </span>
                            </div>

                            {{-- Judul Cerita --}}
                            <h4 class="font-sans-inter text-base md:text-lg font-semibold text-[#1A1512]">
                                {{ $item['title'] ?? 'Our Journey' }}
                            </h4>

                            {{-- Deskripsi Cerita --}}
                            <p class="text-xs text-neutral-600 leading-relaxed">
                                {{ $item['desc'] ?? ($item['description'] ?? '') }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- =========================================================
             7. GALLERY SECTION (#gallery)
             ========================================================= --}}
        @if(!empty($gallery) && count($gallery) > 0)
        <section id="gallery" class="px-6 py-20 bg-white rounded-3xl my-10 shadow-sm reveal">
            <div class="text-center mb-16 space-y-2">
                <span class="text-xs font-sans-inter tracking-[0.3em] uppercase text-[#8C7355] font-semibold">
                    Memories
                </span>
                <h2 class="font-serif-cormorant text-4xl md:text-5xl font-bold text-[#1A1512]">
                    Our Gallery
                </h2>
                <p class="text-xs text-neutral-500 max-w-md mx-auto leading-relaxed pt-1">
                    Momen indah perjalanan kami dalam bingkai kenangan
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 max-w-4xl mx-auto">
                @foreach($gallery as $photo)
                    <div class="aspect-square rounded-2xl overflow-hidden bg-neutral-100 shadow-md cursor-pointer group"
                         @click="openLightbox('{{ asset('storage/' . $photo) }}')">
                        <img src="{{ asset('storage/' . $photo) }}" alt="Foto Galeri" class="w-full h-full object-cover group-hover:scale-108 transition duration-700">
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- =========================================================
             8. WEDDING WISHES & RSVP (#wishes)
             ========================================================= --}}
        <section id="wishes" class="px-6 py-20 reveal">
            <div class="text-center mb-12 space-y-2">
                <span class="text-xs font-sans-inter tracking-[0.3em] uppercase text-[#8C7355] font-semibold">
                    Guest Book
                </span>
                <h2 class="font-serif-cormorant text-4xl md:text-5xl font-bold text-[#1A1512]">
                    Wedding Wishes
                </h2>
                <p class="text-xs text-neutral-500 max-w-md mx-auto leading-relaxed pt-1">
                    Doa dan ucapan dari keluarga serta sahabat tercinta
                </p>
                <div class="flex items-center justify-center gap-3 pt-3 text-xs">
                    <span class="px-3.5 py-1 rounded-full bg-neutral-900 text-white font-medium">
                        <strong x-text="counts.hadir">0</strong> Hadir
                    </span>
                    <span class="px-3.5 py-1 rounded-full bg-neutral-200 text-neutral-700 font-medium">
                        <strong x-text="counts.total">0</strong> Total Pesan
                    </span>
                </div>
            </div>

            <div class="max-w-2xl mx-auto space-y-8">
                {{-- Form RSVP --}}
                <form @submit.prevent="submitRsvp('{{ route('invitation.wish', $invitation->slug) }}')" class="p-8 rounded-3xl bg-white border border-neutral-200/70 shadow-xl space-y-5">
                    <div x-show="rsvpSuccessMsg" x-cloak class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs text-center font-medium">
                        <span x-text="rsvpSuccessMsg"></span>
                    </div>
                    <div x-show="rsvpErrorMsg" x-cloak class="p-3.5 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs text-center font-medium">
                        <span x-text="rsvpErrorMsg"></span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-neutral-700 mb-1.5">Nama Lengkap</label>
                        <input type="text" x-model="rsvpForm.guest_name" required placeholder="Nama Anda"
                               class="w-full px-4 py-3 rounded-2xl bg-[#FAF8F5] border border-neutral-200 text-neutral-800 text-xs focus:ring-2 focus:ring-black focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-neutral-700 mb-1.5">Konfirmasi Kehadiran</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center justify-center gap-2 p-3 rounded-2xl border text-xs font-medium cursor-pointer transition"
                                   :class="rsvpForm.attendance === 'hadir' ? 'bg-black text-white border-black' : 'bg-[#FAF8F5] text-neutral-600 border-neutral-200'">
                                <input type="radio" value="hadir" x-model="rsvpForm.attendance" class="sr-only">
                                <span>✓ Hadir</span>
                            </label>
                            <label class="flex items-center justify-center gap-2 p-3 rounded-2xl border text-xs font-medium cursor-pointer transition"
                                   :class="rsvpForm.attendance === 'tidak_hadir' ? 'bg-black text-white border-black' : 'bg-[#FAF8F5] text-neutral-600 border-neutral-200'">
                                <input type="radio" value="tidak_hadir" x-model="rsvpForm.attendance" class="sr-only">
                                <span>✗ Berhalangan</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-neutral-700 mb-1.5">Doa &amp; Ucapan</label>
                        <textarea x-model="rsvpForm.message" rows="3" required placeholder="Tuliskan doa restu untuk kedua mempelai..."
                                  class="w-full px-4 py-3 rounded-2xl bg-[#FAF8F5] border border-neutral-200 text-neutral-800 text-xs focus:ring-2 focus:ring-black focus:outline-none"></textarea>
                    </div>

                    <button type="submit"
                            :disabled="rsvpSubmitting"
                            class="w-full min-h-[48px] py-3.5 rounded-full font-sans-inter font-semibold text-xs tracking-wider uppercase bg-black hover:bg-neutral-800 text-white active:scale-95 transition disabled:opacity-50 flex items-center justify-center gap-2 shadow-md">
                        <span x-show="!rsvpSubmitting">Kirim Ucapan</span>
                        <span x-show="rsvpSubmitting" x-cloak>Mengirim...</span>
                    </button>
                </form>

                {{-- Live Wishes Stream --}}
                <div class="space-y-4 max-h-[420px] overflow-y-auto pr-1">
                    <template x-for="wish in wishes" :key="wish.id">
                        <div class="p-5 rounded-2xl bg-white border border-neutral-200/60 shadow-sm text-xs space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-neutral-900 text-sm" x-text="wish.guest_name"></span>
                                <span :class="wish.attendance === 'hadir' ? 'bg-black text-white' : 'bg-neutral-200 text-neutral-600'"
                                      class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold"
                                      x-text="wish.attendance === 'hadir' ? 'Hadir' : 'Berhalangan'"></span>
                            </div>
                            <p class="text-neutral-600 leading-relaxed" x-text="wish.message"></p>
                            <span class="block text-[10px] text-neutral-400 pt-1" x-text="wish.time_ago"></span>
                        </div>
                    </template>
                    <div x-show="wishes.length === 0" class="text-center py-8 text-xs text-neutral-400 italic">
                        Belum ada ucapan. Jadilah yang pertama memberikan doa.
                    </div>
                </div>
            </div>
        </section>

        {{-- =========================================================
             9. WEDDING GIFT (#gift)
             ========================================================= --}}
        @if(!empty($gifts) && count($gifts) > 0)
        <section id="gift" class="px-6 py-20 reveal">
            <div class="text-center mb-12 space-y-2">
                <span class="text-xs font-sans-inter tracking-[0.3em] uppercase text-[#8C7355] font-semibold">
                    Wedding Gift
                </span>
                <h2 class="font-serif-cormorant text-4xl md:text-5xl font-bold text-[#1A1512]">
                    Tanda Kasih
                </h2>
                <p class="text-xs text-neutral-500 max-w-md mx-auto leading-relaxed pt-1">
                    Doa restu Anda merupakan karunia terindah bagi kami. Namun apabila Anda ingin memberikan tanda kasih secara digital, Anda dapat melalui:
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-6 max-w-2xl mx-auto">
                @foreach($gifts as $index => $acc)
                <div class="p-6 rounded-3xl bg-white border border-neutral-200/70 shadow-lg text-center space-y-3">
                    <span class="text-xs font-bold tracking-wider text-[#8C7355] uppercase block">{{ $acc['bank'] }}</span>
                    <div class="font-mono text-xl font-bold text-[#1A1512] tracking-widest">
                        {{ $acc['account_number'] }}
                    </div>
                    <p class="text-xs text-neutral-500">a.n {{ $acc['account_name'] }}</p>

                    <button type="button"
                            @click="copyText('{{ $acc['account_number'] }}', {{ $index }})"
                            class="w-full min-h-[44px] px-4 py-2.5 rounded-full text-xs font-semibold bg-neutral-100 hover:bg-neutral-200 text-neutral-800 transition active:scale-95 flex items-center justify-center gap-2">
                        <svg class="w-3.5 h-3.5 text-neutral-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                        </svg>
                        <span x-text="copiedIndex === {{ $index }} ? '✓ Berhasil Tersalin' : 'Copy Rekening'">Copy Rekening</span>
                    </button>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- =========================================================
             10. FOOTER SECTION (#footer)
             ========================================================= --}}
        <footer class="px-6 py-20 text-center relative overflow-hidden bg-neutral-900 text-white rounded-3xl mt-12 mx-4 md:mx-0 shadow-2xl reveal">
            <div class="max-w-md mx-auto space-y-4">
                <span class="text-xs font-sans-inter tracking-[0.3em] uppercase text-[#BCA385] font-semibold block">
                    Thank You
                </span>
                <h3 class="font-serif-cormorant text-4xl md:text-5xl font-bold text-white">
                    {{ $primaryName }} &amp; {{ $secondaryName }}
                </h3>
                <p class="text-xs text-neutral-400 leading-relaxed px-4 pt-1">
                    Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu.
                </p>
                <div class="pt-8 border-t border-neutral-800 text-[10px] text-neutral-500 font-sans-inter tracking-widest uppercase">
                    <p>{{ $invitation->title }}</p>
                    <p class="mt-1">Modern Editorial Wedding Invitation</p>
                </div>
            </div>
        </footer>

    </main>

    {{-- Include Shared Invitation Engine --}}
    @include('themes.partials.engine')

</body>
</html>
