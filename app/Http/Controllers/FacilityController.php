<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\AssetSection;
use App\Models\Facility;
use App\Models\Regulation;
use App\Models\Setting;
use App\Models\SidebarMenu;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::active()->with('photos')->get();
        $kategoriList = Facility::kategoriList();
        $jenisList = Facility::jenisList();
        $sektorList = Facility::sektorList();
        $heroBg = Setting::getValue('hero_bg_image');
        $heroSetting = Setting::where('key', 'hero_bg_image')->first();
        $heroTs = $heroSetting?->updated_at?->timestamp ?? time();
        $sectionHeaders = SidebarMenu::active()->ordered()->where('menu_name', '!=', 'Beranda')->get()->keyBy(function($m) {
            return ltrim($m->target_link, '/');
        });
        $asetSection = AssetSection::first();
        $asetMainImage = $asetSection?->main_image;
        $asetSubImage = $asetSection?->sub_image;

        $totalSD = Facility::active()->where(function ($query) {
            $query->where('nama', 'LIKE', '%SD%')
                ->orWhere('nama', 'LIKE', '%Sekolah Dasar%');
        })->count();

        $totalSMP = Facility::active()->where(function ($query) {
            $query->where('nama', 'LIKE', '%SMP%')
                ->orWhere('nama', 'LIKE', '%Sekolah Menengah Pertama%');
        })->count();

        $totalSMA = Facility::active()->where(function ($query) {
            $query->where('nama', 'LIKE', '%SMA%')
                ->orWhere('nama', 'LIKE', '%SMK%')
                ->orWhere('nama', 'LIKE', '%Sekolah Menengah Atas%');
        })->count();

        return view('gis.index', compact('facilities', 'kategoriList', 'jenisList', 'sektorList', 'heroBg', 'heroTs', 'sectionHeaders', 'asetMainImage', 'asetSubImage', 'totalSD', 'totalSMP', 'totalSMA'));
    }

    public function semuaFasilitas()
    {
        $facilities = Facility::active()->with('photos')->get();
        $kategoriList = Facility::kategoriList();
        $jenisList = Facility::jenisList();
        $sektorList = Facility::sektorList();
        $pageTitle = 'Semua Fasilitas';
        $pageSubtitle = 'Seluruh fasilitas umum yang tersedia di Desa Nekmese';
        $currentFilter = '';
        return view('gis.kategori', compact('facilities', 'kategoriList', 'jenisList', 'sektorList', 'pageTitle', 'pageSubtitle', 'currentFilter'));
    }

    public function kategori()
    {
        $filter = request()->route()->parameter('_filter', '');
        $configs = [
            'aset_desa'     => ['title' => 'Aset Desa', 'subtitle' => 'Aset milik Desa Nekmese'],
            'Pendidikan'    => ['title' => 'Fasilitas Pendidikan', 'subtitle' => 'Sarana pendidikan di Desa Nekmese'],
            'Kesehatan'     => ['title' => 'Fasilitas Kesehatan', 'subtitle' => 'Sarana kesehatan di Desa Nekmese'],
            'Tempat Ibadah' => ['title' => 'Tempat Ibadah', 'subtitle' => 'Sarana ibadah di Desa Nekmese'],
        ];

        if (!isset($configs[$filter])) {
            abort(404);
        }

        if ($filter === 'aset_desa') {
            $asetKategori = array_keys(array_filter(Facility::jenisList(), fn($j) => $j === 'aset_desa'));
            $facilities = Facility::active()->with('photos')->whereIn('kategori', $asetKategori)->get();
        } else {
            $sektorKategori = array_keys(array_filter(Facility::sektorList(), fn($s) => $s === $filter));
            $facilities = Facility::active()->with('photos')->whereIn('kategori', $sektorKategori)->get();
        }

        $kategoriList = Facility::kategoriList();
        $jenisList = Facility::jenisList();
        $sektorList = Facility::sektorList();
        $pageTitle = $configs[$filter]['title'];
        $pageSubtitle = $configs[$filter]['subtitle'];
        $currentFilter = $filter;

        $slugMap = ['aset_desa' => 'aset-desa', 'Pendidikan' => 'pendidikan', 'Kesehatan' => 'kesehatan', 'Tempat Ibadah' => 'ibadah'];
        $sectionHeader = SidebarMenu::where('target_link', '/' . ($slugMap[$filter] ?? ''))->first();

        return view('gis.kategori', compact('facilities', 'kategoriList', 'jenisList', 'sektorList', 'pageTitle', 'pageSubtitle', 'currentFilter', 'sectionHeader'));
    }

    public function placeholder()
    {
        $routeName = request()->route()->getName();
        $isPengumuman = $routeName === 'fasilitas.pengumuman';
        $pageTitle = $isPengumuman ? 'Pengumuman' : 'Peraturan Desa';

        if ($isPengumuman) {
            $items = Announcement::active()->latest()->paginate(10);
            $pageSubtitle = 'Informasi dan pengumuman terbaru dari Pemerintah Desa Nekmese';
        } else {
            $items = Regulation::active()->latest()->paginate(10);
            $pageSubtitle = 'Dokumen peraturan dan keputusan Desa Nekmese';
        }

        return view('gis.placeholder', compact('pageTitle', 'pageSubtitle', 'items'));
    }

    public function apiIndex()
    {
        return response()->json(Facility::active()->with('photos')->get());
    }

    public function apiShow(Facility $facility)
    {
        return response()->json($facility->load('photos'));
    }
}
