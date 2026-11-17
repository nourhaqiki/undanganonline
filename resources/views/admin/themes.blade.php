<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Manajemen Tema Extensible | Undangan Online</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Tailwind CSS v4 & Alpine.js CDN -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .font-brand { font-family: 'Playfair Display', Georgia, serif; }
        .font-sans-main { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
    </style>
</head>
<body class="font-sans-main bg-stone-950 text-stone-100 overflow-x-hidden antialiased selection:bg-amber-400 selection:text-stone-950 min-h-screen pb-16"
      x-data="{
          accentColor: '#D4AF37'
      }">

    <!-- Admin Navigation Bar -->
    <header class="border-b border-white/10 bg-stone-900 sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-gradient-to-tr from-amber-400 to-amber-600 flex items-center justify-center text-stone-950 font-bold font-brand">
                        U
                    </span>
                    <span class="font-brand font-bold text-base text-white tracking-wide">
                        Admin<span class="text-amber-400">Panel</span>
                    </span>
                </a>

                <nav class="hidden md:flex items-center gap-2 text-xs font-semibold">
                    <a href="{{ route('admin.dashboard') }}" class="px-3 py-1.5 rounded-lg text-stone-300 hover:text-white hover:bg-white/5 transition">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.invitations') }}" class="px-3 py-1.5 rounded-lg text-stone-300 hover:text-white hover:bg-white/5 transition">
                        Manajemen Undangan
                    </a>
                    <a href="{{ route('admin.themes') }}" class="px-3 py-1.5 rounded-lg bg-amber-400/20 text-amber-300 font-bold">
                        Manajemen Tema
                    </a>
                </nav>
            </div>

            <div class="flex items-center gap-3">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="min-h-[36px] px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white/10 hover:bg-red-500/20 text-stone-300 hover:text-red-300 transition">
                        Keluar
                    </button>
                </form>
            </div>
        </div>

        <div class="md:hidden flex items-center justify-around border-t border-white/5 py-2 px-2 text-xs font-semibold bg-stone-900/90">
            <a href="{{ route('admin.dashboard') }}" class="text-stone-400 hover:text-white">Dashboard</a>
            <a href="{{ route('admin.invitations') }}" class="text-stone-400 hover:text-white">Undangan</a>
            <a href="{{ route('admin.themes') }}" class="text-amber-400">Tema</a>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 pt-8 space-y-8">
        @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-950/80 border border-emerald-500/40 text-emerald-200 text-xs flex items-center gap-2">
            <span>✓ {{ session('success') }}</span>
        </div>
        @endif

        @if(isset($errors) && $errors->any())
        <div class="p-4 rounded-2xl bg-red-950/80 border border-red-500/40 text-red-200 text-xs">
            <strong class="font-bold block mb-1">Gagal menyimpan tema baru:</strong>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs uppercase tracking-wider text-amber-400 font-bold">Desain &amp; Arsitektur</span>
                <h1 class="text-2xl font-bold text-white">Sistem Tema Extensible</h1>
                <p class="text-xs text-stone-400 mt-1">
                    Aktifkan/nonaktifkan tema atau tambahkan tema baru dengan kloning otomatis file Blade.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Kolom Kiri (2 Kolom): Daftar Tema -->
            <div class="lg:col-span-2 space-y-4">
                <h2 class="text-sm font-bold text-white uppercase tracking-wider">Daftar Tema Tersedia ({{ $themes->count() }})</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($themes as $theme)
                    <div class="p-5 rounded-3xl bg-stone-900 border border-white/10 shadow-lg flex flex-col justify-between space-y-4">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-4 h-4 rounded-full border border-white/20 shadow" style="background-color: {{ $theme->accent_color }};"></span>
                                    <h3 class="text-sm font-bold text-white">{{ $theme->name }}</h3>
                                </div>
                                <span class="text-[10px] uppercase font-semibold px-2 py-0.5 rounded-full {{ $theme->is_active ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-stone-800 text-stone-500' }}">
                                    {{ $theme->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>

                            <p class="text-xs text-stone-400 leading-relaxed mb-3">
                                {{ $theme->description ?? 'Tidak ada deskripsi.' }}
                            </p>

                            <div class="flex items-center gap-2 text-[11px] text-stone-500 font-mono">
                                <span>Slug: {{ $theme->slug }}</span>
                                <span>•</span>
                                <span>{{ $theme->category }}</span>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-white/5 flex items-center justify-between text-xs">
                            <span class="text-stone-400 text-[11px]">
                                Dipakai: <strong class="text-white">{{ $theme->invitations_count }}</strong> undangan
                            </span>

                            <form action="{{ route('admin.themes.toggle', $theme->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="px-3 py-1.5 rounded-xl font-semibold text-xs transition {{ $theme->is_active ? 'bg-white/10 hover:bg-red-500/20 text-stone-300 hover:text-red-300' : 'bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300' }}">
                                    {{ $theme->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Kolom Kanan (1 Kolom): Form Tambah Tema Baru -->
            <div class="space-y-4">
                <h2 class="text-sm font-bold text-amber-400 uppercase tracking-wider">Tambah Tema Baru</h2>

                <div class="p-6 rounded-3xl bg-stone-900 border border-white/10 shadow-xl">
                    <form action="{{ route('admin.themes.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-semibold text-stone-300 mb-1">Nama Tema</label>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Adat Minang Modern"
                                   class="w-full px-4 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs focus:ring-2 focus:ring-amber-400 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-stone-300 mb-1">Slug Nama File</label>
                            <input type="text" name="slug" value="{{ old('slug') }}" required placeholder="Contoh: minang"
                                   class="w-full px-4 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs font-mono focus:ring-2 focus:ring-amber-400 focus:outline-none">
                            <p class="text-[10px] text-stone-500 mt-1">Akan membuat file: <code>themes/{slug}.blade.php</code></p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-stone-300 mb-1">Kategori</label>
                            <select name="category" class="w-full px-3 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                                <option value="pernikahan">Pernikahan</option>
                                <option value="tunangan">Tunangan</option>
                                <option value="bisnis">Bisnis</option>
                                <option value="semua">Semua</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-stone-300 mb-1">Warna Aksen Preview (Hex)</label>
                            <div class="flex items-center gap-2">
                                <input type="color" x-model="accentColor" class="w-9 h-9 rounded-lg bg-transparent border-0 cursor-pointer p-0">
                                <input type="text" name="accent_color" x-model="accentColor" required
                                       class="flex-1 px-4 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs font-mono uppercase">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-stone-300 mb-1">Kloning Struktur Dari Tema</label>
                            <select name="source_theme" class="w-full px-3 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                                <option value="_base-template">_base-template (Template Standar Bersih)</option>
                                @foreach($themes as $t)
                                    <option value="{{ $t->slug }}">{{ $t->name }} ({{ $t->slug }}.blade.php)</option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-stone-500 mt-1">File sumber akan diduplikasi otomatis ke tema baru.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-stone-300 mb-1">Deskripsi Singkat Gaya Tema</label>
                            <textarea name="description" rows="2" placeholder="Gaya nuansa Minang klasik dengan sentuhan ornamen rumah gadang..."
                                      class="w-full px-4 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs focus:ring-2 focus:ring-amber-400 focus:outline-none">{{ old('description') }}</textarea>
                        </div>

                        <button type="submit" 
                                class="w-full min-h-[44px] py-3 rounded-xl font-bold text-xs uppercase tracking-wider bg-amber-400 hover:bg-amber-300 text-stone-950 shadow-lg shadow-amber-400/20 active:scale-95 transition">
                            + Tambah &amp; Kloning Tema
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
