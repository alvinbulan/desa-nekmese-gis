<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Regulation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RegulationController extends Controller
{
    public function index()
    {
        $regulations = Regulation::latest()->paginate(15);
        return view('admin.regulations.index', compact('regulations'));
    }

    public function create()
    {
        return view('admin.regulations.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|in:peraturan,keputusan',
            'tanggal' => 'nullable|date',
            'file' => 'nullable|mimes:pdf,doc,docx|max:10240',
            'active' => 'boolean',
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('regulations', 'public');
        }
        $validated['active'] = $request->boolean('active', true);

        Regulation::create($validated);

        return redirect()->route('admin.regulations.index')
            ->with('success', 'Peraturan berhasil ditambahkan.');
    }

    public function edit(Regulation $regulation)
    {
        return view('admin.regulations.form', compact('regulation'));
    }

    public function update(Request $request, Regulation $regulation)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|in:peraturan,keputusan',
            'tanggal' => 'nullable|date',
            'file' => 'nullable|mimes:pdf,doc,docx|max:10240',
            'active' => 'boolean',
        ]);

        if ($request->hasFile('file')) {
            if ($regulation->file_path) Storage::disk('public')->delete($regulation->file_path);
            $validated['file_path'] = $request->file('file')->store('regulations', 'public');
        }
        $validated['active'] = $request->boolean('active', true);

        $regulation->update($validated);

        return redirect()->route('admin.regulations.index')
            ->with('success', 'Peraturan berhasil diperbarui.');
    }

    public function destroy(Regulation $regulation)
    {
        if ($regulation->file_path) Storage::disk('public')->delete($regulation->file_path);
        $regulation->delete();

        return redirect()->route('admin.regulations.index')
            ->with('success', 'Peraturan berhasil dihapus.');
    }
}
