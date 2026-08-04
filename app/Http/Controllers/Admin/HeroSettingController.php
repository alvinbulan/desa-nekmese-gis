<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class HeroSettingController extends Controller
{
    public function edit()
    {
        $heroBg = Setting::getValue('hero_bg_image');
        return view('admin.settings.hero', compact('heroBg'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $oldValue = Setting::getValue('hero_bg_image');

        if ($request->hasFile('hero_image')) {
            // Delete old file
            if ($oldValue && \Storage::disk('public')->exists($oldValue)) {
                \Storage::disk('public')->delete($oldValue);
            }

            $ext = strtolower($request->file('hero_image')->extension());
            $path = $request->file('hero_image')->storeAs('hero', 'beranda-hero-' . now()->timestamp . '.' . $ext, 'public');
            Setting::setValue('hero_bg_image', $path);
        } elseif ($request->boolean('remove_image')) {
            // Remove image, fall back to default
            if ($oldValue && \Storage::disk('public')->exists($oldValue)) {
                \Storage::disk('public')->delete($oldValue);
            }
            Setting::setValue('hero_bg_image', null);
        }

        return redirect()->route('admin.settings.hero')
            ->with('success', 'Gambar latar hero berhasil diperbarui.');
    }
}
