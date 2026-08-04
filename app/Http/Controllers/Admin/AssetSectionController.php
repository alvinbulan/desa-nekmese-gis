<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssetSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetSectionController extends Controller
{
    public function edit()
    {
        $data = AssetSection::first();
        return view('admin.settings.aset-section', [
            'mainImage' => $data?->main_image,
            'subImage' => $data?->sub_image,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'sub_image'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $data = AssetSection::first() ?? new AssetSection();

        if ($request->hasFile('main_image')) {
            if ($data->main_image && Storage::disk('public')->exists($data->main_image)) {
                Storage::disk('public')->delete($data->main_image);
            }
            $data->main_image = $request->file('main_image')->store('section-assets', 'public');
            $data->main_image = substr($data->main_image, 0, strrpos($data->main_image, '.')) . strtolower(substr($data->main_image, strrpos($data->main_image, '.')));
        }

        if ($request->hasFile('sub_image')) {
            if ($data->sub_image && Storage::disk('public')->exists($data->sub_image)) {
                Storage::disk('public')->delete($data->sub_image);
            }
            $data->sub_image = $request->file('sub_image')->store('section-assets', 'public');
            $data->sub_image = substr($data->sub_image, 0, strrpos($data->sub_image, '.')) . strtolower(substr($data->sub_image, strrpos($data->sub_image, '.')));
        }

        if ($request->boolean('remove_main_image')) {
            if ($data->main_image && Storage::disk('public')->exists($data->main_image)) {
                Storage::disk('public')->delete($data->main_image);
            }
            $data->main_image = null;
        }

        if ($request->boolean('remove_sub_image')) {
            if ($data->sub_image && Storage::disk('public')->exists($data->sub_image)) {
                Storage::disk('public')->delete($data->sub_image);
            }
            $data->sub_image = null;
        }

        $data->save();

        return redirect()->back()->with('success', 'Perubahan berhasil disimpan!');
    }
}
