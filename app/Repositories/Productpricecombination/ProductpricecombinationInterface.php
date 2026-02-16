<?php

namespace App\Repositories\Productpricecombination;

interface ProductpricecombinationInterface
{
    public function getAll();

    public function getById($id);

    public function getByAny($column, $value);

    public function getByColumns(array $options);

    public function create(array $attributes);

    public function update($id, array $attributes);

    public function delete($id);

}