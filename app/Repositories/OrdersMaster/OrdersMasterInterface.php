<?php

namespace App\Repositories\OrdersMaster;

interface OrdersMasterInterface
{
    public function self();

    public function getAll(array $options);

    public function getById($id);

    public function getByAny($column, $value);

    public function getByAnyTwoValue($colom1, $value1, $colom2, $value2);

    public function create(array $attributes);

    public function update($id, array $attributes);

    public function delete($id);

    public function getAllByUser(array $options);
}