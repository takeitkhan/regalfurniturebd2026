<?php

namespace App\Repositories\Slider;

interface SliderInterface
{
    public function getAll();

    public function getById($id);

    public function getByAny($column, $value);

    public function getByFilter(array $filter = [],$orderBy = ['position' => 'asc'],$limit = 0);

    public function create(array $attributes);

    public function update($id, array $attributes);

    public function delete($id);
}
