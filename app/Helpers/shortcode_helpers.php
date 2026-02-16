<?php
use App\Models\Pcombinationdata;
use App\Models\Image;
use App\Models\Term;
use App\Models\Review;
use App\Models\OrdersDetail;
use App\Models\FlashShedule;
use App\Models\FlashItem;
use App\Models\PaymentSetting;
use App\Models\Coupon;
use Illuminate\Support\Facades\Session;
use App\Models\Product;
use App\Models\HomeSetting;
/**
 * Main Shortcode Functions Start
 */
if (!function_exists('shortcode')) {
    function shortcode($content)
    {
        preg_match_all("/\[tritiyo:([a-zA-Z0-9-_: |=\-_a-zA-Z0-9-_\/ .]+)]/", $content, $matches);


        if ($matches == NULL) {
            return $content;
        }

        //dd($matches);
        // Tidy up
        foreach ($matches[1] as $key => $shortcode) {
            //dd($shortcode);
            if (strstr($shortcode, ' ')) {
                $code = substr($shortcode, 0, strpos($shortcode, ' '));
                $tmp = explode('|', str_replace($code . ' ', '', $shortcode));
                $params = array();
                if (count($tmp)) {
                    foreach ($tmp as $param) {
                        $pair = explode('=', $param);
                        $params[$pair[0]] = $pair[1];
                    }
                }
                $array = array('code' => $code, 'params' => $params);
            } else {
                $array = array('code' => $shortcode, 'params' => array());
            }

            $shortcode_array[$matches[0][$key]] = $array;

            //dd($shortcode_array);
        }

        if (!empty($shortcode_array)) {
            // Replace shortcode instances with HTML strings
            if (count($shortcode_array)) {

                foreach ($shortcode_array as $search => $shortcode) {
                    $code = $shortcode['code'];
                    $params = $shortcode['params'];

                    //dump($params);
                    //die();
                    if (!empty($code) && !empty($params)) {
                        $ncontent = shortcode_parser($code, $params);
                        //dd($content);
                    }

                }
            }
            return $ncontent;
        }
    }
}


if (!function_exists('shortcode_parser')) {
    function shortcode_parser($code, $params)
    {
        //dd($code);
        if (!empty($code) && !empty($params)) {
            $code($params);
            // [tritiyo:imglink img=url|link=url]
            // imglink($params)
        }

    }

}
/**
 * Main Shortcode Functions End
 */


/**
 * Functions
 */
if (!function_exists('imglink')) {
    function imglink($params)
    {
        $img = $params['img'];
        $link = $params['link'];
        echo '<a href="' . $link . '"><img src="' . $img . '" /></a>';

    }
}

if (!function_exists('menulink')) {
    function menulink($params)
    {
        $link = $params['link'];
        $text = $params['text'];

        echo '<a href="' . $link . '">' . $text . '</a>';

    }
}

if (!function_exists('short_code')) {
    function short_code($content)
    {
        $searchArray = array("[", "]");
        $replaceArray = array("{", "},");
        $replace = str_replace($searchArray, $replaceArray, $content);
        $remove = rtrim($replace, ",");
        $jeson_make = '['.$remove.']';
        $jeson = json_decode($jeson_make,true);
        if(is_array($jeson)){
            return $jeson;
        }else{
            return false;
        }
    }
}




