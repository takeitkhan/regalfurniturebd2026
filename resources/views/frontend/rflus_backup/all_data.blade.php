<?php
$categories = $cats['data'];
echo category_open_checkbox_html($categories, $parent = 0, ' ', (!empty($term->parent) ? $term->parent : NULL), FALSE );
?>