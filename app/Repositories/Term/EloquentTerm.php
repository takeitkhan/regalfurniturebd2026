<?php

namespace App\Repositories\Term;


use App\Models\Term;

class EloquentTerm implements TermInterface
{
    private $model;


    /**
     * EloquentTerm constructor.
     * @param Term $model
     */
    public function __construct(Term $model)
    {
        $this->model = $model;
    }

    public function self()
    {
        return $this->model;
    }

    /**
     *
     */
    public function getAll()
    {
        return $this->model
            ->orderBy('name', 'asc')
            //->take(100)
            ->paginate(500);
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
    public function getByAny($column, $value)
    {
        $terms = $this->model->where($column, $value);
        
        return $terms->get();
    }

    public function getByFilter(array $filter = [])
    {
        return $this->model->where($filter)->orderBy('position','asc')->with('home_img','page_img','sub_cats')->withCount('products')->get();
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
     * Extra Methods
     */

    public function get_terms_by_options(array $options = array())
    {
        $defaults = array(
            'type' => 'category',
            'limit' => 100,
            'offset' => 0
        );
        $optionss = array_merge($defaults, $options);
        //$this->model->orderBy('id', 'desc');
        return $this->model->where('type', ($optionss['type'] === 'category') ? 'category' : 'others')->take(!empty($optionss['limit']) ? $optionss['limit'] : 20)->get();
    }

    public function getWhereIn(array $whare = array()){
       return $this->model->whereIn('id',$whare)->orderBy('name', 'ASC')->get();

    }

}