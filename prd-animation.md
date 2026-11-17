# Product Requirements Document (PRD)

## Module: Interactive UI Ornaments & Organic Motion System for Digital Invitations

| Document Metadata | Details |
| :--- | :--- |
| **Feature Name** | Interactive UI Ornaments & Organic Romantic Motion System |
| **Target Event Categories** | **Strictly `wedding` & `engagement` only** |
| **Tech Stack** | Laravel (Blade), Vite, Tailwind CSS, Vanilla JavaScript (ES6+) |
| **Primary Objective** | Deliver fluid, natural, physics-inspired motion and interactive UI components with 60 FPS mobile performance, avoiding stiff linear animations. |

---

## 1. Architecture & Conditional Activation Rules

### 1.1. Blade / Backend Conditional Rendering
The Laravel Blade template must pass the event category safely to the DOM (e.g., via a `data-event-type` attribute on the `<body>` or root wrapper `#invitation-app`).
* **Activation Rule:** Romantic ornaments (floral corners, arch frames) and organic ambient motion (butterflies, swaying leaves) **MUST ONLY** render and initialize when `data-event-type` is strictly `"wedding"` or `"engagement"`.
* **Fallback Rule:** For any other event category (e.g., `"corporate"`, `"seminar"`, `"birthday"`, `"aqiqah"`), Blade must completely omit the butterfly container and floral SVG wrappers from the DOM, and JS motion modules must abort initialization early.

### 1.2. Frontend File & Asset Organization (Laravel + Vite)
* **Blade Components:** `resources/views/components/invitation/`
  * `music-player.blade.php`
  * `bottom-nav.blade.php`
  * `ambient-butterflies.blade.php`
  * `floral-ornaments.blade.php`
* **CSS / Tailwind Extensions:** `resources/css/motion.css` (imported into `app.css` and compiled via Vite).
* **JavaScript Modules:** `resources/js/invitation/`
  * `motion-engine.js` (IntersectionObserver scroll reveals & parallax)
  * `butterfly-system.js` (Organic flight path generator)
  * `ui-interactions.js` (Audio controller, Scroll-Spy nav, Copy-to-Clipboard)

---

## 2. Functional Scope 1: Interactive UI Ornaments & Components

### 2.1. Floating Vinyl Music Player
* **Position & Layering:** Fixed at bottom-right above the navigation bar (`fixed bottom-20 right-4 z-50`).
* **Visual Design:** `42px x 42px` circular glassmorphic button (`backdrop-blur-md bg-white/30 border border-white/40 shadow-lg`).
* **Behavior & State:**
  * Triggers `audio.play()` automatically upon clicking the **"Open Invitation"** button on the cover gate (satisfying browser autoplay policies via user gesture).
  * Applies continuous smooth rotation (`animate-spin-slow`: `8s linear infinite`) when playing; pauses rotation smoothly (`animation-play-state: paused;`) without snapping back to `0deg` when muted.

### 2.2. Mobile-First Glassmorphic Bottom Navigation Bar
* **Position & Layout:** Centered floating pill (`fixed bottom-4 left-1/2 -translate-x-1/2 z-50 w-[92%] max-w-md rounded-full backdrop-blur-lg bg-white/75 shadow-xl border border-white/50`).
* **Navigation Items:** Maximum 5 items with crisp SVG icons and micro-labels (`10px`): `Cover`, `Couple`, `Events`, `Gallery`, `RSVP`.
* **JavaScript Behavior:**
  * Uses `element.scrollIntoView({ behavior: 'smooth' })` on click.
  * Implements an `IntersectionObserver` Scroll-Spy (`rootMargin: '-40% 0px -40% 0px'`) to dynamically highlight the active section pill with a smooth background transition.

### 2.3. Corner Ornaments, Dividers & Photo Masking
* **Asset Constraints:** Inline `<svg>` or optimized `.webp` files (`< 40KB` each) positioned using `pointer-events-none select-none`.
* **Photo Masking:** Custom CSS/Tailwind utility classes for Arch (`rounded-t-full`) and Oval frames with subtle double-ring borders (`ring-1 ring-offset-4`).

### 2.4. Smart Copy-to-Clipboard & Toast Feedback
* **Behavior:** Clicking any `.btn-copy` button reads `data-copy-target`, executes `navigator.clipboard.writeText()`, and triggers a non-blocking floating toast notification at the top viewport (`translateY(-20px)` to `translateY(0)` with spring easing) for `2.5 seconds`.

---

## 3. Functional Scope 2: Natural & Organic Motion System

To eliminate robotic, linear, or stiff movements, all animations **MUST** adhere to custom `cubic-bezier` curves, nested transform hierarchies, and asynchronous timing offsets.

### 3.1. Organic Butterfly Flight System (3 to 5 Butterflies Max)
To make butterflies look genuinely alive rather than sliding statically across the screen, each butterfly **MUST** use a **3-layer nested DOM structure** so wing flapping, body banking/tilting, and flight trajectory operate independently:

```html
<!-- Required Nested Architecture per Butterfly -->
<div class="butterfly-flight-path"> <!-- Layer 1: JS/CSS curved trajectory & drift -->
  <div class="butterfly-body-tilt"> <!-- Layer 2: Organic pitch, roll & micro-bobbing -->
    <svg class="butterfly-wings"> <!-- Layer 3: Asymmetric 3D wing flap -->
      <g class="wing-left">...</g>
      <g class="wing-right">...</g>
    </svg>
  </div>
</div>
```

* **Wing Flap Physics (Layer 3):**
  * Avoid uniform `scaleX` loops. Use `perspective(400px) rotateY()` (`-65deg` to `20deg`) with a fast downstroke and slightly slower upstroke (`cubic-bezier(0.45, 0.05, 0.55, 0.95)`).
  * Vary flap speed dynamically (`0.28s` to `0.45s` per flap)—flapping faster when ascending and gliding briefly (`animation-play-state` or slowed keyframes) when descending.
* **Body Tilt & Micro-Turbulence (Layer 2):**
  * Apply subtle vertical bobbing (`translateY(-4px)` to `translateY(4px)`) synchronized inversely with wing downstrokes, plus subtle banking angles (`rotateZ(-12deg)` to `rotateZ(12deg)`) when turning.
* **Curved Trajectory & Desynchronization (Layer 1):**
  * Strictly limit active instances to **3–5 butterflies** (`opacity: 0.75` to `0.9`, size `20px` to `30px`).
  * Use multi-stop `@keyframes` with `translate3d(x, y, 0)` and `cubic-bezier(0.42, 0, 0.58, 1)` along S-curve paths across the viewport over `14s` to `22s`.
  * **Crucial:** Assign prime-number durations and negative `animation-delay` values to each butterfly so their flight paths and wing flaps **never** synchronize.
  * Must include `pointer-events: none` and `z-index: 40` so they float gracefully over content without blocking clicks.

### 3.2. Multi-Layered Botanical Wind Sway (Floral Ornaments)
Instead of rotating an entire flower cluster as a single rigid block (which looks artificial), botanical ornaments must simulate real wind physics:
* **Anchor Point:** Set `transform-origin` strictly at the stem base (e.g., `bottom left` or `top right`).
* **Layered Asynchronous Sway:** Split floral SVGs into at least 2–3 sub-layers (`<g class="leaf-back">`, `<g class="flower-main">`, `<g class="leaf-front">`).
  * Animate each layer with slightly different rotation arcs (`-2.5deg` to `3.5deg`), different durations (`4.5s`, `5.8s`, `7.1s`), and `cubic-bezier(0.37, 0, 0.63, 1)` so leaves rustle organically against each other.

### 3.3. Fluid Cover Gate Transition & Scroll Lock
* **Initial State:** `body` has `overflow-hidden h-screen`.
* **Opening Sequence (Triggered on "Open Invitation" click):**
  1. Cover gate animates upward using `transform: translate3d(0, -100%, 0)` with an exponential deceleration curve (`cubic-bezier(0.76, 0, 0.24, 1)`, duration `900ms`) while simultaneously scaling inner cover typography slightly (`scale(1.04)`) and fading opacity to `0`.
  2. At `450ms` into the cover exit, unlock `body` scroll, trigger background audio fade-in, reveal the Bottom Nav & Floating Music Player (`translateY(20px)` to `0`), and initialize the `butterfly-system.js` module.

### 3.4. Scroll-Triggered Reveal Animations (`IntersectionObserver`)
* **Physics Curve:** Avoid default `ease` or `linear`. Use a custom silk-smooth deceleration curve: `cubic-bezier(0.22, 1, 0.36, 1)` (ease-out-quint feel) over `850ms` to `1000ms`.
* **Animation Variants (via `data-reveal` attributes):**
  * `data-reveal="fade-up"`: `translate3d(0, 28px, 0)` + `opacity: 0` $\rightarrow$ `translate3d(0, 0, 0)` + `opacity: 1`.
  * `data-reveal="zoom-soft"`: `scale3d(0.94, 0.94, 1)` + `opacity: 0` $\rightarrow$ `scale3d(1, 1, 1)` + `opacity: 1`.
  * `data-reveal="mask-reveal"`: Clip-path or vertical uncurtain for calligraphic headings.
* **Natural Staggering:** Elements inside a `[data-reveal-group]` container automatically receive incremental `transition-delay` values (`120ms` steps) calculated via JS so cards, names, and timelines cascade effortlessly.
* **Single Execution:** Disconnect observer per element once revealed (`observer.unobserve(entry.target)`) to prevent distracting re-animations when scrolling back up.

---

## 4. Performance, Rendering & Accessibility Standards

1. **Composite-Only Properties:** Every animation in `motion.css` and JS **MUST** exclusively animate `transform` (`translate3d`, `scale3d`, `rotate`, `rotateY`) and `opacity`. Animating `top`, `left`, `margin`, `width`, or `box-shadow` is strictly prohibited to guarantee 60 FPS on mid-range mobile devices.
2. **Layer Promotion:** Apply `will-change: transform, opacity` selectively on active moving elements (butterflies and cover gate) and remove `will-change` after scroll-reveal transitions finish to conserve GPU memory.
3. **Accessibility (`prefers-reduced-motion`):** Wrap all continuous ambient animations (butterflies, swaying flowers, vinyl spin) inside `@media (prefers-reduced-motion: no-preference)`. When reduced motion is preferred, gracefully fall back to static decorative placement and instant opacity fades.
4. **Vite Bundling:** Ensure all motion JS modules use clean ES imports and lazy-initialize after `DOMContentLoaded` and Cover Gate interaction so First Contentful Paint (FCP) remains under `1.2s`.

---

## 5. Acceptance Criteria (QA Checklist)

- [ ] **Conditional Isolation:** Butterflies and floral sway animations render and run *only* when Laravel passes `wedding` or `engagement` as the event type; zero butterfly DOM nodes exist for `corporate` or other events.
- [ ] **Organic Butterfly Physics:** Butterflies exhibit independent 3D wing flapping, subtle vertical bobbing, and non-linear S-curve flight paths without synchronized/robotic looping.
- [ ] **Multi-Layer Wind Sway:** Corner floral SVGs sway with layered, asynchronous leaf/flower movement anchored strictly at the stem base.
- [ ] **Zero Click Interference:** Tapping buttons (RSVP, Google Maps, Copy Account) while a butterfly passes directly over them works 100% reliably (`pointer-events: none`).
- [ ] **Smooth Cover-to-Content Handover:** Clicking "Open Invitation" smoothly lifts the cover (`cubic-bezier(0.76, 0, 0.24, 1)`), starts music playback, unlocks scrolling, and spawns the ambient butterflies without frame drops.
- [ ] **60 FPS Mobile Performance:** Chrome DevTools Performance profiler confirms zero layout thrashing and steady 60 FPS scrolling on mobile emulation.
