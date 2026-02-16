<?php

namespace App\Repositories\Coupon;


use App\Models\Coupon;
use Carbon\Carbon;

class EloquentCoupon implements CouponInterface
{
    private $model;


    /**
     * EloquentCoupon constructor.
     * @param Coupon $model
     */
    public function __construct(Coupon $model)
    {
        $this->model = $model;
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

    /**
    * @param $column
    * @param $value
    */
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

    /**
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $this->getById($id)->delete();
        return true;
    }

    /**
     * @param array $where
     * @param array $data
     * @return bool
     */
    public function updateWhere(Array $where = array(), Array $data = array())
    {
        $this->model->where($where)
            ->update($data);
        return true;
    }

    public function getWhere(Array $where = array()){
        return $this->model
            ->where($where)
            ->orderBy('id', 'desc')
            //->take(100)
            ->paginate(10);
    }


    public function getCoupon($coupon_code)
    {
        $coupon = Coupon::where('coupon_code',$coupon_code)->where('start_date','<=', Carbon::now('Asia/Dhaka'))->where('end_date', '>=', Carbon::now('Asia/Dhaka'))->first();

        return $coupon;
    }
}