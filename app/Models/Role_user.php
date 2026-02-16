<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Role_user extends Model
{
    protected $table = 'role_user';
    protected $fillable = [
        'role_id', 'user_id'
    ];

    public function up()
    {
        Schema::create('role_user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('role_id');
            $table->string('user_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('role_user');
    }

    public function roles()
    {
        return $this->belongsTo('App\Models\Role');
    }
}
