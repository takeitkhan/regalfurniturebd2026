<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';
    protected $fillable = [
        'user_id', 'item_id', 'comment_on', 'commenter', 'commenter_photo', 'commenter_email',
        'commenter_phone', 'comment', 'parent_id', 'is_active'
    ];


    public function user(){

        return $this->belongsTo(User::class,'user_id','id');
    }


    public function product()
    {
        return $this->hasOne(User::class,'item_id','id');
    }
}
