<?php

namespace App\Repositories\Comment;


use App\Models\Comment;

class EloquentComment implements CommentInterface
{
    private $model;


    /**
     * EloquentComment constructor.
     * @param Comment $model
     */
    public function __construct(Comment $model)
    {
        $this->model = $model;
    }


    public function getAll()
    {
        return $this->model
            ->orderBy('id', 'desc')
            //->take(100)
            ->paginate(10);
    }


    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

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

    // Custom methods

    public function getByParentId($id)
    {
        return $this->model
            ->orderBy('id', 'desc')
            ->where('parent_id', $id)
            ->where('is_active', 1)
            ->limit(40)
            ->get();
    }

    public function getByProductId($id)
    {
        return $this->model
            ->orderBy('id', 'desc')
            ->where('item_id', $id)
            ->where('is_active', 1)
            ->limit(40)
            ->get();
    }
}