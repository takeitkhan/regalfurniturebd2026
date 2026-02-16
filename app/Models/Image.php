<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['file_name', 'file_type', 'file_size', 'file_extension', 'file_directory', 'status', 'user_id'];

    public static $rules = [
        'file' => 'required|mimes:png,gif,jpeg,jpg,bmp,webp'
    ];
    
    public static $messages = [
        'file.mimes' => 'Uploaded file is not in image format',
        'file.required' => 'Image is required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function attribute()
    {
        return $this->belongsTo('App\Models\Attribute');
    }

}
