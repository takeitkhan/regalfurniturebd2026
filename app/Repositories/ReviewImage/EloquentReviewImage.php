<?php

namespace App\Repositories\ReviewImage;

use App\Models\ReviewImage;

class EloquentReviewImage implements ReviewImageInterface
{
    private $model;


    /**
     * EloquentReview constructor.
     * @param Review $model
     */
    public function __construct(ReviewImage $model)
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

    public function self(){
        return $this->model;
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
