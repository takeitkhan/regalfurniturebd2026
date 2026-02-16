<?php

namespace App\Repositories\Newsletter;


use App\Models\Newsletter;

class EloquentNewsletter implements NewsletterInterface
{
    private $model;


    /**
     * EloquentNewsletter constructor.
     * @param Newsletter $model
     */
    public function __construct(Newsletter $model)
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

    public function getByAny($column,$value)
    {
        
        return $this->model->where($column,$value)->get();
    }

    /**
     * @param array $att
     */
    public function create(array $attr)
    {
        $subscriber = $this->getByAny('email',$attr['email']??null)->first();

        if($subscriber)
            return true;

        return $this->model->create($attr);
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