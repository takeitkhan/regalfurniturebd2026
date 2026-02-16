<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\ProductAttributesData\ProductAttributesDataInterface;
use App\Repositories\ProductCategories\ProductCategoriesInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductCategoriesController extends Controller
{

    /**
     * @var ProductCategoriesInterface
     */
    private $product_categories;
    /**
     * @var ProductAttributesDataInterface
     */
    private $product_attributes_data;

    /**
     * ProductCategoriesController constructor.
     * @param ProductCategoriesInterface $product_categories
     * @param ProductAttributesDataInterface $product_attributes_data
     */
    public function __construct(ProductCategoriesInterface $product_categories, ProductAttributesDataInterface $product_attributes_data)
    {

        $this->product_categories = $product_categories;
        $this->product_attributes_data = $product_attributes_data;
    }

    public function destroy($id)
    {

        //dd($id);
        try {
            $this->product_categories->delete($id);
            return redirect()->back()->with('success', 'Successfully deleted');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('products')->withErrors($ex->getMessage());
        }
    }


    // Extra Methods

    public function is_attgroup_active(Request $request)
    {

        $id = $request->get('id');
        $old_id = $request->get('old_id');
        $mainpid = $request->get('old_mainpid');

        $done = $this->product_attributes_data->deleteByAny('main_pid', $mainpid);

        //dd($done);

        $new_attributes = array(
            'is_attgroup_active' => $request->get('value')
        );

        if (!empty($old_id)) {
            $old_attributes = array(
                'is_attgroup_active' => $request->get('old_value')
            );
            $old = $this->product_categories->update($old_id, $old_attributes);
        }
        try {
            $new = $this->product_categories->update($id, $new_attributes);

            return redirect()->back()->with('success', 'Successfully save changed');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('products')->withErrors($ex->getMessage());
        }

    }
}
