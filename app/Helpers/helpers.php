<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

function owndebugger($array)
{
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}

if (!function_exists('clean')) {
    /**
     * @param $string
     * @return string|string[]|null
     */
    function clean($string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }
}

if (!function_exists('category_sidebar_menu')) {
    /**
     * @param $category See $cats variable by printing on banks.blade.php
     * @param int $parent parent id
     * @param string $seperator Space
     * @param $cid Current option id
     * @return string Full html options
     */
    function category_sidebar_menu($category, $parent = 0, $seperator = ' ', $cid = null)
    {
        $html = null;
        if ($parent === null) {
            $current_lvl_keys = array_keys(array_column($category, 'parent'), $parent, true);
        } else {
            $current_lvl_keys = array_keys(array_column($category, 'parent'), $parent);
        }

        //dd($current_lvl_keys);
        if (!empty($current_lvl_keys)) {
            $html .= '<ul>';
            foreach ($current_lvl_keys as $key) {
                $html .= "<li><a href='#'>" . $category[$key]['name'] . '</a></li>';
                $html .= category_sidebar_menu($category, $category[$key]['id'], $seperator . '', $cid);
            }
            $html .= '</ul>';
        }
        //dump($html);
        return $html;
    }
}

if (!function_exists('category_sidebar_menu_on_category_page')) {
    /**
     * @param $category See $cats variable by printing on banks.blade.php
     * @param int $parent parent id
     * @param string $seperator Space
     * @param $cid Current option id
     * @return string Full html options
     */
    function category_sidebar_menu_on_category_page($category, $parent = 0, $seperator = ' ', $cid = null)
    {
        $html = null;
        $current_lvl_keys = array_keys(array_column($category, 'parent'), $parent);

        if (!empty($current_lvl_keys)) {
            if ($parent === 100) {
                $html .= '<ul class="nav nav-pills nav-stacked nav-tree" id="myTree" data-toggle="nav-tree" data-nav-tree-expanded="fa fa-minus" data-nav-tree-collapsed="fa fa-plus">';
            } else {
                $html .= '<ul class="nav nav-pills nav-stacked nav-tree sub-side-manu">';
            }
            foreach ($current_lvl_keys as $key) {
                $totalproduct = App\Models\Product::whereRaw('parent_id IS NULL')->whereRaw('FIND_IN_SET(' . $category[$key]['id'] . ', categories)')->get();
                $total = $totalproduct->count();
                $activeness = !empty(Request::segment(2) == $category[$key]['seo_url']) ? 'active' : '';

                $html .= "<li class=' $activeness '>";

                $html .= "<span class='badge pull-right'>" . $total . '</span>';
                $html .= "<a href='" . category_seo_url_by_id($category[$key]['id']) . "'>" . $category[$key]['name'] . '</a>';
                $html .= category_sidebar_menu_on_category_page($category, $category[$key]['id'], $seperator, $cid = null);
                $html .= '</li>';
            }
            $html .= '</ul>';
        }
        return $html;
    }
}

if (!function_exists('category_sidebar_menu_on_home_page')) {
    /**
     * @param $category See $cats variable by printing on banks.blade.php
     * @param int $parent parent id
     * @param string $seperator Space
     * @param $cid Current option id
     * @return string Full html options
     */
    function category_sidebar_menu_on_home_page($category, $parent = 0, $seperator = ' ', $cid = null)
    {
        $html = null;
        $current_lvl_keys = array_keys(array_column($category, 'parent'), $parent);

        if (!empty($current_lvl_keys)) {
            if ($parent === 100) {
                $html .= '<ul class="" id="">';
            } else {
                $html .= '<ul class="">';
            }
            foreach ($current_lvl_keys as $key) {
                $totalproduct = App\Models\Product::whereRaw('parent_id IS NULL')->whereRaw('FIND_IN_SET(' . $category[$key]['id'] . ', categories)')->get();
                $total = $totalproduct->count();
                $activeness = !empty(Request::segment(2) == $category[$key]['seo_url']) ? 'active' : '';

                $html .= "<li class=' $activeness '>";
                $html .= "<a href='" . category_seo_url_by_id($category[$key]['id']) . "'>" . $category[$key]['name'];
                if ($category[$key]['parent'] == 100) {
                    $html .= "<span class='pull-right circle'><i class='fa fa-angle-right'></i></span>";
                }
                $html .= '</a>';
                $html .= category_sidebar_menu_on_home_page($category, $category[$key]['id'], $seperator, $cid = null);
                $html .= '</li>';
            }
            $html .= '</ul>';
        }
        return $html;
    }
}

if (!function_exists('category_sticky_menu_on_home_page')) {
    /**
     * @param $category See $cats variable by printing on banks.blade.php
     * @param int $parent parent id
     * @param string $seperator Space
     * @param $cid Current option id
     * @return string Full html options
     */
    function category_sticky_menu_on_home_page($category, $parent = 0, $seperator = ' ', $cid = null)
    {
        $html = null;
        $current_lvl_keys = array_keys(array_column($category, 'parent'), $parent);

        if (!empty($current_lvl_keys)) {
            if ($parent === 100) {
                $html .= '<ul class="" id="">';
            } else {
                $html .= '<ul class="">';
            }
            foreach ($current_lvl_keys as $key) {
                $totalproduct = App\Models\Product::whereRaw('parent_id IS NULL')->whereRaw('FIND_IN_SET(' . $category[$key]['id'] . ', categories)')->get();
                $total = $totalproduct->count();
                $activeness = !empty(Request::segment(2) == $category[$key]['seo_url']) ? 'active' : '';

                $html .= "<li class=' $activeness '>";
                $html .= "<a href='" . category_seo_url_by_id($category[$key]['id']) . "'>" . $category[$key]['name'];
                if ($category[$key]['parent'] == 100) {
                    $html .= "<span class='pull-right circle'><i class='fa fa-angle-down'></i></span>";
                }
                $html .= '</a>';
                $html .= category_sticky_menu_on_home_page($category, $category[$key]['id'], $seperator, $cid = null);
                $html .= '</li>';
            }
            $html .= '</ul>';
        }
        return $html;
    }
}

if (!function_exists('select_option_html')) {
    /**
     * @param $category See $cats variable by printing on banks.blade.php
     * @param int $parent parent id
     * @param string $seperator Space
     * @param $cid Current option id
     * @return string Full html options
     */
    function select_option_html($category, $parent = 0, $seperator = ' ', $cid = null, $li = false, $others = false)
    {
        $html = '';
        if ($parent === null) {
            $current_lvl_keys = array_keys(array_column($category, 'parent'), $parent, true);
        } else {
            $current_lvl_keys = array_keys(array_column($category, 'parent'), $parent);
        }

        //dd($current_lvl_keys);
        if (!empty($current_lvl_keys)) {
            if ($li == true) {
                $html .= '<ul class="on_terms">';
            }
            foreach ($current_lvl_keys as $key) {
                $is_selected = ($cid == $category[$key]['id']) ? 'selected="selected"' : '';
                if ($li == true) {
                    $editbtn = '<a type="button" href="' . url('/edit_term/' . $category[$key]['id']) . '" class="btn btn-box-tool"><i class="fa fa-pencil-square-o"></i></a>';
                    if ($category[$key]['id'] != 1 && $category[$key]['id'] != 2) {
                        $delbtn = '<span class="pull-right"><a class="btn btn-xs btn-danger delete_form"
                       href="' . url('delete_term/' . $category[$key]['id']) . '"
                       onclick="return confirm(\'You are attempting to remove this category forever. Are you Sure?\')"
                       title="Delete Now">
                        <i class="fa fa-times"></i>
                    </a></span>';
                    } else {
                        $delbtn = '';
                    }
//                    $delbtn = '<span class="pull-right">
//                        ' . Form::open(['method' => 'delete', 'route' => ['delete_term', $category[$key]['id']], 'class' => 'delete_form']) . '
//                        ' . Form::button('<i class="fa fa-times"></i>', array('type' => 'submit', 'class' => 'btn btn-xs btn-danger')) . '
//                        ' . Form::close() . '</span>';
                    $html .= '<li>' . $delbtn . $seperator . '(' . $category[$key]['id'] . ') ' . $category[$key]['name'] . $editbtn . '</li>';
                    $html .= select_option_html($category, $category[$key]['id'], $seperator . '', $cid, true);
                } else {
                    $html .= '<option ' . $is_selected . " value='" . $category[$key]['id'] . "'>" . $seperator . $category[$key]['name'] . '</option>';
                    $html .= select_option_html($category, $category[$key]['id'], $seperator . '-&nbsp;', $cid, false);
                }
            }
            if ($li == true) {
                $html .= '</ul>';
            }
        }

        return $html;
    }
}

if (!function_exists('select_option_html_on_front')) {
    /**
     * @param $category See $cats variable by printing on banks.blade.php
     * @param int $parent parent id
     * @param string $seperator Space
     * @param $cid Current option id
     * @return string Full html options
     */
    function select_option_html_on_front($category, $parent = 0, $seperator = ' ', $cid = null, $li = false, $others = false)
    {
        $html = '';
        if ($parent === null) {
            $current_lvl_keys = array_keys(array_column($category, 'parent'), $parent, true);
        } else {
            $current_lvl_keys = array_keys(array_column($category, 'parent'), $parent);
        }

        //dd($current_lvl_keys);
        if (!empty($current_lvl_keys)) {
            if ($li == true) {
                $html .= '<ul class="on_terms">';
            }
            foreach ($current_lvl_keys as $key) {
                $is_selected = ($cid == $category[$key]['id']) ? 'selected="selected"' : '';
                if ($li == true) {
                    $editbtn = '<a type="button" href="' . url('/edit_term/' . $category[$key]['id']) . '" class="btn btn-box-tool"><i class="fa fa-pencil-square-o"></i></a>';
                    if ($category[$key]['id'] != 1 && $category[$key]['id'] != 2) {
                        $delbtn = '<span class="pull-right"><a class="btn btn-xs btn-danger delete_form"
                       href="' . url('delete_term/' . $category[$key]['id']) . '"
                       onclick="return confirm(\'You are attempting to remove this category forever. Are you Sure?\')"
                       title="Delete Now">
                        <i class="fa fa-times"></i>
                    </a></span>';
                    } else {
                        $delbtn = '';
                    }
//                    $delbtn = '<span class="pull-right">
//                        ' . Form::open(['method' => 'delete', 'route' => ['delete_term', $category[$key]['id']], 'class' => 'delete_form']) . '
//                        ' . Form::button('<i class="fa fa-times"></i>', array('type' => 'submit', 'class' => 'btn btn-xs btn-danger')) . '
//                        ' . Form::close() . '</span>';
                    $html .= '<li>' . $delbtn . $seperator . '(' . $category[$key]['id'] . ') ' . $category[$key]['name'] . $editbtn . '</li>';
                    $html .= select_option_html($category, $category[$key]['id'], $seperator . '', $cid, true);
                } else {
                    $html .= '<option ' . $is_selected . " value='" . $category[$key]['id'] . "'>" . $seperator . $category[$key]['name'] . '</option>';
                    $html .= select_option_html($category, $category[$key]['id'], $seperator . '-&nbsp;', $cid, false);
                }
            }
            if ($li == true) {
                $html .= '</ul>';
            }
        }

        return $html;
    }
}

if (!function_exists('category_in_product_links_page')) {
    function category_in_product_links_page($parent_id, $product_id, $user_id)
    {
        $sub_terms = App\Models\Term::where('parent', $parent_id)->orderBy('name', 'asc')->get();
        dump($sub_terms);
        foreach ($sub_terms as $sub_term):
            $html = '<option id="dblclick_cat" value="' . $sub_term->id . '" data-mainpid="' . $product_id . '" data-userid="' . $user_id . '" data-title="' . $sub_term->name . '" data-attgroup="' . $sub_term->connected_with . '">&nbsp;&nbsp;&nbsp;' . $sub_term->name . '</option>';
        endforeach;

        return $html;
    }
}

if (!function_exists('category_h_checkbox_html')) {
    /*
     * @param mixed $categories
     * @param int   $parent_id = 0
     * @param string  $name = 'name' Name of checkbox <example><input type="checkbox" value="name[]"> </examble>
     */

    function category_h_checkbox_html($category, $parent = 2, $name = 'name', $selected_category_ids = [])
    {
        $current_lvl_keys = array_keys(array_column($category, 'parent'), $parent);
        //dump($current_lvl_keys);
        if (!empty($current_lvl_keys)) :
            ?>

            <ul id="<?php echo $name ?>-id-<?php echo $parent; ?>" style="list-style: none;">
                <?php foreach ($current_lvl_keys as $key) :
                    ?>
                    <li>
                        <input type="checkbox" id="<?php echo $category[$key]['id']; ?>" name="<?php echo $name; ?>[]"
                               value="<?php echo $category[$key]['id']; ?>" <?php echo(in_array($category[$key]['id'], $selected_category_ids) ? ' checked="checked" ' : ''); ?>>
                        <label for="<?php echo $category[$key]['id']; ?>"><?php echo $category[$key]['name']; ?></label>
                    </li>
                    <?php echo category_h_checkbox_html($category, $category[$key]['id'], $name, $selected_category_ids); ?>
                <?php endforeach; ?>
            </ul>
        <?php
        endif;
    }
}

if (!function_exists('category_open_checkbox_html')) {
    /*
     * @param mixed $categories
     * @param int   $parent_id = 0
     * @param string  $name = 'name' Name of checkbox <example><input type="checkbox" value="name[]"> </examble>
     */

    function category_open_checkbox_html($category, $parent = 100, $name = 'name', $selected_category_ids = [])
    {
        $current_lvl_keys = array_keys(array_column($category, 'parent'), $parent);

        //dd($current_lvl_keys);
        if (!empty($current_lvl_keys)) :
            ?>

            <ul id="<?php echo $name ?>-id-<?php echo $parent; ?>" style="list-style: none;">
                <?php foreach ($current_lvl_keys as $key) :
                    ?>
                    <li>
                        <a href="<?php echo url('c/' . $category[$key]['seo_url']); ?>">
                            <?php echo $category[$key]['name']; ?>
                        </a>
                    </li>
                    <?php echo category_open_checkbox_html($category, $category[$key]['id'], $name, $selected_category_ids); ?>
                <?php endforeach; ?>
            </ul>
        <?php
        endif;
    }
}

if (!function_exists('limit_text')) {
    /**
     * @param $text Content Parameter
     * @param $limit Limit in Words
     * @return string NewContent Returns
     */
    function limit_text($text, $limit)
    {
        if (str_word_count($text, 0) > $limit) {
            $words = str_word_count($text, 2);
            $pos = array_keys($words);
            $text = substr($text, 0, $pos[$limit]) . '...';
        }
        return strip_tags($text);
    }
}

if (!function_exists('limit_character')) {
    /**
     * @param $text Content Parameter
     * @param $limit Limit in Words
     * @return string NewContent Returns
     */
    function limit_character($text, $limit)
    {
//        if (str_word_count($text, 0) > $limit) {
//            $words = str_word_count($text, 2);
//            $pos = array_keys($words);
//            $text = substr($text, 0, $pos[$limit]) . '...';
//        }
//        return strip_tags($text);

        $string = strip_tags($text);
        if (strlen($string) > $limit) {
            $stringCut = substr($string, 0, $limit);
            $endPoint = strrpos($stringCut, ' ');

            //if the string doesn't contain any space then it will cut without word basis.
            $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
            $string .= '...';
        }
        return $string;
    }
}

if (!function_exists('image_ids')) {
    /**
     * @param $post
     * @return string
     */
    function image_ids($post, $p = false, $onlyimage = false)
    {
        if ($p == true) {
            if (!empty($post->product_attributes)) {
                $ids = [];
                foreach ($post->product_attributes as $attribute) {
                    if (!empty($attribute) && $attribute->attribute === 'image') {
                        if ($attribute['module_type'] === 'products' && $onlyimage == true) {
                            $ids[] = !empty($attribute['values']) ? $attribute['values'] : null;
                        }
                    }
                }
                return implode(', ', $ids);
            } else {
                return 0;
            }
        } else {
            if (!empty($post->attributes)) {
                $ids = [];
                foreach ($post->attributes as $attribute) {
                    if (!empty($attribute)) {
                        if ($attribute['module_type'] === 'posts') {
                            $ids[] = !empty($attribute['values']) ? $attribute['values'] : null;
                        }
                    }
                }
                return implode(', ', $ids);
            } else {
                return 0;
            }
        }
    }
}

if (!function_exists('category_ids')) {
    /**
     * @param $post
     * @return string
     */
    function category_ids($post)
    {
        if (!empty($post->product_attributes)) {
            $ids = [];
            foreach ($post->product_attributes as $attribute) {
                if (!empty($attribute) && $attribute->attribute === 'category_id') {
                    if ($attribute['module_type'] === 'products') {
                        $ids[] = !empty($attribute['values']) ? $attribute['values'] : null;
                    }
                }
            }
            return $ids;
        } else {
            return 0;
        }
    }
}

if (!function_exists('product_attributes')) {
    function product_attributes($post, $p = false)
    {
        if ($p == true) {
            if (!empty($post->product_attributes)) {
                $html['photos'] = null;
                $html['id'] = null;

                $html['photos'] = '<div class="timeline-item" style="border: 1px solid #EEE; padding: 0 5px;"><h4 class="timeline-header">Uploaded photos</h4><div class="timeline-body">';

                foreach ($post->product_attributes as $image) {
                    if ($image->module_type === 'products' && $image->attribute !== 'product_information' && $image->attribute === 'image') {
                        $img = App\Models\Image::find($image->values);
                        //dump($img);
                        $html['photos'] .= '<img src="' . url($img->icon_size_directory) . '" alt="' . $img->original_name . '" class="margin" style="max-width: 80px; max-height: 80px; border: 1px dotted #ddd;">';
                        //$html .= '<span>' . $img->id . '</span>';
                        $html['photos'] .= '<a href="' . url('delete_attribute', ['id' => $image->id]) . '">x</a>';
                    }
                    $html['id'] .= $image->id;
                }
                $html['photos'] .= '</div></div>';
                return $html;
            }
        } else {
            if (!empty($post->product_attributes)) {
                $html['values'] = null;
                $html['id'] = null;

                foreach ($post->product_attributes as $variation) {
                    if ($variation->module_type === 'products' && $variation->attribute === 'product_information') {
                        $html['values'] = $variation->values;
                        $html['id'] = $variation->id;
                    }
                }

                return $html;
            }
        }
    }
}

if (!function_exists('images_by_ids')) {
    function images_by_ids($values, array $options = [])
    {
        $default = [
            'link' => false,
            'size' => 'icons'
        ];
        $options = array_merge($default, $options);

        if (!empty($values)) {
            if (strpos($values, ',') !== false) {
                $image_ids = explode(',', $values);

                foreach ($image_ids as $img_id) {
                    $img = App\Models\Image::find($img_id);

                    if ($options['link'] == false) {
                        $del_link = '<a href="' . url('delete_attribute', ['id' => $img->id]) . '">x</a>';
                    } else {
                        $del_link = false;
                    }

                    if ($options['size'] === 'full') {
                        $imgdir = url($img->full_size_directory);
                    } elseif ($options['size'] === 'icons') {
                        $imgdir = url($img->icon_size_directory);
                    } else {
                        $imgdir = url($img->icon_size_directory);
                    }

                    $html[] = '<img src="' . $imgdir . '" alt="' . $img->original_name . '" class="margin" style="max-width: 80px; max-height: 80px; border: 1px dotted #ddd;">';
                    //$html .= '<span>' . $img->id . '</span>';
                    $html[] .= $del_link;
                }
                return $html;
            } else {
                $img = App\Models\Image::find($values);

                if ($options['link'] == false) {
                    $del_link = '<a href="' . url('delete_attribute', ['id' => $img->id]) . '">x</a>';
                } else {
                    $del_link = false;
                }

                if ($options['size'] === 'full') {
                    $imgdir = url($img->full_size_directory);
                } elseif ($options['size'] === 'icons') {
                    $imgdir = url($img->icon_size_directory);
                } else {
                    $imgdir = url($img->icon_size_directory);
                }

                $html = '<img src="' . $imgdir . '" alt="' . $img->original_name . '" class="margin" style="max-width: 80px; max-height: 80px; border: 1px dotted #ddd;">';
                //$html .= '<span>' . $img->id . '</span>';
                $html .= $del_link;

                return $html;
            }
        }
    }
}

if (!function_exists('uploaded_photos')) {
    function uploaded_photos($post, $p = false)
    {
        if ($p == true) {
            if (!empty($post->product_attributes)) {
                $html = '<div class="timeline-item" style="border: 1px solid #EEE; padding: 0 5px;"><h4 class="timeline-header">Uploaded photos</h4><div class="timeline-body">';

                foreach ($post->product_attributes as $image) {
                    if ($image->module_type === 'products' && $image->attribute !== 'product_information') {
                        $img = App\Models\Image::find($image->values);
                        $html .= '<img src="' . url($img->icon_size_directory) . '" alt="' . $img->original_name . '" class="margin" style="max-width: 80px; max-height: 80px; border: 1px dotted #ddd;">';
                        //$html .= '<span>' . $img->id . '</span>';
                        $html .= '<a href="' . url('delete_attribute', ['id' => $image->id]) . '">x</a>';
                    }
                }
                $html .= '</div></div>';

                return $html;
            }
        } else {
            if (!empty($post->attributes)) {
                $html = '<div class="timeline-item" style="border: 1px solid #EEE; padding: 0 5px;"><h4 class="timeline-header">Uploaded photos</h4><div class="timeline-body">';
                foreach ($post->attributes as $image) {
                    if ($image->module_type === 'posts' && $image->attribute !== 'product_information') {
                        $img = App\Models\Image::find($image->values);
                        $html .= '<img src="' . url($img->icon_size_directory) . '" alt="' . $img->original_name . '" class="margin" style="max-width: 80px; max-height: 80px; border: 1px dotted #ddd;">';
                        //$html .= '<span>' . $img->id . '</span>';
                        $html .= '<a href="' . url('delete_attribute', ['id' => $image->id]) . '">x</a>';
                    } elseif ($image->module_type === 'pages' && $image->attribute !== 'product_information') {
                        $img = App\Models\Image::find($image->values);
                        $html .= '<img src="' . url($img->icon_size_directory) . '" alt="' . $img->original_name . '" class="margin" style="max-width: 80px; max-height: 80px; border: 1px dotted #ddd;">';
                        //$html .= '<span>' . $img->id . '</span>';
                        $html .= '<a href="' . url('delete_attribute', ['id' => $image->id]) . '">x</a>';
                    }
                }
                $html .= '</div></div>';

                return $html;
            }
        }
    }
}
if (!function_exists('get_product_categories')) {
    function get_product_categories()
    {
        $default = [
            'type' => 'category',
            'limit' => 500,
            'offset' => 0
        ];
        $cats = App\Models\Term::where('type', ($default['type'] === 'category') ? 'category' : 'others')->take(!empty($default['limit']) ? $default['limit'] : 20)->get();
        $categories = $cats->toArray();

        return $categories;
    }
}

/**
 *
 */
if (!function_exists('get_userrole_by_user_id')) {
    function get_userrole_by_user_id($userid)
    {
        $role_id = \App\Models\Role_user::where('user_id', $userid)->get()->first();
        return $role_id;
    }
}
/**
 *
 */
if (!function_exists('message_handler')) {
    function message_handler()
    {
        $html = null;
    }
}

if (!function_exists('sendSMS')) {
    function sendSMS($receiver, $message)
    {
        //https://api.mobireach.com.bd/SendTextMessage?Username=xxxxxxxx&Password=xxxxxxxxx&From=xxxxxxxxxxxxx&To=xxxxxxxxxxxxx&Message=testmessage

        /** @var
         * ROBI
         * $smscontent
         */

        $rand = random_code();
        $smscontent = urlencode($message);

//        $url = 'https://api.mobireach.com.bd/SendTextMessage?Username=rfl_bestbuy&Password=Abcd@1234&From=RFL%20BestBuy&To=88' . $receiver . '&Message=' . $smscontent;
//
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
//
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
//        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
//        $response = curl_exec($ch);
//        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//        curl_close($ch);

        /** ROBI */

        /** @var
         * SSL
         * $user
         */

        $user = 'regalapi';
        $pass = 'SSl@1234';
        $sid = 'Regal';
        
        /*$params = [
            'user' => $user,
            'pass' => $pass,
            'sid'  => $sid,
            'sms'  => $smscontent,
            'msisdn' => '88'.$receiver,
            'csmsid' => $rand
            ];
        
    $ch = curl_init('http://sms.sslwireless.com/pushapi/dynamic/server.php');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;*/
        
        
        //Old
        /*
        $curl = curl_init();

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'http://sms.sslwireless.com/pushapi/dynamic/server.php?user=' . $user . '&pass=' . $pass . '&sid=' . $sid . '&sms=' . $smscontent . '&msisdn=88' . $receiver . '&csmsid=' . $rand,
                CURLOPT_USERAGENT => 'Sample cURL Request'
            )
        );
        $resp = curl_exec($curl);
        curl_close($curl);
        return $resp;
        //echo $resp;
*/
        /** SSL */
        
               //New
        $fields = http_build_query([

            'userid' => 'bbmlf_api',

            'password' =>md5('roD8c8C'),

            'msisdn' =>'88'.$receiver,

            'masking' => '28585',

            'message' =>urldecode($smscontent),

            'unicode'=>false,

        ]);
//        dd($fields);

        $url = 'http://sms.prangroup.com/postman/api/sendsms?'.$fields;

        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL,$url);

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_FAILONERROR, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        curl_close($ch);
        //New

        //dd($url);


        return $response;
        
    }
}

if (!function_exists('adminSMSConfig')) {
    function adminSMSConfig($paymentsetting)
    {
        $admin = [];

        if (!empty($paymentsetting->admin_cell_one)) {
            $admin[] = $paymentsetting->admin_cell_one;
        }

        if (!empty($paymentsetting->admin_cell_two)) {
            $admin[] = $paymentsetting->admin_cell_two;
        }

        if (!empty($paymentsetting->admin_cell_three)) {
            $admin[] = $paymentsetting->admin_cell_three;
        }

        if (!empty($paymentsetting->admin_cell_four)) {
            $admin[] = $paymentsetting->admin_cell_four;
        }

        if (!empty($paymentsetting->admin_cell_five)) {
            $admin[] = $paymentsetting->admin_cell_five;
        }

        return $admin;
    }
}
if (!function_exists('get_delivery_fee')) {
    function get_delivery_fee($type = null)
    {
        $data = App\Models\PaymentSetting::find(1)->first();
        if ($type == true) {
            return $data->inside_dhaka_fee;
        } else {
            return $data->outside_dhaka_fee;
        }
    }
}

if (!function_exists('convert_youtube')) {
    function convert_youtube($string)
    {
        return preg_replace(
            "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
            '<iframe src="//www.youtube.com/embed/$2" allowfullscreen></iframe>',
            $string
        );
    }
}

if (!function_exists('get_thana')) {
    function get_thana()
    {
        $thanas = DB::table('districts')->distinct()->select('thana')->groupBy('thana')->get();
        return $thanas;
    }
}

if (!function_exists('get_dis_or_div_by_thana')) {
    function get_dis_or_div_by_thana($thana)
    {
        $thana = DB::table('districts')->distinct()->select('*')->where('thana', $thana)->get()->first();
        return $thana;
    }
}
if (!function_exists('get_districts')) {
    function get_districts()
    {
        $districts = DB::table('districts')->distinct()->groupBy('district')->select('*')->where('is_active', 1)->get();
        return $districts;
    }
}

if (!function_exists('get_thanas_by_district')) {
    function get_thanas_by_district($district)
    {
        $thanas = DB::table('districts')->distinct()->groupBy('thana')->select('*')->where('district', $district)->get();
        return $thanas;
    }
}

if (!function_exists('arr_delete')) {
    function arr_delete($main, $del)
    {
        foreach ($del as $key => $value) {
            unset($main[$key]);
        }
        return $main;
    }
}

if (!function_exists('get_dynamic_category')) {
    function get_dynamic_category($cat_id, $cols)
    {
        $thml = '';
        $diy_cat_parent = App\Models\Term::Where(['parent' => 1])->orderBy('position', 'ASC')->get();
        //dd($diy_cat_parent);

        $diy_cat_sub = App\Models\Term::Where(['parent' => $cat_id])->orderBy('position', 'ASC')->get();
        //dump($diy_cat_sub->count());

        if ($diy_cat_sub->count() != 0) {
            //dump($diy_cat_parent);
            if (!empty($cols)) {
                $cc = 'style="column-count: ' . $cols . '"';
            } else {
                $cc = 'style="column-count: 3;"';
            }
            $thml .= '<ul class="cd_sub_cat" ' . $cc . '>';
            foreach ($diy_cat_sub as $dc_sub) {
                $thml .= '<li><a href="' . url('/c/' . $dc_sub->seo_url) . '"> ' . $dc_sub->name . '</a>';

                $diy_cat_chil = App\Models\Term::Where(['parent' => $dc_sub->id])->orderBy('position', 'ASC')->get();
                if ($diy_cat_chil->count() != 0) {
                    $thml .= '<ul class="cd_child_cat">';

                    foreach ($diy_cat_chil as $dc_chil) {
                        $thml .= '<li><a href="' . url('/c/' . $dc_chil->seo_url) . '"> ' . $dc_chil->name . '</a></li>';
                    }

                    $thml .= '</ul>';
                }

                $thml .= '</li>';
            }
            $thml .= '</ul>';
        }

        echo $thml;
    }
}

if (!function_exists('random_code')) {
    function random_code()
    {
        $a = '';
        for ($i = 0; $i < 8; $i++) {
            $a .= mt_rand(0, 9);
        }
        return $a;
    }
}


if (!function_exists('get_user_role')) {
    function get_user_role($id)
    {
        $role = DB::table('role_user as ru')
            ->join('roles as r', 'r.id', '=', 'ru.role_id')
            ->select('*', 'r.id as r_id', 'ru.id as ru_id')->where(['ru.user_id' => $id])->first();
        return $role;
    }
}


if (!function_exists('role_receiver')) {
    function role_employee()
    {
        // 1 = Admin , 2 = manager, 3 = editor, 4 = product_manag, 9 = employee
        $data = [1,9];
        return $data;
    }
}


if (!function_exists('is_employee')) {
    function is_employee()
    {
        $is_login = auth()->user();
        if($is_login){
            $role = get_user_role($is_login->id);
            if(in_array($role->r_id, role_employee())){
                $data = true;
            }else{
                $data = false;
            }

        }else{
            $data = false;
        }
        return $data;
    }
}


if (!function_exists('get_shop_type')) {
    function get_shop_type ()
    {
        $data = [
            'Dealer' => 'Dealer',
            'Regal Showrooms' => 'Regal Showrooms', 
            'Regal with best buy' => 'Regal with best buy',
            'Chatbuy' => 'Chatbuy'
            ]; 
        return $data;
    }
}



if(!function_exists('send_sms_formatting')){
    function send_sms_formatting($phone,$order,$customer,$message){
        
        //$message = $message.$order_id.$customer;
        
        $replace_array  = [
            '#customer_name#' => $customer??'',
            '#order_id#' => $order??''
            ];
            
        $message = strtr(strip_tags($message),$replace_array);
        if(is_array($phone)){


        foreach($phone as $number){
            sendSMS($number,$message);
        }
        
        }else{
            return sendSMS($phone,$message);
        }
        
        
    }
}

