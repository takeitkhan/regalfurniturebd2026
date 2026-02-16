<?php

namespace App\Console\Commands;

use App\Models\Term;
use Illuminate\Console\Command;

class ProductSortOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sort:products {--category_id=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sort products by category ID';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $categoryId = $this->option('category_id');
        $this->info('Sorting products by category ID: '.$categoryId);

        $terms = Term::query()
            ->when($categoryId, function ($query, $categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->whereHas('products')
            ->where('type', 'category')
            ->with('products')
            ->get();

        foreach ($terms as $term) {
            $products = $term->products;

            foreach ($products as $index => $product) {
                if ($product->is_attgroup_active) {
                    $product->sort_order = $index + 1;
                } else {
                    $product->sort_order = 0;
                }
                $product->save();
                $this->info("Updated product ID: {$product->id} with sort order: {$product->sort_order}");
            }
        }
        return 0;
    }
}
