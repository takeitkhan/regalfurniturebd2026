<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\OrdersMaster;
use App\Models\Product;
use App\Repositories\Dashboard\DashboardInterface;
use DB;
use Harimayco\Menu\Facades\Menu;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Validator;
use function compact;

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
        // Total products count (no optimization needed here)
        $total_product = Product::query()->count('id');

        // Get all order status counts in one query
        $orderStatusCounts = OrdersMaster::query()->select('order_status', DB::raw('count(*) as count'))
            ->groupBy('order_status')
            ->pluck('count', 'order_status');

        $total_orders = $orderStatusCounts->sum();

        // Assign values or default to 0 if not present
        $placed_orders = $orderStatusCounts['placed'] ?? 0;
        $processing_orders = $orderStatusCounts['processing'] ?? 0;
        $confirmed_orders = $orderStatusCounts['confirmed'] ?? 0;
        $production_orders = $orderStatusCounts['production'] ?? 0;
        $complete_orders = $orderStatusCounts['done'] ?? 0;
        $customer_unreachable_orders = $orderStatusCounts['Customer-Unreachable'] ?? 0;
        $cancelled_orders = $orderStatusCounts['cancel'] ?? 0;
        $refund_orders = $orderStatusCounts['refund'] ?? 0;

        return view('dashboard', compact(
            'total_product',
            'total_orders',
            'placed_orders',
            'processing_orders',
            'confirmed_orders',
            'production_orders',
            'complete_orders',
            'customer_unreachable_orders',
            'cancelled_orders',
            'refund_orders'
        ));
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
            'special' => true,
            'is_active' => (int)$request->get('is_active')
        ];
        //dd($attributes);
        try {
            $this->dashboard->update($id, $attributes);
            Cache::forget('common-widget-id'.$id);
            return redirect('widgets')->with('success', 'Successfully save changed');
        } catch (QueryException $ex) {
            return redirect('widgets')->withErrors($ex->getMessage());
        }
    }

    /**
     * @param  Request  $request
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
                'special' => true,
                'is_active' => (int)$request->get('is_active')
            ];
            //dd($attributes);

            try {
                $this->dashboard->create($attributes);
                return redirect('widgets')->with('success', 'Successfully save changed');
            } catch (QueryException $ex) {
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
        } catch (QueryException $ex) {
            return redirect('widgets')->withErrors($ex->getMessage());
        }
    }
}
