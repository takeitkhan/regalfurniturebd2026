<?php
// Fourth Cat
if (!empty($homesettig->cat_fourth)) {
    $cat_fourth = $homesettig->cat_fourth;
    $fc = explode('|', $cat_fourth);
    $term_fourth = \App\Term::where('id', $fc[0])->get()->first();
    $products_fourth = \App\ProductCategories::leftJoin('products', function ($join) {
        $join->on('productcategories.main_pid', '=', 'products.id');
    })->where(['productcategories.term_id' => $term_fourth->id])->limit(8)->get();
}

// Fifth Cat
if (!empty($homesettig->cat_fifth)) {
    $cat_fifth = $homesettig->cat_fifth;
    $fic = explode('|', $cat_fifth);
    $term_fifth = \App\Term::where('id', $fic[0])->get()->first();
    $products_fifth = \App\ProductCategories::leftJoin('products', function ($join) {
        $join->on('productcategories.main_pid', '=', 'products.id');
    })->where(['productcategories.term_id' => $term_fifth->id])->limit(8)->get();
}

// Sixth Cat
if (!empty($homesettig->cat_sixth)) {
    $cat_sixth = $homesettig->cat_sixth;
    $tc = explode('|', $cat_sixth);
    $term_sixth = \App\Term::where('id', $tc[0])->get()->first();
    $products_sixth = \App\ProductCategories::leftJoin('products', function ($join) {
        $join->on('productcategories.main_pid', '=', 'products.id');
    })->where(['productcategories.term_id' => $term_sixth->id])->limit(8)->get();
}


// Seventh Cat
if (!empty($homesettig->cat_seventh)) {
    $cat_seventh = $homesettig->cat_seventh;
    $tc = explode('|', $cat_seventh);
    $term_seventh = \App\Term::where('id', $tc[0])->get()->first();
    $products_seventh = \App\ProductCategories::leftJoin('products', function ($join) {
        $join->on('productcategories.main_pid', '=', 'products.id');
    })->where(['productcategories.term_id' => $term_seventh->id])->limit(8)->get();
}

// Eighth Cat
if (!empty($homesettig->cat_eighth)) {
    $cat_eighth = $homesettig->cat_eighth;
    $tc = explode('|', $cat_eighth);
    $term_eighth = \App\Term::where('id', $tc[0])->get()->first();
    $products_eighth = \App\ProductCategories::leftJoin('products', function ($join) {
        $join->on('productcategories.main_pid', '=', 'products.id');
    })->where(['productcategories.term_id' => $term_eighth->id])->limit(8)->get();
}