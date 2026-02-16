<?php

namespace App\Repositories\Term;

interface TermInterface
{
    public function getAll();

    public function self();

    public function getById($id);

    public function getByAny($column, $value);

    public function getByFilter(array $filter = []);

    public function create(array $attributes);

    public function update($id, array $attributes);

    public function delete($id);

    /**
     * Extra methods
     */

    public function get_terms_by_options(array $options = array());
    public function getWhereIn(array $options = array());
}