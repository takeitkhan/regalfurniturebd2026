<?php

namespace App\Repositories\Media;

interface MediaInterface
{
    public function self();
    
    public function getAll();

    public function getById($id);

    public function getByAny($column,$value);

    public function create(array $attributes);

    public function update($id, array $attributes);

    public function delete($id);

    public function getMediaOnSearch(array $options);
}