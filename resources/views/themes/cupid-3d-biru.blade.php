@php
    $evData = $invitation->event_data ?? [];
    $isPreview = \Illuminate\Support\Str::startsWith($invitation->slug ?? '', 'demo-') || empty($evData);

    $primaryName = !empty($evData['primary_name']) ? $evData['primary_name'] : 'Naufal Aditya Pratama, S.Kom.';
    $secondaryName = !empty($evData['secondary_name']) ? $evData['secondary_name'] : 'Aulia Zahra Maharani, S.Ds.';
    $primaryNick = \Illuminate\Support\Str::before(trim($primaryName), ' ');
    $secondaryNick = \Illuminate\Support\Str::before(trim($secondaryName), ' ');
    $hasSecondary = !empty($evData['secondary_name']) || $isPreview;

    // Display order in hero/cover keeps Bride & Groom harmony ("Aulia & Naufal" in preview, or secondary & primary)
    $brideDisplayName = $hasSecondary ? $secondaryName : $primaryName;
    $brideNick = $hasSecondary ? $secondaryNick : $primaryNick;
    $groomDisplayName = $primaryName;
    $groomNick = $primaryNick;

    $coupleDisplay = $hasSecondary
        ? ($isPreview ? 'Aulia &amp; Naufal' : e($primaryNick) . ' &amp; ' . e($secondaryNick))
        : e($primaryName);

    $initials = $hasSecondary
        ? strtoupper(\Illuminate\Support\Str::substr($isPreview ? 'A' : $primaryNick, 0, 1) . ' & ' . \Illuminate\Support\Str::substr($isPreview ? 'N' : $secondaryNick, 0, 1))
        : strtoupper(\Illuminate\Support\Str::substr($primaryNick, 0, 2));

    $primaryParents = !empty($evData['primary_title_parents'])
        ? $evData['primary_title_parents']
        : 'Putra Kedua dari Bapak Drs. Budi Santoso & Ibu Sri Wahyuni';
    $secondaryParents = !empty($evData['secondary_title_parents'])
        ? $evData['secondary_title_parents']
        : 'Putri Pertama dari Bapak Ir. Hendra Wijaya & Ibu Ratna Sari';

    $primaryIg = !empty($evData['primary_instagram']) ? ltrim($evData['primary_instagram'], '@') : ($isPreview ? 'naufalpratama' : '');
    $secondaryIg = !empty($evData['secondary_instagram']) ? ltrim($evData['secondary_instagram'], '@') : ($isPreview ? 'auliazahra' : '');

    $greeting = !empty($evData['greeting'])
        ? $evData['greeting']
        : 'Dengan penuh rasa syukur dan ketenangan hati, kami mengundang Anda untuk merayakan awal babak baru kisah cinta kami.';
    $quote = !empty($evData['quote'])
        ? $evData['quote']
        : 'Dan di antara tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu isteri-isteri dari jenismu sendiri, supaya kamu cenderung dan merasa tenteram kepadanya, dan dijadikan-Nya di antaramu rasa kasih dan sayang.';

    $s1 = $evData['session_1'] ?? [];
    $s1Title = !empty($s1['title']) ? $s1['title'] : 'Akad Nikah';
    $s1Date = !empty($s1['date']) ? $s1['date'] : '2026-12-19';
    $s1Time = !empty($s1['time']) ? $s1['time'] : 'Pukul 08.00 – 10.00 WIB';
    $s1Venue = !empty($s1['venue']) ? $s1['venue'] : 'Masjid Al-Akbar Surabaya';
    $s1Address = !empty($s1['address']) ? $s1['address'] : 'Jl. Masjid Al-Akbar Timur No. 1, Pagesangan, Surabaya';
    $s1Maps = array_key_exists('maps_url', $s1) ? $s1['maps_url'] : 'https://maps.google.com/?q=Masjid+Al+Akbar+Surabaya';

    $s2 = $evData['session_2'] ?? [];
    $s2Enabled = $isPreview || !empty($s2['enabled']) || !empty($s2['title']);
    $s2Title = !empty($s2['title']) ? $s2['title'] : 'Resepsi Pernikahan';
    $s2Date = !empty($s2['date']) ? $s2['date'] : $s1Date;
    $s2Time = !empty($s2['time']) ? $s2['time'] : 'Pukul 11.00 – 14.00 WIB';
    $s2Venue = !empty($s2['venue']) ? $s2['venue'] : 'Grand Ballroom Hotel Majapahit';
    $s2Address = !empty($s2['address']) ? $s2['address'] : 'Jl. Tunjungan No. 65, Genteng, Surabaya';
    $s2Maps = array_key_exists('maps_url', $s2) ? $s2['maps_url'] : 'https://maps.google.com/?q=Hotel+Majapahit+Surabaya';

    $s1DateCarbon = \Carbon\Carbon::parse($s1Date)->locale('id');
    $s1DateFormatted = $s1DateCarbon->isoFormat('dddd, D MMMM Y');
    $s2DateFormatted = \Carbon\Carbon::parse($s2Date)->locale('id')->isoFormat('dddd, D MMMM Y');
    $s1City = trim(\Illuminate\Support\Str::afterLast($s1Address, ','));
    $coverSubline = $s1DateFormatted . ($s1City ? ' • ' . $s1City : '');

    $s1TimeClean = preg_replace('/[^0-9:]/', '', explode('-', str_replace('–', '-', $s1Time))[0] ?? '08:00');
    if (strlen($s1TimeClean) === 5 && str_contains($s1TimeClean, ':')) {
        $targetIso = $s1Date . 'T' . $s1TimeClean . ':00+07:00';
    } elseif (strlen(str_replace('.', ':', $s1TimeClean)) === 5) {
        $targetIso = $s1Date . 'T' . str_replace('.', ':', $s1TimeClean) . ':00+07:00';
    } else {
        $targetIso = !empty($targetDate) ? $targetDate : ($s1Date . 'T08:00:00+07:00');
    }

    $coverImage = !empty($evData['cover_image']) ? asset('storage/' . $evData['cover_image']) : '';
    $groomPhoto = !empty($evData['groom_photo']) ? asset('storage/' . $evData['groom_photo']) : '';
    $bridePhoto = !empty($evData['bride_photo']) ? asset('storage/' . $evData['bride_photo']) : '';
    $galleryImages = !empty($evData['gallery_images']) && is_array($evData['gallery_images']) ? $evData['gallery_images'] : [];

    $rundownItems = !empty($evData['rundown']) && is_array($evData['rundown']) ? $evData['rundown'] : [];

    $giftAccounts = !empty($evData['gift_accounts']) && is_array($evData['gift_accounts'])
        ? $evData['gift_accounts']
        : ($isPreview ? [
            ['bank' => 'Bank BCA', 'account_number' => '8290471829', 'account_name' => 'Aulia Zahra Maharani'],
            ['bank' => 'Bank Mandiri', 'account_number' => '1420098374612', 'account_name' => 'Naufal Aditya Pratama'],
        ] : []);

    $musicMode = $evData['music_mode'] ?? 'ambient';
    $musicUrl = ($musicMode === 'custom' && !empty($evData['music_file'])) ? asset('storage/' . $evData['music_file']) : '';

    $guestNameVal = !empty($guestName) ? $guestName : 'Tamu Spesial';
    $guestAddrVal = !empty($guestAddress) ? $guestAddress : '';
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>{{ $invitation->title ?? 'The Wedding of Aulia & Naufal' }} — Serene Blue 3D Invitation</title>

  <!-- Google Fonts dengan Fallback Sistem yang Aman -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Great+Vibes&family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />

  <style>
    /* ==========================================================================
       1. DESIGN TOKENS & RESET (SERENE PASTEL BLUE & SKY MIST PALETTE)
       ========================================================================== */
    :root {
      --bg-mist: #f0f9ff;
      --bg-sky-soft: #eff6ff;
      --bg-powder: #dbeafe;
      --bg-ice: #e0f2fe;
      --blue-primary: #3b82f6;
      --blue-deep: #2563eb;
      --blue-soft: #60a5fa;
      --blue-light: #bae6fd;
      --blue-powder: #93c5fd;
      --gold-warm: #e6b86a;
      --gold-champagne: #d4a373;
      --slate-navy: #1e3a5f;
      --slate-soft: #475569;
      --white-glass: rgba(255, 255, 255, 0.85);

      --font-script: 'Great Vibes', 'Playfair Display', Georgia, cursive;
      --font-serif: 'Playfair Display', Georgia, serif;
      --font-cute: 'Fredoka', 'Plus Jakarta Sans', system-ui, sans-serif;
      --font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      -webkit-tap-highlight-color: transparent;
    }

    html {
      font-size: 16px;
      scroll-behavior: smooth;
      overflow-x: hidden;
    }

    body {
      font-family: var(--font-sans);
      color: var(--slate-navy);
      background: linear-gradient(180deg, #eff6ff 0%, #dbeafe 35%, #e0f2fe 70%, #f0f9ff 100%);
      min-height: 100dvh;
      overflow-x: hidden;
      line-height: 1.6;
      -webkit-font-smoothing: antialiased;
    }

    button,
    input,
    select,
    textarea {
      font-family: inherit;
    }

    a {
      text-decoration: none;
      color: inherit;
    }

    /* ==========================================================================
       2. 3D CLAYMORPHISM SURFACES & TACTILE BUTTONS (FROSTED SKY BLUE)
       ========================================================================== */
    .clay-card {
      background: linear-gradient(145deg, rgba(255, 255, 255, 0.92), rgba(239, 246, 255, 0.85));
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border-radius: 28px;
      border: 2px solid #bae6fd;
      box-shadow:
        0 18px 38px -14px rgba(37, 99, 235, 0.16),
        inset 0 -7px 14px rgba(96, 165, 250, 0.14),
        inset 0 7px 14px rgba(255, 255, 255, 0.96);
      transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.35s ease;
      min-width: 0;
      overflow-wrap: break-word;
    }

    .clay-card:hover {
      box-shadow:
        0 24px 46px -12px rgba(37, 99, 235, 0.24),
        inset 0 -7px 14px rgba(96, 165, 250, 0.18),
        inset 0 7px 16px rgba(255, 255, 255, 1);
    }

    .clay-pill {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.4rem;
      background: linear-gradient(145deg, #ffffff, #e0f2fe);
      border-radius: 9999px;
      border: 1.5px solid #bae6fd;
      box-shadow:
        0 8px 18px -6px rgba(37, 99, 235, 0.18),
        inset 0 -3px 6px rgba(96, 165, 250, 0.14),
        inset 0 3px 6px rgba(255, 255, 255, 0.96);
    }

    .btn-clay-primary {
      position: relative;
      overflow: hidden;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.55rem;
      background: linear-gradient(135deg, #60a5fa 0%, #2563eb 100%);
      color: #ffffff;
      font-family: var(--font-cute);
      font-weight: 600;
      border-radius: 9999px;
      border: 2px solid rgba(255, 255, 255, 0.78);
      box-shadow:
        0 14px 28px -8px rgba(37, 99, 235, 0.45),
        inset 0 -5px 10px rgba(30, 58, 95, 0.28),
        inset 0 5px 10px rgba(255, 255, 255, 0.52);
      cursor: pointer;
      transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.25s ease;
    }

    .btn-clay-primary:hover {
      transform: translateY(-3px) scale(1.02);
      box-shadow:
        0 18px 34px -6px rgba(37, 99, 235, 0.56),
        inset 0 -5px 10px rgba(30, 58, 95, 0.28),
        inset 0 5px 12px rgba(255, 255, 255, 0.68);
    }

    .btn-clay-primary:active {
      transform: translateY(2px) scale(0.98);
    }

    .btn-clay-primary::after {
      content: '';
      position: absolute;
      top: -50%;
      left: -65%;
      width: 42%;
      height: 200%;
      background: linear-gradient(
        to right,
        rgba(255, 255, 255, 0) 0%,
        rgba(255, 255, 255, 0.58) 50%,
        rgba(255, 255, 255, 0) 100%
      );
      transform: rotate(24deg);
      animation: shimmerSweep 3.2s infinite ease-in-out;
    }

    @keyframes shimmerSweep {
      0%, 55% { left: -65%; }
      100% { left: 135%; }
    }

    /* ==========================================================================
       3. KEYFRAME ANIMATIONS (FLOATING, HEART PULSE, CINEMATIC INTRO)
       ========================================================================== */
    @keyframes floatSlow {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      50% { transform: translateY(-9px) rotate(1.4deg); }
    }

    @keyframes floatReverse {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      50% { transform: translateY(8px) rotate(-1.4deg); }
    }

    @keyframes pulseHeart {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.14); }
    }

    .anim-float { animation: floatSlow 4.6s ease-in-out infinite; }
    .anim-float-rev { animation: floatReverse 5.1s ease-in-out infinite; }
    .anim-pulse-heart { animation: pulseHeart 2.1s ease-in-out infinite; }

    /* Stage 1: Pure-Code Cinematic Opening Video (Calm Serene Sky Blue) */
    #stage-video-intro {
      position: fixed;
      inset: 0;
      z-index: 60;
      height: 100dvh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: space-between;
      padding: clamp(0.85rem, 3vw, 2rem);
      background: radial-gradient(circle at 50% 38%, #ffffff 0%, #e0f2fe 46%, #dbeafe 100%);
      overflow-x: hidden;
      overflow-y: auto;
      user-select: none;
      transition: opacity 0.9s cubic-bezier(0.4, 0, 0.2, 1), transform 1s cubic-bezier(0.4, 0, 0.2, 1);
    }

    #stage-video-intro.is-exiting {
      opacity: 0;
      transform: scale(1.07);
      pointer-events: none;
    }

    @keyframes doveLeftIn {
      0% { transform: translate(-110px, 24px) rotate(-28deg) scale(0.68); opacity: 0; }
      38% { transform: translate(-16px, 0px) rotate(-8deg) scale(1); opacity: 1; }
      70% { transform: translate(-14px, -5px) rotate(-5deg) scale(1.04); opacity: 1; }
      100% { transform: translate(-14px, 0px) rotate(-7deg) scale(1); opacity: 1; }
    }

    @keyframes doveRightIn {
      0% { transform: translate(110px, 24px) rotate(28deg) scale(0.68); opacity: 0; }
      38% { transform: translate(16px, 0px) rotate(8deg) scale(1); opacity: 1; }
      70% { transform: translate(14px, -5px) rotate(5deg) scale(1.04); opacity: 1; }
      100% { transform: translate(14px, 0px) rotate(7deg) scale(1); opacity: 1; }
    }

    @keyframes crystalHeartPop {
      0%, 26% { transform: scale(0.35) translateY(22px); opacity: 0; }
      52% { transform: scale(1.08) translateY(-4px); opacity: 1; }
      68%, 100% { transform: scale(1) translateY(0); opacity: 1; }
    }

    @keyframes introCaptionRise {
      0%, 40% { opacity: 0; transform: translateY(16px); }
      68%, 100% { opacity: 1; transform: translateY(0); }
    }

    @keyframes auraPulse {
      0%, 20% { transform: scale(0.3); opacity: 0; }
      50% { transform: scale(1.25); opacity: 0.85; }
      100% { transform: scale(1.05); opacity: 0.55; }
    }

    .intro-dove-left { animation: doveLeftIn 5s cubic-bezier(0.22, 1, 0.36, 1) forwards; }
    .intro-dove-right { animation: doveRightIn 5s cubic-bezier(0.22, 1, 0.36, 1) forwards; }
    .intro-heart-emblem { animation: crystalHeartPop 5s cubic-bezier(0.22, 1, 0.36, 1) forwards; }
    .intro-caption { animation: introCaptionRise 5s cubic-bezier(0.22, 1, 0.36, 1) forwards; }
    .intro-aura { animation: auraPulse 5s ease-out forwards; }

    /* ==========================================================================
       4. STAGE 2: INTERACTIVE 3D COVER (FIT 100DVH WITHOUT CLIPPING)
       ========================================================================== */
    #stage-cover {
      position: fixed;
      inset: 0;
      z-index: 40;
      min-height: 100dvh;
      max-height: 100dvh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: clamp(0.75rem, 2.5vw, 1.5rem);
      perspective: 1400px;
      background: radial-gradient(circle at 50% 25%, rgba(240, 249, 255, 0.72) 0%, rgba(219, 234, 254, 0.78) 55%, rgba(224, 242, 254, 0.82) 100%);
      overflow-y: auto;
      transition: opacity 0.9s ease, transform 1s cubic-bezier(0.65, 0, 0.35, 1);
    }

    #stage-cover.is-hidden {
      display: none;
    }

    #stage-cover.cover-opening {
      opacity: 0;
      transform: scale(1.12) translateY(-30px) rotateX(8deg);
      pointer-events: none;
    }

    .cover-card {
      width: 100%;
      max-width: 420px;
      padding: clamp(1.1rem, 3.2vw, 1.85rem) clamp(1.1rem, 3.5vw, 1.75rem);
      text-align: center;
      display: flex;
      flex-direction: column;
      align-items: center;
      transform-style: preserve-3d;
      transition: transform 0.2s ease-out;
      margin: auto;
    }

    .cover-illustration {
      width: clamp(150px, 40vw, 225px);
      height: auto;
      margin: 0.25rem 0;
    }

    /* ==========================================================================
       5. CANVAS LAYERS (CUPID 3D BACKGROUND + CONFETTI CELEBRATION)
       ========================================================================== */
    #cupid-canvas {
      position: fixed;
      inset: 0;
      width: 100%;
      height: 100%;
      z-index: 5;
      pointer-events: none;
    }

    #confetti-canvas {
      position: fixed;
      inset: 0;
      width: 100%;
      height: 100%;
      z-index: 70;
      pointer-events: none;
    }

    /* ==========================================================================
       6. STAGE 3: MAIN RESPONSIVE CONTENT LAYOUT
       ========================================================================== */
    #stage-main-content {
      position: relative;
      z-index: 15;
      padding-bottom: 5.5rem;
      overflow-x: hidden;
    }

    #stage-main-content.is-hidden {
      display: none;
    }

    .container-narrow {
      width: 100%;
      max-width: 720px;
      margin: 0 auto;
      padding: 0 clamp(1rem, 4vw, 1.5rem);
    }

    .container-wide {
      width: 100%;
      max-width: 960px;
      margin: 0 auto;
      padding: 0 clamp(1rem, 4vw, 1.5rem);
    }

    .section-block {
      padding: clamp(2rem, 5vw, 3.25rem) 0;
    }

    .section-header {
      text-align: center;
      margin-bottom: clamp(1.5rem, 4vw, 2.25rem);
    }

    .section-title {
      font-family: var(--font-serif);
      font-size: clamp(1.65rem, 5vw, 2.35rem);
      font-weight: 700;
      color: var(--slate-navy);
      margin-top: 0.45rem;
      line-height: 1.2;
    }

    .script-title {
      font-family: var(--font-script);
      font-size: clamp(2.65rem, 8.5vw, 4.5rem);
      font-weight: 400;
      color: var(--slate-navy);
      line-height: 1.12;
      word-break: break-word;
    }

    /* Responsive Grids */
    .grid-2-col {
      display: grid;
      grid-template-columns: minmax(0, 1fr);
      gap: clamp(1.1rem, 3vw, 1.75rem);
    }

    @media (min-width: 700px) {
      .grid-2-col {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }
    }

    .gallery-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: clamp(0.75rem, 2.5vw, 1.15rem);
    }

    .gallery-item {
      aspect-ratio: 4 / 5;
      border-radius: 22px;
      overflow: hidden;
      cursor: pointer;
      padding: 6px;
    }

    .gallery-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 16px;
      display: block;
      transition: transform 0.4s ease;
    }

    .gallery-item:hover img {
      transform: scale(1.05);
    }

    .countdown-grid {
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: clamp(0.45rem, 2vw, 1rem);
      max-width: 520px;
      margin: 1.5rem auto 0;
    }

    .countdown-box {
      padding: clamp(0.75rem, 2.5vw, 1.25rem) 0.25rem;
      text-align: center;
      border-radius: 22px;
      min-width: 0;
    }

    .countdown-num {
      display: block;
      font-family: var(--font-cute);
      font-size: clamp(1.25rem, 4.5vw, 2.25rem);
      font-weight: 700;
      color: var(--blue-deep);
      line-height: 1.1;
    }

    .countdown-label {
      display: block;
      font-size: clamp(0.62rem, 2vw, 0.78rem);
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      color: var(--slate-soft);
      margin-top: 0.25rem;
    }

    /* Timeline Layout */
    .timeline-list {
      position: relative;
      display: flex;
      flex-direction: column;
      gap: 1.25rem;
    }

    .timeline-item {
      display: flex;
      align-items: flex-start;
      gap: clamp(0.65rem, 2.5vw, 0.9rem);
    }

    .timeline-badge {
      width: 52px;
      height: 52px;
      border-radius: 18px;
      flex-shrink: 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* Form Controls */
    .form-group {
      display: flex;
      flex-direction: column;
      gap: 0.35rem;
      text-align: left;
    }

    .form-label {
      font-size: 0.78rem;
      font-weight: 700;
      color: var(--slate-navy);
    }

    .form-control {
      width: 100%;
      padding: 0.78rem 1rem;
      border-radius: 16px;
      background: rgba(255, 255, 255, 0.95);
      border: 1.5px solid #bae6fd;
      color: var(--slate-navy);
      font-size: 0.9rem;
      outline: none;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .form-control:focus {
      border-color: var(--blue-primary);
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
    }

    /* Scroll Reveal */
    .reveal-on-scroll {
      opacity: 0;
      transform: translateY(26px) scale(0.98);
      transition: opacity 0.75s cubic-bezier(0.22, 1, 0.36, 1), transform 0.75s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .reveal-on-scroll.is-visible {
      opacity: 1;
      transform: translateY(0) scale(1);
    }

    /* Vinyl Music Player Floating Button */
    .music-fab-wrap {
      position: fixed;
      bottom: max(1.15rem, env(safe-area-inset-bottom));
      right: 1.15rem;
      z-index: 50;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .music-fab {
      width: 52px;
      height: 52px;
      border-radius: 9999px;
      background: linear-gradient(135deg, #1e3a5f, #3b82f6);
      color: #ffffff;
      border: 3px solid #ffffff;
      box-shadow: 0 12px 24px -6px rgba(30, 58, 95, 0.42);
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: transform 0.2s ease;
    }

    .music-fab:hover {
      transform: scale(1.06);
    }

    @keyframes spinVinyl {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    .vinyl-disc {
      width: 100%;
      height: 100%;
      border-radius: 9999px;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
    }

    .vinyl-disc.is-playing {
      animation: spinVinyl 5.5s linear infinite;
    }

    /* Toast Notification */
    #cute-toast {
      position: fixed;
      top: 1.15rem;
      left: 50%;
      transform: translate(-50%, -140%);
      z-index: 80;
      padding: 0.65rem 1.2rem;
      font-family: var(--font-cute);
      font-size: 0.85rem;
      font-weight: 600;
      color: var(--slate-navy);
      opacity: 0;
      pointer-events: none;
      transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.35s ease;
      white-space: nowrap;
      max-width: 92vw;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    #cute-toast.is-shown {
      transform: translate(-50%, 0);
      opacity: 1;
    }

    /* Modal Dialogs (Guest Tester & Lightbox) */
    #guest-modal,
    #lightbox-modal {
      position: fixed;
      inset: 0;
      z-index: 75;
      background: rgba(30, 58, 95, 0.45);
      backdrop-filter: blur(6px);
      display: none;
      align-items: center;
      justify-content: center;
      padding: 1rem;
    }

    #guest-modal.is-open,
    #lightbox-modal.is-open {
      display: flex;
    }

    /* Utility Helpers */
    .text-blue-deep { color: var(--blue-deep); }
    .text-slate-soft { color: var(--slate-soft); }
    .font-cute { font-family: var(--font-cute); }
    .font-serif { font-family: var(--font-serif); }
  </style>
</head>
<body>

  <!-- ========================================================================
       SHARED SVG DEFS: 3D CLAY GRADIENTS & SOFT SHADOW FILTERS (SERENE BLUE)
       ======================================================================== -->
  <svg width="0" height="0" style="position:absolute;width:0;height:0;overflow:hidden" aria-hidden="true">
    <defs>
      <filter id="clayShadow" x="-20%" y="-20%" width="140%" height="140%">
        <feDropShadow dx="0" dy="8" stdDeviation="7" flood-color="#2563eb" flood-opacity="0.2" />
      </filter>

      <linearGradient id="ceruleanClayGrad" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#dbeafe" />
        <stop offset="48%" stop-color="#60a5fa" />
        <stop offset="100%" stop-color="#2563eb" />
      </linearGradient>

      <linearGradient id="warmGoldGrad" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#fff4cc" />
        <stop offset="50%" stop-color="#e6b86a" />
        <stop offset="100%" stop-color="#c48a3b" />
      </linearGradient>

      <linearGradient id="skyMistClayGrad" x1="15%" y1="10%" x2="85%" y2="90%">
        <stop offset="0%" stop-color="#ffffff" />
        <stop offset="48%" stop-color="#e0f2fe" />
        <stop offset="100%" stop-color="#93c5fd" />
      </linearGradient>

      <linearGradient id="powderBlueClayGrad" x1="10%" y1="10%" x2="90%" y2="90%">
        <stop offset="0%" stop-color="#f0f9ff" />
        <stop offset="52%" stop-color="#bae6fd" />
        <stop offset="100%" stop-color="#7dd3fc" />
      </linearGradient>

      <linearGradient id="periwinkleClayGrad" x1="10%" y1="10%" x2="90%" y2="90%">
        <stop offset="0%" stop-color="#ffffff" />
        <stop offset="50%" stop-color="#e0e7ff" />
        <stop offset="100%" stop-color="#a5b4fc" />
      </linearGradient>

      <linearGradient id="skinClayGrad" x1="20%" y1="10%" x2="80%" y2="90%">
        <stop offset="0%" stop-color="#fff4ed" />
        <stop offset="60%" stop-color="#ffdecb" />
        <stop offset="100%" stop-color="#f3bc9c" />
      </linearGradient>

      <linearGradient id="suitClayGrad" x1="15%" y1="10%" x2="85%" y2="95%">
        <stop offset="0%" stop-color="#3b5b84" />
        <stop offset="55%" stop-color="#1e3a5f" />
        <stop offset="100%" stop-color="#10223b" />
      </linearGradient>

      <linearGradient id="gownClayGrad" x1="20%" y1="0%" x2="80%" y2="100%">
        <stop offset="0%" stop-color="#ffffff" />
        <stop offset="65%" stop-color="#f0f9ff" />
        <stop offset="100%" stop-color="#dbeafe" />
      </linearGradient>

      <linearGradient id="leafClayGrad" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#d1fae5" />
        <stop offset="55%" stop-color="#6ee7b7" />
        <stop offset="100%" stop-color="#34d399" />
      </linearGradient>
    </defs>
  </svg>

  <!-- ========================================================================
       CANVAS LAYER 1: ANIMATED 3D CLAY CUPID & BOUNCING HEARTS BACKGROUND
       ======================================================================== -->
  <canvas id="cupid-canvas" aria-hidden="true"></canvas>

  <!-- ========================================================================
       CANVAS LAYER 2: CELEBRATION CONFETTI & HEARTS BURST
       ======================================================================== -->
  <canvas id="confetti-canvas" aria-hidden="true"></canvas>

  <!-- ========================================================================
       STAGE 1: PURE-CODE CINEMATIC OPENING VIDEO (AUTO-PLAY + SKIP BUTTON)
       ======================================================================== -->
  <section id="stage-video-intro" aria-label="Video Pembukaan Undangan">
    <!-- Top Bar -->
    <div style="width:100%;max-width:760px;display:flex;align-items:center;justify-content:space-between;z-index:5;">
      <div class="clay-pill" style="padding:0.4rem 0.9rem;font-size:0.75rem;font-weight:600;color:var(--slate-soft);">
        <span style="width:8px;height:8px;border-radius:50%;background:var(--blue-primary);display:inline-block;"></span>
        <span>Love Story Intro</span>
      </div>

      <button
        id="btn-skip-video"
        type="button"
        class="clay-pill"
        style="padding:0.45rem 1rem;font-size:0.8rem;font-weight:700;color:var(--slate-navy);cursor:pointer;"
      >
        <span>Lewati Intro ➔</span>
      </button>
    </div>

    <!-- Center 3D Cinematic Stage -->
    <div style="position:relative;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;margin:auto 0;z-index:4;width:100%;max-width:440px;">
      <div class="intro-aura" style="position:absolute;width:260px;height:260px;border-radius:50%;background:radial-gradient(circle,rgba(186,230,253,0.9) 0%,rgba(230,184,106,0.32) 55%,transparent 75%);filter:blur(18px);"></div>

      <div style="position:relative;width:min(280px, 85vw);height:210px;display:flex;align-items:center;justify-content:center;">
        <svg class="intro-dove-left" style="position:absolute;width:145px;height:145px;" viewBox="0 0 160 160" filter="url(#clayShadow)">
          <path d="M12 105 Q45 128 84 96" fill="none" stroke="url(#ceruleanClayGrad)" stroke-width="5" stroke-linecap="round" />
          <circle cx="84" cy="92" r="38" fill="none" stroke="url(#warmGoldGrad)" stroke-width="12" />
          <ellipse cx="70" cy="60" rx="9" ry="3.5" transform="rotate(-25 70 60)" fill="#ffffff" opacity="0.85" />
          <g transform="translate(34, 20)">
            <ellipse cx="26" cy="25" rx="20" ry="14" fill="url(#gownClayGrad)" />
            <circle cx="39" cy="17" r="11" fill="url(#gownClayGrad)" />
            <path d="M49 17 L57 20 L49 23 Z" fill="url(#warmGoldGrad)" />
            <circle cx="42" cy="15" r="2.2" fill="#1e3a5f" />
            <ellipse cx="43" cy="19" rx="3.5" ry="2" fill="#93c5fd" opacity="0.8" />
            <path d="M10 20 Q0 6 18 13 Q26 16 24 24 Z" fill="#ffffff" />
          </g>
        </svg>

        <svg class="intro-dove-right" style="position:absolute;width:145px;height:145px;" viewBox="0 0 160 160" filter="url(#clayShadow)">
          <path d="M148 105 Q115 128 76 96" fill="none" stroke="url(#ceruleanClayGrad)" stroke-width="5" stroke-linecap="round" />
          <circle cx="76" cy="92" r="38" fill="none" stroke="url(#ceruleanClayGrad)" stroke-width="12" />
          <polygon points="76,36 87,47 76,56 65,47" fill="#e0f2fe" stroke="#ffffff" stroke-width="2" />
          <g transform="translate(68, 20)">
            <ellipse cx="32" cy="25" rx="20" ry="14" fill="url(#gownClayGrad)" />
            <circle cx="19" cy="17" r="11" fill="url(#gownClayGrad)" />
            <path d="M9 17 L1 20 L9 23 Z" fill="url(#warmGoldGrad)" />
            <circle cx="16" cy="15" r="2.2" fill="#1e3a5f" />
            <ellipse cx="15" cy="19" rx="3.5" ry="2" fill="#93c5fd" opacity="0.8" />
            <path d="M48 20 Q58 6 40 13 Q32 16 34 24 Z" fill="#ffffff" />
          </g>
        </svg>

        <div class="intro-heart-emblem" style="position:relative;z-index:6;margin-top:1.75rem;">
          <svg style="width:126px;height:126px;" viewBox="0 0 160 160" filter="url(#clayShadow)">
            <path
              d="M80 138 C80 138 22 100 22 58 C22 36 39 22 57 22 C69 22 76 29 80 37 C84 29 91 22 103 22 C121 22 138 36 138 58 C138 100 80 138 80 138 Z"
              fill="url(#skyMistClayGrad)"
              stroke="#ffffff"
              stroke-width="4.5"
            />
            <ellipse cx="52" cy="46" rx="14" ry="7" transform="rotate(-30 52 46)" fill="#ffffff" opacity="0.92" />
            <text
              x="80"
              y="84"
              text-anchor="middle"
              fill="#1e3a5f"
              font-family="Playfair Display, Georgia, serif"
              font-weight="700"
              font-size="28"
            >{{ $initials }}</text>
          </svg>
        </div>
      </div>

      <p class="intro-caption font-cute" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.16em;font-weight:600;color:var(--blue-deep);margin-top:0.4rem;">
        {{ $isPreview ? 'The Wedding Celebration of' : ($invitation->title ?? 'The Wedding Celebration of') }}
      </p>
      <h1 class="intro-caption script-title" style="margin-top:0.25rem;">
        {!! $coupleDisplay !!}
      </h1>
      <p class="intro-caption" style="font-size:0.85rem;color:var(--slate-soft);font-weight:600;margin-top:0.25rem;">
        {{ $s1DateFormatted }}
      </p>
    </div>

    <!-- Bottom Progress Bar -->
    <div style="width:100%;max-width:360px;z-index:5;display:flex;flex-direction:column;align-items:center;gap:0.45rem;">
      <div style="width:100%;height:8px;background:rgba(255,255,255,0.85);border-radius:9999px;overflow:hidden;padding:1.5px;box-shadow:inset 0 1px 3px rgba(30,58,95,0.1);">
        <div id="video-progress-bar" style="height:100%;width:0%;border-radius:9999px;background:linear-gradient(90deg,#60a5fa,#2563eb,#e6b86a);transition:width 0.1s linear;"></div>
      </div>
      <span style="font-size:0.72rem;color:var(--slate-soft);font-weight:500;">Menyiapkan undangan spesial untuk Anda...</span>
    </div>
  </section>

  <!-- ========================================================================
       STAGE 2: INTERACTIVE 3D COVER & DYNAMIC GUEST NAME (?to=)
       ======================================================================== -->
  <section id="stage-cover" class="is-hidden" aria-label="Sampul Undangan">
    @if($isPreview)
    <button
      id="btn-test-guest"
      type="button"
      class="clay-pill"
      title="Ubah Nama Tamu (?to=)"
      style="position:fixed;top:0.85rem;right:0.85rem;z-index:45;padding:0.42rem 0.85rem;font-size:0.75rem;font-weight:600;color:var(--slate-soft);cursor:pointer;"
    >
      <span>✏️ Ubah Tamu</span>
    </button>
    @endif

    <!-- 3D Tilt Cover Card -->
    <div id="cover-tilt-card" class="clay-card cover-card">
      <div class="clay-pill font-cute" style="padding:0.3rem 0.9rem;font-size:0.72rem;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:var(--blue-deep);">
        ✨ {{ strtoupper($invitation->category ?? 'Wedding Invitation') === 'PERNIKAHAN' ? 'Wedding Invitation' : ($invitation->category ?? 'Wedding Invitation') }} ✨
      </div>

      <!-- 3D Clay Bride & Groom Under Serene Blue Hydrangea & White Rose Floral Arch -->
      <div class="cover-illustration anim-float">
        <svg style="width:100%;height:auto;display:block;" viewBox="0 0 320 290" filter="url(#clayShadow)">
          <path d="M52 256 L52 132 A108 108 0 0 1 268 132 L268 256" fill="none" stroke="url(#skyMistClayGrad)" stroke-width="24" stroke-linecap="round" />
          <path d="M52 256 L52 132 A108 108 0 0 1 268 132 L268 256" fill="none" stroke="#ffffff" stroke-width="5" stroke-linecap="round" stroke-dasharray="1 18" opacity="0.95" />

          <ellipse cx="115" cy="34" rx="18" ry="9" transform="rotate(-28 115 34)" fill="url(#leafClayGrad)" />
          <ellipse cx="205" cy="34" rx="18" ry="9" transform="rotate(28 205 34)" fill="url(#leafClayGrad)" />
          <ellipse cx="72" cy="74" rx="16" ry="8" transform="rotate(-55 72 74)" fill="url(#leafClayGrad)" />
          <ellipse cx="248" cy="74" rx="16" ry="8" transform="rotate(55 248 74)" fill="url(#leafClayGrad)" />
          <circle cx="160" cy="24" r="20" fill="url(#ceruleanClayGrad)" />
          <circle cx="160" cy="24" r="9" fill="#e0f2fe" />
          <circle cx="132" cy="32" r="15" fill="url(#gownClayGrad)" />
          <circle cx="188" cy="32" r="15" fill="url(#gownClayGrad)" />
          <circle cx="96" cy="52" r="14" fill="url(#periwinkleClayGrad)" />
          <circle cx="224" cy="52" r="14" fill="url(#periwinkleClayGrad)" />

          <path d="M160 85 C160 85 147 75 147 66 C147 61 151 58 155 58 C158 58 159 60 160 62 C161 60 162 58 165 58 C169 58 173 61 173 66 C173 75 160 85 160 85 Z" fill="url(#ceruleanClayGrad)" />

          <g transform="translate(82, 102)">
            <path d="M14 146 C14 106 24 92 46 92 C68 92 78 106 78 146 Z" fill="url(#suitClayGrad)" />
            <polygon points="36,92 56,92 46,122" fill="#ffffff" />
            <polygon points="38,97 46,102 38,107" fill="url(#ceruleanClayGrad)" />
            <polygon points="54,97 46,102 54,107" fill="url(#ceruleanClayGrad)" />
            <circle cx="46" cy="102" r="3" fill="#bae6fd" />
            <rect x="14" y="28" width="64" height="64" rx="32" fill="url(#skinClayGrad)" />
            <path d="M14 54 C14 24 28 14 48 14 C68 14 78 26 78 52 C74 40 62 34 46 36 C30 38 20 44 14 54 Z" fill="#1e293b" />
            <circle cx="34" cy="60" r="4" fill="#1e293b" />
            <circle cx="35.5" cy="58.5" r="1.4" fill="#ffffff" />
            <circle cx="58" cy="60" r="4" fill="#1e293b" />
            <circle cx="59.5" cy="58.5" r="1.4" fill="#ffffff" />
            <ellipse cx="27" cy="68" rx="5.5" ry="3.2" fill="#fda4af" opacity="0.6" />
            <ellipse cx="65" cy="68" rx="5.5" ry="3.2" fill="#fda4af" opacity="0.6" />
            <path d="M41 70 Q46 76 51 70" fill="none" stroke="#64748b" stroke-width="2.6" stroke-linecap="round" />
          </g>

          <g transform="translate(152, 106)">
            <path d="M10 40 C4 83 6 123 18 142 L74 142 C86 123 88 83 82 40 Z" fill="#ffffff" opacity="0.88" />
            <path d="M12 142 C14 104 24 90 46 90 C68 90 78 104 80 142 Z" fill="url(#gownClayGrad)" />
            <path d="M26 92 Q46 104 66 92" fill="none" stroke="#60a5fa" stroke-width="3" stroke-linecap="round" />
            <rect x="12" y="26" width="68" height="68" rx="34" fill="#334155" />
            <rect x="16" y="28" width="60" height="60" rx="30" fill="url(#skinClayGrad)" />
            <path d="M16 50 C18 28 32 22 46 32 C60 22 74 28 76 50 C66 40 54 38 46 42 C38 38 26 40 16 50 Z" fill="#334155" />
            <circle cx="34" cy="24" r="7" fill="url(#ceruleanClayGrad)" />
            <circle cx="46" cy="21" r="8" fill="url(#gownClayGrad)" />
            <circle cx="58" cy="24" r="7" fill="url(#periwinkleClayGrad)" />
            <circle cx="35" cy="58" r="4" fill="#1e293b" />
            <circle cx="36.5" cy="56.5" r="1.4" fill="#ffffff" />
            <circle cx="57" cy="58" r="4" fill="#1e293b" />
            <circle cx="58.5" cy="56.5" r="1.4" fill="#ffffff" />
            <ellipse cx="28" cy="66" rx="5.5" ry="3.2" fill="#fda4af" opacity="0.65" />
            <ellipse cx="64" cy="66" rx="5.5" ry="3.2" fill="#fda4af" opacity="0.65" />
            <path d="M41 68 Q46 74 51 68" fill="none" stroke="#64748b" stroke-width="2.6" stroke-linecap="round" />
            <g transform="translate(26, 104)">
              <circle cx="14" cy="12" r="9" fill="url(#ceruleanClayGrad)" />
              <circle cx="26" cy="12" r="9" fill="url(#gownClayGrad)" />
              <circle cx="20" cy="6" r="9" fill="url(#powderBlueClayGrad)" />
              <circle cx="20" cy="16" r="7" fill="url(#leafClayGrad)" />
            </g>
          </g>

          <rect x="44" y="246" width="232" height="22" rx="11" fill="url(#skyMistClayGrad)" stroke="#ffffff" stroke-width="3" />
        </svg>
      </div>

      <h2 class="script-title" style="font-size:clamp(2.2rem, 7.5vw, 3.2rem);margin-top:0.15rem;">
        {!! $coupleDisplay !!}
      </h2>
      <p style="font-size:0.82rem;color:var(--slate-soft);font-weight:600;">
        {{ $coverSubline }}
      </p>

      <!-- Dynamic Guest Box -->
      <div style="width:100%;margin-top:0.85rem;padding:0.75rem 1rem;border-radius:20px;background:rgba(255,255,255,0.88);border:1.5px solid #bae6fd;">
        <p style="font-size:0.68rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--slate-soft);font-weight:700;">
          Kepada Yth. Bapak/Ibu/Saudara/i
        </p>
        <p id="guest-name-display" class="font-cute" style="font-size:clamp(1.1rem, 4vw, 1.35rem);font-weight:700;color:var(--slate-navy);margin-top:0.2rem;word-break:break-word;">
          {{ $guestNameVal }}
        </p>
        @if(!empty($guestAddrVal))
          <p style="font-size:0.75rem;color:var(--slate-soft);margin-top:0.15rem;word-break:break-word;">
            di {{ $guestAddrVal }}
          </p>
        @endif
      </div>

      <!-- Open Invitation Button -->
      <button
        id="btn-open-invitation"
        type="button"
        class="btn-clay-primary"
        style="width:100%;margin-top:1rem;padding:0.9rem 1.4rem;font-size:clamp(0.95rem, 3.5vw, 1.08rem);"
      >
        <span class="anim-pulse-heart" style="font-size:1.15rem;">💌</span>
        <span>Buka Undangan</span>
      </button>
    </div>
  </section>

  <!-- ========================================================================
       STAGE 3: MAIN ROMANTIC 3D WEDDING INVITATION CONTENT
       ======================================================================== -->
  <main id="stage-main-content" class="is-hidden">

    <!-- Floating Vinyl Music Controller -->
    @if($musicMode !== 'none')
    <div class="music-fab-wrap">
      <div class="clay-pill" style="padding:0.35rem 0.8rem;font-size:0.72rem;font-weight:600;color:var(--slate-navy);">
        <span>🎵 {{ $musicUrl ? 'Musik Latar' : 'Music Box' }}</span>
      </div>
      <button id="btn-toggle-music" type="button" class="music-fab" aria-label="Putar atau Jeda Musik">
        <div id="vinyl-disc" class="vinyl-disc is-playing">
          <span style="position:absolute;inset:5px;border-radius:50%;border:1px solid rgba(255,255,255,0.3);"></span>
          <span style="width:20px;height:20px;border-radius:50%;background:var(--blue-soft);display:flex;align-items:center;justify-content:center;font-size:0.7rem;">♪</span>
        </div>
      </button>
    </div>
    @endif

    <!-- ======================================================================
         1. HERO SECTION
         ====================================================================== -->
    <header class="section-block" style="padding-top:clamp(2.25rem, 6vw, 3.75rem);text-align:center;">
      <div class="container-narrow">
        <div class="clay-pill font-cute" style="padding:0.35rem 1rem;font-size:0.74rem;font-weight:600;letter-spacing:0.12em;text-transform:uppercase;color:var(--blue-deep);margin-bottom:0.75rem;">
          {{ $isPreview ? 'We Are Getting Married' : ($invitation->title ?? 'We Are Getting Married') }}
        </div>

        <h1 class="script-title">
          @if($hasSecondary)
            {{ $isPreview ? 'Aulia' : $primaryNick }} <span style="color:var(--blue-primary);">&amp;</span> {{ $isPreview ? 'Naufal' : $secondaryNick }}
          @else
            {{ $primaryName }}
          @endif
        </h1>

        <p style="font-size:clamp(0.88rem, 2.8vw, 1rem);color:var(--slate-soft);max-width:460px;margin:0.65rem auto 0;">
          {{ $greeting }}
        </p>

        <!-- Cover Photo (Jika diunggah) atau 3D Serene Sky Gazebo Illustration -->
        @if($coverImage)
          <div class="anim-float clay-card" style="width:min(260px, 72vw);aspect-ratio:1/1;margin:1.5rem auto;padding:10px;border-radius:50%;cursor:pointer;" onclick="window.openCupidLightbox && window.openCupidLightbox('{{ $coverImage }}')">
            <img src="{{ $coverImage }}" alt="{{ $invitation->title ?? 'Foto Sampul' }}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;display:block;" />
          </div>
        @else
          <div class="anim-float" style="max-width:360px;margin:1.5rem auto;">
            <svg style="width:100%;height:auto;display:block;" viewBox="0 0 400 300" filter="url(#clayShadow)">
              <path d="M55 108 Q200 16 345 108 Z" fill="url(#skyMistClayGrad)" stroke="#ffffff" stroke-width="3" />
              <path d="M95 108 Q200 36 305 108 Z" fill="url(#powderBlueClayGrad)" opacity="0.82" />
              <circle cx="200" cy="34" r="14" fill="url(#warmGoldGrad)" />
              <path d="M55 108 Q90 124 127 108 Q163 124 200 108 Q236 124 272 108 Q308 124 345 108" fill="none" stroke="url(#ceruleanClayGrad)" stroke-width="8" stroke-linecap="round" />

              <rect x="78" y="112" width="14" height="152" rx="7" fill="url(#gownClayGrad)" />
              <rect x="308" y="112" width="14" height="152" rx="7" fill="url(#gownClayGrad)" />
              <circle cx="85" cy="124" r="14" fill="url(#ceruleanClayGrad)" />
              <circle cx="102" cy="116" r="11" fill="url(#periwinkleClayGrad)" />
              <circle cx="315" cy="124" r="14" fill="url(#ceruleanClayGrad)" />
              <circle cx="298" cy="116" r="11" fill="url(#periwinkleClayGrad)" />

              <path d="M92 116 Q200 152 308 116" fill="none" stroke="#e6b86a" stroke-width="2.5" stroke-dasharray="4 4" />
              <circle cx="145" cy="131" r="4.5" fill="#fef3c7" />
              <circle cx="200" cy="136" r="5.5" fill="#fef3c7" />
              <circle cx="255" cy="131" r="4.5" fill="#fef3c7" />

              <g transform="translate(122, 130)">
                <path d="M14 134 C14 98 24 86 44 86 C64 86 74 98 74 134 Z" fill="url(#suitClayGrad)" />
                <polygon points="35,86 53,86 44,112" fill="#ffffff" />
                <rect x="16" y="28" width="56" height="56" rx="28" fill="url(#skinClayGrad)" />
                <path d="M16 50 C16 24 30 16 46 16 C62 16 72 26 72 48 C64 38 52 34 42 36 C30 38 22 42 16 50 Z" fill="#1e293b" />
                <circle cx="34" cy="56" r="3.5" fill="#1e293b" />
                <circle cx="54" cy="56" r="3.5" fill="#1e293b" />
                <ellipse cx="28" cy="63" rx="5" ry="2.8" fill="#fda4af" opacity="0.65" />
                <ellipse cx="60" cy="63" rx="5" ry="2.8" fill="#fda4af" opacity="0.65" />
                <path d="M40 65 Q44 70 48 65" fill="none" stroke="#64748b" stroke-width="2.4" stroke-linecap="round" />
              </g>

              <g transform="translate(194, 134)">
                <path d="M12 130 C14 96 24 84 44 84 C64 84 74 96 76 130 Z" fill="url(#gownClayGrad)" />
                <rect x="14" y="26" width="60" height="60" rx="30" fill="#334155" />
                <rect x="17" y="28" width="54" height="54" rx="27" fill="url(#skinClayGrad)" />
                <path d="M17 48 C20 28 32 22 44 32 C56 22 68 28 71 48 C62 38 52 36 44 40 C36 36 26 38 17 48 Z" fill="#334155" />
                <circle cx="44" cy="22" r="7" fill="url(#ceruleanClayGrad)" />
                <circle cx="34" cy="54" r="3.5" fill="#1e293b" />
                <circle cx="54" cy="54" r="3.5" fill="#1e293b" />
                <ellipse cx="28" cy="61" rx="5" ry="2.8" fill="#fda4af" opacity="0.65" />
                <ellipse cx="60" cy="61" rx="5" ry="2.8" fill="#fda4af" opacity="0.65" />
                <path d="M40 63 Q44 68 48 63" fill="none" stroke="#64748b" stroke-width="2.4" stroke-linecap="round" />
              </g>

              <path d="M200 172 C200 172 189 163 189 155 C189 150 193 147 196 147 C198 147 199 149 200 151 C201 149 202 147 204 147 C207 147 211 150 211 155 C211 163 200 172 200 172 Z" fill="url(#ceruleanClayGrad)" />
              <rect x="52" y="262" width="296" height="22" rx="11" fill="url(#skyMistClayGrad)" stroke="#ffffff" stroke-width="3" />
            </svg>
          </div>
        @endif

        <!-- Save the Date Pill Card -->
        <div class="clay-card" style="display:inline-flex;flex-wrap:wrap;align-items:center;justify-content:center;gap:1rem;padding:1rem 1.5rem;max-width:100%;">
          <div style="text-align:center;">
            <p style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--slate-soft);font-weight:700;">Save The Date</p>
            <p class="font-cute" style="font-size:1.05rem;font-weight:700;color:var(--slate-navy);">{{ $s1DateFormatted }}</p>
          </div>
          <button id="btn-save-date" type="button" class="btn-clay-primary" style="padding:0.65rem 1.2rem;font-size:0.85rem;">
            <span>📅 Simpan ke Kalender</span>
          </button>
        </div>
      </div>
    </header>

    <!-- ======================================================================
         2. AYAT CINTA / QUOTES
         ====================================================================== -->
    <section class="section-block reveal-on-scroll">
      <div class="container-narrow">
        <div class="clay-card" style="padding:clamp(1.5rem, 4vw, 2.25rem);text-align:center;position:relative;">
          <div style="width:76px;height:44px;margin:-2.3rem auto 0.6rem;">
            <svg viewBox="0 0 120 70" filter="url(#clayShadow)">
              <path d="M60 35 C35 10 10 15 15 35 C10 55 35 60 60 35 Z" fill="url(#ceruleanClayGrad)" />
              <path d="M60 35 C85 10 110 15 105 35 C110 55 85 60 60 35 Z" fill="url(#ceruleanClayGrad)" />
              <circle cx="60" cy="35" r="11" fill="url(#powderBlueClayGrad)" stroke="#ffffff" stroke-width="2" />
            </svg>
          </div>

          <p class="font-serif" style="font-style:italic;font-size:clamp(0.95rem, 2.8vw, 1.12rem);line-height:1.7;">
            “{{ $quote }}”
          </p>
          <div class="clay-pill font-cute" style="margin-top:1rem;padding:0.3rem 0.95rem;font-size:0.75rem;font-weight:600;color:var(--blue-deep);">
            {{ $isPreview || str_contains($quote, 'tanda-tanda') ? 'QS. Ar-Rum : 21' : ($invitation->title ?? 'Untaian Doa') }}
          </div>
        </div>
      </div>
    </section>

    <!-- ======================================================================
         3. PROFIL MEMPELAI (BRIDE & GROOM 3D CLAY CARDS)
         ====================================================================== -->
    <section class="section-block reveal-on-scroll">
      <div class="container-wide">
        <div class="section-header">
          <div class="clay-pill font-cute" style="padding:0.3rem 0.95rem;font-size:0.74rem;font-weight:600;text-transform:uppercase;color:var(--blue-deep);">
            {{ $hasSecondary ? 'Bride &amp; Groom' : 'Penyelenggara Acara' }}
          </div>
          <h2 class="section-title">{{ $hasSecondary ? 'Mempelai Berbahagia' : 'Profil Penyelenggara' }}</h2>
        </div>

        <div class="{{ $hasSecondary ? 'grid-2-col' : 'container-narrow' }}">
          @if($hasSecondary)
          <!-- Bride Card (Mempelai 2 / Wanita) -->
          <div class="clay-card profile-tilt-card" style="padding:clamp(1.4rem, 4vw, 2rem);text-align:center;display:flex;flex-direction:column;align-items:center;">
            <div style="width:128px;height:128px;border-radius:50%;background:linear-gradient(180deg,#dbeafe,#f0f9ff);padding:6px;margin-bottom:0.9rem;overflow:hidden;flex-shrink:0;">
              @if($bridePhoto)
                <img src="{{ $bridePhoto }}" alt="{{ $brideDisplayName }}" loading="lazy" decoding="async" style="width:100%;height:100%;object-fit:cover;border-radius:50%;display:block;" />
              @else
                <svg style="width:100%;height:100%;" viewBox="0 0 140 140" filter="url(#clayShadow)">
                  <circle cx="70" cy="70" r="62" fill="url(#skyMistClayGrad)" />
                  <rect x="34" y="28" width="72" height="76" rx="36" fill="#334155" />
                  <path d="M30 124 C34 98 48 92 70 92 C92 92 106 98 110 124 Z" fill="url(#gownClayGrad)" />
                  <rect x="39" y="34" width="62" height="62" rx="31" fill="url(#skinClayGrad)" />
                  <path d="M39 58 C42 36 56 30 70 40 C84 30 98 36 101 58 C90 46 78 44 70 48 C62 44 50 46 39 58 Z" fill="#334155" />
                  <circle cx="56" cy="30" r="8" fill="url(#ceruleanClayGrad)" />
                  <circle cx="70" cy="27" r="9" fill="url(#gownClayGrad)" />
                  <circle cx="84" cy="30" r="8" fill="url(#periwinkleClayGrad)" />
                  <circle cx="58" cy="65" r="4" fill="#1e293b" />
                  <circle cx="59.5" cy="63.5" r="1.4" fill="#ffffff" />
                  <circle cx="82" cy="65" r="4" fill="#1e293b" />
                  <circle cx="83.5" cy="63.5" r="1.4" fill="#ffffff" />
                  <ellipse cx="51" cy="73" rx="6" ry="3.5" fill="#fda4af" opacity="0.65" />
                  <ellipse cx="89" cy="73" rx="6" ry="3.5" fill="#fda4af" opacity="0.65" />
                  <path d="M64 75 Q70 81 76 75" fill="none" stroke="#64748b" stroke-width="2.6" stroke-linecap="round" />
                </svg>
              @endif
            </div>

            <span class="font-cute" style="font-size:0.74rem;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:var(--blue-deep);">The Bride</span>
            <h3 class="font-serif" style="font-size:clamp(1.25rem, 3.8vw, 1.5rem);font-weight:700;margin-top:0.2rem;">
              {{ $brideDisplayName }}
            </h3>
            @if(!empty($secondaryParents))
              <p style="font-size:0.84rem;color:var(--slate-soft);margin-top:0.45rem;">
                {{ $secondaryParents }}
              </p>
            @endif
            @if(!empty($secondaryIg))
              <a href="https://instagram.com/{{ $secondaryIg }}" target="_blank" rel="noopener noreferrer" class="clay-pill" style="margin-top:0.9rem;padding:0.35rem 0.9rem;font-size:0.78rem;font-weight:600;">
                {{ '@' . $secondaryIg }}
              </a>
            @endif
          </div>
          @endif

          <!-- Groom Card (Mempelai 1 / Pria) -->
          <div class="clay-card profile-tilt-card" style="padding:clamp(1.4rem, 4vw, 2rem);text-align:center;display:flex;flex-direction:column;align-items:center;">
            <div style="width:128px;height:128px;border-radius:50%;background:linear-gradient(180deg,#e0f2fe,#dbeafe);padding:6px;margin-bottom:0.9rem;overflow:hidden;flex-shrink:0;">
              @if($groomPhoto)
                <img src="{{ $groomPhoto }}" alt="{{ $groomDisplayName }}" loading="lazy" decoding="async" style="width:100%;height:100%;object-fit:cover;border-radius:50%;display:block;" />
              @else
                <svg style="width:100%;height:100%;" viewBox="0 0 140 140" filter="url(#clayShadow)">
                  <circle cx="70" cy="70" r="62" fill="url(#powderBlueClayGrad)" />
                  <path d="M30 124 C34 98 48 92 70 92 C92 92 106 98 110 124 Z" fill="url(#suitClayGrad)" />
                  <polygon points="58,92 82,92 70,118" fill="#ffffff" />
                  <polygon points="61,97 70,102 61,107" fill="url(#ceruleanClayGrad)" />
                  <polygon points="79,97 70,102 79,107" fill="url(#ceruleanClayGrad)" />
                  <rect x="38" y="34" width="64" height="64" rx="32" fill="url(#skinClayGrad)" />
                  <path d="M38 60 C38 30 52 20 72 20 C92 20 102 32 102 58 C96 46 84 40 68 42 C52 44 44 50 38 60 Z" fill="#1e293b" />
                  <circle cx="58" cy="65" r="4" fill="#1e293b" />
                  <circle cx="59.5" cy="63.5" r="1.4" fill="#ffffff" />
                  <circle cx="82" cy="65" r="4" fill="#1e293b" />
                  <circle cx="83.5" cy="63.5" r="1.4" fill="#ffffff" />
                  <ellipse cx="51" cy="73" rx="6" ry="3.5" fill="#fda4af" opacity="0.6" />
                  <ellipse cx="89" cy="73" rx="6" ry="3.5" fill="#fda4af" opacity="0.6" />
                  <path d="M64 75 Q70 81 76 75" fill="none" stroke="#64748b" stroke-width="2.6" stroke-linecap="round" />
                </svg>
              @endif
            </div>

            <span class="font-cute" style="font-size:0.74rem;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:var(--blue-deep);">{{ $hasSecondary ? 'The Groom' : 'Host' }}</span>
            <h3 class="font-serif" style="font-size:clamp(1.25rem, 3.8vw, 1.5rem);font-weight:700;margin-top:0.2rem;">
              {{ $groomDisplayName }}
            </h3>
            @if(!empty($primaryParents))
              <p style="font-size:0.84rem;color:var(--slate-soft);margin-top:0.45rem;">
                {{ $primaryParents }}
              </p>
            @endif
            @if(!empty($primaryIg))
              <a href="https://instagram.com/{{ $primaryIg }}" target="_blank" rel="noopener noreferrer" class="clay-pill" style="margin-top:0.9rem;padding:0.35rem 0.9rem;font-size:0.78rem;font-weight:600;">
                {{ '@' . $primaryIg }}
              </a>
            @endif
          </div>
        </div>
      </div>
    </section>

    <!-- ======================================================================
         4. COUNTDOWN TIMER
         ====================================================================== -->
    <section class="section-block reveal-on-scroll">
      <div class="container-narrow" style="text-align:center;">
        <h2 class="section-title">Menghitung Hari Bahagia</h2>
        <p style="font-size:0.86rem;color:var(--slate-soft);margin-top:0.3rem;">
          Setiap detik membawa kami semakin dekat menuju janji suci
        </p>

        <div class="countdown-grid">
          <div class="clay-card countdown-box anim-float">
            <span id="cd-days" class="countdown-num">00</span>
            <span class="countdown-label">Hari</span>
          </div>
          <div class="clay-card countdown-box anim-float-rev">
            <span id="cd-hours" class="countdown-num">00</span>
            <span class="countdown-label">Jam</span>
          </div>
          <div class="clay-card countdown-box anim-float">
            <span id="cd-minutes" class="countdown-num">00</span>
            <span class="countdown-label">Menit</span>
          </div>
          <div class="clay-card countdown-box anim-float-rev">
            <span id="cd-seconds" class="countdown-num">00</span>
            <span class="countdown-label">Detik</span>
          </div>
        </div>
      </div>
    </section>

    <!-- ======================================================================
         5. RANGKAIAN ACARA (AKAD NIKAH & RESEPSI)
         ====================================================================== -->
    <section class="section-block reveal-on-scroll">
      <div class="container-wide">
        <div class="section-header">
          <div class="clay-pill font-cute" style="padding:0.3rem 0.95rem;font-size:0.74rem;font-weight:600;text-transform:uppercase;color:var(--blue-deep);">
            Wedding Events
          </div>
          <h2 class="section-title">Rangkaian Acara</h2>
        </div>

        <div class="{{ $s2Enabled ? 'grid-2-col' : 'container-narrow' }}">
          <!-- Sesi 1 -->
          <div class="clay-card" style="padding:clamp(1.4rem, 4vw, 2rem);text-align:center;display:flex;flex-direction:column;align-items:center;justify-content:space-between;">
            <div>
              <div class="anim-float" style="width:88px;height:88px;margin:0 auto 0.5rem;">
                <svg viewBox="0 0 120 120" filter="url(#clayShadow)">
                  <circle cx="48" cy="68" r="26" fill="none" stroke="url(#warmGoldGrad)" stroke-width="10" />
                  <circle cx="74" cy="68" r="26" fill="none" stroke="url(#ceruleanClayGrad)" stroke-width="10" />
                  <polygon points="74,28 83,37 74,45 65,37" fill="#e0f2fe" stroke="#ffffff" stroke-width="1.5" />
                </svg>
              </div>
              <h3 class="font-serif" style="font-size:1.45rem;font-weight:700;">{{ $s1Title }}</h3>
              <p style="font-weight:700;font-size:0.9rem;margin-top:0.65rem;">{{ $s1DateFormatted }}</p>
              <div class="clay-pill font-cute" style="margin-top:0.4rem;padding:0.28rem 0.85rem;font-size:0.76rem;font-weight:700;color:var(--blue-deep);">
                {{ $s1Time }}
              </div>
              <p style="font-weight:700;font-size:0.9rem;margin-top:0.75rem;">{{ $s1Venue }}</p>
              <p style="font-size:0.8rem;color:var(--slate-soft);margin-top:0.2rem;">
                {{ $s1Address }}
              </p>
            </div>

            @if(!empty($s1Maps))
            <a
              href="{{ $s1Maps }}"
              target="_blank"
              rel="noopener noreferrer"
              class="btn-clay-primary"
              style="margin-top:1.25rem;padding:0.72rem 1.35rem;font-size:0.85rem;"
            >
              <span>📍 Buka Google Maps</span>
            </a>
            @endif
          </div>

          <!-- Sesi 2 -->
          @if($s2Enabled)
          <div class="clay-card" style="padding:clamp(1.4rem, 4vw, 2rem);text-align:center;display:flex;flex-direction:column;align-items:center;justify-content:space-between;">
            <div>
              <div class="anim-float-rev" style="width:88px;height:88px;margin:0 auto 0.5rem;">
                <svg viewBox="0 0 120 120" filter="url(#clayShadow)">
                  <ellipse cx="60" cy="102" rx="46" ry="8" fill="#ffffff" />
                  <rect x="24" y="72" width="72" height="28" rx="10" fill="url(#skyMistClayGrad)" />
                  <rect x="34" y="48" width="52" height="26" rx="9" fill="url(#powderBlueClayGrad)" />
                  <rect x="44" y="28" width="32" height="22" rx="8" fill="url(#periwinkleClayGrad)" />
                  <path d="M60 24 C60 24 51 17 51 11 C51 8 54 6 57 6 C59 6 60 7 60 9 C60 7 61 6 63 6 C66 6 69 8 69 11 C69 17 60 24 60 24 Z" fill="url(#ceruleanClayGrad)" />
                </svg>
              </div>
              <h3 class="font-serif" style="font-size:1.45rem;font-weight:700;">{{ $s2Title }}</h3>
              <p style="font-weight:700;font-size:0.9rem;margin-top:0.65rem;">{{ $s2DateFormatted }}</p>
              <div class="clay-pill font-cute" style="margin-top:0.4rem;padding:0.28rem 0.85rem;font-size:0.76rem;font-weight:700;color:var(--blue-deep);">
                {{ $s2Time }}
              </div>
              <p style="font-weight:700;font-size:0.9rem;margin-top:0.75rem;">{{ $s2Venue }}</p>
              <p style="font-size:0.8rem;color:var(--slate-soft);margin-top:0.2rem;">
                {{ $s2Address }}
              </p>
            </div>

            @if(!empty($s2Maps))
            <a
              href="{{ $s2Maps }}"
              target="_blank"
              rel="noopener noreferrer"
              class="btn-clay-primary"
              style="margin-top:1.25rem;padding:0.72rem 1.35rem;font-size:0.85rem;"
            >
              <span>📍 Buka Google Maps</span>
            </a>
            @endif
          </div>
          @endif
        </div>
      </div>
    </section>

    <!-- ======================================================================
         6. LOVE STORY TIMELINE
         ====================================================================== -->
    <section class="section-block reveal-on-scroll">
      <div class="container-narrow">
        <div class="section-header">
          <div class="clay-pill font-cute" style="padding:0.3rem 0.95rem;font-size:0.74rem;font-weight:600;text-transform:uppercase;color:var(--blue-deep);">
            Our Journey
          </div>
          <h2 class="section-title">{{ count($rundownItems) > 0 ? 'Susunan Acara' : 'Kisah Cinta Kami' }}</h2>
        </div>

        <div class="timeline-list">
          @if(count($rundownItems) > 0)
            @foreach($rundownItems as $rItem)
            <div class="timeline-item">
              <div class="clay-card timeline-badge">
                <svg style="width:32px;height:32px;" viewBox="0 0 64 64">
                  <rect x="10" y="18" width="44" height="30" rx="8" fill="url(#skyMistClayGrad)" />
                  <path d="M12 20 L32 35 L52 20" fill="none" stroke="#ffffff" stroke-width="3" stroke-linecap="round" />
                  <circle cx="32" cy="34" r="6" fill="url(#ceruleanClayGrad)" />
                </svg>
              </div>
              <div class="clay-card" style="padding:1.15rem 1.3rem;flex:1;">
                @if(!empty($rItem['time']))
                  <span class="font-cute" style="font-size:0.75rem;font-weight:700;color:var(--blue-deep);">{{ $rItem['time'] }}</span>
                @endif
                <h3 class="font-serif" style="font-size:1.12rem;font-weight:700;margin-top:0.15rem;">{{ $rItem['title'] }}</h3>
                @if(!empty($rItem['description']))
                  <p style="font-size:0.84rem;color:var(--slate-soft);margin-top:0.3rem;">{{ $rItem['description'] }}</p>
                @endif
              </div>
            </div>
            @endforeach
          @else
            <div class="timeline-item">
              <div class="clay-card timeline-badge">
                <svg style="width:32px;height:32px;" viewBox="0 0 64 64">
                  <rect x="14" y="24" width="30" height="26" rx="8" fill="url(#powderBlueClayGrad)" />
                  <path d="M44 29 H50 A6 6 0 0 1 50 41 H44" fill="none" stroke="url(#ceruleanClayGrad)" stroke-width="5" />
                  <path d="M24 14 Q22 18 25 20 M33 12 Q31 16 34 18" fill="none" stroke="#3b82f6" stroke-width="2.5" stroke-linecap="round" />
                </svg>
              </div>
              <div class="clay-card" style="padding:1.15rem 1.3rem;flex:1;">
                <span class="font-cute" style="font-size:0.75rem;font-weight:700;color:var(--blue-deep);">Oktober 2022</span>
                <h3 class="font-serif" style="font-size:1.12rem;font-weight:700;margin-top:0.15rem;">Pertemuan Pertama</h3>
                <p style="font-size:0.84rem;color:var(--slate-soft);margin-top:0.3rem;">
                  Berawal dari secangkir kopi di sudut kota Surabaya saat proyek kreatif bersama, obrolan sederhana berubah menjadi tawa yang tak pernah habis.
                </p>
              </div>
            </div>

            <div class="timeline-item">
              <div class="clay-card timeline-badge">
                <svg style="width:32px;height:32px;" viewBox="0 0 64 64">
                  <rect x="10" y="18" width="44" height="30" rx="8" fill="url(#skyMistClayGrad)" />
                  <path d="M12 20 L32 35 L52 20" fill="none" stroke="#ffffff" stroke-width="3" stroke-linecap="round" />
                  <circle cx="32" cy="34" r="6" fill="url(#ceruleanClayGrad)" />
                </svg>
              </div>
              <div class="clay-card" style="padding:1.15rem 1.3rem;flex:1;">
                <span class="font-cute" style="font-size:0.75rem;font-weight:700;color:var(--blue-deep);">Juni 2024</span>
                <h3 class="font-serif" style="font-size:1.12rem;font-weight:700;margin-top:0.15rem;">Menjalin Komitmen</h3>
                <p style="font-size:0.84rem;color:var(--slate-soft);margin-top:0.3rem;">
                  Setelah saling mengenal impian masing-masing, kami sepakat melangkah bersama dan saling menguatkan dalam suka maupun duka.
                </p>
              </div>
            </div>

            <div class="timeline-item">
              <div class="clay-card timeline-badge">
                <svg style="width:32px;height:32px;" viewBox="0 0 64 64">
                  <rect x="14" y="28" width="36" height="24" rx="8" fill="url(#ceruleanClayGrad)" />
                  <path d="M14 28 C14 14 50 14 50 28 Z" fill="url(#periwinkleClayGrad)" />
                  <circle cx="32" cy="28" r="7" fill="none" stroke="url(#warmGoldGrad)" stroke-width="3.5" />
                </svg>
              </div>
              <div class="clay-card" style="padding:1.15rem 1.3rem;flex:1;">
                <span class="font-cute" style="font-size:0.75rem;font-weight:700;color:var(--blue-deep);">Mei 2026</span>
                <h3 class="font-serif" style="font-size:1.12rem;font-weight:700;margin-top:0.15rem;">Momen Lamaran</h3>
                <p style="font-size:0.84rem;color:var(--slate-soft);margin-top:0.3rem;">
                  Di hadapan keluarga besar tercinta, {{ $groomNick }} meminang {{ $brideNick }} dengan penuh kehangatan untuk menyempurnakan separuh agama.
                </p>
              </div>
            </div>
          @endif
        </div>
      </div>
    </section>

    <!-- ======================================================================
         6B. GALERI FOTO (JIKA ADA FOTO GALERI DIUNGGAH)
         ====================================================================== -->
    @if(count($galleryImages) > 0)
    <section class="section-block reveal-on-scroll">
      <div class="container-narrow">
        <div class="section-header">
          <div class="clay-pill font-cute" style="padding:0.3rem 0.95rem;font-size:0.74rem;font-weight:600;text-transform:uppercase;color:var(--blue-deep);">
            Our Moments
          </div>
          <h2 class="section-title">Galeri Foto</h2>
        </div>

        <div class="gallery-grid">
          @foreach($galleryImages as $photo)
          <div class="clay-card gallery-item" onclick="window.openCupidLightbox && window.openCupidLightbox('{{ asset('storage/' . $photo) }}')">
            <img src="{{ asset('storage/' . $photo) }}" alt="Foto Galeri" loading="lazy" decoding="async" />
          </div>
          @endforeach
        </div>
      </div>
    </section>
    @endif

    <!-- ======================================================================
         7. AMPLOP DIGITAL (WEDDING GIFT)
         ====================================================================== -->
    @if(count($giftAccounts) > 0)
    <section class="section-block reveal-on-scroll">
      <div class="container-narrow">
        <div class="section-header">
          <div class="clay-pill font-cute" style="padding:0.3rem 0.95rem;font-size:0.74rem;font-weight:600;text-transform:uppercase;color:var(--blue-deep);">
            Wedding Gift
          </div>
          <h2 class="section-title">Amplop Digital</h2>
          <p style="font-size:0.85rem;color:var(--slate-soft);max-width:460px;margin:0.4rem auto 0;">
            Doa restu Anda merupakan karunia terindah bagi kami. Namun jika memberi adalah ungkapan tanda kasih, Anda dapat mengirimkannya secara digital:
          </p>
        </div>

        <div class="{{ count($giftAccounts) > 1 ? 'grid-2-col' : '' }}">
          @foreach($giftAccounts as $idx => $acc)
          <div class="clay-card" style="padding:1.4rem;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.85rem;">
              <span class="font-cute" style="font-weight:700;font-size:1.1rem;">{{ $acc['bank'] }}</span>
              <span style="width:36px;height:24px;border-radius:6px;background:{{ $idx % 2 === 0 ? 'linear-gradient(135deg,#93c5fd,#2563eb)' : 'linear-gradient(135deg,#fde68a,#d4a373)' }};border:1px solid #ffffff;"></span>
            </div>
            <p style="font-size:0.74rem;color:var(--slate-soft);">Nomor Rekening</p>
            <p class="font-cute" style="font-size:1.25rem;font-weight:700;letter-spacing:0.06em;margin-top:0.1rem;word-break:break-all;">{{ $acc['account_number'] }}</p>
            @if(!empty($acc['account_name']))
              <p style="font-size:0.8rem;font-weight:600;color:var(--slate-soft);margin-top:0.2rem;">a.n. {{ $acc['account_name'] }}</p>
            @endif

            <button
              type="button"
              data-copy="{{ $acc['account_number'] }}"
              data-label="{{ $acc['bank'] }}"
              class="btn-copy-gift clay-pill font-cute"
              style="width:100%;margin-top:1rem;padding:0.65rem 1rem;font-size:0.8rem;font-weight:700;cursor:pointer;"
            >
              <span>📋 Salin Nomor Rekening</span>
            </button>
          </div>
          @endforeach
        </div>
      </div>
    </section>
    @endif

    <!-- ======================================================================
         8. RSVP & BUKU TAMU INTERAKTIF
         ====================================================================== -->
    <section class="section-block reveal-on-scroll">
      <div class="container-narrow">
        <div class="section-header">
          <div class="clay-pill font-cute" style="padding:0.3rem 0.95rem;font-size:0.74rem;font-weight:600;text-transform:uppercase;color:var(--blue-deep);">
            RSVP &amp; Wishes
          </div>
          <h2 class="section-title">Konfirmasi Kehadiran &amp; Doa</h2>
        </div>

        <form id="rsvp-form" class="clay-card" style="padding:clamp(1.3rem, 4vw, 2rem);display:flex;flex-direction:column;gap:0.95rem;">
          <div class="grid-2-col">
            <div class="form-group">
              <label for="rsvp-name" class="form-label">Nama Lengkap</label>
              <input id="rsvp-name" type="text" required placeholder="Masukkan nama Anda..." class="form-control" value="{{ $guestNameVal !== 'Tamu Spesial' ? $guestNameVal : '' }}" />
            </div>

            <div class="form-group">
              <label for="rsvp-attendance" class="form-label">Konfirmasi Kehadiran</label>
              <select id="rsvp-attendance" class="form-control">
                <option value="Hadir">💙 InsyaAllah Hadir</option>
                <option value="Tidak Hadir">🕊️ Maaf, Berhalangan Hadir</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label for="rsvp-guests" class="form-label">Jumlah Tamu</label>
            <select id="rsvp-guests" class="form-control">
              <option value="1 Orang">1 Orang</option>
              <option value="2 Orang" selected>2 Orang</option>
              <option value="Keluarga (3-4 Orang)">Keluarga (3-4 Orang)</option>
            </select>
          </div>

          <div class="form-group">
            <label for="rsvp-message" class="form-label">Ucapan &amp; Doa Restu</label>
            <textarea id="rsvp-message" rows="3" required placeholder="Tuliskan ucapan manis dan doa terbaik..." class="form-control"></textarea>
          </div>

          <button type="submit" id="btn-submit-rsvp" class="btn-clay-primary" style="width:100%;padding:0.85rem 1.4rem;font-size:0.95rem;">
            <span>💌 Kirim Konfirmasi &amp; Ucapan</span>
          </button>
        </form>

        <!-- Daftar Ucapan Tamu -->
        <div id="wishes-list" style="margin-top:1.5rem;display:flex;flex-direction:column;gap:0.85rem;max-height:420px;overflow-y:auto;padding-right:2px;"></div>
      </div>
    </section>

    <footer style="text-align:center;padding:2rem 1rem 1rem;">
      <p class="script-title" style="font-size:2.3rem;">{!! $coupleDisplay !!}</p>
      <p style="font-size:0.8rem;color:var(--slate-soft);margin-top:0.2rem;">Terima kasih atas doa dan kehadiran Anda 💙</p>
    </footer>
  </main>

  <!-- ========================================================================
       MODAL UBAH NAMA TAMU (?to=) & LIGHTBOX GALERI & TOAST NOTIFICATION
       ======================================================================== -->
  @if($isPreview)
  <div id="guest-modal" role="dialog" aria-modal="true" aria-labelledby="modal-guest-title">
    <div class="clay-card" style="width:100%;max-width:360px;padding:1.5rem;text-align:center;">
      <h3 id="modal-guest-title" class="font-cute" style="font-size:1.1rem;font-weight:700;">Simulasi Nama Tamu (?to=)</h3>
      <p style="font-size:0.8rem;color:var(--slate-soft);margin-top:0.3rem;">Masukkan nama tamu untuk melihat tampilan personal pada sampul:</p>
      <input id="input-custom-guest" type="text" placeholder="Contoh: Bpk. Andi & Keluarga" class="form-control" style="margin-top:0.9rem;" />
      <div style="display:flex;gap:0.6rem;margin-top:1rem;">
        <button id="btn-close-guest-modal" type="button" class="clay-pill" style="flex:1;padding:0.6rem;font-size:0.8rem;font-weight:700;cursor:pointer;">
          Batal
        </button>
        <button id="btn-apply-guest-name" type="button" class="btn-clay-primary" style="flex:1;padding:0.6rem;font-size:0.8rem;">
          Terapkan
        </button>
      </div>
    </div>
  </div>
  @endif

  <div id="lightbox-modal" role="dialog" aria-modal="true" aria-label="Pratinjau Foto" onclick="window.closeCupidLightbox && window.closeCupidLightbox()">
    <div class="clay-card" style="position:relative;max-width:92vw;max-height:88vh;padding:0.75rem;" onclick="event.stopPropagation()">
      <button type="button" onclick="window.closeCupidLightbox && window.closeCupidLightbox()" class="clay-pill" style="position:absolute;top:0.85rem;right:0.85rem;width:36px;height:36px;font-weight:700;cursor:pointer;z-index:2;" aria-label="Tutup">✕</button>
      <img id="lightbox-img" src="" alt="Preview" style="max-width:100%;max-height:80vh;border-radius:18px;display:block;object-fit:contain;" />
    </div>
  </div>

  <div id="cute-toast" class="clay-pill" role="status" aria-live="polite">
    <span id="toast-icon">💙</span>
    <span id="toast-text">Berhasil!</span>
  </div>

  <!-- ========================================================================
       JAVASCRIPT: 100% SELF-CONTAINED, SAFE DOM INITIALIZATION, 60FPS CUPID 3D
       ======================================================================== -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      'use strict';

      const CONFIG = {
        isPreview: @json($isPreview),
        wishUrl: @json(!$isPreview && !empty($invitation->slug) ? route('invitation.wish', $invitation->slug) : ''),
        csrfToken: @json(csrf_token()),
        targetIso: @json($targetIso),
        musicMode: @json($musicMode),
        musicUrl: @json($musicUrl),
        eventTitle: @json($invitation->title ?? 'The Wedding Celebration'),
        s1Date: @json(str_replace('-', '', $s1Date)),
        s1Venue: @json($s1Venue),
        s1Address: @json($s1Address),
        initialWishes: @json($wishesData ?? []),
        guestNameInitial: @json($guestName ?? '')
      };

      /* ----------------------------------------------------------------------
         1. SAFE TOAST NOTIFICATION & LIGHTBOX
         ---------------------------------------------------------------------- */
      const toastEl = document.getElementById('cute-toast');
      const toastIconEl = document.getElementById('toast-icon');
      const toastTextEl = document.getElementById('toast-text');
      let toastTimer = null;

      function showToast(message, icon = '💙') {
        if (!toastEl || !toastIconEl || !toastTextEl) return;
        toastIconEl.textContent = icon;
        toastTextEl.textContent = message;
        toastEl.classList.add('is-shown');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => {
          toastEl.classList.remove('is-shown');
        }, 2800);
      }

      const lightboxModal = document.getElementById('lightbox-modal');
      const lightboxImg = document.getElementById('lightbox-img');
      window.openCupidLightbox = (src) => {
        if (!lightboxModal || !lightboxImg || !src) return;
        lightboxImg.src = src;
        lightboxModal.classList.add('is-open');
      };
      window.closeCupidLightbox = () => {
        if (!lightboxModal) return;
        lightboxModal.classList.remove('is-open');
      };

      /* ----------------------------------------------------------------------
         2. ROMANTIC MUSIC CONTROLLER (CUSTOM AUDIO OR WEB AUDIO MUSIC BOX)
         ---------------------------------------------------------------------- */
      class RomanticMusicBox {
        constructor(mode, url) {
          this.mode = mode || 'ambient';
          this.url = url || '';
          this.audioElem = null;
          if (this.mode === 'custom' && this.url) {
            this.audioElem = new Audio(this.url);
            this.audioElem.loop = true;
            this.audioElem.preload = 'none';
          }
          this.ctx = null;
          this.isPlaying = false;
          this.timer = null;
          this.step = 0;
          this.notes = [
            523.25, 659.25, 783.99, 659.25,
            493.88, 587.33, 783.99, 587.33,
            440.00, 523.25, 659.25, 523.25,
            392.00, 493.88, 659.25, 493.88,
            349.23, 440.00, 587.33, 440.00,
            329.63, 392.00, 523.25, 392.00,
            349.23, 440.00, 587.33, 659.25,
            587.33, 493.88, 523.25, 0
          ];
        }

        ensureContext() {
          if (!this.ctx) {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (AudioCtx) this.ctx = new AudioCtx();
          }
          if (this.ctx && this.ctx.state === 'suspended') {
            this.ctx.resume().catch(() => {});
          }
        }

        playNote(freq) {
          if (!freq || !this.ctx) return;
          const now = this.ctx.currentTime;
          const osc = this.ctx.createOscillator();
          const shimmer = this.ctx.createOscillator();
          const gain = this.ctx.createGain();
          const shimmerGain = this.ctx.createGain();

          osc.type = 'sine';
          osc.frequency.setValueAtTime(freq, now);

          shimmer.type = 'triangle';
          shimmer.frequency.setValueAtTime(freq * 2, now);

          shimmerGain.gain.setValueAtTime(0.012, now);
          shimmerGain.gain.exponentialRampToValueAtTime(0.0001, now + 0.75);

          gain.gain.setValueAtTime(0.0001, now);
          gain.gain.linearRampToValueAtTime(0.075, now + 0.03);
          gain.gain.exponentialRampToValueAtTime(0.0001, now + 1.4);

          osc.connect(gain);
          shimmer.connect(shimmerGain);
          shimmerGain.connect(gain);
          gain.connect(this.ctx.destination);

          osc.start(now);
          shimmer.start(now);
          osc.stop(now + 1.45);
          shimmer.stop(now + 1.45);
        }

        start() {
          if (this.mode === 'none') return;
          if (this.mode === 'custom' && this.audioElem) {
            this.audioElem.volume = 0.5;
            this.audioElem.play().then(() => {
              this.isPlaying = true;
              this.syncDiscUI();
            }).catch(() => {});
            return;
          }
          try {
            this.ensureContext();
            if (!this.ctx || this.isPlaying) return;
            this.isPlaying = true;
            this.syncDiscUI();

            this.playNote(this.notes[this.step % this.notes.length]);
            this.step++;
            this.timer = setInterval(() => {
              if (!this.isPlaying) return;
              this.playNote(this.notes[this.step % this.notes.length]);
              this.step++;
            }, 520);
          } catch (_) {}
        }

        pause() {
          this.isPlaying = false;
          if (this.audioElem) this.audioElem.pause();
          clearInterval(this.timer);
          this.syncDiscUI();
        }

        toggle() {
          if (this.mode === 'none') return;
          if (this.isPlaying) {
            this.pause();
            showToast('Musik dijeda', '🔇');
          } else {
            this.start();
            showToast(this.audioElem ? 'Memutar musik latar' : 'Memutar melodi kotak musik romantis', '🎵');
          }
        }

        syncDiscUI() {
          const disc = document.getElementById('vinyl-disc');
          if (!disc) return;
          disc.classList.toggle('is-playing', this.isPlaying);
        }
      }

      const musicBox = new RomanticMusicBox(CONFIG.musicMode, CONFIG.musicUrl);

      /* ----------------------------------------------------------------------
         3. 3D CLAY CUPID & BOUNCING SERENE BLUE HEARTS ENGINE (RETINA CANVAS)
         ---------------------------------------------------------------------- */
      function initCupid3DBackground() {
        const canvas = document.getElementById('cupid-canvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        if (!ctx) return;

        let width = window.innerWidth;
        let height = window.innerHeight;
        let dpr = Math.min(window.devicePixelRatio || 1, 2);

        function resizeCanvas() {
          width = window.innerWidth;
          height = window.innerHeight;
          dpr = Math.min(window.devicePixelRatio || 1, 2);
          canvas.width = Math.floor(width * dpr);
          canvas.height = Math.floor(height * dpr);
          ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        }

        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        function drawClayHeart(x, y, size, colorMain, colorLight, colorShadow, rot, scaleX, scaleY, isHappy, happyTimer) {
          ctx.save();
          ctx.translate(x, y);
          ctx.rotate(rot);
          ctx.scale(scaleX, scaleY);

          ctx.shadowColor = colorShadow;
          ctx.shadowBlur = size * 0.45;
          ctx.shadowOffsetY = size * 0.22;

          const s = size;
          ctx.beginPath();
          ctx.moveTo(0, s * 0.35);
          ctx.bezierCurveTo(0, 0, -s * 0.55, 0, -s * 0.55, s * 0.35);
          ctx.bezierCurveTo(-s * 0.55, s * 0.72, 0, s * 1.02, 0, s * 1.22);
          ctx.bezierCurveTo(0, s * 1.02, s * 0.55, s * 0.72, s * 0.55, s * 0.35);
          ctx.bezierCurveTo(s * 0.55, 0, 0, 0, 0, s * 0.35);
          ctx.closePath();

          const grad = ctx.createRadialGradient(-s * 0.18, s * 0.22, s * 0.05, 0, s * 0.55, s * 0.9);
          grad.addColorStop(0, colorLight);
          grad.addColorStop(0.55, colorMain);
          grad.addColorStop(1, colorShadow);
          ctx.fillStyle = grad;
          ctx.fill();

          ctx.shadowColor = 'transparent';

          ctx.save();
          ctx.translate(-s * 0.24, s * 0.26);
          ctx.rotate(-0.45);
          ctx.fillStyle = 'rgba(255, 255, 255, 0.76)';
          ctx.beginPath();
          ctx.ellipse(0, 0, s * 0.13, s * 0.06, 0, 0, Math.PI * 2);
          ctx.fill();
          ctx.restore();

          if (isHappy && happyTimer > 0) {
            const alpha = Math.min(1, happyTimer / 30);
            ctx.globalAlpha = alpha;
            ctx.strokeStyle = '#1e3a5f';
            ctx.lineWidth = Math.max(1.6, size * 0.075);
            ctx.lineCap = 'round';

            ctx.beginPath();
            ctx.arc(-s * 0.17, s * 0.52, s * 0.075, Math.PI * 1.15, Math.PI * 1.85);
            ctx.stroke();

            ctx.beginPath();
            ctx.arc(s * 0.17, s * 0.52, s * 0.075, Math.PI * 1.15, Math.PI * 1.85);
            ctx.stroke();

            ctx.fillStyle = 'rgba(251, 113, 133, 0.45)';
            ctx.beginPath();
            ctx.ellipse(-s * 0.25, s * 0.61, s * 0.07, s * 0.04, 0, 0, Math.PI * 2);
            ctx.ellipse(s * 0.25, s * 0.61, s * 0.07, s * 0.04, 0, 0, Math.PI * 2);
            ctx.fill();

            ctx.beginPath();
            ctx.arc(0, s * 0.58, s * 0.09, 0.15 * Math.PI, 0.85 * Math.PI);
            ctx.stroke();
          }

          ctx.restore();
        }

        function drawClayCupid(cupid) {
          ctx.save();
          ctx.translate(cupid.x, cupid.y);
          ctx.scale(cupid.facing * cupid.scale, cupid.scale);
          ctx.rotate(cupid.tilt);

          const wingAngle = Math.sin(cupid.wingPhase) * 0.38;
          ctx.save();
          ctx.translate(-12, -4);
          ctx.rotate(-0.35 + wingAngle);
          const wingGrad = ctx.createLinearGradient(-28, -16, 4, 14);
          wingGrad.addColorStop(0, '#ffffff');
          wingGrad.addColorStop(1, '#e0f2fe');
          ctx.fillStyle = wingGrad;
          ctx.shadowColor = 'rgba(37, 99, 235, 0.18)';
          ctx.shadowBlur = 8;
          ctx.beginPath();
          ctx.ellipse(-14, -4, 18, 10, -0.3, 0, Math.PI * 2);
          ctx.ellipse(-10, 5, 13, 7, -0.1, 0, Math.PI * 2);
          ctx.fill();
          ctx.restore();

          ctx.save();
          ctx.translate(0, -34 + Math.sin(cupid.wingPhase * 0.5) * 2);
          ctx.strokeStyle = '#e6b86a';
          ctx.lineWidth = 3.2;
          ctx.shadowColor = 'rgba(230, 184, 106, 0.72)';
          ctx.shadowBlur = 8;
          ctx.beginPath();
          ctx.ellipse(0, 0, 14, 4.5, 0, 0, Math.PI * 2);
          ctx.stroke();
          ctx.restore();

          const bodyGrad = ctx.createRadialGradient(-4, 6, 2, 0, 10, 20);
          bodyGrad.addColorStop(0, '#fff4ed');
          bodyGrad.addColorStop(1, '#f3bc9c');
          ctx.fillStyle = bodyGrad;
          ctx.beginPath();
          ctx.ellipse(0, 12, 13, 15, 0, 0, Math.PI * 2);
          ctx.fill();

          ctx.fillStyle = '#ffffff';
          ctx.beginPath();
          ctx.ellipse(0, 19, 13.5, 8.5, 0, 0, Math.PI);
          ctx.fill();

          ctx.strokeStyle = '#60a5fa';
          ctx.lineWidth = 4;
          ctx.beginPath();
          ctx.moveTo(-9, 3);
          ctx.lineTo(10, 17);
          ctx.stroke();

          const headGrad = ctx.createRadialGradient(-6, -15, 3, 0, -10, 22);
          headGrad.addColorStop(0, '#fff6f0');
          headGrad.addColorStop(0.65, '#ffdecb');
          headGrad.addColorStop(1, '#f2b796');
          ctx.fillStyle = headGrad;
          ctx.beginPath();
          ctx.arc(0, -10, 17, 0, Math.PI * 2);
          ctx.fill();

          const curls = [
            { x: -12, y: -21, r: 7.5 },
            { x: -3, y: -25, r: 8.5 },
            { x: 7, y: -23, r: 7.5 },
            { x: -15, y: -12, r: 6.5 },
            { x: 13, y: -16, r: 6 }
          ];
          curls.forEach((c) => {
            const cGrad = ctx.createRadialGradient(c.x - 2, c.y - 2, 1, c.x, c.y, c.r);
            cGrad.addColorStop(0, '#fff4cc');
            cGrad.addColorStop(0.6, '#e6b86a');
            cGrad.addColorStop(1, '#c48a3b');
            ctx.fillStyle = cGrad;
            ctx.beginPath();
            ctx.arc(c.x, c.y, c.r, 0, Math.PI * 2);
            ctx.fill();
          });

          ctx.fillStyle = '#1e3a5f';
          ctx.beginPath();
          ctx.arc(6, -10, 2.5, 0, Math.PI * 2);
          ctx.fill();

          ctx.fillStyle = '#ffffff';
          ctx.beginPath();
          ctx.arc(6.8, -10.8, 0.9, 0, Math.PI * 2);
          ctx.fill();

          ctx.fillStyle = 'rgba(251, 113, 133, 0.52)';
          ctx.beginPath();
          ctx.ellipse(9, -5.5, 4, 2.3, 0, 0, Math.PI * 2);
          ctx.fill();

          ctx.strokeStyle = '#64748b';
          ctx.lineWidth = 1.8;
          ctx.lineCap = 'round';
          ctx.beginPath();
          ctx.arc(4, -5.5, 3.2, 0.1 * Math.PI, 0.85 * Math.PI);
          ctx.stroke();

          const pullOffset = cupid.drawTension * 11;
          ctx.save();
          ctx.translate(16, 6);

          ctx.strokeStyle = '#d4a373';
          ctx.lineWidth = 3.5;
          ctx.lineCap = 'round';
          ctx.beginPath();
          ctx.moveTo(-2 - pullOffset * 0.25, -18);
          ctx.quadraticCurveTo(12, 0, -2 - pullOffset * 0.25, 18);
          ctx.stroke();

          ctx.strokeStyle = '#e0f2fe';
          ctx.lineWidth = 1.4;
          ctx.beginPath();
          ctx.moveTo(-2 - pullOffset * 0.25, -18);
          ctx.lineTo(-4 - pullOffset, 0);
          ctx.lineTo(-2 - pullOffset * 0.25, 18);
          ctx.stroke();

          if (cupid.drawTension > 0.05) {
            ctx.strokeStyle = '#e6b86a';
            ctx.lineWidth = 2.2;
            ctx.beginPath();
            ctx.moveTo(-4 - pullOffset, 0);
            ctx.lineTo(18 - pullOffset, 0);
            ctx.stroke();

            ctx.fillStyle = '#3b82f6';
            ctx.beginPath();
            ctx.moveTo(22 - pullOffset, 0);
            ctx.lineTo(15 - pullOffset, -4);
            ctx.lineTo(15 - pullOffset, 4);
            ctx.closePath();
            ctx.fill();
          }

          ctx.restore();
          ctx.restore();
        }

        const heartPalettes = [
          { main: '#60a5fa', light: '#eff6ff', shadow: '#2563eb' },
          { main: '#93c5fd', light: '#f0f9ff', shadow: '#3b82f6' },
          { main: '#7dd3fc', light: '#f0f9ff', shadow: '#0284c7' },
          { main: '#a5b4fc', light: '#eef2ff', shadow: '#6366f1' },
          { main: '#f3d5dc', light: '#fff8fa', shadow: '#cbd5e1' }
        ];

        const hearts = [];
        const heartCount = width < 600 ? 12 : 18;

        for (let i = 0; i < heartCount; i++) {
          const pal = heartPalettes[i % heartPalettes.length];
          hearts.push({
            x: Math.random() * width,
            y: Math.random() * height,
            size: (width < 600 ? 13 : 16) + Math.random() * 14,
            vy: -(0.22 + Math.random() * 0.35),
            swayAmp: 0.35 + Math.random() * 0.55,
            swayFreq: 0.012 + Math.random() * 0.015,
            phase: Math.random() * Math.PI * 2,
            rot: (Math.random() - 0.5) * 0.35,
            scaleX: 1,
            scaleY: 1,
            elasticVel: 0,
            isHappy: false,
            happyTimer: 0,
            pal
          });
        }

        const cupid = {
          x: width * 0.25,
          y: height * 0.28,
          t: 0,
          facing: 1,
          tilt: 0,
          scale: width < 600 ? 0.85 : 1.05,
          wingPhase: 0,
          drawTension: 0,
          shootCooldown: 90,
          isAiming: false,
          aimTarget: null
        };

        const arrows = [];
        const sparkles = [];

        function spawnSparkles(x, y, count = 14) {
          const colors = ['#60a5fa', '#e6b86a', '#ffffff', '#93c5fd', '#a5b4fc'];
          for (let i = 0; i < count; i++) {
            const ang = (Math.PI * 2 * i) / count + Math.random() * 0.4;
            const spd = 1.4 + Math.random() * 3.2;
            sparkles.push({
              x,
              y,
              vx: Math.cos(ang) * spd,
              vy: Math.sin(ang) * spd,
              size: 2.5 + Math.random() * 3.5,
              alpha: 1,
              color: colors[i % colors.length]
            });
          }
        }

        function fireArrowAt(targetX, targetY, targetHeart = null) {
          const dx = targetX - cupid.x;
          const dy = targetY - cupid.y;
          const dist = Math.hypot(dx, dy) || 1;
          const speed = 7.5;

          cupid.facing = dx >= 0 ? 1 : -1;
          arrows.push({
            x: cupid.x + cupid.facing * 18,
            y: cupid.y + 4,
            vx: (dx / dist) * speed,
            vy: (dy / dist) * speed - 1.4,
            gravity: 0.045,
            trail: [],
            targetHeart,
            targetPoint: { x: targetX, y: targetY },
            life: 140
          });
        }

        document.addEventListener('pointerdown', (e) => {
          const interactive = e.target.closest('button, a, input, select, textarea, .clay-card');
          if (interactive) return;
          cupid.isAiming = true;
          cupid.drawTension = 1;
          cupid.shootCooldown = 14;
          cupid.aimTarget = { x: e.clientX, y: e.clientY, heart: null };
        });

        function animateCupidScene() {
          requestAnimationFrame(animateCupidScene);
          ctx.clearRect(0, 0, width, height);

          for (let i = 0; i < hearts.length; i++) {
            const h = hearts[i];
            h.phase += h.swayFreq;
            h.y += h.vy;
            h.x += Math.sin(h.phase) * h.swayAmp;

            const springForce = (1 - h.scaleX) * 0.16;
            h.elasticVel = (h.elasticVel + springForce) * 0.82;
            h.scaleX += h.elasticVel;
            h.scaleY = 2 - h.scaleX;

            if (h.happyTimer > 0) {
              h.happyTimer -= 1;
              if (h.happyTimer <= 0) h.isHappy = false;
            }

            if (h.y < -40) {
              h.y = height + 40;
              h.x = Math.random() * width;
            }
            if (h.x < -40) h.x = width + 40;
            if (h.x > width + 40) h.x = -40;

            drawClayHeart(
              h.x, h.y, h.size, h.pal.main, h.pal.light, h.pal.shadow,
              h.rot + Math.sin(h.phase) * 0.08, h.scaleX, h.scaleY, h.isHappy, h.happyTimer
            );
          }

          cupid.t += 0.0085;
          cupid.wingPhase += 0.24;
          cupid.scale = width < 600 ? 0.84 : 1.04;

          const marginX = Math.min(95, width * 0.18);
          const centerX = width * 0.5;
          const ampX = Math.max(60, width * 0.5 - marginX);
          const targetX = centerX + Math.sin(cupid.t) * ampX * 0.82 + Math.cos(cupid.t * 0.43) * ampX * 0.18;
          const targetY = height * 0.24 + Math.sin(cupid.t * 1.7) * (height * 0.11) + Math.cos(cupid.t * 0.7) * 24;

          const prevX = cupid.x;
          cupid.x += (targetX - cupid.x) * 0.03;
          cupid.y += (targetY - cupid.y) * 0.03;

          const vx = cupid.x - prevX;
          if (!cupid.isAiming && Math.abs(vx) > 0.12) {
            cupid.facing = vx >= 0 ? 1 : -1;
          }
          cupid.tilt = Math.max(-0.22, Math.min(0.22, vx * 0.05));

          cupid.shootCooldown -= 1;
          if (cupid.shootCooldown <= 32 && cupid.shootCooldown > 0) {
            cupid.isAiming = true;
            cupid.drawTension = Math.min(1, cupid.drawTension + 0.06);

            if (!cupid.aimTarget) {
              const visibleHearts = hearts.filter((h) => h.y > 60 && h.y < height - 60);
              const chosen = visibleHearts.length > 0
                ? visibleHearts[Math.floor(Math.random() * visibleHearts.length)]
                : { x: width * 0.5, y: height * 0.5 };
              cupid.aimTarget = { x: chosen.x, y: chosen.y, heart: chosen.pal ? chosen : null };
            }
            if (cupid.aimTarget) {
              cupid.facing = cupid.aimTarget.x >= cupid.x ? 1 : -1;
            }
          } else if (cupid.shootCooldown <= 0) {
            const destX = cupid.aimTarget ? cupid.aimTarget.x : width * 0.5;
            const destY = cupid.aimTarget ? cupid.aimTarget.y : height * 0.45;
            const destHeart = cupid.aimTarget ? cupid.aimTarget.heart : null;

            fireArrowAt(destX, destY, destHeart);
            cupid.drawTension = 0;
            cupid.isAiming = false;
            cupid.aimTarget = null;
            cupid.shootCooldown = 135 + Math.floor(Math.random() * 110);
          }

          drawClayCupid(cupid);

          for (let i = arrows.length - 1; i >= 0; i--) {
            const a = arrows[i];
            a.trail.push({ x: a.x, y: a.y });
            if (a.trail.length > 14) a.trail.shift();

            a.x += a.vx;
            a.y += a.vy;
            a.vy += a.gravity;
            a.life -= 1;

            for (let j = 0; j < a.trail.length; j++) {
              const pt = a.trail[j];
              const ratio = (j + 1) / a.trail.length;
              ctx.fillStyle = j % 2 === 0
                ? `rgba(230, 184, 106, ${ratio * 0.58})`
                : `rgba(96, 165, 250, ${ratio * 0.52})`;
              ctx.beginPath();
              ctx.arc(pt.x, pt.y, ratio * 3.2, 0, Math.PI * 2);
              ctx.fill();
            }

            const angle = Math.atan2(a.vy, a.vx);
            ctx.save();
            ctx.translate(a.x, a.y);
            ctx.rotate(angle);

            ctx.fillStyle = '#bae6fd';
            ctx.beginPath();
            ctx.moveTo(-14, 0);
            ctx.lineTo(-20, -4.5);
            ctx.lineTo(-16, 0);
            ctx.lineTo(-20, 4.5);
            ctx.closePath();
            ctx.fill();

            ctx.strokeStyle = '#d4a373';
            ctx.lineWidth = 2.4;
            ctx.beginPath();
            ctx.moveTo(-15, 0);
            ctx.lineTo(10, 0);
            ctx.stroke();

            ctx.fillStyle = '#3b82f6';
            ctx.beginPath();
            ctx.moveTo(16, 0);
            ctx.lineTo(8, -5);
            ctx.lineTo(10, 0);
            ctx.lineTo(8, 5);
            ctx.closePath();
            ctx.fill();
            ctx.restore();

            let hit = false;
            for (let k = 0; k < hearts.length; k++) {
              const h = hearts[k];
              if (Math.hypot(a.x - h.x, a.y - (h.y + h.size * 0.5)) < h.size * 0.95) {
                h.scaleX = 1.38;
                h.elasticVel = -0.14;
                h.isHappy = true;
                h.happyTimer = 150;
                spawnSparkles(h.x, h.y + h.size * 0.4, 14);
                hit = true;
                break;
              }
            }

            if (!hit && a.targetPoint && Math.hypot(a.x - a.targetPoint.x, a.y - a.targetPoint.y) < 18) {
              spawnSparkles(a.x, a.y, 12);
              hit = true;
            }

            if (hit || a.life <= 0 || a.x < -60 || a.x > width + 60 || a.y > height + 60) {
              arrows.splice(i, 1);
            }
          }

          for (let i = sparkles.length - 1; i >= 0; i--) {
            const sp = sparkles[i];
            sp.x += sp.vx;
            sp.y += sp.vy;
            sp.vx *= 0.95;
            sp.vy *= 0.95;
            sp.alpha -= 0.026;

            if (sp.alpha <= 0) {
              sparkles.splice(i, 1);
              continue;
            }

            ctx.save();
            ctx.globalAlpha = sp.alpha;
            ctx.fillStyle = sp.color;
            ctx.beginPath();
            ctx.arc(sp.x, sp.y, sp.size, 0, Math.PI * 2);
            ctx.fill();
            ctx.restore();
          }
        }

        animateCupidScene();
      }

      initCupid3DBackground();

      /* ----------------------------------------------------------------------
         4. CELEBRATION CONFETTI BURST (SERENE BLUE & GOLD ON "BUKA UNDANGAN")
         ---------------------------------------------------------------------- */
      function triggerRomanticConfetti() {
        const canvas = document.getElementById('confetti-canvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        if (!ctx) return;

        const dpr = Math.min(window.devicePixelRatio || 1, 2);
        const w = window.innerWidth;
        const h = window.innerHeight;
        canvas.width = Math.floor(w * dpr);
        canvas.height = Math.floor(h * dpr);
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

        const colors = ['#3b82f6', '#93c5fd', '#e6b86a', '#a5b4fc', '#7dd3fc'];
        const pieces = [];

        for (let i = 0; i < 90; i++) {
          const angle = Math.random() * Math.PI * 2;
          const speed = 3.5 + Math.random() * 10.5;
          pieces.push({
            x: w * 0.5,
            y: h * 0.5,
            vx: Math.cos(angle) * speed,
            vy: Math.sin(angle) * speed - 3.5,
            size: 7 + Math.random() * 7,
            color: colors[i % colors.length],
            rot: Math.random() * Math.PI * 2,
            rotSpeed: (Math.random() - 0.5) * 0.14,
            alpha: 1,
            isHeart: i % 2 === 0
          });
        }

        function renderConfetti() {
          ctx.clearRect(0, 0, w, h);
          let active = false;

          for (let i = 0; i < pieces.length; i++) {
            const p = pieces[i];
            p.x += p.vx;
            p.y += p.vy;
            p.vy += 0.22;
            p.vx *= 0.985;
            p.rot += p.rotSpeed;
            p.alpha -= 0.012;

            if (p.alpha > 0) {
              active = true;
              ctx.save();
              ctx.translate(p.x, p.y);
              ctx.rotate(p.rot);
              ctx.globalAlpha = p.alpha;
              ctx.fillStyle = p.color;

              if (p.isHeart) {
                const s = p.size * 0.52;
                ctx.beginPath();
                ctx.moveTo(0, s * 0.4);
                ctx.bezierCurveTo(0, 0, -s, 0, -s, s * 0.4);
                ctx.bezierCurveTo(-s, s * 0.9, 0, s * 1.25, 0, s * 1.5);
                ctx.bezierCurveTo(0, s * 1.25, s, s * 0.9, s, s * 0.4);
                ctx.bezierCurveTo(s, 0, 0, 0, 0, s * 0.4);
                ctx.fill();
              } else {
                ctx.beginPath();
                ctx.ellipse(0, 0, p.size * 0.6, p.size * 0.34, 0, 0, Math.PI * 2);
                ctx.fill();
              }
              ctx.restore();
            }
          }

          if (active) {
            requestAnimationFrame(renderConfetti);
          } else {
            ctx.clearRect(0, 0, w, h);
          }
        }

        renderConfetti();
      }

      /* ----------------------------------------------------------------------
         5. STAGE 1 -> STAGE 2 -> STAGE 3 TRANSITION ORCHESTRATION
         ---------------------------------------------------------------------- */
      const stageVideo = document.getElementById('stage-video-intro');
      const stageCover = document.getElementById('stage-cover');
      const stageMain = document.getElementById('stage-main-content');
      const progressBar = document.getElementById('video-progress-bar');
      const btnSkipVideo = document.getElementById('btn-skip-video');
      const btnOpenInvitation = document.getElementById('btn-open-invitation');

      let introCompleted = false;
      const introDuration = 5000;
      const introStart = performance.now();

      function enterCoverStage() {
        if (introCompleted) return;
        introCompleted = true;

        if (stageCover) stageCover.classList.remove('is-hidden');
        if (stageVideo) {
          stageVideo.classList.add('is-exiting');
          setTimeout(() => {
            stageVideo.style.display = 'none';
          }, 950);
        }
      }

      function tickIntroVideo(now) {
        if (introCompleted) return;
        const elapsed = now - introStart;
        const pct = Math.min(100, (elapsed / introDuration) * 100);
        if (progressBar) progressBar.style.width = pct + '%';

        if (elapsed >= introDuration) {
          enterCoverStage();
        } else {
          requestAnimationFrame(tickIntroVideo);
        }
      }

      requestAnimationFrame(tickIntroVideo);
      if (btnSkipVideo) {
        btnSkipVideo.addEventListener('click', enterCoverStage);
      }

      if (btnOpenInvitation) {
        btnOpenInvitation.addEventListener('click', () => {
          triggerRomanticConfetti();
          musicBox.start();

          if (stageCover) stageCover.classList.add('cover-opening');
          if (stageMain) stageMain.classList.remove('is-hidden');

          setTimeout(() => {
            if (stageCover) stageCover.classList.add('is-hidden');
            window.scrollTo({ top: 0, behavior: 'smooth' });
            initScrollReveal();
          }, 850);
        });
      }

      /* ----------------------------------------------------------------------
         6. DYNAMIC GUEST NAME (?to=) & MODAL SIMULATOR
         ---------------------------------------------------------------------- */
      const guestDisplayEl = document.getElementById('guest-name-display');
      const rsvpNameInput = document.getElementById('rsvp-name');

      function setGuestName(rawName) {
        const clean = (rawName || '').trim() || 'Tamu Spesial';
        if (guestDisplayEl) guestDisplayEl.textContent = clean;
        if (clean !== 'Tamu Spesial' && rsvpNameInput) {
          rsvpNameInput.value = clean;
        }
      }

      if (CONFIG.guestNameInitial) {
        setGuestName(CONFIG.guestNameInitial);
      } else {
        try {
          const params = new URLSearchParams(window.location.search);
          setGuestName(params.get('to'));
        } catch (_) {
          setGuestName('');
        }
      }

      const guestModal = document.getElementById('guest-modal');
      const btnTestGuest = document.getElementById('btn-test-guest');
      const btnCloseGuestModal = document.getElementById('btn-close-guest-modal');
      const btnApplyGuestName = document.getElementById('btn-apply-guest-name');
      const inputCustomGuest = document.getElementById('input-custom-guest');

      if (btnTestGuest && guestModal) {
        btnTestGuest.addEventListener('click', () => {
          if (inputCustomGuest && guestDisplayEl) {
            inputCustomGuest.value = guestDisplayEl.textContent === 'Tamu Spesial' ? '' : guestDisplayEl.textContent;
          }
          guestModal.classList.add('is-open');
        });
      }

      if (btnCloseGuestModal && guestModal) {
        btnCloseGuestModal.addEventListener('click', () => {
          guestModal.classList.remove('is-open');
        });
      }

      if (btnApplyGuestName && guestModal) {
        btnApplyGuestName.addEventListener('click', () => {
          setGuestName(inputCustomGuest ? inputCustomGuest.value : '');
          guestModal.classList.remove('is-open');
          showToast('Nama tamu berhasil diperbarui!', '✨');
        });
      }

      /* ----------------------------------------------------------------------
         7. 3D TILT INTERACTION ON COVER & PROFILE CARDS
         ---------------------------------------------------------------------- */
      function attach3DTilt(el, maxAngle = 6) {
        if (!el) return;
        el.addEventListener('mousemove', (e) => {
          const rect = el.getBoundingClientRect();
          const nx = (e.clientX - rect.left) / rect.width - 0.5;
          const ny = (e.clientY - rect.top) / rect.height - 0.5;
          el.style.transform = `perspective(1000px) rotateX(${(-ny * maxAngle * 2).toFixed(2)}deg) rotateY(${(nx * maxAngle * 2).toFixed(2)}deg)`;
        });
        el.addEventListener('mouseleave', () => {
          el.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg)';
        });
      }

      attach3DTilt(document.getElementById('cover-tilt-card'), 6);
      document.querySelectorAll('.profile-tilt-card').forEach((card) => attach3DTilt(card, 5));

      /* ----------------------------------------------------------------------
         8. REAL-TIME COUNTDOWN TIMER
         ---------------------------------------------------------------------- */
      const weddingTimestamp = new Date(CONFIG.targetIso).getTime();
      const cdDays = document.getElementById('cd-days');
      const cdHours = document.getElementById('cd-hours');
      const cdMinutes = document.getElementById('cd-minutes');
      const cdSeconds = document.getElementById('cd-seconds');

      function updateCountdown() {
        const diff = Math.max(0, weddingTimestamp - Date.now());
        const d = Math.floor(diff / 86400000);
        const h = Math.floor((diff / 3600000) % 24);
        const m = Math.floor((diff / 60000) % 60);
        const s = Math.floor((diff / 1000) % 60);

        if (cdDays) cdDays.textContent = String(d).padStart(2, '0');
        if (cdHours) cdHours.textContent = String(h).padStart(2, '0');
        if (cdMinutes) cdMinutes.textContent = String(m).padStart(2, '0');
        if (cdSeconds) cdSeconds.textContent = String(s).padStart(2, '0');
      }

      updateCountdown();
      setInterval(updateCountdown, 1000);

      /* ----------------------------------------------------------------------
         9. SAVE THE DATE (.ICS) & CLIPBOARD COPY
         ---------------------------------------------------------------------- */
      const btnSaveDate = document.getElementById('btn-save-date');
      if (btnSaveDate) {
        btnSaveDate.addEventListener('click', () => {
          const ics = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//Undangan Online//ID',
            'BEGIN:VEVENT',
            `DTSTART:${CONFIG.s1Date}T010000Z`,
            `DTEND:${CONFIG.s1Date}T070000Z`,
            `SUMMARY:${CONFIG.eventTitle}`,
            `DESCRIPTION:${CONFIG.eventTitle}`,
            `LOCATION:${CONFIG.s1Venue}, ${CONFIG.s1Address}`,
            'END:VEVENT',
            'END:VCALENDAR'
          ].join('\r\n');

          const blob = new Blob([ics], { type: 'text/calendar;charset=utf-8' });
          const link = document.createElement('a');
          link.href = URL.createObjectURL(blob);
          link.download = 'Undangan-Acara.ics';
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);
          showToast('Pengingat kalender berhasil diunduh!', '📅');
        });
      }

      document.querySelectorAll('.btn-copy-gift').forEach((btn) => {
        btn.addEventListener('click', () => {
          const code = btn.getAttribute('data-copy') || '';
          const label = btn.getAttribute('data-label') || 'Nomor';
          if (navigator.clipboard) {
            navigator.clipboard.writeText(code).then(() => {
              showToast(`${label} (${code}) berhasil disalin!`, '📋');
            }).catch(() => fallbackCopy(code, label));
          } else {
            fallbackCopy(code, label);
          }
        });
      });

      function fallbackCopy(code, label) {
        const temp = document.createElement('textarea');
        temp.value = code;
        temp.style.position = 'fixed';
        temp.style.opacity = '0';
        document.body.appendChild(temp);
        temp.select();
        try {
          document.execCommand('copy');
          showToast(`${label} (${code}) berhasil disalin!`, '📋');
        } catch (_) {
          showToast('Silakan salin manual nomor rekening di atas', '✨');
        }
        document.body.removeChild(temp);
      }

      /* ----------------------------------------------------------------------
         10. INTERACTIVE RSVP & GUESTBOOK WISHES (AJAX TERINTEGRASI)
         ---------------------------------------------------------------------- */
      const wishesListEl = document.getElementById('wishes-list');
      const rsvpForm = document.getElementById('rsvp-form');
      const rsvpSubmitBtn = document.getElementById('btn-submit-rsvp');

      const seedWishes = [
        {
          guest_name: 'Nabila Putri',
          attendance: 'hadir',
          guests: '2 Orang',
          message: 'Selamat menempuh hidup baru Aulia & Naufal! Semoga menjadi keluarga yang sakinah, mawaddah, warahmah, dan selalu bahagia 💙',
          time_ago: 'Baru saja'
        },
        {
          guest_name: 'Rizky & Keluarga',
          attendance: 'hadir',
          guests: 'Keluarga (3-4 Orang)',
          message: 'Barakallah untuk kedua mempelai. Lancar sampai hari H ya, sampai jumpa di Surabaya!',
          time_ago: '15 menit lalu'
        },
        {
          guest_name: 'Clarissa Wijaya',
          attendance: 'tidak_hadir',
          guests: '1 Orang',
          message: 'Maaf belum bisa hadir langsung karena tugas luar kota, tapi doaku selalu menyertai kebahagiaan kalian berdua ✨',
          time_ago: '1 jam lalu'
        }
      ];

      function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text || '';
        return div.innerHTML;
      }

      function addWishItem(item, prepend = false) {
        if (!wishesListEl) return;
        const name = item.guest_name || item.name || 'Tamu';
        const initials = name
          .split(' ')
          .slice(0, 2)
          .map((part) => part[0] || '')
          .join('')
          .toUpperCase() || '💙';

        const isComing = item.attendance === 'hadir' || item.attendance === 'Hadir';
        const badgeBg = isComing ? '#dbeafe' : '#f1f5f9';
        const badgeColor = isComing ? '#1d4ed8' : '#475569';
        const guestSuffix = item.guests ? ' (' + escapeHtml(item.guests) + ')' : '';

        const card = document.createElement('div');
        card.className = 'clay-card';
        card.style.cssText = 'padding:1rem 1.15rem;display:flex;align-items:flex-start;gap:0.85rem;transition:all 0.45s ease;';
        card.innerHTML = `
          <div class="font-cute" style="width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,#3b82f6,#93c5fd);color:#ffffff;font-weight:700;font-size:0.85rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            ${escapeHtml(initials)}
          </div>
          <div style="flex:1;min-width:0;">
            <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:0.4rem;">
              <h4 class="font-cute" style="font-weight:700;font-size:0.95rem;color:var(--slate-navy);">${escapeHtml(name)}</h4>
              <span style="padding:0.18rem 0.65rem;border-radius:9999px;font-size:0.7rem;font-weight:700;background:${badgeBg};color:${badgeColor};">
                ${isComing ? '💙 Hadir' + guestSuffix : '🕊️ Berhalangan'}
              </span>
            </div>
            <p style="font-size:0.84rem;color:var(--slate-soft);margin-top:0.35rem;word-break:break-word;">
              ${escapeHtml(item.message)}
            </p>
            <span style="display:block;font-size:0.68rem;color:var(--slate-soft);opacity:0.75;margin-top:0.4rem;">
              ${escapeHtml(item.time_ago || item.time || 'Baru saja')}
            </span>
          </div>
        `;

        if (prepend) {
          card.style.opacity = '0';
          card.style.transform = 'translateY(-12px)';
          wishesListEl.prepend(card);
          requestAnimationFrame(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
          });
        } else {
          wishesListEl.appendChild(card);
        }
      }

      const wishesToRender = (CONFIG.initialWishes && CONFIG.initialWishes.length > 0)
        ? CONFIG.initialWishes
        : (CONFIG.isPreview ? seedWishes : []);
      wishesToRender.forEach((w) => addWishItem(w, false));

      if (rsvpForm) {
        rsvpForm.addEventListener('submit', async (e) => {
          e.preventDefault();
          const nameEl = document.getElementById('rsvp-name');
          const attEl = document.getElementById('rsvp-attendance');
          const guestsEl = document.getElementById('rsvp-guests');
          const msgEl = document.getElementById('rsvp-message');

          const name = nameEl ? nameEl.value.trim() : '';
          const message = msgEl ? msgEl.value.trim() : '';
          const attVal = attEl ? attEl.value : 'Hadir';
          const attCode = attVal === 'Hadir' ? 'hadir' : 'tidak_hadir';
          if (!name || !message) return;

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
                  attendance: attCode,
                  message: message
                })
              });
              const data = await res.json();
              if (!res.ok || !data.success) throw new Error();
              addWishItem(data.wish, true);
              if (msgEl) msgEl.value = '';
              showToast(data.message || 'Terima kasih! Konfirmasi & doa terkirim', '💌');
            } catch (_) {
              showToast('Gagal mengirim ucapan. Periksa koneksi Anda.', '⚠️');
            } finally {
              if (rsvpSubmitBtn) rsvpSubmitBtn.disabled = false;
            }
          } else {
            addWishItem({
              guest_name: name,
              attendance: attCode,
              guests: guestsEl ? guestsEl.value : '1 Orang',
              message,
              time_ago: 'Baru saja'
            }, true);
            if (msgEl) msgEl.value = '';
            showToast('Terima kasih! Konfirmasi & doa terkirim', '💌');
          }
        });
      }

      /* ----------------------------------------------------------------------
         11. SCROLL REVEAL & MUSIC BUTTON
         ---------------------------------------------------------------------- */
      function initScrollReveal() {
        const items = document.querySelectorAll('.reveal-on-scroll');
        if (!('IntersectionObserver' in window)) {
          items.forEach((el) => el.classList.add('is-visible'));
          return;
        }

        const observer = new IntersectionObserver(
          (entries) => {
            entries.forEach((entry) => {
              if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
              }
            });
          },
          { threshold: 0.1 }
        );

        items.forEach((el) => observer.observe(el));
      }

      const btnToggleMusic = document.getElementById('btn-toggle-music');
      if (btnToggleMusic) {
        btnToggleMusic.addEventListener('click', () => {
          musicBox.toggle();
        });
      }
    });
  </script>
</body>
</html>
