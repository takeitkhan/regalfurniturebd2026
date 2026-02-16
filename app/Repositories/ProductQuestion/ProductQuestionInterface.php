<?php
namespace App\Repositories\ProductQuestion;

interface ProductQuestionInterface
{
    public function getAll();

    public function getById($id);

    public function self();

    public function getByAny($column, $value);

    public function create(array $attributes);

    public function getByFilter(array $attributes);

    public function update($id, array $attributes);

    public function delete($id);

}