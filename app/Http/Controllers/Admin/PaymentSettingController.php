<?php

namespace App\Http\Controllers\Admin;


use Validator;
use App\Repositories\PaymentSetting\PaymentSettingInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentSettingController extends Controller
{
    private $paymentsetting;

    /**
     * settingController constructor.
     * @param PaymentSettingInterface $paymentsetting
     */
    public function __construct(PaymentSettingInterface $paymentsetting)
    {
        $this->paymentsetting = $paymentsetting;
    }

    /**
     * @return $this
     */
    public function settings()
    {
        $paymentsettings = $this->paymentsetting->getAll();
        //dd($paymentsettings);
        return view('menu.paymentsettings')
            ->with('payment_settings', $paymentsettings);
    }

    /**
     * @param $id
     * @return $this
     */

    public function edit_setting($id)
    {
        if (isset($id)) {
            $paymentsetting = $this->paymentsetting->getById($id);
            return view('menu.paymentsettings')
                ->with('setting', $paymentsetting);
        }

    }

    /**
     * @param \App\Http\Controllers\Admin\Request $request
     * @param $id
     * @return $this|RedirectResponse
     */
    public function setting_update_save(Request $request, $id)
    {

        $d = $this->paymentsetting->getById($id);
        //dd($request->all());
        // store
        $attributes = [
            'admin_cell_one' => $request->get('admin_cell_one'),
            'admin_cell_two' => $request->get('admin_cell_two'),
            'admin_cell_three' => $request->get('admin_cell_three'),
            'admin_cell_four' => $request->get('admin_cell_four'),
            'admin_cell_five' => $request->get('admin_cell_five'),
            'bkash_active' => $request->get('bkash_active'),
            'image_bkash' => $request->get('image_bkash'),
            'nagad_active' => $request->get('nagad_active'),
            'image_nagad' => $request->get('image_nagad'),
            'debitcredit_active' => $request->get('debitcredit_active'),
            'image_debitcredit' => $request->get('image_debitcredit'),
            'citybank_active' => $request->get('citybank_active'),
            'image_citybank' => $request->get('image_citybank'),
            'mobilebanking_active' => $request->get('mobilebanking_active'),
            'image_mobilebanking' => $request->get('image_mobilebanking'),
            'rocket_active' => $request->get('rocket_active'),
            'image_rocket' => $request->get('image_rocket'),
            'cashondelivery_active' => $request->get('cashondelivery_active'),
            'image_cashondelivery' => $request->get('image_cashondelivery'),
            'decidable_amount' => $request->get('decidable_amount'),
            'inside_dhaka_fee' => $request->get('inside_dhaka_fee'),
            'outside_dhaka_fee' => $request->get('outside_dhaka_fee'),
            'decidable_amount_od' => $request->get('decidable_amount_od'),
            'inside_dhaka_od' => $request->get('inside_dhaka_od'),
            'outside_dhaka_od' => $request->get('outside_dhaka_od'),
            'first_range' => $request->get('first_range'),
            'first_range_discount' => $request->get('first_range_discount'),
            'second_range' => $request->get('second_range'),
            'second_range_discount' => $request->get('second_range_discount'),
            'rp_active' => $request->get('rp_active'),
            'rp_fraction' => $request->get('rp_fraction'),
            'rp_point' => $request->get('rp_point'),
            'rp_convert_tk' => $request->get('rp_convert_tk'),
        ];

        //dd($attributes);

        try {
            $p = $this->paymentsetting->update($d->id, $attributes);
            //dd($p);

            return redirect('payment_settings')->with('success', 'Successfully save changed');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('payment_settings')->withErrors($ex->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return $this
     * @internal param Request $request
     */
    public function store(Request $request)
    {
        // validate
        // read more on validation at
        $validator = Validator::make($request->all(),
            [
                'admin_cell_one' => 'required',
                'admin_cell_two' => 'required',
                'bkash_active' => 'required',
                'nagad_active' => 'required',
                'debitcredit_active' => 'required',
                'mobilebanking_active' => 'required',
                'rocket_active' => 'required',
                'cashondelivery_active' => 'required',
                'decidable_amount' => 'required',
                'inside_dhaka_fee' => 'required',
                'outside_dhaka_fee' => 'required',
                'decidable_amount_od' => 'required',
                'inside_dhaka_od' => 'required',
                'outside_dhaka_od' => 'required',
            ]
        );

        // process the login
        if ($validator->fails()) {
            return redirect('paymentsettings')
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            $attributes = [
                'admin_cell_one' => $request->get('admin_cell_one'),
                'admin_cell_two' => $request->get('admin_cell_two'),
                'admin_cell_three' => $request->get('admin_cell_three'),
                'admin_cell_four' => $request->get('admin_cell_four'),
                'admin_cell_five' => $request->get('admin_cell_five'),
                'bkash_active' => $request->get('bkash_active'),
                'image_bkash' => $request->get('image_bkash'),
                'nagad_active' => $request->get('nagad_active'),
                'image_nagad' => $request->get('image_nagad'),
                'debitcredit_active' => $request->get('debitcredit_active'),
                'image_debitcredit' => $request->get('image_debitcredit'),
                'citybank_active' => $request->get('citybank_active'),
                'image_citybank' => $request->get('image_citybank'),
                'mobilebanking_active' => $request->get('mobilebanking_active'),
                'image_mobilebanking' => $request->get('image_mobilebanking'),
                'rocket_active' => $request->get('rocket_active'),
                'image_rocket' => $request->get('image_rocket'),
                'cashondelivery_active' => $request->get('cashondelivery_active'),
                'image_cashondelivery' => $request->get('image_cashondelivery'),
                'decidable_amount' => $request->get('decidable_amount'),
                'inside_dhaka_fee' => $request->get('inside_dhaka_fee'),
                'outside_dhaka_fee' => $request->get('outside_dhaka_fee'),
                'decidable_amount_od' => $request->get('decidable_amount_od'),
                'inside_dhaka_od' => $request->get('inside_dhaka_od'),
                'outside_dhaka_od' => $request->get('outside_dhaka_od'),
                'first_range' => $request->get('first_range'),
                'first_range_discount' => $request->get('first_range_discount'),
                'second_range' => $request->get('second_range'),
                'second_range_discount' => $request->get('second_range_discount'),
                'rp_active' => $request->get('rp_active'),
                'rp_fraction' => $request->get('rp_fraction'),
                'rp_point' => $request->get('rp_point'),
                'rp_convert_tk' => $request->get('rp_convert_tk'),
            ];

            //dd($attributes);

            try {
                $this->paymentsetting->create($attributes);
                return redirect('payment_settings')->with('success', 'Successfully save changed');
            } catch (\Illuminate\Database\QueryException $ex) {
                return redirect('payment_settings')->withErrors($ex->getMessage());
            }
        }

    }

    /**
     * @param $id
     * @return $this
     */
    public function destroy($id)
    {
        try {
            $this->paymentsetting->delete($id);
            return redirect('payment_settings')->with('success', 'Successfully deleted');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('payment_settings')->withErrors($ex->getMessage());
        }
    }
}
