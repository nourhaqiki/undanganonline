<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ $invitation->title }} | Undangan Acara</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS v4 & Alpine.js CDN -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .font-simple-title { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
        .font-simple-body { font-family: 'Inter', system-ui, sans-serif; }
    </style>
</head>
@php $eventType = match ($invitation->category) { 'pernikahan' => 'wedding', 'tunangan' => 'engagement', default => $invitation->category }; @endphp
<body data-event-type="{{ $eventType }}" class="font-simple-body bg-slate-50 text-slate-800 overflow-x-clip overflow-hidden h-dvh antialiased selection:bg-blue-600 selection:text-white"
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
         class="motion-cover fixed inset-0 z-50 h-dvh w-full overflow-hidden flex flex-col justify-between items-center text-center p-6 bg-gradient-to-b from-slate-900 to-slate-950 text-white border-b border-slate-800">
        
        <!-- Badge Acara Atas -->
        <div class="pt-6 flex flex-col items-center">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-semibold tracking-wider uppercase bg-blue-500/10 text-blue-400 border border-blue-500/20">
                Official Event Invitation
            </span>
        </div>

        <!-- Judul & Penyelenggara -->
        <div class="max-w-md w-full my-auto space-y-4">
            <p class="text-xs uppercase tracking-widest text-slate-400 font-semibold">{{ $invitation->title }}</p>
            <h1 class="font-simple-title text-3xl sm:text-4xl font-extrabold tracking-tight text-white leading-tight">
                {{ $invitation->event_data['primary_name'] ?? 'Penyelenggara Acara' }}
                @if(!empty($invitation->event_data['secondary_name']))
                    <span class="block text-xl text-blue-400 font-normal my-1">&amp;</span>
                    {{ $invitation->event_data['secondary_name'] }}
                @endif
            </h1>

            <!-- Kotak Sapaan Personal Tamu (?to=) -->
            <div class="mt-6 p-4 rounded-2xl bg-slate-800/80 border border-slate-700 shadow-xl max-w-xs mx-auto backdrop-blur-sm">
                <p class="text-xs text-slate-400 font-medium">Kepada Yth. Bapak/Ibu:</p>
                <p class="text-base font-bold text-blue-300 mt-1 truncate">
                    {{ !empty($guestName) ? $guestName : 'Tamu Undangan' }}
                </p>
                @if(!empty($guestAddress))
                    <p class="text-xs text-slate-300 mt-1 truncate">
                        di {{ $guestAddress }}
                    </p>
                @endif
            </div>
        </div>

        <!-- Tombol Buka Undangan -->
        <div class="pb-8 w-full max-w-xs">
            <button @click="openInvitation()" 
                    type="button"
                    class="w-full min-h-[48px] px-6 py-3 rounded-full font-simple-title font-semibold text-xs tracking-wider uppercase bg-blue-600 text-white shadow-lg shadow-blue-500/25 hover:bg-blue-500 active:scale-95 transition-all flex items-center justify-center gap-2.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <span>Buka Undangan Acara</span>
            </button>
        </div>
    </div>

    {{-- ==================== MAIN CONTENT UNDANGAN ==================== --}}
    <main class="relative max-w-lg mx-auto bg-white border-x border-slate-200 shadow-xl min-h-screen pb-24">

        {{-- 2. HERO SECTION --}}
        <section id="hero" class="relative text-center px-6 pt-12 pb-16 overflow-hidden bg-slate-50 border-b border-slate-200">
            @if(!empty($invitation->event_data['cover_image']))
                <div class="w-44 h-44 sm:w-52 sm:h-52 mx-auto rounded-2xl overflow-hidden p-1 bg-white shadow-md border border-slate-200 mb-6">
                    <img src="{{ asset('storage/' . $invitation->event_data['cover_image']) }}" alt="{{ $invitation->title }}" class="invitation-photo-oval w-full h-full object-cover rounded-xl">
                </div>
            @endif

            <span class="inline-block px-3 py-1 rounded-full text-[11px] font-bold text-blue-600 bg-blue-50 border border-blue-200 mb-2">
                {{ $invitation->title }}
            </span>
            <h1 class="font-simple-title text-2xl sm:text-3xl font-extrabold text-slate-900 mb-2">
                {{ $invitation->event_data['primary_name'] }}
                @if(!empty($invitation->event_data['secondary_name']))
                    <span class="text-blue-600 block text-lg my-0.5">&amp;</span>
                    {{ $invitation->event_data['secondary_name'] }}
                @endif
            </h1>

            <p class="text-xs text-slate-500 font-medium mt-1">
                {{ \Carbon\Carbon::parse($invitation->event_data['session_1']['date'] ?? now())->isoFormat('dddd, D MMMM Y') }}
            </p>

            <!-- Countdown Timer -->
            <div class="mt-8 p-4 rounded-2xl bg-white border border-slate-200 shadow-sm max-w-xs mx-auto">
                <p class="text-xs uppercase tracking-wider text-slate-500 font-semibold mb-3">Hitung Mundur</p>
                <div class="grid grid-cols-4 gap-2 text-center">
                    <div class="bg-slate-50 rounded-xl p-2 border border-slate-200">
                        <span class="block text-2xl font-bold text-blue-600 font-mono" x-text="countdown.days">00</span>
                        <span class="text-[10px] uppercase text-slate-500">Hari</span>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-2 border border-slate-200">
                        <span class="block text-2xl font-bold text-blue-600 font-mono" x-text="countdown.hours">00</span>
                        <span class="text-[10px] uppercase text-slate-500">Jam</span>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-2 border border-slate-200">
                        <span class="block text-2xl font-bold text-blue-600 font-mono" x-text="countdown.minutes">00</span>
                        <span class="text-[10px] uppercase text-slate-500">Menit</span>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-2 border border-slate-200">
                        <span class="block text-2xl font-bold text-blue-600 font-mono" x-text="countdown.seconds">00</span>
                        <span class="text-[10px] uppercase text-slate-500">Detik</span>
                    </div>
                </div>
            </div>

            <!-- Simpan Google Calendar -->
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
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full text-xs font-semibold bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 shadow-sm transition active:scale-95">
                    <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Simpan ke Google Calendar</span>
                </a>
            </div>
        </section>

        {{-- 3. PROFIL SECTION --}}
        <section id="profil" class="px-6 py-12 reveal-on-scroll">
            <div class="text-center max-w-sm mx-auto mb-8">
                <p class="text-sm font-semibold text-blue-600 mb-1">
                    {{ $invitation->event_data['greeting'] ?? 'Salam Sejahtera,' }}
                </p>
                @if(!empty($invitation->event_data['quote']))
                <blockquote class="text-xs text-slate-600 leading-relaxed italic border-l-2 border-blue-500 pl-3 py-1 my-3 text-left">
                    "{{ $invitation->event_data['quote'] }}"
                </blockquote>
                @endif
            </div>

            <div class="space-y-4">
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 text-center">
                    @if(!empty($invitation->event_data['groom_photo']))
                        <div class="w-28 h-28 mx-auto mb-3 rounded-full overflow-hidden border-2 border-blue-500 p-0.5 bg-white">
                            <img src="{{ asset('storage/' . $invitation->event_data['groom_photo']) }}" alt="{{ $invitation->event_data['primary_name'] }}" loading="lazy" decoding="async" class="w-full h-full object-cover rounded-full">
                        </div>
                    @endif
                    <h3 class="font-simple-title text-lg font-bold text-slate-900">{{ $invitation->event_data['primary_name'] }}</h3>
                    @if(!empty($invitation->event_data['primary_title_parents']))
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">{{ $invitation->event_data['primary_title_parents'] }}</p>
                    @endif
                    @if(!empty($invitation->event_data['primary_instagram']))
                        <a href="https://instagram.com/{{ ltrim($invitation->event_data['primary_instagram'], '@') }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1.5 mt-3 text-xs text-blue-600 font-medium hover:underline">
                            <span>@ {{ ltrim($invitation->event_data['primary_instagram'], '@') }}</span>
                        </a>
                    @endif
                </div>

                @if(!empty($invitation->event_data['secondary_name']))
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 text-center">
                    @if(!empty($invitation->event_data['bride_photo']))
                        <div class="w-28 h-28 mx-auto mb-3 rounded-full overflow-hidden border-2 border-blue-500 p-0.5 bg-white">
                            <img src="{{ asset('storage/' . $invitation->event_data['bride_photo']) }}" alt="{{ $invitation->event_data['secondary_name'] }}" loading="lazy" decoding="async" class="w-full h-full object-cover rounded-full">
                        </div>
                    @endif
                    <h3 class="font-simple-title text-lg font-bold text-slate-900">{{ $invitation->event_data['secondary_name'] }}</h3>
                    @if(!empty($invitation->event_data['secondary_title_parents']))
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">{{ $invitation->event_data['secondary_title_parents'] }}</p>
                    @endif
                    @if(!empty($invitation->event_data['secondary_instagram']))
                        <a href="https://instagram.com/{{ ltrim($invitation->event_data['secondary_instagram'], '@') }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1.5 mt-3 text-xs text-blue-600 font-medium hover:underline">
                            <span>@ {{ ltrim($invitation->event_data['secondary_instagram'], '@') }}</span>
                        </a>
                    @endif
                </div>
                @endif
            </div>
        </section>

        {{-- 4. ACARA --}}
        <section id="acara" class="px-6 py-12 border-t border-slate-200 reveal-on-scroll">
            <div class="text-center mb-8">
                <span class="text-xs uppercase tracking-wider text-blue-600 font-bold">Waktu &amp; Tempat</span>
                <h2 class="font-simple-title text-2xl font-bold text-slate-900 mt-1">Jadwal Acara</h2>
            </div>

            <div class="space-y-5">
                <!-- Sesi 1 -->
                @php $s1 = $invitation->event_data['session_1'] ?? []; @endphp
                <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-sm relative">
                    <span class="inline-block px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase bg-blue-100 text-blue-700 mb-2">
                        SESI UTAMA
                    </span>
                    <h3 class="font-simple-title text-lg font-bold text-slate-900 mb-2">{{ $s1['title'] ?? 'Sesi Pembukaan & Presentasi' }}</h3>
                    <div class="space-y-1.5 text-xs text-slate-600">
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($s1['date'] ?? now())->isoFormat('dddd, D MMMM Y') }}</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $s1['time'] ?? 'Pukul 09.00 WIB' }}</span>
                        </p>
                        <p class="flex items-start gap-2 pt-1">
                            <svg class="w-4 h-4 text-blue-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span><strong class="text-slate-900">{{ $s1['venue'] ?? '' }}</strong><br>{{ $s1['address'] ?? '' }}</span>
                        </p>
                    </div>

                    @if(!empty($s1['maps_url']))
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <a href="{{ $s1['maps_url'] }}" target="_blank" rel="noopener noreferrer"
                           class="w-full min-h-[44px] inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold bg-blue-50 hover:bg-blue-100 text-blue-700 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            <span>Lihat di Google Maps</span>
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Sesi 2 -->
                @php $s2 = $invitation->event_data['session_2'] ?? []; @endphp
                @if(!empty($s2['enabled']) || !empty($s2['title']))
                <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-sm relative">
                    <span class="inline-block px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase bg-slate-100 text-slate-700 mb-2">
                        SESI TAMBAHAN
                    </span>
                    <h3 class="font-simple-title text-lg font-bold text-slate-900 mb-2">{{ $s2['title'] ?? 'Ramah Tamah & Networking' }}</h3>
                    <div class="space-y-1.5 text-xs text-slate-600">
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($s2['date'] ?? now())->isoFormat('dddd, D MMMM Y') }}</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $s2['time'] ?? 'Pukul 13.00 WIB' }}</span>
                        </p>
                        <p class="flex items-start gap-2 pt-1">
                            <svg class="w-4 h-4 text-blue-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span><strong class="text-slate-900">{{ $s2['venue'] ?? '' }}</strong><br>{{ $s2['address'] ?? '' }}</span>
                        </p>
                    </div>

                    @if(!empty($s2['maps_url']))
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <a href="{{ $s2['maps_url'] }}" target="_blank" rel="noopener noreferrer"
                           class="w-full min-h-[44px] inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold bg-blue-50 hover:bg-blue-100 text-blue-700 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            <span>Lihat di Google Maps</span>
                        </a>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </section>

        {{-- 5. GALERI --}}
        @if(!empty($invitation->event_data['gallery_images']) && count($invitation->event_data['gallery_images']) > 0)
        <section id="galeri" class="px-6 py-12 border-t border-slate-200 reveal-on-scroll">
            <div class="text-center mb-8">
                <span class="text-xs uppercase tracking-wider text-blue-600 font-bold">Dokumentasi</span>
                <h2 class="font-simple-title text-2xl font-bold text-slate-900 mt-1">Galeri Foto</h2>
            </div>

            <div class="grid grid-cols-2 gap-3">
                @foreach($invitation->event_data['gallery_images'] as $photo)
                    <div class="aspect-square rounded-2xl overflow-hidden bg-slate-100 border border-slate-200 cursor-pointer group shadow-sm"
                         @click="openLightbox('{{ asset('storage/' . $photo) }}')">
                        <img src="{{ asset('storage/' . $photo) }}" alt="Foto Galeri" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- 6. KADO / REKENING DUKUNGAN --}}
        @if(!empty($invitation->event_data['gift_accounts']) && count($invitation->event_data['gift_accounts']) > 0)
        <section id="kado" class="px-6 py-12 border-t border-slate-200 reveal-on-scroll">
            <div class="text-center mb-8">
                <span class="text-xs uppercase tracking-wider text-blue-600 font-bold">Kontribusi / Rekening</span>
                <h2 class="font-simple-title text-2xl font-bold text-slate-900 mt-1">Informasi Pembayaran / Kado</h2>
            </div>

            <div class="space-y-4">
                @foreach($invitation->event_data['gift_accounts'] as $index => $acc)
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 shadow-sm">
                    <span class="text-xs font-bold tracking-wider text-blue-700 uppercase">{{ $acc['bank'] }}</span>
                    <div class="font-mono text-lg font-bold text-slate-900 tracking-wider my-1">
                        {{ $acc['account_number'] }}
                    </div>
                    <p class="text-xs text-slate-500 mb-4">a.n {{ $acc['account_name'] }}</p>

                    <button type="button" data-copy-target="{{ $acc['account_number'] }}"
                            class="btn-copy w-full min-h-[44px] px-4 py-2 rounded-xl text-xs font-semibold bg-white hover:bg-slate-100 text-slate-800 border border-slate-300 transition active:scale-95">
                        <span x-text="copiedIndex === {{ $index }} ? 'Nomor Tersalin!' : 'Salin Nomor Rekening'">Salin Nomor Rekening</span>
                    </button>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- 7. RSVP --}}
        <section id="ucapan" class="px-6 py-12 border-t border-slate-200 reveal-on-scroll">
            <div class="text-center mb-8">
                <span class="text-xs uppercase tracking-wider text-blue-600 font-bold">Buku Tamu</span>
                <h2 class="font-simple-title text-2xl font-bold text-slate-900 mt-1">Konfirmasi &amp; Ucapan</h2>
                <div class="flex items-center justify-center gap-4 mt-3 text-xs">
                    <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-medium">
                        <strong x-text="counts.hadir">0</strong> Hadir
                    </span>
                    <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 font-medium border border-slate-200">
                        <strong x-text="counts.total">0</strong> Total Pesan
                    </span>
                </div>
            </div>

            <!-- Form RSVP AJAX -->
            <form @submit.prevent="submitRsvp('{{ route('invitation.wish', $invitation->slug) }}')" class="p-6 rounded-2xl bg-slate-50 border border-slate-200 mb-8 space-y-4">
                <div x-show="rsvpSuccessMsg" x-cloak class="p-3 rounded-xl bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs text-center font-medium">
                    <span x-text="rsvpSuccessMsg"></span>
                </div>
                <div x-show="rsvpErrorMsg" x-cloak class="p-3 rounded-xl bg-red-100 border border-red-300 text-red-800 text-xs text-center font-medium">
                    <span x-text="rsvpErrorMsg"></span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                    <input type="text" x-model="rsvpForm.guest_name" required placeholder="Contoh: Nour Haqiki"
                           class="w-full px-4 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Status Kehadiran</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl border border-slate-200 text-xs font-medium cursor-pointer transition"
                               :class="rsvpForm.attendance === 'hadir' ? 'bg-blue-50 border-blue-500 text-blue-700' : 'bg-white text-slate-600'">
                            <input type="radio" value="hadir" x-model="rsvpForm.attendance" class="sr-only">
                            <span>Hadir</span>
                        </label>
                        <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl border border-slate-200 text-xs font-medium cursor-pointer transition"
                               :class="rsvpForm.attendance === 'tidak_hadir' ? 'bg-blue-50 border-blue-500 text-blue-700' : 'bg-white text-slate-600'">
                            <input type="radio" value="tidak_hadir" x-model="rsvpForm.attendance" class="sr-only">
                            <span>Tidak Hadir</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Pesan &amp; Ucapan</label>
                    <textarea x-model="rsvpForm.message" rows="3" required placeholder="Tuliskan ucapan atau konfirmasi Anda..."
                              class="w-full px-4 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                </div>

                <button type="submit" 
                        :disabled="rsvpSubmitting"
                        class="w-full min-h-[44px] py-3 rounded-xl font-simple-title font-bold text-xs tracking-wider uppercase bg-blue-600 text-white hover:bg-blue-700 active:scale-95 transition disabled:opacity-50 flex items-center justify-center gap-2">
                    <span x-show="!rsvpSubmitting">Kirim Konfirmasi</span>
                    <span x-show="rsvpSubmitting" x-cloak>Mengirimkan...</span>
                </button>
            </form>

            <!-- Daftar Pesan Live -->
            <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
                <template x-for="wish in wishes" :key="wish.id">
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="font-bold text-slate-900 text-sm" x-text="wish.guest_name"></span>
                            <span :class="wish.attendance === 'hadir' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600'"
                                  class="px-2 py-0.5 rounded-full text-[10px] font-semibold"
                                  x-text="wish.attendance === 'hadir' ? 'Hadir' : 'Tidak Hadir'"></span>
                        </div>
                        <p class="text-slate-700 leading-relaxed" x-text="wish.message"></p>
                        <span class="block text-[10px] text-slate-400 mt-2" x-text="wish.time_ago"></span>
                    </div>
                </template>
                <div x-show="wishes.length === 0" class="text-center py-6 text-xs text-slate-400 italic">
                    Belum ada ucapan. Jadilah yang pertama memberikan konfirmasi!
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="text-center py-8 text-[11px] text-slate-400 border-t border-slate-200">
            <p>{{ $invitation->title }}</p>
            <p class="mt-1">Undangan Acara Online Modern</p>
        </footer>
    </main>

    @include('themes.partials.engine')
</body>
</html>
