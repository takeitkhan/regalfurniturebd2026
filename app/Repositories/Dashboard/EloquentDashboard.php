<?php

namespace App\Repositories\Dashboard;


use App\Models\Dashboard;

class EloquentDashboard implements DashboardInterface
{
    private $model;


    /**
     * EloquentDashboard constructor.
     * @param Dashboard $model
     */
    public function __construct(Dashboard $model)
    {
        $this->model = $model;
    }

    /**
     *
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * @param $id
     */
    public function getById($id)
    {
        // return $this->model->find($id);
        return $this->model->where('id', $id)->first();
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