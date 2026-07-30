<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->paginate(15);
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'nullable|string',
            'tipe' => 'required|in:pengumuman,kegiatan',
            'tanggal' => 'nullable|date',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'active' => 'boolean',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('announcements', 'public');
        }
        $validated['active'] = $request->boolean('active', true);

        Announcement::create($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.form', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'nullable|string',
            'tipe' => 'required|in:pengumuman,kegiatan',
            'tanggal' => 'nullable|date',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'active' => 'boolean',
        ]);

        if ($request->hasFile('gambar')) {
            if ($announcement->gambar) \Storage::disk('public')->delete($announcement->gambar);
            $validated['gambar'] = $request->file('gambar')->store('announcements', 'public');
        }
        $validated['active'] = $request->boolean('active', true);

        $announcement->update($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Announcement $announcement)
    {
        if ($announcement->gambar) \Storage::disk('public')->delete($announcement->gambar);
        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}
