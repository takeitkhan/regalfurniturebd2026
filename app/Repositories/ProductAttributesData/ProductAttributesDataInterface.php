<?php

namespace App\Repositories\ProductAttributesData;

interface ProductAttributesDataInterface
{
    public function getAll();

    public function getById($id);

    public function getByAny($column, $value);

    public function create(array $attributes);

    public function insert(array $attributes);

    public function update($id, array $attributes);

    public function delete($id);

    public function deleteByAny($column, $value);

    public function updateByWhere(array $column, array $value);

}