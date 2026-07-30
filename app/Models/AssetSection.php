<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetSection extends Model
{
    protected $fillable = ['main_image', 'sub_image'];

    protected $table = 'section_assets';
}
