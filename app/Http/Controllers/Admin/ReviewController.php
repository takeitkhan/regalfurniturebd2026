<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Review\ReviewInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    /**
     * @var ReviewInterface
     */
    private $review;

    /**
     * ReviewController constructor.
     * @param ReviewInterface $review
     */
    public function __construct(ReviewInterface $review)
    {

        $this->review = $review;
    }

    public function review()
    {
        $reviews = $this->review->getAll();
        return view('review.index')->with(['reviews' => $reviews]);

    }
    public function quick_review_approve($id,$action)
    {

        $attributes = array(
            'is_active' => $action
        );

        $review = $this->review->update($id, $attributes);
        return redirect('review')->with('success', 'Successfully Approved');
    }

}
