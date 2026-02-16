<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Repositories\Dashboard\DashboardInterface;
use Harimayco\Menu\Facades\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Validator;

class DashboardController extends Controller
{

    protected $dashboard;
    
    /**
     * DashboardController constructor.
     */
    public function __construct(DashboardInterface $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    public function index()
    {
        // dd( \App\Models\OrdersMaster::count());
        return view('dashboard');
    }

    /**
     * WordPress Like Menus Manager
     * @return $this
     */
    public function menus()
    {
        $menuList = Menu::get(1);
        return view('menu.index')->with('menus', $menuList);
    }

    /**
     * @return $this
     */
    public function widgets()
    {
        $widgets = $this->dashboard->getAll();
        return view('menu.widgets')->with('widgets', $widgets);
    }

    public function edit_widget($id)
    {
        if (isset($id)) {
            $widget = $this->dashboard->getById($id);
            $widgets = $this->dashboard->getAll();
            return view('menu.widgets')
                ->with('widget', $widget)
                ->with('widgets', $widgets);
        }

    }

    public function widget_update_save(Request $request, $id)
    {
        //$id = $request->get('widget_id');

        $d = $this->dashboard->getById($id);
        //dd($d);
        // store
        $attributes = [
            'name' => $request->get('widget_name'),
            'type' => $request->get('widget_type'),
            'position' => $request->get('widget_position'),
            'cssid' => $request->get('widget_id'),
            'cssclass' => $request->get('widget_css_class'),
            'description' => $request->get('widget_content'),
            'special' => TRUE,
            'is_active' => (int)$request->get('is_active')
        ];
        //dd($attributes);
        try {
            $this->dashboard->update($id, $attributes);
            Cache::forget('common-widget-id'.$id);
            return redirect('widgets')->with('success', 'Successfully save changed');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('widgets')->withErrors($ex->getMessage());
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
                'widget_name' => 'required',
                'widget_type' => 'required',
                'widget_position' => 'required',
                'widget_content' => 'required'
            ]
        );

        // process the login
        if ($validator->fails()) {
            return redirect('widgets')
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            $attributes = [
                'name' => $request->get('widget_name'),
                'type' => $request->get('widget_type'),
                'position' => $request->get('widget_position'),
                'cssid' => $request->get('widget_id'),
                'cssclass' => $request->get('widget_css_class'),
                'description' => $request->get('widget_content'),
                'special' => TRUE,
                'is_active' => (int)$request->get('is_active')
            ];
            //dd($attributes);

            try {
                $this->dashboard->create($attributes);
                return redirect('widgets')->with('success', 'Successfully save changed');
            } catch (\Illuminate\Database\QueryException $ex) {
                return redirect('widgets')->withErrors($ex->getMessage());
            }
        }

    }

    /**
     * @param $id
     * @return $this
     */
    public function destroy($id)
    {
        try {
            $this->dashboard->delete($id);
            return redirect('widgets')->with('success', 'Successfully deleted');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('widgets')->withErrors($ex->getMessage());
        }
    }
}
