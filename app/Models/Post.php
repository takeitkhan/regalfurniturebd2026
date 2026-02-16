<?php

namespace App\Models;

use App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Post extends Model
{
    protected $table = 'posts';
    protected $fillable = [
        'user_id', 'title', 'sub_title', 'seo_url', 'author', 'description', 'categories', 'images', 'brand', 'tags', 'youtube',
        'is_auto_post', 'short_description', 'is_upcoming', 'phone', 'opening_hours', 'latitude', 'longitude', 'phone_numbers', 'address',
        'is_sticky', 'division', 'district', 'thana', 'shop_type','qty','unit','lang', 'is_active'
    ];

    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table = new Post();
            $table->title = 'This is a post';
            $table->sub_title = 'this is a post sub title';
            $table->seo_url = 'this-is-a-post-sub-title';
            $table->author = null;
            $table->description = 'none for now';
            $table->categories = null;
            $table->images = null;
            $table->brand = null;
            $table->tags = null;
            $table->youtube = null;
            $table->is_auto_post = null;
            $table->short_description = null;
            $table->opening_hours = null;
            $table->latitude = null;
            $table->longitude = null;
            $table->phone_numbers = null;
            $table->division = null;
            $table->district = null;
            $table->thana = null;
            $table->shop_type = null;
            $table->is_upcoming = null;
            $table->address = null;
            $table->is_sticky = 1;
            $table->lang = 'en';
            $table->is_active = 1;
            $table->save();
        });
    }

    public function down()
    {
        Schema::dropIfExists('posts');
    }

}
