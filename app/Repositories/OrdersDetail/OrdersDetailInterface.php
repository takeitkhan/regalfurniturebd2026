<?php
namespace App\Repositories\OrdersDetail;

interface OrdersDetailInterface
{
    public function self();

    public function getAll();

    public function getById($id);

    public function getByAny($column, $value);

    public function create(array $attributes);

    public function update($id, array $attributes);

    public function delete($id);

    public function getProductBySecretKey(array $options = array());

    public function updateByVendor(array $options, array $data);

    public function getAllByUser(array $options);
}
