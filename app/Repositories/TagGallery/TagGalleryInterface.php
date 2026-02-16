<?php

namespace App\Repositories\TagGallery;

interface TagGalleryInterface
{
    public function getAll();

    public function getById($id);

    public function getByAny($column, $value);

    public function getByFilter(array $filter = [],$orderBy = ['position' => 'desc'],$limit = 0);

    public function create(array $attributes);

    public function update($id, array $attributes);

    public function delete($id);
}