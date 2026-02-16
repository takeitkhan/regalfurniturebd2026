<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategories;
use App\Models\Term;
use Illuminate\Http\Request;
use function response;

class ProductSortController extends Controller
{
    public function index()
    {
        $categories = Term::query()->isCategory()
            ->withCount('products')
            ->orderBy('products_count', 'desc')
            ->paginate();

        return view('admin.product.sort', compact('categories'));
    }

    public function show(int $id)
    {
        $products = ProductCategories::query()
            ->where('term_id', $id)
            ->where('is_attgroup_active', 1)
            ->with('product', function ($query) {
                $query->select('id', 'title', 'sku', 'product_code');
            })
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('admin.product.serialize', compact('products'));
    }

    public function productSerializeUpdate(Request $request)
    {
        $products = ProductCategories::query()
            ->where('is_attgroup_active', 1)
            ->get();

        foreach ($products as $product) {
            $product->timestamps = false; // To disable update_at field updation
            $id = $product->id;

            foreach ($request->order as $order) {
                if ($order['id'] == $id) {
                    $product->update(['sort_order' => $order['position']]);
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Sorting update successfully'
        ]);
    }
}
