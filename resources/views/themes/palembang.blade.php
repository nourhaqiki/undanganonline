<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $invitation->title }} | Undangan Adat Palembang</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Cinzel & Lora -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800&family=Lora:ital,wght@0,400;0,500;0,600;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS v4 & Alpine.js CDN -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .font-palembang-title { font-family: 'Cinzel', serif; }
        .font-palembang-body { font-family: 'Lora', Georgia, serif; }
        .font-sans-ui { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
    </style>
</head>
@php $eventType = match ($invitation->category) { 'pernikahan' => 'wedding', 'tunangan' => 'engagement', default => $invitation->category }; @endphp
<body data-event-type="{{ $eventType }}" class="font-palembang-body bg-[#1E0407] text-[#FAF5E6] overflow-x-hidden overflow-hidden h-screen antialiased selection:bg-[#E0A938] selection:text-[#1E0407]"
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
         class="motion-cover fixed inset-0 z-50 h-dvh w-full overflow-hidden flex flex-col justify-between items-center text-center p-6 bg-gradient-to-b from-[#2B050B] via-[#1E0407] to-[#120204] border-b border-[#E0A938]/30">
        
        <!-- Ornamen Songket Sriwijaya Atas -->
        <div class="pt-6 flex flex-col items-center">
            <svg class="w-10 h-10 text-[#E0A938]" viewBox="0 0 100 100" fill="currentColor">
                <polygon points="50,10 65,35 90,50 65,65 50,90 35,65 10,50 35,35"/>
                <circle cx="50" cy="50" r="8" fill="#FAF5E6"/>
            </svg>
            <span class="mt-2 text-[11px] font-palembang-title tracking-[0.3em] uppercase text-[#E0A938] font-bold">
                Songket Sriwijaya
            </span>
        </div>

        <!-- Judul & Nama Mempelai -->
        <div class="max-w-md w-full my-auto space-y-4">
            <p class="text-xs tracking-[0.25em] uppercase text-[#F3D58C] font-sans-ui">{{ $invitation->title }}</p>
            <h1 class="font-palembang-title text-3xl sm:text-4xl font-bold tracking-wider text-[#FAF5E6] leading-tight">
                {{ $invitation->event_data['primary_name'] ?? 'Mempelai 1' }}
                @if(!empty($invitation->event_data['secondary_name']))
                    <span class="block text-2xl text-[#E0A938] font-normal my-1">&amp;</span>
                    {{ $invitation->event_data['secondary_name'] }}
                @endif
            </h1>

            <!-- Kotak Sapaan Personal Tamu (?to=) -->
            <div class="mt-6 p-4 rounded-2xl bg-[#4A0A12]/80 border border-[#E0A938]/40 shadow-inner max-w-xs mx-auto backdrop-blur-sm">
                <p class="text-xs font-sans-ui text-[#D7B5B9]">Kepada Yth. Bapak/Ibu/Saudara/i:</p>
                <p class="text-base font-bold text-[#E0A938] mt-1 font-sans-ui truncate">
                    {{ !empty($guestName) ? $guestName : 'Tamu Undangan' }}
                </p>
                @if(!empty($guestAddress))
                    <p class="text-xs text-[#F3D58C] mt-1 font-sans-ui truncate">
                        di {{ $guestAddress }}
                    </p>
                @endif
            </div>
        </div>

        <!-- Tombol Buka Undangan -->
        <div class="pb-8 w-full max-w-xs">
            <button @click="openInvitation()" 
                    type="button"
                    class="w-full min-h-[48px] px-6 py-3 rounded-full font-palembang-title font-bold text-xs tracking-widest uppercase bg-gradient-to-r from-[#E0A938] to-[#B37E1C] text-[#2B050B] shadow-lg shadow-[#E0A938]/20 hover:from-[#F3D58C] hover:to-[#E0A938] active:scale-95 transition-all flex items-center justify-center gap-2.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>Buka Undangan</span>
            </button>
        </div>
    </div>

    {{-- ==================== MAIN CONTENT UNDANGAN ==================== --}}
    <main class="relative max-w-lg mx-auto bg-[#26050A] border-x border-[#E0A938]/20 shadow-2xl min-h-screen pb-24">

        {{-- 2. HERO SECTION --}}
        <section id="hero" class="relative text-center px-6 pt-12 pb-16 overflow-hidden">
            <div class="flex justify-center mb-4">
                <svg class="w-10 h-10 text-[#E0A938]" viewBox="0 0 100 100" fill="currentColor">
                    <polygon points="50,10 65,35 90,50 65,65 50,90 35,65 10,50 35,35"/>
                    <circle cx="50" cy="50" r="8" fill="#FAF5E6"/>
                </svg>
            </div>

            @if(!empty($invitation->event_data['cover_image']))
                <div class="w-48 h-48 sm:w-56 sm:h-56 mx-auto rounded-full overflow-hidden p-1.5 bg-gradient-to-tr from-[#E0A938] via-[#FAF5E6] to-[#6B0F1A] shadow-2xl mb-6">
                    <img src="{{ asset('storage/' . $invitation->event_data['cover_image']) }}" alt="{{ $invitation->title }}" class="invitation-photo-oval w-full h-full object-cover rounded-full">
                </div>
            @endif

            <p class="text-xs uppercase tracking-[0.25em] text-[#E0A938] font-palembang-title mb-2 font-bold">{{ $invitation->title }}</p>
            <h1 class="font-palembang-title text-3xl sm:text-4xl font-bold text-[#FAF5E6] mb-3">
                {{ $invitation->event_data['primary_name'] }}
                @if(!empty($invitation->event_data['secondary_name']))
                    <span class="text-[#E0A938] text-2xl block">&amp;</span>
                    {{ $invitation->event_data['secondary_name'] }}
                @endif
            </h1>

            <p class="text-sm text-[#F3D58C] font-sans-ui mt-3">
                {{ \Carbon\Carbon::parse($invitation->event_data['session_1']['date'] ?? now())->isoFormat('dddd, D MMMM Y') }}
            </p>

            <!-- Countdown Timer -->
            <div class="mt-8 p-4 rounded-2xl bg-[#4A0A12]/60 border border-[#E0A938]/30 max-w-xs mx-auto">
                <p class="text-xs uppercase tracking-wider text-[#E0A938] font-palembang-title mb-3 font-bold">Menghitung Hari Bahagia</p>
                <div class="grid grid-cols-4 gap-2 text-center font-sans-ui">
                    <div class="bg-[#1E0407] rounded-xl p-2 border border-[#E0A938]/20">
                        <span class="block text-2xl font-bold text-[#E0A938] font-mono" x-text="countdown.days">00</span>
                        <span class="text-[10px] uppercase text-[#D7B5B9]">Hari</span>
                    </div>
                    <div class="bg-[#1E0407] rounded-xl p-2 border border-[#E0A938]/20">
                        <span class="block text-2xl font-bold text-[#E0A938] font-mono" x-text="countdown.hours">00</span>
                        <span class="text-[10px] uppercase text-[#D7B5B9]">Jam</span>
                    </div>
                    <div class="bg-[#1E0407] rounded-xl p-2 border border-[#E0A938]/20">
                        <span class="block text-2xl font-bold text-[#E0A938] font-mono" x-text="countdown.minutes">00</span>
                        <span class="text-[10px] uppercase text-[#D7B5B9]">Menit</span>
                    </div>
                    <div class="bg-[#1E0407] rounded-xl p-2 border border-[#E0A938]/20">
                        <span class="block text-2xl font-bold text-[#E0A938] font-mono" x-text="countdown.seconds">00</span>
                        <span class="text-[10px] uppercase text-[#D7B5B9]">Detik</span>
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
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full text-xs font-sans-ui font-semibold bg-[#4A0A12] hover:bg-[#5E0D17] text-[#FAF5E6] border border-[#E0A938]/30 transition active:scale-95">
                    <svg class="w-4 h-4 text-[#E0A938]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Simpan ke Kalender</span>
                </a>
            </div>
        </section>

        {{-- 3. PROFIL SECTION --}}
        <section id="profil" class="px-6 py-12 border-t border-[#E0A938]/20 reveal-on-scroll">
            <div class="text-center max-w-sm mx-auto mb-8">
                <p class="text-sm font-palembang-title text-[#E0A938] mb-2 font-semibold">
                    {{ $invitation->event_data['greeting'] ?? 'Assalamu’alaikum Warahmatullahi Wabarakatuh' }}
                </p>
                <blockquote class="text-xs text-[#F3D58C] leading-relaxed italic border-y border-[#E0A938]/30 py-3 my-4">
                    "{{ $invitation->event_data['quote'] ?? 'Dengan memohon rahmat dan ridho Allah SWT, kami bermaksud menyelenggarakan acara pernikahan putra-putri kami tercinta.' }}"
                </blockquote>
            </div>

            <div class="space-y-6">
                <div class="p-5 rounded-2xl bg-[#4A0A12]/50 border border-[#E0A938]/30 text-center">
                    @if(!empty($invitation->event_data['groom_photo']))
                        <div class="w-32 h-40 mx-auto mb-3 rounded-t-full rounded-b-xl overflow-hidden border-2 border-[#E0A938] p-0.5 bg-[#1E0407]">
                            <img src="{{ asset('storage/' . $invitation->event_data['groom_photo']) }}" alt="{{ $invitation->event_data['primary_name'] }}" class="w-full h-full object-cover rounded-t-full rounded-b-lg">
                        </div>
                    @endif
                    <h3 class="font-palembang-title text-2xl font-bold text-[#FAF5E6]">{{ $invitation->event_data['primary_name'] }}</h3>
                    @if(!empty($invitation->event_data['primary_title_parents']))
                        <p class="text-xs text-[#D7B5B9] mt-1 leading-relaxed font-sans-ui">{{ $invitation->event_data['primary_title_parents'] }}</p>
                    @endif
                    @if(!empty($invitation->event_data['primary_instagram']))
                        <a href="https://instagram.com/{{ ltrim($invitation->event_data['primary_instagram'], '@') }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1.5 mt-3 text-xs text-[#E0A938] hover:underline font-sans-ui">
                            <span>@ {{ ltrim($invitation->event_data['primary_instagram'], '@') }}</span>
                        </a>
                    @endif
                </div>

                @if(!empty($invitation->event_data['secondary_name']))
                <div class="text-center text-[#E0A938] font-palembang-title text-xl font-bold">&amp;</div>
                <div class="p-5 rounded-2xl bg-[#4A0A12]/50 border border-[#E0A938]/30 text-center">
                    @if(!empty($invitation->event_data['bride_photo']))
                        <div class="w-32 h-40 mx-auto mb-3 rounded-t-full rounded-b-xl overflow-hidden border-2 border-[#E0A938] p-0.5 bg-[#1E0407]">
                            <img src="{{ asset('storage/' . $invitation->event_data['bride_photo']) }}" alt="{{ $invitation->event_data['secondary_name'] }}" class="w-full h-full object-cover rounded-t-full rounded-b-lg">
                        </div>
                    @endif
                    <h3 class="font-palembang-title text-2xl font-bold text-[#FAF5E6]">{{ $invitation->event_data['secondary_name'] }}</h3>
                    @if(!empty($invitation->event_data['secondary_title_parents']))
                        <p class="text-xs text-[#D7B5B9] mt-1 leading-relaxed font-sans-ui">{{ $invitation->event_data['secondary_title_parents'] }}</p>
                    @endif
                    @if(!empty($invitation->event_data['secondary_instagram']))
                        <a href="https://instagram.com/{{ ltrim($invitation->event_data['secondary_instagram'], '@') }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1.5 mt-3 text-xs text-[#E0A938] hover:underline font-sans-ui">
                            <span>@ {{ ltrim($invitation->event_data['secondary_instagram'], '@') }}</span>
                        </a>
                    @endif
                </div>
                @endif
            </div>
        </section>

        {{-- 4. ACARA --}}
        <section id="acara" class="px-6 py-12 border-t border-[#E0A938]/20 reveal-on-scroll">
            <div class="text-center mb-8">
                <span class="text-xs uppercase tracking-[0.25em] text-[#E0A938] font-palembang-title font-bold">Waktu &amp; Tempat</span>
                <h2 class="font-palembang-title text-2xl sm:text-3xl font-bold text-[#FAF5E6] mt-1">Akad &amp; Munggah</h2>
            </div>

            <div class="space-y-6 font-sans-ui">
                <!-- Sesi 1 -->
                @php $s1 = $invitation->event_data['session_1'] ?? []; @endphp
                <div class="p-6 rounded-2xl bg-[#4A0A12]/60 border border-[#E0A938]/40 relative overflow-hidden">
                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold tracking-wider bg-[#E0A938]/20 text-[#E0A938] mb-3">
                        AKAD NIKAH
                    </span>
                    <h3 class="font-palembang-title text-xl font-bold text-[#FAF5E6] mb-2">{{ $s1['title'] ?? 'Akad Nikah' }}</h3>
                    <div class="space-y-1.5 text-xs text-[#F3D58C]">
                        <p class="flex items-center gap-2">
                            <span class="text-[#E0A938]">✦</span>
                            <span>{{ \Carbon\Carbon::parse($s1['date'] ?? now())->isoFormat('dddd, D MMMM Y') }}</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <span class="text-[#E0A938]">✦</span>
                            <span>{{ $s1['time'] ?? 'Pukul 09.00 WIB' }}</span>
                        </p>
                        <p class="flex items-start gap-2 pt-1">
                            <span class="text-[#E0A938] mt-0.5">✦</span>
                            <span><strong class="text-[#FAF5E6]">{{ $s1['venue'] ?? '' }}</strong><br>{{ $s1['address'] ?? '' }}</span>
                        </p>
                    </div>

                    @if(!empty($s1['maps_url']))
                    <div class="mt-4 pt-4 border-t border-[#E0A938]/20">
                        <a href="{{ $s1['maps_url'] }}" target="_blank" rel="noopener noreferrer"
                           class="w-full min-h-[44px] inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold bg-[#E0A938]/15 hover:bg-[#E0A938]/25 text-[#E0A938] border border-[#E0A938]/30 transition">
                            <span>Petunjuk Lokasi (Google Maps)</span>
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Sesi 2 -->
                @php $s2 = $invitation->event_data['session_2'] ?? []; @endphp
                @if(!empty($s2['enabled']) || !empty($s2['title']))
                <div class="p-6 rounded-2xl bg-[#4A0A12]/60 border border-[#E0A938]/40 relative overflow-hidden">
                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold tracking-wider bg-[#FAF5E6]/10 text-[#FAF5E6] mb-3">
                        MUNGGAH / RESEPSI
                    </span>
                    <h3 class="font-palembang-title text-xl font-bold text-[#FAF5E6] mb-2">{{ $s2['title'] ?? 'Resepsi' }}</h3>
                    <div class="space-y-1.5 text-xs text-[#F3D58C]">
                        <p class="flex items-center gap-2">
                            <span class="text-[#E0A938]">✦</span>
                            <span>{{ \Carbon\Carbon::parse($s2['date'] ?? now())->isoFormat('dddd, D MMMM Y') }}</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <span class="text-[#E0A938]">✦</span>
                            <span>{{ $s2['time'] ?? 'Pukul 11.00 WIB' }}</span>
                        </p>
                        <p class="flex items-start gap-2 pt-1">
                            <span class="text-[#E0A938] mt-0.5">✦</span>
                            <span><strong class="text-[#FAF5E6]">{{ $s2['venue'] ?? '' }}</strong><br>{{ $s2['address'] ?? '' }}</span>
                        </p>
                    </div>

                    @if(!empty($s2['maps_url']))
                    <div class="mt-4 pt-4 border-t border-[#E0A938]/20">
                        <a href="{{ $s2['maps_url'] }}" target="_blank" rel="noopener noreferrer"
                           class="w-full min-h-[44px] inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold bg-[#E0A938]/15 hover:bg-[#E0A938]/25 text-[#E0A938] border border-[#E0A938]/30 transition">
                            <span>Petunjuk Lokasi (Google Maps)</span>
                        </a>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </section>

        {{-- 5. GALERI --}}
        @if(!empty($invitation->event_data['gallery_images']) && count($invitation->event_data['gallery_images']) > 0)
        <section id="galeri" class="px-6 py-12 border-t border-[#E0A938]/20 reveal-on-scroll">
            <div class="text-center mb-8">
                <span class="text-xs uppercase tracking-[0.25em] text-[#E0A938] font-palembang-title font-bold">Dokumentasi</span>
                <h2 class="font-palembang-title text-2xl sm:text-3xl font-bold text-[#FAF5E6] mt-1">Galeri Kenangan</h2>
            </div>

            <div class="grid grid-cols-2 gap-3">
                @foreach($invitation->event_data['gallery_images'] as $photo)
                    <div class="aspect-square rounded-2xl overflow-hidden bg-[#1E0407] border border-[#E0A938]/30 cursor-pointer group shadow-lg p-1"
                         @click="openLightbox('{{ asset('storage/' . $photo) }}')">
                        <img src="{{ asset('storage/' . $photo) }}" alt="Foto Galeri" class="w-full h-full object-cover rounded-xl group-hover:scale-105 transition duration-500">
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- 6. KADO DIGITAL --}}
        @if(!empty($invitation->event_data['gift_accounts']) && count($invitation->event_data['gift_accounts']) > 0)
        <section id="kado" class="px-6 py-12 border-t border-[#E0A938]/20 reveal-on-scroll font-sans-ui">
            <div class="text-center mb-8">
                <span class="text-xs uppercase tracking-[0.25em] text-[#E0A938] font-palembang-title font-bold">Tanda Kasih</span>
                <h2 class="font-palembang-title text-2xl sm:text-3xl font-bold text-[#FAF5E6] mt-1">Amplop Digital</h2>
                <p class="text-xs text-[#D7B5B9] mt-2 max-w-xs mx-auto">
                    Doa restu Anda merupakan karunia yang tak ternilai bagi kami:
                </p>
            </div>

            <div class="space-y-4">
                @foreach($invitation->event_data['gift_accounts'] as $index => $acc)
                <div class="p-5 rounded-2xl bg-[#4A0A12]/60 border border-[#E0A938]/40 shadow-lg">
                    <span class="text-xs font-bold tracking-wider text-[#E0A938] uppercase">{{ $acc['bank'] }}</span>
                    <div class="font-mono text-lg font-bold text-[#FAF5E6] tracking-wider my-1">
                        {{ $acc['account_number'] }}
                    </div>
                    <p class="text-xs text-[#D7B5B9] mb-4">a.n {{ $acc['account_name'] }}</p>

                    <button type="button" data-copy-target="{{ $acc['account_number'] }}"
                            class="btn-copy w-full min-h-[44px] px-4 py-2 rounded-xl text-xs font-semibold bg-[#E0A938]/15 hover:bg-[#E0A938]/25 text-[#E0A938] border border-[#E0A938]/30 transition active:scale-95">
                        <span x-text="copiedIndex === {{ $index }} ? 'Nomor Tersalin!' : 'Salin Nomor Rekening'">Salin Nomor Rekening</span>
                    </button>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- 7. RSVP & UCAPAN --}}
        <section id="ucapan" class="px-6 py-12 border-t border-[#E0A938]/20 reveal-on-scroll font-sans-ui">
            <div class="text-center mb-8">
                <span class="text-xs uppercase tracking-[0.25em] text-[#E0A938] font-palembang-title font-bold">Buku Tamu</span>
                <h2 class="font-palembang-title text-2xl sm:text-3xl font-bold text-[#FAF5E6] mt-1">Kato Sambuik &amp; Doa</h2>
                <div class="flex items-center justify-center gap-4 mt-3 text-xs">
                    <span class="px-3 py-1 rounded-full bg-[#E0A938]/20 text-[#E0A938] border border-[#E0A938]/40 font-medium">
                        <strong x-text="counts.hadir">0</strong> Hadir
                    </span>
                    <span class="px-3 py-1 rounded-full bg-[#4A0A12] text-[#FAF5E6] font-medium border border-white/10">
                        <strong x-text="counts.total">0</strong> Total Ucapan
                    </span>
                </div>
            </div>

            <!-- Form RSVP AJAX -->
            <form @submit.prevent="submitRsvp('{{ route('invitation.wish', $invitation->slug) }}')" class="p-6 rounded-2xl bg-[#4A0A12]/50 border border-[#E0A938]/30 mb-8 space-y-4">
                <div x-show="rsvpSuccessMsg" x-cloak class="p-3 rounded-xl bg-emerald-900/50 border border-emerald-500/30 text-emerald-300 text-xs text-center font-medium">
                    <span x-text="rsvpSuccessMsg"></span>
                </div>
                <div x-show="rsvpErrorMsg" x-cloak class="p-3 rounded-xl bg-red-900/50 border border-red-500/30 text-red-300 text-xs text-center font-medium">
                    <span x-text="rsvpErrorMsg"></span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#D7B5B9] mb-1">Nama Lengkap</label>
                    <input type="text" x-model="rsvpForm.guest_name" required placeholder="Contoh: Nour Haqiki"
                           class="w-full px-4 py-2.5 rounded-xl bg-[#1E0407] border border-[#E0A938]/30 text-[#FAF5E6] text-xs focus:ring-2 focus:ring-[#E0A938] focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#D7B5B9] mb-1">Konfirmasi Kehadiran</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl border border-[#E0A938]/30 text-xs font-medium cursor-pointer transition"
                               :class="rsvpForm.attendance === 'hadir' ? 'bg-[#E0A938]/20 border-[#E0A938] text-[#E0A938]' : 'bg-[#1E0407] text-[#D7B5B9]'">
                            <input type="radio" value="hadir" x-model="rsvpForm.attendance" class="sr-only">
                            <span>Berkenan Hadir</span>
                        </label>
                        <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl border border-[#E0A938]/30 text-xs font-medium cursor-pointer transition"
                               :class="rsvpForm.attendance === 'tidak_hadir' ? 'bg-[#E0A938]/20 border-[#E0A938] text-[#E0A938]' : 'bg-[#1E0407] text-[#D7B5B9]'">
                            <input type="radio" value="tidak_hadir" x-model="rsvpForm.attendance" class="sr-only">
                            <span>Belum Dapat Hadir</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#D7B5B9] mb-1">Doa Restu &amp; Ucapan</label>
                    <textarea x-model="rsvpForm.message" rows="3" required placeholder="Sampaikan doa dan ucapan selamat Anda..."
                              class="w-full px-4 py-2.5 rounded-xl bg-[#1E0407] border border-[#E0A938]/30 text-[#FAF5E6] text-xs focus:ring-2 focus:ring-[#E0A938] focus:outline-none"></textarea>
                </div>

                <button type="submit" 
                        :disabled="rsvpSubmitting"
                        class="w-full min-h-[44px] py-3 rounded-xl font-palembang-title font-bold text-xs tracking-wider uppercase bg-[#E0A938] text-[#2B050B] hover:bg-[#F3D58C] active:scale-95 transition disabled:opacity-50 flex items-center justify-center gap-2">
                    <span x-show="!rsvpSubmitting">Kirim Doa Restu</span>
                    <span x-show="rsvpSubmitting" x-cloak>Mengirimkan...</span>
                </button>
            </form>

            <!-- Daftar Ucapan Live -->
            <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
                <template x-for="wish in wishes" :key="wish.id">
                    <div class="p-4 rounded-xl bg-[#4A0A12]/40 border border-[#E0A938]/20 text-xs">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="font-bold text-[#FAF5E6] text-sm" x-text="wish.guest_name"></span>
                            <span :class="wish.attendance === 'hadir' ? 'bg-[#E0A938]/20 text-[#E0A938]' : 'bg-[#1E0407] text-[#D7B5B9]'"
                                  class="px-2 py-0.5 rounded-full text-[10px] font-semibold"
                                  x-text="wish.attendance === 'hadir' ? 'Hadir' : 'Belum Hadir'"></span>
                        </div>
                        <p class="text-[#F3D58C] leading-relaxed" x-text="wish.message"></p>
                        <span class="block text-[10px] text-[#A67E84] mt-2" x-text="wish.time_ago"></span>
                    </div>
                </template>
                <div x-show="wishes.length === 0" class="text-center py-6 text-xs text-[#A67E84] italic">
                    Belum ada ucapan doa. Jadilah yang pertama memberikan doa restu!
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="text-center py-8 text-[11px] text-[#A67E84] border-t border-[#E0A938]/20">
            <p>{{ $invitation->title }}</p>
            <p class="mt-1">Terima Kasih atas Kehadiran &amp; Doa Restu Bapak/Ibu/Saudara/i</p>
        </footer>
    </main>

    @include('themes.partials.engine')
    @include('themes.partials.butterflies')
</body>
</html>
