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

    <!-- Tailwind CSS v4, Alpine.js CDN & SheetJS (Excel/CSV Parser) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

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

    <div class="max-w-4xl mx-auto px-4 pt-8 space-y-8" x-data="manageApp" x-init="initGuests()">

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

        <!-- 4 Kartu Rekap: RSVP & Tamu Undangan -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
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
            <div class="p-4 rounded-2xl bg-amber-400/10 border border-amber-400/30 text-center cursor-pointer hover:bg-amber-400/15 transition"
                 @click="tab = 'guests'">
                <span class="text-[10px] sm:text-xs font-semibold text-amber-300 uppercase tracking-wider block">Daftar Tamu</span>
                <span class="text-2xl sm:text-3xl font-extrabold text-amber-300 mt-1 block" x-text="guests.length">0</span>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex flex-wrap items-center gap-2 border-b border-white/10 pb-2">
            <button type="button" @click="tab = 'guests'"
                    :class="tab === 'guests' ? 'bg-amber-400 text-stone-950 font-bold' : 'text-stone-400 hover:text-white'"
                    class="min-h-[40px] px-5 py-2 rounded-xl text-xs transition flex items-center gap-1.5">
                <span>👥 Kelola Tamu (Import Excel)</span>
                <span x-show="guests.length > 0" class="px-2 py-0.5 rounded-full text-[10px] bg-stone-900 text-amber-300 border border-white/10 font-bold" x-text="guests.length"></span>
            </button>
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

                    <!-- Foto Mempelai Pria & Wanita -->
                    <div class="pt-4 border-t border-white/10 grid grid-cols-1 sm:grid-cols-2 gap-4"
                         x-data="{
                             groomSrc: '{{ !empty($ed['groom_photo']) ? asset('storage/' . $ed['groom_photo']) : '' }}',
                             brideSrc: '{{ !empty($ed['bride_photo']) ? asset('storage/' . $ed['bride_photo']) : '' }}',
                             onPick(e, key) {
                                 const f = e.target.files[0];
                                 if (f) this[key] = URL.createObjectURL(f);
                             }
                         }">
                        <div class="p-4 rounded-2xl bg-stone-950 border border-white/10 space-y-2.5">
                            <label class="block text-xs font-semibold text-stone-300">Foto Mempelai Pria</label>
                            <div class="flex items-center gap-3">
                                <div class="w-16 h-20 rounded-xl overflow-hidden bg-stone-900 border border-white/10 shrink-0 flex items-center justify-center">
                                    <template x-if="groomSrc">
                                        <img :src="groomSrc" alt="Foto Mempelai Pria" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!groomSrc">
                                        <span class="text-[10px] text-stone-500 text-center px-1">Belum ada foto</span>
                                    </template>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <input type="file" name="groom_photo" accept="image/*" @change="onPick($event, 'groomSrc')"
                                           class="w-full text-[11px] text-stone-400 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[11px] file:font-semibold file:bg-amber-400 file:text-stone-950 cursor-pointer">
                                    <p class="text-[10px] text-stone-500 mt-1">Tampil di semua tema. Maks. 2MB.</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 rounded-2xl bg-stone-950 border border-white/10 space-y-2.5">
                            <label class="block text-xs font-semibold text-stone-300">Foto Mempelai Wanita</label>
                            <div class="flex items-center gap-3">
                                <div class="w-16 h-20 rounded-xl overflow-hidden bg-stone-900 border border-white/10 shrink-0 flex items-center justify-center">
                                    <template x-if="brideSrc">
                                        <img :src="brideSrc" alt="Foto Mempelai Wanita" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!brideSrc">
                                        <span class="text-[10px] text-stone-500 text-center px-1">Belum ada foto</span>
                                    </template>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <input type="file" name="bride_photo" accept="image/*" @change="onPick($event, 'brideSrc')"
                                           class="w-full text-[11px] text-stone-400 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[11px] file:font-semibold file:bg-amber-400 file:text-stone-950 cursor-pointer">
                                    <p class="text-[10px] text-stone-500 mt-1">Tampil di semua tema. Maks. 2MB.</p>
                                </div>
                            </div>
                        </div>
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

        <!-- TAB 3: KELOLA TAMU & IMPORT EXCEL -->
        <div x-show="tab === 'guests'" x-cloak class="space-y-6">

            <!-- 1. Panduan & Download Template Excel / CSV -->
            <div class="p-6 rounded-3xl bg-stone-900 border border-white/10 shadow-xl space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <span class="text-[10px] uppercase tracking-wider text-amber-400 font-bold block mb-1">Template Resmi Excel &amp; CSV</span>
                        <h2 class="text-sm sm:text-base font-bold text-white">Unduh Contoh Template Daftar Tamu</h2>
                        <p class="text-xs text-stone-400 mt-0.5 leading-relaxed">
                            Gunakan format template resmi ini untuk mengisi nama tamu, alamat/kota, dan nomor WhatsApp secara cepat.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 shrink-0">
                        <a href="{{ route('guest.template', ['format' => 'xlsx']) }}"
                           class="min-h-[44px] px-4 py-2.5 rounded-xl text-xs font-semibold bg-emerald-600 hover:bg-emerald-500 text-white transition active:scale-95 flex items-center gap-2 shadow-lg shadow-emerald-600/20">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Download Template (.xlsx)</span>
                        </a>

                        <a href="{{ route('guest.template', ['format' => 'csv']) }}"
                           class="min-h-[44px] px-4 py-2.5 rounded-xl text-xs font-semibold bg-stone-800 hover:bg-stone-700 text-stone-200 border border-white/10 transition active:scale-95 flex items-center gap-2">
                            <svg class="w-4 h-4 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            <span>Format CSV (.csv)</span>
                        </a>
                    </div>
                </div>

                <!-- Petunjuk Kolom File -->
                <div class="p-3.5 rounded-2xl bg-stone-950/80 border border-white/5 text-[11px] text-stone-300 grid grid-cols-1 sm:grid-cols-4 gap-2">
                    <div class="space-y-0.5">
                        <span class="font-bold text-amber-400 block">1. Nama Tamu (Wajib)</span>
                        <p class="text-stone-400 text-[10px]">Contoh: Bpk. Bambang, Siti &amp; Suami</p>
                    </div>
                    <div class="space-y-0.5">
                        <span class="font-bold text-amber-400 block">2. Alamat / Kota (Opsional)</span>
                        <p class="text-stone-400 text-[10px]">Muncul sebagai "di [Alamat]" di cover</p>
                    </div>
                    <div class="space-y-0.5">
                        <span class="font-bold text-amber-400 block">3. No WhatsApp (Opsional)</span>
                        <p class="text-stone-400 text-[10px]">Format: 0812xxx atau 62812xxx</p>
                    </div>
                    <div class="space-y-0.5">
                        <span class="font-bold text-amber-400 block">4. Catatan (Opsional)</span>
                        <p class="text-stone-400 text-[10px]">Pengelompokan (Keluarga, Kantor)</p>
                    </div>
                </div>
            </div>

            <!-- 2. Area Drag & Drop / Upload File Excel/CSV -->
            <div class="p-6 rounded-3xl bg-stone-900 border border-white/10 shadow-xl space-y-4">
                <span class="text-xs font-bold text-amber-400 uppercase tracking-wider block">Unggah / Update File Excel</span>

                <div @dragover.prevent="isDragging = true"
                     @dragleave.prevent="isDragging = false"
                     @drop.prevent="handleDrop($event)"
                     :class="isDragging ? 'border-amber-400 bg-amber-400/10' : 'border-white/15 bg-stone-950/60 hover:border-amber-400/50'"
                     class="p-8 rounded-2xl border-2 border-dashed transition-all text-center space-y-3 cursor-pointer relative">
                    
                    <input type="file"
                           accept=".xlsx,.xls,.csv"
                           @change="handleFileUpload($event)"
                           class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-10">

                    <div class="w-12 h-12 rounded-full bg-amber-400/10 text-amber-400 flex items-center justify-center mx-auto text-xl border border-amber-400/20">
                        📁
                    </div>

                    <div class="space-y-1">
                        <p class="text-sm font-semibold text-white">Tarik &amp; lepas file Excel / CSV ke sini</p>
                        <p class="text-xs text-stone-400">Atau <span class="text-amber-400 font-semibold underline">klik untuk memilih file</span> dari komputer/HP Anda</p>
                        <p class="text-[10px] text-stone-500">Mendukung file .xlsx, .xls, dan .csv</p>
                    </div>
                </div>

                <!-- Notifikasi Status Upload -->
                <div x-show="uploadStatus" x-cloak class="p-3.5 rounded-xl bg-emerald-950/80 border border-emerald-500/40 text-emerald-200 text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span x-text="uploadStatus"></span>
                </div>

                <div x-show="uploadError" x-cloak class="p-3.5 rounded-xl bg-red-950/80 border border-red-500/40 text-red-200 text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span x-text="uploadError"></span>
                </div>
            </div>

            <!-- 3. Form Tambah Tamu Manual -->
            <div class="p-6 rounded-3xl bg-stone-900 border border-white/10 shadow-xl space-y-3">
                <span class="text-xs font-bold text-stone-300 uppercase tracking-wider block">Tambah Tamu Manual</span>

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-2">
                    <input type="text" x-model="newGuestName" placeholder="Nama Tamu (Wajib)"
                           class="px-3.5 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs focus:ring-2 focus:ring-amber-400 focus:outline-none">
                    <input type="text" x-model="newGuestAddress" placeholder="Alamat / Kota (Opsional)"
                           class="px-3.5 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs focus:ring-2 focus:ring-amber-400 focus:outline-none">
                    <input type="text" x-model="newGuestPhone" placeholder="No WA (0812...)"
                           class="px-3.5 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs focus:ring-2 focus:ring-amber-400 focus:outline-none">
                    <input type="text" x-model="newGuestNote" placeholder="Catatan (Teman, dsb)"
                           class="px-3.5 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs focus:ring-2 focus:ring-amber-400 focus:outline-none">
                </div>

                <div class="flex justify-end pt-1">
                    <button type="button" @click="addGuestManual()"
                            class="min-h-[40px] px-5 py-2 rounded-xl text-xs font-semibold bg-amber-400 hover:bg-amber-300 text-stone-950 transition active:scale-95 flex items-center gap-1.5">
                        <span>+ Tambah ke Daftar</span>
                    </button>
                </div>
            </div>

            <!-- 4. Toolbar & Tabel Daftar Tamu -->
            <div class="p-6 rounded-3xl bg-stone-900 border border-white/10 shadow-xl space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <h2 class="text-xs font-bold text-amber-400 uppercase tracking-wider">Daftar Tamu &amp; Link Personal</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] bg-stone-950 text-stone-300 border border-white/10 font-bold"
                              x-text="filteredGuests.length + ' dari ' + guests.length + ' Tamu'"></span>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" @click="exportToExcel()"
                                :disabled="guests.length === 0"
                                class="min-h-[38px] px-3.5 py-2 rounded-xl text-xs font-semibold bg-emerald-600/90 hover:bg-emerald-600 text-white transition active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-1.5 shadow-md shadow-emerald-950">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            <span>Download Excel (Dengan Link)</span>
                        </button>

                        <button type="button" @click="clearAllGuests()"
                                x-show="guests.length > 0"
                                class="min-h-[38px] px-3 py-2 rounded-xl text-xs font-medium bg-red-950/60 hover:bg-red-900/80 text-red-300 border border-red-500/30 transition active:scale-95">
                            Kosongkan
                        </button>
                    </div>
                </div>

                <!-- Input Live Search -->
                <div class="relative">
                    <input type="text" x-model="guestSearch" placeholder="Cari nama tamu, alamat, atau no HP..."
                           class="w-full px-4 py-2.5 pl-10 rounded-xl bg-stone-950 border border-white/10 text-white text-xs focus:ring-2 focus:ring-amber-400 focus:outline-none">
                    <svg class="w-4 h-4 text-stone-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <!-- Tabel Data Tamu Desktop -->
                <div class="hidden sm:block overflow-x-auto rounded-2xl border border-white/10">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-stone-950 border-b border-white/10 text-stone-400 uppercase tracking-wider text-[10px]">
                            <tr>
                                <th class="py-3 px-3 w-12 text-center">No</th>
                                <th class="py-3 px-4">Nama Tamu</th>
                                <th class="py-3 px-3">Alamat / Kota</th>
                                <th class="py-3 px-3">No WA</th>
                                <th class="py-3 px-4">Link Undangan Personal</th>
                                <th class="py-3 px-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 bg-stone-900/50">
                            <template x-for="(g, idx) in filteredGuests" :key="idx">
                                <tr class="hover:bg-white/[0.02] transition">
                                    <td class="py-3 px-3 text-center text-stone-500 text-[11px]" x-text="idx + 1"></td>
                                    <td class="py-3 px-4 font-semibold text-white">
                                        <span x-text="g.name"></span>
                                        <span x-show="g.note" class="block text-[10px] text-stone-400 font-normal" x-text="'(' + g.note + ')'"></span>
                                    </td>
                                    <td class="py-3 px-3 text-stone-300">
                                        <span x-text="g.address ? g.address : '-'"></span>
                                    </td>
                                    <td class="py-3 px-3 font-mono text-[11px] text-stone-300">
                                        <span x-text="g.phone ? g.phone : '-'"></span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <a :href="getGuestUrl(g)" target="_blank"
                                           class="text-[11px] font-mono text-amber-300 hover:underline block max-w-xs truncate"
                                           x-text="getGuestUrl(g)"></a>
                                    </td>
                                    <td class="py-3 px-3 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <button type="button" @click="copyLink(g, idx)"
                                                    class="min-h-[32px] px-2.5 py-1 rounded-lg text-[11px] font-medium transition active:scale-95 border"
                                                    :class="copiedIndex === idx ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40' : 'bg-white/5 hover:bg-white/10 text-stone-300 border-white/10'">
                                                <span x-text="copiedIndex === idx ? 'Tersalin!' : 'Salin'"></span>
                                            </button>

                                            <a :href="getWaLink(g)" target="_blank"
                                               class="min-h-[32px] px-2.5 py-1 rounded-lg text-[11px] font-medium bg-emerald-600 hover:bg-emerald-500 text-white transition active:scale-95 flex items-center gap-1">
                                                <span>WA</span>
                                                <span class="text-[10px]">↗</span>
                                            </a>

                                            <button type="button" @click="removeGuest(idx)"
                                                    class="min-h-[32px] px-2 py-1 rounded-lg text-stone-400 hover:text-red-400 text-xs">
                                                ✕
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>

                            <tr x-show="filteredGuests.length === 0">
                                <td colspan="6" class="py-8 text-center text-xs text-stone-500 italic">
                                    <span x-show="guests.length === 0">Belum ada daftar tamu. Silakan upload file Excel atau tambah manual di atas.</span>
                                    <span x-show="guests.length > 0">Tidak ada tamu yang sesuai dengan pencarian "<span x-text="guestSearch"></span>".</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Tampilan Mobile (Card List) -->
                <div class="sm:hidden space-y-2.5">
                    <template x-for="(g, idx) in filteredGuests" :key="idx">
                        <div class="p-3.5 rounded-2xl bg-stone-950 border border-white/10 space-y-2 text-xs">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <span class="text-sm font-bold text-white block" x-text="g.name"></span>
                                    <span class="text-[10px] text-stone-400" x-text="g.address ? 'di ' + g.address : 'Alamat: -'"></span>
                                    <span x-show="g.phone" class="block text-[10px] font-mono text-stone-400" x-text="'WA: ' + g.phone"></span>
                                </div>
                                <button type="button" @click="removeGuest(idx)" class="text-stone-500 hover:text-red-400 text-sm p-1">✕</button>
                            </div>

                            <div class="p-2 rounded-xl bg-stone-900 border border-white/5 text-[11px] font-mono text-amber-300 break-all select-all truncate">
                                <span x-text="getGuestUrl(g)"></span>
                            </div>

                            <div class="grid grid-cols-2 gap-2 pt-1">
                                <button type="button" @click="copyLink(g, idx)"
                                        class="min-h-[40px] py-2 px-3 rounded-xl text-xs font-semibold border transition active:scale-95 flex items-center justify-center gap-1.5"
                                        :class="copiedIndex === idx ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40' : 'bg-white/10 text-stone-200 border-white/10'">
                                    <span x-text="copiedIndex === idx ? 'Tersalin!' : 'Salin Link'"></span>
                                </button>

                                <a :href="getWaLink(g)" target="_blank"
                                   class="min-h-[40px] py-2 px-3 rounded-xl text-xs font-semibold bg-emerald-600 text-white transition active:scale-95 flex items-center justify-center gap-1.5">
                                    <span>Kirim WA</span>
                                    <span>↗</span>
                                </a>
                            </div>
                        </div>
                    </template>

                    <div x-show="filteredGuests.length === 0" class="py-8 text-center text-xs text-stone-500 italic">
                        <span x-show="guests.length === 0">Belum ada data tamu. Silakan upload file Excel atau tambah manual.</span>
                        <span x-show="guests.length > 0">Tidak ada tamu yang cocok dengan filter pencarian.</span>
                    </div>
                </div>
            </div>
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


    <!-- Alpine Component Script -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('manageApp', () => ({
             tab: 'guests',
             category: '{{ old('category', $invitation->category) }}',
             selectedTheme: '{{ old('theme_slug', $invitation->theme_slug) }}',
             hasSession2: {{ old('session_2_title', $invitation->event_data['session_2']['title'] ?? '') ? 'true' : 'false' }},
             musicMode: '{{ old('music_mode', $invitation->event_data['music_mode'] ?? 'ambient') }}',
             giftAccounts: {{ json_encode(old('gift_accounts', $invitation->event_data['gift_accounts'] ?? [['bank' => '', 'account_number' => '', 'account_name' => '']])) }},
             
             // --- STATE MANAJEMEN TAMU & EXCEL ---
             baseUrl: '{{ url('/u/' . $invitation->slug) }}',
             invitationTitle: '{{ addslashes($invitation->title) }}',
             storageKey: 'invitation_guests_{{ $invitation->slug }}',
             guests: [],
             guestSearch: '',
             newGuestName: '',
             newGuestAddress: '',
             newGuestPhone: '',
             newGuestNote: '',
             isDragging: false,
             uploadStatus: '',
             uploadError: '',
             copiedIndex: null,

             addGiftAccount() {
                 this.giftAccounts.push({ bank: '', account_number: '', account_name: '' });
             },
             removeGiftAccount(index) {
                 if (this.giftAccounts.length > 1) {
                     this.giftAccounts.splice(index, 1);
                 }
             },

             // Inisialisasi daftar tamu dari LocalStorage / Server
             initGuests() {
                 try {
                     const saved = localStorage.getItem(this.storageKey);
                     if (saved && JSON.parse(saved).length > 0) {
                         this.guests = JSON.parse(saved);
                     } else {
                         const serverGuests = {!! json_encode($invitation->event_data['guests'] ?? []) !!};
                         if (serverGuests && serverGuests.length > 0) {
                             this.guests = serverGuests;
                         } else {
                             this.guests = [
                                 { name: 'Nour Haqiki', address: 'Surabaya', phone: '081234567890', note: 'Keluarga' },
                                 { name: 'Bpk. Ahmad Subagyo', address: 'Jakarta', phone: '085712345678', note: 'Rekan Kerja' },
                                 { name: 'Siti Rahmawati & Pasangan', address: 'Yogyakarta', phone: '', note: 'Sahabat Kuliah' }
                             ];
                         }
                         this.saveGuests();
                     }
                 } catch(e) {
                     this.guests = [];
                 }
             },

             saveGuests() {
                 try {
                     localStorage.setItem(this.storageKey, JSON.stringify(this.guests));
                 } catch(e) {}
             },

             addGuestManual() {
                 if (!this.newGuestName.trim()) {
                     alert('Silakan masukkan nama tamu terlebih dahulu.');
                     return;
                 }
                 this.guests.push({
                     name: this.newGuestName.trim(),
                     address: this.newGuestAddress.trim(),
                     phone: this.newGuestPhone.trim(),
                     note: this.newGuestNote.trim()
                 });
                 this.newGuestName = '';
                 this.newGuestAddress = '';
                 this.newGuestPhone = '';
                 this.newGuestNote = '';
                 this.saveGuests();
             },

             removeGuest(index) {
                 if (confirm('Hapus tamu ' + this.guests[index].name + '?')) {
                     this.guests.splice(index, 1);
                     this.saveGuests();
                 }
             },

             clearAllGuests() {
                 if (confirm('Apakah Anda yakin ingin mengosongkan seluruh daftar tamu? Tindakan ini tidak dapat dibatalkan.')) {
                     this.guests = [];
                     this.saveGuests();
                 }
             },

             getGuestUrl(guest) {
                 let url = this.baseUrl;
                 const params = [];
                 if (guest.name && guest.name.trim()) {
                     params.push('to=' + encodeURIComponent(guest.name.trim()));
                 }
                 if (guest.address && guest.address.trim()) {
                     params.push('addr=' + encodeURIComponent(guest.address.trim()));
                 }
                 if (params.length > 0) {
                     url += '?' + params.join('&');
                 }
                 return url;
             },

             getWaMessage(guest) {
                 const name = guest.name ? guest.name.trim() : 'Bapak/Ibu/Saudara/i';
                 const address = guest.address && guest.address.trim() ? ` di ${guest.address.trim()}` : '';
                 const url = this.getGuestUrl(guest);
                 return `Kepada Yth. Bapak/Ibu/Saudara/i ${name}${address},\n\nTanpa mengurangi rasa hormat, perkenankan kami mengundang Bapak/Ibu/Saudara/i untuk menghadiri acara kami:\n\n*${this.invitationTitle}*\n\nInformasi lengkap dan konfirmasi kehadiran dapat diakses melalui tautan undangan personal berikut:\n${url}\n\nMerupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan untuk hadir dan memberikan doa restu.\n\nTerima kasih.`;
             },

             getWaLink(guest) {
                 let phone = (guest.phone || '').toString().replace(/[^0-9]/g, '');
                 if (phone.startsWith('0')) {
                     phone = '62' + phone.substring(1);
                 }
                 const msg = encodeURIComponent(this.getWaMessage(guest));
                 if (phone) {
                     return `https://wa.me/${phone}?text=${msg}`;
                 }
                 return `https://wa.me/?text=${msg}`;
             },

             copyLink(guest, index) {
                 const url = this.getGuestUrl(guest);
                 if (!navigator.clipboard) {
                     const el = document.createElement('textarea');
                     el.value = url;
                     document.body.appendChild(el);
                     el.select();
                     document.execCommand('copy');
                     document.body.removeChild(el);
                 } else {
                     navigator.clipboard.writeText(url);
                 }
                 this.copiedIndex = index;
                 setTimeout(() => { if (this.copiedIndex === index) this.copiedIndex = null; }, 2000);
             },

             handleFileUpload(event) {
                 const file = event.target.files[0];
                 if (!file) return;
                 this.parseExcelFile(file);
                 event.target.value = '';
             },

             handleDrop(event) {
                 this.isDragging = false;
                 const file = event.dataTransfer.files[0];
                 if (!file) return;
                 this.parseExcelFile(file);
             },

             parseExcelFile(file) {
                 this.uploadStatus = '';
                 this.uploadError = '';
                 const reader = new FileReader();
                 reader.onload = (e) => {
                     try {
                         const data = new Uint8Array(e.target.result);
                         const workbook = XLSX.read(data, { type: 'array' });
                         const firstSheetName = workbook.SheetNames[0];
                         const worksheet = workbook.Sheets[firstSheetName];
                         const json = XLSX.utils.sheet_to_json(worksheet, { defval: '' });

                         if (!json || json.length === 0) {
                             this.uploadError = 'File kosong atau tidak memiliki data baris tamu.';
                             return;
                         }

                         let added = 0;
                         json.forEach(row => {
                             let name = '';
                             let address = '';
                             let phone = '';
                             let note = '';

                             for (let key in row) {
                                 const cleanKey = key.trim().toLowerCase();
                                 if (cleanKey.includes('nama') || cleanKey === 'name') {
                                     name = row[key];
                                 } else if (cleanKey.includes('alamat') || cleanKey.includes('kota') || cleanKey.includes('tempat') || cleanKey === 'address') {
                                     address = row[key];
                                 } else if (cleanKey.includes('wa') || cleanKey.includes('whatsapp') || cleanKey.includes('telp') || cleanKey.includes('phone') || cleanKey.includes('hp')) {
                                     phone = row[key];
                                 } else if (cleanKey.includes('catatan') || cleanKey.includes('note') || cleanKey.includes('keterangan')) {
                                     note = row[key];
                                 }
                             }

                             if (name && String(name).trim()) {
                                 this.guests.push({
                                     name: String(name).trim(),
                                     address: String(address || '').trim(),
                                     phone: String(phone || '').trim(),
                                     note: String(note || '').trim()
                                 });
                                 added++;
                             }
                         });

                         this.saveGuests();
                         this.uploadStatus = `Berhasil menambahkan ${added} tamu dari file "${file.name}"!`;
                         setTimeout(() => { this.uploadStatus = ''; }, 6000);
                     } catch (err) {
                         this.uploadError = 'Gagal membaca file: ' + err.message;
                     }
                 };
                 reader.readAsArrayBuffer(file);
             },

             exportToExcel() {
                 if (this.guests.length === 0) {
                     alert('Belum ada data tamu untuk diekspor.');
                     return;
                 }
                 const exportData = this.guests.map((g, idx) => ({
                     'No': idx + 1,
                     'Nama Tamu': g.name,
                     'Alamat': g.address || '',
                     'No WhatsApp': g.phone,
                     'Link': this.getGuestUrl(g),
                     'Catatan': g.note
                 }));
                 const ws = XLSX.utils.json_to_sheet(exportData);
                 ws['!cols'] = [
                     { wch: 5 },
                     { wch: 25 },
                     { wch: 20 },
                     { wch: 18 },
                     { wch: 60 },
                     { wch: 25 }
                 ];
                 const wb = XLSX.utils.book_new();
                 XLSX.utils.book_append_sheet(wb, ws, "Daftar Tamu Undangan");
                 const safeTitle = this.invitationTitle.replace(/[^a-zA-Z0-9]/g, '_');
                 XLSX.writeFile(wb, `Daftar_Tamu_${safeTitle}.xlsx`);
             },

             get filteredGuests() {
                 if (!this.guestSearch.trim()) return this.guests;
                 const q = this.guestSearch.toLowerCase();
                 return this.guests.filter(g => 
                     (g.name && g.name.toLowerCase().includes(q)) ||
                     (g.address && g.address.toLowerCase().includes(q)) ||
                     (g.phone && g.phone.toLowerCase().includes(q)) ||
                     (g.note && g.note.toLowerCase().includes(q))
                 );
             }
         }));
        });
    </script>
</body>
</html>
