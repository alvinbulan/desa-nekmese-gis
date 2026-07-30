<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SidebarMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SidebarMenuController extends Controller
{
    public function index()
    {
        $menus = SidebarMenu::ordered()->get();
        return view('admin.sidebar-menus.index', compact('menus'));
    }

    public function edit(SidebarMenu $sidebarMenu)
    {
        return view('admin.sidebar-menus.form', compact('sidebarMenu'));
    }

    public function update(Request $request, SidebarMenu $sidebarMenu)
    {
        $request->validate([
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'menu_name' => 'required|string|max:255',
            'icon_name' => 'nullable|string|max:100',
            'target_link' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'heading_text' => 'nullable|string|max:500',
        ]);

        $data = $request->only(['menu_name', 'icon_name', 'target_link', 'sort_order', 'active', 'heading_text']);

        $slug = Str::slug($sidebarMenu->menu_name);
        $timestamp = now()->timestamp;

        // Handle banner image — unique name per menu
        if ($request->hasFile('banner_image')) {
            if ($sidebarMenu->banner_image_url && \Storage::disk('public')->exists($sidebarMenu->banner_image_url)) {
                \Storage::disk('public')->delete($sidebarMenu->banner_image_url);
            }
            $ext = $request->file('banner_image')->extension();
            $data['banner_image_url'] = $request->file('banner_image')->storeAs('headers', $slug . '-banner-' . $timestamp . '.' . $ext, 'public');
        } elseif ($request->boolean('remove_banner')) {
            if ($sidebarMenu->banner_image_url && \Storage::disk('public')->exists($sidebarMenu->banner_image_url)) {
                \Storage::disk('public')->delete($sidebarMenu->banner_image_url);
            }
            $data['banner_image_url'] = null;
        }

        // Handle background (header) image — unique name per menu
        if ($request->hasFile('background_image')) {
            if ($sidebarMenu->background_image_url && \Storage::disk('public')->exists($sidebarMenu->background_image_url)) {
                \Storage::disk('public')->delete($sidebarMenu->background_image_url);
            }
            $ext = $request->file('background_image')->extension();
            $data['background_image_url'] = $request->file('background_image')->storeAs('headers', $slug . '-' . $timestamp . '.' . $ext, 'public');
        } elseif ($request->boolean('remove_background')) {
            if ($sidebarMenu->background_image_url && \Storage::disk('public')->exists($sidebarMenu->background_image_url)) {
                \Storage::disk('public')->delete($sidebarMenu->background_image_url);
            }
            $data['background_image_url'] = null;
        }

        $data['active'] = $request->boolean('active');
        $sidebarMenu->update($data);

        return redirect()->route('admin.sidebar-menus.index')
            ->with('success', 'Menu "' . $sidebarMenu->menu_name . '" berhasil diperbarui.');
    }
}
