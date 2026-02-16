<?php

namespace App\Models;

use App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Page extends Model
{
    protected $table = 'pages';
    protected $fillable = [
        'user_id', 'title', 'sub_title', 'seo_url', 'description', 'images', 'is_sticky', 'lang', 'is_active'
    ];

    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table = new Post();
            $table->title = 'This is a page';
            $table->sub_title = 'this is a page sub title';
            $table->seo_url = 'this-is-a-page-sub-title';
            $table->description = 'none for now';
            $table->images = '1';
            $table->is_sticky = 1;
            $table->lang = 'en';
            $table->is_active = 1;
            $table->save();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pages');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attributes()
    {
        return \App\Models\Page::hasMany('App\Attribute', 'post_id', 'id');
        //return App\Page::hasMany('App\Attribute');
        //return App\Page::hasMany(Attribute::class);
    }
}
