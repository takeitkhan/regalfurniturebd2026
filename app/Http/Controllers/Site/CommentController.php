<?php

namespace App\Http\Controllers\Site;

use App\Repositories\Comment\CommentInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class CommentController extends Controller
{
    /**
     * @var CommentInterface
     */
    private $comment;

    /**
     * CommentController constructor.
     * @param CommentInterface $comment
     */
    public function __construct(CommentInterface $comment)
    {
        $this->comment = $comment;
    }

    public function comments()
    {

    }

    /**
     * @param $id
     * @return $this
     */
    public function add_comment()
    {
        $comment = $this->comment->getAll()->toArray();
        return view('comment.form')->with(['comment' => $comment]);
    }

    /**
     * @param $id
     * @return $this
     */
    public function edit_comment($id)
    {
        if (isset($id)) {
            $comment = $this->comment->getById($id);
            $terms = $this->term->getAll()->toArray();

            $default = array(
                'type' => 'category',
                'limit' => 250,
                'offset' => 0
            );
            $cats = $this->get_comment_categories($default);
            $categories = $cats->toArray();
            $medias = $this->media->getAll();

            return view('comment.form')
                ->with(['comment' => $comment, 'medias' => $medias, 'terms' => $terms, 'categories' => $categories]);
        }

    }

    /**
     * @param Request $request
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function comment_update_save(Request $request, $id)
    {


        $d = $this->comment->getById($id);
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
            return redirect('comments')
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
                'categories' => implode(',', $request->get('categories')),
                'images' => $request->get('images'),
                'brand' => $request->get('brand'),
                'tags' => $request->get('tags'),
                'youtube' => $request->get('youtube'),
                'is_auto_comment' => $request->get('is_auto_comment'),
                'short_description' => $request->get('short_description'),
                'phone' => $request->get('phone'),
                'opening_hours' => $request->get('opening_hours'),
                'latitude' => $request->get('latitude'),
                'longitude' => $request->get('longitude'),
                'phone_numbers' => $request->get('phone_numbers'),
                'address' => $request->get('address'),
                'is_sticky' => $request->get('is_sticky'),
                'lang' => $request->get('lang'),
                'is_active' => $request->get('is_active')
            ];

            try {
                $comment = $this->comment->update($d->id, $attributes);
                return redirect('comments')->with('success', 'Successfully save changed');
            } catch (\Illuminate\Database\QueryException $ex) {
                return redirect('comments')->withErrors($ex->getMessage());
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
                'comment_on' => 'required',
                'name' => 'required',
                'item_id' => 'required',
                'email' => 'required',
                'comment' => 'required'
            ]
        );

        // process the login
        if ($validator->fails()) {
            return redirect('comments')
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            $attributes = [
                'comment_on' => $request->get('comment_on'),
                'item_id' => $request->get('item_id'),
                'commenter' => $request->get('name'),
                'commenter_email' => $request->get('email'),
                'comment' => $request->get('comment'),
                'is_active' => 0
            ];

            try {
                $comment = $this->comment->create($attributes);
                return redirect()->back();
                //return response()->json(['message' => 'Comment has posted. Please wait for approval.']);
            } catch (\Illuminate\Database\QueryException $ex) {
                return response()->json(['message' => $ex->getMessage()]);
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
            $this->comment->delete($id);
            return redirect('comments')->with('success', 'Successfully deleted');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('comments')->withErrors($ex->getMessage());
        }
    }
}
