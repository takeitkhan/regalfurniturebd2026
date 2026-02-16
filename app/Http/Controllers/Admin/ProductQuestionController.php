<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\ProductQuestion\ProductQuestionInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductQuestionController extends Controller
{
    /**
     * @var ProductQuestionInterface
     */
    private $productQuestion;

    /**
     * ProductQuestionController constructor.
     * @param ProductQuestionInterface $productQuestion
     */
    public function __construct(ProductQuestionInterface $productQuestion)
    {

        $this->productQuestion = $productQuestion;
    }

    public function questions(Request $request)
    {
        $option = [
            'main_pid' => null,
            'titel' => (($request->get('titel')) ? $request->get('titel') : null)
        ];

        $questions = $this->productQuestion->getByFilter($option);
        return view('question.index')->with(['questions' => $questions]);


    }


    public function product_question_ans(Request $request, $id)
    {
        $question = $this->productQuestion->getById($id);
      


        if ($question->count() > 0 && auth()->check()) {
            $question = $question->first();


            $attributes = [
                'main_pid' => $question->main_pid,
                'user_id' => auth()->user()->id,
                'vendor_id' => $question->vendor_id,
                'description' => $request->get('post'),
                'qa_type' => 2,
                'que_id' => $id,
                'is_active' => 1

            ];
            //dd($attributes);


            try {
                $this->productQuestion->create($attributes);
                return redirect()->back()->with('success', 'Successfully save changed');
            } catch (\Illuminate\Database\QueryException $ex) {
                return redirect()->back()->withErrors($ex->getMessage());
            }


        } else {
            return redirect()->back();
        }
    }

    public function questions_isActive($id){

        $dbquestion = $this->productQuestion->getById($id);
      
        if($dbquestion->is_active == 1){
            $this->productQuestion->update($id, ['is_active' => 0]);
        }else{
            $this->productQuestion->update($id, ['is_active' => 1]);
        }
        return redirect()->back();
    }

}
