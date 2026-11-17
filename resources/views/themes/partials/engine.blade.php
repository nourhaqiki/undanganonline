{{-- Shared invitation behavior; visual motion lives in Vite modules. --}}
<script>
function invitationEngine(config) {
    return {
        isOpen: false, musicPlaying: false, musicMode: config.musicMode || 'ambient', musicUrl: config.musicUrl || '', audioElem: null, audioCtx: null, synthInterval: null, ambientLevel: 0,
        lightboxOpen: false, activePhoto: '', copiedIndex: null,
        countdown: { days: '00', hours: '00', minutes: '00', seconds: '00', isPast: false }, targetDateStr: config.targetDate || '', wishes: config.initialWishes || [], counts: config.initialCounts || { total: 0, hadir: 0, tidak_hadir: 0 },
        rsvpForm: { guest_name: config.guestName || '', attendance: 'hadir', message: '' }, rsvpSubmitting: false, rsvpSuccessMsg: '', rsvpErrorMsg: '',

        init() {
            if (this.musicMode === 'custom' && this.musicUrl) { this.audioElem = new Audio(this.musicUrl); this.audioElem.loop = true; }
            this.updateCountdown(); setInterval(() => this.updateCountdown(), 1000);
        },

        openInvitation() {
            this.isOpen = true;
            this.playMusic(0);
            setTimeout(() => {
                document.body.classList.remove('overflow-hidden', 'h-screen');
                document.body.classList.add('invitation-open');
                this.fadeMusicIn();
                window.dispatchEvent(new CustomEvent('invitation:opened'));
            }, 450);
        },

        playMusic(level = .4) {
            if (this.musicMode === 'none') return;
            if (this.musicMode === 'custom' && this.audioElem) {
                this.audioElem.volume = level;
                this.audioElem.play().then(() => this.setMusicState(true)).catch(() => {});
                return;
            }
            this.ambientLevel = level; this.setMusicState(true); this.startAmbientSynth();
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

        setMusicState(playing) { this.musicPlaying = playing; window.dispatchEvent(new CustomEvent('invitation:music-state', { detail: { playing } })); },
        pauseMusic() { this.audioElem?.pause(); this.audioCtx?.suspend(); if (this.synthInterval) clearInterval(this.synthInterval); this.synthInterval = null; this.setMusicState(false); },
        toggleMusic() { if (this.musicPlaying) return this.pauseMusic(); if (this.musicMode === 'ambient' && this.audioCtx?.state === 'suspended') this.audioCtx.resume(); this.playMusic(); },

        startAmbientSynth() {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;
            if (!this.audioCtx) this.audioCtx = new AudioContext();
            if (this.audioCtx.state === 'suspended') this.audioCtx.resume();
            this.scheduleSynthChords();
        },

        playTone(frequency, duration, delay = 0) {
            if (!this.audioCtx) return;
            const start = this.audioCtx.currentTime + delay, oscillator = this.audioCtx.createOscillator(), gain = this.audioCtx.createGain();
            oscillator.type = 'sine'; oscillator.frequency.setValueAtTime(frequency, start);
            gain.gain.setValueAtTime(.0001, start); gain.gain.exponentialRampToValueAtTime(Math.max(.0001, this.ambientLevel), start + .3); gain.gain.exponentialRampToValueAtTime(.0001, start + duration);
            oscillator.connect(gain); gain.connect(this.audioCtx.destination); oscillator.start(start); oscillator.stop(start + duration + .1);
        },

        scheduleSynthChords() {
            const chords = [[293.66, 369.99, 440, 554.37], [246.94, 329.63, 392, 493.88], [277.18, 369.99, 440, 554.37], [329.63, 440, 493.88, 659.25]];
            let index = 0;
            const playChord = () => { if (!this.musicPlaying || !this.audioCtx) return; chords[index++ % chords.length].forEach((frequency, note) => this.playTone(frequency, 3.8, note * .15)); };
            playChord(); if (this.synthInterval) clearInterval(this.synthInterval); this.synthInterval = setInterval(playChord, 4200);
        },

        updateCountdown() {
            if (!this.targetDateStr) return;
            const difference = new Date(this.targetDateStr).getTime() - Date.now();
            if (difference <= 0) return this.countdown = { days: '00', hours: '00', minutes: '00', seconds: '00', isPast: true };
            this.countdown = { days: String(Math.floor(difference / 86400000)).padStart(2, '0'), hours: String(Math.floor(difference % 86400000 / 3600000)).padStart(2, '0'), minutes: String(Math.floor(difference % 3600000 / 60000)).padStart(2, '0'), seconds: String(Math.floor(difference % 60000 / 1000)).padStart(2, '0'), isPast: false };
        },

        copyText(text, index) { navigator.clipboard?.writeText(text).catch(() => {}); this.copiedIndex = index; window.dispatchEvent(new CustomEvent('invitation:copy')); setTimeout(() => this.copiedIndex = null, 2500); },
        openLightbox(src) { this.activePhoto = src; this.lightboxOpen = true; },
        closeLightbox() { this.lightboxOpen = false; this.activePhoto = ''; },

        submitRsvp(endpointUrl) {
            if (!this.rsvpForm.guest_name.trim() || !this.rsvpForm.message.trim()) { this.rsvpErrorMsg = 'Mohon lengkapi nama dan ucapan Anda.'; return; }
            this.rsvpSubmitting = true; this.rsvpErrorMsg = ''; this.rsvpSuccessMsg = '';
            fetch(endpointUrl, { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify(this.rsvpForm) })
                .then((response) => { if (!response.ok) throw new Error(); return response.json(); })
                .then((data) => { if (!data.success) throw new Error(); this.wishes.unshift(data.wish); this.counts = data.counts; this.rsvpSuccessMsg = data.message || 'Ucapan berhasil dikirim!'; this.rsvpForm.message = ''; })
                .catch(() => this.rsvpErrorMsg = 'Gagal mengirim ucapan. Periksa koneksi Anda.')
                .finally(() => this.rsvpSubmitting = false);
        },
    };
}
</script>

@include('components.invitation.ambient-butterflies', ['eventType' => $eventType])
@include('components.invitation.floral-ornaments', ['eventType' => $eventType])
@include('components.invitation.music-player')
@include('components.invitation.bottom-nav')

<div x-cloak x-show="lightboxOpen" @keydown.escape.window="closeLightbox()" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/90 p-4" role="dialog" aria-modal="true">
    <button @click="closeLightbox()" type="button" aria-label="Tutup foto" class="absolute right-4 top-4 rounded-full bg-white/10 p-2 text-white hover:bg-white/20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">Tutup</button>
    <div class="max-h-[90vh] max-w-4xl overflow-hidden rounded-2xl" @click.away="closeLightbox()"><img :src="activePhoto" alt="Preview foto" class="max-h-[85vh] w-full object-contain"></div>
</div>

<div data-copy-toast role="status" aria-live="polite" class="rounded-full border border-amber-400/30 bg-stone-900 px-4 py-2.5 text-xs font-semibold text-amber-300 shadow-2xl">Nomor rekening tersalin.</div>
