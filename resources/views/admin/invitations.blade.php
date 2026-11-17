<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Manajemen Undangan &amp; Moderasi | Undangan Online</title>

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
          activeWishesModal: false,
          activeInvitationTitle: '',
          activeWishes: [],

          openWishes(title, wishes) {
              this.activeInvitationTitle = title;
              this.activeWishes = wishes;
              this.activeWishesModal = true;
          },
          closeWishes() {
              this.activeWishesModal = false;
          }
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
                    <a href="{{ route('admin.invitations') }}" class="px-3 py-1.5 rounded-lg bg-amber-400/20 text-amber-300 font-bold">
                        Manajemen Undangan
                    </a>
                    <a href="{{ route('admin.themes') }}" class="px-3 py-1.5 rounded-lg text-stone-300 hover:text-white hover:bg-white/5 transition">
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
            <a href="{{ route('admin.invitations') }}" class="text-amber-400">Undangan</a>
            <a href="{{ route('admin.themes') }}" class="text-stone-400 hover:text-white">Tema</a>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 pt-8 space-y-6">
        @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-950/80 border border-emerald-500/40 text-emerald-200 text-xs flex items-center gap-2">
            <span>✓ {{ session('success') }}</span>
        </div>
        @endif

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs uppercase tracking-wider text-amber-400 font-bold">Data Undangan</span>
                <h1 class="text-2xl font-bold text-white">Manajemen Undangan &amp; Moderasi Ucapan</h1>
            </div>
            <div>
                <span class="text-xs text-stone-400">Total: <strong>{{ $invitations->total() }}</strong> Undangan</span>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <form method="GET" action="{{ route('admin.invitations') }}" class="p-4 rounded-2xl bg-stone-900 border border-white/10 flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau slug undangan..."
                       class="w-full px-4 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs focus:ring-2 focus:ring-amber-400 focus:outline-none">
            </div>

            <div class="sm:w-48">
                <select name="category" class="w-full px-3 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs">
                    <option value="semua">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>
                            {{ ucfirst($cat) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="min-h-[40px] px-5 py-2.5 rounded-xl text-xs font-semibold bg-amber-400 hover:bg-amber-300 text-stone-950 transition">
                    Filter
                </button>
                @if(request('search') || (request('category') && request('category') !== 'semua'))
                <a href="{{ route('admin.invitations') }}" class="min-h-[40px] px-3.5 py-2.5 rounded-xl text-xs font-semibold bg-white/10 hover:bg-white/20 text-stone-300 transition flex items-center">
                    Reset
                </a>
                @endif
            </div>
        </form>

        <!-- Tabel Undangan -->
        <div class="p-6 rounded-3xl bg-stone-900 border border-white/10 shadow-xl overflow-hidden">
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
                        @forelse($invitations as $inv)
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
                            <td class="py-3.5 px-3 text-center">
                                <button type="button" 
                                        @click="openWishes('{{ addslashes($inv->title) }}', {{ json_encode($inv->wishes->map(fn($w) => ['id' => $w->id, 'guest_name' => $w->guest_name, 'attendance' => $w->attendance, 'message' => $w->message, 'time' => $w->created_at->format('d/m/Y H:i'), 'delete_url' => route('admin.wishes.destroy', $w->id)])) }})"
                                        class="px-2 py-1 rounded-lg text-xs font-bold {{ $inv->wishes_count > 0 ? 'bg-amber-400/20 text-amber-300 hover:bg-amber-400/30' : 'bg-stone-800 text-stone-500' }} transition">
                                    {{ $inv->wishes_count }} Ucapan
                                </button>
                            </td>
                            <td class="py-3.5 px-3 text-stone-400 text-[11px]">
                                {{ $inv->created_at->format('d M Y') }}
                            </td>
                            <td class="py-3.5 px-3 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('invitation.show', $inv->slug) }}" target="_blank"
                                       class="px-2.5 py-1.5 rounded-lg text-[11px] font-semibold bg-white/10 hover:bg-white/20 text-stone-200 transition">
                                        Live
                                    </a>
                                    <a href="{{ route('invitation.manage', ['slug' => $inv->slug, 'token' => $inv->edit_token]) }}" target="_blank"
                                       class="px-2.5 py-1.5 rounded-lg text-[11px] font-semibold bg-amber-400/20 hover:bg-amber-400/30 text-amber-300 transition">
                                        Kelola
                                    </a>
                                    <form action="{{ route('admin.invitations.destroy', $inv->id) }}" method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus undangan ini beserta seluruh file media? Tindakan ini tidak dapat dibatalkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1.5 rounded-lg text-[11px] font-semibold bg-red-500/10 hover:bg-red-500/20 text-red-400 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-stone-500 italic">
                                Tidak ada undangan yang cocok dengan kriteria pencarian.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($invitations->hasPages())
            <div class="pt-6 border-t border-white/10">
                {{ $invitations->links() }}
            </div>
            @endif
        </div>
    </main>

    <!-- Modal Moderasi Ucapan Tamu -->
    <div x-cloak x-show="activeWishesModal"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
         @keydown.escape.window="closeWishes()">
        <div class="max-w-lg w-full bg-stone-900 border border-white/10 rounded-3xl p-6 shadow-2xl space-y-4 max-h-[85vh] flex flex-col"
             @click.away="closeWishes()">
            <div class="flex items-center justify-between pb-3 border-b border-white/10">
                <div>
                    <span class="text-[10px] uppercase font-bold text-amber-400 tracking-wider">Moderasi Pesan Buku Tamu</span>
                    <h2 class="text-sm font-bold text-white truncate max-w-xs" x-text="activeInvitationTitle"></h2>
                </div>
                <button type="button" @click="closeWishes()" class="p-1.5 text-stone-400 hover:text-white rounded-lg">✕</button>
            </div>

            <div class="overflow-y-auto space-y-3 flex-1 pr-1">
                <template x-for="w in activeWishes" :key="w.id">
                    <div class="p-3.5 rounded-2xl bg-stone-950 border border-white/5 space-y-1.5 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-white" x-text="w.guest_name"></span>
                            <span :class="w.attendance === 'hadir' ? 'text-emerald-400 bg-emerald-500/10' : 'text-stone-400 bg-stone-800'"
                                  class="px-2 py-0.5 rounded-full text-[10px] font-semibold"
                                  x-text="w.attendance === 'hadir' ? 'Hadir' : 'Tidak Hadir'"></span>
                        </div>
                        <p class="text-stone-300 leading-relaxed" x-text="w.message"></p>
                        <div class="flex items-center justify-between pt-1 border-t border-white/5">
                            <span class="text-[10px] text-stone-500" x-text="w.time"></span>
                            <form :action="w.delete_url" method="POST" onsubmit="return confirm('Hapus ucapan tamu ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[11px] text-red-400 hover:text-red-300 font-semibold">
                                    Hapus Ucapan
                                </button>
                            </form>
                        </div>
                    </div>
                </template>

                <div x-show="activeWishes.length === 0" class="py-8 text-center text-xs text-stone-500 italic">
                    Belum ada ucapan pada undangan ini.
                </div>
            </div>

            <div class="pt-3 border-t border-white/10 text-right">
                <button type="button" @click="closeWishes()" class="px-4 py-2 rounded-xl text-xs font-semibold bg-white/10 hover:bg-white/15 text-stone-200">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</body>
</html>
