<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Productpricecombination\ProductpricecombinationInterface;
use App\Http\Controllers\Controller;

class ProductpricecombinationController extends Controller
{
    /**
     * @var ProductpricecombinationInterface
     */
    private $productpricecombination;

    /**
     * ProductCategoriesController constructor.
     * @param ProductpricecombinationInterface $productpricecombination
     */
    public function __construct(ProductpricecombinationInterface $productpricecombination)
    {
        $this->productpricecombination = $productpricecombination;
    }

    public function destroy($id)
    {
        //dd($id);
        try {
            $this->productpricecombination->delete($id);
            return redirect()->back()->with('success', 'Successfully deleted');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('products')->withErrors($ex->getMessage());
        }
    }
}
