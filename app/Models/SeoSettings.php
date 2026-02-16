<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoSettings extends Model
{
    use HasFactory;
    protected $table = 'seo_settings';
    protected $fillable = ['post_id', 'post_type', 'seo_meta'];

    public static function getMeta($id){
        $data = SeoSettings::where('id', $id)->first();
        $data = json_decode($data->seo_meta) ?? null;
        return $data ?? null;
    }
}
