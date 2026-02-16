
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>window.jQuery || document.write('<script src="{{ URL::asset('public/frontend/js/vendor/jquery-3.3.1.min.js') }}"><\/script>')</script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript" src="{{ URL::asset('public/frontend/js/main.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('public/frontend/js/plugins.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('public/frontend/js/style.js') }}"></script>

<script>
var overlay = document.getElementById("overlay");
window.addEventListener('load', function(){
  overlay.style.display = 'none';
})
</script>

<script>
$(document).ready(function() {
    jQuery('[data-toggle=tooltip]').tooltip();
}); 

jQuery(document).ready(function($){
    
    $(window).on('load',function(){
        $('#arafta').modal('show');
    });
    
  $(window).on('load',function(){
        $( '#alimify-modal' ).modal('show')
  })
  
  $(window).on("click",function(){
    
    $.ajax({
            url: "{{ route('cookie') }}",
            success: function(result){
            // jQuery("#div1").html(result);
            console.log(result);
        }});
    
  })


        document.addEventListener('click',function(e){
            if($(e.target).closest('.sidenav, .one-mobile').length > 0)
            {
                return
            }
            
             closeNav()
        })

  
});





    function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
    }

    function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
    }


</script>

<script>
        $("#img_01").elevateZoom({
        gallery:'gal1',
        cursor: 'pointer',
        galleryActiveClass: 'active',
        imageCrossfade: true,
        responsive: true
    });
    
    // var ezApi = $('#img_01').data('elevateZoom').changeState('disable');
    
</script>

<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('.content_one, .sidebar_one').theiaStickySidebar({
// Settings
            //additionalMarginTop: 50
        });
    });
</script>





@if(!empty(Session::get('sweet_alert')))
    <script>
        var data = '<?php echo Session::get('sweet_alert') ?>';
        swal("", data, "success");
    </script>
@endif

@if(!empty(Session::get('sweet_error')))
    <script>
        var data = '<?php echo Session::get('sweet_error') ?>';
        swal("", data, "warning");
    </script>
@endif


