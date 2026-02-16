<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('metas')) {
    function metas($setting, array $urls = [])
    {
        $options = [
            'url' => null,
            'img_url' => null,
            'title' => null,
            'description' => null,
            'keywords' => null
        ];
        $urls = array_merge($options, $urls);

        if (!empty($setting[0])) {
            $setting = $setting[0];
        } else {
            $setting = \App\Models\Setting::where('id', 1)->get()->first();
        }

//        dd($setting);
        $html = '<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">';
        $html .= '<title>' . (!empty($urls['title']) ? $urls['title'] : $setting->com_name) . '</title>';

        $html .= '<meta name="description" content="' . (!empty($urls['description']) ? $urls['description'] : $setting->com_metadescription) . '">';
        $html .= '<meta name="keywords" content="' . (!empty($urls['keywords']) ? $urls['keywords'] : $setting->com_metakeywords) . '">';
        $html .= '<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">';
        $html .= '<meta name="viewport" content="width=device-width, initial-scale=1">';
        // facebook, social media ogs

        $html .= '<meta name="author" content="Tritiyo Limited">';
        $html .= '<meta name="robots" content="index, follow" />';

        $html .= '<meta property="og:site_name" content="' . (!empty($urls['title']) ? $urls['title'] : $setting->com_name) . '"/>';
        $html .= '<meta property="og:locale" content="bn_BD"/>';
        $html .= '<meta property="og:type" content="article"/>';
        $html .= '<meta property="og:title" content="' . (!empty($urls['title']) ? $urls['title'] : $setting->com_name) . '"/>';
        $html .= '<meta property="og:description" content="' . (!empty($urls['description']) ? $urls['description'] : $setting->com_metadescription) . '"/>';
        $html .= '<meta property="og:url" content="' . (!empty($urls['url']) ? $urls['url'] : $setting->com_website) . '" />';
        $html .= '<meta property="og:image" content="' . (!empty($urls['img_url']) ? $urls['img_url'] : null) . '"/>';
        $html .= '<meta property="fb:pages" content="' . $setting->com_facebookpageid . '"/>';
        $html .= '<link rel="shortcut icon" href="' . $setting->com_favicon . '" />';

        return $html;
    }
}

if (!function_exists('dynamic_widget')) {
    function dynamic_widget($widgets, array $params = [])
    {
        $options = [
            'id' => null,
            'heading' => null
        ];
        $params = array_merge($options, $params);
        foreach ($widgets as $widget) {
            if ($params['id'] == $widget->id) {
                return $widget->description;
            }
        }
    }
}

if (!function_exists('widget_params')) {
    function widget_params($widgets, array $params = [])
    {
        $options = [
            'id' => null,
            'heading' => null
        ];
        $params = array_merge($options, $params);

        foreach ($widgets as $widget) {
            if ($params['id'] == $widget->id) {
                return $widget;
            }
        }
    }
}

if (!function_exists('pagination_custom')) {
    function pagination_custom($item_count, $limit, $cur_page, $link)
    {
        $page_count = ceil($item_count / $limit);
        $current_range = [($cur_page - 2 < 1 ? 1 : $cur_page - 2), ($cur_page + 2 > $page_count ? $page_count : $cur_page + 2)];

        // First and Last pages
        $first_page = $cur_page > 3 ? '<a href="' . sprintf($link, '1') . '">1</a>' . ($cur_page < 5 ? ', ' : ' ... ') : null;
        $last_page = $cur_page < $page_count - 2 ? ($cur_page > $page_count - 4 ? ', ' : ' ... ') . '<a href="' . sprintf($link, $page_count) . '">' . $page_count . '</a>' : null;

        // Previous and next page
        $previous_page = $cur_page > 1 ? '<a href="' . sprintf($link, ($cur_page - 1)) . '">Previous</a> | ' : null;
        $next_page = $cur_page < $page_count ? ' | <a href="' . sprintf($link, ($cur_page + 1)) . '">Next</a>' : null;

        // Display pages that are in range
        for ($x = $current_range[0]; $x <= $current_range[1]; ++$x) {
            $pages[] = '<a href="' . sprintf($link, $x) . '">' . ($x == $cur_page ? '<strong>' . $x . '</strong>' : $x) . '</a>';
        }

        if ($page_count > 1) {
            return '<p class="pagination"><strong>Pages:</strong> ' . $previous_page . $first_page . implode(', ', $pages) . $last_page . $next_page . '</p>';
        }
    }
}

if (!function_exists('pagination')) {
    function pagination($total, $per_page = 10, $page = 1, $url = '?')
    {
        $adjacents = '2';

        $prevlabel = '&lsaquo; Prev';
        $nextlabel = 'Next &rsaquo;';
        $lastlabel = 'Last &rsaquo;&rsaquo;';

        $page = ($page == 0 ? 1 : $page);
        $start = ($page - 1) * $per_page;

        $prev = $page - 1;
        $next = $page + 1;

        $lastpage = ceil($total / $per_page);

        $lpm1 = $lastpage - 1; //last page minus 1

        $pagination = '';
        if ($lastpage > 1) {
            $pagination .= "<ul class='pagination'>";
            $pagination .= "<li class='page_info'>Page {$page} of {$lastpage}</li>";

            if ($page > 1) {
                $pagination .= "<li><a href='{$url}pages={$prev}'>{$prevlabel}</a></li>";
            }

            if ($lastpage < 7 + ($adjacents * 2)) {
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $page) {
                        $pagination .= "<li><a class='current'>{$counter}</a></li>";
                    } else {
                        $pagination .= "<li><a href='{$url}pages={$counter}'>{$counter}</a></li>";
                    }
                }
            } elseif ($lastpage > 5 + ($adjacents * 2)) {
                if ($page < 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                        if ($counter == $page) {
                            $pagination .= "<li><a class='current'>{$counter}</a></li>";
                        } else {
                            $pagination .= "<li><a href='{$url}pages={$counter}'>{$counter}</a></li>";
                        }
                    }
                    $pagination .= "<li class='dot'>...</li>";
                    $pagination .= "<li><a href='{$url}pages={$lpm1}'>{$lpm1}</a></li>";
                    $pagination .= "<li><a href='{$url}pages={$lastpage}'>{$lastpage}</a></li>";
                } elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                    $pagination .= "<li><a href='{$url}pages=1'>1</a></li>";
                    $pagination .= "<li><a href='{$url}pages=2'>2</a></li>";
                    $pagination .= "<li class='dot'>...</li>";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                        if ($counter == $page) {
                            $pagination .= "<li><a class='current'>{$counter}</a></li>";
                        } else {
                            $pagination .= "<li><a href='{$url}pages={$counter}'>{$counter}</a></li>";
                        }
                    }
                    $pagination .= "<li class='dot'>..</li>";
                    $pagination .= "<li><a href='{$url}pages={$lpm1}'>{$lpm1}</a></li>";
                    $pagination .= "<li><a href='{$url}pages={$lastpage}'>{$lastpage}</a></li>";
                } else {
                    $pagination .= "<li><a href='{$url}pages=1'>1</a></li>";
                    $pagination .= "<li><a href='{$url}pages=2'>2</a></li>";
                    $pagination .= "<li class='dot'>..</li>";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                        if ($counter == $page) {
                            $pagination .= "<li><a class='current'>{$counter}</a></li>";
                        } else {
                            $pagination .= "<li><a href='{$url}pages={$counter}'>{$counter}</a></li>";
                        }
                    }
                }
            }

            if ($page < $counter - 1) {
                $pagination .= "<li><a href='{$url}pages={$next}'>{$nextlabel}</a></li>";
                $pagination .= "<li><a href='{$url}pages=$lastpage'>{$lastlabel}</a></li>";
            }

            $pagination .= '</ul>';
        }

        return $pagination;
    }

    if (!function_exists('get_parent_menus')) {
        function get_parent_menus($menu_id)
        {
            $menus = DB::table('menu_items')->where('depth', 0)->where('menu', $menu_id)->orderBy('sort', 'ASC')->get();
            return $menus;
        }
    }

    if (!function_exists('get_sub_menus')) {
        function get_sub_menus($parent_id)
        {
            $menus = DB::table('menu_items')->where('parent', $parent_id)->where('depth', '!=', 0)->get();
            return $menus;
        }
    }

    if (!function_exists('get_sub_menu_list')) {
        function get_sub_menu_list($parent_id)
        {
            $menus = DB::table('menu_items')->where('parent', $parent_id)->where('depth', '!=', 0)->orderBy('sort', 'asc')->get();
            if($menus->count() > 0) {
                return $menus;
            }else{
                return false;
            }

        }
    }




    //SELECT * FROM `menu_items` WHERE depth = 0 ORDER BY sort ASC
//SELECT * FROM `menu_items` WHERE parent = 10 ORDER BY sort ASC
}
