<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Undangan Berhasil Dibuat! | {{ $invitation->title }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS v4, Alpine.js CDN & SheetJS (Excel/CSV Parser) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .font-brand { font-family: 'Playfair Display', Georgia, serif; }
        .font-sans-main { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
    </style>
</head>
<body class="font-sans-main bg-stone-950 text-stone-100 overflow-x-hidden antialiased selection:bg-amber-400 selection:text-stone-950 min-h-screen py-10 px-4">

    <div class="max-w-xl mx-auto space-y-6" x-data="successApp">

        <!-- Banner Sukses -->
        <div class="text-center space-y-2">
            <div class="w-16 h-16 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center mx-auto text-3xl shadow-xl shadow-emerald-500/10 mb-4 border border-emerald-500/30">
                ✓
            </div>
            <span class="inline-block px-3.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-amber-400/10 text-amber-300 border border-amber-400/20">
                Penerbitan Berhasil
            </span>
            <h1 class="font-brand text-2xl sm:text-3xl font-bold text-white">Undangan Anda Siap Disebarkan!</h1>
            <p class="text-xs text-stone-400">Undangan online Anda telah aktif dan dapat langsung diakses publik.</p>
        </div>

        <!-- Card 1: Link Undangan Publik -->
        <div class="p-6 rounded-3xl bg-stone-900 border border-white/10 shadow-xl space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-stone-300 uppercase tracking-wider">Tautan Publik Utama</span>
                <a :href="personalizedUrl" target="_blank" class="text-xs text-amber-400 hover:underline flex items-center gap-1 font-semibold">
                    <span>Buka Undangan</span>
                    <span>↗</span>
                </a>
            </div>

            <div class="flex items-center gap-2 p-3 rounded-2xl bg-stone-950 border border-white/10 text-xs font-mono text-amber-300 break-all select-all">
                <span class="truncate flex-1" x-text="personalizedUrl"></span>
            </div>

            <div class="grid grid-cols-2 gap-2 pt-1">
                <button type="button" @click="copyText(personalizedUrl, 'copiedLink')"
                        class="min-h-[44px] py-2.5 px-4 rounded-xl text-xs font-semibold bg-white/10 hover:bg-white/15 text-stone-200 border border-white/10 transition active:scale-95 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                    </svg>
                    <span x-text="copiedLink ? 'Link Tersalin!' : 'Salin Link Undangan'">Salin Link Undangan</span>
                </button>

                <a :href="personalizedUrl" target="_blank"
                   class="min-h-[44px] py-2.5 px-4 rounded-xl text-xs font-semibold bg-amber-400 hover:bg-amber-300 text-stone-950 transition active:scale-95 flex items-center justify-center gap-1.5">
                    <span>Lihat Halaman Live</span>
                </a>
            </div>
        </div>

        <!-- Card 2: Generator Tamu & WhatsApp Real-Time -->
        <div class="p-6 rounded-3xl bg-stone-900 border border-white/10 shadow-xl space-y-4">
            <div>
                <span class="text-xs font-bold text-amber-400 uppercase tracking-wider block mb-1">Generator Link Tamu Satuan</span>
                <p class="text-xs text-stone-400 leading-relaxed">
                    Ketik nama dan alamat tamu di bawah ini. Sistem akan otomatis mempersonalisasi link (<code class="text-amber-300">?to=...&amp;addr=...</code>) dan menyiapkan teks WhatsApp.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-semibold text-stone-300 mb-1.5">Nama Tamu Undangan:</label>
                    <input type="text" x-model="guestName" placeholder="Contoh: Nour Haqiki & Pasangan"
                           class="w-full px-4 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs focus:ring-2 focus:ring-amber-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-stone-300 mb-1.5">Alamat / Kota (Opsional):</label>
                    <input type="text" x-model="guestAddress" placeholder="Contoh: Surabaya"
                           class="w-full px-4 py-2.5 rounded-xl bg-stone-950 border border-white/10 text-white text-xs focus:ring-2 focus:ring-amber-400 focus:outline-none">
                </div>
            </div>

            <!-- Preview Pesan WhatsApp -->
            <div>
                <label class="block text-[11px] font-semibold text-stone-400 mb-1">Preview Pesan WhatsApp:</label>
                <div class="p-3.5 rounded-2xl bg-stone-950 border border-white/10 text-xs text-stone-300 font-sans whitespace-pre-line leading-relaxed max-h-48 overflow-y-auto">
                    <span x-text="waMessage"></span>
                </div>
            </div>

            <!-- Action Buttons WhatsApp -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1">
                <button type="button" @click="copyText(waMessage, 'copiedWaText')"
                        class="min-h-[44px] py-2.5 px-4 rounded-xl text-xs font-semibold bg-white/10 hover:bg-white/15 text-stone-200 border border-white/10 transition active:scale-95 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span x-text="copiedWaText ? 'Teks WhatsApp Tersalin!' : 'Salin Teks Pengantar'">Salin Teks Pengantar</span>
                </button>

                <a :href="waShareLink" target="_blank" rel="noopener noreferrer"
                   class="min-h-[44px] py-2.5 px-4 rounded-xl text-xs font-bold bg-[#25D366] hover:bg-[#20ba5a] text-stone-950 transition active:scale-95 flex items-center justify-center gap-2 shadow-md">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                    </svg>
                    <span>Kirim via WhatsApp Langsung</span>
                </a>
            </div>
        </div>

        <!-- Card 3: Download File Excel Tamu (Lengkap dengan Kolom Link Undangan) -->
        <div class="p-6 rounded-3xl bg-stone-900 border border-white/10 shadow-xl space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-amber-400 uppercase tracking-wider block">Unduh Rekap Tamu &amp; Link Undangan</span>
                    <p class="text-xs text-stone-400 mt-0.5">Daftar tamu lengkap dengan kolom <code class="text-amber-300 font-mono">Link</code> undangan personal.</p>
                </div>
                <span x-show="excelGuests.length > 0" class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30"
                      x-text="excelGuests.length + ' Tamu Siap'"></span>
            </div>

            <!-- Tombol Utama Download Excel dengan Kolom Link -->
            <button type="button" @click="exportExcelWithLinks()"
                    class="w-full min-h-[48px] py-3 px-5 rounded-2xl text-xs sm:text-sm font-bold bg-emerald-600 hover:bg-emerald-500 text-white transition active:scale-95 flex items-center justify-center gap-2.5 shadow-xl shadow-emerald-600/20">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Download File Excel Tamu (+ Kolom Link Undangan)</span>
            </button>

            <!-- Keterangan Format File Hasil Download -->
            <div class="p-3.5 rounded-2xl bg-stone-950/80 border border-white/5 text-[11px] text-stone-400 space-y-1">
                <div class="flex items-center gap-1.5 text-stone-300 font-semibold">
                    <span class="text-emerald-400">✓</span>
                    <span>Struktur Kolom File Excel Hasil Download:</span>
                </div>
                <p class="text-[10px] text-stone-400 pl-4 font-mono text-amber-300/80">
                    No | Nama Tamu | Alamat | No WhatsApp | Catatan | Link
                </p>
                <p class="text-[10px] text-stone-500 pl-4">
                    Kolom <strong class="text-stone-300">Link</strong> otomatis terisi tautan personal undangan untuk setiap tamu.
                </p>
            </div>

            <!-- Pratinjau Daftar Tamu & Quick Action Salin / WhatsApp -->
            <div x-show="excelGuests.length > 0" x-cloak class="space-y-2 pt-2 border-t border-white/10">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-bold text-white" x-text="excelGuests.length + ' Tamu Siap Disebarkan:'"></span>
                    <a href="{{ route('invitation.manage', ['slug' => $invitation->slug, 'token' => $invitation->edit_token]) }}"
                       class="text-amber-400 hover:underline font-semibold text-[11px]">Buka Panel Kelola Tamu ↗</a>
                </div>

                <div class="max-h-56 overflow-y-auto space-y-2 pr-1">
                    <template x-for="(g, idx) in excelGuests" :key="idx">
                        <div class="p-2.5 rounded-xl bg-stone-950 border border-white/5 flex items-center justify-between gap-2 text-xs">
                            <div class="truncate">
                                <span class="font-semibold text-white block truncate" x-text="(idx + 1) + '. ' + g.name"></span>
                                <span class="text-[10px] text-stone-400 block truncate" x-text="(g.address ? 'di ' + g.address + ' • ' : '') + getGuestUrl(g)"></span>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <button type="button" @click="copyGuestLink(g, idx)"
                                        class="min-h-[32px] px-2.5 py-1 rounded-lg text-[10px] font-semibold border transition active:scale-95"
                                        :class="copiedIndex === idx ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40' : 'bg-white/10 text-stone-200 border-white/10'">
                                    <span x-text="copiedIndex === idx ? 'Tersalin' : 'Salin'"></span>
                                </button>
                                <a :href="getWaLink(g)" target="_blank"
                                   class="min-h-[32px] px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-emerald-600 hover:bg-emerald-500 text-white transition active:scale-95 flex items-center gap-1 shadow-sm">
                                    <span>WA</span>
                                    <span>↗</span>
                                </a>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Alternatif jika belum upload tamu saat di builder -->
            <div x-show="excelGuests.length === 0" class="pt-2 border-t border-white/5 flex items-center justify-between text-[11px] text-stone-400">
                <span>Belum ada tamu terunggah dari builder?</span>
                <a href="{{ route('invitation.manage', ['slug' => $invitation->slug, 'token' => $invitation->edit_token]) }}"
                   class="text-amber-400 hover:underline font-semibold">
                    Unggah Tamu di Panel Kelola ↗
                </a>
            </div>
        </div>

        <!-- Card 3: Kotak Tautan Kelola Mandiri (Rahasia) -->
        <div class="p-6 rounded-3xl bg-amber-400/5 border border-amber-400/30 shadow-xl space-y-3">
            <div class="flex items-center gap-2 text-amber-400 font-bold text-xs uppercase tracking-wider">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span>Tautan Rahasia Kelola Mandiri (Simpan URL Ini!)</span>
            </div>

            <p class="text-xs text-stone-300 leading-relaxed">
                Gunakan tautan di bawah ini untuk mengedit data undangan, mengganti tema, serta memantau rekap kehadiran tamu kapan saja <strong>tanpa perlu mendaftar atau login</strong>:
            </p>

            @php
                $manageUrl = route('invitation.manage', ['slug' => $invitation->slug, 'token' => $invitation->edit_token]);
            @endphp
            <div class="p-3 rounded-2xl bg-stone-950 border border-amber-400/20 text-xs font-mono text-amber-300 break-all select-all">
                {{ $manageUrl }}
            </div>

            <div class="grid grid-cols-2 gap-2 pt-1">
                <button type="button" @click="copyText('{{ $manageUrl }}', 'copiedManageUrl')"
                        class="min-h-[44px] py-2.5 px-4 rounded-xl text-xs font-semibold bg-amber-400/20 hover:bg-amber-400/30 text-amber-300 border border-amber-400/30 transition active:scale-95 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                    </svg>
                    <span x-text="copiedManageUrl ? 'Tautan Kelola Tersalin!' : 'Salin Tautan Kelola'">Salin Tautan Kelola</span>
                </button>

                <a href="{{ $manageUrl }}"
                   class="min-h-[44px] py-2.5 px-4 rounded-xl text-xs font-semibold bg-white/10 hover:bg-white/15 text-stone-200 border border-white/10 transition active:scale-95 flex items-center justify-center">
                    <span>Masuk ke Halaman Kelola</span>
                </a>
            </div>
        </div>

        <div class="text-center pt-2">
            <a href="{{ route('home') }}" class="text-xs text-stone-500 hover:text-stone-300">
                ← Kembali ke Beranda Utama
            </a>
        </div>
    </div>


    <!-- Alpine Component Script -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('successApp', () => ({
             guestName: '',
             guestAddress: '',
             baseUrl: '{{ url('/u/' . $invitation->slug) }}',
             title: '{{ addslashes($invitation->title) }}',
             copiedLink: false,
             copiedWaText: false,
             copiedManageUrl: false,

             // State Tamu dari Upload Builder (Home)
             excelGuests: [],
             uploadedGuests: {!! json_encode(json_decode(session('uploaded_guests', '[]'), true) ?? ($invitation->event_data['guests'] ?? [])) !!},
             copiedIndex: null,

             init() {
                 if (this.uploadedGuests && this.uploadedGuests.length > 0) {
                     this.excelGuests = this.uploadedGuests;
                 }
             },

             get personalizedUrl() {
                 let url = this.baseUrl;
                 const params = [];
                 if (this.guestName.trim()) {
                     params.push('to=' + encodeURIComponent(this.guestName.trim()));
                 }
                 if (this.guestAddress.trim()) {
                     params.push('addr=' + encodeURIComponent(this.guestAddress.trim()));
                 }
                 if (params.length > 0) {
                     url += '?' + params.join('&');
                 }
                 return url;
             },

             get waMessage() {
                 const name = this.guestName.trim() ? this.guestName.trim() : '[Nama Tamu]';
                 const address = this.guestAddress.trim() ? ` di ${this.guestAddress.trim()}` : '';
                 return `Kepada Yth. Bapak/Ibu/Saudara/i ${name}${address},\n\nTanpa mengurangi rasa hormat, kami bermaksud mengundang Bapak/Ibu/Saudara/i untuk menghadiri acara kami:\n\n*${this.title}*\n\nInformasi lengkap dan konfirmasi kehadiran dapat diakses melalui tautan undangan berikut:\n${this.personalizedUrl}\n\nMerupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan untuk hadir dan memberikan doa restu.\n\nTerima kasih.`;
             },

             get waShareLink() {
                 return 'https://wa.me/?text=' + encodeURIComponent(this.waMessage);
             },

             copyText(val, prop) {
                 if (!navigator.clipboard) {
                     const el = document.createElement('textarea');
                     el.value = val;
                     document.body.appendChild(el);
                     el.select();
                     document.execCommand('copy');
                     document.body.removeChild(el);
                 } else {
                     navigator.clipboard.writeText(val);
                 }
                 this[prop] = true;
                 setTimeout(() => this[prop] = false, 2500);
             },

             // Helper Excel Batch
             getGuestUrl(g) {
                 let url = this.baseUrl;
                 const params = [];
                 if (g.name && g.name.trim()) params.push('to=' + encodeURIComponent(g.name.trim()));
                 if (g.address && g.address.trim()) params.push('addr=' + encodeURIComponent(g.address.trim()));
                 if (params.length > 0) url += '?' + params.join('&');
                 return url;
             },

             getWaLink(g) {
                 let phone = (g.phone || '').toString().replace(/[^0-9]/g, '');
                 if (phone.startsWith('0')) phone = '62' + phone.substring(1);
                 const name = g.name ? g.name.trim() : 'Bapak/Ibu/Saudara/i';
                 const address = g.address && g.address.trim() ? ` di ${g.address.trim()}` : '';
                 const url = this.getGuestUrl(g);
                 const msg = `Kepada Yth. Bapak/Ibu/Saudara/i ${name}${address},\n\nTanpa mengurangi rasa hormat, kami bermaksud mengundang Bapak/Ibu/Saudara/i untuk menghadiri acara kami:\n\n*${this.title}*\n\nInformasi lengkap dan konfirmasi kehadiran dapat diakses melalui link undangan berikut:\n${url}\n\nTerima kasih.`;
                 if (phone) return `https://wa.me/${phone}?text=${encodeURIComponent(msg)}`;
                 return `https://wa.me/?text=${encodeURIComponent(msg)}`;
             },

             copyGuestLink(g, idx) {
                 const url = this.getGuestUrl(g);
                 this.copyText(url, 'copiedLink');
                 this.copiedIndex = idx;
                 setTimeout(() => { if (this.copiedIndex === idx) this.copiedIndex = null; }, 2000);
             },

             // Download Excel dengan Kolom Link Undangan
             exportExcelWithLinks() {
                 let list = this.excelGuests;
                 if (!list || list.length === 0) {
                     if (this.guestName.trim()) {
                         list = [{
                             name: this.guestName.trim(),
                             address: this.guestAddress.trim(),
                             phone: '',
                             note: 'Tamu Satuan'
                         }];
                     } else {
                         list = [
                             { name: 'Nour Haqiki', address: 'Surabaya', phone: '081234567890', note: 'Keluarga' },
                             { name: 'Bpk. Ahmad Subagyo', address: 'Jakarta', phone: '085712345678', note: 'Rekan Kerja' },
                             { name: 'Siti Rahmawati', address: 'Yogyakarta', phone: '', note: 'Sahabat' }
                         ];
                     }
                 }

                 const exportData = list.map((g, idx) => ({
                     'No': idx + 1,
                     'Nama Tamu': g.name,
                     'Alamat': g.address || '',
                     'No WhatsApp': g.phone || '',
                     'Catatan': g.note || '',
                     'Link': this.getGuestUrl(g)
                 }));

                 const ws = XLSX.utils.json_to_sheet(exportData);
                 ws['!cols'] = [
                     { wch: 6 },
                     { wch: 28 },
                     { wch: 22 },
                     { wch: 18 },
                     { wch: 20 },
                     { wch: 65 }
                 ];
                 const wb = XLSX.utils.book_new();
                 XLSX.utils.book_append_sheet(wb, ws, "Daftar Tamu & Link");
                 const safeTitle = this.title.replace(/[^a-zA-Z0-9]/g, '_');
                 XLSX.writeFile(wb, `Daftar_Tamu_${safeTitle}_Dengan_Link.xlsx`);
             }
         }));
        });
    </script>
</body>
</html>
