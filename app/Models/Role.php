<?php

namespace App\Models;

//use App\Repositories\RUser\RUserInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Role extends Model
{

    protected $table = 'roles';
    protected $fillable = [
        'name', 'description'
    ];

    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }

    public function getById($id)
    {
        return $this->find($id);
    }

    public function get_all()
    {
        return $this->get();
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}
