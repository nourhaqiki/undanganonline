<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $invitation->title }} | Undangan Online</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Lora:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">

    <!-- Tailwind CSS v4 & Alpine.js CDN -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .font-serif-title { font-family: 'Playfair Display', Georgia, serif; }
        .font-body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
    </style>
</head>
@php $eventType = match ($invitation->category) { 'pernikahan' => 'wedding', 'tunangan' => 'engagement', default => $invitation->category }; @endphp
<body data-event-type="{{ $eventType }}" class="font-body bg-stone-900 text-stone-100 overflow-x-hidden overflow-hidden h-screen antialiased selection:bg-amber-400 selection:text-stone-900"
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
         class="motion-cover fixed inset-0 z-50 h-dvh w-full overflow-hidden flex flex-col justify-between items-center text-center p-6 bg-stone-950 bg-radial from-stone-900 to-black border-b border-white/10">
        
        <!-- Ornamen Atas -->
        <div class="pt-6">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-medium tracking-widest uppercase bg-amber-400/10 text-amber-300 border border-amber-400/20">
                {{ strtoupper($invitation->category ?? 'Undangan Spesial') }}
            </span>
        </div>

        <!-- Judul & Nama Mempelai -->
        <div class="max-w-md w-full my-auto space-y-4">
            <h2 class="text-sm tracking-widest uppercase text-stone-400">{{ $invitation->title }}</h2>
            <h1 class="font-serif-title text-4xl sm:text-5xl font-bold tracking-tight text-white leading-tight">
                {{ $invitation->event_data['primary_name'] ?? 'Mempelai 1' }}
                @if(!empty($invitation->event_data['secondary_name']))
                    <span class="block text-2xl text-amber-400 font-normal my-1">&amp;</span>
                    {{ $invitation->event_data['secondary_name'] }}
                @endif
            </h1>

            <!-- Kotak Sapaan Personal Tamu (?to=) -->
            <div class="mt-6 p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm max-w-xs mx-auto">
                <p class="text-xs text-stone-400">Kepada Yth. Bapak/Ibu/Saudara/i:</p>
                <p class="text-base font-semibold text-amber-300 mt-0.5 truncate">
                    {{ !empty($guestName) ? $guestName : 'Tamu Undangan' }}
                </p>
                @if(!empty($guestAddress))
                    <p class="text-xs text-stone-300/80 mt-1 truncate">
                        di {{ $guestAddress }}
                    </p>
                @endif
            </div>
        </div>

        <!-- Tombol Buka Undangan -->
        <div class="pb-8 w-full max-w-xs">
            <button @click="openInvitation()" 
                    type="button"
                    class="w-full min-h-[48px] px-6 py-3 rounded-full font-semibold text-sm bg-gradient-to-r from-amber-400 to-amber-500 text-stone-950 shadow-lg shadow-amber-500/20 hover:from-amber-300 hover:to-amber-400 active:scale-95 transition-all flex items-center justify-center gap-2.5">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>Buka Undangan</span>
            </button>
        </div>
    </div>

    {{-- ==================== MAIN CONTENT UNDANGAN ==================== --}}
    <main class="relative max-w-lg mx-auto bg-stone-900 border-x border-white/5 shadow-2xl min-h-screen pb-24">

        {{-- 2. HERO SECTION --}}
        <section id="hero" class="relative text-center px-6 pt-12 pb-16 overflow-hidden">
            @if(!empty($invitation->event_data['cover_image']))
                <div class="w-48 h-48 sm:w-56 sm:h-56 mx-auto rounded-full overflow-hidden p-1.5 bg-gradient-to-tr from-amber-400 to-amber-600 shadow-xl mb-6">
                    <img src="{{ asset('storage/' . $invitation->event_data['cover_image']) }}" alt="{{ $invitation->title }}" class="invitation-photo-oval w-full h-full object-cover rounded-full">
                </div>
            @else
                <div class="w-32 h-32 mx-auto rounded-full bg-amber-400/10 border-2 border-dashed border-amber-400/40 flex items-center justify-center mb-6">
                    <svg class="w-12 h-12 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
            @endif

            <p class="text-xs uppercase tracking-widest text-amber-400 mb-2 font-medium">{{ $invitation->title }}</p>
            <h1 class="font-serif-title text-3xl sm:text-4xl font-bold text-white mb-3">
                {{ $invitation->event_data['primary_name'] }}
                @if(!empty($invitation->event_data['secondary_name']))
                    <span class="text-amber-400 text-2xl block">&amp;</span>
                    {{ $invitation->event_data['secondary_name'] }}
                @endif
            </h1>

            <p class="text-sm text-stone-300 font-medium mt-3">
                {{ \Carbon\Carbon::parse($invitation->event_data['session_1']['date'] ?? now())->isoFormat('dddd, D MMMM Y') }}
            </p>

            <!-- Real-Time Countdown Timer -->
            <div class="mt-8 p-4 rounded-2xl bg-white/5 border border-white/10 max-w-xs mx-auto">
                <p class="text-xs uppercase tracking-wider text-stone-400 mb-3 font-semibold">Menghitung Hari</p>
                <div class="grid grid-cols-4 gap-2 text-center">
                    <div class="bg-stone-800/80 rounded-xl p-2 border border-white/5">
                        <span class="block text-2xl font-bold text-amber-400 font-mono" x-text="countdown.days">00</span>
                        <span class="text-[10px] uppercase text-stone-400">Hari</span>
                    </div>
                    <div class="bg-stone-800/80 rounded-xl p-2 border border-white/5">
                        <span class="block text-2xl font-bold text-amber-400 font-mono" x-text="countdown.hours">00</span>
                        <span class="text-[10px] uppercase text-stone-400">Jam</span>
                    </div>
                    <div class="bg-stone-800/80 rounded-xl p-2 border border-white/5">
                        <span class="block text-2xl font-bold text-amber-400 font-mono" x-text="countdown.minutes">00</span>
                        <span class="text-[10px] uppercase text-stone-400">Menit</span>
                    </div>
                    <div class="bg-stone-800/80 rounded-xl p-2 border border-white/5">
                        <span class="block text-2xl font-bold text-amber-400 font-mono" x-text="countdown.seconds">00</span>
                        <span class="text-[10px] uppercase text-stone-400">Detik</span>
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
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full text-xs font-semibold bg-white/10 hover:bg-white/15 text-stone-200 border border-white/10 transition active:scale-95">
                    <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Simpan ke Google Calendar</span>
                </a>
            </div>
        </section>

        {{-- 3. PROFIL SECTION --}}
        <section id="profil" class="px-6 py-12 border-t border-white/5 reveal-on-scroll">
            <div class="text-center max-w-sm mx-auto mb-8">
                @if(!empty($invitation->event_data['greeting']))
                    <p class="text-sm font-serif-title italic text-amber-300 mb-2">{{ $invitation->event_data['greeting'] }}</p>
                @endif
                @if(!empty($invitation->event_data['quote']))
                    <blockquote class="text-xs text-stone-300 leading-relaxed italic border-l-2 border-amber-400/40 pl-3 my-4">
                        "{{ $invitation->event_data['quote'] }}"
                    </blockquote>
                @endif
            </div>

            <!-- Kartu Profil Mempelai / Penyelenggara -->
            <div class="space-y-6">
                <!-- Profil 1 -->
                <div class="p-5 rounded-2xl bg-white/5 border border-white/10 text-center">
                    @if(!empty($invitation->event_data['groom_photo']))
                        <div class="w-32 h-40 mx-auto mb-3 rounded-t-full rounded-b-xl overflow-hidden border-2 border-amber-400 p-0.5">
                            <img src="{{ asset('storage/' . $invitation->event_data['groom_photo']) }}" alt="{{ $invitation->event_data['primary_name'] }}" class="w-full h-full object-cover rounded-t-full rounded-b-lg">
                        </div>
                    @endif
                    <h3 class="font-serif-title text-2xl font-bold text-white">{{ $invitation->event_data['primary_name'] }}</h3>
                    @if(!empty($invitation->event_data['primary_title_parents']))
                        <p class="text-xs text-stone-400 mt-1 leading-relaxed">{{ $invitation->event_data['primary_title_parents'] }}</p>
                    @endif
                    @if(!empty($invitation->event_data['primary_instagram']))
                        <a href="https://instagram.com/{{ ltrim($invitation->event_data['primary_instagram'], '@') }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1.5 mt-3 text-xs text-amber-300 hover:text-amber-200">
                            <span>@ {{ ltrim($invitation->event_data['primary_instagram'], '@') }}</span>
                        </a>
                    @endif
                </div>

                <!-- Profil 2 (jika ada) -->
                @if(!empty($invitation->event_data['secondary_name']))
                <div class="text-center text-amber-400 font-serif-title text-xl font-bold">&amp;</div>
                <div class="p-5 rounded-2xl bg-white/5 border border-white/10 text-center">
                    @if(!empty($invitation->event_data['bride_photo']))
                        <div class="w-32 h-40 mx-auto mb-3 rounded-t-full rounded-b-xl overflow-hidden border-2 border-amber-400 p-0.5">
                            <img src="{{ asset('storage/' . $invitation->event_data['bride_photo']) }}" alt="{{ $invitation->event_data['secondary_name'] }}" class="w-full h-full object-cover rounded-t-full rounded-b-lg">
                        </div>
                    @endif
                    <h3 class="font-serif-title text-2xl font-bold text-white">{{ $invitation->event_data['secondary_name'] }}</h3>
                    @if(!empty($invitation->event_data['secondary_title_parents']))
                        <p class="text-xs text-stone-400 mt-1 leading-relaxed">{{ $invitation->event_data['secondary_title_parents'] }}</p>
                    @endif
                    @if(!empty($invitation->event_data['secondary_instagram']))
                        <a href="https://instagram.com/{{ ltrim($invitation->event_data['secondary_instagram'], '@') }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1.5 mt-3 text-xs text-amber-300 hover:text-amber-200">
                            <span>@ {{ ltrim($invitation->event_data['secondary_instagram'], '@') }}</span>
                        </a>
                    @endif
                </div>
                @endif
            </div>
        </section>

        {{-- 4. ACARA & JADWAL DUAL SESI --}}
        <section id="acara" class="px-6 py-12 border-t border-white/5 reveal-on-scroll">
            <div class="text-center mb-8">
                <span class="text-xs uppercase tracking-widest text-amber-400 font-medium">Jadwal &amp; Lokasi</span>
                <h2 class="font-serif-title text-2xl sm:text-3xl font-bold text-white mt-1">Rangkaian Acara</h2>
            </div>

            <div class="space-y-6">
                <!-- Sesi 1 -->
                @php $s1 = $invitation->event_data['session_1'] ?? []; @endphp
                <div class="p-6 rounded-2xl bg-white/5 border border-white/10 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-amber-400/5 rounded-bl-full pointer-events-none"></div>
                    <span class="inline-block px-3 py-1 rounded-full text-[11px] font-semibold bg-amber-400/20 text-amber-300 mb-3">
                        SESI UTAMA
                    </span>
                    <h3 class="font-serif-title text-xl font-bold text-white mb-2">{{ $s1['title'] ?? 'Akad Nikah / Acara Utama' }}</h3>
                    <div class="space-y-1.5 text-xs text-stone-300">
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($s1['date'] ?? now())->isoFormat('dddd, D MMMM Y') }}</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $s1['time'] ?? 'Pukul 09.00 WIB - Selesai' }}</span>
                        </p>
                        <p class="flex items-start gap-2 pt-1">
                            <svg class="w-4 h-4 text-amber-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span><strong class="text-white">{{ $s1['venue'] ?? '' }}</strong><br>{{ $s1['address'] ?? '' }}</span>
                        </p>
                    </div>

                    @if(!empty($s1['maps_url']))
                    <div class="mt-4 pt-4 border-t border-white/10">
                        <a href="{{ $s1['maps_url'] }}" target="_blank" rel="noopener noreferrer"
                           class="w-full min-h-[44px] inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold bg-white/10 hover:bg-white/15 text-amber-300 border border-white/10 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            <span>Buka Google Maps</span>
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Sesi 2 (Jika Ada) -->
                @php $s2 = $invitation->event_data['session_2'] ?? []; @endphp
                @if(!empty($s2['enabled']) || !empty($s2['title']))
                <div class="p-6 rounded-2xl bg-white/5 border border-white/10 relative overflow-hidden">
                    <span class="inline-block px-3 py-1 rounded-full text-[11px] font-semibold bg-white/10 text-stone-300 mb-3">
                        SESI TAMBAHAN
                    </span>
                    <h3 class="font-serif-title text-xl font-bold text-white mb-2">{{ $s2['title'] ?? 'Resepsi' }}</h3>
                    <div class="space-y-1.5 text-xs text-stone-300">
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($s2['date'] ?? now())->isoFormat('dddd, D MMMM Y') }}</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $s2['time'] ?? 'Pukul 11.00 WIB - Selesai' }}</span>
                        </p>
                        <p class="flex items-start gap-2 pt-1">
                            <svg class="w-4 h-4 text-amber-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span><strong class="text-white">{{ $s2['venue'] ?? '' }}</strong><br>{{ $s2['address'] ?? '' }}</span>
                        </p>
                    </div>

                    @if(!empty($s2['maps_url']))
                    <div class="mt-4 pt-4 border-t border-white/10">
                        <a href="{{ $s2['maps_url'] }}" target="_blank" rel="noopener noreferrer"
                           class="w-full min-h-[44px] inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold bg-white/10 hover:bg-white/15 text-amber-300 border border-white/10 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            <span>Buka Google Maps</span>
                        </a>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </section>

        {{-- 5. GALERI LIGHTBOX (JIKA ADA FOTO) --}}
        @if(!empty($invitation->event_data['gallery_images']) && count($invitation->event_data['gallery_images']) > 0)
        <section id="galeri" class="px-6 py-12 border-t border-white/5 reveal-on-scroll">
            <div class="text-center mb-8">
                <span class="text-xs uppercase tracking-widest text-amber-400 font-medium">Momen Bahagia</span>
                <h2 class="font-serif-title text-2xl sm:text-3xl font-bold text-white mt-1">Galeri Foto</h2>
            </div>

            <div class="grid grid-cols-2 gap-3">
                @foreach($invitation->event_data['gallery_images'] as $photo)
                    <div class="aspect-square rounded-2xl overflow-hidden bg-stone-800 border border-white/10 cursor-pointer group shadow-lg"
                         @click="openLightbox('{{ asset('storage/' . $photo) }}')">
                        <img src="{{ asset('storage/' . $photo) }}" alt="Foto Galeri" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- 6. KADO DIGITAL / AMPLOP (JIKA ADA REKENING) --}}
        @if(!empty($invitation->event_data['gift_accounts']) && count($invitation->event_data['gift_accounts']) > 0)
        <section id="kado" class="px-6 py-12 border-t border-white/5 reveal-on-scroll">
            <div class="text-center mb-8">
                <span class="text-xs uppercase tracking-widest text-amber-400 font-medium">Tanda Kasih</span>
                <h2 class="font-serif-title text-2xl sm:text-3xl font-bold text-white mt-1">Kado &amp; Amplop Digital</h2>
                <p class="text-xs text-stone-400 mt-2 max-w-xs mx-auto">
                    Doa restu Anda adalah karunia terbaik. Namun jika ingin memberi tanda kasih, dapat disalurkan melalui rekening berikut:
                </p>
            </div>

            <div class="space-y-4">
                @foreach($invitation->event_data['gift_accounts'] as $index => $acc)
                <div class="p-5 rounded-2xl bg-gradient-to-br from-stone-800 to-stone-850 border border-white/10 shadow-lg">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold tracking-wider text-amber-400 uppercase">{{ $acc['bank'] }}</span>
                        <svg class="w-5 h-5 text-stone-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <div class="font-mono text-lg font-bold text-white tracking-wider my-1">
                        {{ $acc['account_number'] }}
                    </div>
                    <p class="text-xs text-stone-400 mb-4">a.n {{ $acc['account_name'] }}</p>

                    <button type="button" data-copy-target="{{ $acc['account_number'] }}"
                            class="btn-copy w-full min-h-[44px] px-4 py-2 rounded-xl text-xs font-semibold bg-white/10 hover:bg-white/15 text-stone-200 border border-white/10 transition active:scale-95 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                        </svg>
                        <span x-text="copiedIndex === {{ $index }} ? 'Tersalin!' : 'Salin Nomor Rekening'">Salin Nomor Rekening</span>
                    </button>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- 7. RSVP & BUKU TAMU LIVE --}}
        <section id="ucapan" class="px-6 py-12 border-t border-white/5 reveal-on-scroll">
            <div class="text-center mb-8">
                <span class="text-xs uppercase tracking-widest text-amber-400 font-medium">Buku Tamu</span>
                <h2 class="font-serif-title text-2xl sm:text-3xl font-bold text-white mt-1">Konfirmasi &amp; Ucapan</h2>
                <div class="flex items-center justify-center gap-4 mt-3 text-xs">
                    <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-medium">
                        <strong x-text="counts.hadir">0</strong> Hadir
                    </span>
                    <span class="px-3 py-1 rounded-full bg-white/10 text-stone-300 font-medium">
                        <strong x-text="counts.total">0</strong> Total Ucapan
                    </span>
                </div>
            </div>

            <!-- Form RSVP AJAX -->
            <form @submit.prevent="submitRsvp('{{ route('invitation.wish', $invitation->slug) }}')" class="p-6 rounded-2xl bg-white/5 border border-white/10 mb-8 space-y-4">
                <div x-show="rsvpSuccessMsg" x-cloak class="p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs text-center font-medium">
                    <span x-text="rsvpSuccessMsg"></span>
                </div>
                <div x-show="rsvpErrorMsg" x-cloak class="p-3 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-xs text-center font-medium">
                    <span x-text="rsvpErrorMsg"></span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-300 mb-1">Nama Anda</label>
                    <input type="text" x-model="rsvpForm.guest_name" required placeholder="Contoh: Nour Haqiki"
                           class="w-full px-4 py-2.5 rounded-xl bg-stone-800 border border-white/10 text-white text-xs focus:ring-2 focus:ring-amber-400 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-300 mb-1">Konfirmasi Kehadiran</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl border border-white/10 text-xs font-medium cursor-pointer transition"
                               :class="rsvpForm.attendance === 'hadir' ? 'bg-amber-400/20 border-amber-400 text-amber-300' : 'bg-stone-800 text-stone-400'">
                            <input type="radio" value="hadir" x-model="rsvpForm.attendance" class="sr-only">
                            <span>Hadir</span>
                        </label>
                        <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl border border-white/10 text-xs font-medium cursor-pointer transition"
                               :class="rsvpForm.attendance === 'tidak_hadir' ? 'bg-amber-400/20 border-amber-400 text-amber-300' : 'bg-stone-800 text-stone-400'">
                            <input type="radio" value="tidak_hadir" x-model="rsvpForm.attendance" class="sr-only">
                            <span>Tidak Hadir</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-300 mb-1">Doa &amp; Ucapan Hangat</label>
                    <textarea x-model="rsvpForm.message" rows="3" required placeholder="Tuliskan pesan selamat & doa restu Anda..."
                              class="w-full px-4 py-2.5 rounded-xl bg-stone-800 border border-white/10 text-white text-xs focus:ring-2 focus:ring-amber-400 focus:outline-none"></textarea>
                </div>

                <button type="submit" 
                        :disabled="rsvpSubmitting"
                        class="w-full min-h-[44px] py-3 rounded-xl font-semibold text-xs bg-amber-400 text-stone-950 hover:bg-amber-300 active:scale-95 transition disabled:opacity-50 flex items-center justify-center gap-2">
                    <span x-show="!rsvpSubmitting">Kirim Konfirmasi &amp; Ucapan</span>
                    <span x-show="rsvpSubmitting" x-cloak>Mengirimkan...</span>
                </button>
            </form>

            <!-- Daftar Ucapan Live (AJAX Prepend) -->
            <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
                <template x-for="wish in wishes" :key="wish.id">
                    <div class="p-4 rounded-xl bg-white/5 border border-white/5 text-xs">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="font-bold text-white text-sm" x-text="wish.guest_name"></span>
                            <span :class="wish.attendance === 'hadir' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-stone-700 text-stone-400'"
                                  class="px-2 py-0.5 rounded-full text-[10px] font-semibold"
                                  x-text="wish.attendance === 'hadir' ? 'Hadir' : 'Tidak Hadir'"></span>
                        </div>
                        <p class="text-stone-300 leading-relaxed" x-text="wish.message"></p>
                        <span class="block text-[10px] text-stone-500 mt-2" x-text="wish.time_ago"></span>
                    </div>
                </template>
                <div x-show="wishes.length === 0" class="text-center py-6 text-xs text-stone-500 italic">
                    Belum ada ucapan. Jadilah yang pertama memberikan doa restu!
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="text-center py-8 text-[11px] text-stone-500 border-t border-white/5">
            <p>{{ $invitation->title }}</p>
            <p class="mt-1">Dibuat dengan generator undangan digital</p>
        </footer>
    </main>

    {{-- Include Shared Floating Elements, Modals & Logic --}}
    @include('themes.partials.engine')
</body>
</html>
