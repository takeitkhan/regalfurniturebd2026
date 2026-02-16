<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Returns\ReturnsInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReturnsController extends Controller
{
    /**
     * @var ReturnsInterface
     */
    private $returns;

    /**
     * ReturnsController constructor.
     * @param ReturnsInterface $returns
     */
    public function __construct(ReturnsInterface $returns)
    {

        $this->returns = $returns;
    }

    public function all_returns(Request $request){
        $returns = $this->returns->getAll();
        return view('returns.all_returns')
            ->with(['returns' => $returns]);

    }

    public function quick_returns_approve($id)
    {

        $attributes = array(
            'is_active' => 1
        );

        $returns = $this->returns->update($id, $attributes);
        return redirect('all_returns')->with('success', 'Successfully Approved');
    }

}
