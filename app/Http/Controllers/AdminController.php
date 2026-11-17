<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\InvitationWish;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Tampilkan form login admin
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    /**
     * Proses login admin
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }

    /**
     * Logout admin
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    /**
     * Dashboard statistik & ringkasan
     */
    public function dashboard()
    {
        $totalInvitations = Invitation::count();
        $totalHadir = InvitationWish::where('attendance', 'hadir')->count();
        $totalWishes = InvitationWish::count();
        $totalActiveThemes = Theme::where('is_active', true)->count();

        $recentInvitations = Invitation::with('theme')
            ->withCount('wishes')
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalInvitations',
            'totalHadir',
            'totalWishes',
            'totalActiveThemes',
            'recentInvitations'
        ));
    }

    /**
     * Daftar seluruh undangan & moderasi ucapan
     */
    public function invitations(Request $request)
    {
        $query = Invitation::with(['theme', 'wishes'])->withCount('wishes');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category') && $request->category !== 'semua') {
            $query->where('category', $request->category);
        }

        $invitations = $query->latest()->paginate(12)->withQueryString();
        $categories = ['pernikahan', 'tunangan', 'bisnis', 'lainnya'];

        return view('admin.invitations', compact('invitations', 'categories'));
    }

    /**
     * Hapus undangan beserta seluruh berkas gambar dan musik
     */
    public function destroyInvitation($id)
    {
        $invitation = Invitation::findOrFail($id);
        $eventData = $invitation->event_data ?? [];

        // Hapus Cover
        if (!empty($eventData['cover_image']) && Storage::disk('public')->exists($eventData['cover_image'])) {
            Storage::disk('public')->delete($eventData['cover_image']);
        }

        // Hapus Gallery
        if (!empty($eventData['gallery_images']) && is_array($eventData['gallery_images'])) {
            foreach ($eventData['gallery_images'] as $photo) {
                if (Storage::disk('public')->exists($photo)) {
                    Storage::disk('public')->delete($photo);
                }
            }
        }

        // Hapus Custom Music
        if (!empty($eventData['music_file']) && Storage::disk('public')->exists($eventData['music_file'])) {
            Storage::disk('public')->delete($eventData['music_file']);
        }

        $title = $invitation->title;
        $invitation->delete();

        return back()->with('success', "Undangan \"{$title}\" beserta seluruh berkas berhasil dihapus.");
    }

    /**
     * Hapus ucapan tamu (moderasi spam)
     */
    public function destroyWish($id)
    {
        $wish = InvitationWish::findOrFail($id);
        $wish->delete();

        return back()->with('success', 'Ucapan tamu berhasil dihapus.');
    }

    /**
     * Manajemen tema extensible
     */
    public function themes()
    {
        $themes = Theme::withCount('invitations')->get();
        return view('admin.themes', compact('themes'));
    }

    /**
     * Toggle status aktif tema
     */
    public function toggleTheme($id)
    {
        $theme = Theme::findOrFail($id);
        $theme->is_active = !$theme->is_active;
        $theme->save();

        $statusText = $theme->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Tema \"{$theme->name}\" berhasil {$statusText}.");
    }

    /**
     * Tambah tema baru & otomatis kloning file Blade sumber
     */
    public function storeTheme(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|alpha_dash|max:50|unique:themes,slug',
            'category' => 'required|string|in:pernikahan,tunangan,bisnis,semua',
            'accent_color' => 'required|string|regex:/^#([a-fA-F0-9]{3}|[a-fA-F0-9]{6})$/',
            'description' => 'nullable|string|max:500',
            'source_theme' => 'required|string',
        ]);

        $newSlug = Str::slug($request->slug);
        $sourceTheme = $request->source_theme;

        $sourcePath = resource_path("views/themes/{$sourceTheme}.blade.php");
        $destPath = resource_path("views/themes/{$newSlug}.blade.php");

        // Fallback sumber jika file pilihan tidak ditemukan
        if (!File::exists($sourcePath)) {
            $sourcePath = resource_path("views/themes/_base-template.blade.php");
        }

        // Kloning file Blade jika belum ada
        if (File::exists($sourcePath) && !File::exists($destPath)) {
            File::copy($sourcePath, $destPath);
        }

        Theme::create([
            'name' => $request->name,
            'slug' => $newSlug,
            'category' => $request->category,
            'accent_color' => $request->accent_color,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return back()->with('success', "Tema \"{$request->name}\" berhasil dibuat dan file template \"{$newSlug}.blade.php\" siap dikustomisasi.");
    }
}
