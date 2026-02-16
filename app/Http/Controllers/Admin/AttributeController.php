<?php

namespace App\Http\Controllers\Admin;


use App\Repositories\Attgroup\AttgroupInterface;
use Illuminate\Http\Request;
use Validator;

use App\Http\Controllers\Controller;
use App\Repositories\Attribute\AttributeInterface;

class AttributeController extends Controller
{
    protected $attribute;
    /**
     * @var AttgroupInterface
     */
    private $attgroup;

    /**
     * attributeController constructor.
     * @param AttgroupInterface $attgroup
     */
    public function __construct(AttributeInterface $attribute, AttgroupInterface $attgroup)
    {
        $this->attribute = $attribute;
        $this->attgroup = $attgroup;
    }


    /**
     * @return $this
     */
    public function attributes()
    {
        $attributes = $this->attribute->getAll();
        $categories = $this->attribute->getAll()->toArray();
        return view('attributes.attributes')
            ->with('attributes', $attributes)
            ->with('cats', $categories);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function add_attributes(Request $request)
    {

        $id = $request->segment(2);
        $attgroup = $this->attgroup->getById($id);

        $single_id = $request->get('single');
        if (!empty($single_id)) {
            $single = $this->attribute->getById($single_id);
            //dd($single);
        } else {
            $single = '';
        }

        $attributes = $this->attribute->getByAny('attgroup_id', $id, 'position');

        return view('attributes.add_attributes')
            ->with(['attributes' => $attributes, 'attgroup' => $attgroup, 'att' => $single]);

    }

    /**
     * @param $id
     * @return $this
     */

    public function edit_attribute($id)
    {
//        if (isset($id)) {
//            $attribute = $this->attribute->getById($id);
//            $attributes = $this->attribute->getAll();
//            $categories = $this->attribute->getAll()->toArray();
//            return view('attribute.attributes')
//                ->with('attribute', $attribute)
//                ->with('attributes', $attributes)
//                ->with('cats', $categories);
//        }

    }

    public function sortable_update(Request $request)
    {
        $jDecode = json_decode($request->get('data'));

        foreach ($jDecode as $key => $value) {
            //dump($value->data_id);
            //dump($value->p_position);
            //dump($value->c_position);

            $attributes = ['position' => $value->c_position];

            $this->attribute->update($value->data_id, $attributes);
        }

        //die();
    }

    /**
     * @param \App\Http\Controllers\Admin\Request $request
     * @param $id
     * @return $this|RedirectResponse
     */
    public function attribute_update_save(Request $request, $id)
    {
        $this->attribute->getById($id);
        //dd($d);
        // store
        $attributes = [
            'user_id' => $request->get('user_id'),
            'attgroup_id' => $request->get('attgroup_id'),
            'field_label' => $request->get('field_label'),
            'field_name' => $request->get('field_name'),
            'css_class' => $request->get('css_class'),
            'css_id' => $request->get('css_id'),
            'minimum' => $request->get('minimum'),
            'maximum' => $request->get('maximum'),
            'prepend' => $request->get('prepend'),
            'append' => $request->get('append'),
            'field_type' => $request->get('field_type'),
            'field_capability' => $request->get('field_capability'),
            'instructions' => $request->get('instructions'),
            'is_required' => $request->get('is_required'),
            'default_value' => $request->get('default_value'),
            'placeholder' => $request->get('placeholder'),
            'position' => $request->get('position'),
        ];
        //dd($attributes);

        try {
            $this->attribute->update($id, $attributes);
            return redirect('add_attributes/' . $request->get('attgroup_id'))->with('success', 'Successfully save changed');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('add_attributes/' . $request->get('attgroup_id'))->withErrors($ex->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return $this
     * @internal param Request $request
     */
    public function store(Request $request)
    {
        // validate
        // read more on validation at
        $validator = Validator::make($request->all(),
            [
                'field_label' => 'required',
                'field_name' => 'required',
                'field_type' => 'required',
                'is_required' => 'required',
            ]
        );

        // process the login
        if ($validator->fails()) {
            return redirect('attributes')
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            $attributes = [
                'user_id' => $request->get('user_id'),
                'attgroup_id' => $request->get('attgroup_id'),
                'field_label' => $request->get('field_label'),
                'field_name' => strtolower($request->get('field_name')),
                'css_class' => $request->get('css_class'),
                'css_id' => $request->get('css_id'),
                'minimum' => $request->get('minimum'),
                'maximum' => $request->get('maximum'),
                'prepend' => $request->get('prepend'),
                'append' => $request->get('append'),
                'field_type' => $request->get('field_type'),
                'field_capability' => $request->get('field_capability'),
                'instructions' => $request->get('instructions'),
                'is_required' => $request->get('is_required'),
                'default_value' => $request->get('default_value'),
                'placeholder' => $request->get('placeholder'),
                'position' => $request->get('position'),
            ];

            //dd($attributes);

            try {
                $this->attribute->create($attributes);
                return redirect('add_attributes/' . $request->get('attgroup_id'))->with('success', 'Successfully save changed');
            } catch (\Illuminate\Database\QueryException $ex) {
                //dd($ex->errorInfo[1]);
                if ($ex->errorInfo[1] === 1048) {
                    $msg = $ex->errorInfo[2];
                } else {
                    $msg = $ex->getMessage();
                }
                return redirect('add_attributes/' . $request->get('attgroup_id'))->withErrors($msg);
            }
        }

    }

    /**
     * @param Request $request
     * @return $this
     */
    public function destroy(Request $request)
    {
        //dd($request);
        $attribute_id = $request->get('attribute_id');
        $attgroup_id = $request->get('attgroup_id');
        try {
            $this->attribute->delete($attribute_id);
            $product_attribute_data = \App\Models\ProductAttributesData::where('attribute_id', $attribute_id)->where('attgroup_id', $attgroup_id)->delete();
            //return redirect('attributes')->with('success', 'Successfully deleted');
            return redirect()->back()->with('success', 'Successfully deleted');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect()->back()->withErrors($ex->getMessage());
            //return redirect('attributes')->withErrors($ex->getMessage());
        }
    }
}
