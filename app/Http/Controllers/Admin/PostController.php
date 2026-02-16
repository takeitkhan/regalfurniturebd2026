<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attribute;
use App\Exports\ShowroomsExport;
use App\Imports\ShowroomsImport;
use App\Repositories\Term\TermInterface;
use DB;
use Validator;
use App\Repositories\Attribute\AttributeInterface;
use App\Repositories\Media\MediaInterface;
use App\Repositories\Post\PostInterface;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Models\Term;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    /**
     * @var PostInterface
     */
    private $post;
    /**
     * @var MediaInterface
     */
    private $media;
    /**
     * @var AttributeInterface
     */
    private $attribute;
    /**
     * @var TermInterface
     */
    private $term;

    /**
     * PostController constructor.
     * @param PostInterface $post
     * @param AttributeInterface $attribute
     * @param MediaInterface $media
     * @param TermInterface $term
     */
    public function __construct(PostInterface $post, AttributeInterface $attribute, MediaInterface $media, TermInterface $term)
    {
        $this->post = $post;
        $this->media = $media;
        $this->attribute = $attribute;
        $this->term = $term;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function posts(Request $request)
    {

        $default = array(
            'categories' => null,
            'search_key' => $request->get('search_key')
        );
        $posts = $this->post->getByArr($default);

        return view('post.posts', compact('posts'))->with(['posts' => $posts]);
    }

    public function our_showroom()
    {

        $default = array(
            'categories' => 651
        );
        $posts = $this->post->getByArr($default);

        return view('post.posts', compact('posts'))->with(['posts' => $posts]);
    }


    /**
     * @param $id
     * @return $this
     */
    public function add_post()
    {
        $medias = $this->media->getAll();

        $terms = Term::where('parent', 2)->get();
        //dd($terms)  ;
        $default = [
            'type' => 'category',
            'limit' => 1000,
            'offset' => 0
        ];
        $cats = $this->get_post_categories($default);
        $categories = $cats->toArray();
        //dump($categories);

        return view('post.form')->with(['medias' => $medias, 'terms' => $terms->toArray(), 'categories' => $categories]);
    }

    public function add_showroom()
    {
        $medias = $this->media->getAll();

        $terms = Term::where('parent', 2)->get();
        //dd($terms)  ;
        $default = [
            'type' => 'category',
            'limit' => 1000,
            'offset' => 0
        ];
        $cats = $this->get_post_categories($default);
        $categories = $cats->toArray();

        Cache::forget('common-showrooms');
        //dump($categories);

        return view('post.form')->with(['medias' => $medias, 'terms' => $terms->toArray(), 'categories' => $categories]);
    }

    /**
     * @param $id
     * @return $this
     */
    public function edit_post($id)
    {
        if (isset($id)) {
            $post = $this->post->getById($id);
            //dd($post);
            $terms = $this->term->getAll()->toArray();

            $default = [
                'type' => 'category',
                'limit' => 1000,
                'offset' => 0
            ];
            $cats = $this->get_post_categories($default);
            $categories = $cats->toArray();
            $medias = $this->media->getAll();
            $divisions = DB::table('districts')->distinct()->select('division')->get();

            return view('post.form')
                ->with(['post' => $post, 'medias' => $medias, 'terms' => $terms, 'divisions' => $divisions, 'categories' => $categories]);
        }
    }

    public function edit_showroom($id)
    {
        if (isset($id)) {
            $post = $this->post->getById($id);
            //dd($post);
            $terms = $this->term->getAll()->toArray();

            $default = [
                'type' => 'category',
                'limit' => 1000,
                'offset' => 0
            ];
            $cats = $this->get_post_categories($default);
            $categories = $cats->toArray();
            $medias = $this->media->getAll();
            $divisions = DB::table('districts')->distinct()->select('division')->get();


            return view('post.form')
                ->with(['post' => $post, 'medias' => $medias, 'terms' => $terms, 'divisions' => $divisions, 'categories' => $categories]);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function post_update_save(Request $request, $id)
    {
        $d = $this->post->getById($id);
        //owndebugger($d); die();
        // validate
        // read more on validation at
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'seo_url' => 'required',
                'description' => 'required',
                'lang' => 'required'
            ]
        );
// dd($request->get('categories'));
        // process the login
        if ($validator->fails()) {
            return redirect('posts')
                ->withErrors($validator)
                ->withInput();
        } else {
            $attributes = [
                'user_id' => $request->get('user_id'),
                'title' => $request->get('title'),
                'sub_title' => $request->get('sub_title'),
                'seo_url' => $request->get('seo_url'),
                'author' => $request->get('author'),
                'description' => $request->get('description'),
                'categories' => !empty($request->get('categories')) && $request->get('categories') ? implode(',', $request->get('categories')) : '',
                'images' => $request->get('images'),
                'brand' => $request->get('brand'),
                'tags' => $request->get('tags'),
                'youtube' => $request->get('youtube'),
                'is_auto_post' => $request->get('is_auto_post'),
                'short_description' => $request->get('short_description'),
                'phone' => $request->get('phone'),
                'division' => get_dis_or_div_by_thana($request->get('thana'))->division,
                'district' => get_dis_or_div_by_thana($request->get('thana'))->district,
                'thana' => $request->get('thana'),
                'shop_type' => $request->get('shop_type'),
                'opening_hours' => $request->get('opening_hours'),
                'latitude' => $request->get('latitude'),
                'longitude' => $request->get('longitude'),
                'phone_numbers' => $request->get('phone_numbers'),
                'address' => $request->get('address'),
                'is_upcoming' => $request->get('is_upcoming'),
                'is_sticky' => $request->get('is_sticky'),
                'lang' => $request->get('lang'),
                'is_active' => $request->get('is_active')
            ];

            //dd($attributes);
            try {
                $post = $this->post->update($d->id, $attributes);
                Cache::forget('common-showrooms-'.$request->district);
                Cache::forget('common-showrooms-');
                //dd($post);
                return redirect()->back()->with('success', 'Successfully save changed');
            } catch (\Illuminate\Database\QueryException $ex) {
                return redirect()->back()->withErrors($ex->getMessage());
            }
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

        // validate
        // read more on validation at
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|max:255',
                'seo_url' => 'required',
                'description' => 'required',
                'categories' => 'required',
                'lang' => 'required'
            ]
        );

        // process the login
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            $attributes = [
                'user_id' => $request->get('user_id'),
                'title' => $request->get('title'),
                'sub_title' => $request->get('sub_title'),
                'seo_url' => $request->get('seo_url'),
                'author' => $request->get('author'),
                'description' => $request->get('description'),
                'categories' => !empty($request->get('categories')) && $request->get('categories') ? implode(',', $request->get('categories')) : '',
                'images' => $request->get('images'),
                'brand' => $request->get('brand'),
                'tags' => $request->get('tags'),
                'youtube' => $request->get('youtube'),
                'is_auto_post' => $request->get('is_auto_post'),
                'short_description' => $request->get('short_description'),
                'phone' => $request->get('phone'),
                'district' => get_dis_or_div_by_thana($request->get('thana'))->district,
                'division' => get_dis_or_div_by_thana($request->get('thana'))->division,
                'thana' => $request->get('thana'),
                'shop_type' => $request->get('shop_type'),
                'opening_hours' => $request->get('opening_hours'),
                'latitude' => $request->get('latitude'),
                'longitude' => $request->get('longitude'),
                'phone_numbers' => $request->get('phone_numbers'),
                'address' => $request->get('address'),
                'is_upcoming' => $request->get('is_upcoming'),
                'is_sticky' => $request->get('is_sticky'),
                'lang' => $request->get('lang'),
                'is_active' => $request->get('is_active')
            ];

            // dd($attributes);

            try {
                $this->post->create($attributes);
                Cache::forget('common-showrooms-'.$request->district);
                Cache::forget('common-showrooms-');
                return redirect()->back()->with('success', 'Successfully Added');
            } catch (\Illuminate\Database\QueryException $ex) {
                return redirect()->back()->withErrors($ex->getMessage());
            }
        }
    }

    /**
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $post = $this->post->getById($id);
            $this->post->delete($id);
            Cache::forget('common-showrooms-'.$post->district);
            Cache::forget('common-showrooms-');
            return redirect('posts')->with('success', 'Successfully deleted');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('posts')->withErrors($ex->getMessage());
        }
    }

    public function get_post_categories(array $options = [])
    {
        $default = [
            'type' => 'category',
            'limit' => 10,
            'offset' => 0
        ];

        $optionss = array_merge($default, $options);

        return $this->term->get_terms_by_options($optionss);
    }


    /** Exports Imports */

    public function export_showrooms(Request $request)
    {

        return Excel::download(new ShowroomsExport, 'showrooms.xlsx');
    }

    public function import_showrooms_view()
    {

        return view('post.import_showrooms_form');
    }

    public function import_showrooms(Request $request)
    {
        if ($request->hasFile('import_file')) {
            $path = $request->file('import_file');
            $data = Excel::import(new ShowroomsImport, $path);
        }
        Cache::forget('common-showrooms-'.$request->district);
        Cache::forget('common-showrooms-');

        return redirect('our_showroom')->with('success', 'Successfully imported and updated');
    }



}
