<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller;
use App\Repositories\Attgroup\AttgroupInterface;

class AttgroupController extends Controller
{
    /**
     * @var AttgroupInterface
     */
    private $attgroup;

    /**
     * AttgroupController constructor.
     * @param AttgroupInterface $attgroup
     */
    public function __construct(AttgroupInterface $attgroup)
    {
        $this->attgroup = $attgroup;
    }

    public function attgroups()
    {
        $attributes = $this->attgroup->getAll();
        return view('attgroup.attgroups')
            ->with('attgroups', $attributes);
    }

    public function add_attgroup(Request $request)
    {
        $attgroups = $this->attgroup->getAll();
        return view('attgroup.add_att_group')
            ->with('attgroups', $attgroups);
    }

    public function edit_att_group($id)
    {
        $att_group = $this->attgroup->getById($id);
        return view('attgroup.add_att_group')
            ->with('attgroup', $att_group);
    }

    public function attgroup_update_save(Request $request, $id)
    {
        $validator = Validator::make($request->all(),
            [
                'group_name' => 'required',
                'group_name_slug' => 'required',
                'position' => 'required'
            ]
        );


        if ($validator->fails()) {
            return redirect('attgroups')
                ->withErrors($validator)
                ->withInput();
        } else {
            $attributes = [
                'user_id' => $request->get('user_id'),
                'group_name' => $request->get('group_name'),
                'group_name_slug' => $request->get('group_name_slug'),
                'position' => $request->get('position'),
                'is_active' => 1
            ];

            try {

                //dd($attributes);
                $this->attgroup->update($request->get('group_id'), $attributes);
                return redirect('attgroups')->with('success', 'Successfully Modified');
            } catch (\Illuminate\Database\QueryException $ex) {
                return redirect('attgroups')->withErrors($ex->getMessage());
            }
        }
    }

    public function store(Request $request)
    {


        //dd($request);
        $validator = Validator::make($request->all(),
            [
                'group_name' => 'required',
                'group_name_slug' => 'required',
                'position' => 'required'
            ]
        );


        if ($validator->fails()) {
            return redirect('attgroups')
                ->withErrors($validator)
                ->withInput();
        } else {
            $attributes = [
                'user_id' => $request->get('user_id'),
                'group_name' => $request->get('group_name'),
                'group_name_slug' => $request->get('group_name_slug'),
                'position' => $request->get('position'),
                'is_active' => 1
            ];

            try {

                //dd($attributes);
                $this->attgroup->create($attributes);
                return redirect('attgroups')->with('success', 'Successfully Added');
            } catch (\Illuminate\Database\QueryException $ex) {
                return redirect('attgroups')->withErrors($ex->getMessage());
            }
        }
    }

    public function destroy($id)
    {
        try {

            $padata = \App\Models\ProductAttributesData::where('attgroup_id', $id)->delete();
            $attributes = \App\Models\Attribute::where('attgroup_id', $id)->delete();
            $term_connection_update = \App\Models\Term::where('connected_with', $id)->update(array('connected_with' => NULL));
            $this->attgroup->delete($id);

            return redirect()->back()->with('success', 'Successfully deleted');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect()->back()->withErrors($ex->getMessage());
        }
    }
}
