<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    protected $table = 'paymentsettings';
    protected $fillable = [
        'admin_cell_one', 'admin_cell_two', 'admin_cell_three', 'admin_cell_four', 'admin_cell_five',
        'bkash_active', 'image_bkash', 'nagad_active', 'image_nagad', 'debitcredit_active', 'image_debitcredit', 'citybank_active', 'image_citybank',
        'mobilebanking_active', 'image_mobilebanking', 'rocket_active', 'image_rocket', 'cashondelivery_active', 'image_cashondelivery',
        'decidable_amount', 'inside_dhaka_fee', 'outside_dhaka_fee', 'decidable_amount_od', 'inside_dhaka_od',  'outside_dhaka_od',
        'first_range', 'first_range_discount', 'second_range', 'second_range_discount',
        'rp_fraction', 'rp_point', 'rp_convert_tk', 'rp_active',
    ];
}
