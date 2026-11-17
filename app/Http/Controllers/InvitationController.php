<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\InvitationWish;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    /**
     * Tampilkan landing page & multi-step builder
     */
    public function create()
    {
        $themes = Theme::where('is_active', true)->get();
        return view('welcome', compact('themes'));
    }

    /**
     * Simpan undangan baru dari builder
     */
    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|in:pernikahan,tunangan,bisnis,lainnya',
            'theme_slug' => 'required|string|exists:themes,slug',
            'title' => 'required|string|max:255',
            'primary_name' => 'required|string|max:255',
            'primary_title_parents' => 'nullable|string|max:255',
            'primary_instagram' => 'nullable|string|max:255',
            'secondary_name' => 'nullable|string|max:255',
            'secondary_title_parents' => 'nullable|string|max:255',
            'secondary_instagram' => 'nullable|string|max:255',
            'greeting' => 'nullable|string|max:255',
            'quote' => 'nullable|string',
            'session_1_title' => 'required|string|max:255',
            'session_1_date' => 'required|date',
            'session_1_time' => 'required|string|max:100',
            'session_1_venue' => 'required|string|max:255',
            'session_1_address' => 'required|string',
            'session_1_maps_url' => 'nullable|string|max:500',
            'session_2_title' => 'nullable|string|max:255',
            'session_2_date' => 'nullable|date',
            'session_2_time' => 'nullable|string|max:100',
            'session_2_venue' => 'nullable|string|max:255',
            'session_2_address' => 'nullable|string',
            'session_2_maps_url' => 'nullable|string|max:500',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery_images' => 'nullable|array|max:4',
            'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'music_mode' => 'required|string|in:none,ambient,custom',
            'music_file' => 'nullable|file|mimes:mp3,wav,ogg,audio/mpeg|max:5120',
        ]);

        // Upload Cover
        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('invitations/covers', 'public');
        }

        // Upload Gallery Images (Max 4)
        $galleryPaths = [];
        if ($request->hasFile('gallery_images')) {
            foreach (array_slice($request->file('gallery_images'), 0, 4) as $photo) {
                if ($photo) {
                    $galleryPaths[] = $photo->store('invitations/galleries', 'public');
                }
            }
        }

        // Upload Custom Music
        $musicPath = null;
        if ($request->music_mode === 'custom' && $request->hasFile('music_file')) {
            $musicPath = $request->file('music_file')->store('invitations/music', 'public');
        }

        // Parse Gift Accounts
        $giftAccounts = [];
        if ($request->has('gift_accounts') && is_array($request->gift_accounts)) {
            foreach ($request->gift_accounts as $acc) {
                if (!empty($acc['bank']) && !empty($acc['account_number'])) {
                    $giftAccounts[] = [
                        'bank' => trim($acc['bank']),
                        'account_number' => trim($acc['account_number']),
                        'account_name' => trim($acc['account_name'] ?? ''),
                    ];
                }
            }
        }

        // Parse Rundown / Agenda
        $rundown = [];
        if ($request->has('rundown') && is_array($request->rundown)) {
            foreach ($request->rundown as $item) {
                if (!empty($item['title'])) {
                    $rundown[] = [
                        'time' => trim($item['time'] ?? ''),
                        'title' => trim($item['title']),
                        'description' => trim($item['description'] ?? ''),
                    ];
                }
            }
        }

        // Generate Unique Slug
        $baseSlug = Str::slug($request->title);
        if (empty($baseSlug)) {
            $baseSlug = 'undangan';
        }
        do {
            $slug = $baseSlug . '-' . Str::lower(Str::random(4));
        } while (Invitation::where('slug', $slug)->exists());

        // Generate 24-character edit token
        $editToken = Str::random(24);

        $eventData = [
            'greeting' => $request->greeting,
            'quote' => $request->quote,
            'primary_name' => $request->primary_name,
            'primary_title_parents' => $request->primary_title_parents,
            'primary_instagram' => $request->primary_instagram,
            'secondary_name' => $request->secondary_name,
            'secondary_title_parents' => $request->secondary_title_parents,
            'secondary_instagram' => $request->secondary_instagram,
            'session_1' => [
                'title' => $request->session_1_title,
                'date' => $request->session_1_date,
                'time' => $request->session_1_time,
                'venue' => $request->session_1_venue,
                'address' => $request->session_1_address,
                'maps_url' => $request->session_1_maps_url,
            ],
            'session_2' => [
                'enabled' => $request->boolean('session_2_enabled'),
                'title' => $request->session_2_title,
                'date' => $request->session_2_date,
                'time' => $request->session_2_time,
                'venue' => $request->session_2_venue,
                'address' => $request->session_2_address,
                'maps_url' => $request->session_2_maps_url,
            ],
            'rundown' => $rundown,
            'cover_image' => $coverPath,
            'gallery_images' => $galleryPaths,
            'gift_accounts' => $giftAccounts,
            'music_mode' => $request->music_mode ?? 'ambient',
            'music_file' => $musicPath,
        ];

        $invitation = Invitation::create([
            'slug' => $slug,
            'edit_token' => $editToken,
            'category' => $request->category,
            'theme_slug' => $request->theme_slug,
            'title' => $request->title,
            'event_data' => $eventData,
        ]);

        return redirect()->route('invitation.success', $invitation->slug);
    }

    /**
     * Halaman Sukses & Generator Link Tamu
     */
    public function success($slug)
    {
        $invitation = Invitation::where('slug', $slug)->firstOrFail();
        return view('success', compact('invitation'));
    }

    /**
     * Halaman Edit Mandiri Pembuat Undangan
     */
    public function manage($slug, $token)
    {
        $invitation = Invitation::where('slug', $slug)
            ->where('edit_token', $token)
            ->firstOrFail();

        $wishes = $invitation->wishes;
        $hadirCount = $wishes->where('attendance', 'hadir')->count();
        $tidakHadirCount = $wishes->where('attendance', 'tidak_hadir')->count();
        $themes = Theme::where('is_active', true)->get();

        return view('manage', compact('invitation', 'wishes', 'hadirCount', 'tidakHadirCount', 'themes'));
    }

    /**
     * Update data undangan dari Halaman Kelola
     */
    public function update(Request $request, $slug, $token)
    {
        $invitation = Invitation::where('slug', $slug)
            ->where('edit_token', $token)
            ->firstOrFail();

        $request->validate([
            'category' => 'required|string|in:pernikahan,tunangan,bisnis,lainnya',
            'theme_slug' => 'required|string|exists:themes,slug',
            'title' => 'required|string|max:255',
            'primary_name' => 'required|string|max:255',
            'primary_title_parents' => 'nullable|string|max:255',
            'primary_instagram' => 'nullable|string|max:255',
            'secondary_name' => 'nullable|string|max:255',
            'secondary_title_parents' => 'nullable|string|max:255',
            'secondary_instagram' => 'nullable|string|max:255',
            'greeting' => 'nullable|string|max:255',
            'quote' => 'nullable|string',
            'session_1_title' => 'required|string|max:255',
            'session_1_date' => 'required|date',
            'session_1_time' => 'required|string|max:100',
            'session_1_venue' => 'required|string|max:255',
            'session_1_address' => 'required|string',
            'session_1_maps_url' => 'nullable|string|max:500',
            'session_2_title' => 'nullable|string|max:255',
            'session_2_date' => 'nullable|date',
            'session_2_time' => 'nullable|string|max:100',
            'session_2_venue' => 'nullable|string|max:255',
            'session_2_address' => 'nullable|string',
            'session_2_maps_url' => 'nullable|string|max:500',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery_images' => 'nullable|array|max:4',
            'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'music_mode' => 'required|string|in:none,ambient,custom',
            'music_file' => 'nullable|file|mimes:mp3,wav,ogg,audio/mpeg|max:5120',
        ]);

        $currentData = $invitation->event_data ?? [];

        // Update Cover
        $coverPath = $currentData['cover_image'] ?? null;
        if ($request->hasFile('cover_image')) {
            if ($coverPath && Storage::disk('public')->exists($coverPath)) {
                Storage::disk('public')->delete($coverPath);
            }
            $coverPath = $request->file('cover_image')->store('invitations/covers', 'public');
        }

        // Update Gallery Images
        $galleryPaths = $currentData['gallery_images'] ?? [];
        if ($request->boolean('clear_gallery')) {
            foreach ($galleryPaths as $oldPath) {
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $galleryPaths = [];
        }

        if ($request->hasFile('gallery_images')) {
            foreach (array_slice($request->file('gallery_images'), 0, 4) as $photo) {
                if ($photo && count($galleryPaths) < 4) {
                    $galleryPaths[] = $photo->store('invitations/galleries', 'public');
                }
            }
        }

        // Update Custom Music
        $musicPath = $currentData['music_file'] ?? null;
        if ($request->music_mode === 'custom' && $request->hasFile('music_file')) {
            if ($musicPath && Storage::disk('public')->exists($musicPath)) {
                Storage::disk('public')->delete($musicPath);
            }
            $musicPath = $request->file('music_file')->store('invitations/music', 'public');
        } elseif ($request->music_mode !== 'custom' && $musicPath) {
            if (Storage::disk('public')->exists($musicPath)) {
                Storage::disk('public')->delete($musicPath);
            }
            $musicPath = null;
        }

        // Parse Gift Accounts
        $giftAccounts = [];
        if ($request->has('gift_accounts') && is_array($request->gift_accounts)) {
            foreach ($request->gift_accounts as $acc) {
                if (!empty($acc['bank']) && !empty($acc['account_number'])) {
                    $giftAccounts[] = [
                        'bank' => trim($acc['bank']),
                        'account_number' => trim($acc['account_number']),
                        'account_name' => trim($acc['account_name'] ?? ''),
                    ];
                }
            }
        }

        // Parse Rundown / Agenda
        $rundown = [];
        if ($request->has('rundown') && is_array($request->rundown)) {
            foreach ($request->rundown as $item) {
                if (!empty($item['title'])) {
                    $rundown[] = [
                        'time' => trim($item['time'] ?? ''),
                        'title' => trim($item['title']),
                        'description' => trim($item['description'] ?? ''),
                    ];
                }
            }
        }

        $eventData = [
            'greeting' => $request->greeting,
            'quote' => $request->quote,
            'primary_name' => $request->primary_name,
            'primary_title_parents' => $request->primary_title_parents,
            'primary_instagram' => $request->primary_instagram,
            'secondary_name' => $request->secondary_name,
            'secondary_title_parents' => $request->secondary_title_parents,
            'secondary_instagram' => $request->secondary_instagram,
            'session_1' => [
                'title' => $request->session_1_title,
                'date' => $request->session_1_date,
                'time' => $request->session_1_time,
                'venue' => $request->session_1_venue,
                'address' => $request->session_1_address,
                'maps_url' => $request->session_1_maps_url,
            ],
            'session_2' => [
                'enabled' => $request->boolean('session_2_enabled'),
                'title' => $request->session_2_title,
                'date' => $request->session_2_date,
                'time' => $request->session_2_time,
                'venue' => $request->session_2_venue,
                'address' => $request->session_2_address,
                'maps_url' => $request->session_2_maps_url,
            ],
            'rundown' => $rundown,
            'cover_image' => $coverPath,
            'gallery_images' => $galleryPaths,
            'gift_accounts' => $giftAccounts,
            'music_mode' => $request->music_mode ?? 'ambient',
            'music_file' => $musicPath,
        ];

        $invitation->update([
            'category' => $request->category,
            'theme_slug' => $request->theme_slug,
            'title' => $request->title,
            'event_data' => $eventData,
        ]);

        return redirect()->route('invitation.manage', [
            'slug' => $slug,
            'token' => $token,
        ])->with('success', 'Data undangan berhasil diperbarui!');
    }

    /**
     * Halaman Publik Undangan (/u/{slug})
     */
    public function show(Request $request, $slug)
    {
        $invitation = Invitation::with(['theme', 'wishes'])->where('slug', $slug)->firstOrFail();

        $viewName = 'themes.' . $invitation->theme_slug;
        if (!view()->exists($viewName)) {
            $viewName = 'themes._base-template';
        }

        $guestName = $request->query('to', '');

        $wishes = $invitation->wishes;
        $wishesData = $wishes->map(fn($w) => [
            'id' => $w->id,
            'guest_name' => $w->guest_name,
            'attendance' => $w->attendance,
            'message' => $w->message,
            'time_ago' => $w->created_at->diffForHumans(),
        ])->values()->all();

        $initialCounts = [
            'total' => $wishes->count(),
            'hadir' => $wishes->where('attendance', 'hadir')->count(),
            'tidak_hadir' => $wishes->where('attendance', 'tidak_hadir')->count(),
        ];

        $session1 = $invitation->event_data['session_1'] ?? [];
        $date = $session1['date'] ?? date('Y-m-d');
        $timeParts = explode(' - ', $session1['time'] ?? '09:00');
        $targetDate = $date . 'T' . ($timeParts[0] ?? '09:00');

        return view($viewName, compact('invitation', 'guestName', 'wishesData', 'initialCounts', 'targetDate'));
    }

    /**
     * Simpan Ucapan & Konfirmasi Kehadiran via AJAX
     */
    public function storeWish(Request $request, $slug)
    {
        $invitation = Invitation::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'guest_name' => 'required|string|max:100',
            'attendance' => 'required|in:hadir,tidak_hadir',
            'message' => 'required|string|max:1000',
        ]);

        $wish = $invitation->wishes()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Ucapan & konfirmasi kehadiran Anda berhasil dikirim!',
            'wish' => [
                'id' => $wish->id,
                'guest_name' => $wish->guest_name,
                'attendance' => $wish->attendance,
                'message' => $wish->message,
                'time_ago' => $wish->created_at->diffForHumans(),
            ],
            'counts' => [
                'total' => $invitation->wishes()->count(),
                'hadir' => $invitation->wishes()->where('attendance', 'hadir')->count(),
                'tidak_hadir' => $invitation->wishes()->where('attendance', 'tidak_hadir')->count(),
            ],
        ]);
    }
}
