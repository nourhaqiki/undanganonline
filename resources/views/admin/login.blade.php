<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Login Administrator | Undangan Online</title>

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
<body class="font-sans-main bg-stone-950 text-stone-100 overflow-x-hidden antialiased selection:bg-amber-400 selection:text-stone-950 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full space-y-6">
        <!-- Logo & Header -->
        <div class="text-center space-y-2">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5">
                <span class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-amber-400 to-amber-600 flex items-center justify-center text-stone-950 font-bold text-xl font-brand shadow-lg shadow-amber-500/20">
                    U
                </span>
                <span class="font-brand font-bold text-xl text-white tracking-wide">
                    Undangan<span class="text-amber-400">Online</span>
                </span>
            </a>
            <h1 class="text-xl font-bold text-white pt-2">Masuk Panel Administrator</h1>
            <p class="text-xs text-stone-400">Autentikasi khusus pengelola sistem undangan online</p>
        </div>

        <!-- Form Login Card -->
        <div class="p-6 sm:p-8 rounded-3xl bg-stone-900 border border-white/10 shadow-2xl space-y-5">
            @if(isset($errors) && $errors->any())
            <div class="p-3.5 rounded-2xl bg-red-950/70 border border-red-500/40 text-red-200 text-xs">
                <span class="font-semibold block mb-0.5">Akses Ditolak</span>
                {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-stone-200 mb-1.5">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', 'admin@undangan.test') }}" required autofocus
                           class="w-full min-h-[44px] px-4 py-2.5 rounded-xl bg-stone-950 border border-white/15 text-white text-xs placeholder:text-stone-500 focus:outline-none focus:ring-2 focus:ring-amber-400 focus-visible:ring-2 focus-visible:ring-amber-400">
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-stone-200 mb-1.5">Kata Sandi</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••"
                           class="w-full min-h-[44px] px-4 py-2.5 rounded-xl bg-stone-950 border border-white/15 text-white text-xs placeholder:text-stone-500 focus:outline-none focus:ring-2 focus:ring-amber-400 focus-visible:ring-2 focus-visible:ring-amber-400">
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-xs text-stone-300">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-stone-950 border-white/20 text-amber-500 focus:ring-0">
                        <span>Ingat Sesi Masuk</span>
                    </label>
                </div>

                <button type="submit" 
                        class="w-full min-h-[44px] py-3 rounded-xl font-bold text-xs uppercase tracking-wider bg-amber-400 hover:bg-amber-300 text-stone-950 shadow-lg shadow-amber-400/20 active:scale-95 transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-400">
                    Masuk ke Dashboard Admin
                </button>
            </form>
        </div>

        <div class="text-center">
            <a href="{{ route('home') }}" class="text-xs text-stone-500 hover:text-stone-300">
                ← Kembali ke Beranda Utama
            </a>
        </div>
    </div>

</body>
</html>
