<?php
namespace App\Repositories\Post;

interface PostInterface
{
    public function getAll();

    public function self();

    public function getById($id);

    public function create(array $attributes);

    public function update($id, array $attributes);

    public function delete($id);

    public function getByAny($column, $value);

    public function getByArr(array $attributes);
    
}