@props(['eventType'])

@if (in_array($eventType, ['wedding', 'engagement'], true))
<div data-butterfly-container aria-hidden="true" class="pointer-events-none fixed inset-0 z-35 overflow-hidden select-none"></div>

<style>
    /* Container & Creature Core */
    [data-butterfly-container] {
        perspective: 800px;
        contain: layout style size;
    }

    .bf-creature {
        position: fixed;
        left: 0;
        top: 0;
        width: 60px;
        height: 60px;
        margin-left: -30px;
        margin-top: -30px;
        pointer-events: none;
        will-change: transform;
        transform-style: preserve-3d;
    }

    .bf-scaler {
        width: 100%;
        height: 100%;
        position: relative;
        transform-style: preserve-3d;
    }

    /* 3D Body Assembly */
    .bf-body-3d {
        position: relative;
        width: 100%;
        height: 100%;
        transform-style: preserve-3d;
    }

    /* Central Body (Thorax, Abdomen, Antennae) */
    .bf-torso {
        position: absolute;
        left: 50%;
        top: 50%;
        width: 14px;
        height: 42px;
        transform: translate(-50%, -50%);
        z-index: 3;
        filter: drop-shadow(0 1px 2px rgba(0,0,0,0.3));
    }
    .bf-torso svg {
        width: 100%;
        height: 100%;
        display: block;
    }

    /* 3D Hinged Wings */
    .bf-wing {
        position: absolute;
        top: 50%;
        width: 44px;
        height: 56px;
        margin-top: -28px;
        transform-style: preserve-3d;
        will-change: transform;
    }

    /* Left wing: sits left of torso, hinged on right edge */
    .bf-wing-left {
        right: 50%;
        margin-right: -2px;
        transform-origin: 100% 46%;
    }

    /* Right wing: sits right of torso, hinged on left edge */
    .bf-wing-right {
        left: 50%;
        margin-left: -2px;
        transform-origin: 0% 46%;
    }

    .bf-wing svg {
        width: 100%;
        height: 100%;
        display: block;
        overflow: visible;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.18));
    }

    /* Wing Flap Animations (Fast realistic flutter in 3D) */
    .bf-creature.is-flapping .bf-wing-left {
        animation: bfFlapLeft 0.17s cubic-bezier(0.45, 0.05, 0.55, 0.95) infinite alternate;
    }
    .bf-creature.is-flapping .bf-wing-right {
        animation: bfFlapRight 0.17s cubic-bezier(0.45, 0.05, 0.55, 0.95) infinite alternate;
    }

    @keyframes bfFlapLeft {
        0%   { transform: rotateY(-18deg) rotateZ(-3deg); }
        100% { transform: rotateY(74deg)  rotateZ(5deg);  }
    }

    @keyframes bfFlapRight {
        0%   { transform: rotateY(18deg)  rotateZ(3deg);  }
        100% { transform: rotateY(-74deg) rotateZ(-5deg); }
    }

    /* Glide State (Held in an elegant dihedral V-shape) */
    .bf-creature.is-gliding .bf-wing-left {
        transform: rotateY(24deg) rotateZ(2deg);
        transition: transform 0.28s ease-out;
    }
    .bf-creature.is-gliding .bf-wing-right {
        transform: rotateY(-24deg) rotateZ(-2deg);
        transition: transform 0.28s ease-out;
    }

    /* Depth of Field tiers */
    .bf-creature.is-depth-bg {
        filter: blur(0.4px);
    }
    .bf-creature.is-depth-fg {
        filter: drop-shadow(0 2px 6px rgba(0,0,0,0.3));
    }
</style>

<script>
(function () {
    'use strict';

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', startButterflies);
    } else {
        startButterflies();
    }

    function startButterflies() {
        const container = document.querySelector('[data-butterfly-container]');
        if (!container || container.dataset.initialized) return;
        container.dataset.initialized = 'true';

        // Organic color palettes suited for luxury/wedding themes (Gold, Champagne, Warm Rose, Golden Amber)
        const PALETTES = [
            {
                body: '#7A5C2E',
                accent: '#E6CA85',
                wingGrad1: '#F4DFB0',
                wingGrad2: '#C9A35C',
                vein: '#8A6A32',
                dust: '#FFF3D6'
            },
            {
                body: '#6E4E37',
                accent: '#D9A779',
                wingGrad1: '#F5DAC1',
                wingGrad2: '#BF8A5C',
                vein: '#7D5333',
                dust: '#FFF0E4'
            },
            {
                body: '#82652B',
                accent: '#F3D27E',
                wingGrad1: '#FAEDB8',
                wingGrad2: '#D4AF37',
                vein: '#94722E',
                dust: '#FFF9E0'
            },
            {
                body: '#5E4A35',
                accent: '#C5A059',
                wingGrad1: '#EADBB6',
                wingGrad2: '#B88E3E',
                vein: '#6E5224',
                dust: '#FAF4E4'
            }
        ];

        // Screen dimensions
        let W = window.innerWidth;
        let H = window.innerHeight;

        window.addEventListener('resize', () => {
            W = window.innerWidth;
            H = window.innerHeight;
        }, { passive: true });

        // Optimal count based on screen width (5 for mobile, 7 for tablet/desktop)
        const COUNT = W < 640 ? 5 : 7;
        const creatures = [];

        // Track user interaction for natural reactive flutter
        let pointer = { x: -9999, y: -9999, active: false, timer: null };

        const onPointerMove = (e) => {
            pointer.x = e.clientX || (e.touches && e.touches[0] ? e.touches[0].clientX : pointer.x);
            pointer.y = e.clientY || (e.touches && e.touches[0] ? e.touches[0].clientY : pointer.y);
            pointer.active = true;
            clearTimeout(pointer.timer);
            pointer.timer = setTimeout(() => { pointer.active = false; }, 1800);
        };

        window.addEventListener('mousemove', onPointerMove, { passive: true });
        window.addEventListener('touchmove', onPointerMove, { passive: true });
        window.addEventListener('touchstart', onPointerMove, { passive: true });

        // Build individual butterfly instance
        class Butterfly {
            constructor(index) {
                this.index = index;
                this.palette = PALETTES[index % PALETTES.length];

                // Depth tier: 0 = background, 1 = midground, 2 = foreground
                if (index === 0) {
                    this.tier = 2; // foreground hero
                } else if (index % 3 === 0) {
                    this.tier = 0; // background distant
                } else {
                    this.tier = 1; // midground
                }

                // Scale & opacity per tier (dibuat ~setengah ukuran agar lebih proporsional & anggun)
                if (this.tier === 0) {
                    this.scale = 0.26 + Math.random() * 0.06;
                    this.baseOpacity = 0.38 + Math.random() * 0.15;
                    this.speedBase = 0.85 + Math.random() * 0.35;
                } else if (this.tier === 1) {
                    this.scale = 0.36 + Math.random() * 0.08;
                    this.baseOpacity = 0.68 + Math.random() * 0.15;
                    this.speedBase = 1.15 + Math.random() * 0.45;
                } else {
                    this.scale = 0.48 + Math.random() * 0.08;
                    this.baseOpacity = 0.88 + Math.random() * 0.10;
                    this.speedBase = 1.35 + Math.random() * 0.50;
                }

                // Initial position (distributed across screen)
                this.x = Math.random() * W;
                this.y = Math.random() * H;

                // Motion vectors
                this.heading = Math.random() * Math.PI * 2;
                this.targetHeading = this.heading;
                this.turnSpeed = 0.035 + Math.random() * 0.025;
                this.speed = this.speedBase;

                // Rhythm timers: flapping vs gliding cycles
                this.isFlapping = true;
                this.cycleTimer = 0;
                this.cycleDuration = this.rand(1.4, 3.2); // flap duration
                this.glideDuration = this.rand(0.7, 1.8);

                // Undulating harmonic flutter
                this.flutterPhase = Math.random() * Math.PI * 2;
                this.flutterFreq = 9 + Math.random() * 4;

                // DOM element creation
                this.createDOM(container);
            }

            rand(min, max) {
                return Math.random() * (max - min) + min;
            }

            createDOM(parent) {
                this.el = document.createElement('div');
                this.el.className = 'bf-creature is-flapping' +
                    (this.tier === 0 ? ' is-depth-bg' : (this.tier === 2 ? ' is-depth-fg' : ''));
                this.el.style.opacity = this.baseOpacity;

                const gradId = 'bf-g-' + this.index;

                this.el.innerHTML = `
                    <div class="bf-scaler" style="transform: scale(${this.scale});">
                        <div class="bf-body-3d">
                            <!-- Left Wing -->
                            <div class="bf-wing bf-wing-left">
                                ${this.renderLeftWingSVG(gradId)}
                            </div>
                            <!-- Central Torso -->
                            <div class="bf-torso">
                                ${this.renderTorsoSVG()}
                            </div>
                            <!-- Right Wing -->
                            <div class="bf-wing bf-wing-right">
                                ${this.renderRightWingSVG(gradId)}
                            </div>
                        </div>
                    </div>
                `;

                parent.appendChild(this.el);
            }

            renderTorsoSVG() {
                const { body, accent } = this.palette;
                return `
                <svg viewBox="0 0 14 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Antennae with delicate curved tips and clubs -->
                    <path d="M6 9 C4 5 2 3 1.2 3.5" stroke="${body}" stroke-width="0.75" stroke-linecap="round"/>
                    <circle cx="1.2" cy="3.5" r="0.9" fill="${accent}"/>
                    <path d="M8 9 C10 5 12 3 12.8 3.5" stroke="${body}" stroke-width="0.75" stroke-linecap="round"/>
                    <circle cx="12.8" cy="3.5" r="0.9" fill="${accent}"/>

                    <!-- Head -->
                    <circle cx="7" cy="10" r="2.4" fill="${body}"/>
                    <!-- Eyes -->
                    <circle cx="5.8" cy="9.4" r="0.6" fill="${accent}" opacity="0.8"/>
                    <circle cx="8.2" cy="9.4" r="0.6" fill="${accent}" opacity="0.8"/>

                    <!-- Thorax -->
                    <ellipse cx="7" cy="16" rx="2.2" ry="3.8" fill="${body}"/>
                    <!-- Thorax velvet highlight -->
                    <ellipse cx="7" cy="15.5" rx="1.2" ry="2.2" fill="${accent}" opacity="0.45"/>

                    <!-- Segmented Abdomen -->
                    <path d="M7 20 C5.8 20 5.4 23 5.4 28 C5.4 34 6.2 38 7 40 C7.8 38 8.6 34 8.6 28 C8.6 23 8.2 20 7 20 Z" fill="${body}"/>
                    <!-- Segment lines -->
                    <line x1="5.6" y1="23.5" x2="8.4" y2="23.5" stroke="${accent}" stroke-width="0.5" opacity="0.4"/>
                    <line x1="5.6" y1="27" x2="8.4" y2="27" stroke="${accent}" stroke-width="0.5" opacity="0.4"/>
                    <line x1="5.9" y1="30.5" x2="8.1" y2="30.5" stroke="${accent}" stroke-width="0.5" opacity="0.4"/>
                    <line x1="6.3" y1="34" x2="7.7" y2="34" stroke="${accent}" stroke-width="0.5" opacity="0.4"/>
                </svg>`;
            }

            renderRightWingSVG(gradId) {
                const { wingGrad1, wingGrad2, vein, dust } = this.palette;
                return `
                <svg viewBox="0 0 44 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="${gradId}-r" x1="0%" y1="20%" x2="100%" y2="80%">
                            <stop offset="0%" stop-color="${wingGrad1}" stop-opacity="0.88"/>
                            <stop offset="55%" stop-color="${wingGrad2}" stop-opacity="0.75"/>
                            <stop offset="100%" stop-color="${vein}" stop-opacity="0.68"/>
                        </linearGradient>
                    </defs>

                    <!-- Forewing (Upper Wing) -->
                    <path d="M1 21
                             C3 10 14 1 34 1
                             C41 1 44 5 42 10
                             C40 16 35 23 31 29
                             C24 32 11 28 1 24 Z"
                          fill="url(#${gradId}-r)"
                          stroke="${vein}" stroke-width="0.5" stroke-opacity="0.6"/>

                    <!-- Forewing delicate veins -->
                    <path d="M2 22 C10 15 22 8 34 2" stroke="${vein}" stroke-width="0.6" stroke-opacity="0.65"/>
                    <path d="M14 13 C22 13 32 12 40 8" stroke="${vein}" stroke-width="0.45" stroke-opacity="0.5"/>
                    <path d="M18 16 C26 19 32 21 37 18" stroke="${vein}" stroke-width="0.4" stroke-opacity="0.45"/>
                    <path d="M12 19 C18 24 24 27 28 28" stroke="${vein}" stroke-width="0.4" stroke-opacity="0.45"/>

                    <!-- Forewing translucent dust spots (khas kupu-kupu alami) -->
                    <circle cx="36" cy="6" r="1.3" fill="${dust}" opacity="0.85"/>
                    <circle cx="39" cy="9.5" r="1.1" fill="${dust}" opacity="0.85"/>
                    <circle cx="37" cy="14" r="0.9" fill="${dust}" opacity="0.75"/>
                    <circle cx="34" cy="19" r="0.9" fill="${dust}" opacity="0.75"/>

                    <!-- Hindwing (Lower Wing with scalloped margin) -->
                    <path d="M1 26
                             C10 27 28 29 31 38
                             C33 44 28 51 22 54
                             C18 56 14 55 10 50
                             C6 44 2 36 1 31 Z"
                          fill="url(#${gradId}-r)"
                          stroke="${vein}" stroke-width="0.5" stroke-opacity="0.55"/>

                    <!-- Hindwing veins -->
                    <path d="M2 28 C10 32 20 38 24 48" stroke="${vein}" stroke-width="0.5" stroke-opacity="0.55"/>
                    <path d="M10 34 C16 41 18 47 18 52" stroke="${vein}" stroke-width="0.4" stroke-opacity="0.45"/>
                    <path d="M6 31 C8 37 9 44 9 48" stroke="${vein}" stroke-width="0.4" stroke-opacity="0.45"/>

                    <!-- Hindwing margin dots -->
                    <circle cx="28" cy="42" r="1" fill="${dust}" opacity="0.8"/>
                    <circle cx="24" cy="48" r="0.9" fill="${dust}" opacity="0.8"/>
                    <circle cx="18" cy="52" r="0.8" fill="${dust}" opacity="0.7"/>
                </svg>`;
            }

            renderLeftWingSVG(gradId) {
                const { wingGrad1, wingGrad2, vein, dust } = this.palette;
                // Mirrored horizontally: x transformed from (44 - x)
                return `
                <svg viewBox="0 0 44 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="${gradId}-l" x1="100%" y1="20%" x2="0%" y2="80%">
                            <stop offset="0%" stop-color="${wingGrad1}" stop-opacity="0.88"/>
                            <stop offset="55%" stop-color="${wingGrad2}" stop-opacity="0.75"/>
                            <stop offset="100%" stop-color="${vein}" stop-opacity="0.68"/>
                        </linearGradient>
                    </defs>

                    <!-- Forewing (Left Upper) -->
                    <path d="M43 21
                             C41 10 30 1 10 1
                             C3 1 0 5 2 10
                             C4 16 9 23 13 29
                             C20 32 33 28 43 24 Z"
                          fill="url(#${gradId}-l)"
                          stroke="${vein}" stroke-width="0.5" stroke-opacity="0.6"/>

                    <!-- Forewing delicate veins -->
                    <path d="M42 22 C34 15 22 8 10 2" stroke="${vein}" stroke-width="0.6" stroke-opacity="0.65"/>
                    <path d="M30 13 C22 13 12 12 4 8" stroke="${vein}" stroke-width="0.45" stroke-opacity="0.5"/>
                    <path d="M26 16 C18 19 12 21 7 18" stroke="${vein}" stroke-width="0.4" stroke-opacity="0.45"/>
                    <path d="M32 19 C26 24 20 27 16 28" stroke="${vein}" stroke-width="0.4" stroke-opacity="0.45"/>

                    <!-- Forewing dust spots -->
                    <circle cx="8" cy="6" r="1.3" fill="${dust}" opacity="0.85"/>
                    <circle cx="5" cy="9.5" r="1.1" fill="${dust}" opacity="0.85"/>
                    <circle cx="7" cy="14" r="0.9" fill="${dust}" opacity="0.75"/>
                    <circle cx="10" cy="19" r="0.9" fill="${dust}" opacity="0.75"/>

                    <!-- Hindwing (Left Lower) -->
                    <path d="M43 26
                             C34 27 16 29 13 38
                             C11 44 16 51 22 54
                             C26 56 30 55 34 50
                             C38 44 42 36 43 31 Z"
                          fill="url(#${gradId}-l)"
                          stroke="${vein}" stroke-width="0.5" stroke-opacity="0.55"/>

                    <!-- Hindwing veins -->
                    <path d="M42 28 C34 32 24 38 20 48" stroke="${vein}" stroke-width="0.5" stroke-opacity="0.55"/>
                    <path d="M34 34 C28 41 26 47 26 52" stroke="${vein}" stroke-width="0.4" stroke-opacity="0.45"/>
                    <path d="M38 31 C36 37 35 44 35 48" stroke="${vein}" stroke-width="0.4" stroke-opacity="0.45"/>

                    <!-- Hindwing margin dots -->
                    <circle cx="16" cy="42" r="1" fill="${dust}" opacity="0.8"/>
                    <circle cx="20" cy="48" r="0.9" fill="${dust}" opacity="0.8"/>
                    <circle cx="26" cy="52" r="0.8" fill="${dust}" opacity="0.7"/>
                </svg>`;
            }

            update(dt, now) {
                this.cycleTimer += dt;

                // State machine: alternating between Flapping and Gliding
                if (this.isFlapping) {
                    if (this.cycleTimer > this.cycleDuration) {
                        this.isFlapping = false;
                        this.cycleTimer = 0;
                        this.el.classList.remove('is-flapping');
                        this.el.classList.add('is-gliding');
                    }
                } else {
                    if (this.cycleTimer > this.glideDuration) {
                        this.isFlapping = true;
                        this.cycleTimer = 0;
                        this.cycleDuration = this.rand(1.5, 3.4);
                        this.glideDuration = this.rand(0.8, 2.0);
                        this.el.classList.remove('is-gliding');
                        this.el.classList.add('is-flapping');
                    }
                }

                // Periodic heading wander: gentle turns
                if (Math.random() < 0.02) {
                    this.targetHeading += this.rand(-0.6, 0.6);
                }

                // Interactive reaction: veer gently away if pointer/touch is nearby
                if (pointer.active) {
                    const dx = this.x - pointer.x;
                    const dy = this.y - pointer.y;
                    const distSq = dx * dx + dy * dy;
                    const avoidRadius = 80;
                    if (distSq < avoidRadius * avoidRadius && distSq > 1) {
                        const dist = Math.sqrt(distSq);
                        const awayAngle = Math.atan2(dy, dx);
                        this.targetHeading = awayAngle + this.rand(-0.25, 0.25);
                        this.speed = Math.min(this.speedBase * 2.1, 4.0);
                        if (!this.isFlapping) {
                            this.isFlapping = true;
                            this.cycleTimer = 0;
                            this.el.classList.remove('is-gliding');
                            this.el.classList.add('is-flapping');
                        }
                    }
                }

                // Screen boundary steering (gentle repel from edges)
                const margin = 80;
                if (this.x < margin) {
                    this.targetHeading = this.headingLerp(this.targetHeading, 0, 0.08);
                } else if (this.x > W - margin) {
                    this.targetHeading = this.headingLerp(this.targetHeading, Math.PI, 0.08);
                }
                if (this.y < margin) {
                    this.targetHeading = this.headingLerp(this.targetHeading, Math.PI / 2, 0.08);
                } else if (this.y > H - margin) {
                    this.targetHeading = this.headingLerp(this.targetHeading, -Math.PI / 2, 0.08);
                }

                // Wrap-around fallback if carried past screen
                if (this.x < -60) this.x = W + 40;
                if (this.x > W + 60) this.x = -40;
                if (this.y < -60) this.y = H + 40;
                if (this.y > H + 60) this.y = -40;

                // Smoothly interpolate current heading towards target
                let diff = this.targetHeading - this.heading;
                // Normalize angle diff to [-PI, PI]
                diff = Math.atan2(Math.sin(diff), Math.cos(diff));
                this.heading += diff * this.turnSpeed;

                // Natural speed variations (faster when flapping, smoothly gliding when cruising)
                const targetSpeed = this.isFlapping ? this.speedBase * (1.1 + Math.sin(now * 0.005) * 0.2) : this.speedBase * 0.78;
                this.speed += (targetSpeed - this.speed) * 0.05;

                // Position update
                this.x += Math.cos(this.heading) * this.speed;
                this.y += Math.sin(this.heading) * this.speed;

                // Organic vertical flutter oscillation (undulation with flap rhythm, proporsional dengan skala baru)
                const flutterOffset = this.isFlapping ? Math.sin(now * 0.012 + this.flutterPhase) * 1.3 : Math.sin(now * 0.004 + this.flutterPhase) * 0.4;

                // Banking tilt into curves (roll angle based on angular turn rate)
                const bankAngle = Math.max(-24, Math.min(24, diff * 32));

                // Total angle in degrees: SVG points UP by default, so +90 degrees aligns it with the velocity vector
                const angleDeg = (this.heading * 180 / Math.PI) + 90;

                // Apply GPU-accelerated transform
                this.el.style.transform = `translate3d(${this.x.toFixed(1)}px, ${(this.y + flutterOffset).toFixed(1)}px, 0) rotate(${angleDeg.toFixed(1)}deg) skewX(${bankAngle.toFixed(1)}deg)`;
            }

            headingLerp(current, target, rate) {
                let diff = target - current;
                diff = Math.atan2(Math.sin(diff), Math.cos(diff));
                return current + diff * rate;
            }
        }

        // Initialize butterfly fleet
        for (let i = 0; i < COUNT; i++) {
            creatures.push(new Butterfly(i));
        }

        // Main animation loop (optimized 60fps with delta time)
        let lastTime = performance.now();
        let isRunning = true;

        function animate(now) {
            if (!isRunning) return;
            const dt = Math.min((now - lastTime) / 1000, 0.05);
            lastTime = now;

            for (let i = 0; i < creatures.length; i++) {
                creatures[i].update(dt, now);
            }

            requestAnimationFrame(animate);
        }

        requestAnimationFrame(animate);

        // Pause loop when tab is backgrounded to conserve 100% CPU/battery
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                isRunning = false;
            } else {
                isRunning = true;
                lastTime = performance.now();
                requestAnimationFrame(animate);
            }
        });
    }
}());
</script>
@endif
