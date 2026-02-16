<?php

namespace App\Repositories\Media;


use App\Models\Image;


class EloquentMedia implements MediaInterface
{
    private $model;
    

    /**
     * EloquentMedia constructor.
     * @param Media $model
     */
    public function __construct(Image $model)
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
        return $this->model->where('status', 1)
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


    public function getByAny($column, $value)
    {
        return $this->model->where($column,$value)->get();
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

    public function getMediaOnSearch(Array $options = array())
    {

        $default = array(
            'search_key' => null,
            'limit' => 10,
            'offset' => 0
        );

        $no = array_merge($default, $options);

        //dd($no);

        if (!empty($no['limit'])) {
            $limit = $no['limit'];
        } else {
            $limit = 10;
        }

        if (!empty($no['offset'])) {
            $offset = $no['offset'];
        } else {
            $offset = 0;
        }

        // LOC = line of code
        if (!empty($no['search_key'])) {

            return $this->model
                ->orWhere('original_name', 'like', "%{$no["search_key"]}%")
                ->orWhere('filename', 'like', "%{$no["search_key"]}%")
                ->orWhere('file_type', 'like', "%{$no["search_key"]}%")
                ->offset($offset)
                ->limit($limit)
                ->paginate(20);

        }

    }
}
