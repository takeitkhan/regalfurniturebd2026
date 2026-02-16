<?php

namespace App\Repositories\Temporaryorder;


use App\Models\Temporaryorder;

class EloquentTemporaryorder implements TemporaryorderInterface
{
    private $model;


    /**
     * EloquentTemporaryorder constructor.
     * @param Temporaryorder $model
     */
    public function __construct(Temporaryorder $model)
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