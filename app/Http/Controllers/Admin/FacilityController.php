<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\FacilityPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FacilityController extends Controller
{
    public function index(Request $request)
    {
        $query = Facility::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($kategori = $request->get('kategori')) {
            $query->where('kategori', $kategori);
        }

        $facilities = $query->latest()->paginate(15)->withQueryString();
        $kategoriList = Facility::kategoriList();

        return view('admin.facilities.index', compact('facilities', 'kategoriList'));
    }

    public function create()
    {
        $kategoriList = Facility::kategoriList();
        return view('admin.facilities.form', compact('kategoriList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|in:' . implode(',', array_keys(Facility::kategoriList())),
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'alamat' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'active' => 'boolean',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $validated['active'] = $request->boolean('active');

        $facility = Facility::create($validated);

        // Simpan foto yang diupload bersama form (CREATE mode)
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('facilities/photos', 'public');
                $path = substr($path, 0, strrpos($path, '.')) . strtolower(substr($path, strrpos($path, '.')));
                $facility->photos()->create(['photo_path' => $path]);
            }
        }

        return redirect()->route('admin.facilities.index')
            ->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function edit(Facility $facility)
    {
        $kategoriList = Facility::kategoriList();
        return view('admin.facilities.form', compact('facility', 'kategoriList'));
    }

    public function update(Request $request, Facility $facility)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|in:' . implode(',', array_keys(Facility::kategoriList())),
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'alamat' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'active' => 'boolean',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $validated['active'] = $request->boolean('active');

        $facility->update($validated);

        // Simpan foto yang diupload bersama form (fallback jika AJAX tidak digunakan)
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('facilities/photos', 'public');
                $path = substr($path, 0, strrpos($path, '.')) . strtolower(substr($path, strrpos($path, '.')));
                $facility->photos()->create(['photo_path' => $path]);
            }
        }

        return redirect()->route('admin.facilities.index')
            ->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy(Facility $facility)
    {
        $facility->delete();

        return redirect()->route('admin.facilities.index')
            ->with('success', 'Fasilitas berhasil dihapus.');
    }

    /**
     * Hapus satu gambar tertentu pada fasilitas tanpa menghapus fasilitas itu sendiri.
     *
     * Multi-gambar disimpan pada tabel relasi facility_photos (satu baris per foto).
     * Method menerima photo_id untuk menemukan record yang tepat, menghapus file fisik
     * di disk public (public/images), lalu menghapus record dari database.
     */
    public function destroyImage(Request $request, $facilityId)
    {
        $facility = Facility::findOrFail($facilityId);

        $photo = FacilityPhoto::where('facility_id', $facility->id)
            ->findOrFail($request->input('photo_id'));

        if ($photo->photo_path) {
            $fullPath = public_path('images/' . ltrim($photo->photo_path, '/'));
            if (is_file($fullPath)) {
                \Illuminate\Support\Facades\File::delete($fullPath);
            }
        }

        $photo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Gambar berhasil dihapus!',
        ]);
    }
}
