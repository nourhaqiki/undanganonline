<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Generator Undangan Online Modern &amp; Elegan</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS v4 & Alpine.js CDN -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .font-brand { font-family: 'Playfair Display', Georgia, serif; }
        .font-sans-main { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
    </style>
</head>
<body class="font-sans-main bg-stone-950 text-stone-100 overflow-x-hidden antialiased selection:bg-amber-400 selection:text-stone-950">

    <!-- Header / Navbar Ringkas -->
    <header class="border-b border-white/10 bg-stone-950/80 backdrop-blur-md sticky top-0 z-40">
        <div class="max-w-5xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                <span class="w-9 h-9 rounded-xl bg-gradient-to-tr from-amber-400 to-amber-600 flex items-center justify-center text-stone-950 font-bold text-lg font-brand shadow-md">
                    U
                </span>
                <span class="font-brand font-bold text-lg text-white tracking-wide">
                    Undangan<span class="text-amber-400">Online</span>
                </span>
            </a>

            <div class="flex items-center gap-3">
                <a href="#builder" class="min-h-[44px] inline-flex items-center justify-center px-4 py-2 rounded-full text-xs font-semibold bg-amber-400 hover:bg-amber-300 text-stone-950 transition active:scale-95 shadow-md shadow-amber-400/20">
                    Buat Undangan
                </a>
                <a href="{{ route('admin.login') }}" class="min-h-[44px] inline-flex items-center justify-center px-3 py-2 text-xs font-medium text-stone-400 hover:text-white transition">
                    Admin
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative pt-12 pb-16 px-4 text-center overflow-hidden">
        <div class="max-w-3xl mx-auto space-y-5">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold tracking-wider uppercase bg-amber-400/10 text-amber-300 border border-amber-400/30">
                ✨ Platform Generator Undangan Digital Modern
            </span>
            <h1 class="font-brand text-3xl sm:text-5xl font-bold tracking-tight text-white leading-tight">
                Buat Undangan Online Elegan <br class="hidden sm:inline">
                <span class="bg-gradient-to-r from-amber-300 via-amber-400 to-amber-500 bg-clip-text text-transparent">
                    Hanya Dalam Hitungan Menit
                </span>
            </h1>
            <p class="text-sm sm:text-base text-stone-400 max-w-xl mx-auto leading-relaxed">
                Tersedia beragam tema adat tradisional dan gaya bisnis eksklusif. Dilengkapi fitur interaktif: layar sampul terkunci, live RSVP, musik pengiring, dan amplop digital.
            </p>

            <div class="pt-3 flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="#builder" class="w-full sm:w-auto min-h-[48px] px-8 py-3.5 rounded-full font-bold text-sm bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-stone-950 shadow-xl shadow-amber-400/20 active:scale-95 transition-all flex items-center justify-center gap-2">
                    <span>Mulai Buat Undangan Gratis</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- 4 Keunggulan Fitur Grid -->
        <div class="max-w-4xl mx-auto mt-16 grid grid-cols-2 md:grid-cols-4 gap-4 text-left">
            <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
                <span class="text-2xl">📱</span>
                <h2 class="font-bold text-sm text-white mt-2">100% Mobile-First</h2>
                <p class="text-xs text-stone-400 mt-1">Optimal di segala tipe smartphone tanpa horizontal scroll.</p>
            </div>
            <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
                <span class="text-2xl">🎭</span>
                <h2 class="font-bold text-sm text-white mt-2">Tema Adat &amp; Bisnis</h2>
                <p class="text-xs text-stone-400 mt-1">Pilihan tema Jawa, Sunda, Palembang, hingga Bisnis VIP.</p>
            </div>
            <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
                <span class="text-2xl">💬</span>
                <h2 class="font-bold text-sm text-white mt-2">RSVP &amp; Buku Tamu</h2>
                <p class="text-xs text-stone-400 mt-1">Konfirmasi kehadiran real-time tanpa reload halaman.</p>
            </div>
            <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
                <span class="text-2xl">🎁</span>
                <h2 class="font-bold text-sm text-white mt-2">Amplop &amp; Musik</h2>
                <p class="text-xs text-stone-400 mt-1">1-Klik salin no rekening dan pemutar musik latar otomatis.</p>
            </div>
        </div>
    </section>

    <!-- Multi-Step Builder Form Section -->
    <section id="builder" class="py-16 px-4 bg-stone-900 border-t border-white/10">
        <div class="max-w-2xl mx-auto"
             x-data="{
                 step: {{ (isset($errors) && $errors->any()) ? (old('step', 1)) : 1 }},
                 category: '{{ old('category', 'pernikahan') }}',
                 selectedTheme: '{{ old('theme_slug', 'jawa') }}',
                 hasSession2: {{ old('session_2_title') ? 'true' : 'false' }},
                 musicMode: '{{ old('music_mode', 'ambient') }}',
                 giftAccounts: {{ old('gift_accounts') ? json_encode(old('gift_accounts')) : json_encode([['bank' => 'BCA', 'account_number' => '', 'account_name' => '']]) }},
                 
                 addGiftAccount() {
                     this.giftAccounts.push({ bank: '', account_number: '', account_name: '' });
                 },
                 removeGiftAccount(index) {
                     if (this.giftAccounts.length > 1) {
                         this.giftAccounts.splice(index, 1);
                     }
                 },
                 nextStep() {
                     if (this.step < 4) this.step++;
                 },
                 prevStep() {
                     if (this.step > 1) this.step--;
                 }
             }">

            <div class="text-center mb-8">
                <span class="text-xs font-bold uppercase tracking-widest text-amber-400">Online Builder</span>
                <h2 class="font-brand text-2xl sm:text-3xl font-bold text-white mt-1">Rancang Undangan Anda</h2>
                <p class="text-xs text-stone-400 mt-1">Lengkapi 4 langkah sederhana di bawah ini untuk menerbitkan undangan</p>
            </div>

            <!-- Error Banner jika ada validasi error -->
            @if(isset($errors) && $errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-red-950/70 border border-red-500/40 text-red-200 text-xs">
                <strong class="font-bold text-red-300 block mb-1">Terdapat kesalahan pengisian form:</strong>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Step Indicator Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center justify-between relative">
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-stone-800 w-full z-0"></div>
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-amber-400 z-0 transition-all duration-300"
                         :style="'width: ' + ((step - 1) / 3 * 100) + '%'"></div>

                    <!-- Step 1 -->
                    <div class="relative z-10 flex flex-col items-center">
                        <button type="button" @click="step = 1"
                                :class="step >= 1 ? 'bg-amber-400 text-stone-950 font-bold' : 'bg-stone-800 text-stone-400'"
                                class="w-8 h-8 rounded-full flex items-center justify-center text-xs transition">1</button>
                        <span class="text-[10px] mt-1 font-medium text-stone-400 hidden sm:block">Tema</span>
                    </div>
                    <!-- Step 2 -->
                    <div class="relative z-10 flex flex-col items-center">
                        <button type="button" @click="step = 2"
                                :class="step >= 2 ? 'bg-amber-400 text-stone-950 font-bold' : 'bg-stone-800 text-stone-400'"
                                class="w-8 h-8 rounded-full flex items-center justify-center text-xs transition">2</button>
                        <span class="text-[10px] mt-1 font-medium text-stone-400 hidden sm:block">Profil</span>
                    </div>
                    <!-- Step 3 -->
                    <div class="relative z-10 flex flex-col items-center">
                        <button type="button" @click="step = 3"
                                :class="step >= 3 ? 'bg-amber-400 text-stone-950 font-bold' : 'bg-stone-800 text-stone-400'"
                                class="w-8 h-8 rounded-full flex items-center justify-center text-xs transition">3</button>
                        <span class="text-[10px] mt-1 font-medium text-stone-400 hidden sm:block">Acara</span>
                    </div>
                    <!-- Step 4 -->
                    <div class="relative z-10 flex flex-col items-center">
                        <button type="button" @click="step = 4"
                                :class="step >= 4 ? 'bg-amber-400 text-stone-950 font-bold' : 'bg-stone-800 text-stone-400'"
                                class="w-8 h-8 rounded-full flex items-center justify-center text-xs transition">4</button>
                        <span class="text-[10px] mt-1 font-medium text-stone-400 hidden sm:block">Galeri &amp; Kado</span>
                    </div>
                </div>
            </div>

            <!-- Form Builder Multi-Step -->
            <form action="{{ route('invitation.store') }}" method="POST" enctype="multipart/form-data" class="bg-stone-950/60 border border-white/10 rounded-3xl p-6 sm:p-8 shadow-2xl">
                @csrf
                <input type="hidden" name="step" :value="step">

                {{-- ==================== LANGKAH 1: KATEGORI & TEMA ==================== --}}
                <div x-show="step === 1" x-cloak class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-stone-300 uppercase tracking-wider mb-2">1. Pilih Kategori Acara</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                            @foreach(['pernikahan' => 'Pernikahan', 'tunangan' => 'Tunangan', 'bisnis' => 'Bisnis', 'lainnya' => 'Lainnya'] as $key => $lbl)
                            <label class="flex items-center justify-center p-3 rounded-2xl border text-xs font-semibold cursor-pointer transition text-center"
                                   :class="category === '{{ $key }}' ? 'bg-amber-400/20 border-amber-400 text-amber-300' : 'bg-stone-900 border-white/10 text-stone-400 hover:border-white/20'">
                                <input type="radio" name="category" value="{{ $key }}" x-model="category" class="sr-only">
                                <span>{{ $lbl }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-stone-300 uppercase tracking-wider mb-2">2. Judul Acara Undangan</label>
                        <input type="text" name="title" value="{{ old('title', 'The Wedding of Tasya & Zun') }}" required
                               placeholder="Contoh: The Wedding of Tasya & Zun"
                               class="w-full px-4 py-3 rounded-2xl bg-stone-900 border border-white/10 text-white text-xs focus:ring-2 focus:ring-amber-400 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-stone-300 uppercase tracking-wider mb-2">3. Pilih Desain Tema</label>
                        <p class="text-[11px] text-stone-400 mb-3">Tema di bawah otomatis terfilter berdasarkan kategori yang dipilih.</p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            @foreach($themes as $th)
                            <label x-show="category === 'semua' || '{{ $th->category }}' === 'semua' || category === '{{ $th->category }}' || (category === 'tunangan' && '{{ $th->category }}' === 'pernikahan') || (category === 'lainnya')"
                                   class="relative p-4 rounded-2xl border cursor-pointer transition text-left flex flex-col justify-between"
                                   :class="selectedTheme === '{{ $th->slug }}' ? 'bg-amber-400/10 border-amber-400 shadow-md shadow-amber-400/10' : 'bg-stone-900 border-white/10 hover:border-white/20'">
                                <input type="radio" name="theme_slug" value="{{ $th->slug }}" x-model="selectedTheme" class="sr-only" required>
                                
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="w-3.5 h-3.5 rounded-full border border-white/20" style="background-color: {{ $th->accent_color }};"></span>
                                            <strong class="text-xs font-bold text-white">{{ $th->name }}</strong>
                                        </div>
                                        <span class="text-[10px] px-2 py-0.5 rounded-full uppercase font-medium bg-white/5 text-stone-400">
                                            {{ $th->category }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-stone-400 line-clamp-2 leading-relaxed">
                                        {{ $th->description }}
                                    </p>
                                </div>

                                <div class="mt-3 pt-2 border-t border-white/5 flex items-center justify-between text-[11px]">
                                    <span class="text-amber-400 font-semibold" x-show="selectedTheme === '{{ $th->slug }}'">✓ Terpilih</span>
                                    <span class="text-stone-500" x-show="selectedTheme !== '{{ $th->slug }}'">Pilih Tema</span>
                                    <span class="font-mono text-stone-500 text-[10px]">{{ $th->accent_color }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ==================== LANGKAH 2: PROFIL & SAMBUTAN ==================== --}}
                <div x-show="step === 2" x-cloak class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-stone-300 mb-1" x-text="(category === 'bisnis' || category === 'lainnya') ? 'Nama Acara / Penyelenggara' : 'Nama Mempelai 1 (Pria/Wanita)'"></label>
                            <input type="text" name="primary_name" value="{{ old('primary_name', 'Zun') }}" required
                                   class="w-full px-4 py-2.5 rounded-xl bg-stone-900 border border-white/10 text-white text-xs focus:ring-2 focus:ring-amber-400 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-stone-400 mb-1" x-text="(category === 'bisnis' || category === 'lainnya') ? 'Jabatan / Instansi' : 'Nama Lengkap & Orang Tua 1'"></label>
                            <input type="text" name="primary_title_parents" value="{{ old('primary_title_parents', 'Putra dari Bpk. Bambang & Ibu Sri') }}"
                                   class="w-full px-4 py-2.5 rounded-xl bg-stone-900 border border-white/10 text-white text-xs focus:ring-2 focus:ring-amber-400 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-stone-400 mb-1">Instagram Mempelai/Penyelenggara 1 (Opsional)</label>
                        <input type="text" name="primary_instagram" value="{{ old('primary_instagram') }}" placeholder="@username"
                               class="w-full px-4 py-2.5 rounded-xl bg-stone-900 border border-white/10 text-white text-xs focus:ring-2 focus:ring-amber-400 focus:outline-none">
                    </div>

                    <!-- Profil Pasangan / Partner (Bila Ada) -->
                    <div class="pt-4 border-t border-white/10">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-stone-300 mb-1" x-text="(category === 'bisnis' || category === 'lainnya') ? 'Nama Mitra / Pembicara 2 (Opsional)' : 'Nama Mempelai 2'"></label>
                                <input type="text" name="secondary_name" value="{{ old('secondary_name', 'Tasya') }}"
                                       class="w-full px-4 py-2.5 rounded-xl bg-stone-900 border border-white/10 text-white text-xs focus:ring-2 focus:ring-amber-400 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-stone-400 mb-1" x-text="(category === 'bisnis' || category === 'lainnya') ? 'Jabatan / Instansi 2' : 'Nama Lengkap & Orang Tua 2'"></label>
                                <input type="text" name="secondary_title_parents" value="{{ old('secondary_title_parents', 'Putri dari Bpk. Hendra & Ibu Rina') }}"
                                       class="w-full px-4 py-2.5 rounded-xl bg-stone-900 border border-white/10 text-white text-xs focus:ring-2 focus:ring-amber-400 focus:outline-none">
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-xs font-semibold text-stone-400 mb-1">Instagram Mempelai/Partner 2 (Opsional)</label>
                            <input type="text" name="secondary_instagram" value="{{ old('secondary_instagram') }}" placeholder="@username"
                                   class="w-full px-4 py-2.5 rounded-xl bg-stone-900 border border-white/10 text-white text-xs focus:ring-2 focus:ring-amber-400 focus:outline-none">
                        </div>
                    </div>

                    <!-- Salam & Kutipan -->
                    <div class="pt-4 border-t border-white/10 space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-stone-300 mb-1">Salam Pembuka</label>
                            <input type="text" name="greeting" value="{{ old('greeting', 'Assalamu’alaikum Warahmatullahi Wabarakatuh') }}"
                                   class="w-full px-4 py-2.5 rounded-xl bg-stone-900 border border-white/10 text-white text-xs focus:ring-2 focus:ring-amber-400 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-stone-300 mb-1">Kutipan / Doa / Deskripsi Singkat</label>
                            <textarea name="quote" rows="2"
                                      class="w-full px-4 py-2.5 rounded-xl bg-stone-900 border border-white/10 text-white text-xs focus:ring-2 focus:ring-amber-400 focus:outline-none">{{ old('quote', 'Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri.') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- ==================== LANGKAH 3: WAKTU & LOKASI ACARA ==================== --}}
                <div x-show="step === 3" x-cloak class="space-y-6">
                    <!-- Sesi 1 (Wajib) -->
                    <div class="p-5 rounded-2xl bg-stone-900/90 border border-white/10 space-y-4">
                        <div class="flex items-center justify-between">
                            <strong class="text-xs uppercase font-bold text-amber-400 tracking-wider">Sesi 1 (Acara Utama)</strong>
                            <span class="text-[10px] text-stone-400 font-medium">Akad / Acara Pokok</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-semibold text-stone-300 mb-1">Nama Sesi</label>
                                <input type="text" name="session_1_title" value="{{ old('session_1_title', 'Akad Nikah') }}" required
                                       class="w-full px-3 py-2 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-stone-300 mb-1">Tanggal Acara</label>
                                <input type="date" name="session_1_date" value="{{ old('session_1_date', date('Y-m-d', strtotime('+1 month'))) }}" required
                                       class="w-full px-3 py-2 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-semibold text-stone-300 mb-1">Waktu / Jam</label>
                                <input type="text" name="session_1_time" value="{{ old('session_1_time', '09:00 - 11:00 WIB') }}" required
                                       class="w-full px-3 py-2 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-stone-300 mb-1">Nama Gedung / Tempat</label>
                                <input type="text" name="session_1_venue" value="{{ old('session_1_venue', 'Masjid Agung Al-Falah') }}" required
                                       class="w-full px-3 py-2 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-stone-300 mb-1">Alamat Lengkap</label>
                            <input type="text" name="session_1_address" value="{{ old('session_1_address', 'Jl. Jenderal Sudirman No. 45, Jakarta Pusat') }}" required
                                   class="w-full px-3 py-2 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-stone-300 mb-1">Tautan Google Maps</label>
                            <input type="url" name="session_1_maps_url" value="{{ old('session_1_maps_url', 'https://maps.google.com') }}"
                                   class="w-full px-3 py-2 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                        </div>
                    </div>

                    <!-- Toggle Sesi 2 (Opsional) -->
                    <div class="pt-2">
                        <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-semibold text-stone-300">
                            <input type="checkbox" name="session_2_enabled" value="1" x-model="hasSession2" class="w-4 h-4 rounded text-amber-500 bg-stone-900 border-white/20 focus:ring-0">
                            <span>Tambahkan Sesi 2 (Resepsi / Gala Dinner / Ramah Tamah)</span>
                        </label>
                    </div>

                    <!-- Form Sesi 2 -->
                    <div x-show="hasSession2" x-cloak class="p-5 rounded-2xl bg-stone-900/90 border border-white/10 space-y-4">
                        <div class="flex items-center justify-between">
                            <strong class="text-xs uppercase font-bold text-amber-400 tracking-wider">Sesi 2 (Tambahan)</strong>
                            <span class="text-[10px] text-stone-400 font-medium">Resepsi</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-semibold text-stone-300 mb-1">Nama Sesi 2</label>
                                <input type="text" name="session_2_title" value="{{ old('session_2_title', 'Resepsi Pernikahan') }}"
                                       class="w-full px-3 py-2 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-stone-300 mb-1">Tanggal Sesi 2</label>
                                <input type="date" name="session_2_date" value="{{ old('session_2_date', date('Y-m-d', strtotime('+1 month'))) }}"
                                       class="w-full px-3 py-2 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-semibold text-stone-300 mb-1">Waktu Sesi 2</label>
                                <input type="text" name="session_2_time" value="{{ old('session_2_time', '11:00 - 14:00 WIB') }}"
                                       class="w-full px-3 py-2 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-stone-300 mb-1">Gedung / Tempat Sesi 2</label>
                                <input type="text" name="session_2_venue" value="{{ old('session_2_venue', 'Grand Ballroom Hotel Mulia') }}"
                                       class="w-full px-3 py-2 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-stone-300 mb-1">Alamat Lengkap Sesi 2</label>
                            <input type="text" name="session_2_address" value="{{ old('session_2_address', 'Jl. Asia Afrika Senayan, Jakarta Pusat') }}"
                                   class="w-full px-3 py-2 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-stone-300 mb-1">Tautan Google Maps Sesi 2</label>
                            <input type="url" name="session_2_maps_url" value="{{ old('session_2_maps_url', 'https://maps.google.com') }}"
                                   class="w-full px-3 py-2 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                        </div>
                    </div>
                </div>

                {{-- ==================== LANGKAH 4: GALERI, KADO & MUSIK ==================== --}}
                <div x-show="step === 4" x-cloak class="space-y-6">
                    <!-- Upload Sampul & Galeri -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-stone-300 mb-1">Foto Sampul Utama (Cover Image)</label>
                            <input type="file" name="cover_image" accept="image/*"
                                   class="w-full text-xs text-stone-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-400 file:text-stone-950 hover:file:bg-amber-300 cursor-pointer">
                            <p class="text-[10px] text-stone-500 mt-1">Maks. 2MB (JPG, PNG, WEBP). Jika kosong, ornamen tema akan digunakan.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-300 mb-1">Foto Galeri (Maksimal 4 Foto)</label>
                            <input type="file" name="gallery_images[]" multiple accept="image/*"
                                   class="w-full text-xs text-stone-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-white/10 file:text-stone-200 hover:file:bg-white/20 cursor-pointer">
                            <p class="text-[10px] text-stone-500 mt-1">Dapat memilih hingga 4 foto untuk ditampilkan dalam grid &amp; lightbox.</p>
                        </div>
                    </div>

                    <!-- Rekening Kado / Amplop Digital Dinamis -->
                    <div class="pt-4 border-t border-white/10">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <label class="block text-xs font-bold text-stone-300">Amplop Digital / Kado Online</label>
                                <p class="text-[10px] text-stone-400">Tamu dapat menyalin nomor rekening dengan 1 klik.</p>
                            </div>
                            <button type="button" @click="addGiftAccount()" class="px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-amber-400/20 text-amber-300 hover:bg-amber-400/30 transition">
                                + Tambah Bank
                            </button>
                        </div>

                        <div class="space-y-3">
                            <template x-for="(acc, index) in giftAccounts" :key="index">
                                <div class="p-3 rounded-2xl bg-stone-900 border border-white/10 flex flex-col sm:flex-row gap-2 items-center">
                                    <input type="text" :name="'gift_accounts[' + index + '][bank]'" x-model="acc.bank" placeholder="Bank/E-Wallet (e.g. BCA, Mandiri, GoPay)"
                                           class="w-full sm:w-1/3 px-3 py-2 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                                    <input type="text" :name="'gift_accounts[' + index + '][account_number]'" x-model="acc.account_number" placeholder="Nomor Rekening"
                                           class="w-full sm:w-1/3 px-3 py-2 rounded-xl bg-stone-950 border border-white/10 text-white text-xs font-mono">
                                    <input type="text" :name="'gift_accounts[' + index + '][account_name]'" x-model="acc.account_name" placeholder="Atas Nama"
                                           class="w-full sm:w-1/3 px-3 py-2 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                                    
                                    <button type="button" @click="removeGiftAccount(index)" x-show="giftAccounts.length > 1"
                                            class="p-2 text-stone-400 hover:text-red-400 text-xs">✕</button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Pilihan Musik Latar -->
                    <div class="pt-4 border-t border-white/10 space-y-3">
                        <label class="block text-xs font-bold text-stone-300">Musik Latar Pengiring</label>
                        <div class="grid grid-cols-3 gap-2.5">
                            <label class="flex flex-col items-center justify-center p-3 rounded-2xl border text-center cursor-pointer transition"
                                   :class="musicMode === 'none' ? 'bg-amber-400/20 border-amber-400 text-amber-300' : 'bg-stone-900 border-white/10 text-stone-400'">
                                <input type="radio" name="music_mode" value="none" x-model="musicMode" class="sr-only">
                                <span class="text-sm">🔇</span>
                                <span class="text-[11px] font-semibold mt-1">Tanpa Musik</span>
                            </label>
                            <label class="flex flex-col items-center justify-center p-3 rounded-2xl border text-center cursor-pointer transition"
                                   :class="musicMode === 'ambient' ? 'bg-amber-400/20 border-amber-400 text-amber-300' : 'bg-stone-900 border-white/10 text-stone-400'">
                                <input type="radio" name="music_mode" value="ambient" x-model="musicMode" class="sr-only">
                                <span class="text-sm">✨</span>
                                <span class="text-[11px] font-semibold mt-1">Melodi Ambient</span>
                            </label>
                            <label class="flex flex-col items-center justify-center p-3 rounded-2xl border text-center cursor-pointer transition"
                                   :class="musicMode === 'custom' ? 'bg-amber-400/20 border-amber-400 text-amber-300' : 'bg-stone-900 border-white/10 text-stone-400'">
                                <input type="radio" name="music_mode" value="custom" x-model="musicMode" class="sr-only">
                                <span class="text-sm">🎵</span>
                                <span class="text-[11px] font-semibold mt-1">Upload MP3</span>
                            </label>
                        </div>

                        <div x-show="musicMode === 'custom'" x-cloak class="p-3 rounded-2xl bg-stone-900 border border-white/10">
                            <label class="block text-xs text-stone-300 mb-1">Pilih File Lagu MP3 (Maks. 5MB)</label>
                            <input type="file" name="music_file" accept="audio/mp3,audio/mpeg,audio/wav,audio/ogg"
                                   class="w-full text-xs text-stone-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-400 file:text-stone-950">
                        </div>
                    </div>
                </div>

                <!-- Navigasi Langkah (Prev / Next / Submit) -->
                <div class="mt-8 pt-6 border-t border-white/10 flex items-center justify-between gap-3">
                    <button type="button" @click="prevStep()" x-show="step > 1"
                            class="min-h-[44px] px-5 py-2.5 rounded-full text-xs font-semibold bg-white/10 hover:bg-white/15 text-stone-300 transition active:scale-95">
                        ← Kembali
                    </button>
                    <div x-show="step === 1"></div>

                    <button type="button" @click="nextStep()" x-show="step < 4"
                            class="min-h-[44px] px-6 py-2.5 rounded-full text-xs font-semibold bg-amber-400 hover:bg-amber-300 text-stone-950 transition active:scale-95 ml-auto flex items-center gap-1.5">
                        <span>Lanjut ke Langkah Berikutnya</span>
                        <span>→</span>
                    </button>

                    <button type="submit" x-show="step === 4"
                            class="min-h-[44px] px-8 py-3 rounded-full text-xs font-bold uppercase tracking-wider bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-stone-950 shadow-lg shadow-amber-400/20 active:scale-95 transition ml-auto flex items-center gap-2">
                        <span>🎉 Buat Undangan Sekarang</span>
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-8 px-4 text-center text-xs text-stone-500 border-t border-white/10 bg-stone-950">
        <p>&copy; {{ date('Y') }} Undangan Online. Seluruh hak cipta dilindungi.</p>
    </footer>
</body>
</html>
