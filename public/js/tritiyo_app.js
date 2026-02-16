jQuery(document).ready(function ($) {
    $.noConflict();

    /**
     * Save Widget ajaxFn
     */
    ajaxFn($, '#widget_form', 'widget_save',
        {
            widget_name: {
                required: true,
                minlength: 3,
                maxlength: 30
            },
            widget_content: "required",
            widget_css_class: {
                required: true,
                minlength: 2
            }
        },
        {
            widget_name: "Please enter your widget name",
            widget_content: "Please enter your widget content",
            widget_css_class: "Please enter your widget css class"
        }
    );

    /**
     * Save term ajaxFn
     */
    ajaxFn($, '#term_form', 'term_save',
        {
            term_name: {
                required: true,
                minlength: 3,
                maxlength: 30
            },
            term_content: "required",
            seo_url: "required",
            term_css_class: {
                required: true,
                minlength: 2
            }
        },
        {
            term_name: "Please enter your term name",
            term_content: "Please enter your term content",
            seo_url: "Please enter your term SEO URL",
            term_css_class: "Please enter your term css class"
        }
    );
});