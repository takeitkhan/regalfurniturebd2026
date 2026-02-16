<?php
namespace App\Repositories\Coupon;

interface CouponInterface
{
    public function getAll();

    public function getById($id);

    public function getByAny($column, $value);

    public function create(array $attributes);

    public function update($id, array $attributes);

    public function delete($id);

    public function updateWhere(array $where, array $data);

    public function getWhere(array $where);

    public function getCoupon($coupon_code);

}
