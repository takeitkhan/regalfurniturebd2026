<?php

namespace App\Repositories\FlashShedule;


use App\Models\FlashShedule;
use Carbon\Carbon;

class EloquentFlashShedule implements FlashSheduleInterface
{
    private $model;


    /**
     * EloquentFlashShedule constructor.
     * @param FlashShedule $model
     */
    public function __construct(FlashShedule $model)
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

    public function currentFlash()
    {
        $model = $this->model->where('fs_start_date','<=',Carbon::now('Asia/Dhaka'))
                             ->where('fs_end_date','>=',Carbon::now('Asia/Dhaka'))
                             ->where('fs_is_active',1)
                             ->orderBy('fs_end_date','ASC');
                             

        return $model->first();
    }
}