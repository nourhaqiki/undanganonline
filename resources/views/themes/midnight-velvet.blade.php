@php
    $evData = $invitation->event_data ?? [];
    $isPreview = \Illuminate\Support\Str::startsWith($invitation->slug ?? '', 'demo-') || empty($evData);

    $primaryName = !empty($evData['primary_name']) ? $evData['primary_name'] : 'Adrian Mahendra, S.Ars';
    $secondaryName = !empty($evData['secondary_name']) ? $evData['secondary_name'] : 'Elena Natasya Putri';
    $primaryNick = \Illuminate\Support\Str::before(trim($primaryName), ' ');
    $secondaryNick = \Illuminate\Support\Str::before(trim($secondaryName), ' ');
    $hasSecondary = !empty($evData['secondary_name']) || $isPreview;

    $groomDisplayName = $primaryName;
    $groomNick = $primaryNick;
    $brideDisplayName = $hasSecondary ? $secondaryName : $primaryName;
    $brideNick = $hasSecondary ? $secondaryNick : $primaryNick;

    $coupleDisplay = $hasSecondary
        ? e($primaryNick) . ' &amp; ' . e($secondaryNick)
        : e($primaryName);

    $couplePlain = $hasSecondary
        ? $primaryNick . ' & ' . $secondaryNick
        : $primaryName;

    $initials = $hasSecondary
        ? strtoupper(\Illuminate\Support\Str::substr($primaryNick, 0, 1) . ' & ' . \Illuminate\Support\Str::substr($secondaryNick, 0, 1))
        : strtoupper(\Illuminate\Support\Str::substr($primaryNick, 0, 2));

    $initialsCompact = str_replace(' ', '', $initials);

    $primaryParents = !empty($evData['primary_title_parents'])
        ? $evData['primary_title_parents']
        : 'Putra Pertama dari Bpk. Hendra Mahendra & Ibu Clarissa Wijaya';
    $secondaryParents = !empty($evData['secondary_title_parents'])
        ? $evData['secondary_title_parents']
        : 'Putri Bungsu dari Bpk. Surya Kusuma & Ibu Ratna Dewi';

    $primaryIg = !empty($evData['primary_instagram']) ? ltrim($evData['primary_instagram'], '@') : ($isPreview ? 'adrian.mahendra' : '');
    $secondaryIg = !empty($evData['secondary_instagram']) ? ltrim($evData['secondary_instagram'], '@') : ($isPreview ? 'elenanatasya' : '');

    $greeting = !empty($evData['greeting'])
        ? $evData['greeting']
        : 'Two Hearts, One Midnight Flame';
    $quote = !empty($evData['quote'])
        ? $evData['quote']
        : 'Cinta dewasa bukan sekadar janji di bawah cahaya matahari, melainkan keberanian untuk saling menggenggam dalam gelapnya malam.';

    $s1 = $evData['session_1'] ?? [];
    $s1Title = !empty($s1['title']) ? $s1['title'] : 'Sacred Vows (Akad)';
    $s1Date = !empty($s1['date']) ? $s1['date'] : '2027-02-14';
    $s1Time = !empty($s1['time']) ? $s1['time'] : '16:00 WIB';
    $s1Venue = !empty($s1['venue']) ? $s1['venue'] : 'The Glasshouse Chapel, Dharmawangsa';
    $s1Address = !empty($s1['address']) ? $s1['address'] : 'Jl. Brawijaya Raya No. 26, Kebayoran Baru, Jakarta Selatan';
    $s1Maps = array_key_exists('maps_url', $s1) ? $s1['maps_url'] : 'https://maps.google.com/?q=The+Langham+Jakarta';

    $s2 = $evData['session_2'] ?? [];
    $s2Enabled = $isPreview || !empty($s2['enabled']) || !empty($s2['title']);
    $s2Title = !empty($s2['title']) ? $s2['title'] : 'Midnight Candlelight Gala';
    $s2Date = !empty($s2['date']) ? $s2['date'] : $s1Date;
    $s2Time = !empty($s2['time']) ? $s2['time'] : '19:30 - 23:00 WIB';
    $s2Venue = !empty($s2['venue']) ? $s2['venue'] : 'Velvet Ballroom, The Langham Jakarta';
    $s2Address = !empty($s2['address']) ? $s2['address'] : 'District 8, SCBD Lot 28, Jakarta Selatan';
    $s2Maps = array_key_exists('maps_url', $s2) ? $s2['maps_url'] : 'https://maps.google.com/?q=The+Langham+Jakarta';

    $s1DateCarbon = \Carbon\Carbon::parse($s1Date)->locale('id');
    $s1DateFormatted = $s1DateCarbon->isoFormat('dddd, D MMMM Y');
    $s1DateShort = $s1DateCarbon->format('d.m.Y');
    $s1DateHero = $s1DateCarbon->isoFormat('D MMMM Y');
    $s2DateFormatted = \Carbon\Carbon::parse($s2Date)->locale('id')->isoFormat('dddd, D MMMM Y');
    $s1City = trim(\Illuminate\Support\Str::afterLast($s1Address, ','));

    $s1TimeClean = preg_replace('/[^0-9:]/', '', explode('-', str_replace('–', '-', $s1Time))[0] ?? '16:00');
    if (strlen($s1TimeClean) === 5 && str_contains($s1TimeClean, ':')) {
        $targetIso = $s1Date . 'T' . $s1TimeClean . ':00+07:00';
    } elseif (strlen(str_replace('.', ':', $s1TimeClean)) === 5) {
        $targetIso = $s1Date . 'T' . str_replace('.', ':', $s1TimeClean) . ':00+07:00';
    } else {
        $targetIso = !empty($targetDate) ? $targetDate : ($s1Date . 'T16:00:00+07:00');
    }

    $coverImage = !empty($evData['cover_image']) ? asset('storage/' . $evData['cover_image']) : '';
    $groomPhoto = !empty($evData['groom_photo']) ? asset('storage/' . $evData['groom_photo']) : '';
    $bridePhoto = !empty($evData['bride_photo']) ? asset('storage/' . $evData['bride_photo']) : '';
    $galleryImages = !empty($evData['gallery_images']) && is_array($evData['gallery_images']) ? $evData['gallery_images'] : [];
    $rundownItems = !empty($evData['rundown']) && is_array($evData['rundown']) ? $evData['rundown'] : [];

    $giftAccounts = !empty($evData['gift_accounts']) && is_array($evData['gift_accounts'])
        ? $evData['gift_accounts']
        : ($isPreview ? [
            ['bank' => 'BANK BCA', 'account_number' => '883099124410', 'account_name' => 'Adrian Mahendra'],
            ['bank' => 'BANK MANDIRI', 'account_number' => '124000897721', 'account_name' => 'Elena Natasya Putri'],
        ] : []);

    $musicMode = $evData['music_mode'] ?? 'ambient';
    $musicUrl = ($musicMode === 'custom' && !empty($evData['music_file'])) ? asset('storage/' . $evData['music_file']) : '';

    $guestNameVal = !empty($guestName) ? $guestName : 'Tamu Spesial';
    $guestAddrVal = !empty($guestAddress) ? $guestAddress : '';
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $invitation->title ?? ($couplePlain . ' — The Midnight Velvet Union') }}</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts: Cinzel, Cormorant Garamond, Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Tailwind Custom Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        obsidian: '#0B0204',
                        velvet: '#1A0509',
                        crimson: '#3D0A14',
                        burgundy: '#590D1D',
                        rosegold: '#E5B299',
                        champagne: '#F5D5C6',
                        ember: '#FF4D6D'
                    },
                    fontFamily: {
                        display: ['Cinzel', 'serif'],
                        editorial: ['Cormorant Garamond', 'serif'],
                        sans: ['Plus Jakarta Sans', 'sans-serif']
                    },
                    animation: {
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'spin-slow': 'spin 12s linear infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'shimmer': 'shimmer 3s linear infinite'
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-12px)' }
                        },
                        shimmer: {
                            '0%': { backgroundPosition: '-200% center' },
                            '100%': { backgroundPosition: '200% center' }
                        }
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --color-obsidian: #0B0204;
            --color-velvet: #1A0509;
            --color-rosegold: #E5B299;
            --color-champagne: #F5D5C6;
        }

        body {
            background-color: var(--color-obsidian);
            color: var(--color-champagne);
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
            -webkit-tap-highlight-color: transparent;
        }

        .text-foil {
            background: linear-gradient(
                120deg,
                #C98A70 0%,
                #F5D5C6 25%,
                #E5B299 50%,
                #FFF0E8 75%,
                #C98A70 100%
            );
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shimmer 5s linear infinite;
        }

        .velvet-card {
            background: linear-gradient(145deg, rgba(45, 9, 16, 0.72) 0%, rgba(15, 3, 6, 0.88) 100%);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(229, 178, 153, 0.22);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.7), inset 0 1px 0 rgba(245, 213, 198, 0.15);
        }

        .velvet-radial {
            background: radial-gradient(circle at 50% 30%, rgba(92, 13, 29, 0.45) 0%, rgba(26, 5, 9, 0.85) 55%, #0B0204 100%);
        }

        .btn-velvet-cta {
            background: linear-gradient(135deg, #6E1124 0%, #3D0A14 50%, #590D1D 100%);
            border: 1px solid rgba(245, 213, 198, 0.5);
            box-shadow: 0 0 25px rgba(229, 178, 153, 0.25), inset 0 0 15px rgba(255, 77, 109, 0.3);
            transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .btn-velvet-cta:hover, .btn-velvet-cta:active {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 0 38px rgba(229, 178, 153, 0.5), inset 0 0 20px rgba(255, 77, 109, 0.5);
            border-color: #F5D5C6;
        }

        .curtain-panel {
            transition: transform 1.6s cubic-bezier(0.77, 0, 0.175, 1), opacity 1.4s ease;
        }
        .curtain-open-left {
            transform: translateX(-105%) skewX(-3deg);
            opacity: 0;
        }
        .curtain-open-right {
            transform: translateX(105%) skewX(3deg);
            opacity: 0;
        }

        .reveal-up {
            opacity: 0;
            transform: translateY(32px);
            transition: all 0.9s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .reveal-up.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #0B0204; }
        ::-webkit-scrollbar-thumb { background: #590D1D; border-radius: 10px; }
    </style>
</head>
<body class="antialiased selection:bg-burgundy selection:text-champagne">

    <!-- =====================================================================
         STAGE 1: PURE-CODE CINEMATIC OPENING VIDEO INTRO
    ====================================================================== -->
    <section id="stage-intro-video" class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-obsidian overflow-hidden transition-opacity duration-1000">
        <canvas id="intro-video-canvas" class="absolute inset-0 w-full h-full"></canvas>

        <div class="absolute top-0 left-0 right-0 h-10 sm:h-14 bg-black/80 backdrop-blur-sm border-b border-rosegold/10 z-10 flex items-center justify-between px-5">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-ember animate-ping"></span>
                <span class="text-[10px] tracking-[0.3em] uppercase text-rosegold/80 font-medium">Cinema Prologue • 4K Procedural</span>
            </div>
            <button id="btn-skip-intro" type="button" class="text-[11px] tracking-[0.2em] uppercase text-champagne/80 hover:text-white border border-rosegold/30 hover:border-rosegold px-3 py-1 rounded-full transition-all backdrop-blur-md cursor-pointer">
                Lewati Intro &rarr;
            </button>
        </div>

        <div class="relative z-10 text-center px-6 max-w-lg mx-auto pointer-events-none">
            <div id="intro-emblem" class="w-24 h-24 mx-auto mb-6 relative flex items-center justify-center opacity-0 scale-75 transition-all duration-1000">
                <svg viewBox="0 0 120 120" class="w-full h-full drop-shadow-[0_0_20px_rgba(229,178,153,0.45)]">
                    <defs>
                        <linearGradient id="foilGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#F5D5C6"/>
                            <stop offset="50%" stop-color="#E5B299"/>
                            <stop offset="100%" stop-color="#9E5A47"/>
                        </linearGradient>
                    </defs>
                    <circle cx="60" cy="60" r="54" fill="none" stroke="url(#foilGrad)" stroke-width="0.75" stroke-dasharray="4 4" class="origin-center animate-spin-slow"/>
                    <circle cx="60" cy="60" r="46" fill="none" stroke="url(#foilGrad)" stroke-width="1.2"/>
                    <path d="M60 86 C60 86 30 66 30 46 C30 36 38 30 47 30 C53 30 58 34 60 39 C62 34 67 30 73 30 C82 30 90 36 90 46 C90 66 60 86 60 86 Z"
                          fill="rgba(92, 13, 29, 0.45)" stroke="url(#foilGrad)" stroke-width="1.5"/>
                </svg>
                <span class="absolute font-display text-lg tracking-widest text-champagne font-semibold">{{ $initialsCompact }}</span>
            </div>

            <p id="intro-line-1" class="font-editorial italic text-xl sm:text-2xl text-rosegold/90 tracking-wide opacity-0 translate-y-4 transition-all duration-1000">
                "When two souls surrender to the dark..."
            </p>
            <h1 id="intro-line-2" class="font-display text-2xl sm:text-4xl tracking-[0.22em] uppercase text-foil font-semibold mt-3 opacity-0 translate-y-4 transition-all duration-1000">
                A Night of Passion &amp; Eternity
            </h1>
            <p id="intro-line-3" class="text-xs tracking-[0.35em] uppercase text-champagne/70 mt-4 opacity-0 transition-all duration-1000">
                {!! $coupleDisplay !!} &bull; {{ $s1DateShort }}
            </p>
        </div>

        <div class="absolute bottom-0 left-0 right-0 h-14 bg-black/85 backdrop-blur-md border-t border-rosegold/15 z-10 px-6 flex flex-col justify-center">
            <div class="flex justify-between text-[10px] tracking-widest text-rosegold/70 uppercase mb-1.5">
                <span>Prologue Film</span>
                <span id="intro-timer">00:00 / 00:07</span>
            </div>
            <div class="w-full h-1 bg-velvet rounded-full overflow-hidden">
                <div id="intro-progress-bar" class="h-full bg-gradient-to-r from-burgundy via-ember to-champagne w-0 transition-all duration-100 ease-linear"></div>
            </div>
        </div>
    </section>

    <!-- =====================================================================
         STAGE 2: INTERACTIVE VELVET GUEST COVER
    ====================================================================== -->
    <section id="stage-guest-cover" class="fixed inset-0 z-40 hidden items-center justify-center overflow-hidden">
        <div id="curtain-left" class="curtain-panel absolute inset-y-0 left-0 w-1/2 bg-gradient-to-r from-[#0B0204] via-[#24060C] to-[#360912] border-r border-rosegold/20 shadow-[20px_0_50px_rgba(0,0,0,0.9)]">
            <div class="w-full h-full opacity-30 bg-[repeating-linear-gradient(90deg,transparent,transparent_25px,rgba(0,0,0,0.6)_50px,rgba(229,178,153,0.06)_75px)]"></div>
        </div>

        <div id="curtain-right" class="curtain-panel absolute inset-y-0 right-0 w-1/2 bg-gradient-to-l from-[#0B0204] via-[#24060C] to-[#360912] border-l border-rosegold/20 shadow-[-20px_0_50px_rgba(0,0,0,0.9)]">
            <div class="w-full h-full opacity-30 bg-[repeating-linear-gradient(90deg,transparent,transparent_25px,rgba(0,0,0,0.6)_50px,rgba(229,178,153,0.06)_75px)]"></div>
        </div>

        <div id="cover-content" class="relative z-10 w-full max-w-md mx-auto px-5 py-8 text-center transition-all duration-700">
            <div class="velvet-card rounded-3xl p-7 sm:p-10 relative overflow-hidden">
                <svg class="absolute top-3 left-3 w-12 h-12 text-rosegold/30" viewBox="0 0 64 64" fill="none" stroke="currentColor">
                    <path d="M4 60 C4 25 25 4 60 4 M12 60 C12 32 32 12 60 12" stroke-width="1"/>
                    <circle cx="16" cy="16" r="2.5" fill="currentColor"/>
                </svg>
                <svg class="absolute bottom-3 right-3 w-12 h-12 text-rosegold/30 rotate-180" viewBox="0 0 64 64" fill="none" stroke="currentColor">
                    <path d="M4 60 C4 25 25 4 60 4 M12 60 C12 32 32 12 60 12" stroke-width="1"/>
                    <circle cx="16" cy="16" r="2.5" fill="currentColor"/>
                </svg>

                <p class="text-[11px] tracking-[0.35em] uppercase text-rosegold/80 mb-3">
                    {{ strtoupper($invitation->category ?? 'PERNIKAHAN') === 'PERNIKAHAN' ? 'The Intimate Union Of' : ($invitation->title ?? 'The Intimate Union Of') }}
                </p>

                <div class="w-20 h-20 mx-auto my-3 rounded-full border border-rosegold/40 flex items-center justify-center bg-obsidian/60 shadow-[0_0_30px_rgba(229,178,153,0.2)] animate-float">
                    <span class="font-display text-2xl text-foil font-bold tracking-wider">{{ $initials }}</span>
                </div>

                <h2 class="font-display text-3xl sm:text-4xl text-foil font-semibold tracking-wider mt-3">
                    {!! $coupleDisplay !!}
                </h2>
                <p class="font-editorial italic text-lg text-champagne/80 mt-1">
                    {{ $s1DateFormatted }}
                </p>

                <div class="flex items-center justify-center gap-3 my-6">
                    <span class="h-[1px] w-12 bg-gradient-to-r from-transparent to-rosegold/50"></span>
                    <span class="w-1.5 h-1.5 rotate-45 bg-rosegold"></span>
                    <span class="h-[1px] w-12 bg-gradient-to-l from-transparent to-rosegold/50"></span>
                </div>

                <div class="bg-obsidian/60 border border-rosegold/20 rounded-2xl py-4 px-5 mb-7">
                    <p class="text-[11px] uppercase tracking-[0.25em] text-rosegold/70 mb-1">Kepada Yth. Bapak/Ibu/Saudara/i</p>
                    <h3 id="guest-name-display" class="font-editorial text-2xl sm:text-3xl font-semibold text-white tracking-wide break-words">
                        {{ $guestNameVal }}
                    </h3>
                    @if(!empty($guestAddrVal))
                        <p class="text-xs text-rosegold/80 mt-1">di {{ $guestAddrVal }}</p>
                    @endif
                    <p class="text-[11px] text-champagne/60 mt-1">Kehadiran Anda adalah kehormatan bagi malam penuh cinta kami.</p>
                </div>

                <button id="btn-open-invitation" type="button" class="btn-velvet-cta w-full py-4 px-6 rounded-full flex items-center justify-center gap-3 text-champagne font-medium tracking-[0.2em] uppercase text-xs sm:text-sm cursor-pointer group">
                    <svg class="w-4 h-4 text-rosegold group-hover:scale-125 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                    <span>Buka Undangan</span>
                </button>
            </div>
        </div>
    </section>

    <!-- =====================================================================
         STAGE 3: MAIN INVITATION EXPERIENCE
    ====================================================================== -->
    <main id="stage-main-invitation" class="hidden relative min-h-screen velvet-radial pb-28">
        <canvas id="petals-canvas" class="fixed inset-0 pointer-events-none z-20 w-full h-full"></canvas>

        @if($musicMode !== 'none')
        <button id="btn-audio-toggle" type="button" aria-label="Toggle Romantic Music" class="fixed top-5 right-5 z-30 w-11 h-11 rounded-full velvet-card flex items-center justify-center border border-rosegold/40 shadow-lg group cursor-pointer">
            <div id="vinyl-disc" class="w-7 h-7 rounded-full bg-gradient-to-tr from-obsidian via-burgundy to-rosegold/40 border border-rosegold/60 flex items-center justify-center animate-spin-slow">
                <div class="w-2 h-2 rounded-full bg-champagne"></div>
            </div>
            <span id="audio-muted-line" class="hidden absolute w-7 h-[1.5px] bg-ember rotate-45"></span>
        </button>
        @endif

        <div class="relative z-10 max-w-lg mx-auto px-4 sm:px-6 pt-8 space-y-12">

            <!-- 1. HERO SECTION -->
            <header id="sec-hero" class="velvet-card rounded-[2rem] p-7 sm:p-10 text-center relative overflow-hidden reveal-up">
                <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-72 h-72 bg-burgundy/40 rounded-full blur-3xl pointer-events-none"></div>

                <p class="text-[11px] tracking-[0.35em] uppercase text-rosegold/90 mb-4">
                    {{ $isPreview ? 'We Are Tying The Knot' : ($invitation->title ?? 'We Are Tying The Knot') }}
                </p>

                <!-- Cover Photo (jika diunggah) atau Siluet Artistik -->
                <div class="relative w-56 h-72 sm:w-64 sm:h-80 mx-auto my-5 rounded-t-full rounded-b-3xl p-1.5 bg-gradient-to-b from-rosegold/70 via-burgundy/50 to-rosegold/30 shadow-[0_15px_40px_rgba(0,0,0,0.8)]">
                    <div class="w-full h-full rounded-t-full rounded-b-[1.35rem] bg-gradient-to-b from-[#2E0710] via-[#170307] to-[#0B0204] relative overflow-hidden flex items-end justify-center">
                        @if($coverImage)
                            <img src="{{ $coverImage }}" alt="{{ $invitation->title ?? $couplePlain }}" class="w-full h-full object-cover cursor-pointer" onclick="window.openVelvetLightbox && window.openVelvetLightbox('{{ $coverImage }}')">
                        @else
                            <div class="absolute top-10 w-36 h-36 rounded-full bg-gradient-to-tr from-ember/30 via-rosegold/30 to-champagne/40 blur-xl animate-pulse-slow"></div>
                            <div class="absolute top-12 w-28 h-28 rounded-full border border-rosegold/30"></div>

                            <svg viewBox="0 0 200 240" class="w-full h-5/6 relative z-10 drop-shadow-[0_10px_15px_rgba(0,0,0,0.9)]">
                                <defs>
                                    <linearGradient id="silhouetteGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" stop-color="#24050B"/>
                                        <stop offset="100%" stop-color="#090103"/>
                                    </linearGradient>
                                    <linearGradient id="rimLight" x1="0%" y1="0%" x2="100%" y2="0%">
                                        <stop offset="0%" stop-color="#E5B299" stop-opacity="0.7"/>
                                        <stop offset="50%" stop-color="#FF4D6D" stop-opacity="0.3"/>
                                        <stop offset="100%" stop-color="#F5D5C6" stop-opacity="0.8"/>
                                    </linearGradient>
                                </defs>
                                <path d="M45 240 L48 155 C48 132 62 122 76 122 C84 122 92 127 96 135 L102 240 Z" fill="url(#silhouetteGrad)" stroke="url(#rimLight)" stroke-width="0.8"/>
                                <circle cx="75" cy="98" r="18" fill="url(#silhouetteGrad)" stroke="url(#rimLight)" stroke-width="0.8"/>
                                <path d="M95 240 L102 142 C105 128 116 122 127 124 C140 126 152 142 158 240 Z" fill="url(#silhouetteGrad)" stroke="url(#rimLight)" stroke-width="0.8"/>
                                <circle cx="122" cy="103" r="16" fill="url(#silhouetteGrad)" stroke="url(#rimLight)" stroke-width="0.8"/>
                                <circle cx="136" cy="96" r="8" fill="url(#silhouetteGrad)" stroke="url(#rimLight)" stroke-width="0.6"/>
                                <path d="M135 98 C155 120 165 165 168 235" fill="none" stroke="url(#rimLight)" stroke-width="0.8" stroke-dasharray="2 2"/>
                                <circle cx="99" cy="100" r="2" fill="#F5D5C6" class="animate-ping"/>
                            </svg>
                        @endif
                    </div>
                </div>

                <h1 class="font-display text-4xl sm:text-5xl text-foil font-bold tracking-wider mt-4">
                    {!! $coupleDisplay !!}
                </h1>
                <p class="font-editorial italic text-xl text-champagne/90 mt-2">
                    "{{ $greeting }}"
                </p>
                <div class="inline-block mt-4 px-5 py-1.5 rounded-full border border-rosegold/30 bg-obsidian/50 text-xs tracking-[0.25em] uppercase text-rosegold">
                    {{ $s1DateHero }}{{ $s1City ? ' • ' . $s1City : '' }}
                </div>
            </header>

            <!-- 2. INTIMATE QUOTE & LOVE STORY / RUNDOWN TIMELINE -->
            <section id="sec-story" class="velvet-card rounded-3xl p-7 sm:p-8 reveal-up">
                <div class="text-center mb-8">
                    <span class="text-[10px] tracking-[0.3em] uppercase text-rosegold/70">Our Sensual Chapter</span>
                    <h2 class="font-display text-2xl sm:text-3xl text-foil font-semibold mt-1">
                        {{ count($rundownItems) > 0 ? 'Susunan Acara' : 'Kisah Cinta Kami' }}
                    </h2>
                    <p class="font-editorial italic text-lg text-champagne/80 mt-3 leading-relaxed">
                        "{{ $quote }}"
                    </p>
                </div>

                <div class="relative border-l border-rosegold/30 ml-3 pl-6 space-y-7">
                    @if(count($rundownItems) > 0)
                        @foreach($rundownItems as $rIdx => $rItem)
                        <div class="relative">
                            <span class="absolute -left-[31px] top-1 w-3.5 h-3.5 rounded-full {{ $loop->last ? 'bg-ember border-2 border-champagne shadow-[0_0_12px_#FF4D6D]' : 'bg-burgundy border-2 border-rosegold shadow-[0_0_10px_#E5B299]' }}"></span>
                            @if(!empty($rItem['time']))
                                <span class="text-xs tracking-widest uppercase text-rosegold font-semibold">{{ $rItem['time'] }}</span>
                            @endif
                            <h3 class="font-editorial text-xl text-white font-semibold mt-0.5">{{ $rItem['title'] }}</h3>
                            @if(!empty($rItem['description']))
                                <p class="text-xs sm:text-sm text-champagne/75 mt-1 leading-relaxed">{{ $rItem['description'] }}</p>
                            @endif
                        </div>
                        @endforeach
                    @else
                        <div class="relative">
                            <span class="absolute -left-[31px] top-1 w-3.5 h-3.5 rounded-full bg-burgundy border-2 border-rosegold shadow-[0_0_10px_#E5B299]"></span>
                            <span class="text-xs tracking-widest uppercase text-rosegold font-semibold">2023 • Pertemuan Pertama</span>
                            <h3 class="font-editorial text-xl text-white font-semibold mt-0.5">Tatapan di Galeri Malam</h3>
                            <p class="text-xs sm:text-sm text-champagne/75 mt-1 leading-relaxed">
                                Di tengah pameran seni malam di Jakarta, percakapan singkat tentang lukisan klasik berubah menjadi ketertarikan yang tak terelakkan.
                            </p>
                        </div>

                        <div class="relative">
                            <span class="absolute -left-[31px] top-1 w-3.5 h-3.5 rounded-full bg-burgundy border-2 border-rosegold shadow-[0_0_10px_#E5B299]"></span>
                            <span class="text-xs tracking-widest uppercase text-rosegold font-semibold">2025 • Lamaran Intim</span>
                            <h3 class="font-editorial text-xl text-white font-semibold mt-0.5">Janji di Bawah Cahaya Lilin</h3>
                            <p class="text-xs sm:text-sm text-champagne/75 mt-1 leading-relaxed">
                                Makan malam privat dengan iringan jazz lembut menjadi saksi saat cincin rose-gold disematkan sebagai simbol komitmen seumur hidup.
                            </p>
                        </div>

                        <div class="relative">
                            <span class="absolute -left-[31px] top-1 w-3.5 h-3.5 rounded-full bg-ember border-2 border-champagne shadow-[0_0_12px_#FF4D6D]"></span>
                            <span class="text-xs tracking-widest uppercase text-rosegold font-semibold">{{ $s1DateHero }} • Pernikahan</span>
                            <h3 class="font-editorial text-xl text-white font-semibold mt-0.5">The Midnight Velvet Union</h3>
                            <p class="text-xs sm:text-sm text-champagne/75 mt-1 leading-relaxed">
                                Merayakan penyatuan dua jiwa dalam pesta malam yang intim, hangat, dan penuh gairah bersama orang-orang tercinta.
                            </p>
                        </div>
                    @endif
                </div>
            </section>

            <!-- 3. GROOM & BRIDE PROFILE CARDS -->
            <section id="sec-couple" class="space-y-5 reveal-up">
                <div class="text-center">
                    <span class="text-[10px] tracking-[0.3em] uppercase text-rosegold/70">{{ $hasSecondary ? 'The Bride &amp; Groom' : 'Penyelenggara' }}</span>
                    <h2 class="font-display text-2xl sm:text-3xl text-foil font-semibold mt-1">{{ $hasSecondary ? 'Mempelai' : 'Profil Penyelenggara' }}</h2>
                </div>

                <!-- Groom Card -->
                <div class="velvet-card rounded-3xl p-6 flex items-center gap-5">
                    @if($groomPhoto)
                        <div class="w-20 h-20 shrink-0 rounded-2xl overflow-hidden border border-rosegold/50 shadow-inner">
                            <img src="{{ $groomPhoto }}" alt="{{ $groomDisplayName }}" loading="lazy" decoding="async" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="w-20 h-20 shrink-0 rounded-2xl bg-gradient-to-br from-burgundy to-obsidian border border-rosegold/40 flex items-center justify-center shadow-inner">
                            <span class="font-display text-3xl text-foil font-bold">{{ strtoupper(\Illuminate\Support\Str::substr($groomNick, 0, 1)) }}</span>
                        </div>
                    @endif
                    <div class="min-w-0">
                        <span class="text-[10px] uppercase tracking-[0.25em] text-rosegold">{{ $hasSecondary ? 'The Groom' : 'Host' }}</span>
                        <h3 class="font-display text-xl sm:text-2xl text-white font-semibold break-words">{{ $groomDisplayName }}</h3>
                        @if(!empty($primaryParents))
                            <p class="text-xs text-champagne/70 mt-1">{{ $primaryParents }}</p>
                        @endif
                        @if(!empty($primaryIg))
                            <a href="https://instagram.com/{{ $primaryIg }}" target="_blank" rel="noopener noreferrer" class="inline-block mt-2 text-[11px] text-rosegold hover:underline">{{ '@' . $primaryIg }}</a>
                        @endif
                    </div>
                </div>

                <!-- Bride Card -->
                @if($hasSecondary)
                <div class="velvet-card rounded-3xl p-6 flex items-center gap-5">
                    @if($bridePhoto)
                        <div class="w-20 h-20 shrink-0 rounded-2xl overflow-hidden border border-rosegold/50 shadow-inner">
                            <img src="{{ $bridePhoto }}" alt="{{ $brideDisplayName }}" loading="lazy" decoding="async" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="w-20 h-20 shrink-0 rounded-2xl bg-gradient-to-br from-burgundy to-obsidian border border-rosegold/40 flex items-center justify-center shadow-inner">
                            <span class="font-display text-3xl text-foil font-bold">{{ strtoupper(\Illuminate\Support\Str::substr($brideNick, 0, 1)) }}</span>
                        </div>
                    @endif
                    <div class="min-w-0">
                        <span class="text-[10px] uppercase tracking-[0.25em] text-rosegold">The Bride</span>
                        <h3 class="font-display text-xl sm:text-2xl text-white font-semibold break-words">{{ $brideDisplayName }}</h3>
                        @if(!empty($secondaryParents))
                            <p class="text-xs text-champagne/70 mt-1">{{ $secondaryParents }}</p>
                        @endif
                        @if(!empty($secondaryIg))
                            <a href="https://instagram.com/{{ $secondaryIg }}" target="_blank" rel="noopener noreferrer" class="inline-block mt-2 text-[11px] text-rosegold hover:underline">{{ '@' . $secondaryIg }}</a>
                        @endif
                    </div>
                </div>
                @endif
            </section>

            <!-- 4. LIVE COUNTDOWN & EVENT DETAILS -->
            <section id="sec-event" class="velvet-card rounded-3xl p-7 sm:p-8 text-center reveal-up">
                <span class="text-[10px] tracking-[0.3em] uppercase text-rosegold/70">Save The Night</span>
                <h2 class="font-display text-2xl sm:text-3xl text-foil font-semibold mt-1 mb-6">Menghitung Detik Bahagia</h2>

                <div class="grid grid-cols-4 gap-2.5 sm:gap-3 mb-8">
                    <div class="bg-obsidian/70 border border-rosegold/25 rounded-2xl py-3 px-2">
                        <span id="cd-days" class="font-display text-2xl sm:text-3xl font-bold text-foil block">00</span>
                        <span class="text-[10px] uppercase tracking-widest text-rosegold/70">Hari</span>
                    </div>
                    <div class="bg-obsidian/70 border border-rosegold/25 rounded-2xl py-3 px-2">
                        <span id="cd-hours" class="font-display text-2xl sm:text-3xl font-bold text-foil block">00</span>
                        <span class="text-[10px] uppercase tracking-widest text-rosegold/70">Jam</span>
                    </div>
                    <div class="bg-obsidian/70 border border-rosegold/25 rounded-2xl py-3 px-2">
                        <span id="cd-minutes" class="font-display text-2xl sm:text-3xl font-bold text-foil block">00</span>
                        <span class="text-[10px] uppercase tracking-widest text-rosegold/70">Menit</span>
                    </div>
                    <div class="bg-obsidian/70 border border-rosegold/25 rounded-2xl py-3 px-2">
                        <span id="cd-seconds" class="font-display text-2xl sm:text-3xl font-bold text-ember block">00</span>
                        <span class="text-[10px] uppercase tracking-widest text-rosegold/70">Detik</span>
                    </div>
                </div>

                <div class="space-y-4 text-left">
                    <!-- Sesi 1 -->
                    <div class="bg-obsidian/60 border border-rosegold/20 rounded-2xl p-5">
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <h3 class="font-display text-lg text-rosegold font-semibold">{{ $s1Title }}</h3>
                            <span class="text-[10px] px-2.5 py-0.5 rounded-full bg-burgundy/60 text-champagne border border-rosegold/30 shrink-0">{{ $s1Time }}</span>
                        </div>
                        <p class="text-[11px] text-rosegold/80 mb-1">{{ $s1DateFormatted }}</p>
                        <p class="text-xs text-champagne/80 leading-relaxed">
                            <strong class="text-white">{{ $s1Venue }}</strong><br>
                            {{ $s1Address }}
                        </p>
                        @if(!empty($s1Maps))
                        <a href="{{ $s1Maps }}" target="_blank" rel="noopener noreferrer" class="mt-3 inline-flex items-center gap-1.5 text-[11px] uppercase tracking-wider text-rosegold hover:text-white transition-colors">
                            <span>Buka Google Maps &rarr;</span>
                        </a>
                        @endif
                    </div>

                    <!-- Sesi 2 -->
                    @if($s2Enabled)
                    <div class="bg-obsidian/60 border border-rosegold/20 rounded-2xl p-5">
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <h3 class="font-display text-lg text-rosegold font-semibold">{{ $s2Title }}</h3>
                            <span class="text-[10px] px-2.5 py-0.5 rounded-full bg-burgundy/60 text-champagne border border-rosegold/30 shrink-0">{{ $s2Time }}</span>
                        </div>
                        <p class="text-[11px] text-rosegold/80 mb-1">{{ $s2DateFormatted }}</p>
                        <p class="text-xs text-champagne/80 leading-relaxed">
                            <strong class="text-white">{{ $s2Venue }}</strong><br>
                            {{ $s2Address }}
                        </p>
                        <div class="mt-3 pt-3 border-t border-rosegold/15 flex items-center justify-between text-[11px]">
                            <span class="text-rosegold/80">Dress Code:</span>
                            <span class="font-medium text-champagne">Midnight Black, Crimson &amp; Gold</span>
                        </div>
                        @if(!empty($s2Maps))
                        <a href="{{ $s2Maps }}" target="_blank" rel="noopener noreferrer" class="mt-2 inline-flex items-center gap-1.5 text-[11px] uppercase tracking-wider text-rosegold hover:text-white transition-colors">
                            <span>Buka Google Maps &rarr;</span>
                        </a>
                        @endif
                    </div>
                    @endif
                </div>

                <div class="grid {{ !empty($s1Maps) ? 'grid-cols-2' : 'grid-cols-1' }} gap-3 mt-6">
                    @if(!empty($s1Maps))
                    <a href="{{ $s1Maps }}" target="_blank" rel="noopener noreferrer" class="py-3 px-4 rounded-xl border border-rosegold/40 bg-obsidian/70 hover:bg-burgundy/50 text-xs uppercase tracking-wider text-champagne font-medium transition-all flex items-center justify-center gap-2">
                        <span>Google Maps</span>
                    </a>
                    @endif
                    <button id="btn-save-calendar" type="button" class="py-3 px-4 rounded-xl btn-velvet-cta text-xs uppercase tracking-wider text-champagne font-medium flex items-center justify-center gap-2 cursor-pointer">
                        <span>Simpan Tanggal</span>
                    </button>
                </div>
            </section>

            <!-- 4B. GALERI FOTO (JIKA DIUNGGAH) -->
            @if(count($galleryImages) > 0)
            <section id="sec-gallery" class="velvet-card rounded-3xl p-7 sm:p-8 text-center reveal-up">
                <span class="text-[10px] tracking-[0.3em] uppercase text-rosegold/70">Captured Moments</span>
                <h2 class="font-display text-2xl sm:text-3xl text-foil font-semibold mt-1 mb-6">Galeri Foto</h2>

                <div class="grid grid-cols-2 gap-3">
                    @foreach($galleryImages as $photo)
                    <div class="aspect-[4/5] rounded-2xl overflow-hidden border border-rosegold/30 bg-obsidian/60 cursor-pointer group"
                         onclick="window.openVelvetLightbox && window.openVelvetLightbox('{{ asset('storage/' . $photo) }}')">
                        <img src="{{ asset('storage/' . $photo) }}" alt="Foto Galeri" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    </div>
                    @endforeach
                </div>
            </section>
            @endif

            <!-- 5. DIGITAL GIFT / AMPLOP DIGITAL -->
            @if(count($giftAccounts) > 0)
            <section id="sec-gift" class="velvet-card rounded-3xl p-7 sm:p-8 text-center reveal-up">
                <span class="text-[10px] tracking-[0.3em] uppercase text-rosegold/70">Wedding Gift</span>
                <h2 class="font-display text-2xl sm:text-3xl text-foil font-semibold mt-1">Tanda Kasih</h2>
                <p class="text-xs text-champagne/75 mt-2 mb-6 leading-relaxed">
                    Doa restu Anda merupakan karunia terindah. Namun jika memberi adalah ungkapan tanda kasih, Anda dapat mengirimkannya melalui:
                </p>

                <div class="space-y-3">
                    @foreach($giftAccounts as $acc)
                    <div class="bg-gradient-to-r from-[#2B0810] to-[#120306] border border-rosegold/30 rounded-2xl p-4 flex items-center justify-between gap-3 text-left">
                        <div class="min-w-0">
                            <span class="text-[10px] uppercase tracking-widest text-rosegold font-bold">{{ $acc['bank'] }}</span>
                            <p class="font-display text-lg text-white tracking-wider mt-0.5 break-all">{{ $acc['account_number'] }}</p>
                            @if(!empty($acc['account_name']))
                                <p class="text-[11px] text-champagne/70">a.n. {{ $acc['account_name'] }}</p>
                            @endif
                        </div>
                        <button type="button" onclick="copyAccountNumber('{{ $acc['account_number'] }}', '{{ addslashes($acc['bank']) }}')" class="px-3.5 py-2 rounded-xl bg-burgundy/70 hover:bg-burgundy border border-rosegold/40 text-[11px] uppercase tracking-wider text-champagne transition-all cursor-pointer shrink-0">
                            Salin
                        </button>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif

            <!-- 6. INTERACTIVE RSVP & ROMANTIC WISHES WALL -->
            <section id="sec-rsvp" class="velvet-card rounded-3xl p-7 sm:p-8 reveal-up">
                <div class="text-center mb-6">
                    <span class="text-[10px] tracking-[0.3em] uppercase text-rosegold/70">RSVP &amp; Wishes</span>
                    <h2 class="font-display text-2xl sm:text-3xl text-foil font-semibold mt-1">Konfirmasi &amp; Doa Restu</h2>
                </div>

                <form id="rsvp-form" class="space-y-4">
                    <div>
                        <label for="input-rsvp-name" class="block text-[11px] uppercase tracking-wider text-rosegold/80 mb-1.5">Nama Lengkap</label>
                        <input type="text" id="input-rsvp-name" required value="{{ $guestNameVal !== 'Tamu Spesial' ? $guestNameVal : '' }}" placeholder="Masukkan nama Anda" class="w-full px-4 py-3 rounded-xl bg-obsidian/80 border border-rosegold/25 text-sm text-white placeholder-champagne/30 focus:outline-none focus:border-rosegold transition-colors">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="input-rsvp-status" class="block text-[11px] uppercase tracking-wider text-rosegold/80 mb-1.5">Kehadiran</label>
                            <select id="input-rsvp-status" class="w-full px-3.5 py-3 rounded-xl bg-obsidian/80 border border-rosegold/25 text-sm text-white focus:outline-none focus:border-rosegold">
                                <option value="hadir">Hadir Malam Ini</option>
                                <option value="tidak_hadir">Maaf Berhalangan</option>
                            </select>
                        </div>
                        <div>
                            <label for="input-rsvp-guests" class="block text-[11px] uppercase tracking-wider text-rosegold/80 mb-1.5">Jumlah Tamu</label>
                            <select id="input-rsvp-guests" class="w-full px-3.5 py-3 rounded-xl bg-obsidian/80 border border-rosegold/25 text-sm text-white focus:outline-none focus:border-rosegold">
                                <option value="1 Orang">1 Orang</option>
                                <option value="2 Orang (Pasangan)">2 Orang (Pasangan)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="input-rsvp-wish" class="block text-[11px] uppercase tracking-wider text-rosegold/80 mb-1.5">Ucapan Romantis</label>
                        <textarea id="input-rsvp-wish" rows="3" required placeholder="Tuliskan doa dan harapan hangat..." class="w-full px-4 py-3 rounded-xl bg-obsidian/80 border border-rosegold/25 text-sm text-white placeholder-champagne/30 focus:outline-none focus:border-rosegold transition-colors"></textarea>
                    </div>

                    <button type="submit" id="btn-submit-rsvp" class="btn-velvet-cta w-full py-3.5 rounded-xl text-xs uppercase tracking-[0.2em] font-semibold text-champagne cursor-pointer disabled:opacity-50">
                        Kirim Konfirmasi &amp; Ucapan
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-rosegold/20">
                    <h3 class="text-xs uppercase tracking-widest text-rosegold mb-4 flex items-center justify-between">
                        <span>Ucapan Tamu Undangan</span>
                        <span id="wish-count" class="px-2 py-0.5 rounded-full bg-burgundy text-champagne text-[10px]">0</span>
                    </h3>

                    <div id="wishes-container" class="space-y-3 max-h-72 overflow-y-auto pr-1"></div>
                </div>
            </section>

            <footer class="text-center pt-4 pb-6">
                <p class="font-display text-lg text-foil tracking-widest">{{ $initials }}</p>
                <p class="text-[11px] text-champagne/50 mt-1">Crafted with Passion &bull; The Midnight Velvet Edition</p>
            </footer>
        </div>

        <!-- Floating Bottom Glass Navigation Bar -->
        <nav class="fixed bottom-4 left-1/2 -translate-x-1/2 z-30 w-[92%] max-w-md velvet-card rounded-full px-4 py-2.5 flex items-center justify-around border border-rosegold/30 shadow-[0_10px_30px_rgba(0,0,0,0.9)]">
            <a href="#sec-hero" class="flex flex-col items-center text-champagne/75 hover:text-rosegold transition-colors p-1">
                <span class="text-[10px] uppercase tracking-wider font-medium">Home</span>
            </a>
            <a href="#sec-story" class="flex flex-col items-center text-champagne/75 hover:text-rosegold transition-colors p-1">
                <span class="text-[10px] uppercase tracking-wider font-medium">Story</span>
            </a>
            <a href="#sec-event" class="flex flex-col items-center text-champagne/75 hover:text-rosegold transition-colors p-1">
                <span class="text-[10px] uppercase tracking-wider font-medium">Acara</span>
            </a>
            @if(count($giftAccounts) > 0)
            <a href="#sec-gift" class="flex flex-col items-center text-champagne/75 hover:text-rosegold transition-colors p-1">
                <span class="text-[10px] uppercase tracking-wider font-medium">Hadiah</span>
            </a>
            @endif
            <a href="#sec-rsvp" class="flex flex-col items-center text-champagne/75 hover:text-rosegold transition-colors p-1">
                <span class="text-[10px] uppercase tracking-wider font-medium">RSVP</span>
            </a>
        </nav>
    </main>

    <!-- Lightbox Modal -->
    <div id="velvet-lightbox" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/90 backdrop-blur-sm p-4" role="dialog" aria-modal="true" onclick="window.closeVelvetLightbox && window.closeVelvetLightbox()">
        <div class="relative max-w-2xl w-full" onclick="event.stopPropagation()">
            <button type="button" onclick="window.closeVelvetLightbox && window.closeVelvetLightbox()" class="absolute -top-3 -right-3 w-9 h-9 rounded-full velvet-card border border-rosegold/50 text-champagne flex items-center justify-center cursor-pointer">&times;</button>
            <img id="velvet-lightbox-img" src="" alt="Preview" class="w-full max-h-[82vh] object-contain rounded-2xl border border-rosegold/30">
        </div>
    </div>

    <!-- Custom Non-Blocking Toast Notification -->
    <div id="toast-notification" class="fixed top-6 left-1/2 -translate-x-1/2 z-50 pointer-events-none opacity-0 -translate-y-4 transition-all duration-300">
        <div class="velvet-card px-5 py-3 rounded-full border border-rosegold/50 shadow-2xl flex items-center gap-2.5">
            <span class="w-2 h-2 rounded-full bg-ember"></span>
            <span id="toast-message" class="text-xs font-medium text-champagne tracking-wide">Notifikasi</span>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const CONFIG = {
                isPreview: @json($isPreview),
                wishUrl: @json(!$isPreview && !empty($invitation->slug) ? route('invitation.wish', $invitation->slug) : ''),
                csrfToken: @json(csrf_token()),
                targetIso: @json($targetIso),
                musicMode: @json($musicMode),
                musicUrl: @json($musicUrl),
                eventTitle: @json($invitation->title ?? ($couplePlain . ' - The Midnight Velvet Union')),
                s1Date: @json(str_replace('-', '', $s1Date)),
                s1Venue: @json($s1Venue),
                s1Address: @json($s1Address),
                initialWishes: @json($wishesData ?? []),
                guestNameInitial: @json($guestName ?? '')
            };

            /* -----------------------------------------------------------------
               1. DYNAMIC GUEST NAME PARSER (?to=Nama+Tamu)
            ------------------------------------------------------------------ */
            const guestDisplayEl = document.getElementById('guest-name-display');
            const rsvpNameInput = document.getElementById('input-rsvp-name');
            const urlParams = new URLSearchParams(window.location.search);
            const guestParam = urlParams.get('to');
            const initialGuest = CONFIG.guestNameInitial || (guestParam && guestParam.trim() !== '' ? decodeURIComponent(guestParam.replace(/\+/g, ' ')) : '');
            if (initialGuest) {
                if (guestDisplayEl) guestDisplayEl.textContent = initialGuest;
                if (rsvpNameInput) rsvpNameInput.value = initialGuest;
            }

            /* -----------------------------------------------------------------
               LIGHTBOX & TOAST HELPER
            ------------------------------------------------------------------ */
            const lightboxEl = document.getElementById('velvet-lightbox');
            const lightboxImg = document.getElementById('velvet-lightbox-img');
            window.openVelvetLightbox = function(src) {
                if (!lightboxEl || !lightboxImg || !src) return;
                lightboxImg.src = src;
                lightboxEl.classList.remove('hidden');
                lightboxEl.classList.add('flex');
            };
            window.closeVelvetLightbox = function() {
                if (!lightboxEl) return;
                lightboxEl.classList.add('hidden');
                lightboxEl.classList.remove('flex');
            };

            let toastTimeout;
            function showToast(message) {
                const toast = document.getElementById('toast-notification');
                const toastMsg = document.getElementById('toast-message');
                if (!toast || !toastMsg) return;
                toastMsg.textContent = message;
                toast.classList.remove('opacity-0', '-translate-y-4');
                toast.classList.add('opacity-100', 'translate-y-0');

                clearTimeout(toastTimeout);
                toastTimeout = setTimeout(() => {
                    toast.classList.remove('opacity-100', 'translate-y-0');
                    toast.classList.add('opacity-0', '-translate-y-4');
                }, 3000);
            }

            /* -----------------------------------------------------------------
               2. STAGE 1: PURE-CODE CINEMATIC OPENING VIDEO INTRO
            ------------------------------------------------------------------ */
            const introSection = document.getElementById('stage-intro-video');
            const coverSection = document.getElementById('stage-guest-cover');
            const mainSection = document.getElementById('stage-main-invitation');
            const introCanvas = document.getElementById('intro-video-canvas');
            const introCtx = introCanvas.getContext('2d');

            let introAnimationId;
            let introCompleted = false;
            const INTRO_DURATION = 6800;
            const introStartTime = performance.now();

            function resizeIntroCanvas() {
                introCanvas.width = window.innerWidth;
                introCanvas.height = window.innerHeight;
            }
            window.addEventListener('resize', resizeIntroCanvas);
            resizeIntroCanvas();

            const introParticles = Array.from({ length: 55 }, () => ({
                x: Math.random() * introCanvas.width,
                y: Math.random() * introCanvas.height,
                radius: Math.random() * 3.5 + 1,
                speedY: -(Math.random() * 0.9 + 0.3),
                speedX: (Math.random() - 0.5) * 0.4,
                alpha: Math.random() * 0.7 + 0.2,
                isBokeh: Math.random() > 0.75
            }));

            function renderIntroVideo(now) {
                if (introCompleted) return;
                const elapsed = now - introStartTime;
                const progress = Math.min(elapsed / INTRO_DURATION, 1);

                document.getElementById('intro-progress-bar').style.width = `${progress * 100}%`;
                const sec = Math.floor(elapsed / 1000);
                document.getElementById('intro-timer').textContent = `00:0${Math.min(sec, 7)} / 00:07`;

                if (elapsed > 500) {
                    const emb = document.getElementById('intro-emblem');
                    emb.classList.remove('opacity-0', 'scale-75');
                    emb.classList.add('opacity-100', 'scale-100');
                }
                if (elapsed > 1400) {
                    document.getElementById('intro-line-1').classList.remove('opacity-0', 'translate-y-4');
                }
                if (elapsed > 2600) {
                    document.getElementById('intro-line-2').classList.remove('opacity-0', 'translate-y-4');
                }
                if (elapsed > 3800) {
                    document.getElementById('intro-line-3').classList.remove('opacity-0');
                }

                const w = introCanvas.width;
                const h = introCanvas.height;
                const bgGrad = introCtx.createRadialGradient(w / 2, h / 2, 20, w / 2, h / 2, Math.max(w, h) * 0.75);
                bgGrad.addColorStop(0, '#3D0A14');
                bgGrad.addColorStop(0.55, '#1A0509');
                bgGrad.addColorStop(1, '#080103');
                introCtx.fillStyle = bgGrad;
                introCtx.fillRect(0, 0, w, h);

                const t = elapsed * 0.0012;
                for (let layer = 0; layer < 4; layer++) {
                    introCtx.beginPath();
                    introCtx.moveTo(0, h);
                    for (let x = 0; x <= w; x += 20) {
                        const wave1 = Math.sin(x * 0.003 + t + layer * 1.1) * 55;
                        const wave2 = Math.cos(x * 0.006 - t * 1.3 + layer) * 30;
                        const y = h * (0.45 + layer * 0.12) + wave1 + wave2;
                        introCtx.lineTo(x, y);
                    }
                    introCtx.lineTo(w, h);
                    introCtx.closePath();

                    const silkGrad = introCtx.createLinearGradient(0, h * 0.3, w, h);
                    silkGrad.addColorStop(0, `rgba(110, 17, 36, ${0.14 - layer * 0.02})`);
                    silkGrad.addColorStop(0.5, `rgba(229, 178, 153, ${0.08 - layer * 0.015})`);
                    silkGrad.addColorStop(1, 'rgba(11, 2, 4, 0.2)');
                    introCtx.fillStyle = silkGrad;
                    introCtx.fill();
                }

                introParticles.forEach(p => {
                    p.y += p.speedY;
                    p.x += p.speedX + Math.sin(t + p.y * 0.01) * 0.25;
                    if (p.y < -20) {
                        p.y = h + 20;
                        p.x = Math.random() * w;
                    }

                    introCtx.beginPath();
                    if (p.isBokeh) {
                        const r = p.radius * 5;
                        const bokehGrad = introCtx.createRadialGradient(p.x, p.y, 0, p.x, p.y, r);
                        bokehGrad.addColorStop(0, `rgba(245, 213, 198, ${p.alpha * 0.35})`);
                        bokehGrad.addColorStop(1, 'rgba(229, 178, 153, 0)');
                        introCtx.fillStyle = bokehGrad;
                        introCtx.arc(p.x, p.y, r, 0, Math.PI * 2);
                    } else {
                        introCtx.fillStyle = `rgba(245, 213, 198, ${p.alpha})`;
                        introCtx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                    }
                    introCtx.fill();
                });

                if (progress < 1) {
                    introAnimationId = requestAnimationFrame(renderIntroVideo);
                } else {
                    transitionToGuestCover();
                }
            }

            introAnimationId = requestAnimationFrame(renderIntroVideo);

            function transitionToGuestCover() {
                if (introCompleted) return;
                introCompleted = true;
                cancelAnimationFrame(introAnimationId);

                introSection.classList.add('opacity-0', 'pointer-events-none');
                coverSection.classList.remove('hidden');
                coverSection.classList.add('flex');

                setTimeout(() => {
                    introSection.classList.add('hidden');
                }, 1000);
            }

            document.getElementById('btn-skip-intro').addEventListener('click', transitionToGuestCover);

            /* -----------------------------------------------------------------
               3. AUDIO ENGINE (CUSTOM MP3 OR SYNTHESIZED WEB AUDIO PAD)
            ------------------------------------------------------------------ */
            let audioCtx = null;
            let customAudio = (CONFIG.musicMode === 'custom' && CONFIG.musicUrl) ? new Audio(CONFIG.musicUrl) : null;
            if (customAudio) {
                customAudio.loop = true;
                customAudio.preload = 'none';
            }
            let isPlayingAudio = false;
            let chordInterval = null;

            function startRomanticMusic() {
                if (CONFIG.musicMode === 'none') return;
                if (CONFIG.musicMode === 'custom' && customAudio) {
                    customAudio.volume = 0.5;
                    customAudio.play().then(() => {
                        isPlayingAudio = true;
                        updateAudioUI();
                    }).catch(() => {});
                    return;
                }
                try {
                    if (!audioCtx) {
                        const AudioContext = window.AudioContext || window.webkitAudioContext;
                        audioCtx = new AudioContext();
                    }
                    if (audioCtx.state === 'suspended') {
                        audioCtx.resume();
                    }
                    if (isPlayingAudio) return;
                    isPlayingAudio = true;
                    updateAudioUI();

                    const chords = [
                        [146.83, 174.61, 220.00, 261.63, 329.63],
                        [116.54, 174.61, 220.00, 261.63, 349.23],
                        [98.00,  146.83, 174.61, 220.00, 293.66],
                        [110.00, 164.81, 220.00, 277.18, 329.63]
                    ];
                    let step = 0;

                    function playWarmPadChord(freqs) {
                        if (!isPlayingAudio || !audioCtx) return;
                        const now = audioCtx.currentTime;
                        freqs.forEach((freq, idx) => {
                            const osc = audioCtx.createOscillator();
                            const gain = audioCtx.createGain();
                            const filter = audioCtx.createBiquadFilter();

                            osc.type = idx % 2 === 0 ? 'sine' : 'triangle';
                            osc.frequency.setValueAtTime(freq, now);

                            filter.type = 'lowpass';
                            filter.frequency.setValueAtTime(650, now);

                            gain.gain.setValueAtTime(0.0001, now);
                            gain.gain.exponentialRampToValueAtTime(0.025, now + 1.5);
                            gain.gain.exponentialRampToValueAtTime(0.0001, now + 4.8);

                            osc.connect(filter);
                            filter.connect(gain);
                            gain.connect(audioCtx.destination);

                            osc.start(now);
                            osc.stop(now + 5.0);
                        });
                    }

                    playWarmPadChord(chords[0]);
                    chordInterval = setInterval(() => {
                        step = (step + 1) % chords.length;
                        playWarmPadChord(chords[step]);
                    }, 4600);
                } catch (e) {
                    console.warn('Web Audio API fallback handled gracefully:', e);
                }
            }

            function toggleAudio() {
                if (CONFIG.musicMode === 'none') return;
                if (isPlayingAudio) {
                    isPlayingAudio = false;
                    if (customAudio) customAudio.pause();
                    clearInterval(chordInterval);
                    updateAudioUI();
                } else {
                    startRomanticMusic();
                }
            }

            function updateAudioUI() {
                const disc = document.getElementById('vinyl-disc');
                const slash = document.getElementById('audio-muted-line');
                if (!disc || !slash) return;
                if (isPlayingAudio) {
                    disc.classList.add('animate-spin-slow');
                    slash.classList.add('hidden');
                } else {
                    disc.classList.remove('animate-spin-slow');
                    slash.classList.remove('hidden');
                }
            }

            const btnAudioToggle = document.getElementById('btn-audio-toggle');
            if (btnAudioToggle) btnAudioToggle.addEventListener('click', toggleAudio);

            /* -----------------------------------------------------------------
               4. STAGE 2 -> STAGE 3: DRAMATIC CURTAIN SPLIT & PETAL BURST
            ------------------------------------------------------------------ */
            document.getElementById('btn-open-invitation').addEventListener('click', () => {
                startRomanticMusic();
                mainSection.classList.remove('hidden');

                document.getElementById('cover-content').classList.add('opacity-0', 'scale-95');
                document.getElementById('curtain-left').classList.add('curtain-open-left');
                document.getElementById('curtain-right').classList.add('curtain-open-right');

                initPetalEngine(true);
                initScrollReveal();

                setTimeout(() => {
                    coverSection.classList.add('hidden');
                }, 1650);
            });

            /* -----------------------------------------------------------------
               5. FLOATING ROSE PETALS & GOLDEN SPARKS ENGINE
            ------------------------------------------------------------------ */
            const petalsCanvas = document.getElementById('petals-canvas');
            const pCtx = petalsCanvas.getContext('2d');
            let petals = [];

            function resizePetalsCanvas() {
                petalsCanvas.width = window.innerWidth;
                petalsCanvas.height = window.innerHeight;
            }
            window.addEventListener('resize', resizePetalsCanvas);
            resizePetalsCanvas();

            function createPetal(isBurst = false) {
                const w = petalsCanvas.width;
                const h = petalsCanvas.height;
                return {
                    x: isBurst ? w / 2 + (Math.random() - 0.5) * 140 : Math.random() * w,
                    y: isBurst ? h / 2 + (Math.random() - 0.5) * 140 : -20,
                    size: Math.random() * 8 + 5,
                    speedX: isBurst ? (Math.random() - 0.5) * 9 : (Math.random() - 0.5) * 1.2,
                    speedY: isBurst ? (Math.random() - 0.6) * 9 : Math.random() * 1.4 + 0.6,
                    rotation: Math.random() * Math.PI * 2,
                    rotSpeed: (Math.random() - 0.5) * 0.05,
                    isGoldSpark: Math.random() > 0.65,
                    alpha: Math.random() * 0.7 + 0.3
                };
            }

            function initPetalEngine(withBurst = false) {
                petals = [];
                for (let i = 0; i < 28; i++) {
                    const p = createPetal(false);
                    p.y = Math.random() * petalsCanvas.height;
                    petals.push(p);
                }
                if (withBurst) {
                    for (let i = 0; i < 45; i++) {
                        petals.push(createPetal(true));
                    }
                }
                animatePetals();
            }

            function animatePetals() {
                const w = petalsCanvas.width;
                const h = petalsCanvas.height;
                pCtx.clearRect(0, 0, w, h);

                petals.forEach((p, idx) => {
                    p.speedX *= 0.985;
                    if (p.speedY < 0.7) p.speedY += 0.04;
                    if (p.speedY > 2.2) p.speedY *= 0.96;

                    p.x += p.speedX + Math.sin(p.rotation) * 0.5;
                    p.y += p.speedY;
                    p.rotation += p.rotSpeed;

                    if (p.y > h + 25) {
                        if (petals.length > 30) {
                            petals.splice(idx, 1);
                            return;
                        } else {
                            p.y = -15;
                            p.x = Math.random() * w;
                        }
                    }

                    pCtx.save();
                    pCtx.translate(p.x, p.y);
                    pCtx.rotate(p.rotation);

                    if (p.isGoldSpark) {
                        pCtx.fillStyle = `rgba(245, 213, 198, ${p.alpha})`;
                        pCtx.beginPath();
                        pCtx.arc(0, 0, p.size * 0.32, 0, Math.PI * 2);
                        pCtx.fill();
                    } else {
                        pCtx.fillStyle = `rgba(138, 17, 42, ${p.alpha})`;
                        pCtx.beginPath();
                        pCtx.moveTo(0, -p.size);
                        pCtx.bezierCurveTo(p.size, -p.size, p.size, p.size, 0, p.size);
                        pCtx.bezierCurveTo(-p.size, p.size, -p.size, -p.size, 0, -p.size);
                        pCtx.fill();
                    }
                    pCtx.restore();
                });

                requestAnimationFrame(animatePetals);
            }

            /* -----------------------------------------------------------------
               6. SCROLL-TRIGGERED REVEAL ANIMATIONS
            ------------------------------------------------------------------ */
            function initScrollReveal() {
                const elements = document.querySelectorAll('.reveal-up');
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                        }
                    });
                }, { threshold: 0.12 });

                elements.forEach(el => observer.observe(el));
            }

            /* -----------------------------------------------------------------
               7. LIVE COUNTDOWN TIMER
            ------------------------------------------------------------------ */
            const targetTime = new Date(CONFIG.targetIso).getTime();

            function updateCountdown() {
                const diff = Math.max(0, targetTime - Date.now());
                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
                const minutes = Math.floor((diff / (1000 * 60)) % 60);
                const seconds = Math.floor((diff / 1000) % 60);

                document.getElementById('cd-days').textContent = String(days).padStart(2, '0');
                document.getElementById('cd-hours').textContent = String(hours).padStart(2, '0');
                document.getElementById('cd-minutes').textContent = String(minutes).padStart(2, '0');
                document.getElementById('cd-seconds').textContent = String(seconds).padStart(2, '0');
            }
            setInterval(updateCountdown, 1000);
            updateCountdown();

            /* -----------------------------------------------------------------
               8. INTERACTIVE RSVP & WISHES WALL (AJAX TERINTEGRASI)
            ------------------------------------------------------------------ */
            const rsvpForm = document.getElementById('rsvp-form');
            const wishesContainer = document.getElementById('wishes-container');
            const wishCountEl = document.getElementById('wish-count');
            const rsvpSubmitBtn = document.getElementById('btn-submit-rsvp');
            let wishCounter = 0;

            const seedWishes = [
                {
                    guest_name: 'Clarissa & Julian',
                    attendance: 'hadir',
                    message: 'Selamat menempuh hidup baru! Semoga api cinta kalian selalu menyala hangat dan penuh gairah selamanya.'
                },
                {
                    guest_name: 'Dr. Reza Gunawan',
                    attendance: 'hadir',
                    message: 'Tema yang sangat elegan dan berkelas. Sampai bertemu di malam gala nanti!'
                }
            ];

            function escapeHTML(str) {
                return String(str || '').replace(/[&<>"']/g, m => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
                }[m]));
            }

            function renderWishCard(item, prepend = false) {
                if (!wishesContainer) return;
                wishCounter++;
                if (wishCountEl) wishCountEl.textContent = wishCounter;

                const name = item.guest_name || item.name || 'Tamu';
                const isHadir = item.attendance === 'hadir' || item.attendance === 'Hadir';
                const statusLabel = isHadir ? 'Hadir' : 'Berhalangan';
                const guestNote = item.guests ? ` <span class="text-[10px] text-rosegold/70">(${escapeHTML(item.guests)})</span>` : '';

                const wishCard = document.createElement('div');
                wishCard.className = 'bg-obsidian/80 border border-rosegold/25 rounded-2xl p-4 transition-all duration-500';
                wishCard.innerHTML = `
                    <div class="flex items-center justify-between mb-1">
                        <span class="font-semibold text-sm text-champagne">${escapeHTML(name)}${guestNote}</span>
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-burgundy text-champagne">${statusLabel}</span>
                    </div>
                    <p class="text-xs text-champagne/85 leading-relaxed">"${escapeHTML(item.message)}"</p>
                `;

                if (prepend) {
                    wishCard.classList.add('opacity-0', '-translate-y-2');
                    wishesContainer.prepend(wishCard);
                    requestAnimationFrame(() => {
                        wishCard.classList.remove('opacity-0', '-translate-y-2');
                    });
                } else {
                    wishesContainer.appendChild(wishCard);
                }
            }

            const wishesToRender = (CONFIG.initialWishes && CONFIG.initialWishes.length > 0)
                ? CONFIG.initialWishes
                : (CONFIG.isPreview ? seedWishes : []);
            wishesToRender.forEach(w => renderWishCard(w, false));

            if (rsvpForm) {
                rsvpForm.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const name = document.getElementById('input-rsvp-name').value.trim();
                    const status = document.getElementById('input-rsvp-status').value;
                    const guests = document.getElementById('input-rsvp-guests').value;
                    const wishInput = document.getElementById('input-rsvp-wish');
                    const wish = wishInput.value.trim();
                    if (!name || !wish) return;

                    if (CONFIG.wishUrl) {
                        if (rsvpSubmitBtn) rsvpSubmitBtn.disabled = true;
                        try {
                            const res = await fetch(CONFIG.wishUrl, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': CONFIG.csrfToken
                                },
                                body: JSON.stringify({
                                    guest_name: name,
                                    attendance: status,
                                    message: wish
                                })
                            });
                            const data = await res.json();
                            if (!res.ok || !data.success) throw new Error();
                            renderWishCard(data.wish, true);
                            wishInput.value = '';
                            showToast(data.message || 'Terima kasih! Konfirmasi & ucapan Anda telah terkirim.');
                        } catch (_) {
                            showToast('Gagal mengirim ucapan. Periksa koneksi Anda.');
                        } finally {
                            if (rsvpSubmitBtn) rsvpSubmitBtn.disabled = false;
                        }
                    } else {
                        renderWishCard({ guest_name: name, attendance: status, guests, message: wish }, true);
                        wishInput.value = '';
                        showToast('Terima kasih! Konfirmasi & ucapan Anda telah terkirim.');
                    }
                });
            }

            /* -----------------------------------------------------------------
               9. SAVE TO CALENDAR (.ICS DOWNLOAD) & CLIPBOARD COPY
            ------------------------------------------------------------------ */
            document.getElementById('btn-save-calendar').addEventListener('click', () => {
                const icsContent = [
                    'BEGIN:VCALENDAR',
                    'VERSION:2.0',
                    'BEGIN:VEVENT',
                    `DTSTART:${CONFIG.s1Date}T090000Z`,
                    `DTEND:${CONFIG.s1Date}T160000Z`,
                    `SUMMARY:${CONFIG.eventTitle}`,
                    `DESCRIPTION:${CONFIG.eventTitle}`,
                    `LOCATION:${CONFIG.s1Venue}, ${CONFIG.s1Address}`,
                    'END:VEVENT',
                    'END:VCALENDAR'
                ].join('\r\n');

                const blob = new Blob([icsContent], { type: 'text/calendar;charset=utf-8' });
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = 'Midnight-Velvet-Wedding.ics';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                showToast('Jadwal acara berhasil disimpan ke kalender!');
            });

            window.copyAccountNumber = function(number, label) {
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(number).then(() => {
                        showToast(`Nomor ${label} berhasil disalin!`);
                    }).catch(() => fallbackCopy(number, label));
                } else {
                    fallbackCopy(number, label);
                }
            };

            function fallbackCopy(number, label) {
                const tempInput = document.createElement('input');
                tempInput.value = number;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);
                showToast(`Nomor ${label} berhasil disalin!`);
            }
        });
    </script>
</body>
</html>
