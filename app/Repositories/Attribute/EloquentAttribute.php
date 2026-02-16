<?php

namespace App\Repositories\Attribute;


use App\Models\Attribute;

class EloquentAttribute implements AttributeInterface
{
    private $model;


    /**
     * EloquentAttribute constructor.
     * @param Attribute $model
     */
    public function __construct(Attribute $model)
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
            ->paginate(50);
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
     * @return mixed
     */
    public function getByAny($column, $value, $orderBy = null)
    {
        if (!empty($orderBy)) {
            return $this->model->where($column, $value)->orderBy('position', 'asc')->get();
        } else {
            return $this->model->where($column, $value)->get();
        }

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

    public function update_or_create(array $array)
    {
        $this->model->update_or_create($array);
    }

//    Custom Function

    /**
     * @param $id
     * @return mixed
     */
    public function getFilter($id){
        $field_cap_one = 'both';
        $field_cap_two = 'filterable';
        return $this->model
            ->where('attgroup_id', $id)
            ->where(function($query) use ($field_cap_one, $field_cap_two){
                $query->where('field_capability', $field_cap_one);
                $query->orWhere('field_capability', $field_cap_two);
            })
            ->get();
    }

}