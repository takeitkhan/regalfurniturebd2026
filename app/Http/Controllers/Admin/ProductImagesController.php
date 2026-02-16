<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\ProductImages\ProductImagesInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductImages;
use App\Repositories\Product\ProductInterface;
use Illuminate\Support\Facades\Cache;

class ProductImagesController extends Controller
{
    /**
     * ProductImagesController constructor.
     * @param ProductImagesInterface $product_images
     */
    public function __construct(ProductImagesInterface $product_images, ProductInterface $product)
    {
        $this->product_images = $product_images;
        $this->product = $product;
    }

    public function destroy($id)
    {
        //dd($id);
        try {
            $pimg = $this->product_images->getById($id);
            $this->product_images->delete($id);
            $product = $this->product->getById($pimg->main_pid);
            Cache::forget('product-images-videos-degrees-'.$product->seo_url);
            return redirect()->back()->with('success', 'Successfully deleted');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('products')->withErrors($ex->getMessage());
        }
    }


    // Extra Methods

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function is_main_image(Request $request)
    {

        //dd($request);
        $id = $request->get('id');
        $old_id = $request->get('old_id');

        $new_attributes = array(
            'is_main_image' => $request->get('value')
        );

        if (!empty($old_id)) {
            $old_attributes = array(
                'is_main_image' => $request->get('old_value')
            );
            $old = $this->product_images->update($old_id, $old_attributes);
        }

        try {
            $new = $this->product_images->update($id, $new_attributes);
            $pimg = $this->product_images->getById($id);
            $product = $this->product->getById($pimg->main_pid);
            Cache::forget('product-images-videos-degrees-'.$product->seo_url);


            
            return redirect()->back()->with('success', 'Successfully save changed');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('products')->withErrors($ex->getMessage());
        }

    }

    public function update_image_setup(Request $request)
    {
        $images = ProductImages::where('main_pid', $request->product_id)->where('is_main_image', $request->val)->get();

        foreach($images as $img)
        {
            $img->is_main_image = 0;
            $img->save();
        }

        $updateCurrent = ProductImages::where('id', $request->id)->first();
        if($updateCurrent){

            $updateCurrent->is_main_image = $request->val;
            $updateCurrent->save();
        }

        $product = $this->product->getById($request->product_id);
        Cache::forget('product-images-videos-degrees-'.$product->seo_url);

        return redirect()->back()->with('success', 'Successfully save changed');
    }
}
