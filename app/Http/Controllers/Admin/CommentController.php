<?php

namespace App\Http\Controllers\Admin;

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

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function comments()
    {
        $comments = $this->comment->getAll();
        return view('comment.comments', compact('comments'))->with(['comments' => $comments]);
    }

    /**
     * @param $id
     * @return $this
     */
    public function add_comment()
    {
        $comments = $this->comment->getAll();
        return view('comment.form')->with('comments', $comments);
    }

    /**
     * @param $id
     * @return $this
     */
    public function edit_comment($id)
    {
        if (isset($id)) {
            $comment = $this->comment->getById($id);
            $comments = $this->comment->getAll();
            return view('comment.form')
                ->with(['comment' => $comment, 'comments' => $comments]);
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
                $comment = $this->comment->update($d->id, $attributes);
                return redirect('commentss')->with('success', 'Successfully save changed');
            } catch (\Illuminate\Database\QueryException $ex) {
                return redirect('commentss')->withErrors($ex->getMessage());
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
            return redirect('commentss')
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
                $comment = $this->comment->create($attributes);
                return redirect('add_comment')->with('success', 'Successfully Added');

            } catch (\Illuminate\Database\QueryException $ex) {
                return redirect('commentss')->withErrors($ex->getMessage());
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
            return redirect('commentss')->with('success', 'Successfully deleted');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('commentss')->withErrors($ex->getMessage());
        }
    }


    public function quick_comment_approve($id)
    {

        $attributes = array(
            'is_active' => 1
        );

        $comment = $this->comment->update($id, $attributes);
        return redirect('commentss')->with('success', 'Successfully Approved');
    }


    public function add_reply(Request $request)
    {
        $parent_id = $request->get('comment_id');
        $comment = $this->comment->getById($parent_id);

        $comments = $this->comment->getAll();
        return view('comment.reply_form')->with([
            'comments' => $comments,
            'comment' => $comment,
        ]);
    }


    /**
     * @param Request $request
     * @return $this
     * @internal param Request $request
     */
    public function reply_store(Request $request)
    {
        //dd($request);
        // validate
        // read more on validation at
        $validator = Validator::make($request->all(),
            [
                'user_id' => 'required',
                'item_id' => 'required',
                'comment' => 'required',
                'parent_id' => 'required',
            ]
        );

        // process the login
        if ($validator->fails()) {
            return redirect('add_reply')
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            $attributes = [
                'user_id' => $request->get('user_id'),
                'item_id' => $request->get('item_id'),
                'comment_on' => $request->get('comment_on'),
                'commenter' => $request->get('user_id'),
                'commenter_photo' => null,
                'commenter_email' => null,
                'commenter_phone' => null,
                'comment' => $request->get('comment'),
                'parent_id' => $request->get('parent_id'),
                'is_active' => 1
            ];

            try {
                $comment = $this->comment->create($attributes);
                return redirect('commentss')->with('success', 'Successfully Added');

            } catch (\Illuminate\Database\QueryException $ex) {
                return redirect('commentss')->withErrors($ex->getMessage());
            }
        }

    }


}
