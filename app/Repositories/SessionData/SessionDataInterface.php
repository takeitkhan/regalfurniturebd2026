<?php
namespace App\Repositories\SessionData;

interface SessionDataInterface
{
    public function getAll();

    public function getById($id);

    public function getByAny($column, $value);

    public function create(array $attributes);

    public function update($id, array $attributes);

    public function delete($id);

    public function updateByKey($key,$value);

    public function store($key,$value);

    public function getFirstByKey($key);
    
    public function getAllByKey($key);

}
