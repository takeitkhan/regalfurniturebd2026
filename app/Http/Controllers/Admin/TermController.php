<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Term\TermInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TermController extends Controller
{
    protected $term;

    /**
     * TermController constructor.
     */
    public function __construct(TermInterface $term)
    {
        $this->term = $term;
    }

    /**
     * @return $this
     */
    public function terms()
    {
        $terms = $this->term->getAll();
        $categories = $this->term->getAll()->toArray();
        return view('term.terms')
            ->with('terms', $terms)
            ->with('cats', $categories);
    }

    /**
     * @param $id
     * @return $this
     */
    public function edit_term($id)
    {
        if (isset($id)) {
            $term = $this->term->getById($id);
            $terms = $this->term->getAll();
            //dd($term);
            $categories = $this->term->getAll()->toArray();
            return view('term.terms')
                ->with('term', $term)
                ->with('terms', $terms)
                ->with('cats', $categories);
        }
    }

    /**
     * @param \App\Http\Controllers\Admin\Request $request
     * @param $id
     * @return $this|RedirectResponse
     */
    public function term_update_save(Request $request, $id)
    {
        //dd($request);
        //$id = $request->get('term_id');

        $d = $this->term->getById($id);
        //dd($d);
        // store
        $attributes = [
            'name' => $request->get('term_name'),
            'seo_url' => $request->get('seo_url'),
            'type' => $request->get('term_type'),
            'position' => $request->get('term_position'),
            'cssid' => $request->get('term_css_id'),
            'cssclass' => $request->get('term_css_class'),
            'description' => $request->get('description'),
            'term_keywords' => $request->get('term_keywords'),
            'seo_h1' => $request->get('seo_h1'),
            'seo_h2' => $request->get('seo_h2'),
            'seo_h3' => $request->get('seo_h3'),
            'seo_h4' => $request->get('seo_h4'),
            'seo_h5' => $request->get('seo_h5'),
            'parent' => $request->get('term_parent'),
            'connected_with' => $request->get('connected_with'),
            'page_image' => $request->get('page_image'),
            'home_image' => $request->get('home_image'),
            'term_menu_icon' => $request->get('term_menu_icon'),
            'term_menu_arrow' => $request->get('term_menu_arrow'),
            'with_sub_menu' => $request->get('with_sub_menu'),
            'sub_menu_width' => $request->get('sub_menu_width'),
            'banner1' => $request->get('banner1'),
            'banner2' => $request->get('banner2'),
            'special_notification' => $request->get('special_notification'),
            'column_count' => $request->get('column_count'),
            'is_published' => $request->get('is_published'),
            'in_product_home' => $request->get('in_product_home'),
            'is_active' => 1
        ];

        //dd($attributes);

        try {
            $this->term->update($id, $attributes);

            Cache::forget('category-category-info-'.$request->seo_url);
            Cache::forget('category-category-subcat-'.$request->seo_url);
            Cache::forget('category-category-subcat-products');
            Cache::forget('category-category-recommended'.$request->seo_url);
            Cache::forget('category-category-filter'.$request->seo_url);
            Cache::forget('category-category-tag-gal'.$request->seo_url);
            Cache::forget('home-top-category');


            $parentTerm = $this->term->getById($request->get('term_parent'));

            if($parentTerm) {
                Cache::forget('category-category-info-'.$parentTerm->seo_url);
                Cache::forget('category-category-subcat-'.$parentTerm->seo_url);
                Cache::forget('category-category-recommended'.$parentTerm->seo_url);
                Cache::forget('category-category-filter'.$parentTerm->seo_url);
                Cache::forget('category-category-tag-gal'.$parentTerm->seo_url);
                Cache::forget('home-top-category');
                Cache::forget('category-category-subcat-products');

            }

            return redirect('terms')->with('success', 'Successfully save changed');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('terms')->withErrors($ex->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return $this
     * @internal param Request $request
     */
    public function store(Request $request)
    {
        //dd($request);
        // read more on validation at
        $validator = Validator::make(
            $request->all(),
            [
                'term_name' => 'required',
                'seo_url' => 'required',
                'term_id' => 'alpha_dash',
                'term_position' => 'required',
                // 'term_content' => 'required',
                // 'banner1' => 'nullable',
                // 'banner2' => 'nullable',
            ]
        );

        // process the login
        if ($validator->fails()) {
            return redirect('terms')
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            $attributes = [
                'name' => $request->get('term_name'),
                'seo_url' => $request->get('seo_url'),
                'type' => $request->get('term_type'),
                'position' => $request->get('term_position'),
                'cssid' => $request->get('term_css_id'),
                'cssclass' => $request->get('term_css_class'),
                'description' => $request->get('description'),
                'seo_h1' => $request->get('seo_h1'),
                'seo_h2' => $request->get('seo_h2'),
                'seo_h3' => $request->get('seo_h3'),
                'seo_h4' => $request->get('seo_h4'),
                'seo_h5' => $request->get('seo_h5'),
                'parent' => $request->get('term_parent'),
                'connected_with' => $request->get('connected_with'),
                'page_image' => $request->get('page_image'),
                'home_image' => $request->get('home_image'),
                'term_menu_icon' => $request->get('term_menu_icon'),
                'term_menu_arrow' => $request->get('term_menu_arrow'),
                'with_sub_menu' => $request->get('with_sub_menu'),
                'sub_menu_width' => $request->get('sub_menu_width'),
                'column_count' => $request->get('column_count'),
                'banner1' => $request->get('banner1'),
                'banner2' => $request->get('banner2'),
                'special_notification' => $request->get('special_notification'),
                'column_count' => $request->get('column_count'),
                'is_published' => $request->get('is_published'),
                'in_product_home' => $request->get('in_product_home'),
                'is_active' => 1
            ];

            //dd($attributes);
            try {
                $done = $this->term->create($attributes);

                if($request->get('term_parent')){

                    $parentTerm = $this->term->getById($request->get('term_parent'));

                    if($parentTerm) {
                        Cache::forget('category-category-info-'.$parentTerm->seo_url);
                        Cache::forget('category-category-subcat-'.$parentTerm->seo_url);
                        Cache::forget('category-category-recommended'.$parentTerm->seo_url);
                        Cache::forget('category-category-filter'.$parentTerm->seo_url);
                        Cache::forget('category-category-tag-gal'.$parentTerm->seo_url);
                        Cache::forget('home-top-category');
                        Cache::forget('category-category-subcat-products');

                    }


                }



                //dd($done);
                return redirect('terms')->with('success', 'Successfully save changed');
            } catch (\Illuminate\Database\QueryException $ex) {
                //dd($ex);
                return redirect('terms')->withErrors($ex->getMessage());
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
            $term = $this->term->getById($id);
            $this->term->delete($id);
            $parentTerm = $this->term->getById($term->term_parent);
            if($parentTerm) {
                Cache::forget('category-category-info-'.$parentTerm->seo_url);
                Cache::forget('category-category-subcat-'.$parentTerm->seo_url);
                Cache::forget('category-category-recommended'.$parentTerm->seo_url);
                Cache::forget('category-category-filter'.$parentTerm->seo_url);
                Cache::forget('category-category-tag-gal'.$parentTerm->seo_url);
                Cache::forget('home-top-category');
                Cache::forget('category-category-subcat-products');
            }


            return redirect('terms')->with('success', 'Successfully deleted');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('terms')->withErrors($ex->getMessage());
        }
    }

    // Custom Methods

    // public function termSerialise()
    // {
    //     // $termList = [];
    //     $termList = $this->term->self()->orderBy('serial', 'ASC')->where('type','category')->get();
    //     // foreach ($db_term as $term){
    //     //     $termList[$term->id] = $term;
    //     // }

    //     return view('term.termSerialise')->with('termList', $termList);
    // }

    // public function termSerialiseUpdate(Request $request){
    //    $tasks = $this->term->getAll();
    //     foreach ($tasks as $task) {
    //         $task->timestamps = false; // To disable update_at field updation
    //         $id = $task->id;

    //         foreach ($request->order as $order) {
    //             if ($order['id'] == $id) {
    //                 $task->update(['serial' => $order['position']]);

    //                 $parentTerm = $this->term->getById($task->term_parent);
    //                 if($parentTerm) {
    //                     Cache::forget('category-category-subcat-'.$parentTerm->seo_url);
    //                 }

    //             }
    //         }
    //     }


    //     return response($id, 200);
    // }


    public function termSerialise()
    {
        $termList = $this->term->self()
            ->where('is_published', 1)
            ->where('in_product_home', 1)
            ->where('id', '!=', 1)
            ->orderBy('serial', 'ASC')
            ->where('type', 'category')
            ->get();
        
        return view('term.termSerialise')->with('termList', $termList);
    }
    
    public function termSerialiseUpdate(Request $request)
    {
        $tasks = $this->term->getAll();
        
        foreach ($tasks as $task) {
            $task->timestamps = false; // To disable update_at field updation
            $id = $task->id;
            
            foreach ($request->order as $order) {
                if ($order['id'] == $id) {
                    $task->update(['serial' => $order['position']]);
                }
            }
        }
        
        
        return response($id, 200);
    }


    public function check_if_cat_url_exists(Request $request)
    {
        $seo_url = $request->get('seo_url');
        $term = $this->term->getByAny('seo_url', $seo_url);
        if ($term->first()) {
            $url = $term->first()->seo_url;
            $nu = $url . '-' . date('ms');
            $m = $nu;
        } else {
            $m = $seo_url;
        }
        return response()->json(['url' => $m]);
    }

    public function get_categories_on_search(Request $request)
    {
        $terms = \App\Models\Term::where('name', 'like', '%' . $request->get('search_param') . '%')->orderBy('name', 'asc')->get();
        //dd($terms);
        $main_pid = $request->get('main_pid');

        $html = null;
        foreach ($terms as $term) {
            $html .= '<option id="dblclick_cat"
            value="' . $term->id . '"
            data-mainpid="' . (!empty($main_pid) ? $main_pid : null) . '"
            data-userid="' . (!empty(Auth::user()->id) ? Auth::user()->id : null) . '"
            data-title="' . $term->name . '"
            data-attgroup="' . $term->connected_with . '">';
            $html .= $term->name;
            $html .= '</option>';

            $sub_terms = \App\Models\Term::where('parent', $term->id)->orderBy('name', 'asc')->get();
            foreach ($sub_terms as $sub_term) {
                $html .= '<option id="dblclick_cat"
                value="' . $sub_term->id . '"
                data-mainpid="' . (!empty($main_pid) ? $main_pid : null) . '"
                data-userid="' . (!empty(Auth::user()->id) ? Auth::user()->id : null) . '"
                data-title="' . $sub_term->name . '"
                data-attgroup="' . $sub_term->connected_with . '">';
                $html .= '&nbsp;&nbsp;&nbsp;' . $sub_term->name;
                $html .= '</option>';

                $sub_termss = \App\Models\Term::where('parent', $sub_term->id)->orderBy('name', 'asc')->get();
                foreach ($sub_termss as $sub_terms) {
                    $html .= '<option id="dblclick_cat"
                    value="' . $sub_terms->id . '"
                    data-mainpid="' . (!empty($main_pid) ? $main_pid : null) . '"
                    data-userid="' . (!empty(Auth::user()->id) ? Auth::user()->id : null) . '"
                    data-title="' . $sub_terms->name . '"
                    data-attgroup="' . $sub_terms->connected_with . '">';
                    $html .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $sub_terms->name;
                    $html .= '</option>';
                }
            }
        }

        return response()->json(['html' => $html]);
    }
}
