<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $invitation->title }} | Undangan Adat Sunda</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Playfair Display & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS v4 & Alpine.js CDN -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .font-sunda-title { font-family: 'Playfair Display', Georgia, serif; }
        .font-sunda-body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
    </style>
</head>
@php $eventType = match ($invitation->category) { 'pernikahan' => 'wedding', 'tunangan' => 'engagement', default => $invitation->category }; @endphp
<body data-event-type="{{ $eventType }}" class="font-sunda-body bg-[#112D22] text-[#F8F9FA] overflow-x-hidden overflow-hidden h-screen antialiased selection:bg-[#C59B27] selection:text-[#112D22]"
      x-data="invitationEngine({
          musicMode: '{{ $invitation->event_data['music_mode'] ?? 'ambient' }}',
          musicUrl: '{{ !empty($invitation->event_data['music_file']) ? asset('storage/' . $invitation->event_data['music_file']) : '' }}',
          guestName: {{ json_encode($guestName ?? '') }},
          targetDate: '{{ $targetDate ?? '' }}',
          initialWishes: {{ json_encode($wishesData ?? []) }},
          initialCounts: {{ json_encode($initialCounts ?? ['total' => 0, 'hadir' => 0, 'tidak_hadir' => 0]) }}
      })">

    {{-- ==================== 1. COVER SCREEN TERKUNCI ==================== --}}
    <div id="cover-screen" data-cover-gate
         :class="isOpen ? 'is-opening' : ''"
         class="motion-cover fixed inset-0 z-50 h-dvh w-full overflow-hidden flex flex-col justify-between items-center text-center p-6 bg-gradient-to-b from-[#112D22] to-[#0A1D16] border-b border-[#C59B27]/30">
        
        <!-- Ornamen Parahyangan Atas -->
        <div class="pt-6 flex flex-col items-center">
            <svg class="w-8 h-8 text-[#C59B27]" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
            </svg>
            <span class="mt-2 text-[11px] tracking-[0.25em] uppercase text-[#C59B27] font-semibold">
                Serat Uleman
            </span>
        </div>

        <!-- Judul & Nama Mempelai -->
        <div class="max-w-md w-full my-auto space-y-4">
            <p class="text-xs tracking-[0.2em] uppercase text-[#D2C5A5]">{{ $invitation->title }}</p>
            <h1 class="font-sunda-title text-4xl sm:text-5xl font-bold tracking-tight text-[#F8F9FA] leading-tight">
                {{ $invitation->event_data['primary_name'] ?? 'Mempelai 1' }}
                @if(!empty($invitation->event_data['secondary_name']))
                    <span class="block text-2xl text-[#C59B27] font-normal my-1">&amp;</span>
                    {{ $invitation->event_data['secondary_name'] }}
                @endif
            </h1>

            <!-- Kotak Sapaan Personal Tamu (?to=) -->
            <div class="mt-6 p-4 rounded-2xl bg-[#1B4332]/80 border border-[#C59B27]/40 shadow-inner max-w-xs mx-auto backdrop-blur-sm">
                <p class="text-xs text-[#B7C7B0]">Kairing Hormat Bapak/Ibu/Saderek:</p>
                <p class="text-base font-bold text-[#C59B27] mt-1 truncate">
                    {{ !empty($guestName) ? $guestName : 'Tamu Undangan' }}
                </p>
                @if(!empty($guestAddress))
                    <p class="text-xs text-[#D2C5A5] mt-1 truncate">
                        di {{ $guestAddress }}
                    </p>
                @endif
            </div>
        </div>

        <!-- Tombol Buka Undangan -->
        <div class="pb-8 w-full max-w-xs">
            <button @click="openInvitation()" 
                    type="button"
                    class="w-full min-h-[48px] px-6 py-3 rounded-full font-semibold text-xs tracking-wider uppercase bg-gradient-to-r from-[#C59B27] to-[#A07914] text-[#112D22] shadow-lg shadow-[#C59B27]/20 hover:from-[#DFC05A] hover:to-[#C59B27] active:scale-95 transition-all flex items-center justify-center gap-2.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>Buka Serat Uleman</span>
            </button>
        </div>
    </div>

    {{-- ==================== MAIN CONTENT UNDANGAN ==================== --}}
    <main class="relative max-w-lg mx-auto bg-[#15382B] border-x border-[#C59B27]/20 shadow-2xl min-h-screen pb-24">

        {{-- 2. HERO SECTION --}}
        <section id="hero" class="relative text-center px-6 pt-12 pb-16 overflow-hidden">
            <span class="inline-block px-3 py-1 rounded-full text-[10px] tracking-widest uppercase font-bold text-[#C59B27] bg-[#C59B27]/10 border border-[#C59B27]/30 mb-4">
                Wilujeng Sumping
            </span>

            @if(!empty($invitation->event_data['cover_image']))
                <div class="w-48 h-48 sm:w-56 sm:h-56 mx-auto rounded-full overflow-hidden p-1.5 bg-gradient-to-tr from-[#C59B27] via-[#F8F9FA] to-[#1B4332] shadow-2xl mb-6">
                    <img src="{{ asset('storage/' . $invitation->event_data['cover_image']) }}" alt="{{ $invitation->title }}" class="invitation-photo-oval w-full h-full object-cover rounded-full">
                </div>
            @endif

            <p class="text-xs uppercase tracking-[0.2em] text-[#C59B27] mb-2 font-semibold">{{ $invitation->title }}</p>
            <h1 class="font-sunda-title text-3xl sm:text-4xl font-bold text-[#F8F9FA] mb-3">
                {{ $invitation->event_data['primary_name'] }}
                @if(!empty($invitation->event_data['secondary_name']))
                    <span class="text-[#C59B27] text-2xl block">&amp;</span>
                    {{ $invitation->event_data['secondary_name'] }}
                @endif
            </h1>

            <p class="text-sm text-[#D2C5A5] mt-3 font-medium">
                {{ \Carbon\Carbon::parse($invitation->event_data['session_1']['date'] ?? now())->isoFormat('dddd, D MMMM Y') }}
            </p>

            <!-- Countdown Timer -->
            <div class="mt-8 p-4 rounded-2xl bg-[#112D22]/80 border border-[#C59B27]/30 max-w-xs mx-auto">
                <p class="text-xs uppercase tracking-wider text-[#C59B27] mb-3 font-semibold">Ngetang Dinten</p>
                <div class="grid grid-cols-4 gap-2 text-center">
                    <div class="bg-[#0A1D16] rounded-xl p-2 border border-[#C59B27]/20">
                        <span class="block text-2xl font-bold text-[#C59B27] font-mono" x-text="countdown.days">00</span>
                        <span class="text-[10px] uppercase text-[#B7C7B0]">Dinten</span>
                    </div>
                    <div class="bg-[#0A1D16] rounded-xl p-2 border border-[#C59B27]/20">
                        <span class="block text-2xl font-bold text-[#C59B27] font-mono" x-text="countdown.hours">00</span>
                        <span class="text-[10px] uppercase text-[#B7C7B0]">Jam</span>
                    </div>
                    <div class="bg-[#0A1D16] rounded-xl p-2 border border-[#C59B27]/20">
                        <span class="block text-2xl font-bold text-[#C59B27] font-mono" x-text="countdown.minutes">00</span>
                        <span class="text-[10px] uppercase text-[#B7C7B0]">Menit</span>
                    </div>
                    <div class="bg-[#0A1D16] rounded-xl p-2 border border-[#C59B27]/20">
                        <span class="block text-2xl font-bold text-[#C59B27] font-mono" x-text="countdown.seconds">00</span>
                        <span class="text-[10px] uppercase text-[#B7C7B0]">Detik</span>
                    </div>
                </div>
            </div>

            <!-- Simpan ke Google Calendar -->
            @php
                $s1 = $invitation->event_data['session_1'] ?? [];
                $calDate = str_replace('-', '', $s1['date'] ?? date('Y-m-d'));
                $calTimes = explode(' - ', $s1['time'] ?? '09:00 - 12:00');
                $startH = str_replace(':', '', $calTimes[0] ?? '0900');
                $endH = isset($calTimes[1]) ? str_replace(':', '', $calTimes[1]) : '1200';
                $calUrl = 'https://calendar.google.com/calendar/render?action=TEMPLATE'
                    . '&text=' . urlencode($invitation->title)
                    . '&dates=' . $calDate . 'T' . str_pad($startH, 4, '0') . '00Z/' . $calDate . 'T' . str_pad($endH, 4, '0') . '00Z'
                    . '&details=' . urlencode($invitation->event_data['quote'] ?? $invitation->title)
                    . '&location=' . urlencode(($s1['venue'] ?? '') . ', ' . ($s1['address'] ?? ''));
            @endphp
            <div class="mt-5">
                <a href="{{ $calUrl }}" target="_blank" rel="noopener noreferrer" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full text-xs font-semibold bg-[#112D22] hover:bg-[#1B4332] text-[#F8F9FA] border border-[#C59B27]/30 transition active:scale-95">
                    <svg class="w-4 h-4 text-[#C59B27]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Simpen dina Kalender</span>
                </a>
            </div>
        </section>

        {{-- 3. PROFIL SECTION --}}
        <section id="profil" class="px-6 py-12 border-t border-[#C59B27]/20 reveal-on-scroll">
            <div class="text-center max-w-sm mx-auto mb-8">
                <p class="text-sm font-sunda-title italic text-[#C59B27] mb-2">
                    {{ $invitation->event_data['greeting'] ?? 'Bismillahirrohmanirrohim' }}
                </p>
                <blockquote class="text-xs text-[#D2C5A5] leading-relaxed italic border-y border-[#C59B27]/30 py-3 my-4">
                    "{{ $invitation->event_data['quote'] ?? 'Maha Suci Allah anu parantos nyiptakeun mahluk-Na sapasang-pasang. Mugia ieu pernikahan janten jalan kabagjaan tur karidoan ti Gusti Nu Maha Suci.' }}"
                </blockquote>
            </div>

            <div class="space-y-6">
                <div class="p-5 rounded-2xl bg-[#112D22]/60 border border-[#C59B27]/30 text-center">
                    <h3 class="font-sunda-title text-2xl font-bold text-[#F8F9FA]">{{ $invitation->event_data['primary_name'] }}</h3>
                    @if(!empty($invitation->event_data['primary_title_parents']))
                        <p class="text-xs text-[#B7C7B0] mt-1 leading-relaxed">{{ $invitation->event_data['primary_title_parents'] }}</p>
                    @endif
                    @if(!empty($invitation->event_data['primary_instagram']))
                        <a href="https://instagram.com/{{ ltrim($invitation->event_data['primary_instagram'], '@') }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1.5 mt-3 text-xs text-[#C59B27] hover:underline">
                            <span>@ {{ ltrim($invitation->event_data['primary_instagram'], '@') }}</span>
                        </a>
                    @endif
                </div>

                @if(!empty($invitation->event_data['secondary_name']))
                <div class="text-center text-[#C59B27] font-sunda-title text-xl font-bold">&amp;</div>
                <div class="p-5 rounded-2xl bg-[#112D22]/60 border border-[#C59B27]/30 text-center">
                    <h3 class="font-sunda-title text-2xl font-bold text-[#F8F9FA]">{{ $invitation->event_data['secondary_name'] }}</h3>
                    @if(!empty($invitation->event_data['secondary_title_parents']))
                        <p class="text-xs text-[#B7C7B0] mt-1 leading-relaxed">{{ $invitation->event_data['secondary_title_parents'] }}</p>
                    @endif
                    @if(!empty($invitation->event_data['secondary_instagram']))
                        <a href="https://instagram.com/{{ ltrim($invitation->event_data['secondary_instagram'], '@') }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1.5 mt-3 text-xs text-[#C59B27] hover:underline">
                            <span>@ {{ ltrim($invitation->event_data['secondary_instagram'], '@') }}</span>
                        </a>
                    @endif
                </div>
                @endif
            </div>
        </section>

        {{-- 4. ACARA --}}
        <section id="acara" class="px-6 py-12 border-t border-[#C59B27]/20 reveal-on-scroll">
            <div class="text-center mb-8">
                <span class="text-xs uppercase tracking-[0.2em] text-[#C59B27] font-bold">Runtuyan Acara</span>
                <h2 class="font-sunda-title text-2xl sm:text-3xl font-bold text-[#F8F9FA] mt-1">Akad &amp; Walimatul Ursy</h2>
            </div>

            <div class="space-y-6">
                <!-- Sesi 1 -->
                @php $s1 = $invitation->event_data['session_1'] ?? []; @endphp
                <div class="p-6 rounded-2xl bg-[#112D22]/70 border border-[#C59B27]/40 relative overflow-hidden">
                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold tracking-wider bg-[#C59B27]/20 text-[#C59B27] mb-3">
                        AKAD NIKAH / SESI UTAMA
                    </span>
                    <h3 class="font-sunda-title text-xl font-bold text-[#F8F9FA] mb-2">{{ $s1['title'] ?? 'Akad Nikah' }}</h3>
                    <div class="space-y-1.5 text-xs text-[#D2C5A5]">
                        <p class="flex items-center gap-2">
                            <span class="text-[#C59B27]">✦</span>
                            <span>{{ \Carbon\Carbon::parse($s1['date'] ?? now())->isoFormat('dddd, D MMMM Y') }}</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <span class="text-[#C59B27]">✦</span>
                            <span>{{ $s1['time'] ?? 'Tabuh 09.00 WIB' }}</span>
                        </p>
                        <p class="flex items-start gap-2 pt-1">
                            <span class="text-[#C59B27] mt-0.5">✦</span>
                            <span><strong class="text-[#F8F9FA]">{{ $s1['venue'] ?? '' }}</strong><br>{{ $s1['address'] ?? '' }}</span>
                        </p>
                    </div>

                    @if(!empty($s1['maps_url']))
                    <div class="mt-4 pt-4 border-t border-[#C59B27]/20">
                        <a href="{{ $s1['maps_url'] }}" target="_blank" rel="noopener noreferrer"
                           class="w-full min-h-[44px] inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold bg-[#C59B27]/15 hover:bg-[#C59B27]/25 text-[#C59B27] border border-[#C59B27]/30 transition">
                            <span>Pituduh Lokasi (Google Maps)</span>
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Sesi 2 -->
                @php $s2 = $invitation->event_data['session_2'] ?? []; @endphp
                @if(!empty($s2['enabled']) || !empty($s2['title']))
                <div class="p-6 rounded-2xl bg-[#112D22]/70 border border-[#C59B27]/40 relative overflow-hidden">
                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold tracking-wider bg-[#F8F9FA]/10 text-[#F8F9FA] mb-3">
                        WALIMATUL URSY / RESEPSI
                    </span>
                    <h3 class="font-sunda-title text-xl font-bold text-[#F8F9FA] mb-2">{{ $s2['title'] ?? 'Resepsi' }}</h3>
                    <div class="space-y-1.5 text-xs text-[#D2C5A5]">
                        <p class="flex items-center gap-2">
                            <span class="text-[#C59B27]">✦</span>
                            <span>{{ \Carbon\Carbon::parse($s2['date'] ?? now())->isoFormat('dddd, D MMMM Y') }}</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <span class="text-[#C59B27]">✦</span>
                            <span>{{ $s2['time'] ?? 'Tabuh 11.00 WIB' }}</span>
                        </p>
                        <p class="flex items-start gap-2 pt-1">
                            <span class="text-[#C59B27] mt-0.5">✦</span>
                            <span><strong class="text-[#F8F9FA]">{{ $s2['venue'] ?? '' }}</strong><br>{{ $s2['address'] ?? '' }}</span>
                        </p>
                    </div>

                    @if(!empty($s2['maps_url']))
                    <div class="mt-4 pt-4 border-t border-[#C59B27]/20">
                        <a href="{{ $s2['maps_url'] }}" target="_blank" rel="noopener noreferrer"
                           class="w-full min-h-[44px] inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold bg-[#C59B27]/15 hover:bg-[#C59B27]/25 text-[#C59B27] border border-[#C59B27]/30 transition">
                            <span>Pituduh Lokasi (Google Maps)</span>
                        </a>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </section>

        {{-- 5. GALERI --}}
        @if(!empty($invitation->event_data['gallery_images']) && count($invitation->event_data['gallery_images']) > 0)
        <section id="galeri" class="px-6 py-12 border-t border-[#C59B27]/20 reveal-on-scroll">
            <div class="text-center mb-8">
                <span class="text-xs uppercase tracking-[0.2em] text-[#C59B27] font-bold">Momen Éndah</span>
                <h2 class="font-sunda-title text-2xl sm:text-3xl font-bold text-[#F8F9FA] mt-1">Galeri Poto</h2>
            </div>

            <div class="grid grid-cols-2 gap-3">
                @foreach($invitation->event_data['gallery_images'] as $photo)
                    <div class="aspect-square rounded-2xl overflow-hidden bg-[#0A1D16] border border-[#C59B27]/30 cursor-pointer group shadow-lg p-1"
                         @click="openLightbox('{{ asset('storage/' . $photo) }}')">
                        <img src="{{ asset('storage/' . $photo) }}" alt="Foto Galeri" class="w-full h-full object-cover rounded-xl group-hover:scale-105 transition duration-500">
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- 6. KADO DIGITAL --}}
        @if(!empty($invitation->event_data['gift_accounts']) && count($invitation->event_data['gift_accounts']) > 0)
        <section id="kado" class="px-6 py-12 border-t border-[#C59B27]/20 reveal-on-scroll">
            <div class="text-center mb-8">
                <span class="text-xs uppercase tracking-[0.2em] text-[#C59B27] font-bold">Kado &amp; Tanda Asih</span>
                <h2 class="font-sunda-title text-2xl sm:text-3xl font-bold text-[#F8F9FA] mt-1">Amplop Digital</h2>
                <p class="text-xs text-[#B7C7B0] mt-2 max-w-xs mx-auto">
                    Kasadayaan pidua ti wargi sadayana mangrupi kado anu paling utami:
                </p>
            </div>

            <div class="space-y-4">
                @foreach($invitation->event_data['gift_accounts'] as $index => $acc)
                <div class="p-5 rounded-2xl bg-[#112D22]/70 border border-[#C59B27]/40 shadow-lg">
                    <span class="text-xs font-bold tracking-wider text-[#C59B27] uppercase">{{ $acc['bank'] }}</span>
                    <div class="font-mono text-lg font-bold text-[#F8F9FA] tracking-wider my-1">
                        {{ $acc['account_number'] }}
                    </div>
                    <p class="text-xs text-[#B7C7B0] mb-4">a.n {{ $acc['account_name'] }}</p>

                    <button type="button" data-copy-target="{{ $acc['account_number'] }}"
                            class="btn-copy w-full min-h-[44px] px-4 py-2 rounded-xl text-xs font-semibold bg-[#C59B27]/15 hover:bg-[#C59B27]/25 text-[#C59B27] border border-[#C59B27]/30 transition active:scale-95">
                        <span x-text="copiedIndex === {{ $index }} ? 'Parantos Disalin!' : 'Salin Nomer Rekening'">Salin Nomer Rekening</span>
                    </button>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- 7. RSVP & UCAPAN --}}
        <section id="ucapan" class="px-6 py-12 border-t border-[#C59B27]/20 reveal-on-scroll">
            <div class="text-center mb-8">
                <span class="text-xs uppercase tracking-[0.2em] text-[#C59B27] font-bold">Buku Tamu</span>
                <h2 class="font-sunda-title text-2xl sm:text-3xl font-bold text-[#F8F9FA] mt-1">Neda Pidua</h2>
                <div class="flex items-center justify-center gap-4 mt-3 text-xs">
                    <span class="px-3 py-1 rounded-full bg-[#C59B27]/20 text-[#C59B27] border border-[#C59B27]/40 font-medium">
                        <strong x-text="counts.hadir">0</strong> Sumping
                    </span>
                    <span class="px-3 py-1 rounded-full bg-[#112D22] text-[#F8F9FA] font-medium border border-white/10">
                        <strong x-text="counts.total">0</strong> Total Pidua
                    </span>
                </div>
            </div>

            <!-- Form RSVP AJAX -->
            <form @submit.prevent="submitRsvp('{{ route('invitation.wish', $invitation->slug) }}')" class="p-6 rounded-2xl bg-[#112D22]/60 border border-[#C59B27]/30 mb-8 space-y-4">
                <div x-show="rsvpSuccessMsg" x-cloak class="p-3 rounded-xl bg-emerald-900/50 border border-emerald-500/30 text-emerald-300 text-xs text-center font-medium">
                    <span x-text="rsvpSuccessMsg"></span>
                </div>
                <div x-show="rsvpErrorMsg" x-cloak class="p-3 rounded-xl bg-red-900/50 border border-red-500/30 text-red-300 text-xs text-center font-medium">
                    <span x-text="rsvpErrorMsg"></span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#B7C7B0] mb-1">Wasta Saderek (Nama)</label>
                    <input type="text" x-model="rsvpForm.guest_name" required placeholder="Contoh: Nour Haqiki"
                           class="w-full px-4 py-2.5 rounded-xl bg-[#0A1D16] border border-[#C59B27]/30 text-[#F8F9FA] text-xs focus:ring-2 focus:ring-[#C59B27] focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#B7C7B0] mb-1">Kasayogian Kasumpingan</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl border border-[#C59B27]/30 text-xs font-medium cursor-pointer transition"
                               :class="rsvpForm.attendance === 'hadir' ? 'bg-[#C59B27]/20 border-[#C59B27] text-[#C59B27]' : 'bg-[#0A1D16] text-[#B7C7B0]'">
                            <input type="radio" value="hadir" x-model="rsvpForm.attendance" class="sr-only">
                            <span>Tiasa Sumping</span>
                        </label>
                        <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl border border-[#C59B27]/30 text-xs font-medium cursor-pointer transition"
                               :class="rsvpForm.attendance === 'tidak_hadir' ? 'bg-[#C59B27]/20 border-[#C59B27] text-[#C59B27]' : 'bg-[#0A1D16] text-[#B7C7B0]'">
                            <input type="radio" value="tidak_hadir" x-model="rsvpForm.attendance" class="sr-only">
                            <span>Hapunten Henteu Tiasa</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#B7C7B0] mb-1">Pidua &amp; Panyuhun (Ucapan)</label>
                    <textarea x-model="rsvpForm.message" rows="3" required placeholder="Tuliskeun doa sareng harepan saderek..."
                              class="w-full px-4 py-2.5 rounded-xl bg-[#0A1D16] border border-[#C59B27]/30 text-[#F8F9FA] text-xs focus:ring-2 focus:ring-[#C59B27] focus:outline-none"></textarea>
                </div>

                <button type="submit" 
                        :disabled="rsvpSubmitting"
                        class="w-full min-h-[44px] py-3 rounded-xl font-semibold text-xs tracking-wider uppercase bg-[#C59B27] text-[#112D22] hover:bg-[#DFC05A] active:scale-95 transition disabled:opacity-50 flex items-center justify-center gap-2">
                    <span x-show="!rsvpSubmitting">Kirimkeun Pidua</span>
                    <span x-show="rsvpSubmitting" x-cloak>Nuju Ngirimkeun...</span>
                </button>
            </form>

            <!-- Daftar Ucapan Live -->
            <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
                <template x-for="wish in wishes" :key="wish.id">
                    <div class="p-4 rounded-xl bg-[#112D22]/50 border border-[#C59B27]/20 text-xs">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="font-bold text-[#F8F9FA] text-sm" x-text="wish.guest_name"></span>
                            <span :class="wish.attendance === 'hadir' ? 'bg-[#C59B27]/20 text-[#C59B27]' : 'bg-[#0A1D16] text-[#B7C7B0]'"
                                  class="px-2 py-0.5 rounded-full text-[10px] font-semibold"
                                  x-text="wish.attendance === 'hadir' ? 'Sumping' : 'Hapunten Henteu Sumping'"></span>
                        </div>
                        <p class="text-[#D2C5A5] leading-relaxed" x-text="wish.message"></p>
                        <span class="block text-[10px] text-[#869E80] mt-2" x-text="wish.time_ago"></span>
                    </div>
                </template>
                <div x-show="wishes.length === 0" class="text-center py-6 text-xs text-[#869E80] italic">
                    Teu acan aya serat pidua. Saderek janten anu munggaran masihan doa!
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="text-center py-8 text-[11px] text-[#869E80] border-t border-[#C59B27]/20">
            <p>{{ $invitation->title }}</p>
            <p class="mt-1">Hatur Nuhun Kana Kasumpingan Sareng Piduana</p>
        </footer>
    </main>

    @include('themes.partials.engine')
</body>
</html>
