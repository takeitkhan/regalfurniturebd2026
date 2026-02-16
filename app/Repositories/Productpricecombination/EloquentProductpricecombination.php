<?php

namespace App\Repositories\Productpricecombination;


use App\Models\Productpricecombination;

class EloquentProductpricecombination implements ProductpricecombinationInterface
{
    private $model;


    /**
     * EloquentProductpricecombination constructor.
     * @param Productpricecombination $model
     */
    public function __construct(Productpricecombination $model)
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
     * @param $column
     * @param $value
     */
    public function getByColumns(array $options = array())
    {

        $default = array(
            'type' => null,
            'id' => null
        );

        $no = array_merge($default, $options);

        return $this->model
            ->where('type', $no['type'])
            ->where('id', $no['id'])
            ->get();
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