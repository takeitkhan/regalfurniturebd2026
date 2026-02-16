<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class FlashShedule extends Model
{
    protected $table = 'flash_schedules';
    protected $fillable = [
        'fs_name', 'fs_description', 'fs_start_date', 'fs_end_date','fs_price_time', 'fs_is_active'
    ];

    protected $appends = ['show_price','timestamp'];

    public function getShowPriceAttribute()
    {
        return $this->fs_price_time <= Carbon::now('Asia/Dhaka');
    }

    public function getTimeStampAttribute()
    {
        return strtotime($this->fs_end_date);
    }
}
