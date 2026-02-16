<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Pcombinationdata\PcombinationdataInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PcombinationdataController extends Controller
{
    /**
     * @var PcombinationdataInterface
     */
    private $pcombinationdata;

    /**
     * ProductCategoriesController constructor.
     * @param PcombinationdataInterface $pcombinationdata
     */
    public function __construct(PcombinationdataInterface $pcombinationdata)
    {
        $this->pcombinationdata = $pcombinationdata;
    }

    public function destroy($id)
    {
        //dd($id);
        try {
            $this->pcombinationdata->delete($id);
            return redirect()->back()->with('success', 'Successfully deleted');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('products')->withErrors($ex->getMessage());
        }
    }
}
