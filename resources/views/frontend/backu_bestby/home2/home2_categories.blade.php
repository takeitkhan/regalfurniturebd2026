@php
$home_cat = get_home_cat();
@endphp

@if($home_cat)

<div id="so_categories_16425506561529398732" class="so-categories module custom-slidercates custom-slidercates_onsar">
    <h3 class="modtitle"><span>Shop by Categories</span></h3>




    <div class="modcontent">
        <div class="yt-content-slider cat-wrap" data-rtl="yes" data-loop="yes" data-autoplay="yes" data-autoheight="no"
             data-delay="4" data-speed="0.6" data-margin="30" data-items_column00="5"
             data-items_column0="5" data-items_column1="4" data-items_column2="3" data-items_column3="2"
             data-items_column4="1" data-arrows="yes" data-pagination="no" data-lazyload="yes"
             data-loop="no" data-hoverpause="yes">

            <?php
                echo $home_cat
            ?>



        </div>
    </div>
</div>
@endif