{{-- Shared invitation behavior; visual motion lives in Vite modules. --}}
@php
    $themeSlug = $invitation->theme_slug ?? '';
    $hasCustomChrome = $hasCustomChrome ?? in_array($themeSlug, ['velvet-overture', 'film-roll'], true);
@endphp
<script>
function invitationEngine(config) {
    return {
        isOpen: false,
        musicPlaying: false,
        musicMode: config.musicMode || 'ambient',
        musicUrl: config.musicUrl || '',
        audioElem: null,
        audioCtx: null,
        synthInterval: null,
        ambientLevel: 0,
        lightboxOpen: false,
        activePhoto: '',
        copiedIndex: null,
        countdown: { days: '00', hours: '00', minutes: '00', seconds: '00', isPast: false },
        targetDateStr: config.targetDate || '',
        wishes: config.initialWishes || [],
        counts: config.initialCounts || { total: 0, hadir: 0, tidak_hadir: 0 },
        rsvpForm: { guest_name: config.guestName || '', attendance: 'hadir', message: '' },
        rsvpSubmitting: false,
        rsvpSuccessMsg: '',
        rsvpErrorMsg: '',

        init() {
            if (this.musicMode === 'custom' && this.musicUrl) {
                this.audioElem = new Audio(this.musicUrl);
                this.audioElem.loop = true;
                this.audioElem.preload = 'none';
            }
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    this.audioElem?.pause();
                    this.audioCtx?.suspend();
                } else if (this.musicPlaying) {
                    this.audioElem?.play().catch(() => {});
                    this.audioCtx?.resume();
                }
            });
            this.updateCountdown();
            setInterval(() => this.updateCountdown(), 1000);
        },

        openInvitation() {
            this.isOpen = true;
            this.playMusic(0);

            const cover = document.querySelector('[data-cover-gate]');
            if (cover) {
                const onEnd = () => {
                    cover.style.display = 'none';
                    cover.removeEventListener('transitionend', onEnd);
                };
                cover.addEventListener('transitionend', onEnd);
            }

            setTimeout(() => {
                document.body.classList.remove('overflow-hidden', 'h-screen', 'h-dvh');
                document.body.classList.add('invitation-open');
                this.fadeMusicIn();
                window.dispatchEvent(new CustomEvent('invitation:opened'));
            }, 350);
        },

        playMusic(level = .4) {
            if (this.musicMode === 'none') return;
            if (this.musicMode === 'custom' && this.audioElem) {
                this.audioElem.volume = level;
                this.audioElem.play().then(() => this.setMusicState(true)).catch(() => {});
                return;
            }
            this.ambientLevel = level;
            this.setMusicState(true);
            this.startAmbientSynth();
        },

        fadeMusicIn() {
            const target = .4;
            const fade = setInterval(() => {
                if (!this.musicPlaying) return clearInterval(fade);
                if (this.musicMode === 'custom' && this.audioElem) {
                    this.audioElem.volume = Math.min(target, this.audioElem.volume + .04);
                    if (this.audioElem.volume >= target) clearInterval(fade);
                    return;
                }
                this.ambientLevel = Math.min(.035, this.ambientLevel + .004);
                if (this.ambientLevel >= .035) clearInterval(fade);
            }, 60);
        },

        setMusicState(playing) {
            this.musicPlaying = playing;
            window.dispatchEvent(new CustomEvent('invitation:music-state', { detail: { playing } }));
        },

        pauseMusic() {
            this.audioElem?.pause();
            this.audioCtx?.suspend();
            if (this.synthInterval) clearInterval(this.synthInterval);
            this.synthInterval = null;
            this.setMusicState(false);
        },

        toggleMusic() {
            if (this.musicPlaying) return this.pauseMusic();
            if (this.musicMode === 'ambient' && this.audioCtx?.state === 'suspended') this.audioCtx.resume();
            this.playMusic();
        },

        startAmbientSynth() {
            const AC = window.AudioContext || window.webkitAudioContext;
            if (!AC) return;
            if (!this.audioCtx) this.audioCtx = new AC();
            if (this.audioCtx.state === 'suspended') this.audioCtx.resume();
            this.scheduleSynthChords();
        },

        playTone(freq, dur, delay = 0) {
            if (!this.audioCtx) return;
            const t = this.audioCtx.currentTime + delay;
            const osc = this.audioCtx.createOscillator();
            const gain = this.audioCtx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(freq, t);
            gain.gain.setValueAtTime(.0001, t);
            gain.gain.exponentialRampToValueAtTime(Math.max(.0001, this.ambientLevel), t + .3);
            gain.gain.exponentialRampToValueAtTime(.0001, t + dur);
            osc.connect(gain);
            gain.connect(this.audioCtx.destination);
            osc.start(t);
            osc.stop(t + dur + .1);
        },

        scheduleSynthChords() {
            const chords = [
                [293.66, 369.99, 440, 554.37],
                [246.94, 329.63, 392, 493.88],
                [277.18, 369.99, 440, 554.37],
                [329.63, 440, 493.88, 659.25]
            ];
            let idx = 0;
            const play = () => {
                if (!this.musicPlaying || !this.audioCtx) return;
                chords[idx++ % chords.length].forEach((f, n) => this.playTone(f, 3.8, n * .15));
            };
            play();
            if (this.synthInterval) clearInterval(this.synthInterval);
            this.synthInterval = setInterval(play, 4200);
        },

        updateCountdown() {
            if (!this.targetDateStr) return;
            const diff = new Date(this.targetDateStr).getTime() - Date.now();
            if (diff <= 0) {
                this.countdown = { days: '00', hours: '00', minutes: '00', seconds: '00', isPast: true };
                return;
            }
            this.countdown = {
                days:    String(Math.floor(diff / 86400000)).padStart(2, '0'),
                hours:   String(Math.floor(diff % 86400000 / 3600000)).padStart(2, '0'),
                minutes: String(Math.floor(diff % 3600000 / 60000)).padStart(2, '0'),
                seconds: String(Math.floor(diff % 60000 / 1000)).padStart(2, '0'),
                isPast: false
            };
        },

        copyText(text, index) {
            navigator.clipboard?.writeText(text).catch(() => {});
            this.copiedIndex = index;
            window.dispatchEvent(new CustomEvent('invitation:copy'));
            setTimeout(() => this.copiedIndex = null, 2500);
        },

        openLightbox(src) { this.activePhoto = src; this.lightboxOpen = true; },
        closeLightbox() { this.lightboxOpen = false; this.activePhoto = ''; },

        submitRsvp(url) {
            if (!this.rsvpForm.guest_name.trim() || !this.rsvpForm.message.trim()) {
                this.rsvpErrorMsg = 'Mohon lengkapi nama dan ucapan Anda.';
                return;
            }
            this.rsvpSubmitting = true;
            this.rsvpErrorMsg = '';
            this.rsvpSuccessMsg = '';
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(this.rsvpForm)
            })
            .then(r => { if (!r.ok) throw new Error(); return r.json(); })
            .then(data => {
                if (!data.success) throw new Error();
                this.wishes.unshift(data.wish);
                this.counts = data.counts;
                this.rsvpSuccessMsg = data.message || 'Ucapan berhasil dikirim!';
                this.rsvpForm.message = '';
            })
            .catch(() => this.rsvpErrorMsg = 'Gagal mengirim ucapan. Periksa koneksi Anda.')
            .finally(() => this.rsvpSubmitting = false);
        },
    };
}
</script>

@include('components.invitation.ambient-butterflies', ['eventType' => $eventType, 'themeSlug' => $themeSlug])
@if (!$hasCustomChrome)
    @include('components.invitation.floral-ornaments', ['eventType' => $eventType])
    @include('components.invitation.music-player')
    @include('components.invitation.bottom-nav')

    <div x-cloak x-show="lightboxOpen" @keydown.escape.window="closeLightbox()" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/90 p-4" role="dialog" aria-modal="true">
        <button @click="closeLightbox()" type="button" aria-label="Tutup foto" class="absolute right-4 top-4 rounded-full bg-white/10 px-3.5 py-2 text-xs font-semibold text-white hover:bg-white/20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">Tutup</button>
        <div class="max-h-[90vh] max-w-4xl overflow-hidden rounded-2xl" @click.away="closeLightbox()"><img :src="activePhoto" alt="Preview foto" class="max-h-[85vh] w-full object-contain"></div>
    </div>
@endif

<div data-copy-toast role="status" aria-live="polite" class="rounded-full border border-amber-400/30 bg-stone-900 px-4 py-2.5 text-xs font-semibold text-amber-300 shadow-2xl">Nomor rekening tersalin.</div>
