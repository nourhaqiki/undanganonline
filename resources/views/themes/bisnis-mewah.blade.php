<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $invitation->title }} | VIP Executive Invitation</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Cinzel & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS v4 & Alpine.js CDN -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .font-luxury-title { font-family: 'Cinzel', serif; }
        .font-luxury-body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
    </style>
</head>
@php $eventType = match ($invitation->category) { 'pernikahan' => 'wedding', 'tunangan' => 'engagement', default => $invitation->category }; @endphp
<body data-event-type="{{ $eventType }}" class="font-luxury-body bg-[#070B14] text-slate-100 overflow-x-hidden overflow-hidden h-screen antialiased selection:bg-[#D4AF37] selection:text-[#070B14]"
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
         class="motion-cover fixed inset-0 z-50 h-dvh w-full overflow-hidden flex flex-col justify-between items-center text-center p-6 bg-gradient-to-b from-[#0F172A] via-[#070B14] to-black border-b border-[#D4AF37]/30">
        
        <!-- Badge Eksekutif VIP Atas -->
        <div class="pt-6 flex flex-col items-center">
            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-[10px] font-luxury-title tracking-[0.3em] uppercase bg-[#D4AF37]/10 text-[#D4AF37] border border-[#D4AF37]/30 font-bold">
                Exclusive VIP Pass
            </span>
        </div>

        <!-- Judul & Penyelenggara -->
        <div class="max-w-md w-full my-auto space-y-4">
            <p class="text-xs tracking-[0.25em] uppercase text-slate-400 font-semibold">{{ $invitation->title }}</p>
            <h1 class="font-luxury-title text-3xl sm:text-4xl font-bold tracking-wider text-white leading-tight">
                {{ $invitation->event_data['primary_name'] ?? 'Executive Event' }}
                @if(!empty($invitation->event_data['secondary_name']))
                    <span class="block text-xl text-[#D4AF37] font-normal my-1">&amp;</span>
                    {{ $invitation->event_data['secondary_name'] }}
                @endif
            </h1>

            <!-- Kotak Tamu Kehormatan (?to=) -->
            <div class="mt-6 p-4 rounded-2xl bg-white/5 border border-[#D4AF37]/30 shadow-2xl max-w-xs mx-auto backdrop-blur-md">
                <p class="text-xs text-slate-400 font-medium">Kepada Tamu Kehormatan:</p>
                <p class="text-base font-bold text-[#D4AF37] mt-1 truncate">
                    {{ !empty($guestName) ? $guestName : 'Distinguished Guest' }}
                </p>
                @if(!empty($guestAddress))
                    <p class="text-xs text-slate-300 mt-1 truncate">
                        di {{ $guestAddress }}
                    </p>
                @endif
            </div>
        </div>

        <!-- Tombol Akses Undangan -->
        <div class="pb-8 w-full max-w-xs">
            <button @click="openInvitation()" 
                    type="button"
                    class="w-full min-h-[48px] px-6 py-3 rounded-full font-luxury-title font-bold text-xs tracking-widest uppercase bg-gradient-to-r from-[#D4AF37] to-[#B38F22] text-[#070B14] shadow-lg shadow-[#D4AF37]/20 hover:from-[#E5C358] hover:to-[#D4AF37] active:scale-95 transition-all flex items-center justify-center gap-2.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
                <span>Akses Undangan VIP</span>
            </button>
        </div>
    </div>

    {{-- ==================== MAIN CONTENT UNDANGAN ==================== --}}
    <main class="relative max-w-lg mx-auto bg-[#0C1222] border-x border-slate-800 shadow-2xl min-h-screen pb-24">

        {{-- 2. HERO SECTION --}}
        <section id="hero" class="relative text-center px-6 pt-12 pb-16 overflow-hidden">
            @if(!empty($invitation->event_data['cover_image']))
                <div class="w-44 h-44 sm:w-52 sm:h-52 mx-auto rounded-3xl overflow-hidden p-1.5 bg-gradient-to-tr from-[#D4AF37] via-slate-700 to-[#D4AF37] shadow-2xl mb-6">
                    <img src="{{ asset('storage/' . $invitation->event_data['cover_image']) }}" alt="{{ $invitation->title }}" class="invitation-photo-oval w-full h-full object-cover rounded-2xl">
                </div>
            @endif

            <p class="text-xs uppercase tracking-[0.25em] text-[#D4AF37] font-luxury-title mb-2 font-bold">{{ $invitation->title }}</p>
            <h1 class="font-luxury-title text-3xl sm:text-4xl font-bold text-white mb-3">
                {{ $invitation->event_data['primary_name'] }}
                @if(!empty($invitation->event_data['secondary_name']))
                    <span class="text-[#D4AF37] text-xl block my-1">&amp;</span>
                    {{ $invitation->event_data['secondary_name'] }}
                @endif
            </h1>

            <p class="text-sm text-slate-300 font-medium mt-3">
                {{ \Carbon\Carbon::parse($invitation->event_data['session_1']['date'] ?? now())->isoFormat('dddd, D MMMM Y') }}
            </p>

            <!-- Countdown Timer -->
            <div class="mt-8 p-4 rounded-2xl bg-white/5 border border-[#D4AF37]/30 max-w-xs mx-auto">
                <p class="text-xs uppercase tracking-wider text-[#D4AF37] font-luxury-title mb-3 font-bold">Hitung Mundur Acara</p>
                <div class="grid grid-cols-4 gap-2 text-center">
                    <div class="bg-[#070B14] rounded-xl p-2 border border-slate-800">
                        <span class="block text-2xl font-bold text-[#D4AF37] font-mono" x-text="countdown.days">00</span>
                        <span class="text-[10px] uppercase text-slate-400">Hari</span>
                    </div>
                    <div class="bg-[#070B14] rounded-xl p-2 border border-slate-800">
                        <span class="block text-2xl font-bold text-[#D4AF37] font-mono" x-text="countdown.hours">00</span>
                        <span class="text-[10px] uppercase text-slate-400">Jam</span>
                    </div>
                    <div class="bg-[#070B14] rounded-xl p-2 border border-slate-800">
                        <span class="block text-2xl font-bold text-[#D4AF37] font-mono" x-text="countdown.minutes">00</span>
                        <span class="text-[10px] uppercase text-slate-400">Menit</span>
                    </div>
                    <div class="bg-[#070B14] rounded-xl p-2 border border-slate-800">
                        <span class="block text-2xl font-bold text-[#D4AF37] font-mono" x-text="countdown.seconds">00</span>
                        <span class="text-[10px] uppercase text-slate-400">Detik</span>
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
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full text-xs font-semibold bg-white/10 hover:bg-white/15 text-white border border-[#D4AF37]/30 transition active:scale-95">
                    <svg class="w-4 h-4 text-[#D4AF37]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Simpan ke Kalender Kerja</span>
                </a>
            </div>
        </section>

        {{-- 3. PROFIL SECTION --}}
        <section id="profil" class="px-6 py-12 border-t border-slate-800 reveal-on-scroll">
            <div class="text-center max-w-sm mx-auto mb-8">
                <p class="text-sm font-luxury-title text-[#D4AF37] mb-2 font-bold tracking-wider">
                    {{ $invitation->event_data['greeting'] ?? 'Dengan Hormat,' }}
                </p>
                @if(!empty($invitation->event_data['quote']))
                <blockquote class="text-xs text-slate-300 leading-relaxed italic border-l-2 border-[#D4AF37] pl-3 py-1 my-4 text-left">
                    "{{ $invitation->event_data['quote'] }}"
                </blockquote>
                @endif
            </div>

            <div class="space-y-6">
                <div class="p-5 rounded-2xl bg-white/5 border border-slate-800 text-center">
                    @if(!empty($invitation->event_data['groom_photo']))
                        <div class="w-28 h-28 mx-auto mb-3 rounded-full overflow-hidden border-2 border-[#D4AF37] p-0.5">
                            <img src="{{ asset('storage/' . $invitation->event_data['groom_photo']) }}" alt="{{ $invitation->event_data['primary_name'] }}" class="w-full h-full object-cover rounded-full">
                        </div>
                    @endif
                    <h3 class="font-luxury-title text-xl font-bold text-white">{{ $invitation->event_data['primary_name'] }}</h3>
                    @if(!empty($invitation->event_data['primary_title_parents']))
                        <p class="text-xs text-slate-400 mt-1 leading-relaxed">{{ $invitation->event_data['primary_title_parents'] }}</p>
                    @endif
                    @if(!empty($invitation->event_data['primary_instagram']))
                        <a href="https://instagram.com/{{ ltrim($invitation->event_data['primary_instagram'], '@') }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1.5 mt-3 text-xs text-[#D4AF37] hover:underline">
                            <span>@ {{ ltrim($invitation->event_data['primary_instagram'], '@') }}</span>
                        </a>
                    @endif
                </div>

                @if(!empty($invitation->event_data['secondary_name']))
                <div class="p-5 rounded-2xl bg-white/5 border border-slate-800 text-center">
                    @if(!empty($invitation->event_data['bride_photo']))
                        <div class="w-28 h-28 mx-auto mb-3 rounded-full overflow-hidden border-2 border-[#D4AF37] p-0.5">
                            <img src="{{ asset('storage/' . $invitation->event_data['bride_photo']) }}" alt="{{ $invitation->event_data['secondary_name'] }}" class="w-full h-full object-cover rounded-full">
                        </div>
                    @endif
                    <h3 class="font-luxury-title text-xl font-bold text-white">{{ $invitation->event_data['secondary_name'] }}</h3>
                    @if(!empty($invitation->event_data['secondary_title_parents']))
                        <p class="text-xs text-slate-400 mt-1 leading-relaxed">{{ $invitation->event_data['secondary_title_parents'] }}</p>
                    @endif
                    @if(!empty($invitation->event_data['secondary_instagram']))
                        <a href="https://instagram.com/{{ ltrim($invitation->event_data['secondary_instagram'], '@') }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1.5 mt-3 text-xs text-[#D4AF37] hover:underline">
                            <span>@ {{ ltrim($invitation->event_data['secondary_instagram'], '@') }}</span>
                        </a>
                    @endif
                </div>
                @endif
            </div>
        </section>

        {{-- 4. ACARA & AGENDA --}}
        <section id="acara" class="px-6 py-12 border-t border-slate-800 reveal-on-scroll">
            <div class="text-center mb-8">
                <span class="text-xs uppercase tracking-[0.25em] text-[#D4AF37] font-luxury-title font-bold">Waktu &amp; Tempat</span>
                <h2 class="font-luxury-title text-2xl sm:text-3xl font-bold text-white mt-1">Agenda Acara</h2>
            </div>

            <div class="space-y-6">
                <!-- Sesi 1 -->
                @php $s1 = $invitation->event_data['session_1'] ?? []; @endphp
                <div class="p-6 rounded-2xl bg-white/5 border border-slate-800 relative overflow-hidden">
                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold tracking-wider bg-[#D4AF37]/20 text-[#D4AF37] mb-3">
                        AGENDA UTAMA
                    </span>
                    <h3 class="font-luxury-title text-xl font-bold text-white mb-2">{{ $s1['title'] ?? 'Main Session' }}</h3>
                    <div class="space-y-1.5 text-xs text-slate-300">
                        <p class="flex items-center gap-2">
                            <span class="text-[#D4AF37]">✦</span>
                            <span>{{ \Carbon\Carbon::parse($s1['date'] ?? now())->isoFormat('dddd, D MMMM Y') }}</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <span class="text-[#D4AF37]">✦</span>
                            <span>{{ $s1['time'] ?? '09:00 WIB' }}</span>
                        </p>
                        <p class="flex items-start gap-2 pt-1">
                            <span class="text-[#D4AF37] mt-0.5">✦</span>
                            <span><strong class="text-white">{{ $s1['venue'] ?? '' }}</strong><br>{{ $s1['address'] ?? '' }}</span>
                        </p>
                    </div>

                    @if(!empty($s1['maps_url']))
                    <div class="mt-4 pt-4 border-t border-slate-800">
                        <a href="{{ $s1['maps_url'] }}" target="_blank" rel="noopener noreferrer"
                           class="w-full min-h-[44px] inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold bg-[#D4AF37]/15 hover:bg-[#D4AF37]/25 text-[#D4AF37] border border-[#D4AF37]/30 transition">
                            <span>Petunjuk Lokasi (Google Maps)</span>
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Sesi 2 -->
                @php $s2 = $invitation->event_data['session_2'] ?? []; @endphp
                @if(!empty($s2['enabled']) || !empty($s2['title']))
                <div class="p-6 rounded-2xl bg-white/5 border border-slate-800 relative overflow-hidden">
                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold tracking-wider bg-white/10 text-slate-300 mb-3">
                        GALA &amp; NETWORKING
                    </span>
                    <h3 class="font-luxury-title text-xl font-bold text-white mb-2">{{ $s2['title'] ?? 'Networking Session' }}</h3>
                    <div class="space-y-1.5 text-xs text-slate-300">
                        <p class="flex items-center gap-2">
                            <span class="text-[#D4AF37]">✦</span>
                            <span>{{ \Carbon\Carbon::parse($s2['date'] ?? now())->isoFormat('dddd, D MMMM Y') }}</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <span class="text-[#D4AF37]">✦</span>
                            <span>{{ $s2['time'] ?? '13:00 WIB' }}</span>
                        </p>
                        <p class="flex items-start gap-2 pt-1">
                            <span class="text-[#D4AF37] mt-0.5">✦</span>
                            <span><strong class="text-white">{{ $s2['venue'] ?? '' }}</strong><br>{{ $s2['address'] ?? '' }}</span>
                        </p>
                    </div>

                    @if(!empty($s2['maps_url']))
                    <div class="mt-4 pt-4 border-t border-slate-800">
                        <a href="{{ $s2['maps_url'] }}" target="_blank" rel="noopener noreferrer"
                           class="w-full min-h-[44px] inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold bg-[#D4AF37]/15 hover:bg-[#D4AF37]/25 text-[#D4AF37] border border-[#D4AF37]/30 transition">
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
        <section id="galeri" class="px-6 py-12 border-t border-slate-800 reveal-on-scroll">
            <div class="text-center mb-8">
                <span class="text-xs uppercase tracking-[0.25em] text-[#D4AF37] font-luxury-title font-bold">Dokumentasi</span>
                <h2 class="font-luxury-title text-2xl sm:text-3xl font-bold text-white mt-1">Galeri Acara</h2>
            </div>

            <div class="grid grid-cols-2 gap-3">
                @foreach($invitation->event_data['gallery_images'] as $photo)
                    <div class="aspect-square rounded-2xl overflow-hidden bg-slate-900 border border-slate-800 cursor-pointer group shadow-lg"
                         @click="openLightbox('{{ asset('storage/' . $photo) }}')">
                        <img src="{{ asset('storage/' . $photo) }}" alt="Foto Galeri" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- 6. KADO / REKENING DUKUNGAN (JIKA ADA) --}}
        @if(!empty($invitation->event_data['gift_accounts']) && count($invitation->event_data['gift_accounts']) > 0)
        <section id="kado" class="px-6 py-12 border-t border-slate-800 reveal-on-scroll">
            <div class="text-center mb-8">
                <span class="text-xs uppercase tracking-[0.25em] text-[#D4AF37] font-luxury-title font-bold">Dukungan</span>
                <h2 class="font-luxury-title text-2xl sm:text-3xl font-bold text-white mt-1">Rekening Resmi</h2>
            </div>

            <div class="space-y-4">
                @foreach($invitation->event_data['gift_accounts'] as $index => $acc)
                <div class="p-5 rounded-2xl bg-white/5 border border-slate-800 shadow-lg">
                    <span class="text-xs font-bold tracking-wider text-[#D4AF37] uppercase">{{ $acc['bank'] }}</span>
                    <div class="font-mono text-lg font-bold text-white tracking-wider my-1">
                        {{ $acc['account_number'] }}
                    </div>
                    <p class="text-xs text-slate-400 mb-4">a.n {{ $acc['account_name'] }}</p>

                    <button type="button" data-copy-target="{{ $acc['account_number'] }}"
                            class="btn-copy w-full min-h-[44px] px-4 py-2 rounded-xl text-xs font-semibold bg-white/10 hover:bg-white/15 text-white border border-slate-700 transition active:scale-95">
                        <span x-text="copiedIndex === {{ $index }} ? 'Nomor Tersalin!' : 'Salin Nomor Rekening'">Salin Nomor Rekening</span>
                    </button>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- 7. RSVP & BUKU TAMU --}}
        <section id="ucapan" class="px-6 py-12 border-t border-slate-800 reveal-on-scroll">
            <div class="text-center mb-8">
                <span class="text-xs uppercase tracking-[0.25em] text-[#D4AF37] font-luxury-title font-bold">Konfirmasi Delegasi</span>
                <h2 class="font-luxury-title text-2xl sm:text-3xl font-bold text-white mt-1">RSVP Kehadiran</h2>
                <div class="flex items-center justify-center gap-4 mt-3 text-xs">
                    <span class="px-3 py-1 rounded-full bg-[#D4AF37]/20 text-[#D4AF37] border border-[#D4AF37]/40 font-medium">
                        <strong x-text="counts.hadir">0</strong> Konfirmasi Hadir
                    </span>
                    <span class="px-3 py-1 rounded-full bg-slate-800 text-slate-300 font-medium border border-slate-700">
                        <strong x-text="counts.total">0</strong> Total Pesan
                    </span>
                </div>
            </div>

            <!-- Form RSVP AJAX -->
            <form @submit.prevent="submitRsvp('{{ route('invitation.wish', $invitation->slug) }}')" class="p-6 rounded-2xl bg-white/5 border border-slate-800 mb-8 space-y-4">
                <div x-show="rsvpSuccessMsg" x-cloak class="p-3 rounded-xl bg-emerald-950/60 border border-emerald-500/40 text-emerald-300 text-xs text-center font-medium">
                    <span x-text="rsvpSuccessMsg"></span>
                </div>
                <div x-show="rsvpErrorMsg" x-cloak class="p-3 rounded-xl bg-red-950/60 border border-red-500/40 text-red-300 text-xs text-center font-medium">
                    <span x-text="rsvpErrorMsg"></span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Delegasi / Tamu</label>
                    <input type="text" x-model="rsvpForm.guest_name" required placeholder="Contoh: Nour Haqiki"
                           class="w-full px-4 py-2.5 rounded-xl bg-[#070B14] border border-slate-700 text-white text-xs focus:ring-2 focus:ring-[#D4AF37] focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Status Kehadiran</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl border border-slate-700 text-xs font-medium cursor-pointer transition"
                               :class="rsvpForm.attendance === 'hadir' ? 'bg-[#D4AF37]/20 border-[#D4AF37] text-[#D4AF37]' : 'bg-[#070B14] text-slate-400'">
                            <input type="radio" value="hadir" x-model="rsvpForm.attendance" class="sr-only">
                            <span>Konfirmasi Hadir</span>
                        </label>
                        <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl border border-slate-700 text-xs font-medium cursor-pointer transition"
                               :class="rsvpForm.attendance === 'tidak_hadir' ? 'bg-[#D4AF37]/20 border-[#D4AF37] text-[#D4AF37]' : 'bg-[#070B14] text-slate-400'">
                            <input type="radio" value="tidak_hadir" x-model="rsvpForm.attendance" class="sr-only">
                            <span>Berhalangan</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Catatan &amp; Salam Singkat</label>
                    <textarea x-model="rsvpForm.message" rows="3" required placeholder="Tuliskan ucapan atau konfirmasi instansi Anda..."
                              class="w-full px-4 py-2.5 rounded-xl bg-[#070B14] border border-slate-700 text-white text-xs focus:ring-2 focus:ring-[#D4AF37] focus:outline-none"></textarea>
                </div>

                <button type="submit" 
                        :disabled="rsvpSubmitting"
                        class="w-full min-h-[44px] py-3 rounded-xl font-luxury-title font-bold text-xs tracking-wider uppercase bg-[#D4AF37] text-[#070B14] hover:bg-[#E5C358] active:scale-95 transition disabled:opacity-50 flex items-center justify-center gap-2">
                    <span x-show="!rsvpSubmitting">Submit Konfirmasi RSVP</span>
                    <span x-show="rsvpSubmitting" x-cloak>Memproses...</span>
                </button>
            </form>

            <!-- Daftar Pesan Live -->
            <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
                <template x-for="wish in wishes" :key="wish.id">
                    <div class="p-4 rounded-xl bg-white/5 border border-slate-800 text-xs">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="font-bold text-white text-sm" x-text="wish.guest_name"></span>
                            <span :class="wish.attendance === 'hadir' ? 'bg-[#D4AF37]/20 text-[#D4AF37]' : 'bg-slate-800 text-slate-400'"
                                  class="px-2 py-0.5 rounded-full text-[10px] font-semibold"
                                  x-text="wish.attendance === 'hadir' ? 'Hadir' : 'Berhalangan'"></span>
                        </div>
                        <p class="text-slate-300 leading-relaxed" x-text="wish.message"></p>
                        <span class="block text-[10px] text-slate-500 mt-2" x-text="wish.time_ago"></span>
                    </div>
                </template>
                <div x-show="wishes.length === 0" class="text-center py-6 text-xs text-slate-500 italic">
                    Belum ada konfirmasi yang tercatat.
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="text-center py-8 text-[11px] text-slate-500 border-t border-slate-800">
            <p>{{ $invitation->title }}</p>
            <p class="mt-1">Executive Invitation System</p>
        </footer>
    </main>

    @include('themes.partials.engine')
</body>
</html>
