<?php
if (!function_exists('get_category_name')) {
    function get_category_name($id, $key)
    {
        $category = App\Models\Term::find($id);
        //dd($category);
        return $category[$key];
    }
}