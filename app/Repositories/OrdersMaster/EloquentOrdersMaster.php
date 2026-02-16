<?php

namespace App\Repositories\OrdersMaster;


use App\Models\OrdersMaster;
use Illuminate\Support\Facades\Request;

class EloquentOrdersMaster implements OrdersMasterInterface
{
    private $model;

    /**
     * EloquentOrdersMaster constructor.
     * @param OrdersMaster $model
     */
    public function __construct(OrdersMaster $model)
    {
        $this->model = $model;
    }

    /**
     * @param Request $request
     *
     * dd($this->model
     * ->whereRaw(!empty($new['order_status']) ? 'order_status = ' . $new['order_status'] : 'order_status IS NOT NULL')
     * ->orWhere('customer_name', 'like', '%' . $new['search_key'] . '%')
     * ->orWhere('phone', 'like', '%' . $new['search_key'] . '%')
     * ->orWhere('emergency_phone', 'like', '%' . $new['search_key'] . '%')
     * ->orWhere('address', 'like', '%' . $new['search_key'] . '%')
     * ->orWhere('email', 'like', '%' . $new['search_key'] . '%')
     * ->orWhere('order_date', 'like', $new['search_key'] . '%')
     * ->orWhere('payment_method', 'like', $new['search_key'] . '%')
     * ->orderBy('id', 'desc')
     * ->toSql());
     *
     * return $this->model
     * ->orWhereRaw('"' . $new['column'] . '" like "%' . $new['search_key'] . '%"')
     * ->orWhereRaw('"' . $new['column'] . '"  like "%' . $new['search_key'] . '%"')
     * ->orWhereRaw('"' . $new['column'] . '"  like "%' . $new['search_key'] . '%"')
     * ->orWhereRaw('"' . $new['column'] . '"  like "%' . $new['search_key'] . '%"')
     * ->orWhereRaw('"' . $new['column'] . '"  like "%' . $new['search_key'] . '%"')
     * ->orWhereRaw('"' . $new['column'] . '"  like "%' . $new['search_key'] . '%"')
     * ->orWhereRaw('"' . $new['column'] . '"  like "%' . $new['search_key'] . '%"')
     * ->orderBy('id', 'desc')
     * ->paginate(10);
     * @return
     */
    public function getAll(array $options = array())
    {
        $default = array(
            'column' => null,
            'search_key' => null,
            'order_status' => null
        );

        $new = array_merge($default, $options);

        if (!empty($new['order_status'])) {
            $os = " order_status = '" . $new['order_status'] . "'";
        } else {
            $os = " order_status IS NOT NULL OR order_status IS NULL";
        }

        if (!empty($new['search_key']) && !empty($new['column'])) {
            //dd($this->model->whereRaw('' . $new['column'] . ' like "%' . $new['search_key'] . '%"')->toSql());
            return $this->model
                //->where($new['column'], $new['search_key'])
                ->whereRaw('' . $new['column'] . ' like "%' . $new['search_key'] . '%"')
                ->orderBy('id', 'desc')
                ->paginate(10);
        } else {
            return $this->model
                ->whereRaw($os)
                ->orderBy('id', 'desc')
                ->paginate(10);
        }

    }

    public function self(){
        return $this->model;
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
        return $this->model->where($column, $value)->get()->first();
    }

    public function getByAnyTwoValue($col1, $val1, $col2, $val2){
        return $this->model->where($col1, $val1,)->where($col2, $val2);
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

public function getAllByUser(array $options = [])
{
    // 1. Default filters
    $default = [
        'column'       => null,
        'search_key'   => null,
        'order_status' => null,
    ];

    $new = array_merge($default, $options);

    // 2. Base query with alias
    $data = $this->model->from('orders_master as om');

    /*
    |--------------------------------------------------------------------------
    | Vendor Orders
    |--------------------------------------------------------------------------
    */
    if (auth()->user()->isVendor()) {

        $v_id = auth()->user()->id;

        $data = $data->leftJoin(
            'orders_detail as od',
            'od.order_random',
            '=',
            'om.order_random'
        );

        $data = $data->where('od.vendor_id', $v_id);

        // 🔍 Search
        if (!empty($new['search_key']) && !empty($new['column'])) {
            $data = $data->where(function ($query) use ($new) {
                $query->where(
                    $new['column'],
                    'like',
                    '%' . $new['search_key'] . '%'
                );
            });
        }

        // 📌 Status filter
        if (!empty($new['order_status'])) {
            $data = $data->where('om.order_status', $new['order_status']);
        }

        $data = $data->orderBy('om.id', 'desc');

    /*
    |--------------------------------------------------------------------------
    | Admin / Normal User Orders
    |--------------------------------------------------------------------------
    */
    } else {

        // 🔍 Search
        if (!empty($new['search_key']) && !empty($new['column'])) {
            $data = $data->where(function ($query) use ($new) {
                $query->where(
                    $new['column'],
                    'like',
                    '%' . $new['search_key'] . '%'
                );
            });
        }

        // 📌 Status filter
        if (!empty($new['order_status'])) {
            $data = $data->where('om.order_status', $new['order_status']);
        }

        $data = $data->orderBy('om.id', 'desc');
    }

    // 3. Pagination
    return $data->paginate(20);
}


/**
    public function getAllByUser(array $options = array())
    {
        $default = array(
            'column' => null,
            'search_key' => null,
            'order_status' => null
        );
        $new = array_merge($default, $options);
        $data = $this->model;
        if (auth()->user()->isVendor()) {

            $v_id = auth()->user()->id;

            $data = $data->leftJoin('orders_detail as od', 'od.order_random', '=', 'orders_master.order_random');

            $data = $data->where(['od.vendor_id' => $v_id]);
            if (!empty($new['search_key']) && !empty($new['column'])) {
                $data = $data->where(function ($query) {
                    $query->where('orders_master' . $new['column'], 'like', '%' . $new['search_key'] . '%');
                });
            }

            if (!empty($new['order_status'])) {
                $data = $data->where(['orders_master.order_status' => $new['order_status']]);

            }
            $data = $data->orderBy('orders_master.id', 'desc');

        } else {
            if (!empty($new['search_key']) && !empty($new['column']))  {
		$data = $data->where(function ($query) {
                    $query->where($new['column'], 'like', '%' . $new['search_key'] . '%');

                });
empty($new['order_status'])) {
                $data = $data->where(['order_status' => $new['order_status']]);

            }
            $data = $data->orderBy('id', 'desc');
        }
        $data = $data->paginate(20);
        //$data =  $data->get();
        // dd($data);
        return $data;
    }
**/
    public function getByRandom($random)
    {
        $data = $this->model->where(['order_random' => $random])->get()->first();
        return $data;

    }
}
