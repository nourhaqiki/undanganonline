<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Dashboard Admin | Undangan Online</title>

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
<body class="font-sans-main bg-stone-950 text-stone-100 overflow-x-hidden antialiased selection:bg-amber-400 selection:text-stone-950 min-h-screen pb-16">

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
                    <a href="{{ route('admin.dashboard') }}" class="px-3 py-1.5 rounded-lg bg-amber-400/20 text-amber-300">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.invitations') }}" class="px-3 py-1.5 rounded-lg text-stone-300 hover:text-white hover:bg-white/5 transition">
                        Manajemen Undangan
                    </a>
                    <a href="{{ route('admin.themes') }}" class="px-3 py-1.5 rounded-lg text-stone-300 hover:text-white hover:bg-white/5 transition">
                        Manajemen Tema
                    </a>
                </nav>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" target="_blank" class="text-xs text-stone-400 hover:text-stone-200 hidden sm:inline">
                    Lihat Beranda ↗
                </a>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="min-h-[36px] px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white/10 hover:bg-red-500/20 text-stone-300 hover:text-red-300 transition">
                        Keluar
                    </button>
                </form>
            </div>
        </div>

        <!-- Mobile Nav Bar -->
        <div class="md:hidden flex items-center justify-around border-t border-white/5 py-2 px-2 text-xs font-semibold bg-stone-900/90">
            <a href="{{ route('admin.dashboard') }}" class="text-amber-400">Dashboard</a>
            <a href="{{ route('admin.invitations') }}" class="text-stone-400 hover:text-white">Undangan</a>
            <a href="{{ route('admin.themes') }}" class="text-stone-400 hover:text-white">Tema</a>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 pt-8 space-y-8">
        <!-- Notifikasi -->
        @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-950/80 border border-emerald-500/40 text-emerald-200 text-xs flex items-center gap-2">
            <span>✓ {{ session('success') }}</span>
        </div>
        @endif

        <!-- Header Dashboard -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs uppercase tracking-wider text-amber-400 font-bold">Ringkasan Sistem</span>
                <h1 class="text-2xl font-bold text-white">Dashboard Administrator</h1>
            </div>
            <div>
                <a href="{{ route('home') }}#builder" target="_blank"
                   class="min-h-[44px] inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-xs font-semibold bg-amber-400 hover:bg-amber-300 text-stone-950 shadow-lg shadow-amber-400/20 transition active:scale-95">
                    <span>+ Buat Undangan Baru</span>
                </a>
            </div>
        </div>

        <!-- 4 Stat Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Undangan -->
            <div class="p-5 rounded-3xl bg-stone-900 border border-white/10 shadow-lg">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-stone-400">Total Undangan</span>
                    <span class="text-xl">💌</span>
                </div>
                <span class="text-3xl font-extrabold text-white block">{{ $totalInvitations }}</span>
                <span class="text-[11px] text-stone-500 mt-1 block">Telah diterbitkan</span>
            </div>

            <!-- Total Tamu Hadir -->
            <div class="p-5 rounded-3xl bg-emerald-950/40 border border-emerald-500/30 shadow-lg">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-emerald-400">Tamu Konfirmasi Hadir</span>
                    <span class="text-xl">👥</span>
                </div>
                <span class="text-3xl font-extrabold text-emerald-400 block">{{ $totalHadir }}</span>
                <span class="text-[11px] text-emerald-500/70 mt-1 block">RSVP positif</span>
            </div>

            <!-- Total Ucapan -->
            <div class="p-5 rounded-3xl bg-stone-900 border border-white/10 shadow-lg">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-stone-400">Total Ucapan / Doa</span>
                    <span class="text-xl">💬</span>
                </div>
                <span class="text-3xl font-extrabold text-white block">{{ $totalWishes }}</span>
                <span class="text-[11px] text-stone-500 mt-1 block">Dari buku tamu live</span>
            </div>

            <!-- Total Tema Aktif -->
            <div class="p-5 rounded-3xl bg-stone-900 border border-white/10 shadow-lg">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-stone-400">Tema Aktif</span>
                    <span class="text-xl">🎨</span>
                </div>
                <span class="text-3xl font-extrabold text-amber-400 block">{{ $totalActiveThemes }}</span>
                <span class="text-[11px] text-stone-500 mt-1 block">Siap digunakan pengguna</span>
            </div>
        </div>

        <!-- Tabel Undangan Terbaru -->
        <div class="p-6 rounded-3xl bg-stone-900 border border-white/10 shadow-xl space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-white uppercase tracking-wider">Undangan Terbaru</h2>
                    <p class="text-xs text-stone-400">Daftar undangan yang baru saja diterbitkan di platform</p>
                </div>
                <a href="{{ route('admin.invitations') }}" class="text-xs text-amber-400 hover:underline font-semibold">
                    Lihat Semua Undangan →
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-stone-300">
                    <thead class="text-[11px] uppercase tracking-wider text-stone-400 border-b border-white/10 bg-stone-950/40">
                        <tr>
                            <th class="py-3 px-3">Judul Undangan</th>
                            <th class="py-3 px-3">Kategori</th>
                            <th class="py-3 px-3">Tema</th>
                            <th class="py-3 px-3 text-center">Ucapan</th>
                            <th class="py-3 px-3">Dibuat</th>
                            <th class="py-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($recentInvitations as $inv)
                        <tr class="hover:bg-white/5 transition">
                            <td class="py-3.5 px-3">
                                <span class="font-bold text-white block">{{ $inv->title }}</span>
                                <span class="text-[11px] text-stone-400 font-mono">/u/{{ $inv->slug }}</span>
                            </td>
                            <td class="py-3.5 px-3">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase bg-white/5 text-stone-300">
                                    {{ $inv->category }}
                                </span>
                            </td>
                            <td class="py-3.5 px-3">
                                <span class="inline-flex items-center gap-1.5 font-medium text-stone-300">
                                    <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $inv->theme->accent_color ?? '#D4AF37' }};"></span>
                                    <span>{{ $inv->theme->name ?? $inv->theme_slug }}</span>
                                </span>
                            </td>
                            <td class="py-3.5 px-3 text-center font-bold text-amber-400">
                                {{ $inv->wishes_count }}
                            </td>
                            <td class="py-3.5 px-3 text-stone-400 text-[11px]">
                                {{ $inv->created_at->diffForHumans() }}
                            </td>
                            <td class="py-3.5 px-3 text-right">
                                <div class="inline-flex items-center gap-1.5 justify-end">
                                    <a href="{{ route('invitation.show', $inv->slug) }}" target="_blank"
                                       class="min-h-[36px] inline-flex items-center px-3 py-1 rounded-lg text-[11px] font-semibold bg-white/10 hover:bg-white/20 text-stone-200 transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-amber-400">
                                        Lihat Live ↗
                                    </a>
                                    <a href="{{ route('invitation.manage', ['slug' => $inv->slug, 'token' => $inv->edit_token]) }}" target="_blank"
                                       class="min-h-[36px] inline-flex items-center px-3 py-1 rounded-lg text-[11px] font-semibold bg-amber-400/20 hover:bg-amber-400/30 text-amber-300 transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-amber-400">
                                        Kelola
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-stone-500 italic">
                                Belum ada undangan yang dibuat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>
