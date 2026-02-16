jQuery(document).ready(function ($) {
    $.noConflict();

    commonFn($);
    divLoadFn($);
    makeDelay($);
    //updateMiniCart($);

});
/**
 *
 * @param $commonFn this function has lots of common functionality to work on the application
 */
commonFn = function ($) {

    //Datemask dd/mm/yyyy
    $('#date, #date_again, #date_again_again').inputmask('dd/mm/yyyy', {'placeholder': 'dd/mm/yyyy'});
    //Datemask2 mm/dd/yyyy
    $('#date1, #date1_again, #date1_again_again').inputmask('mm/dd/yyyy', {'placeholder': 'mm/dd/yyyy'});

     $('#date_time1, #date_time2, #date_time3').inputmask('mm/dd/yyyy H:i:m', {'placeholder': 'mm/dd/yyyy'});
    //Money Euro
    $('[data-mask]').inputmask();

    //Date range picker
    $('#daterange').daterangepicker();
    //Date range picker with time picker
    $('#daterangewithtime, #daterangewithtime2').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
    //Date range as a button
    $('#daterange-btn').daterangepicker(
        {
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            startDate: moment().subtract(29, 'days'),
            endDate: moment()
        },
        function (start, end) {
            $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );

    //Date picker
    $('#datepicker').datepicker({
        autoclose: true
    });

    // //iCheck for checkbox and radio inputs
    // $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
    //     checkboxClass: 'icheckbox_minimal-blue',
    //     radioClass: 'iradio_minimal-blue'
    // });
    // //Red color scheme for iCheck
    // $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
    //     checkboxClass: 'icheckbox_minimal-red',
    //     radioClass: 'iradio_minimal-red'
    // });
    // //Flat red color scheme for iCheck
    // $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    //     checkboxClass: 'icheckbox_flat-green',
    //     radioClass: 'iradio_flat-green'
    // });

    //Colorpicker
    $('.my-colorpicker1').colorpicker();
    //color picker with addon
    $('.my-colorpicker2').colorpicker();

    //Timepicker
    $('.timepicker').timepicker({
        showInputs: false
    });

    /**
     $('#wysiwyg').summernote({
        height: 300,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: true                  // set focus to editable area after initializing summernote
    });
     **/

    $('#wysiwyg').trumbowyg({
        semantic: false
    });
    // $('#wysiwyg').wysihtml5();

};
/**
 * Load a div after ajax call on the same URL/Route
 * usages: divLoadFn('#divid');
 * @param $
 * @param loaddiv
 */
divLoadFn = function ($, loaddiv) {
    $(loaddiv).load(location.href + " " + loaddiv);
};

/**
 * Copy to clipboard
 * @param text
 * @param el
 */
copyToClipboard = function (text, el) {
    var copyTest = document.queryCommandSupported('copy');
    var elOriginalText = el.attr('data-original-title');

    if (copyTest === true) {
        var copyTextArea = document.createElement("textarea");
        copyTextArea.value = text;
        document.body.appendChild(copyTextArea);
        copyTextArea.select();
        try {
            var successful = document.execCommand('copy');
            var msg = successful ? 'Copied!' : 'Whoops, not copied!';
            el.attr('data-original-title', msg).tooltip('show');
        } catch (err) {
            console.log('Oops, unable to copy');
        }
        document.body.removeChild(copyTextArea);
        el.attr('data-original-title', elOriginalText);
    } else {
        // Fallback if browser doesn't support .execCommand('copy')
        window.prompt("Copy to clipboard: Ctrl+C or Command+C, Enter", text);
    }
};


/**
 *
 * @param $
 * @param formid HTML form ID attribute
 * @param route Laravel Web Route to post
 * @param rules
 * @param messages
 */
ajaxFn = function ($, form, route, rules, messages) {

    //alert(form);

    if (messages !== undefined) {
        var messages = messages;
    } else {
        var messages = $.validator.messages.required = '';
    }

    $(form).validate({
        rules: rules,
        messages: messages,
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `help-block` class to the error element
            error.addClass("help-block");

            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.parent("label"));
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parents(".form-group").addClass("has-error").removeClass("has-success");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents(".form-group").addClass("has-success").removeClass("has-error");
        },
        submitHandler: function (form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function (response) {
                    //alert();
                    window.location.reload(true);
                    //$('.msgbox').html(response).show().addClass('alert-success').delay(2000).fadeOut();
                },
                error: function (response) {
                    //alert();
                    window.location.reload(true);
                    //$('.msgbox').html(response).show().addClass('alert-danger').delay(2000).fadeOut();
                }
            });
        }
    });
};

/**
 *
 * @param id
 * @param routes
 */
ajaxRemoveFn = function (id, url, loadclass) {

    if (result == true) {
        jQuery.ajax({
            type: 'GET',
            dataType: 'JSON',
            url: url,
            data: id,
            success: function (data) {
                //$('.msgbox').html(showSuccess(data.msg)).show().delay(2000).fadeOut();
                $(".timeline-item").load(window.location + " " + ".timeline-item");
                //window.location.reload(true);
                //alert('Success');
            },
            error: function (data) {
                //jQuery("#errormsg").html(data.msg + " Failed to Delete").show().delay(3000).fadeOut();
            }
        });
    } else {
        return false;
    }
};


makeDelay = function (ms) {
    var timer = 0;
    return function (callback) {
        clearTimeout(timer);
        timer = setTimeout(callback, ms);
    };
};