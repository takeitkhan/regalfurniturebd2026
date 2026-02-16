<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $fillable = [
        'com_name', 'com_slogan', 'com_eshtablished', 'com_licensecode', 'com_logourl', 'header_bg', 'com_headerurl','order_phone','com_phone', 'com_email', 'com_address', 'com_addressgooglemap', 'com_website',
        'com_analytics', 'com_chat_box', 'com_metatitle', 'com_metadescription', 'com_metakeywords', 'com_workinghours', 'com_adminname', 'com_adminphone', 'com_adminemail', 'com_adminphotourl',
        'com_facebookpageid', 'com_favicon', 'com_timezone', 'special_notification_product_single_page', 'showroom_location_popup'
    ];
}
