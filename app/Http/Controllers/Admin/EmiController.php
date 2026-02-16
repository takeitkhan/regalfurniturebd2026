<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Emi\EmiInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmiController extends Controller
{
    /**
     * @var PcombinationdataInterface
     */
    private $pcombinationdata;
    /**
     * @var EmiInterface
     */
    private $emi;

    /**
     * ProductCategoriesController constructor.
     * @param EmiInterface $emi
     */
    public function __construct(EmiInterface $emi)
    {
        $this->emi = $emi;
    }

    public function destroy($id)
    {
        //dd($id);
        try {
            $this->emi->delete($id);
            return redirect()->back()->with('success', 'Successfully deleted');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('products')->withErrors($ex->getMessage());
        }
    }
}
