<?php

namespace App\Http\Controllers\Admin;

use App\Attribute;
use DB;
use Validator;
use App\Repositories\Attribute\AttributeInterface;
use App\Repositories\Media\MediaInterface;
use App\Repositories\Page\PageInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    /**
     * @var MediaInterface
     */
    private $media;
    /**
     * @var AttributeInterface
     */
    private $attribute;
    /**
     * @var PageInterface
     */
    private $page;

    /**
     * PageController constructor.
     * @param PageInterface $page
     * @param AttributeInterface $attribute
     * @param MediaInterface $media
     * @internal param PageInterface $page
     */
    public function __construct(PageInterface $page, AttributeInterface $attribute, MediaInterface $media)

    {
        $this->media = $media;
        $this->attribute = $attribute;
        $this->page = $page;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pages()
    {
        $pages = $this->page->getAll();
        return view('page.pages', compact('pages'))->with(['pages' => $pages]);
    }

    /**
     * @param $id
     * @return $this
     */
    public function add_page()
    {
        $medias = $this->media->getAll();
        return view('page.form')->with('medias', $medias);
    }

    /**
     * @param $id
     * @return $this
     */
    public function edit_page($id)
    {
        if (isset($id)) {
            $page = $this->page->getById($id);
            $medias = $this->media->getAll();
            return view('page.form')
                ->with(['page' => $page, 'medias' => $medias]);
        }

    }

    /**
     * @param Request $request
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function page_update_save(Request $request, $id)
    {


        $d = $this->page->getById($id);
        //owndebugger($d); die();
        // validate
        // read more on validation at
        $validator = Validator::make($request->all(),
            [
                'title' => 'required',
                'seo_url' => 'required',
                'description' => 'required',
                'lang' => 'required'
            ]
        );

        // process the login
        if ($validator->fails()) {
            return redirect('pages')
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            $attributes = [
                'user_id' => $request->get('user_id'),
                'title' => $request->get('title'),
                'sub_title' => $request->get('sub_title'),
                'seo_url' => $request->get('seo_url'),
                'description' => $request->get('description'),
                'is_sticky' => $request->get('is_sticky'),
                'lang' => $request->get('lang'),
                'is_active' => $request->get('is_active'),
                'images' => $request->get('images'),
            ];
            try {
                $page = $this->page->update($d->id, $attributes);
                return redirect('pages')->with('success', 'Successfully save changed');
            } catch (\Illuminate\Database\QueryException $ex) {
                return redirect('pages')->withErrors($ex->getMessage());
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
        $validator = Validator::make($request->all(),
            [
                'title' => 'required',
                'seo_url' => 'required',
                'description' => 'required',
                'lang' => 'required'
            ]
        );

        // process the login
        if ($validator->fails()) {
            return redirect('pages')
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            $attributes = [
                'user_id' => $request->get('user_id'),
                'title' => $request->get('title'),
                'sub_title' => $request->get('sub_title'),
                'seo_url' => $request->get('seo_url'),
                'description' => $request->get('description'),
                'is_sticky' => $request->get('is_sticky'),
                'lang' => $request->get('lang'),
                'is_active' => $request->get('is_active'),
                'images' => $request->get('images'),
            ];

            try {
                $page = $this->page->create($attributes);
                return redirect('add_page')->with('success', 'Successfully Added');

            } catch (\Illuminate\Database\QueryException $ex) {
                return redirect('pages')->withErrors($ex->getMessage());
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
            $this->page->delete($id);
            return redirect('pages')->with('success', 'Successfully deleted');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('pages')->withErrors($ex->getMessage());
        }
    }
}
