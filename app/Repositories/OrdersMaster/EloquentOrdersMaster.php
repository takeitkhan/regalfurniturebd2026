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
        'search_term'  => null,
        'order_status' => null,
        'order_id' => null,
        'order_random' => null,
        'customer_name' => null,
        'phone' => null,
        'email' => null,
        'product_code' => null,
        'product_name' => null,
        'payment_method' => null,
        'payment_term_status' => null,
        'formDate' => null,
        'toDate' => null,
        'amount_min' => null,
        'amount_max' => null,
        'order_from' => null,
        'pre_booking_order' => null,
    ];

    $new = array_merge($default, $options);

    // 2. Base query with alias
    $data = $this->model->from('orders_master as om')->select('om.*');

    $needsDetailJoin = !empty($new['product_code']) || !empty($new['product_name']);

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

        $hasOrFilters = !empty($new['search_key']) || !empty($new['search_term']) || !empty($new['order_id']) || !empty($new['order_random'])
            || !empty($new['customer_name']) || !empty($new['phone']) || !empty($new['email'])
            || !empty($new['product_code']) || !empty($new['product_name']) || !empty($new['order_status'])
            || !empty($new['payment_method']) || !empty($new['payment_term_status']);

        if ($hasOrFilters) {
            // 🔍 OR-based search filters
            $data = $data->where(function ($query) use ($new) {
                if (!empty($new['search_key']) && !empty($new['column'])) {
                    $query->orWhere($new['column'], 'like', '%' . $new['search_key'] . '%');
                }
                if (!empty($new['search_term'])) {
                    $term = $new['search_term'];
                    if (is_numeric($term)) {
                        $query->orWhere('om.id', $term);
                    }
                    $query->orWhere('om.order_random', 'like', '%' . $term . '%')
                          ->orWhere('om.customer_name', 'like', '%' . $term . '%')
                          ->orWhere('om.phone', 'like', '%' . $term . '%')
                          ->orWhere('om.email', 'like', '%' . $term . '%')
                          ->orWhereExists(function ($sub) use ($term) {
                              $sub->selectRaw('1')
                                  ->from('orders_detail as od2')
                                  ->whereColumn('od2.order_random', 'om.order_random')
                                  ->where(function ($q) use ($term) {
                                      $q->where('od2.product_code', 'like', '%' . $term . '%')
                                        ->orWhere('od2.product_name', 'like', '%' . $term . '%');
                                  });
                          });
                }
                if (!empty($new['order_id'])) {
                    $query->orWhere('om.id', $new['order_id']);
                }
                if (!empty($new['order_random'])) {
                    $query->orWhere('om.order_random', $new['order_random']);
                }
                if (!empty($new['customer_name'])) {
                    $query->orWhere('om.customer_name', 'like', '%' . $new['customer_name'] . '%');
                }
                if (!empty($new['phone'])) {
                    $query->orWhere('om.phone', 'like', '%' . $new['phone'] . '%');
                }
                if (!empty($new['email'])) {
                    $query->orWhere('om.email', 'like', '%' . $new['email'] . '%');
                }
                if (!empty($new['product_code']) || !empty($new['product_name'])) {
                    $query->orWhereExists(function ($sub) use ($new) {
                        $sub->selectRaw('1')
                            ->from('orders_detail as od2')
                            ->whereColumn('od2.order_random', 'om.order_random');
                        if (!empty($new['product_code'])) {
                            $sub->where('od2.product_code', 'like', '%' . $new['product_code'] . '%');
                        }
                        if (!empty($new['product_name'])) {
                            $sub->where('od2.product_name', 'like', '%' . $new['product_name'] . '%');
                        }
                    });
                }
                if (!empty($new['order_status'])) {
                    $query->orWhere('om.order_status', $new['order_status']);
                }
                if (!empty($new['payment_method'])) {
                    $query->orWhere('om.payment_method', $new['payment_method']);
                }
                if (!empty($new['payment_term_status'])) {
                    $query->orWhere('om.payment_term_status', $new['payment_term_status']);
                }
            });
        }

        if (!empty($new['formDate']) && !empty($new['toDate'])) {
            $data = $data->whereBetween('om.order_date', [$new['formDate'], $new['toDate']]);
        }

        if (!empty($new['amount_min']) || !empty($new['amount_max'])) {
            $min = $new['amount_min'] !== null ? $new['amount_min'] : 0;
            $max = $new['amount_max'] !== null ? $new['amount_max'] : 999999999;
            $data = $data->whereBetween(\DB::raw('CAST(om.total_amount AS DECIMAL(12,2))'), [$min, $max]);
        }

        if (!empty($new['order_from'])) {
            $data = $data->where('om.order_from', $new['order_from']);
        }

        if (!empty($new['pre_booking_order'])) {
            $data = $data->where('om.pre_booking_order', $new['pre_booking_order']);
        }

        $data = $data->distinct()->orderBy('om.id', 'desc');

    /*
    |--------------------------------------------------------------------------
    | Admin / Normal User Orders
    |--------------------------------------------------------------------------
    */
    } else {

        if ($needsDetailJoin) {
            $data = $data->leftJoin('orders_detail as od', 'od.order_random', '=', 'om.order_random');
        }

        $hasOrFilters = !empty($new['search_key']) || !empty($new['search_term']) || !empty($new['order_id']) || !empty($new['order_random'])
            || !empty($new['customer_name']) || !empty($new['phone']) || !empty($new['email'])
            || !empty($new['product_code']) || !empty($new['product_name']) || !empty($new['order_status'])
            || !empty($new['payment_method']) || !empty($new['payment_term_status']);

        if ($hasOrFilters) {
            // 🔍 OR-based search filters
            $data = $data->where(function ($query) use ($new) {
                if (!empty($new['search_key']) && !empty($new['column'])) {
                    $query->orWhere($new['column'], 'like', '%' . $new['search_key'] . '%');
                }
                if (!empty($new['search_term'])) {
                    $term = $new['search_term'];
                    if (is_numeric($term)) {
                        $query->orWhere('om.id', $term);
                    }
                    $query->orWhere('om.order_random', 'like', '%' . $term . '%')
                          ->orWhere('om.customer_name', 'like', '%' . $term . '%')
                          ->orWhere('om.phone', 'like', '%' . $term . '%')
                          ->orWhere('om.email', 'like', '%' . $term . '%')
                          ->orWhereExists(function ($sub) use ($term) {
                              $sub->selectRaw('1')
                                  ->from('orders_detail as od2')
                                  ->whereColumn('od2.order_random', 'om.order_random')
                                  ->where(function ($q) use ($term) {
                                      $q->where('od2.product_code', 'like', '%' . $term . '%')
                                        ->orWhere('od2.product_name', 'like', '%' . $term . '%');
                                  });
                          });
                }
                if (!empty($new['order_id'])) {
                    $query->orWhere('om.id', $new['order_id']);
                }
                if (!empty($new['order_random'])) {
                    $query->orWhere('om.order_random', $new['order_random']);
                }
                if (!empty($new['customer_name'])) {
                    $query->orWhere('om.customer_name', 'like', '%' . $new['customer_name'] . '%');
                }
                if (!empty($new['phone'])) {
                    $query->orWhere('om.phone', 'like', '%' . $new['phone'] . '%');
                }
                if (!empty($new['email'])) {
                    $query->orWhere('om.email', 'like', '%' . $new['email'] . '%');
                }
                if (!empty($new['product_code']) || !empty($new['product_name'])) {
                    $query->orWhereExists(function ($sub) use ($new) {
                        $sub->selectRaw('1')
                            ->from('orders_detail as od2')
                            ->whereColumn('od2.order_random', 'om.order_random');
                        if (!empty($new['product_code'])) {
                            $sub->where('od2.product_code', 'like', '%' . $new['product_code'] . '%');
                        }
                        if (!empty($new['product_name'])) {
                            $sub->where('od2.product_name', 'like', '%' . $new['product_name'] . '%');
                        }
                    });
                }
                if (!empty($new['order_status'])) {
                    $query->orWhere('om.order_status', $new['order_status']);
                }
                if (!empty($new['payment_method'])) {
                    $query->orWhere('om.payment_method', $new['payment_method']);
                }
                if (!empty($new['payment_term_status'])) {
                    $query->orWhere('om.payment_term_status', $new['payment_term_status']);
                }
            });
        }

        if (!empty($new['formDate']) && !empty($new['toDate'])) {
            $data = $data->whereBetween('om.order_date', [$new['formDate'], $new['toDate']]);
        }

        if (!empty($new['amount_min']) || !empty($new['amount_max'])) {
            $min = $new['amount_min'] !== null ? $new['amount_min'] : 0;
            $max = $new['amount_max'] !== null ? $new['amount_max'] : 999999999;
            $data = $data->whereBetween(\DB::raw('CAST(om.total_amount AS DECIMAL(12,2))'), [$min, $max]);
        }

        if (!empty($new['order_from'])) {
            $data = $data->where('om.order_from', $new['order_from']);
        }

        if (!empty($new['pre_booking_order'])) {
            $data = $data->where('om.pre_booking_order', $new['pre_booking_order']);
        }

        $data = $data->distinct()->orderBy('om.id', 'desc');
    }

    // 3. Pagination
    return $data->paginate(30);
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
