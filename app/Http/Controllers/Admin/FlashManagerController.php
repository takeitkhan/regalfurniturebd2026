<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\FlashItem\FlashItemInterface;
use App\Repositories\FlashShedule\FlashSheduleInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Form;
use Illuminate\Support\Facades\Cache;

class FlashManagerController extends Controller
{
    /**
     * @var FlashItemInterface
     */
    private $flashItem;
    /**
     * @var FlashSheduleInterface
     */
    private $flashShedule;

    /**
     * FlashManagerController constructor.
     * @param FlashSheduleInterface $flashShedule
     * @param FlashItemInterface $flashItem
     */
    public function __construct(FlashSheduleInterface $flashShedule, FlashItemInterface $flashItem)
    {

        $this->flashItem = $flashItem;
        $this->flashShedule = $flashShedule;
    }

    /**
     * @return $this
     */
    public function flash_schedule()
    {
        $schedules = $this->flashShedule->getAll();
        return view('flash.schedule', compact('schedules'))
            ->with('schedules', $schedules);
    }

    /**
     * @param Request $request
     * @return $this
     * @internal param Request $request
     */
    public function store(Request $request)
    {
        //dd($request);
        // read more on validation at
        $validator = Validator::make($request->all(),
            [
                'fs_name' => 'required',
                'fs_start_date' => 'required',
                'fs_end_date' => 'required',
                'fs_price_time' => 'required',
                'fs_is_active' => 'required'
            ]
        );

        // process the login
        if ($validator->fails()) {
            return redirect('flash_schedule')
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            $converted = strtotime($request->get('fs_start_date'));
            $start_date = date('Y-m-d H:i:s', $converted);

            $converted1 = strtotime($request->get('fs_end_date'));
            $end_date = date('Y-m-d H:i:s', $converted1);

            $fs_price_time = strtotime($request->get('fs_price_time'));
            $fs_price_time = date('Y-m-d H:i:s', $fs_price_time);

            $attributes = [
                'fs_name' => $request->get('fs_name'),
                'fs_description' => $request->get('fs_description'),
                'fs_start_date' => $start_date,
                'fs_end_date' => $end_date,
                'fs_price_time' => $fs_price_time,
                'fs_is_active' => $request->get('fs_is_active')
            ];

            // dd($attributes);
            try {
                $done = $this->flashShedule->create($attributes);

                Cache::forget('common-flash-sale');
                Cache::forget('home-flash-sale');

                //dd($done);
                return redirect('flash_schedule')->with('success', 'Successfully save changed');
            } catch (\Illuminate\Database\QueryException $ex) {
                //dd($ex);
                return redirect('flash_schedule')->withErrors($ex->getMessage());
            }
        }

    }

    /**
     * @param $id
     * @return $this
     */

    public function edit_flash_schedule($id)
    {
        if (isset($id)) {
            $schedule = $this->flashShedule->getById($id);
            $schedules = $this->flashShedule->getAll();
            return view('flash.schedule')
                ->with('schedule', $schedule)
                ->with('schedules', $schedules);

        }

    }

    /**
     * @param \App\Http\Controllers\Admin\Request $request
     * @param $id
     * @return $this|RedirectResponse
     */
    public function flash_schedule_update_save(Request $request, $id)
    {

        $converted = strtotime($request->get('fs_start_date'));
        $start_date = date('Y-m-d H:i:s', $converted);

        $converted1 = strtotime($request->get('fs_end_date'));
        $end_date = date('Y-m-d H:i:s', $converted1);

        $fs_price_time = strtotime($request->get('fs_price_time'));
        $fs_price_time = date('Y-m-d H:i:s', $fs_price_time);

        $attributes = [
            'fs_name' => $request->get('fs_name'),
            'fs_description' => $request->get('fs_description'),
            'fs_start_date' => $start_date,
            'fs_end_date' => $end_date,
            'fs_price_time' => $fs_price_time,
            'fs_is_active' => $request->get('fs_is_active')
        ];

        // dd($attributes);


        try {
            $this->flashShedule->update($id, $attributes);
            Cache::forget('common-flash-sale');
            Cache::forget('home-flash-sale');

            return redirect('flash_schedule')->with('sweet_alert', 'Successfully save changed');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('flash_schedule')->withErrors($ex->getMessage());
        }
    }

    /**
     *
     *
     * /**
     * @param Request $request
     * @param $id
     * @param $action
     */
    public function flash_schedule_status(Request $request, $id, $action)
    {
        $attributes = [
            'fs_is_active' => $action
        ];

        try {
            $this->flashShedule->update($id, $attributes);

            Cache::forget('common-flash-sale');
            Cache::forget('home-flash-sale');

            return redirect()->back()->with('sweet_alert', 'Successfully save changed');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect()->back()->withErrors($ex->getMessage());
        }


    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function flash_item(Request $request, $id)
    {

        $schedule = $this->flashShedule->getById($id);
        $itmes = $this->flashItem->getWhere(['fi_shedule_id' => $id]);
        return view('flash.item')
            ->with('schedule', $schedule)
            ->with('itmes', $itmes);
    }

    /**
     * @param Request $request
     */
    public function store_flash_items(Request $request)
    {


        //dd($request);

        $validator = Validator::make($request->all(),
            [
                'fi_product_id' => 'required',
                'fi_shedule_id' => 'required',
                'fi_discount' => 'required',
                'fi_show_tag' => 'required'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {


            $attributes = [
                'fi_product_id' => $request->get('fi_product_id'),
                'fi_shedule_id' => $request->get('fi_shedule_id'),
                'fi_discount' => $request->get('fi_discount'),
                'fi_show_tag' => $request->get('fi_show_tag'),
                'fi_qty' => $request->get('fi_qty')
            ];

           // dd($attributes);
            try {
                if($request->get('id')){

                    $this->flashItem->update($request->get('id'), $attributes);
                }else{
                    $this->flashItem->create($attributes);
                }

                Cache::forget('common-flash-sale');
                Cache::forget('home-flash-sale');

                return redirect()->back()->with('success', 'Successfully save changed');
            } catch (\Illuminate\Database\QueryException $ex) {
                //dd($ex);
                return redirect()->back()->withErrors($ex->getMessage());
            }
        }

    }

    public function delete_flash_item(Request $request, $id)
    {
        try {
            $this->flashItem->delete($id);

            Cache::forget('common-flash-sale');
            Cache::forget('home-flash-sale');

            return redirect()->back()->with('success', 'Successfully deleted');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect()->back()->withErrors($ex->getMessage());
        }

    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add_schedule_products(Request $request)
    {
       // dd($request);

        $main_pid = $request->get('fi_product_id');
        $fi_shedule_id = $request->get('fi_shedule_id');

        $product = \App\Models\Product::where('id', $main_pid)->get()->first();
        $main_image = \App\Models\ProductImages::where(['main_pid' => $main_pid, 'is_main_image' => 1])->get()->first();
        $flash_item = \App\Models\FlashItem::where(['fi_shedule_id' => $fi_shedule_id, 'fi_product_id' => $main_pid])->first();


        //dd($flash_item);

        $tksign = '&#2547;';


        $html = '<div style="width: 100%; padding: 20px;">';

        $html .= '<div class="row">';
        $html .= '<div class="col-md-6" style="text-align: left;">';
        $html .= '<img src="'.url($main_image->full_size_directory??"").'" style="height:180px; max-width:200px">';

        $html .= '</div>';
        $html .= '<div class="col-md-6">';
        $html .= '<b>Name:&nbsp;</b>'.$product->title.'<br/>';
        $html .= '<b>Short Des:&nbsp;</b>'.$product->short_description.'<br/>';
        $html .= '<b>Product ID:&nbsp;</b>'.$product->id.'<br/>';
        $html .= '<b>Sku:&nbsp;</b>'.$product->sku.'<br/>';
        $html .= '<b>Qty:&nbsp;</b>'.$product->qty.'<br/>';
        $html .= '<b>Price:&nbsp;</b> '.$tksign . number_format($product->local_selling_price).'<br/>';

        $html .= '</div>';
        $html .= '</div>';


        $html .= '<div class="row"  style="width: 100%; margin: 0">';

        $html .= Form::open(array('url' => url('store_flash_items'), 'autocomplete' => 'off', 'method' => 'post', 'value' => 'PATCH'));
        $html .= Form::hidden('fi_shedule_id', $fi_shedule_id, ['type' => 'hidden']);
        $html .= Form::hidden('fi_product_id', $product->id, ['type' => 'hidden']);

        $html .= Form::hidden('fi_qty',  $product->qty, ['type' => 'hidden']);
        if(isset($flash_item)){
            $html .= Form::hidden('id',  $flash_item->id, ['type' => 'hidden']);
        }

        $html .= '<div class="col-md-3 col-sm-6 col-xs-12">';
        $html .= Form::label('price', 'Price', array('class' => 'price'));
        $html .= Form::text('',$product->local_selling_price , ['readonly',  'class' => 'form-control quantity', 'placeholder' => 'Quantity...']);
        $html .= '</div>';

        $html .= '<div class="col-md-3 col-sm-6 col-xs-12">';
        $html .= Form::label('fi_discount', 'Discount', array('class' => 'fi_discount'));
        $html .= Form::number('fi_discount', (!empty($flash_item->fi_discount) ? $flash_item->fi_discount : NULL), ['required',  'class' => 'form-control fi_discount', 'placeholder' => 'Quantity...']);
        $html .= '</div>';

        $html .= '<div class="col-md-3 col-sm-6 col-xs-12">';
        $html .= Form::label('fi_show_tag', 'Show percentage', array('class' => 'fi_show_tag'));
        $html .= Form::text('fi_show_tag', (!empty($flash_item->fi_show_tag) ? $flash_item->fi_show_tag : NULL), ['required',  'class' => 'form-control fi_show_tag', 'placeholder' => 'Quantity...']);
        $html .= '</div>';



        $html .= '<div class="col-md-3 col-sm-6 col-xs-12">';
        $html .= Form::label('quantity', '&nbsp;', array('class' => 'quantity'));
        $html .= Form::submit('Submit', ['class' => 'btn btn-success btn-sm form-control']);
        $html .= '</div>';


        $html .= Form::close();

        $html .= '</div>';
        $html .= '</div>';

        //echo $html;

        return response()->json(['html' => $html]);

    }

}
