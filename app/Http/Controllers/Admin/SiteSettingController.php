<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function edit()
    {
        $logoUrl = Setting::getValue('logo_url');
        $faviconUrl = Setting::getValue('favicon_url');
        return view('admin.settings.site', compact('logoUrl', 'faviconUrl'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:3072',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,ico,webp|max:1024',
        ]);

        // Handle Logo
        if ($request->hasFile('logo')) {
            $oldLogo = Setting::getValue('logo_url');
            if ($oldLogo && \Storage::disk('public')->exists($oldLogo)) {
                \Storage::disk('public')->delete($oldLogo);
            }
            $path = $request->file('logo')->store('site/logo', 'public');
            $path = substr($path, 0, strrpos($path, '.')) . strtolower(substr($path, strrpos($path, '.')));
            Setting::setValue('logo_url', $path);
        } elseif ($request->boolean('remove_logo')) {
            $oldLogo = Setting::getValue('logo_url');
            if ($oldLogo && \Storage::disk('public')->exists($oldLogo)) {
                \Storage::disk('public')->delete($oldLogo);
            }
            Setting::setValue('logo_url', null);
        }

        // Handle Favicon
        if ($request->hasFile('favicon')) {
            $oldFavicon = Setting::getValue('favicon_url');
            if ($oldFavicon && \Storage::disk('public')->exists($oldFavicon)) {
                \Storage::disk('public')->delete($oldFavicon);
            }
            $path = $request->file('favicon')->store('site/favicon', 'public');
            $path = substr($path, 0, strrpos($path, '.')) . strtolower(substr($path, strrpos($path, '.')));
            Setting::setValue('favicon_url', $path);
        } elseif ($request->boolean('remove_favicon')) {
            $oldFavicon = Setting::getValue('favicon_url');
            if ($oldFavicon && \Storage::disk('public')->exists($oldFavicon)) {
                \Storage::disk('public')->delete($oldFavicon);
            }
            Setting::setValue('favicon_url', null);
        }

        return redirect()->route('admin.settings.site')
            ->with('success', 'Pengaturan identitas desa berhasil diperbarui.');
    }
}
