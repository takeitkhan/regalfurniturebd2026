<?php

namespace App\Repositories\Product;

interface ProductInterface
{
    public function self();

    public function getAll(array $options = array());

    public function getById($id);

    public function getByAny($column, $value);

    public function getByFilter(array $filter = []);

    public function create(array $attributes);

    public function update($id, array $attributes);

    public function delete($id);

    public function getAllExclusive($skip = null, $take = null);

    public function getProductsOnSearch(array $options = array(), $totalrowcount = null);

    public function getProductCategories($id);

    public function getSimilarProduct($id);

    public function getProductImages($id);

    public function getProductAttributesData($id);

    public function get_product_list_by_search_key($key, $limit = null);

    public function getProductByFilter(array $options = array(), $cat);

    public function get_search_product_ajax(array $options = array());

    public function get_search_first_product_cat(array $options = array());

    public function getAllWhere(array $options = array(), array $where = array());

    public function getAllByRole(array $options = array());

    public function getAllWhereByRole(array $options = array(), array $where = array());

}
