<?php

namespace App\Repositories\ProductAttributesData;


use App\Models\ProductAttributesData;

class EloquentProductAttributesData implements ProductAttributesDataInterface
{
    private $model;


    /**
     * EloquentProductAttributesData constructor.
     * @param ProductAttributesData $model
     */
    public function __construct(ProductAttributesData $model)
    {
        $this->model = $model;
    }

    /**
     *
     */
    public function getAll()
    {
        return $this->model
            ->orderBy('id', 'desc')
            //->take(100)
            ->paginate(10);
    }

    /**
     * @param $id
     */
    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @param $column
     * @param $value
     */
    public function getByAny($column, $value)
    {
        return $this->model->where($column, $value)->get();
    }

    /**
     * @param array $att
     */
    public function create(array $att)
    {
        return $this->model->create($att);
    }

    /**
     * @param array $att
     * @return mixed
     */
    public function insert(array $att)
    {
        return $this->model->insert($att);
    }

    /**
     * @param $id
     * @param array $att
     */
    public function update($id, array $att)
    {
        $todo = $this->getById($id);
        $todo->update($att);
        return $todo;
    }

    public function delete($id)
    {
        $this->getById($id)->delete();
        return true;
    }

    public function deleteByAny($column, $value)
    {
        return $this->model->where($column, $value)->delete();
    }


    public function updateByWhere(array $column, array $value)
    {
        return $this->model
            ->where($column)
            ->update($value);
    }

}