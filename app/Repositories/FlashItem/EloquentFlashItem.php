<?php

namespace App\Repositories\FlashItem;


use App\Models\FlashItem;

class EloquentFlashItem implements FlashItemInterface
{
    private $model;


    /**
     * EloquentFlashItem constructor.
     * @param FlashItem $model
     */
    public function __construct(FlashItem $model)
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
        return $this->model->where($column, $value)->with('product','product.firstImage','product.secondImage','product.product_set')->orderBy('id','desc')->get();
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

    /**
     * @param array $att
     * @return bool
     */
    public function getWhere(array $att)
    {
        return $this->model->where($att)
            ->with('product','product.firstImage','product.secondImage','product.product_set')
            ->orderBy('id', 'desc')
            ->paginate(30);

    }
}