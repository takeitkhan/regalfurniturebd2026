<?php

namespace App\Repositories\Slider;

use App\Models\Slider;

class EloquentSlider implements SliderInterface
{
    private $model;


    /**
     * EloquentDistrict constructor.
     * @param District $model
     */
    public function __construct(Slider $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
    
        return $this->model->orderBy('id', 'desc')
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
        return $this->model->where($column, $value)->with('image')->get();
    }


    public function getByFilter(array $filter = [],$orderBy = ['position' => 'asc'],$limit = 0)
    {
        $filter = array_merge($filter,[
            'active' => 1
        ]);

        $results = $this->model->where($filter);

        if($orderBy != null && is_array($orderBy) && count($orderBy) > 0){
            $order_column = array_key_first($orderBy);
            $order_value = $orderBy[$order_column];
            $results = $results->orderBy($order_column,$order_value);
        }

        if($limit !== 0 && $limit !== null){
            $results = $results->limit($limit);
        }


        return $results->with('image')->get();
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