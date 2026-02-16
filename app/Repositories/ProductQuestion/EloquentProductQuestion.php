<?php

namespace App\Repositories\ProductQuestion;


use App\Models\ProductQuestion;

class EloquentProductQuestion implements ProductQuestionInterface
{
    private $model;


    /**
     * EloquentProductQuestion constructor.
     * @param ProductQuestion $model
     */
    public function __construct(ProductQuestion $model)
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

   public function getByFilter(array $options)
    {
        $default = array(
            'main_pid' => null,
            'titel' => null
        );

        $new = array_merge($default, $options);

       // dd($new);
       
        $data =  $this->model->where(['qa_type' => 1]);

        if($new['main_pid']) {
            $data = $data->where(['main_pid' => $new['main_pid']]);
        }
        if($new['titel']){
            $data = $data->where('description', 'like', '%' . $new['titel'] . '%');
        }

        $data = $data->orderBy('id', 'desc')
            ->paginate(10);

        return $data;

    }
}
