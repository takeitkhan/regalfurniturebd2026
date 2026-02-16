<?php


if (!function_exists('gen_field')) {
    function gen_field($fieldtype, array $data = array())
    {
        switch ($fieldtype) :
            case 'text':
                echo get_text($fieldtype, $data);
                break;
            case 'url':
                echo get_text($fieldtype, $data);
                break;
            case 'number':
                echo get_text($fieldtype, $data);
                break;
            case 'textarea':
                echo get_textarea($fieldtype, $data);
                break;
            case 'select':
                echo get_select($fieldtype, $data);
                break;
            case 'checkbox':
                echo get_checkbox($fieldtype, $data);
                break;
            case 'radio':
                echo get_radio($fieldtype, $data);
                break;
            default:
                break;
        endswitch;
    }
}


if (!function_exists('get_text')) {
    function get_text($fieldtype, array $data = array())
    {

        //dump($data);

        $html = '<div class="acf-field acf-field-true-false ' . $fieldtype . '">';

        $html .= '<div class="acf-label">';
        $html .= '<label for="' . $data['field_name'] . '" class="' . $data['field_name'] . '">' . $data['field_label'] . '</label>';
        $html .= '<p><small>' . $data['instructions'] . '</small></p>';
        $html .= '</div>';

        $html .= '<div class="acf-input">';

        if (!empty($data['is_required'])) {
            if ($data['is_required'] == 1) {
                $required = 'required="required"';
            } else {
                $required = null;
            }
        } else {
            $required = null;
        }

        $html .= '<input type="' . $fieldtype . '" value="' . (is_array($data['value']) ? $data['value'][0] : $data['value']) . '" id="' . $data['css_id'] . '" class="' . $data['css_class'] . '" name="attr_save[' . $data['field_id'] . '][value]" ' . $required . '/>';
        $html .= '</div>';


        $html .= '<input type="hidden" value="' . $data['field_id'] . '" name="attr_save[' . $data['field_id'] . '][attribute_id]" />';
        $html .= '<input type="hidden" value="' . $data['attgroup_id'] . '" name="attr_save[' . $data['field_id'] . '][attgroup_id]" />';
        $html .= '<input type="hidden" value="' . $data['field_name'] . '" name="attr_save[' . $data['field_id'] . '][key]" />';
        $html .= '<input type="hidden" value="' . $data['user_id'] . '" name="attr_save[' . $data['field_id'] . '][user_id]" />';
        $html .= '<input type="hidden" value="' . $data['main_pid'] . '" name="attr_save[' . $data['field_id'] . '][main_pid]" />';
        $html .= '<input type="hidden" value="' . $data['default_value'] . '" name="attr_save[' . $data['field_id'] . '][default_value]" />';
        $html .= '<input type="hidden" value="' . (!empty($data['data_save_id']) ? $data['data_save_id'] : null) . '" name="attr_save[' . $data['field_id'] . '][data_save_id]" />';
        $html .= '<input type="hidden" value="' . date('Y-m-d H:i:s') . '" name="attr_save[' . $data['field_id'] . '][created_at]" />';
        $html .= '<input type="hidden" value="' . date('Y-m-d H:i:s') . '" name="attr_save[' . $data['field_id'] . '][updated_at]" />';

        $html .= '</div>';
        return $html;
    }
}

if (!function_exists('get_textarea')) {
    function get_textarea($fieldtype, array $data = array())
    {

        $html = '<div class="acf-field acf-field-true-false ' . $fieldtype . '">';

        $html .= '<div class="acf-label">';
        $html .= '<label for="' . $data['field_name'] . '" class="' . $data['field_name'] . '">' . $data['field_label'] . '</label>';
        $html .= '<p><small>' . $data['instructions'] . '</small></p>';
        $html .= '</div>';

        $html .= '<div class="acf-input">';

        if (!empty($data['is_required'])) {
            if ($data['is_required'] == 1) {
                $required = 'required="required"';
            } else {
                $required = null;
            }
        } else {
            $required = null;
        }

        $html .= '<textarea id="' . $data['css_id'] . '" class="' . $data['css_class'] . '" name="attr_save[' . $data['field_id'] . '][value]" ' . $required . '>' . (is_array($data['value']) ? implode(',', $data['value']) : $data['value']) . '</textarea>';
        $html .= '</div>';


        $html .= '<input type="hidden" value="' . $data['field_id'] . '" name="attr_save[' . $data['field_id'] . '][attribute_id]" />';
        $html .= '<input type="hidden" value="' . $data['attgroup_id'] . '" name="attr_save[' . $data['field_id'] . '][attgroup_id]" />';
        $html .= '<input type="hidden" value="' . $data['field_name'] . '" name="attr_save[' . $data['field_id'] . '][key]" />';
        $html .= '<input type="hidden" value="' . $data['user_id'] . '" name="attr_save[' . $data['field_id'] . '][user_id]" />';
        $html .= '<input type="hidden" value="' . $data['main_pid'] . '" name="attr_save[' . $data['field_id'] . '][main_pid]" />';
        $html .= '<input type="hidden" value="' . $data['default_value'] . '" name="attr_save[' . $data['field_id'] . '][default_value]" />';
        $html .= '<input type="hidden" value="' . (!empty($data['data_save_id']) ? $data['data_save_id'] : null) . '" name="attr_save[' . $data['field_id'] . '][data_save_id]" />';
        $html .= '<input type="hidden" value="' . date('Y-m-d H:i:s') . '" name="attr_save[' . $data['field_id'] . '][created_at]" />';
        $html .= '<input type="hidden" value="' . date('Y-m-d H:i:s') . '" name="attr_save[' . $data['field_id'] . '][updated_at]" />';

        $html .= '</div>';
        return $html;
    }
}

if (!function_exists('get_select')) {
    function get_select($fieldtype, array $data = array())
    {
        $default_value = explode('|', $data['default_value']);

        $field_name = $data['field_name'] . '[' . $data['field_id'] . '][]';

        $html = '<div class="acf-field acf-field-true-false ' . $fieldtype . '">';
        $html .= '<div class="acf-label">';
        $html .= '<label for="' . $data['field_name'] . '" class="' . $data['field_name'] . '">' . $data['field_label'] . '</label>';
        $html .= '<p><small>' . $data['instructions'] . '</small></p>';
        $html .= '</div>';


        $html .= '<div class="acf-input">';

        if (!empty($data['is_required'])) {
            if ($data['is_required'] == 1) {
                $required = 'required="required"';
            } else {
                $required = null;
            }
        } else {
            $required = null;
        }

        $html .= ' <select id="' . $data['css_id'] . '" class="' . $data['css_class'] . '" name="attr_save[' . $data['field_id'] . '][value]" ' . $required . '>';

        foreach ($default_value as $value) {
            $option = explode(':', $value);

            if (is_array($data['value'])) {
                if (in_array($option[0], $data['value'])) {
                    $selected = 'selected="selected"';
                } else {
                    $selected = '';
                }
            } else {
                if ($data['value'] == $option[0]) {
                    $selected = 'selected="selected"';
                } else {
                    $selected = '';
                }
            }


            $html .= '<option value="' . $option[0] . '" ' . $selected . '> ' . $option[1] . '</option>';
        }

        $html .= '</select>';
        $html .= '</div>';

        $html .= '<input type="hidden" value="' . $data['field_id'] . '" name="attr_save[' . $data['field_id'] . '][attribute_id]" />';
        $html .= '<input type="hidden" value="' . $data['attgroup_id'] . '" name="attr_save[' . $data['field_id'] . '][attgroup_id]" />';
        $html .= '<input type="hidden" value="' . $data['field_name'] . '" name="attr_save[' . $data['field_id'] . '][key]" />';
        $html .= '<input type="hidden" value="' . $data['user_id'] . '" name="attr_save[' . $data['field_id'] . '][user_id]" />';
        $html .= '<input type="hidden" value="' . $data['main_pid'] . '" name="attr_save[' . $data['field_id'] . '][main_pid]" />';
        $html .= '<input type="hidden" value="' . (!empty($data['data_save_id']) ? $data['data_save_id'] : null) . '" name="attr_save[' . $data['field_id'] . '][data_save_id]" />';
        $html .= '<input type="hidden" value="' . $data['default_value'] . '" name="attr_save[' . $data['field_id'] . '][default_value]" />';
        $html .= '<input type="hidden" value="' . date('Y-m-d H:i:s') . '" name="attr_save[' . $data['field_id'] . '][created_at]" />';
        $html .= '<input type="hidden" value="' . date('Y-m-d H:i:s') . '" name="attr_save[' . $data['field_id'] . '][updated_at]" />';

        $html .= '</div>';
        return $html;
    }
}


if (!function_exists('get_checkbox')) {
    function get_checkbox($fieldtype, array $data = array())
    {
        $default_value = explode('|', $data['default_value']);
        $field_name = $data['field_name'] . '[' . $data['field_id'] . '][]';

        $html = '<div class="acf-field acf-field-true-false text">';
        $html .= '<div class="acf-label">';
        $html .= '<label for="' . $data['field_name'] . '" class="' . $data['field_name'] . '">' . $data['field_label'] . '</label>';
        $html .= '<p><small>' . $data['instructions'] . '</small></p>';
        $html .= '</div>';

        //dump($data['value']);
        $html .= '<div class="acf-input">';


        if (!empty($data['is_required'])) {
            if ($data['is_required'] == 1) {
                $required = 'required="required"';
            } else {
                $required = null;
            }
        } else {
            $required = null;
        }

        //dump($default_value);
        //dd($default_value);
        $seperated_value = explode(',', $data['value']);
        //dump($seperated_value);
        foreach ($default_value as $value) {
            $option = explode(':', $value);

            //dump($option[0]);

            if (is_array($seperated_value)) {
                //dump($option);
                if (in_array($option[0], $seperated_value)) {
                    $selected = 'checked="checked"';
                } else {
                    $selected = '';
                }
            } else {
                if ($data['value'] == $option[0]) {
                    $selected = 'checked="checked"';
                } else {
                    $selected = '';
                }
            }

            $html .= '<div class="checkbox">';
            $html .= '<label>';
            $html .= '<input type="checkbox" ' . $selected . ' id="' . $data['css_id'] . '" value="' . $option[0] . '" name="attr_save[' . $data['field_id'] . '][value][]" ' . $required . '>';
            $html .= !empty($option[1]) ? $option[1] : null;
            $html .= '</label>';
            $html .= '</div>';

        }
        $html .= '</div>';

        $html .= '<input type="hidden" value="' . $data['field_id'] . '" name="attr_save[' . $data['field_id'] . '][attribute_id]" />';
        $html .= '<input type="hidden" value="' . $data['attgroup_id'] . '" name="attr_save[' . $data['field_id'] . '][attgroup_id]" />';
        $html .= '<input type="hidden" value="' . $data['field_name'] . '" name="attr_save[' . $data['field_id'] . '][key]" />';
        $html .= '<input type="hidden" value="' . $data['user_id'] . '" name="attr_save[' . $data['field_id'] . '][user_id]" />';
        $html .= '<input type="hidden" value="' . $data['main_pid'] . '" name="attr_save[' . $data['field_id'] . '][main_pid]" />';
        $html .= '<input type="hidden" value="' . (!empty($data['data_save_id']) ? $data['data_save_id'] : null) . '" name="attr_save[' . $data['field_id'] . '][data_save_id]" />';
        $html .= '<input type="hidden" value="' . $data['default_value'] . '" name="attr_save[' . $data['field_id'] . '][default_value]" />';
        $html .= '<input type="hidden" value="' . date('Y-m-d H:i:s') . '" name="attr_save[' . $data['field_id'] . '][created_at]" />';
        $html .= '<input type="hidden" value="' . date('Y-m-d H:i:s') . '" name="attr_save[' . $data['field_id'] . '][updated_at]" />';

        $html .= '</div>';


        return $html;
    }
}


if (!function_exists('get_radio')) {
    function get_radio($fieldtype, array $data = array())
    {
        $default_value = explode('|', $data['default_value']);
        $field_name = $data['field_name'] . '[' . $data['field_id'] . '][]';

        $html = '<div class="acf-field acf-field-true-false text">';
        $html .= '<div class="acf-label">';
        $html .= '<label for="' . $data['field_name'] . '" class="' . $data['field_name'] . '">' . $data['field_label'] . '</label>';
        $html .= '<p><small>' . $data['instructions'] . '</small></p>';
        $html .= '</div>';

        $html .= '<div class="acf-input">';

        if (!empty($data['is_required'])) {
            if ($data['is_required'] == 1) {
                $required = 'required="required"';
            } else {
                $required = null;
            }
        } else {
            $required = null;
        }

        foreach ($default_value as $value) {
            //dd($value);
            $option = explode(':', $value);
            if($option[0] && $option[1]){



            if (is_array($data['value'])) {
                if (in_array($option[0], $data['value'])) {
                    $selected = 'checked="checked"';
                } else {
                    $selected = '';
                }
            } else {
                if ($data['value'] == $option[0]) {
                    $selected = 'checked="checked"';
                } else {
                    $selected = '';
                }
            }

            $html .= '<div class="radio">';
            $html .= '<label>';
            $html .= '<input type="radio" ' . $selected . ' id="' . $data['css_id'] . '" value="' . $option[0] . '" name="attr_save[' . $data['field_id'] . '][value]" ' . $required . '>';
            $html .= $option[1];
            $html .= '</label>';
            $html .= '</div>';
            }

        }
       // die;
        $html .= '</div>';


        $html .= '<input type="hidden" value="' . $data['field_id'] . '" name="attr_save[' . $data['field_id'] . '][attribute_id]" />';
        $html .= '<input type="hidden" value="' . $data['attgroup_id'] . '" name="attr_save[' . $data['field_id'] . '][attgroup_id]" />';
        $html .= '<input type="hidden" value="' . $data['field_name'] . '" name="attr_save[' . $data['field_id'] . '][key]" />';
        $html .= '<input type="hidden" value="' . $data['user_id'] . '" name="attr_save[' . $data['field_id'] . '][user_id]" />';
        $html .= '<input type="hidden" value="' . $data['main_pid'] . '" name="attr_save[' . $data['field_id'] . '][main_pid]" />';
        $html .= '<input type="hidden" value="' . (!empty($data['data_save_id']) ? $data['data_save_id'] : null) . '" name="attr_save[' . $data['field_id'] . '][data_save_id]" />';
        $html .= '<input type="hidden" value="' . $data['default_value'] . '" name="attr_save[' . $data['field_id'] . '][default_value]" />';
        $html .= '<input type="hidden" value="' . date('Y-m-d H:i:s') . '" name="attr_save[' . $data['field_id'] . '][created_at]" />';
        $html .= '<input type="hidden" value="' . date('Y-m-d H:i:s') . '" name="attr_save[' . $data['field_id'] . '][updated_at]" />';


        $html .= '</div>';
        return $html;
    }
}