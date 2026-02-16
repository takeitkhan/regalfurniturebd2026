<?php
namespace App\Repositories\ProductImages;

interface ProductImagesInterface
{
    public function getAll();

    public function self();

    public function getById($id);

    public function getByAny($column, $value);

    public function create(array $attributes);

    public function update($id, array $attributes);

    public function delete($id);

}
