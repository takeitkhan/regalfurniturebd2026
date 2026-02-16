<?php

namespace App\Repositories\ProductSet;
use App\Models\ProductSet;

class EloquentProductSet implements ProductSetInterface
{
    private $model;

    /**
     * EloquentRelatedProducts constructor.
     * @param RelatedProducts $model
     */
    public function __construct(ProductSet $model)
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
        return $this->model->find($id);
    }

    
    public function self(){
        return $this->model;
    }
    /**
    * @param $column
    * @param $value
    */
    public function getByAny($column, $value)
    {
        return $this->model->where($column, $value)->with('image')->get();
    }

    /**
     * @param array $att
     */
    public function create(array $att)
    {
        return $this->model->create($att);
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
}
