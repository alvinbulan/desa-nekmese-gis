<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\FacilityPhoto;
use Illuminate\Http\Request;

class FacilityPhotoController extends Controller
{
    public function upload(Request $request, Facility $facility)
    {
        $request->validate([
            'photos' => 'required|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $uploaded = [];

        foreach ($request->file('photos') as $file) {
            $path = $file->store('facilities/photos', 'public');
            $path = substr($path, 0, strrpos($path, '.')) . strtolower(substr($path, strrpos($path, '.')));
            $photo = $facility->photos()->create(['photo_path' => $path]);
            $uploaded[] = ['id' => $photo->id, 'url' => $photo->photo_url];
        }

        return response()->json(['photos' => $uploaded]);
    }

    public function destroy(Facility $facility, FacilityPhoto $photo)
    {
        if ($photo->facility_id !== $facility->id) {
            abort(404);
        }

        $photo->delete();

        return response()->json(['success' => true]);
    }

    public function index(Facility $facility)
    {
        $photos = $facility->photos()->orderBy('id')->get()->map(fn($p) => [
            'id' => $p->id,
            'url' => $p->photo_url,
        ]);

        return response()->json(['photos' => $photos]);
    }
}
