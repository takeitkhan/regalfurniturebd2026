<?php

namespace App\Repositories\SessionData;
use App\Models\SessionData;

class EloquentSessionData implements SessionDataInterface
{
    private $model;
    /**
     * EloquentRelatedProducts constructor.
     * @param RelatedProducts $model
     */
    public function __construct(SessionData $model)
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

    public function updateByKey($key,$value)
    {
        $attr = [
            'session_key' => $key,
            'session_data' => $value
            /**
            992944 => array:5 [▼
                  "item" => array:14 [▼

                    "purchaseprice" => 935
                    "qty" => "1"
                    "variation_id" => "34"
                    "variation_info" => array:12 [▼
                      "id" => 34
                      "main_pid" => 1619
                      "main_pcode" => "992944"
                      "variations" => {#1879 ▶}
                    "product_regular_price" => "1100"
                      "product_selling_discount" => "15"
                      "variation_product_code" => "992944x2"
                      "variation_image" => "5583"
                      "local_selling_price" => 935
                      "local_selling_discount" => "15"
                      "local_regular_price" => "1100"
                      "save" => 165
                    ]
                    "is_dp" => null
                    "flash_discount" => null
                    "item_code" => null
                    "dis_tag" => "15"
                    "pre_price" => "1100"
                    "pset_id" => null
                    "fabric_id" => null
                  ]
        **/

        ];

        $exist = $this->getFirstByKey($key);

        if($exist)
            // cart_16628340241930.smmuxgr1tko,
            return $this->update($exist->id, $attr);
        else
            return $this->create($attr);
    }


    public function store($key,$value)
    {
        $attr = [
            'session_key' => $key,
            'value' => $value
        ];

        return $this->create($attr);
    }

    public function getFirstByKey($key)
    {
        return $this->model->where('session_key',$key)->first();
    }

    public function getAllByKey($key)
    {
        return $this->model->where('session_key',$key)->get();
    }

}
