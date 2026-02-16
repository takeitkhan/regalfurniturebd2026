<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpGenerate extends Model
{
    use HasFactory;
    protected $table = 'otp_generate';
    protected $fillable = ['user_id', 'code', 'expired_at'];
}
