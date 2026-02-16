<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariationGroup extends Model
{
    use HasFactory;
    protected $table = 'variation_groups';

    protected $fillable = ['title', 'slug', 'active'];
}