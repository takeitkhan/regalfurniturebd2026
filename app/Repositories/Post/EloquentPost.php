<?php

namespace App\Repositories\Post;

use App\Models\Attribute;
use App\Models\Post;

class EloquentPost implements PostInterface
{
    private $model;
    /**
     * @var Attribute
     */
    private $attribute;


    /**
     * EloquentPost constructor.
     * @param Post $model
     * @param Attribute $attribute
     */
    public function __construct(Post $model, Attribute $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
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

    public function self()
    {
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
     * @param array $options
     * @return mixed
     */

    public function getByAny($column, $value)
    {
        return $this->model->where($column, $value)->get();
    }


    public function getPostsOnCategories(array $options)
    {
        $default = array(
            'search_key' => null,
            'limit' => 10,
            'categories' => array(),
            'offset' => 0
        );

        $no = array_merge($default, $options);
        //dd($no);
        if (!empty($no['categories'])) {
            return $this->model->whereIn('categories', $no['categories'])
                ->orderBy('id', 'desc')
                ->groupBy('id')
                ->skip($no['offset'])
                ->take($no['limit'])
                ->get();
        } else {
            $this->model
                ->orWhere('categories', 'like', "%{$no["search_key"]}%")
                ->orWhere('title', 'like', "%{$no["search_key"]}%")
                ->orWhere('sub_title', 'like', "%{$no["search_key"]}%")
                ->orWhere('seo_url', 'like', "%{$no["search_key"]}%")
                ->orWhere('description', 'like', "%{$no["search_key"]}%")
                ->whereIn('categories', $no['categories'])->orderBy('id', 'desc')
                ->groupBy('id')
                ->skip($no['offset'])
                ->take($no['limit'])
                ->get();
        }
    }

     /**
     *
     */
    public function getByArr(array $options)
    {
        $default = array(
            'search_key' => null,
            'limit' => 10,
            'categories' => null,
            'offset' => 0
        );
        $no = array_merge($default, $options);
//         dd( $no );
        $data = $this->model;
        if($no['categories'] != null){
            $data = $data->where('categories','LIKE','%'. $no['categories'].'%');
            // dd($no['categories']);
        }

        if($no['search_key'] != null){
            $data = $data->where('title','LIKE','%'. $no['search_key'].'%');
//             dd($no['search_key']);
        }
        $data = $data->orderBy('id', 'desc')->paginate($no['limit']);

        return $data;
    }


}
