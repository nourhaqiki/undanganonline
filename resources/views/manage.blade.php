<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Kelola Undangan: {{ $invitation->title }}</title>

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
<body class="font-sans-main bg-stone-950 text-stone-100 overflow-x-hidden antialiased selection:bg-amber-400 selection:text-stone-950 min-h-screen pb-20">

    <!-- Top Header -->
    <header class="border-b border-white/10 bg-stone-900 sticky top-0 z-30">
        <div class="max-w-4xl mx-auto px-4 h-16 flex items-center justify-between">
            <div>
                <span class="text-[10px] uppercase tracking-wider text-amber-400 font-bold block">Panel Kelola Mandiri</span>
                <h1 class="text-sm font-bold text-white truncate max-w-xs sm:max-w-md">{{ $invitation->title }}</h1>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('invitation.show', $invitation->slug) }}" target="_blank"
                   class="min-h-[40px] px-3.5 py-1.5 rounded-full text-xs font-semibold bg-amber-400 text-stone-950 hover:bg-amber-300 transition flex items-center gap-1.5">
                    <span>Lihat Undangan</span>
                    <span>↗</span>
                </a>
            </div>
        </div>
    </header>

    <div class="max-w-4xl mx-auto px-4 pt-8 space-y-8"
         x-data="{
             tab: 'edit',
             category: '{{ old('category', $invitation->category) }}',
             selectedTheme: '{{ old('theme_slug', $invitation->theme_slug) }}',
             hasSession2: {{ old('session_2_title', $invitation->event_data['session_2']['title'] ?? '') ? 'true' : 'false' }},
             musicMode: '{{ old('music_mode', $invitation->event_data['music_mode'] ?? 'ambient') }}',
             giftAccounts: {{ json_encode(old('gift_accounts', $invitation->event_data['gift_accounts'] ?? [['bank' => '', 'account_number' => '', 'account_name' => '']])) }},
             
             addGiftAccount() {
                 this.giftAccounts.push({ bank: '', account_number: '', account_name: '' });
             },
             removeGiftAccount(index) {
                 if (this.giftAccounts.length > 1) {
                     this.giftAccounts.splice(index, 1);
                 }
             }
         }">

        <!-- Notifikasi Sukses -->
        @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-950/80 border border-emerald-500/40 text-emerald-200 text-xs flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if(isset($errors) && $errors->any())
        <div class="p-4 rounded-2xl bg-red-950/80 border border-red-500/40 text-red-200 text-xs">
            <strong class="font-bold block mb-1">Terjadi kesalahan validasi:</strong>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- 3 Kartu Rekap RSVP -->
        <div class="grid grid-cols-3 gap-3 sm:gap-4">
            <div class="p-4 rounded-2xl bg-stone-900 border border-white/10 text-center">
                <span class="text-[10px] sm:text-xs font-semibold text-stone-400 uppercase tracking-wider block">Total Respon</span>
                <span class="text-2xl sm:text-3xl font-extrabold text-white mt-1 block">{{ $wishes->count() }}</span>
            </div>
            <div class="p-4 rounded-2xl bg-emerald-950/40 border border-emerald-500/30 text-center">
                <span class="text-[10px] sm:text-xs font-semibold text-emerald-400 uppercase tracking-wider block">Konfirmasi Hadir</span>
                <span class="text-2xl sm:text-3xl font-extrabold text-emerald-400 mt-1 block">{{ $hadirCount }}</span>
            </div>
            <div class="p-4 rounded-2xl bg-stone-900 border border-white/10 text-center">
                <span class="text-[10px] sm:text-xs font-semibold text-stone-400 uppercase tracking-wider block">Berhalangan</span>
                <span class="text-2xl sm:text-3xl font-extrabold text-stone-400 mt-1 block">{{ $tidakHadirCount }}</span>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex items-center gap-2 border-b border-white/10 pb-2">
            <button type="button" @click="tab = 'edit'"
                    :class="tab === 'edit' ? 'bg-amber-400 text-stone-950 font-bold' : 'text-stone-400 hover:text-white'"
                    class="min-h-[40px] px-5 py-2 rounded-xl text-xs transition">
                ✏️ Form Edit Undangan
            </button>
            <button type="button" @click="tab = 'wishes'"
                    :class="tab === 'wishes' ? 'bg-amber-400 text-stone-950 font-bold' : 'text-stone-400 hover:text-white'"
                    class="min-h-[40px] px-5 py-2 rounded-xl text-xs transition">
                💬 Rekap Ucapan &amp; RSVP ({{ $wishes->count() }})
            </button>
        </div>

        <!-- TAB 1: FORM EDIT UNDANGAN -->
        <div x-show="tab === 'edit'" x-cloak class="space-y-6">
            <form action="{{ route('invitation.update', ['slug' => $invitation->slug, 'token' => $invitation->edit_token]) }}" 
                  method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                @php $ed = $invitation->event_data ?? []; @endphp

                <!-- 1. Kategori & Tema -->
                <div class="p-6 rounded-3xl bg-stone-900 border border-white/10 space-y-4">
                    <h2 class="text-xs font-bold text-amber-400 uppercase tracking-wider">Kategori &amp; Desain Tema</h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-stone-300 mb-1">Judul Undangan</label>
                            <input type="text" name="title" value="{{ old('title', $invitation->title) }}" required
                                   class="w-full px-4 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-stone-300 mb-1">Kategori</label>
                            <select name="category" x-model="category"
                                    class="w-full px-4 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                                <option value="pernikahan">Pernikahan</option>
                                <option value="tunangan">Tunangan</option>
                                <option value="bisnis">Bisnis</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-stone-300 mb-2">Pilihan Tema Undangan</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($themes as $th)
                            <label class="p-3.5 rounded-2xl border cursor-pointer transition flex items-center justify-between"
                                   :class="selectedTheme === '{{ $th->slug }}' ? 'bg-amber-400/10 border-amber-400 text-amber-300' : 'bg-stone-950 border-white/10 text-stone-400'">
                                <input type="radio" name="theme_slug" value="{{ $th->slug }}" x-model="selectedTheme" class="sr-only">
                                <div class="flex items-center gap-2">
                                    <span class="w-3.5 h-3.5 rounded-full border border-white/20" style="background-color: {{ $th->accent_color }};"></span>
                                    <span class="text-xs font-bold text-white">{{ $th->name }}</span>
                                </div>
                                <span class="text-[10px] uppercase font-mono text-stone-500">{{ $th->slug }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- 2. Profil & Salam -->
                <div class="p-6 rounded-3xl bg-stone-900 border border-white/10 space-y-4">
                    <h2 class="text-xs font-bold text-amber-400 uppercase tracking-wider">Profil Mempelai / Penyelenggara</h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-stone-300 mb-1">Nama Utama 1</label>
                            <input type="text" name="primary_name" value="{{ old('primary_name', $ed['primary_name'] ?? '') }}" required
                                   class="w-full px-4 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-stone-400 mb-1">Keterangan / Orang Tua / Jabatan 1</label>
                            <input type="text" name="primary_title_parents" value="{{ old('primary_title_parents', $ed['primary_title_parents'] ?? '') }}"
                                   class="w-full px-4 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-stone-400 mb-1">Instagram 1 (Opsional)</label>
                        <input type="text" name="primary_instagram" value="{{ old('primary_instagram', $ed['primary_instagram'] ?? '') }}"
                               class="w-full px-4 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-3 border-t border-white/10">
                        <div>
                            <label class="block text-xs font-semibold text-stone-300 mb-1">Nama Utama 2 (Opsional)</label>
                            <input type="text" name="secondary_name" value="{{ old('secondary_name', $ed['secondary_name'] ?? '') }}"
                                   class="w-full px-4 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-stone-400 mb-1">Keterangan / Orang Tua / Jabatan 2</label>
                            <input type="text" name="secondary_title_parents" value="{{ old('secondary_title_parents', $ed['secondary_title_parents'] ?? '') }}"
                                   class="w-full px-4 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-stone-400 mb-1">Instagram 2 (Opsional)</label>
                        <input type="text" name="secondary_instagram" value="{{ old('secondary_instagram', $ed['secondary_instagram'] ?? '') }}"
                               class="w-full px-4 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                    </div>

                    <div class="pt-3 border-t border-white/10 space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-stone-300 mb-1">Salam Pembuka</label>
                            <input type="text" name="greeting" value="{{ old('greeting', $ed['greeting'] ?? '') }}"
                                   class="w-full px-4 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-stone-300 mb-1">Kutipan / Doa / Deskripsi</label>
                            <textarea name="quote" rows="2"
                                      class="w-full px-4 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">{{ old('quote', $ed['quote'] ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- 3. Acara & Sesi -->
                @php $s1 = $ed['session_1'] ?? []; $s2 = $ed['session_2'] ?? []; @endphp
                <div class="p-6 rounded-3xl bg-stone-900 border border-white/10 space-y-4">
                    <h2 class="text-xs font-bold text-amber-400 uppercase tracking-wider">Jadwal Sesi 1 (Utama)</h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-stone-300 mb-1">Nama Sesi 1</label>
                            <input type="text" name="session_1_title" value="{{ old('session_1_title', $s1['title'] ?? '') }}" required
                                   class="w-full px-4 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-stone-300 mb-1">Tanggal</label>
                            <input type="date" name="session_1_date" value="{{ old('session_1_date', $s1['date'] ?? '') }}" required
                                   class="w-full px-4 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-stone-300 mb-1">Waktu</label>
                            <input type="text" name="session_1_time" value="{{ old('session_1_time', $s1['time'] ?? '') }}" required
                                   class="w-full px-4 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-stone-300 mb-1">Gedung / Tempat</label>
                            <input type="text" name="session_1_venue" value="{{ old('session_1_venue', $s1['venue'] ?? '') }}" required
                                   class="w-full px-4 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-stone-300 mb-1">Alamat</label>
                            <input type="text" name="session_1_address" value="{{ old('session_1_address', $s1['address'] ?? '') }}" required
                                   class="w-full px-4 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-stone-300 mb-1">Google Maps URL</label>
                            <input type="url" name="session_1_maps_url" value="{{ old('session_1_maps_url', $s1['maps_url'] ?? '') }}"
                                   class="w-full px-4 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                        </div>
                    </div>

                    <!-- Sesi 2 -->
                    <div class="pt-4 border-t border-white/10">
                        <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-semibold text-stone-300 mb-3">
                            <input type="checkbox" name="session_2_enabled" value="1" x-model="hasSession2" class="w-4 h-4 rounded text-amber-500 bg-stone-900 border-white/20 focus:ring-0">
                            <span>Aktifkan Sesi 2 (Resepsi / Tambahan)</span>
                        </label>

                        <div x-show="hasSession2" x-cloak class="space-y-4 pt-2">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-stone-300 mb-1">Nama Sesi 2</label>
                                    <input type="text" name="session_2_title" value="{{ old('session_2_title', $s2['title'] ?? '') }}"
                                           class="w-full px-4 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-stone-300 mb-1">Tanggal Sesi 2</label>
                                    <input type="date" name="session_2_date" value="{{ old('session_2_date', $s2['date'] ?? '') }}"
                                           class="w-full px-4 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-stone-300 mb-1">Waktu Sesi 2</label>
                                    <input type="text" name="session_2_time" value="{{ old('session_2_time', $s2['time'] ?? '') }}"
                                           class="w-full px-4 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-stone-300 mb-1">Gedung / Tempat Sesi 2</label>
                                    <input type="text" name="session_2_venue" value="{{ old('session_2_venue', $s2['venue'] ?? '') }}"
                                           class="w-full px-4 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-stone-300 mb-1">Alamat Sesi 2</label>
                                    <input type="text" name="session_2_address" value="{{ old('session_2_address', $s2['address'] ?? '') }}"
                                           class="w-full px-4 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-stone-300 mb-1">Google Maps URL Sesi 2</label>
                                    <input type="url" name="session_2_maps_url" value="{{ old('session_2_maps_url', $s2['maps_url'] ?? '') }}"
                                           class="w-full px-4 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Media & Galeri -->
                <div class="p-6 rounded-3xl bg-stone-900 border border-white/10 space-y-4">
                    <h2 class="text-xs font-bold text-amber-400 uppercase tracking-wider">Foto Sampul &amp; Galeri</h2>

                    <div>
                        <label class="block text-xs font-semibold text-stone-300 mb-1">Foto Sampul</label>
                        @if(!empty($ed['cover_image']))
                            <div class="flex items-center gap-3 mb-2">
                                <img src="{{ asset('storage/' . $ed['cover_image']) }}" alt="Cover" class="w-16 h-16 rounded-xl object-cover border border-white/10">
                                <span class="text-xs text-stone-400">Foto sampul saat ini telah terpasang. Unggah foto baru jika ingin mengganti.</span>
                            </div>
                        @endif
                        <input type="file" name="cover_image" accept="image/*"
                               class="w-full text-xs text-stone-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-400 file:text-stone-950">
                    </div>

                    <div class="pt-3 border-t border-white/10">
                        <label class="block text-xs font-semibold text-stone-300 mb-1">Galeri Foto (Maks. 4 Foto)</label>
                        @if(!empty($ed['gallery_images']) && count($ed['gallery_images']) > 0)
                            <div class="flex items-center gap-2 mb-3">
                                @foreach($ed['gallery_images'] as $photo)
                                    <img src="{{ asset('storage/' . $photo) }}" alt="Gallery" class="w-12 h-12 rounded-lg object-cover border border-white/10">
                                @endforeach
                            </div>
                            <label class="inline-flex items-center gap-2 cursor-pointer text-xs text-red-400 mb-2">
                                <input type="checkbox" name="clear_gallery" value="1" class="rounded text-red-500">
                                <span>Hapus seluruh foto galeri yang ada</span>
                            </label>
                        @endif
                        <input type="file" name="gallery_images[]" multiple accept="image/*"
                               class="w-full text-xs text-stone-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-white/10 file:text-stone-200">
                    </div>
                </div>

                <!-- 5. Kado & Musik -->
                <div class="p-6 rounded-3xl bg-stone-900 border border-white/10 space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xs font-bold text-amber-400 uppercase tracking-wider">Rekening Amplop Digital</h2>
                        <button type="button" @click="addGiftAccount()" class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-400/20 text-amber-300">
                            + Tambah Rekening
                        </button>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(acc, index) in giftAccounts" :key="index">
                            <div class="p-3 rounded-2xl bg-stone-950 border border-white/10 flex flex-col sm:flex-row gap-2 items-center">
                                <input type="text" :name="'gift_accounts[' + index + '][bank]'" x-model="acc.bank" placeholder="Bank / E-Wallet"
                                       class="w-full sm:w-1/3 px-3 py-2 rounded-xl bg-stone-900 border border-white/10 text-white text-xs">
                                <input type="text" :name="'gift_accounts[' + index + '][account_number]'" x-model="acc.account_number" placeholder="Nomor Rekening"
                                       class="w-full sm:w-1/3 px-3 py-2 rounded-xl bg-stone-900 border border-white/10 text-white text-xs font-mono">
                                <input type="text" :name="'gift_accounts[' + index + '][account_name]'" x-model="acc.account_name" placeholder="Atas Nama"
                                       class="w-full sm:w-1/3 px-3 py-2 rounded-xl bg-stone-900 border border-white/10 text-white text-xs">
                                
                                <button type="button" @click="removeGiftAccount(index)" x-show="giftAccounts.length > 1"
                                        class="p-2 text-stone-400 hover:text-red-400 text-xs">✕</button>
                            </div>
                        </template>
                    </div>

                    <div class="pt-4 border-t border-white/10 space-y-3">
                        <label class="block text-xs font-bold text-stone-300">Pilihan Musik Latar</label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="p-2.5 rounded-xl border text-center cursor-pointer text-xs font-semibold transition"
                                   :class="musicMode === 'none' ? 'bg-amber-400/20 border-amber-400 text-amber-300' : 'bg-stone-950 border-white/10 text-stone-400'">
                                <input type="radio" name="music_mode" value="none" x-model="musicMode" class="sr-only">
                                <span>Tanpa Musik</span>
                            </label>
                            <label class="p-2.5 rounded-xl border text-center cursor-pointer text-xs font-semibold transition"
                                   :class="musicMode === 'ambient' ? 'bg-amber-400/20 border-amber-400 text-amber-300' : 'bg-stone-950 border-white/10 text-stone-400'">
                                <input type="radio" name="music_mode" value="ambient" x-model="musicMode" class="sr-only">
                                <span>Melodi Ambient</span>
                            </label>
                            <label class="p-2.5 rounded-xl border text-center cursor-pointer text-xs font-semibold transition"
                                   :class="musicMode === 'custom' ? 'bg-amber-400/20 border-amber-400 text-amber-300' : 'bg-stone-950 border-white/10 text-stone-400'">
                                <input type="radio" name="music_mode" value="custom" x-model="musicMode" class="sr-only">
                                <span>Upload MP3</span>
                            </label>
                        </div>

                        <div x-show="musicMode === 'custom'" x-cloak class="p-3 rounded-2xl bg-stone-950 border border-white/10">
                            <label class="block text-xs text-stone-300 mb-1">Ganti File Audio MP3</label>
                            <input type="file" name="music_file" accept="audio/mp3,audio/mpeg,audio/wav,audio/ogg"
                                   class="w-full text-xs text-stone-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-400 file:text-stone-950">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit"
                            class="min-h-[48px] px-8 py-3 rounded-full font-bold text-xs uppercase tracking-wider bg-amber-400 hover:bg-amber-300 text-stone-950 shadow-xl shadow-amber-400/20 active:scale-95 transition">
                        Simpan Perubahan Undangan
                    </button>
                </div>
            </form>
        </div>

        <!-- TAB 2: DAFTAR UCAPAN & RSVP -->
        <div x-show="tab === 'wishes'" x-cloak class="space-y-4">
            <div class="p-6 rounded-3xl bg-stone-900 border border-white/10 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xs font-bold text-amber-400 uppercase tracking-wider">Rekapitulasi Ucapan &amp; Kehadiran Tamu</h2>
                    <span class="text-xs text-stone-400">{{ $wishes->count() }} Respon Tercatat</span>
                </div>

                <div class="space-y-3">
                    @forelse($wishes as $wish)
                    <div class="p-4 rounded-2xl bg-stone-950 border border-white/5 space-y-1.5 text-xs">
                        <div class="flex items-center justify-between">
                            <strong class="text-sm font-bold text-white">{{ $wish->guest_name }}</strong>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold {{ $wish->attendance === 'hadir' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-stone-800 text-stone-400' }}">
                                {{ $wish->attendance === 'hadir' ? 'Hadir' : 'Tidak Hadir' }}
                            </span>
                        </div>
                        <p class="text-stone-300 leading-relaxed">{{ $wish->message }}</p>
                        <span class="block text-[10px] text-stone-500">{{ $wish->created_at->diffForHumans() }} ({{ $wish->created_at->format('d M Y, H:i') }})</span>
                    </div>
                    @empty
                    <div class="py-12 text-center text-xs text-stone-500 italic">
                        Belum ada tamu yang mengirimkan ucapan atau konfirmasi kehadiran.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</body>
</html>
