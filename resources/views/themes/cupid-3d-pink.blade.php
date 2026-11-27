@php
    $evData = $invitation->event_data ?? [];
    $isPreview = \Illuminate\Support\Str::startsWith($invitation->slug ?? '', 'demo-') || empty($evData);

    $primaryName = !empty($evData['primary_name']) ? $evData['primary_name'] : 'Naufal Aditya Pratama, S.Kom.';
    $secondaryName = !empty($evData['secondary_name']) ? $evData['secondary_name'] : 'Aulia Zahra Maharani, S.Ds.';
    $primaryNick = \Illuminate\Support\Str::before(trim($primaryName), ' ');
    $secondaryNick = \Illuminate\Support\Str::before(trim($secondaryName), ' ');
    $hasSecondary = !empty($evData['secondary_name']) || $isPreview;

    $brideDisplayName = $hasSecondary ? $secondaryName : $primaryName;
    $brideNick = $hasSecondary ? $secondaryNick : $primaryNick;
    $groomDisplayName = $primaryName;
    $groomNick = $primaryNick;

    $coupleDisplay = $hasSecondary
        ? ($isPreview ? 'Aulia &amp; Naufal' : e($primaryNick) . ' &amp; ' . e($secondaryNick))
        : e($primaryName);

    $couplePlain = $hasSecondary
        ? ($isPreview ? 'Aulia & Naufal' : $primaryNick . ' & ' . $secondaryNick)
        : $primaryName;

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
        : 'Dengan penuh rasa syukur dan kebahagiaan, kami mengundang Anda untuk merayakan awal babak baru kisah cinta kami.';
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
  <title>{{ $invitation->title ?? 'The Wedding of Aulia & Naufal' }} — 3D Pastel Pink Invitation</title>

  <!-- Google Fonts dengan Fallback Sistem yang Aman -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Great+Vibes&family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />

  <style>
    /* ==========================================================================
       1. DESIGN TOKENS & RESET (ZERO EXTERNAL CSS DEPENDENCY, 100% RESPONSIVE)
       ========================================================================== */
    :root {
      --bg-cream: #fffdf9;
      --bg-blush: #fff0f5;
      --bg-peach: #ffe4e1;
      --bg-lavender: #f4eeff;
      --rose-primary: #e87a90;
      --rose-dark: #c9526b;
      --rose-light: #ffd1dc;
      --gold-warm: #f3c677;
      --gold-dark: #c9933b;
      --berry-text: #4a2335;
      --berry-soft: #7a4b60;
      --white-glass: rgba(255, 255, 255, 0.86);

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
      color: var(--berry-text);
      background: linear-gradient(180deg, #fff8fb 0%, #fff0f5 35%, #fdf3ff 70%, #fffaf5 100%);
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
       2. 3D CLAYMORPHISM SURFACES & TACTILE BUTTONS
       ========================================================================== */
    .clay-card {
      background: linear-gradient(145deg, rgba(255, 255, 255, 0.94), rgba(255, 241, 246, 0.88));
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border-radius: 28px;
      border: 2px solid rgba(255, 255, 255, 0.95);
      box-shadow:
        0 18px 38px -14px rgba(201, 82, 107, 0.22),
        inset 0 -7px 14px rgba(232, 122, 144, 0.14),
        inset 0 7px 14px rgba(255, 255, 255, 0.96);
      transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.35s ease;
    }

    .clay-card:hover {
      box-shadow:
        0 24px 46px -12px rgba(201, 82, 107, 0.3),
        inset 0 -7px 14px rgba(232, 122, 144, 0.18),
        inset 0 7px 16px rgba(255, 255, 255, 1);
    }

    .clay-pill {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.4rem;
      background: linear-gradient(145deg, #ffffff, #ffe8f0);
      border-radius: 9999px;
      border: 1.5px solid rgba(255, 255, 255, 0.95);
      box-shadow:
        0 8px 18px -6px rgba(201, 82, 107, 0.22),
        inset 0 -3px 6px rgba(232, 122, 144, 0.14),
        inset 0 3px 6px rgba(255, 255, 255, 0.95);
    }

    .btn-clay-primary {
      position: relative;
      overflow: hidden;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.55rem;
      background: linear-gradient(135deg, #f392a6 0%, #df657e 100%);
      color: #ffffff;
      font-family: var(--font-cute);
      font-weight: 600;
      border-radius: 9999px;
      border: 2px solid rgba(255, 255, 255, 0.72);
      box-shadow:
        0 14px 28px -8px rgba(223, 101, 126, 0.52),
        inset 0 -5px 10px rgba(158, 49, 71, 0.32),
        inset 0 5px 10px rgba(255, 255, 255, 0.55);
      cursor: pointer;
      transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.25s ease;
    }

    .btn-clay-primary:hover {
      transform: translateY(-3px) scale(1.02);
      box-shadow:
        0 18px 34px -6px rgba(223, 101, 126, 0.64),
        inset 0 -5px 10px rgba(158, 49, 71, 0.32),
        inset 0 5px 12px rgba(255, 255, 255, 0.7);
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
       3. KEYFRAME ANIMATIONS & STAGE 1: 3D CLAY CUPID CINEMATIC OPENING
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

    /* Stage 1 Container: Full-Viewport 3D Clay Cupid Stage */
    #stage-video-intro {
      position: fixed;
      inset: 0;
      z-index: 60;
      height: 100dvh;
      width: 100vw;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: space-between;
      padding: clamp(0.9rem, 2.8vw, 1.75rem);
      background:
        radial-gradient(circle at 18% 85%, rgba(255, 224, 234, 0.9) 0%, transparent 45%),
        radial-gradient(circle at 50% 44%, #fffafc 0%, #ffe6f0 48%, #f3e4ff 100%);
      overflow: hidden;
      user-select: none;
      transition: opacity 0.9s cubic-bezier(0.4, 0, 0.2, 1), transform 1s cubic-bezier(0.4, 0, 0.2, 1);
    }

    #stage-video-intro.is-exiting {
      opacity: 0;
      transform: scale(1.07);
      pointer-events: none;
    }

    /* Fullscreen Interactive 3D Clay Cupid Canvas for Stage 1 */
    #intro-cupid-stage-canvas {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      z-index: 2;
      pointer-events: none;
    }

    /* Soft Pastel Cloud Bank at Bottom Left behind Cupid */
    .intro-cloud-corner {
      position: absolute;
      left: -40px;
      bottom: -35px;
      width: clamp(230px, 46vw, 440px);
      height: clamp(130px, 24vw, 230px);
      z-index: 1;
      pointer-events: none;
      opacity: 0.92;
    }

    /* Center Typography & Crystal Emblem Container (Triggered by Arrow Impact) */
    #intro-center-reveal {
      position: relative;
      z-index: 4;
      margin: auto;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      max-width: 540px;
      padding: 0 1rem;
      pointer-events: none;
      transform: translateY(-4vh);
    }

    /* Crystal Heart & Rings Emblem Before & After Arrow Impact */
    #intro-crystal-emblem {
      position: relative;
      width: clamp(145px, 34vw, 200px);
      height: clamp(145px, 34vw, 200px);
      display: flex;
      align-items: center;
      justify-content: center;
      transform: scale(0.2);
      opacity: 0;
      filter: drop-shadow(0 16px 26px rgba(201, 82, 107, 0.24));
      transition: transform 0.75s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.55s ease, filter 0.75s ease;
    }

    #intro-crystal-emblem.is-placed {
      transform: scale(0.84);
      opacity: 1;
    }

    #intro-crystal-emblem.is-struck {
      transform: scale(1.08);
      opacity: 1;
      filter: drop-shadow(0 22px 38px rgba(232, 122, 144, 0.52));
    }

    /* Monogram Initials & Wedding Rings hidden until arrow strikes center */
    #intro-emblem-rings,
    #intro-emblem-initials {
      opacity: 0;
      transition: opacity 0.45s ease, transform 0.65s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    #intro-crystal-emblem.is-struck #intro-emblem-rings,
    #intro-crystal-emblem.is-struck #intro-emblem-initials {
      opacity: 1;
    }

    /* Glowing Shockwave Ring upon Arrow Impact */
    #intro-shockwave {
      position: absolute;
      width: 120px;
      height: 120px;
      border-radius: 50%;
      border: 4px solid rgba(255, 255, 255, 0.95);
      box-shadow: 0 0 32px rgba(243, 198, 119, 0.9), inset 0 0 24px rgba(255, 181, 197, 0.85);
      opacity: 0;
      transform: scale(0.2);
      pointer-events: none;
    }

    #intro-shockwave.is-bursting {
      animation: shockwaveExpand 1.15s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    @keyframes shockwaveExpand {
      0% { opacity: 1; transform: scale(0.25); }
      65% { opacity: 0.75; transform: scale(2.4); }
      100% { opacity: 0; transform: scale(3.2); }
    }

    /* 3D Clay Typography Reveal after Cupid's Arrow Hits Center */
    .intro-3d-text-group {
      opacity: 0;
      transform: translateY(22px) scale(0.9);
      transition: opacity 0.85s cubic-bezier(0.22, 1, 0.36, 1), transform 0.85s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .intro-3d-text-group.is-revealed {
      opacity: 1;
      transform: translateY(0) scale(1);
    }

    .intro-3d-heading {
      font-family: var(--font-script);
      font-size: clamp(2.8rem, 9.5vw, 4.8rem);
      font-weight: 400;
      line-height: 1.08;
      color: #4a2335;
      text-shadow:
        0 2px 0 #ffffff,
        0 4px 0 #ffd6e2,
        0 7px 0 #f4a2ba,
        0 14px 24px rgba(201, 82, 107, 0.34);
    }

    .intro-3d-subtitle {
      font-family: var(--font-cute);
      font-size: clamp(0.75rem, 2.4vw, 0.92rem);
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.16em;
      color: var(--rose-dark);
      text-shadow: 0 2px 0 #ffffff, 0 4px 10px rgba(201, 82, 107, 0.18);
    }

    .intro-3d-date-pill {
      margin-top: 0.55rem;
      padding: 0.42rem 1.15rem;
      font-family: var(--font-cute);
      font-size: clamp(0.82rem, 2.5vw, 0.96rem);
      font-weight: 700;
      color: var(--berry-text);
    }

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
      background: radial-gradient(circle at 50% 25%, rgba(255, 253, 254, 0.72) 0%, rgba(255, 240, 245, 0.78) 55%, rgba(244, 238, 255, 0.82) 100%);
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
      width: clamp(165px, 42vw, 225px);
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
      color: var(--berry-text);
      margin-top: 0.45rem;
      line-height: 1.2;
    }

    .script-title {
      font-family: var(--font-script);
      font-size: clamp(2.85rem, 9vw, 4.5rem);
      font-weight: 400;
      color: var(--berry-text);
      line-height: 1.12;
    }

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
      transition: transform 0.45s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .gallery-item:hover img {
      transform: scale(1.05);
    }

    #lightbox-modal {
      position: fixed;
      inset: 0;
      z-index: 85;
      background: rgba(74, 35, 53, 0.72);
      backdrop-filter: blur(6px);
      display: none;
      align-items: center;
      justify-content: center;
      padding: 1rem;
    }

    #lightbox-modal.is-open {
      display: flex;
    }

    .countdown-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: clamp(0.5rem, 2vw, 1rem);
      max-width: 520px;
      margin: 1.5rem auto 0;
    }

    .countdown-box {
      padding: clamp(0.75rem, 2.5vw, 1.25rem) 0.35rem;
      text-align: center;
      border-radius: 22px;
    }

    .countdown-num {
      display: block;
      font-family: var(--font-cute);
      font-size: clamp(1.35rem, 4.5vw, 2.25rem);
      font-weight: 700;
      color: var(--rose-dark);
      line-height: 1.1;
    }

    .countdown-label {
      display: block;
      font-size: clamp(0.65rem, 2vw, 0.78rem);
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      color: var(--berry-soft);
      margin-top: 0.25rem;
    }

    .timeline-list {
      position: relative;
      display: flex;
      flex-direction: column;
      gap: 1.25rem;
    }

    .timeline-item {
      display: flex;
      align-items: flex-start;
      gap: 0.9rem;
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

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 0.35rem;
      text-align: left;
    }

    .form-label {
      font-size: 0.78rem;
      font-weight: 700;
      color: var(--berry-text);
    }

    .form-control {
      width: 100%;
      padding: 0.78rem 1rem;
      border-radius: 16px;
      background: rgba(255, 255, 255, 0.94);
      border: 1.5px solid #f7c6d4;
      color: var(--berry-text);
      font-size: 0.9rem;
      outline: none;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .form-control:focus {
      border-color: var(--rose-primary);
      box-shadow: 0 0 0 3px rgba(232, 122, 144, 0.2);
    }

    .reveal-on-scroll {
      opacity: 0;
      transform: translateY(26px) scale(0.98);
      transition: opacity 0.75s cubic-bezier(0.22, 1, 0.36, 1), transform 0.75s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .reveal-on-scroll.is-visible {
      opacity: 1;
      transform: translateY(0) scale(1);
    }

    .music-fab-wrap {
      position: fixed;
      bottom: 1.15rem;
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
      background: linear-gradient(135deg, #4a2335, #7a4b60);
      color: #ffffff;
      border: 3px solid #ffffff;
      box-shadow: 0 12px 24px -6px rgba(74, 35, 53, 0.45);
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
      color: var(--berry-text);
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

    #guest-modal {
      position: fixed;
      inset: 0;
      z-index: 75;
      background: rgba(74, 35, 53, 0.42);
      backdrop-filter: blur(4px);
      display: none;
      align-items: center;
      justify-content: center;
      padding: 1rem;
    }

    #guest-modal.is-open {
      display: flex;
    }

    .text-rose-dark { color: var(--rose-dark); }
    .text-berry-soft { color: var(--berry-soft); }
    .font-cute { font-family: var(--font-cute); }
    .font-serif { font-family: var(--font-serif); }
  </style>
</head>
<body>

  <!-- ========================================================================
       SHARED SVG DEFS: 3D CLAY GRADIENTS & SOFT SHADOW FILTERS (100% VECTOR)
       ======================================================================== -->
  <svg width="0" height="0" style="position:absolute;width:0;height:0;overflow:hidden" aria-hidden="true">
    <defs>
      <filter id="clayShadow" x="-20%" y="-20%" width="140%" height="140%">
        <feDropShadow dx="0" dy="8" stdDeviation="7" flood-color="#c9526b" flood-opacity="0.24" />
      </filter>

      <linearGradient id="roseGoldGrad" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#ffd6e0" />
        <stop offset="48%" stop-color="#f28fa3" />
        <stop offset="100%" stop-color="#c9526b" />
      </linearGradient>

      <linearGradient id="warmGoldGrad" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#fff2bd" />
        <stop offset="50%" stop-color="#f3c677" />
        <stop offset="100%" stop-color="#cf8f36" />
      </linearGradient>

      <linearGradient id="blushClayGrad" x1="15%" y1="10%" x2="85%" y2="90%">
        <stop offset="0%" stop-color="#ffffff" />
        <stop offset="45%" stop-color="#ffdce8" />
        <stop offset="100%" stop-color="#f4a2ba" />
      </linearGradient>

      <linearGradient id="peachClayGrad" x1="10%" y1="10%" x2="90%" y2="90%">
        <stop offset="0%" stop-color="#fff6f2" />
        <stop offset="52%" stop-color="#ffd5c6" />
        <stop offset="100%" stop-color="#f0987e" />
      </linearGradient>

      <linearGradient id="lavenderClayGrad" x1="10%" y1="10%" x2="90%" y2="90%">
        <stop offset="0%" stop-color="#ffffff" />
        <stop offset="50%" stop-color="#ebe0ff" />
        <stop offset="100%" stop-color="#c9b2f5" />
      </linearGradient>

      <linearGradient id="skinClayGrad" x1="20%" y1="10%" x2="80%" y2="90%">
        <stop offset="0%" stop-color="#fff3ec" />
        <stop offset="60%" stop-color="#ffdecb" />
        <stop offset="100%" stop-color="#f4be9f" />
      </linearGradient>

      <linearGradient id="suitClayGrad" x1="15%" y1="10%" x2="85%" y2="95%">
        <stop offset="0%" stop-color="#7a4b60" />
        <stop offset="55%" stop-color="#4a2335" />
        <stop offset="100%" stop-color="#2a111d" />
      </linearGradient>

      <linearGradient id="gownClayGrad" x1="20%" y1="0%" x2="80%" y2="100%">
        <stop offset="0%" stop-color="#ffffff" />
        <stop offset="65%" stop-color="#fff4f8" />
        <stop offset="100%" stop-color="#f6d5e1" />
      </linearGradient>

      <linearGradient id="leafClayGrad" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#d7f5e2" />
        <stop offset="55%" stop-color="#92d4ad" />
        <stop offset="100%" stop-color="#589e76" />
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
       STAGE 1: CINEMATIC 3D CLAY CUPID OPENING ANIMATION (POJOK KIRI BAWAH -> TENGAH)
       ======================================================================== -->
  <section id="stage-video-intro" aria-label="Video Pembukaan 3D Cupid">
    <!-- Decorative 3D Pastel Cloud Bank at Bottom Left -->
    <svg class="intro-cloud-corner" viewBox="0 0 420 220" aria-hidden="true">
      <circle cx="70" cy="180" r="85" fill="url(#blushClayGrad)" />
      <circle cx="165" cy="175" r="95" fill="url(#gownClayGrad)" />
      <circle cx="270" cy="195" r="80" fill="url(#lavenderClayGrad)" />
      <circle cx="345" cy="215" r="65" fill="url(#peachClayGrad)" />
    </svg>

    <!-- Fullscreen 3D Clay Cupid & Golden Arrow Canvas -->
    <canvas id="intro-cupid-stage-canvas" aria-hidden="true"></canvas>

    <!-- Top Navigation Bar -->
    <div style="width:100%;max-width:960px;display:flex;align-items:center;justify-content:space-between;z-index:6;">
      <div class="clay-pill" style="padding:0.42rem 0.95rem;font-size:0.76rem;font-weight:700;color:var(--berry-soft);">
        <span style="width:8px;height:8px;border-radius:50%;background:var(--rose-primary);display:inline-block;"></span>
        <span id="intro-status-label">Cupid's Love Arrow</span>
      </div>

      <button
        id="btn-skip-video"
        type="button"
        class="clay-pill"
        style="padding:0.48rem 1.05rem;font-size:0.8rem;font-weight:700;color:var(--berry-text);cursor:pointer;"
      >
        <span>Lewati Intro ➔</span>
      </button>
    </div>

    <!-- Center 3D Crystal Heart Emblem & Typography Reveal -->
    <div id="intro-center-reveal">
      <!-- Shockwave Ring -->
      <div id="intro-shockwave"></div>

      <!-- 3D Crystal Heart & Interlocking Wedding Rings -->
      <div id="intro-crystal-emblem">
        <svg style="width:100%;height:100%;" viewBox="0 0 220 210">
          <!-- Interlocking 3D Gold & Rose-Gold Rings Behind Heart (Revealed on Impact) -->
          <g id="intro-emblem-rings" transform="translate(0, 6)">
            <circle cx="88" cy="108" r="46" fill="none" stroke="url(#warmGoldGrad)" stroke-width="11" />
            <circle cx="132" cy="108" r="46" fill="none" stroke="url(#roseGoldGrad)" stroke-width="11" />
            <!-- Solitaire Diamond -->
            <polygon points="132,48 144,60 132,71 120,60" fill="#e6f9ff" stroke="#ffffff" stroke-width="2.2" />
          </g>

          <!-- Center 3D Clay Crystal Heart -->
          <path
            d="M110 176 C110 176 34 128 34 74 C34 46 56 28 79 28 C94 28 104 37 110 48 C116 37 126 28 141 28 C164 28 186 46 186 74 C186 128 110 176 110 176 Z"
            fill="url(#blushClayGrad)"
            stroke="#ffffff"
            stroke-width="5.5"
          />

          <!-- Glossy 3D Specular Highlights -->
          <ellipse cx="72" cy="58" rx="18" ry="8.5" transform="rotate(-32 72 58)" fill="#ffffff" opacity="0.92" />
          <circle cx="58" cy="78" r="4.5" fill="#ffffff" opacity="0.75" />

          <!-- Monogram Initials (Revealed when Cupid's arrow strikes) -->
          <text
            id="intro-emblem-initials"
            x="110"
            y="108"
            text-anchor="middle"
            fill="#4A2335"
            font-family="Playfair Display, Georgia, serif"
            font-weight="700"
            font-size="34"
          >
            {{ $initials }}
          </text>
        </svg>
      </div>

      <!-- 3D Typography Revealed When Arrow Strikes Center -->
      <div id="intro-3d-typography" class="intro-3d-text-group">
        <p class="intro-3d-subtitle">The Wedding Celebration of</p>
        <h1 class="intro-3d-heading">{!! $coupleDisplay !!}</h1>
        <div class="clay-pill intro-3d-date-pill">
          <span>✨ {{ $s1DateFormatted }} ✨</span>
        </div>
      </div>
    </div>

    <!-- Bottom Progress Indicator (Aligned Right-Center so Cupid in Bottom-Left Shines Clearly) -->
    <div style="width:100%;max-width:960px;display:flex;justify-content:flex-end;z-index:6;">
      <div style="width:min(320px, 64vw);display:flex;flex-direction:column;align-items:flex-end;gap:0.4rem;">
        <div style="width:100%;height:8px;background:rgba(255,255,255,0.85);border-radius:9999px;overflow:hidden;padding:1.5px;box-shadow:inset 0 1px 3px rgba(0,0,0,0.08);">
          <div id="video-progress-bar" style="height:100%;width:0%;border-radius:9999px;background:linear-gradient(90deg,#e87a90,#f3c677,#c9b2f5);transition:width 0.08s linear;"></div>
        </div>
        <span style="font-size:0.72rem;color:var(--berry-soft);font-weight:600;text-align:right;">
          Kupido mengirimkan panah cinta...
        </span>
      </div>
    </div>
  </section>

  <!-- ========================================================================
       STAGE 2: INTERACTIVE 3D COVER & DYNAMIC GUEST NAME (?to=)
       ======================================================================== -->
  <section id="stage-cover" class="is-hidden" aria-label="Sampul Undangan">
    @if($isPreview)
    <!-- Quick Guest Name Tester Button (Top Right) -->
    <button
      id="btn-test-guest"
      type="button"
      class="clay-pill"
      title="Ubah Nama Tamu (?to=)"
      style="position:fixed;top:0.85rem;right:0.85rem;z-index:45;padding:0.42rem 0.85rem;font-size:0.75rem;font-weight:600;color:var(--berry-soft);cursor:pointer;"
    >
      <span>✏️ Ubah Tamu</span>
    </button>
    @endif

    <!-- 3D Tilt Cover Card -->
    <div id="cover-tilt-card" class="clay-card cover-card">
      <div class="clay-pill font-cute" style="padding:0.3rem 0.9rem;font-size:0.72rem;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:var(--rose-dark);">
        ✨ {{ strtoupper($invitation->category ?? 'Wedding Invitation') === 'PERNIKAHAN' ? 'Wedding Invitation' : ($invitation->category ?? 'Wedding Invitation') }} ✨
      </div>

      <!-- 3D Clay Bride & Groom Under Floral Arch -->
      <div class="cover-illustration anim-float">
        <svg style="width:100%;height:auto;display:block;" viewBox="0 0 320 290" filter="url(#clayShadow)">
          <!-- Floral Arch Frame -->
          <path
            d="M52 256 L52 132 A108 108 0 0 1 268 132 L268 256"
            fill="none"
            stroke="url(#blushClayGrad)"
            stroke-width="24"
            stroke-linecap="round"
          />
          <path
            d="M52 256 L52 132 A108 108 0 0 1 268 132 L268 256"
            fill="none"
            stroke="#ffffff"
            stroke-width="5"
            stroke-linecap="round"
            stroke-dasharray="1 18"
            opacity="0.9"
          />

          <!-- Top Arch Flowers & Leaves -->
          <ellipse cx="115" cy="34" rx="18" ry="9" transform="rotate(-28 115 34)" fill="url(#leafClayGrad)" />
          <ellipse cx="205" cy="34" rx="18" ry="9" transform="rotate(28 205 34)" fill="url(#leafClayGrad)" />
          <ellipse cx="72" cy="74" rx="16" ry="8" transform="rotate(-55 72 74)" fill="url(#leafClayGrad)" />
          <ellipse cx="248" cy="74" rx="16" ry="8" transform="rotate(55 248 74)" fill="url(#leafClayGrad)" />
          <circle cx="160" cy="24" r="20" fill="url(#roseGoldGrad)" />
          <circle cx="160" cy="24" r="9" fill="#ffd9e2" />
          <circle cx="132" cy="32" r="15" fill="url(#peachClayGrad)" />
          <circle cx="188" cy="32" r="15" fill="url(#peachClayGrad)" />
          <circle cx="96" cy="52" r="14" fill="url(#lavenderClayGrad)" />
          <circle cx="224" cy="52" r="14" fill="url(#lavenderClayGrad)" />

          <!-- Floating Mini Heart -->
          <path d="M160 85 C160 85 147 75 147 66 C147 61 151 58 155 58 C158 58 159 60 160 62 C161 60 162 58 165 58 C169 58 173 61 173 66 C173 75 160 85 160 85 Z" fill="url(#roseGoldGrad)" />

          <!-- GROOM (LEFT) -->
          <g transform="translate(82, 102)">
            <path d="M14 146 C14 106 24 92 46 92 C68 92 78 106 78 146 Z" fill="url(#suitClayGrad)" />
            <polygon points="36,92 56,92 46,122" fill="#ffffff" />
            <polygon points="38,97 46,102 38,107" fill="url(#roseGoldGrad)" />
            <polygon points="54,97 46,102 54,107" fill="url(#roseGoldGrad)" />
            <circle cx="46" cy="102" r="3" fill="#ffd1dc" />
            <rect x="14" y="28" width="64" height="64" rx="32" fill="url(#skinClayGrad)" />
            <path d="M14 54 C14 24 28 14 48 14 C68 14 78 26 78 52 C74 40 62 34 46 36 C30 38 20 44 14 54 Z" fill="#3a232d" />
            <circle cx="34" cy="60" r="4" fill="#3a232d" />
            <circle cx="35.5" cy="58.5" r="1.4" fill="#ffffff" />
            <circle cx="58" cy="60" r="4" fill="#3a232d" />
            <circle cx="59.5" cy="58.5" r="1.4" fill="#ffffff" />
            <ellipse cx="27" cy="68" rx="5.5" ry="3.2" fill="#ff9eb1" opacity="0.65" />
            <ellipse cx="65" cy="68" rx="5.5" ry="3.2" fill="#ff9eb1" opacity="0.65" />
            <path d="M41 70 Q46 76 51 70" fill="none" stroke="#8c4a58" stroke-width="2.6" stroke-linecap="round" />
          </g>

          <!-- BRIDE (RIGHT) -->
          <g transform="translate(152, 106)">
            <path d="M10 40 C4 83 6 123 18 142 L74 142 C86 123 88 83 82 40 Z" fill="#ffffff" opacity="0.82" />
            <path d="M12 142 C14 104 24 90 46 90 C68 90 78 104 80 142 Z" fill="url(#gownClayGrad)" />
            <path d="M26 92 Q46 104 66 92" fill="none" stroke="#f28fa3" stroke-width="3" stroke-linecap="round" />
            <rect x="12" y="26" width="68" height="68" rx="34" fill="#4e2e3b" />
            <rect x="16" y="28" width="60" height="60" rx="30" fill="url(#skinClayGrad)" />
            <path d="M16 50 C18 28 32 22 46 32 C60 22 74 28 76 50 C66 40 54 38 46 42 C38 38 26 40 16 50 Z" fill="#4e2e3b" />
            <circle cx="34" cy="24" r="7" fill="url(#roseGoldGrad)" />
            <circle cx="46" cy="21" r="8" fill="url(#peachClayGrad)" />
            <circle cx="58" cy="24" r="7" fill="url(#lavenderClayGrad)" />
            <circle cx="35" cy="58" r="4" fill="#3a232d" />
            <circle cx="36.5" cy="56.5" r="1.4" fill="#ffffff" />
            <circle cx="57" cy="58" r="4" fill="#3a232d" />
            <circle cx="58.5" cy="56.5" r="1.4" fill="#ffffff" />
            <ellipse cx="28" cy="66" rx="5.5" ry="3.2" fill="#ff8da1" opacity="0.7" />
            <ellipse cx="64" cy="66" rx="5.5" ry="3.2" fill="#ff8da1" opacity="0.7" />
            <path d="M41 68 Q46 74 51 68" fill="none" stroke="#8c4a58" stroke-width="2.6" stroke-linecap="round" />
            <!-- Bouquet -->
            <g transform="translate(26, 104)">
              <circle cx="14" cy="12" r="9" fill="url(#roseGoldGrad)" />
              <circle cx="26" cy="12" r="9" fill="url(#peachClayGrad)" />
              <circle cx="20" cy="6" r="9" fill="url(#blushClayGrad)" />
              <circle cx="20" cy="16" r="7" fill="url(#leafClayGrad)" />
            </g>
          </g>

          <!-- Pedestal Base -->
          <rect x="44" y="246" width="232" height="22" rx="11" fill="url(#blushClayGrad)" stroke="#ffffff" stroke-width="3" />
        </svg>
      </div>

      <h2 class="script-title" style="font-size:clamp(2.3rem, 7.5vw, 3.2rem);margin-top:0.15rem;">
        {!! $coupleDisplay !!}
      </h2>
      <p style="font-size:0.82rem;color:var(--berry-soft);font-weight:600;">
        {{ $coverSubline }}
      </p>

      <!-- Dynamic Guest Box -->
      <div style="width:100%;margin-top:0.85rem;padding:0.75rem 1rem;border-radius:20px;background:rgba(255,255,255,0.85);border:1.5px solid #ffd8e4;">
        <p style="font-size:0.68rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--berry-soft);font-weight:700;">
          Kepada Yth. Bapak/Ibu/Saudara/i
        </p>
        <p id="guest-name-display" class="font-cute" style="font-size:clamp(1.1rem, 4vw, 1.35rem);font-weight:700;color:var(--berry-text);margin-top:0.2rem;word-break:break-word;">
          {{ $guestNameVal }}
        </p>
        @if(!empty($guestAddrVal))
          <p style="font-size:0.75rem;color:var(--berry-soft);margin-top:0.15rem;word-break:break-word;">
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
      <div class="clay-pill" style="padding:0.35rem 0.8rem;font-size:0.72rem;font-weight:600;color:var(--berry-text);">
        <span>🎵 {{ $musicUrl ? 'Musik Latar' : 'Music Box' }}</span>
      </div>
      <button id="btn-toggle-music" type="button" class="music-fab" aria-label="Putar atau Jeda Musik">
        <div id="vinyl-disc" class="vinyl-disc is-playing">
          <span style="position:absolute;inset:5px;border-radius:50%;border:1px solid rgba(255,255,255,0.25);"></span>
          <span style="width:20px;height:20px;border-radius:50%;background:var(--rose-primary);display:flex;align-items:center;justify-content:center;font-size:0.7rem;">♪</span>
        </div>
      </button>
    </div>
    @endif

    <!-- ======================================================================
         1. HERO SECTION
         ====================================================================== -->
    <header class="section-block" style="padding-top:clamp(2.25rem, 6vw, 3.75rem);text-align:center;">
      <div class="container-narrow">
        <div class="clay-pill font-cute" style="padding:0.35rem 1rem;font-size:0.74rem;font-weight:600;letter-spacing:0.12em;text-transform:uppercase;color:var(--rose-dark);margin-bottom:0.75rem;">
          {{ $isPreview ? 'We Are Getting Married' : ($invitation->title ?? 'We Are Getting Married') }}
        </div>

        <h1 class="script-title">
          @if($hasSecondary)
            {{ $isPreview ? 'Aulia' : $primaryNick }} <span style="color:var(--rose-primary);">&amp;</span> {{ $isPreview ? 'Naufal' : $secondaryNick }}
          @else
            {{ $primaryName }}
          @endif
        </h1>

        <p style="font-size:clamp(0.88rem, 2.8vw, 1rem);color:var(--berry-soft);max-width:460px;margin:0.65rem auto 0;">
          {{ $greeting }}
        </p>

        <!-- Cover Photo (Jika diunggah) atau 3D Gazebo & Couple Illustration -->
        @if($coverImage)
          <div class="anim-float clay-card" style="width:min(260px, 72vw);aspect-ratio:1/1;margin:1.5rem auto;padding:10px;border-radius:50%;cursor:pointer;" onclick="window.openCupidLightbox && window.openCupidLightbox('{{ $coverImage }}')">
            <img src="{{ $coverImage }}" alt="{{ $invitation->title ?? 'Foto Sampul' }}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;display:block;" />
          </div>
        @else
          <div class="anim-float" style="max-width:360px;margin:1.5rem auto;">
          <svg style="width:100%;height:auto;display:block;" viewBox="0 0 400 300" filter="url(#clayShadow)">
            <path d="M55 108 Q200 16 345 108 Z" fill="url(#blushClayGrad)" stroke="#ffffff" stroke-width="3" />
            <path d="M95 108 Q200 36 305 108 Z" fill="url(#peachClayGrad)" opacity="0.78" />
            <circle cx="200" cy="34" r="14" fill="url(#warmGoldGrad)" />
            <path d="M55 108 Q90 124 127 108 Q163 124 200 108 Q236 124 272 108 Q308 124 345 108" fill="none" stroke="url(#roseGoldGrad)" stroke-width="8" stroke-linecap="round" />

            <!-- Pillars -->
            <rect x="78" y="112" width="14" height="152" rx="7" fill="url(#gownClayGrad)" />
            <rect x="308" y="112" width="14" height="152" rx="7" fill="url(#gownClayGrad)" />
            <circle cx="85" cy="124" r="14" fill="url(#roseGoldGrad)" />
            <circle cx="102" cy="116" r="11" fill="url(#lavenderClayGrad)" />
            <circle cx="315" cy="124" r="14" fill="url(#roseGoldGrad)" />
            <circle cx="298" cy="116" r="11" fill="url(#lavenderClayGrad)" />

            <!-- Fairy Lights -->
            <path d="M92 116 Q200 152 308 116" fill="none" stroke="#f3c677" stroke-width="2.5" stroke-dasharray="4 4" />
            <circle cx="145" cy="131" r="4.5" fill="#fff3b0" />
            <circle cx="200" cy="136" r="5.5" fill="#fff3b0" />
            <circle cx="255" cy="131" r="4.5" fill="#fff3b0" />

            <!-- Groom -->
            <g transform="translate(122, 130)">
              <path d="M14 134 C14 98 24 86 44 86 C64 86 74 98 74 134 Z" fill="url(#suitClayGrad)" />
              <polygon points="35,86 53,86 44,112" fill="#ffffff" />
              <rect x="16" y="28" width="56" height="56" rx="28" fill="url(#skinClayGrad)" />
              <path d="M16 50 C16 24 30 16 46 16 C62 16 72 26 72 48 C64 38 52 34 42 36 C30 38 22 42 16 50 Z" fill="#3a232d" />
              <circle cx="34" cy="56" r="3.5" fill="#3a232d" />
              <circle cx="54" cy="56" r="3.5" fill="#3a232d" />
              <ellipse cx="28" cy="63" rx="5" ry="2.8" fill="#ff9eb1" opacity="0.7" />
              <ellipse cx="60" cy="63" rx="5" ry="2.8" fill="#ff9eb1" opacity="0.7" />
              <path d="M40 65 Q44 70 48 65" fill="none" stroke="#8c4a58" stroke-width="2.4" stroke-linecap="round" />
            </g>

            <!-- Bride -->
            <g transform="translate(194, 134)">
              <path d="M12 130 C14 96 24 84 44 84 C64 84 74 96 76 130 Z" fill="url(#gownClayGrad)" />
              <rect x="14" y="26" width="60" height="60" rx="30" fill="#4e2e3b" />
              <rect x="17" y="28" width="54" height="54" rx="27" fill="url(#skinClayGrad)" />
              <path d="M17 48 C20 28 32 22 44 32 C56 22 68 28 71 48 C62 38 52 36 44 40 C36 36 26 38 17 48 Z" fill="#4e2e3b" />
              <circle cx="44" cy="22" r="7" fill="url(#roseGoldGrad)" />
              <circle cx="34" cy="54" r="3.5" fill="#3a232d" />
              <circle cx="54" cy="54" r="3.5" fill="#3a232d" />
              <ellipse cx="28" cy="61" rx="5" ry="2.8" fill="#ff8da1" opacity="0.7" />
              <ellipse cx="60" cy="61" rx="5" ry="2.8" fill="#ff8da1" opacity="0.7" />
              <path d="M40 63 Q44 68 48 63" fill="none" stroke="#8c4a58" stroke-width="2.4" stroke-linecap="round" />
            </g>

            <!-- Heart Above -->
            <path d="M200 172 C200 172 189 163 189 155 C189 150 193 147 196 147 C198 147 199 149 200 151 C201 149 202 147 204 147 C207 147 211 150 211 155 C211 163 200 172 200 172 Z" fill="url(#roseGoldGrad)" />
            <rect x="52" y="262" width="296" height="22" rx="11" fill="url(#blushClayGrad)" stroke="#ffffff" stroke-width="3" />
          </svg>
        </div>
        @endif

        <!-- Save the Date Pill Card -->
        <div class="clay-card" style="display:inline-flex;flex-wrap:wrap;align-items:center;justify-content:center;gap:1rem;padding:1rem 1.5rem;max-width:100%;">
          <div style="text-align:center;">
            <p style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--berry-soft);font-weight:700;">Save The Date</p>
            <p class="font-cute" style="font-size:1.05rem;font-weight:700;color:var(--berry-text);">{{ $s1DateFormatted }}</p>
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
              <path d="M60 35 C35 10 10 15 15 35 C10 55 35 60 60 35 Z" fill="url(#roseGoldGrad)" />
              <path d="M60 35 C85 10 110 15 105 35 C110 55 85 60 60 35 Z" fill="url(#roseGoldGrad)" />
              <circle cx="60" cy="35" r="11" fill="url(#peachClayGrad)" stroke="#ffffff" stroke-width="2" />
            </svg>
          </div>

          <p class="font-serif" style="font-style:italic;font-size:clamp(0.95rem, 2.8vw, 1.12rem);line-height:1.7;">
            “{{ $quote }}”
          </p>
          <div class="clay-pill font-cute" style="margin-top:1rem;padding:0.3rem 0.95rem;font-size:0.75rem;font-weight:600;color:var(--rose-dark);">
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
          <div class="clay-pill font-cute" style="padding:0.3rem 0.95rem;font-size:0.74rem;font-weight:600;text-transform:uppercase;color:var(--rose-dark);">
            {{ $hasSecondary ? 'Bride &amp; Groom' : 'Penyelenggara Acara' }}
          </div>
          <h2 class="section-title">{{ $hasSecondary ? 'Mempelai Berbahagia' : 'Profil Penyelenggara' }}</h2>
        </div>

        <div class="{{ $hasSecondary ? 'grid-2-col' : 'container-narrow' }}">
          @if($hasSecondary)
          <!-- Bride Card -->
          <div class="clay-card profile-tilt-card" style="padding:clamp(1.4rem, 4vw, 2rem);text-align:center;display:flex;flex-direction:column;align-items:center;">
            <div style="width:128px;height:128px;border-radius:50%;background:linear-gradient(180deg,#ffe4ef,#fff2ea);padding:6px;margin-bottom:0.9rem;overflow:hidden;flex-shrink:0;">
              @if($bridePhoto)
                <img src="{{ $bridePhoto }}" alt="{{ $brideDisplayName }}" loading="lazy" decoding="async" style="width:100%;height:100%;object-fit:cover;border-radius:50%;display:block;" />
              @else
                <svg style="width:100%;height:100%;" viewBox="0 0 140 140" filter="url(#clayShadow)">
                  <circle cx="70" cy="70" r="62" fill="url(#blushClayGrad)" />
                  <rect x="34" y="28" width="72" height="76" rx="36" fill="#4e2e3b" />
                  <path d="M30 124 C34 98 48 92 70 92 C92 92 106 98 110 124 Z" fill="url(#gownClayGrad)" />
                  <rect x="39" y="34" width="62" height="62" rx="31" fill="url(#skinClayGrad)" />
                  <path d="M39 58 C42 36 56 30 70 40 C84 30 98 36 101 58 C90 46 78 44 70 48 C62 44 50 46 39 58 Z" fill="#4e2e3b" />
                  <circle cx="56" cy="30" r="8" fill="url(#roseGoldGrad)" />
                  <circle cx="70" cy="27" r="9" fill="url(#peachClayGrad)" />
                  <circle cx="84" cy="30" r="8" fill="url(#lavenderClayGrad)" />
                  <circle cx="58" cy="65" r="4" fill="#3a232d" />
                  <circle cx="59.5" cy="63.5" r="1.4" fill="#ffffff" />
                  <circle cx="82" cy="65" r="4" fill="#3a232d" />
                  <circle cx="83.5" cy="63.5" r="1.4" fill="#ffffff" />
                  <ellipse cx="51" cy="73" rx="6" ry="3.5" fill="#ff8da1" opacity="0.7" />
                  <ellipse cx="89" cy="73" rx="6" ry="3.5" fill="#ff8da1" opacity="0.7" />
                  <path d="M64 75 Q70 81 76 75" fill="none" stroke="#8c4a58" stroke-width="2.6" stroke-linecap="round" />
                </svg>
              @endif
            </div>

            <span class="font-cute" style="font-size:0.74rem;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:var(--rose-dark);">The Bride</span>
            <h3 class="font-serif" style="font-size:clamp(1.25rem, 3.8vw, 1.5rem);font-weight:700;margin-top:0.2rem;">
              {{ $brideDisplayName }}
            </h3>
            @if(!empty($secondaryParents))
              <p style="font-size:0.84rem;color:var(--berry-soft);margin-top:0.45rem;">
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

          <!-- Groom Card -->
          <div class="clay-card profile-tilt-card" style="padding:clamp(1.4rem, 4vw, 2rem);text-align:center;display:flex;flex-direction:column;align-items:center;">
            <div style="width:128px;height:128px;border-radius:50%;background:linear-gradient(180deg,#fff3df,#ffe4ef);padding:6px;margin-bottom:0.9rem;overflow:hidden;flex-shrink:0;">
              @if($groomPhoto)
                <img src="{{ $groomPhoto }}" alt="{{ $groomDisplayName }}" loading="lazy" decoding="async" style="width:100%;height:100%;object-fit:cover;border-radius:50%;display:block;" />
              @else
                <svg style="width:100%;height:100%;" viewBox="0 0 140 140" filter="url(#clayShadow)">
                  <circle cx="70" cy="70" r="62" fill="url(#peachClayGrad)" />
                  <path d="M30 124 C34 98 48 92 70 92 C92 92 106 98 110 124 Z" fill="url(#suitClayGrad)" />
                  <polygon points="58,92 82,92 70,118" fill="#ffffff" />
                  <polygon points="61,97 70,102 61,107" fill="url(#roseGoldGrad)" />
                  <polygon points="79,97 70,102 79,107" fill="url(#roseGoldGrad)" />
                  <rect x="38" y="34" width="64" height="64" rx="32" fill="url(#skinClayGrad)" />
                  <path d="M38 60 C38 30 52 20 72 20 C92 20 102 32 102 58 C96 46 84 40 68 42 C52 44 44 50 38 60 Z" fill="#3a232d" />
                  <circle cx="58" cy="65" r="4" fill="#3a232d" />
                  <circle cx="59.5" cy="63.5" r="1.4" fill="#ffffff" />
                  <circle cx="82" cy="65" r="4" fill="#3a232d" />
                  <circle cx="83.5" cy="63.5" r="1.4" fill="#ffffff" />
                  <ellipse cx="51" cy="73" rx="6" ry="3.5" fill="#ff9eb1" opacity="0.65" />
                  <ellipse cx="89" cy="73" rx="6" ry="3.5" fill="#ff9eb1" opacity="0.65" />
                  <path d="M64 75 Q70 81 76 75" fill="none" stroke="#8c4a58" stroke-width="2.6" stroke-linecap="round" />
                </svg>
              @endif
            </div>

            <span class="font-cute" style="font-size:0.74rem;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:var(--rose-dark);">{{ $hasSecondary ? 'The Groom' : 'Host' }}</span>
            <h3 class="font-serif" style="font-size:clamp(1.25rem, 3.8vw, 1.5rem);font-weight:700;margin-top:0.2rem;">
              {{ $groomDisplayName }}
            </h3>
            @if(!empty($primaryParents))
              <p style="font-size:0.84rem;color:var(--berry-soft);margin-top:0.45rem;">
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
        <p style="font-size:0.86rem;color:var(--berry-soft);margin-top:0.3rem;">
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
          <div class="clay-pill font-cute" style="padding:0.3rem 0.95rem;font-size:0.74rem;font-weight:600;text-transform:uppercase;color:var(--rose-dark);">
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
                  <circle cx="74" cy="68" r="26" fill="none" stroke="url(#roseGoldGrad)" stroke-width="10" />
                  <polygon points="74,28 83,37 74,45 65,37" fill="#e0f7ff" stroke="#ffffff" stroke-width="1.5" />
                </svg>
              </div>
              <h3 class="font-serif" style="font-size:1.45rem;font-weight:700;">{{ $s1Title }}</h3>
              <p style="font-weight:700;font-size:0.9rem;margin-top:0.65rem;">{{ $s1DateFormatted }}</p>
              <div class="clay-pill font-cute" style="margin-top:0.4rem;padding:0.28rem 0.85rem;font-size:0.76rem;font-weight:700;color:var(--rose-dark);">
                {{ $s1Time }}
              </div>
              <p style="font-weight:700;font-size:0.9rem;margin-top:0.75rem;">{{ $s1Venue }}</p>
              <p style="font-size:0.8rem;color:var(--berry-soft);margin-top:0.2rem;">
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
                  <rect x="24" y="72" width="72" height="28" rx="10" fill="url(#blushClayGrad)" />
                  <rect x="34" y="48" width="52" height="26" rx="9" fill="url(#peachClayGrad)" />
                  <rect x="44" y="28" width="32" height="22" rx="8" fill="url(#lavenderClayGrad)" />
                  <path d="M60 24 C60 24 51 17 51 11 C51 8 54 6 57 6 C59 6 60 7 60 9 C60 7 61 6 63 6 C66 6 69 8 69 11 C69 17 60 24 60 24 Z" fill="url(#roseGoldGrad)" />
                </svg>
              </div>
              <h3 class="font-serif" style="font-size:1.45rem;font-weight:700;">{{ $s2Title }}</h3>
              <p style="font-weight:700;font-size:0.9rem;margin-top:0.65rem;">{{ $s2DateFormatted }}</p>
              <div class="clay-pill font-cute" style="margin-top:0.4rem;padding:0.28rem 0.85rem;font-size:0.76rem;font-weight:700;color:var(--rose-dark);">
                {{ $s2Time }}
              </div>
              <p style="font-weight:700;font-size:0.9rem;margin-top:0.75rem;">{{ $s2Venue }}</p>
              <p style="font-size:0.8rem;color:var(--berry-soft);margin-top:0.2rem;">
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
          <div class="clay-pill font-cute" style="padding:0.3rem 0.95rem;font-size:0.74rem;font-weight:600;text-transform:uppercase;color:var(--rose-dark);">
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
                  <rect x="10" y="18" width="44" height="30" rx="8" fill="url(#blushClayGrad)" />
                  <path d="M12 20 L32 35 L52 20" fill="none" stroke="#ffffff" stroke-width="3" stroke-linecap="round" />
                  <circle cx="32" cy="34" r="6" fill="url(#roseGoldGrad)" />
                </svg>
              </div>
              <div class="clay-card" style="padding:1.15rem 1.3rem;flex:1;">
                @if(!empty($rItem['time']))
                  <span class="font-cute" style="font-size:0.75rem;font-weight:700;color:var(--rose-dark);">{{ $rItem['time'] }}</span>
                @endif
                <h3 class="font-serif" style="font-size:1.12rem;font-weight:700;margin-top:0.15rem;">{{ $rItem['title'] }}</h3>
                @if(!empty($rItem['description']))
                  <p style="font-size:0.84rem;color:var(--berry-soft);margin-top:0.3rem;">{{ $rItem['description'] }}</p>
                @endif
              </div>
            </div>
            @endforeach
          @else
            <!-- Item 1 -->
            <div class="timeline-item">
              <div class="clay-card timeline-badge">
                <svg style="width:32px;height:32px;" viewBox="0 0 64 64">
                  <rect x="14" y="24" width="30" height="26" rx="8" fill="url(#peachClayGrad)" />
                  <path d="M44 29 H50 A6 6 0 0 1 50 41 H44" fill="none" stroke="url(#roseGoldGrad)" stroke-width="5" />
                  <path d="M24 14 Q22 18 25 20 M33 12 Q31 16 34 18" fill="none" stroke="#e87a90" stroke-width="2.5" stroke-linecap="round" />
                </svg>
              </div>
              <div class="clay-card" style="padding:1.15rem 1.3rem;flex:1;">
                <span class="font-cute" style="font-size:0.75rem;font-weight:700;color:var(--rose-dark);">Oktober 2022</span>
                <h3 class="font-serif" style="font-size:1.12rem;font-weight:700;margin-top:0.15rem;">Pertemuan Pertama</h3>
                <p style="font-size:0.84rem;color:var(--berry-soft);margin-top:0.3rem;">
                  Berawal dari secangkir kopi di sudut kota Surabaya saat proyek kreatif bersama, obrolan sederhana berubah menjadi tawa yang tak pernah habis.
                </p>
              </div>
            </div>

            <!-- Item 2 -->
            <div class="timeline-item">
              <div class="clay-card timeline-badge">
                <svg style="width:32px;height:32px;" viewBox="0 0 64 64">
                  <rect x="10" y="18" width="44" height="30" rx="8" fill="url(#blushClayGrad)" />
                  <path d="M12 20 L32 35 L52 20" fill="none" stroke="#ffffff" stroke-width="3" stroke-linecap="round" />
                  <circle cx="32" cy="34" r="6" fill="url(#roseGoldGrad)" />
                </svg>
              </div>
              <div class="clay-card" style="padding:1.15rem 1.3rem;flex:1;">
                <span class="font-cute" style="font-size:0.75rem;font-weight:700;color:var(--rose-dark);">Juni 2024</span>
                <h3 class="font-serif" style="font-size:1.12rem;font-weight:700;margin-top:0.15rem;">Menjalin Komitmen</h3>
                <p style="font-size:0.84rem;color:var(--berry-soft);margin-top:0.3rem;">
                  Setelah saling mengenal impian masing-masing, kami sepakat melangkah bersama dan saling menguatkan dalam suka maupun duka.
                </p>
              </div>
            </div>

            <!-- Item 3 -->
            <div class="timeline-item">
              <div class="clay-card timeline-badge">
                <svg style="width:32px;height:32px;" viewBox="0 0 64 64">
                  <rect x="14" y="28" width="36" height="24" rx="8" fill="url(#roseGoldGrad)" />
                  <path d="M14 28 C14 14 50 14 50 28 Z" fill="url(#lavenderClayGrad)" />
                  <circle cx="32" cy="28" r="7" fill="none" stroke="url(#warmGoldGrad)" stroke-width="3.5" />
                </svg>
              </div>
              <div class="clay-card" style="padding:1.15rem 1.3rem;flex:1;">
                <span class="font-cute" style="font-size:0.75rem;font-weight:700;color:var(--rose-dark);">Mei 2026</span>
                <h3 class="font-serif" style="font-size:1.12rem;font-weight:700;margin-top:0.15rem;">Momen Lamaran</h3>
                <p style="font-size:0.84rem;color:var(--berry-soft);margin-top:0.3rem;">
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
          <div class="clay-pill font-cute" style="padding:0.3rem 0.95rem;font-size:0.74rem;font-weight:600;text-transform:uppercase;color:var(--rose-dark);">
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
          <div class="clay-pill font-cute" style="padding:0.3rem 0.95rem;font-size:0.74rem;font-weight:600;text-transform:uppercase;color:var(--rose-dark);">
            Wedding Gift
          </div>
          <h2 class="section-title">Amplop Digital</h2>
          <p style="font-size:0.85rem;color:var(--berry-soft);max-width:460px;margin:0.4rem auto 0;">
            Doa restu Anda merupakan karunia terindah bagi kami. Namun jika memberi adalah ungkapan tanda kasih, Anda dapat mengirimkannya secara digital:
          </p>
        </div>

        <div class="{{ count($giftAccounts) > 1 ? 'grid-2-col' : '' }}">
          @foreach($giftAccounts as $idx => $acc)
          <div class="clay-card" style="padding:1.4rem;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.85rem;">
              <span class="font-cute" style="font-weight:700;font-size:1.1rem;">{{ $acc['bank'] }}</span>
              <span style="width:36px;height:24px;border-radius:6px;background:{{ $idx % 2 === 0 ? 'linear-gradient(135deg,#ffe29a,#e8b04b)' : 'linear-gradient(135deg,#ffd1dc,#e87a90)' }};border:1px solid #ffffff;"></span>
            </div>
            <p style="font-size:0.74rem;color:var(--berry-soft);">Nomor Rekening</p>
            <p class="font-cute" style="font-size:1.25rem;font-weight:700;letter-spacing:0.06em;margin-top:0.1rem;word-break:break-all;">{{ $acc['account_number'] }}</p>
            @if(!empty($acc['account_name']))
              <p style="font-size:0.8rem;font-weight:600;color:var(--berry-soft);margin-top:0.2rem;">a.n. {{ $acc['account_name'] }}</p>
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
          <div class="clay-pill font-cute" style="padding:0.3rem 0.95rem;font-size:0.74rem;font-weight:600;text-transform:uppercase;color:var(--rose-dark);">
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
                <option value="Hadir">💖 InsyaAllah Hadir</option>
                <option value="Tidak Hadir">🌸 Maaf, Berhalangan Hadir</option>
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
      <p style="font-size:0.8rem;color:var(--berry-soft);margin-top:0.2rem;">Terima kasih atas doa dan kehadiran Anda 💕</p>
    </footer>
  </main>

  <!-- ========================================================================
       MODAL UBAH NAMA TAMU (?to=) & LIGHTBOX GALERI & TOAST NOTIFICATION
       ======================================================================== -->
  @if($isPreview)
  <div id="guest-modal" role="dialog" aria-modal="true" aria-labelledby="modal-guest-title">
    <div class="clay-card" style="width:100%;max-width:360px;padding:1.5rem;text-align:center;">
      <h3 id="modal-guest-title" class="font-cute" style="font-size:1.1rem;font-weight:700;">Simulasi Nama Tamu (?to=)</h3>
      <p style="font-size:0.8rem;color:var(--berry-soft);margin-top:0.3rem;">Masukkan nama tamu untuk melihat tampilan personal pada sampul:</p>
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
    <span id="toast-icon">💖</span>
    <span id="toast-text">Berhasil!</span>
  </div>

  <!-- ========================================================================
       JAVASCRIPT: 100% SELF-CONTAINED, 3D CLAY CUPID CINEMATIC INTRO & INTERACTIONS
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
        couplePlain: @json($couplePlain),
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

      function showToast(message, icon = '💖') {
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
          } catch (_) {
            // Audio autoplay safely deferred until next user click
          }
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
            showToast(this.mode === 'custom' ? 'Memutar musik latar' : 'Memutar melodi kotak musik romantis', '🎵');
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
         3. SHARED 3D CLAY DRAWING HELPERS (HEART & DETAILED CUPID ILLUSTRATION)
         ---------------------------------------------------------------------- */
      function render3DClayHeart(ctx, x, y, size, colorMain, colorLight, colorShadow, rot = 0, scaleX = 1, scaleY = 1, isHappy = false, happyAlpha = 1) {
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

        // Glossy 3D Specular Highlight
        ctx.save();
        ctx.translate(-s * 0.24, s * 0.26);
        ctx.rotate(-0.45);
        ctx.fillStyle = 'rgba(255, 255, 255, 0.78)';
        ctx.beginPath();
        ctx.ellipse(0, 0, s * 0.14, s * 0.065, 0, 0, Math.PI * 2);
        ctx.fill();
        ctx.restore();

        // Cute Smiling Kawaii Face
        if (isHappy && happyAlpha > 0) {
          ctx.globalAlpha = happyAlpha;
          ctx.strokeStyle = '#4A2335';
          ctx.lineWidth = Math.max(1.6, size * 0.075);
          ctx.lineCap = 'round';

          ctx.beginPath();
          ctx.arc(-s * 0.17, s * 0.52, s * 0.075, Math.PI * 1.15, Math.PI * 1.85);
          ctx.stroke();

          ctx.beginPath();
          ctx.arc(s * 0.17, s * 0.52, s * 0.075, Math.PI * 1.15, Math.PI * 1.85);
          ctx.stroke();

          ctx.fillStyle = 'rgba(255, 105, 135, 0.55)';
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

      /* ======================================================================
         4. STAGE 1: CINEMATIC 3D CLAY CUPID AT BOTTOM-LEFT SHOOTING TO CENTER
         ====================================================================== */
      const stageVideo = document.getElementById('stage-video-intro');
      const stageCover = document.getElementById('stage-cover');
      const stageMain = document.getElementById('stage-main-content');
      const progressBar = document.getElementById('video-progress-bar');
      const btnSkipVideo = document.getElementById('btn-skip-video');
      const btnOpenInvitation = document.getElementById('btn-open-invitation');
      const introStatusLabel = document.getElementById('intro-status-label');
      const introCrystalEmblem = document.getElementById('intro-crystal-emblem');
      const introShockwave = document.getElementById('intro-shockwave');
      const intro3dTypography = document.getElementById('intro-3d-typography');

      let introCompleted = false;
      const introDuration = 7200; // 7.2 detik total untuk 5 babak koreografi mulus
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

      if (btnSkipVideo) {
        btnSkipVideo.addEventListener('click', enterCoverStage);
      }

      function initStage1CupidIntro() {
        const canvas = document.getElementById('intro-cupid-stage-canvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        if (!ctx) return;

        let w = window.innerWidth;
        let h = window.innerHeight;
        let dpr = Math.min(window.devicePixelRatio || 1, 2);

        function resizeIntroCanvas() {
          w = window.innerWidth;
          h = window.innerHeight;
          dpr = Math.min(window.devicePixelRatio || 1, 2);
          canvas.width = Math.floor(w * dpr);
          canvas.height = Math.floor(h * dpr);
          ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        }

        resizeIntroCanvas();
        window.addEventListener('resize', resizeIntroCanvas);

        // Ambient floating 3D pastel hearts in Stage 1
        const ambientHearts = Array.from({ length: 12 }, (_, i) => ({
          x: Math.random() * w,
          y: Math.random() * h,
          size: 12 + Math.random() * 14,
          vy: -(0.35 + Math.random() * 0.4),
          phase: i * 0.8,
          colorMain: i % 3 === 0 ? '#f48fa4' : i % 3 === 1 ? '#f3c677' : '#cbb2f7',
          colorLight: '#ffffff',
          colorShadow: 'rgba(201, 82, 107, 0.25)'
        }));

        const arrowTrail = [];
        const burstParticles = [];
        let heartPlacedTriggered = false;
        let impactTriggered = false;

        function spawnImpactExplosion(cx, cy) {
          const palettes = [
            { main: '#f48fa4', light: '#ffe3ea', shadow: '#c9526b' },
            { main: '#f3c677', light: '#fff8e3', shadow: '#c7933e' },
            { main: '#cbb2f7', light: '#f6f0ff', shadow: '#9577cc' },
            { main: '#ffb5c5', light: '#ffffff', shadow: '#d9738a' }
          ];
          for (let i = 0; i < 36; i++) {
            const angle = (Math.PI * 2 * i) / 36 + (Math.random() - 0.5) * 0.25;
            const speed = 2.6 + Math.random() * 6.8;
            burstParticles.push({
              x: cx,
              y: cy,
              vx: Math.cos(angle) * speed,
              vy: Math.sin(angle) * speed - 1.8,
              size: 10 + Math.random() * 14,
              rot: (Math.random() - 0.5) * 0.5,
              rotSpd: (Math.random() - 0.5) * 0.08,
              alpha: 1,
              isHeart: i % 3 !== 0,
              pal: palettes[i % palettes.length]
            });
          }
        }

        // Draw Large Detailed 3D Clay Cupid (Supports Carrying Heart & Aiming Bow)
        function drawHero3DCupid(cupidX, cupidY, scale, facing, bodyTilt, aimAngle, bowTension, wingPhase, recoilOffset, isCarryingHeart, heartBobY) {
          ctx.save();
          ctx.translate(cupidX - Math.cos(aimAngle) * recoilOffset, cupidY - Math.sin(aimAngle) * recoilOffset);
          ctx.scale(facing * scale, scale);
          ctx.rotate(bodyTilt);

          // 1. Soft 3D Ground/Cloud Shadow beneath Cupid
          ctx.fillStyle = 'rgba(201, 82, 107, 0.16)';
          ctx.beginPath();
          ctx.ellipse(0, 66, 46, 13, 0, 0, Math.PI * 2);
          ctx.fill();

          // 2. Flapping 3D Clay Angel Wings
          const flap = Math.sin(wingPhase) * 0.3;
          ctx.save();
          ctx.translate(-22, -6);
          ctx.rotate(-0.35 + flap);
          const wingGrad = ctx.createLinearGradient(-56, -34, 8, 22);
          wingGrad.addColorStop(0, '#ffffff');
          wingGrad.addColorStop(0.65, '#ffe8f1');
          wingGrad.addColorStop(1, '#f7bfd1');
          ctx.fillStyle = wingGrad;
          ctx.shadowColor = 'rgba(201, 82, 107, 0.22)';
          ctx.shadowBlur = 14;
          ctx.beginPath();
          ctx.ellipse(-28, -10, 34, 18, -0.35, 0, Math.PI * 2);
          ctx.ellipse(-22, 8, 26, 13, -0.1, 0, Math.PI * 2);
          ctx.ellipse(-14, 21, 18, 9, 0.15, 0, Math.PI * 2);
          ctx.fill();
          ctx.restore();

          // 3. Cute Chubby Legs & Feet
          const skinGrad = ctx.createRadialGradient(-10, -20, 4, 0, 0, 48);
          skinGrad.addColorStop(0, '#fff5ef');
          skinGrad.addColorStop(0.65, '#ffdecb');
          skinGrad.addColorStop(1, '#f0b18e');

          ctx.fillStyle = skinGrad;
          ctx.beginPath();
          ctx.ellipse(-12, 46, 9, 15, 0.35, 0, Math.PI * 2);
          ctx.fill();
          ctx.beginPath();
          ctx.ellipse(10, 48, 9.5, 16, -0.25, 0, Math.PI * 2);
          ctx.fill();

          // 4. Chubby 3D Clay Torso & Pastel Cloud Tunic
          ctx.fillStyle = skinGrad;
          ctx.beginPath();
          ctx.ellipse(0, 20, 24, 27, 0.08, 0, Math.PI * 2);
          ctx.fill();

          const tunicGrad = ctx.createLinearGradient(0, 18, 0, 46);
          tunicGrad.addColorStop(0, '#ffffff');
          tunicGrad.addColorStop(1, '#fce4ec');
          ctx.fillStyle = tunicGrad;
          ctx.beginPath();
          ctx.ellipse(0, 32, 25, 16, 0, 0, Math.PI);
          ctx.arc(-14, 28, 10, 0, Math.PI * 2);
          ctx.arc(0, 30, 11, 0, Math.PI * 2);
          ctx.arc(14, 28, 10, 0, Math.PI * 2);
          ctx.fill();

          // Rose-Gold Diagonal Ribbon Sash
          ctx.strokeStyle = '#f28fa3';
          ctx.lineWidth = 7;
          ctx.lineCap = 'round';
          ctx.beginPath();
          ctx.moveTo(-17, 4);
          ctx.lineTo(18, 28);
          ctx.stroke();

          render3DClayHeart(ctx, 2, 13, 10, '#f3c677', '#fff6d1', '#c9933b', 0.2, 1, 1, false, 0);

          // 5. Floating Glowing 3D Golden Halo
          ctx.save();
          ctx.translate(-2, -62 + Math.sin(wingPhase * 0.5) * 3);
          ctx.rotate(-0.12);
          ctx.strokeStyle = '#f3c677';
          ctx.lineWidth = 5.5;
          ctx.shadowColor = 'rgba(243, 198, 119, 0.85)';
          ctx.shadowBlur = 14;
          ctx.beginPath();
          ctx.ellipse(0, 0, 24, 7.5, 0, 0, Math.PI * 2);
          ctx.stroke();
          ctx.restore();

          // 6. Chibi 3D Clay Head
          ctx.save();
          ctx.translate(2, -18);
          ctx.rotate(isCarryingHeart ? 0.06 : aimAngle * 0.28);

          const headGrad = ctx.createRadialGradient(-10, -12, 5, 0, 0, 36);
          headGrad.addColorStop(0, '#fff7f2');
          headGrad.addColorStop(0.65, '#ffdecb');
          headGrad.addColorStop(1, '#efaf8c');
          ctx.fillStyle = headGrad;
          ctx.beginPath();
          ctx.arc(0, 0, 30, 0, Math.PI * 2);
          ctx.fill();

          const curls = [
            { x: -20, y: -20, r: 12.5 },
            { x: -6, y: -27, r: 14 },
            { x: 11, y: -24, r: 12.5 },
            { x: -26, y: -6, r: 11 },
            { x: -23, y: 8, r: 9.5 },
            { x: 22, y: -13, r: 9.5 }
          ];
          curls.forEach((c) => {
            const cGrad = ctx.createRadialGradient(c.x - 3, c.y - 3, 1.5, c.x, c.y, c.r);
            cGrad.addColorStop(0, '#fff4bf');
            cGrad.addColorStop(0.58, '#f3c677');
            cGrad.addColorStop(1, '#c98a32');
            ctx.fillStyle = cGrad;
            ctx.beginPath();
            ctx.arc(c.x, c.y, c.r, 0, Math.PI * 2);
            ctx.fill();
          });

          ctx.fillStyle = '#4A2335';
          ctx.beginPath();
          ctx.arc(11, -1, 4.3, 0, Math.PI * 2);
          ctx.fill();

          ctx.fillStyle = '#ffffff';
          ctx.beginPath();
          ctx.arc(12.5, -2.5, 1.6, 0, Math.PI * 2);
          ctx.fill();

          ctx.strokeStyle = '#9c6334';
          ctx.lineWidth = 2.4;
          ctx.lineCap = 'round';
          ctx.beginPath();
          ctx.moveTo(6, -9);
          ctx.quadraticCurveTo(11, -11.5, 16, -8.5);
          ctx.stroke();

          ctx.fillStyle = 'rgba(255, 118, 145, 0.62)';
          ctx.beginPath();
          ctx.ellipse(16, 7, 7, 4, 0, 0, Math.PI * 2);
          ctx.fill();

          ctx.strokeStyle = '#8c4a58';
          ctx.lineWidth = 2.6;
          ctx.beginPath();
          ctx.arc(8, 8, 5, 0.1 * Math.PI, 0.85 * Math.PI);
          ctx.stroke();

          ctx.restore();

          // 7. Either Carrying Glowing 3D Love Heart (Act 1-2) OR Holding Golden Bow (Act 3-5)
          if (isCarryingHeart) {
            // Golden bow slung across back while carrying heart
            ctx.save();
            ctx.translate(-14, 12);
            ctx.rotate(-0.55);
            ctx.strokeStyle = '#e5a43b';
            ctx.lineWidth = 5;
            ctx.lineCap = 'round';
            ctx.beginPath();
            ctx.moveTo(0, -24);
            ctx.quadraticCurveTo(16, 0, 0, 24);
            ctx.stroke();
            ctx.restore();

            // Glowing Aura behind carried 3D Heart
            ctx.save();
            const aura = ctx.createRadialGradient(34, 8 + heartBobY, 4, 34, 8 + heartBobY, 42);
            aura.addColorStop(0, 'rgba(255, 255, 255, 0.9)');
            aura.addColorStop(0.55, 'rgba(255, 181, 197, 0.55)');
            aura.addColorStop(1, 'rgba(255, 181, 197, 0)');
            ctx.fillStyle = aura;
            ctx.beginPath();
            ctx.arc(34, 8 + heartBobY, 42, 0, Math.PI * 2);
            ctx.fill();
            ctx.restore();

            // Large 3D Clay Heart carried carefully in front
            render3DClayHeart(
              ctx,
              34,
              -12 + heartBobY,
              34,
              '#f28fa3',
              '#ffffff',
              '#c9526b',
              -0.12,
              1,
              1,
              true,
              0.95
            );

            // Chubby Arms Hugging the 3D Heart
            ctx.fillStyle = skinGrad;
            ctx.beginPath();
            ctx.roundRect(8, 4 + heartBobY * 0.5, 24, 11, 5.5);
            ctx.fill();
          } else {
            ctx.save();
            ctx.translate(16, 8);
            ctx.rotate(aimAngle);

            const pullDist = bowTension * 22;

            // Left Arm Holding Bow
            ctx.fillStyle = skinGrad;
            ctx.beginPath();
            ctx.roundRect(0, -6, 30, 12, 6);
            ctx.fill();

            // 3D Golden Curved Bow
            ctx.save();
            ctx.translate(28, 0);
            ctx.strokeStyle = '#e5a43b';
            ctx.lineWidth = 6.5;
            ctx.lineCap = 'round';
            ctx.shadowColor = 'rgba(201, 147, 59, 0.35)';
            ctx.shadowBlur = 10;
            ctx.beginPath();
            ctx.moveTo(-6 - pullDist * 0.25, -34);
            ctx.quadraticCurveTo(20, 0, -6 - pullDist * 0.25, 34);
            ctx.stroke();

            ctx.strokeStyle = '#fff1b8';
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(-4 - pullDist * 0.25, -30);
            ctx.quadraticCurveTo(17, 0, -4 - pullDist * 0.25, 30);
            ctx.stroke();

            render3DClayHeart(ctx, 9, -5, 10, '#e87a90', '#ffd6e0', '#c9526b', -Math.PI / 2, 1, 1, false, 0);

            // Elastic Bowstring
            ctx.strokeStyle = '#ffffff';
            ctx.lineWidth = 2.2;
            ctx.beginPath();
            ctx.moveTo(-6 - pullDist * 0.25, -34);
            ctx.lineTo(-10 - pullDist, 0);
            ctx.lineTo(-6 - pullDist * 0.25, 34);
            ctx.stroke();

            // Loaded 3D Love Arrow
            if (bowTension > 0.02) {
              const arrowBackX = -10 - pullDist;
              const arrowTipX = 36 - pullDist;

              ctx.fillStyle = '#ffb5c5';
              ctx.beginPath();
              ctx.moveTo(arrowBackX + 6, 0);
              ctx.lineTo(arrowBackX - 6, -8);
              ctx.lineTo(arrowBackX, 0);
              ctx.lineTo(arrowBackX - 6, 8);
              ctx.closePath();
              ctx.fill();

              ctx.strokeStyle = '#f3c677';
              ctx.lineWidth = 4;
              ctx.beginPath();
              ctx.moveTo(arrowBackX, 0);
              ctx.lineTo(arrowTipX, 0);
              ctx.stroke();

              render3DClayHeart(ctx, arrowTipX + 8, 0, 15, '#e87a90', '#ffe3ea', '#c9526b', -Math.PI / 2, 1, 1, false, 0);
            }
            ctx.restore();

            // Right Arm Pulling Bowstring
            ctx.fillStyle = skinGrad;
            ctx.beginPath();
            ctx.roundRect(-4, -3, 24 - pullDist * 0.75, 11, 5.5);
            ctx.fill();

            ctx.restore();
          }

          ctx.restore();
        }

        // Draw Flying 3D Heart Arrow with Golden Sparkle Trail
        function drawFlying3DArrow(ax, ay, angle, trail) {
          // Glowing Sparkle Ribbon Trail
          for (let i = 0; i < trail.length; i++) {
            const pt = trail[i];
            const ratio = (i + 1) / trail.length;
            ctx.save();
            ctx.fillStyle = i % 2 === 0
              ? `rgba(243, 198, 119, ${ratio * 0.78})`
              : `rgba(255, 158, 177, ${ratio * 0.72})`;
            ctx.beginPath();
            ctx.arc(pt.x, pt.y, ratio * 6.5, 0, Math.PI * 2);
            ctx.fill();
            ctx.restore();
          }

          ctx.save();
          ctx.translate(ax, ay);
          ctx.rotate(angle);

          // Pink & White Feather Fletching
          ctx.fillStyle = '#ffd1dc';
          ctx.beginPath();
          ctx.moveTo(-24, 0);
          ctx.lineTo(-38, -10);
          ctx.lineTo(-30, 0);
          ctx.lineTo(-38, 10);
          ctx.closePath();
          ctx.fill();

          // Golden Shaft
          ctx.strokeStyle = '#f3c677';
          ctx.lineWidth = 4.5;
          ctx.lineCap = 'round';
          ctx.beginPath();
          ctx.moveTo(-30, 0);
          ctx.lineTo(18, 0);
          ctx.stroke();

          // 3D Clay Heart Tip
          render3DClayHeart(ctx, 26, 0, 18, '#e87a90', '#ffe6ed', '#c9526b', -Math.PI / 2, 1, 1, false, 0);
          ctx.restore();
        }

        function animateStage1(now) {
          if (introCompleted) return;
          requestAnimationFrame(animateStage1);

          const elapsed = now - introStart;
          const progress = Math.min(1, elapsed / introDuration);
          if (progressBar) progressBar.style.width = (progress * 100).toFixed(1) + '%';

          ctx.clearRect(0, 0, w, h);

          // 1. Ambient Floating Pastel Hearts
          ambientHearts.forEach((ah) => {
            ah.phase += 0.025;
            ah.y += ah.vy;
            ah.x += Math.sin(ah.phase) * 0.45;
            if (ah.y < -30) ah.y = h + 30;
            render3DClayHeart(ctx, ah.x, ah.y, ah.size, ah.colorMain, ah.colorLight, ah.colorShadow, Math.sin(ah.phase) * 0.15);
          });

          // 2. Responsive Anchor Coordinates
          const isMobile = w < 640;
          const cupidScale = isMobile ? 0.9 : Math.min(1.38, Math.max(1.08, w / 940));
          const cornerX = isMobile ? Math.max(66, w * 0.17) : Math.max(115, w * 0.14);
          const cornerY = isMobile ? h - Math.max(92, h * 0.14) : h - Math.max(125, h * 0.17);

          const targetX = w * 0.5;
          const targetY = h * 0.41;
          const centerCarryX = targetX - 42 * cupidScale;
          const centerCarryY = targetY + 12 * cupidScale;

          const aimAngle = Math.atan2(targetY - cornerY, targetX - cornerX);

          // Helper smoothstep easing
          const easeInOutCubic = (t) => (t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2);

          // 3. 5-ACT CINEMATIC CHOREOGRAPHY:
          //    Babak 1 (0ms - 1450ms)   : Cupid terbang dari atas menuju tengah layar membawa Hati 3D
          //    Babak 2 (1450ms - 2150ms): Cupid meletakkan Hati 3D tepat di tengah layar
          //    Babak 3 (2150ms - 3350ms): Cupid berbalik arah & terbang mulus ke pojok kiri bawah layar
          //    Babak 4 (3350ms - 4450ms): Dari pojok kiri bawah, Cupid membidik & menarik busur emas ke tengah
          //    Babak 5 (4450ms - 7200ms): Panah melesat ke tengah -> Hati meledak indah -> Teks 3D muncul!
          let currentCupidX = cornerX;
          let currentCupidY = cornerY;
          let facing = 1;
          let bodyTilt = 0;
          let bowTension = 0;
          let recoilOffset = 0;
          let isCarryingHeart = false;
          let heartBobY = Math.sin(elapsed * 0.008) * 3;

          if (elapsed < 1450) {
            // BABAK 1: Terbang dari atas menuju tengah membawa Hati 3D
            const t = easeInOutCubic(elapsed / 1450);
            const startX = targetX + (isMobile ? 75 : 130);
            const startY = -95;
            currentCupidX = startX + (centerCarryX - startX) * t - Math.sin(t * Math.PI) * 32;
            currentCupidY = startY + (centerCarryY - startY) * t;
            facing = 1;
            bodyTilt = (1 - t) * 0.18;
            isCarryingHeart = true;

            if (introStatusLabel) {
              introStatusLabel.textContent = 'Kupido membawa cinta ke tengah...';
            }
          } else if (elapsed < 2150) {
            // BABAK 2: Meletakkan / menyimpan Hati 3D di tengah layar
            const t = (elapsed - 1450) / 700;
            currentCupidX = centerCarryX - Math.sin(t * Math.PI * 0.5) * 14;
            currentCupidY = centerCarryY + Math.sin(t * Math.PI) * 8;
            facing = 1;
            bodyTilt = Math.sin(t * Math.PI) * 0.1;
            isCarryingHeart = t < 0.45;
            heartBobY = t * 10;

            if (!heartPlacedTriggered && t >= 0.45) {
              heartPlacedTriggered = true;
              if (introCrystalEmblem) introCrystalEmblem.classList.add('is-placed');
              // Soft sparkle dust saat hati disimpan di tengah
              for (let s = 0; s < 14; s++) {
                const a = (Math.PI * 2 * s) / 14;
                burstParticles.push({
                  x: targetX,
                  y: targetY,
                  vx: Math.cos(a) * 2.2,
                  vy: Math.sin(a) * 2.2,
                  size: 7,
                  rot: 0,
                  rotSpd: 0.05,
                  alpha: 0.9,
                  isHeart: false,
                  pal: { main: '#f3c677', light: '#ffffff', shadow: '#c9933b' }
                });
              }
            }

            if (introStatusLabel) {
              introStatusLabel.textContent = 'Menyimpan hati di pusat layar...';
            }
          } else if (elapsed < 3350) {
            // BABAK 3: Terbang mundur/menukik ke pojok kiri bawah device
            const t = easeInOutCubic((elapsed - 2150) / 1200);
            const startX = centerCarryX - 14;
            const startY = centerCarryY;
            // Curved graceful flight arc toward bottom-left corner
            currentCupidX = startX + (cornerX - startX) * t - Math.sin(t * Math.PI) * (isMobile ? 28 : 55);
            currentCupidY = startY + (cornerY - startY) * t - Math.sin(t * Math.PI) * 35;
            facing = t < 0.72 ? -1 : 1; // Menghadap arah terbang lalu berbalik menghadap tengah layar
            bodyTilt = t < 0.72 ? 0.16 : -0.08 * (1 - t);
            isCarryingHeart = false;

            if (introStatusLabel) {
              introStatusLabel.textContent = 'Bersiap di pojok kiri bawah...';
            }
          } else if (elapsed < 4450) {
            // BABAK 4: Membidik & menarik tali busur emas dari pojok kiri bawah
            const t = (elapsed - 3350) / 1100;
            currentCupidX = cornerX;
            currentCupidY = cornerY + Math.sin(elapsed * 0.006) * 5;
            facing = 1;
            bodyTilt = 0;
            bowTension = Math.min(1, t * t * (3 - 2 * t));

            if (introStatusLabel) {
              introStatusLabel.textContent = 'Membidik panah cinta...';
            }
          } else if (elapsed < 5000) {
            // BABAK 5A: Panah cinta melesat diagonal dari pojok kiri bawah ke tengah layar
            const flightRatio = (elapsed - 4450) / 550;
            const easeFlight = 1 - Math.pow(1 - flightRatio, 2);
            recoilOffset = Math.sin(Math.min(1, flightRatio * 3) * Math.PI) * 14;

            currentCupidX = cornerX;
            currentCupidY = cornerY + Math.sin(elapsed * 0.006) * 5;
            facing = 1;

            const startArrowX = currentCupidX + Math.cos(aimAngle) * 48 * cupidScale;
            const startArrowY = currentCupidY + Math.sin(aimAngle) * 48 * cupidScale;
            const arrowX = startArrowX + (targetX - startArrowX) * easeFlight;
            const arrowY = startArrowY + (targetY - startArrowY) * easeFlight - Math.sin(flightRatio * Math.PI) * 26;

            arrowTrail.push({ x: arrowX, y: arrowY });
            if (arrowTrail.length > 18) arrowTrail.shift();

            drawFlying3DArrow(arrowX, arrowY, aimAngle, arrowTrail);

            if (introStatusLabel) {
              introStatusLabel.textContent = 'Panah cinta melesat!';
            }
          } else {
            // BABAK 5B: Panah tepat mengenai Hati 3D di tengah -> Ledakan kilau & Teks 3D muncul!
            currentCupidX = cornerX;
            currentCupidY = cornerY + Math.sin(elapsed * 0.006) * 5;
            facing = 1;

            if (!impactTriggered) {
              impactTriggered = true;
              spawnImpactExplosion(targetX, targetY);

              if (introCrystalEmblem) introCrystalEmblem.classList.add('is-struck');
              if (introShockwave) introShockwave.classList.add('is-bursting');
              if (intro3dTypography) intro3dTypography.classList.add('is-revealed');
              if (introStatusLabel) introStatusLabel.textContent = CONFIG.couplePlain + ' 💕';
            }

            if (arrowTrail.length > 0) {
              arrowTrail.shift();
              for (let i = 0; i < arrowTrail.length; i++) {
                const pt = arrowTrail[i];
                const ratio = (i + 1) / arrowTrail.length;
                ctx.fillStyle = `rgba(243, 198, 119, ${ratio * 0.5})`;
                ctx.beginPath();
                ctx.arc(pt.x, pt.y, ratio * 5, 0, Math.PI * 2);
                ctx.fill();
              }
            }
          }

          // Render the 3D Clay Cupid
          drawHero3DCupid(
            currentCupidX,
            currentCupidY,
            cupidScale,
            facing,
            bodyTilt,
            aimAngle,
            bowTension,
            elapsed * 0.017,
            recoilOffset,
            isCarryingHeart,
            heartBobY
          );

          // 4. Render Impact Explosion Hearts & Sparkles
          for (let i = burstParticles.length - 1; i >= 0; i--) {
            const p = burstParticles[i];
            p.x += p.vx;
            p.y += p.vy;
            p.vy += 0.14;
            p.vx *= 0.97;
            p.rot += p.rotSpd;
            p.alpha -= 0.013;

            if (p.alpha <= 0) {
              burstParticles.splice(i, 1);
              continue;
            }

            ctx.save();
            ctx.globalAlpha = p.alpha;
            if (p.isHeart) {
              render3DClayHeart(ctx, p.x, p.y, p.size, p.pal.main, p.pal.light, p.pal.shadow, p.rot);
            } else {
              ctx.fillStyle = p.pal.light;
              ctx.beginPath();
              ctx.arc(p.x, p.y, p.size * 0.35, 0, Math.PI * 2);
              ctx.fill();
            }
            ctx.restore();
          }

          if (elapsed >= introDuration) {
            enterCoverStage();
          }
        }

        requestAnimationFrame(animateStage1);
      }

      initStage1CupidIntro();

      /* ----------------------------------------------------------------------
         5. AMBIENT 3D CLAY CUPID & BOUNCING PASTEL HEARTS ENGINE (STAGE 2 & 3)
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
          wingGrad.addColorStop(1, '#ffe6f0');
          ctx.fillStyle = wingGrad;
          ctx.shadowColor = 'rgba(201, 82, 107, 0.18)';
          ctx.shadowBlur = 8;
          ctx.beginPath();
          ctx.ellipse(-14, -4, 18, 10, -0.3, 0, Math.PI * 2);
          ctx.ellipse(-10, 5, 13, 7, -0.1, 0, Math.PI * 2);
          ctx.fill();
          ctx.restore();

          ctx.save();
          ctx.translate(0, -34 + Math.sin(cupid.wingPhase * 0.5) * 2);
          ctx.strokeStyle = '#f3c677';
          ctx.lineWidth = 3.2;
          ctx.shadowColor = 'rgba(243, 198, 119, 0.7)';
          ctx.shadowBlur = 8;
          ctx.beginPath();
          ctx.ellipse(0, 0, 14, 4.5, 0, 0, Math.PI * 2);
          ctx.stroke();
          ctx.restore();

          const bodyGrad = ctx.createRadialGradient(-4, 6, 2, 0, 10, 20);
          bodyGrad.addColorStop(0, '#fff4ed');
          bodyGrad.addColorStop(1, '#f4be9f');
          ctx.fillStyle = bodyGrad;
          ctx.beginPath();
          ctx.ellipse(0, 12, 13, 15, 0, 0, Math.PI * 2);
          ctx.fill();

          ctx.fillStyle = '#ffffff';
          ctx.beginPath();
          ctx.ellipse(0, 19, 13.5, 8.5, 0, 0, Math.PI);
          ctx.fill();

          ctx.strokeStyle = '#f28fa3';
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
            cGrad.addColorStop(0, '#fff0b3');
            cGrad.addColorStop(0.6, '#f3c677');
            cGrad.addColorStop(1, '#cf8f36');
            ctx.fillStyle = cGrad;
            ctx.beginPath();
            ctx.arc(c.x, c.y, c.r, 0, Math.PI * 2);
            ctx.fill();
          });

          ctx.fillStyle = '#4A2335';
          ctx.beginPath();
          ctx.arc(6, -10, 2.5, 0, Math.PI * 2);
          ctx.fill();

          ctx.fillStyle = '#ffffff';
          ctx.beginPath();
          ctx.arc(6.8, -10.8, 0.9, 0, Math.PI * 2);
          ctx.fill();

          ctx.fillStyle = 'rgba(255, 120, 145, 0.6)';
          ctx.beginPath();
          ctx.ellipse(9, -5.5, 4, 2.3, 0, 0, Math.PI * 2);
          ctx.fill();

          ctx.strokeStyle = '#8c4a58';
          ctx.lineWidth = 1.8;
          ctx.lineCap = 'round';
          ctx.beginPath();
          ctx.arc(4, -5.5, 3.2, 0.1 * Math.PI, 0.85 * Math.PI);
          ctx.stroke();

          const pullOffset = cupid.drawTension * 11;
          ctx.save();
          ctx.translate(16, 6);

          ctx.strokeStyle = '#e5a94c';
          ctx.lineWidth = 3.5;
          ctx.lineCap = 'round';
          ctx.beginPath();
          ctx.moveTo(-2 - pullOffset * 0.25, -18);
          ctx.quadraticCurveTo(12, 0, -2 - pullOffset * 0.25, 18);
          ctx.stroke();

          ctx.strokeStyle = '#fff5d6';
          ctx.lineWidth = 1.4;
          ctx.beginPath();
          ctx.moveTo(-2 - pullOffset * 0.25, -18);
          ctx.lineTo(-4 - pullOffset, 0);
          ctx.lineTo(-2 - pullOffset * 0.25, 18);
          ctx.stroke();

          if (cupid.drawTension > 0.05) {
            ctx.strokeStyle = '#f3c677';
            ctx.lineWidth = 2.2;
            ctx.beginPath();
            ctx.moveTo(-4 - pullOffset, 0);
            ctx.lineTo(18 - pullOffset, 0);
            ctx.stroke();

            ctx.fillStyle = '#e87a90';
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
          { main: '#f48fa4', light: '#ffe3ea', shadow: '#c9526b' },
          { main: '#ffb5c5', light: '#fff0f4', shadow: '#d9738a' },
          { main: '#f7b29c', light: '#fff2ed', shadow: '#cf7d65' },
          { main: '#cbb2f7', light: '#f6f0ff', shadow: '#9577cc' },
          { main: '#f3c677', light: '#fff8e3', shadow: '#c7933e' }
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
          const colors = ['#f3c677', '#ff8fa3', '#ffffff', '#cbb2f7'];
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
          if (!introCompleted) return;
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

            render3DClayHeart(
              ctx,
              h.x,
              h.y,
              h.size,
              h.pal.main,
              h.pal.light,
              h.pal.shadow,
              h.rot + Math.sin(h.phase) * 0.08,
              h.scaleX,
              h.scaleY,
              h.isHappy,
              Math.min(1, h.happyTimer / 30)
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
              ctx.fillStyle = `rgba(243, 198, 119, ${ratio * 0.55})`;
              ctx.beginPath();
              ctx.arc(pt.x, pt.y, ratio * 3.2, 0, Math.PI * 2);
              ctx.fill();
            }

            const angle = Math.atan2(a.vy, a.vx);
            ctx.save();
            ctx.translate(a.x, a.y);
            ctx.rotate(angle);

            ctx.fillStyle = '#ffd1dc';
            ctx.beginPath();
            ctx.moveTo(-14, 0);
            ctx.lineTo(-20, -4.5);
            ctx.lineTo(-16, 0);
            ctx.lineTo(-20, 4.5);
            ctx.closePath();
            ctx.fill();

            ctx.strokeStyle = '#e5a94c';
            ctx.lineWidth = 2.4;
            ctx.beginPath();
            ctx.moveTo(-15, 0);
            ctx.lineTo(10, 0);
            ctx.stroke();

            ctx.fillStyle = '#e87a90';
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
         6. CELEBRATION CONFETTI BURST (ON "BUKA UNDANGAN")
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

        const colors = ['#e87a90', '#ffb5c5', '#f3c677', '#cbb2f7', '#ff8fa3'];
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
         7. STAGE 2 -> STAGE 3 COVER OPENING TRANSITION
         ---------------------------------------------------------------------- */
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
         8. DYNAMIC GUEST NAME (?to=) & MODAL SIMULATOR
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
         9. 3D TILT INTERACTION ON COVER & PROFILE CARDS
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
         10. REAL-TIME COUNTDOWN TIMER
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
         11. SAVE THE DATE (.ICS) & CLIPBOARD COPY
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

      /* ----------------------------------------------------------------------
         12. INTERACTIVE RSVP & GUESTBOOK WISHES (AJAX TERINTEGRASI)
         ---------------------------------------------------------------------- */
      const wishesListEl = document.getElementById('wishes-list');
      const rsvpForm = document.getElementById('rsvp-form');
      const rsvpSubmitBtn = document.getElementById('btn-submit-rsvp');

      const seedWishes = [
        {
          guest_name: 'Nabila Putri',
          attendance: 'hadir',
          guests: '2 Orang',
          message: 'Selamat menempuh hidup baru Aulia & Naufal! Semoga menjadi keluarga yang sakinah, mawaddah, warahmah, dan selalu bahagia 💕',
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
          .toUpperCase() || '💖';

        const isComing = item.attendance === 'hadir' || item.attendance === 'Hadir';
        const badgeBg = isComing ? '#ffe3ec' : '#fff0d6';
        const badgeColor = isComing ? '#c9526b' : '#9c6615';
        const guestSuffix = item.guests ? ' (' + escapeHtml(item.guests) + ')' : '';

        const card = document.createElement('div');
        card.className = 'clay-card';
        card.style.cssText = 'padding:1rem 1.15rem;display:flex;align-items:flex-start;gap:0.85rem;transition:all 0.45s ease;';
        card.innerHTML = `
          <div class="font-cute" style="width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,#e87a90,#ffd5c6);color:#ffffff;font-weight:700;font-size:0.85rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            ${escapeHtml(initials)}
          </div>
          <div style="flex:1;min-width:0;">
            <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:0.4rem;">
              <h4 class="font-cute" style="font-weight:700;font-size:0.95rem;color:var(--berry-text);">${escapeHtml(name)}</h4>
              <span style="padding:0.18rem 0.65rem;border-radius:9999px;font-size:0.7rem;font-weight:700;background:${badgeBg};color:${badgeColor};">
                ${isComing ? '💖 Hadir' + guestSuffix : '🌸 Berhalangan'}
              </span>
            </div>
            <p style="font-size:0.84rem;color:var(--berry-soft);margin-top:0.35rem;word-break:break-word;">
              ${escapeHtml(item.message)}
            </p>
            <span style="display:block;font-size:0.68rem;color:var(--berry-soft);opacity:0.75;margin-top:0.4rem;">
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
         13. SCROLL REVEAL & MUSIC BUTTON
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