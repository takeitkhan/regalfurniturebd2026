<?php

namespace App\Repositories\User;


use App\Models\User;

class EloquentUser implements UserInterface
{
    private $model;


    /**
     * EloquentMedia constructor.
     * @param Media $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     *
     */
    public function getAll(Array $options = array())
    {
        $default = array(
            'column' => null,
            'search_key' => null,
            'is_active' => null
        );

        $new = array_merge($default, $options);

        if (!empty($new['order_status'])) {
            $os = " is_active = '" . $new['order_status'] . "'";
        } else {
            $os = " is_active IS NOT NULL OR is_active IS NULL";
        }

        if (!empty($new['search_key']) && !empty($new['column'])) {
            //dd($this->model->whereRaw('' . $new['column'] . ' like "%' . $new['search_key'] . '%"')->toSql());
            return $this->model
                //->where($new['column'], $new['search_key'])
                ->whereRaw('' . $new['column'] . ' like "%' . $new['search_key'] . '%"')
                ->orderBy('id', 'desc')
                ->paginate(25);
        } else {
            return $this->model
                ->whereRaw($os)
                ->orderBy('id', 'desc')
                ->paginate(25);
        }
    }

    /**
     * @param $id
     */
    public function getById($id)
    {
        return $this->model->find($id);
    }

    /**
     * @param $email
     */
    public function getByEmail($email)
    {
        return $this->model->where('email', $email)->get()->first();
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
