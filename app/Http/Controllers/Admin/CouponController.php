<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Coupon\CouponInterface;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CouponController extends Controller
{
    protected $coupon;

    /**
     * CouponController constructor.
     * @param CouponInterface $coupon
     */
    public function __construct(CouponInterface $coupon)
    {
        $this->coupon = $coupon;
    }

    /**
     * @return $this
     */
    public function coupons(Request $request)
    {

        if($request->segment(1) == 'coupons'){
            $coupon_type = 'Coupon';
        }else{
            $coupon_type = 'Voucher';
        }


        $coupons = $this->coupon->getWhere(['coupon_type' => $coupon_type]);
        return view('coupon.coupons')->with(['coupons' => $coupons, 'cv_type' => $coupon_type]);


    }

    /**
     * @param $id
     * @return $this
     */

    public function edit_coupon($id)
    {
        if (isset($id)) {
            $coupon = $this->coupon->getById($id);

            //dump($coupon->coupon_type);
            $coupons = $this->coupon->getWhere(['coupon_type' => $coupon->coupon_type]);
            return view('coupon.coupons')
                ->with('coupon', $coupon)
                ->with('coupons', $coupons)
                ->with('cv_type', $coupon->coupon_type);
        }

    }

    /**
     * @param \App\Http\Controllers\Admin\Request $request
     * @param $id
     * @return $this|RedirectResponse
     */
    public function coupon_update_save(Request $request, $id)
    {

        $converted = strtotime($request->get('start_date'));
        $start_date = date('Y-m-d H:i:s', $converted);

        $converted1 = strtotime($request->get('end_date'));
        $end_date = date('Y-m-d H:i:s', $converted1);
        $attributes = [
            'coupon_code' => $request->get('coupon_code'),
            'coupon_type' => $request->get('coupon_type'),
            'start_date' => $start_date,
            'end_date' => $end_date,
            'amount_type' => $request->get('amount_type'),
            'price' => $request->get('price'),
            'upto_amount' => $request->get('upto_amount'),
            'purchase_min' => $request->get('purchase_min'),
            'purchase_range' => $request->get('purchase_range'),
            'used_limit' => $request->get('used_limit'),
            'comment' => $request->get('comment'),
            'is_active' => 1
        ];

       // dd($attributes);



        try {
            $this->coupon->update($id, $attributes);
            if($attributes['coupon_type'] == 'Coupon'){
                return redirect('coupons')->with('sweet_alert', 'Successfully save changed');
            }else{
                return redirect('vouchers')->with('sweet_alert', 'Successfully save changed');
            }

        } catch (\Illuminate\Database\QueryException $ex) {

            if($attributes['coupon_type'] == 'Coupon'){
                return redirect('coupons')->withErrors($ex->getMessage());
            }else{
                return redirect('vouchers')->withErrors($ex->getMessage());
            }
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @param $action
     */
    public function coupon_status(Request $request, $id, $action)
    {
        $attributes = [
            'is_active' => $action
        ];

        try {
            $this->coupon->update($id, $attributes);
            return redirect()->back()->with('sweet_alert', 'Successfully save changed');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect()->back()->withErrors($ex->getMessage());
        }


    }

    /**
     * @param Request $request
     * @return $this
     * @internal param Request $request
     */
    public function store(Request $request)
    {
       // dd($request);
        // read more on validation at
        $validator = Validator::make($request->all(),
            [
                'coupon_code' => 'required|unique:coupons',
                'coupon_type' => 'required',
                'amount_type' => 'required',
                'price' => 'required',
                'start_date' => 'required',
                'end_date' => 'required'
            ]
        );

        // process the login
        if ($validator->fails()) {
            if($request->get('coupon_type') == 'Coupon') {
                return redirect('coupons')->withErrors($validator)->withInput();
            }else{
                return redirect('vouchers')->withErrors($validator)->withInput();
            }
        } else {

            //dd($request);
            $converted = strtotime($request->get('start_date'));
            $start_date = date('Y-m-d H:i:s', $converted);

            $converted1 = strtotime($request->get('end_date'));
            $end_date = date('Y-m-d H:i:s', $converted1);

            // store
            $attributes = [
                'coupon_code' => $request->get('coupon_code'),
                'coupon_type' => $request->get('coupon_type'),
                'start_date' => $start_date,
                'end_date' => $end_date,
                'amount_type' => $request->get('amount_type'),
                'price' => $request->get('price'),
                'upto_amount' => $request->get('upto_amount'),
                'purchase_min' => $request->get('purchase_min'),
                'purchase_range' => $request->get('purchase_range'),
                'used_limit' => $request->get('used_limit'),
                'comment' => $request->get('comment'),
                'is_active' => $request->get('is_active')
            ];

            //dd($attributes);
            try {
                $done = $this->coupon->create($attributes);
                //dd($done);
                if($attributes['coupon_type'] == 'Coupon') {
                    return redirect('coupons')->with('sweet_alert', 'Successfully save changed');
                }else{
                    return redirect('vouchers')->with('sweet_alert', 'Successfully save changed');
                }
            } catch (\Illuminate\Database\QueryException $ex) {
                //dd($ex);
                if($attributes['coupon_type'] == 'Coupon') {
                    return redirect('coupons')->withErrors($ex->getMessage());
                }else{
                    return redirect('vouchers')->withErrors($ex->getMessage());
                }
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
            $this->coupon->delete($id);
            return redirect('coupons')->with('sweet_alert', 'Successfully deleted');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('coupons')->withErrors($ex->getMessage());
        }
    }


    // Custom Methods

    public function check_if_cat_url_exists(Request $request)
    {
        $seo_url = $request->get('seo_url');
        $coupon = $this->coupon->getByAny('seo_url', $seo_url);
        if ($coupon->first()) {
            $url = $coupon->first()->seo_url;
            $nu = $url . '-' . date('ms');
            $m = $nu;
        } else {
            $m = $seo_url;
        }
        return response()->json(['url' => $m]);

    }
}
