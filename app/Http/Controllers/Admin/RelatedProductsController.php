<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\RelatedProducts\RelatedProductsInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RelatedProductsController extends Controller
{

    /**
     * @var RelatedProductsInterface
     */
    private $related_products;

    /**
     * RelatedProductsController constructor.
     * @param RelatedProductsInterface $related_products
     */
    public function __construct(RelatedProductsInterface $related_products)
    {

        $this->related_products = $related_products;
    }

    /**
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {

        //dd($id);
        try {
            $this->related_products->delete($id);
            return redirect()->back()->with('success', 'Successfully deleted');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('products')->withErrors($ex->getMessage());
        }
    }

}
