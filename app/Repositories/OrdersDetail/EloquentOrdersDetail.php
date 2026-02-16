<?php

namespace App\Repositories\OrdersDetail;


use App\Models\OrdersDetail;
use Illuminate\Support\Facades\DB;

class EloquentOrdersDetail implements OrdersDetailInterface
{
    private $model;

    /**
     * EloquentOrdersDetail constructor.
     * @param OrdersDetail $model
     */
    public function __construct(OrdersDetail $model)
    {
        $this->model = $model;
    }

    public function self(){
        return $this->model;
    }
    /**
     *
     */
    public function getAll()
    {
        return $this->model
            ->orderBy('id', 'desc')
            //->take(100)
            ->paginate(10);
    }

    /**
     * @param $id
     */
    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getByAny($column, $value)
    {
        return $this->model->where($column, $value)->get();
    }

    /**
     * @param array $att
     */
    public function create(array $att)
    {
        return $this->model->create($att);
    }

    /**
     * @param $id
     * @param array $att
     */
    public function update($id, array $att)
    {
        $todo = $this->getById($id);
        $todo->update($att);
        return $todo;
    }

    public function delete($id)
    {
        $this->getById($id)->delete();
        return true;
    }

    public function getProductBySecretKey(array $options = array())
    {
        $default = [
            'user_id' => null,
            'random_code' => null,
            'secret_key' => null
        ];

        $no = array_merge($default, $options);

        if (!empty($no['user_id'])) {
            $data = $this->model->where('user_id', $no['user_id']);

            $data = $data->get();
            return $data;


        }else{
            $data = $this->model->where('order_random', $no['random_code'])
                ->where('secret_key', $no['secret_key']);
            if(auth()->check()){
                if(auth()->user()->isVendor()){
                    $data = $data->where('vendor_id', auth()->user()->id);
                }
            }


            $data = $data->get();
            return $data;
        }


    }

    public function updateByVendor(array $options = array(), array $data = array())
    {

//        dump($data);
//        dd($options);
        $data = $this->model->where($options)->update($data);



        return $data;


    }

    public function getAllByUser(array $options = array())
    {

        $default = array(
            'column' => null,
            'search_key' => null,
            'order_status' => null,
            'formDate' => null,
            'toDate' => null,
            'pre_booking_order' => 0,
            'order_from' => null,
        );


        $new = array_merge($default, $options);

    //   dd($new);

        $data = DB::table('orders_detail as od');
        $data = $data->select('om.*');
        $data = $data->leftJoin('orders_master as om', 'om.order_random', '=', 'od.order_random');


        if (auth()->user()->isVendor()) {
            $v_id = auth()->user()->id;

            $data = $data->where(['od.vendor_id' => $v_id]);
        }

        if (!empty($new['search_key']) && !empty($new['column'])) {
            $data = $data->where($new['column'], 'like', '%' . $new['search_key'] . '%');
        }


        if (!empty($new['order_status'])) {
            $data = $data->where(['od.od_status' => $new['order_status']]);

        }

        if (isset($new['pre_booking_order'])) {
            $data = $data->where(['om.pre_booking_order' => $new['pre_booking_order']]);
            // dd('iCam');
        }
        if (!empty($new['formDate']) && !empty($new['toDate'])) {
//            $data = $data->whereBetween('om.created_at', array($new['formDate'], $new['toDate']));
            $data = $data->where(function($q) use ($new){
                $fromDate = date($new['formDate']);
                $toDate = date($new['toDate']);
                $q->whereDate('om.created_at', '>=', $fromDate)
                    ->whereDate('om.created_at', '<=', $toDate);
            });

        }


        if (!empty($new['order_from'])) {
            $data = $data->where('order_from', 'like', '%' . $new['order_from'] . '%');
        }


        $data = $data->groupBy('om.id');
        $data = $data->orderBy('om.id', 'desc');

        //dd($data->toSql());
        $data = $data->paginate(20);

        //$data =  $data->get();
        // dd($data);
        return $data;


    }
}
