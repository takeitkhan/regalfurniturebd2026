<?php

namespace App\Http\Controllers\Admin;

use App\Exports\NewslettersExport;
use App\Repositories\Newsletter\NewsletterInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class NewsletterController extends Controller
{
    /**
     * @var NewsletterInterface
     */
    private $newsletter;

    /**
     * NewsletterController constructor.
     * @param NewsletterInterface $newsletter
     */
    public function __construct(NewsletterInterface $newsletter)

    {
        $this->newsletter = $newsletter;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function newsletters()
    {


        $newsletters = $this->newsletter->getAll();

        // dd($newsletters);
        return view('newsletter.index')->with(['newsletters' => $newsletters]);
    }

    /**
     * @param $id
     * @return $this
     */
    public function add_page()
    {
        $medias = $this->newsletter->getAll();
        return view('page.form')->with('medias', $medias);
    }

    /**
     * @param $id
     * @return $this
     */
    public function edit_page($id)
    {
        if (isset($id)) {
            $newsletter = $this->newsletter->getById($id);
            return view('page.form');
        }

    }

    /**
     * @param Request $request
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function newsletter_update_save(Request $request, $id)
    {


        $d = $this->newsletter->getById($id);
        //owndebugger($d); die();
        // validate
        // read more on validation at
        $validator = Validator::make($request->all(), [
                'gender' => 'required',
                'email' => 'required'
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
                'gender' => $request->get('gender'),
                'email' => $request->get('email'),
                'post_code' => $request->get('post_code'),
                'service_type' => $request->get('service_type'),
                'is_active' => true
            ];
            try {
                $newsletter = $this->newsletter->update($d->id, $attributes);
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
                'gender' => 'required',
                'email' => 'required'
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
                'gender' => $request->get('gender'),
                'email' => $request->get('email'),
                'post_code' => $request->get('post_code'),
                'service_type' => $request->get('service_type'),
                'is_active' => true
            ];

            try {
                $page = $this->newsletter->create($attributes);
                return redirect('services')->with('success', 'Successfully Added');

            } catch (\Illuminate\Database\QueryException $ex) {
                return redirect('services')->withErrors($ex->getMessage());
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
            $this->newsletter->delete($id);
            return redirect('pages')->with('success', 'Successfully deleted');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('pages')->withErrors($ex->getMessage());
        }
    }


    public function newsletter_status(Request $request, $id, $action)
    {
        $attributes = [
            'is_active' => $action
        ];

        try {
            $this->newsletter->update($id, $attributes);
            return redirect()->back()->with('sweet_alert', 'Successfully save changed');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect()->back()->withErrors($ex->getMessage());
        }


    }


    public function export_newsletters(Request $request)
    {
        return Excel::download(new NewslettersExport, 'newsletter_data.xlsx');
    }
}
