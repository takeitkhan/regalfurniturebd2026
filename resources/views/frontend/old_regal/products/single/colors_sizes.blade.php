<?php

$color_size = \App\Pcombinationdata::where(['main_pid' => $pro->id])->groupBy('color_codes')->get();

//dump($color_size);

?>
@if(count($color_size) > 1)
    <div class="short_description form-group">
        <h4>Color:</h4>
    </div>
    <div class="coor-warp">

        <div class="cc-selector" id="xhmx_color">
            <?php $col_count = 1; ?>
            @foreach($color_size as $cs)
                <input class="active" id="color{{ $cs['id'] }}"
                       type="radio"
                       name="credit-card"
                       value="{{ $cs['id'] }}"
                       data-product="{{ $cs['main_pid'] }}"
                       data-color="{{ $cs['color_codes'] }}"
                       data-id="{{ $cs['id'] }}"
                       data-price="{{ $cs['price'] }}"
                        {{($col_count == 1)? 'checked':''}}
                />
                <label class="drinkcard-cc visa"
                       for="color{{ $cs['id'] }}"
                       style="background: {{ '#'. $cs['color_codes'] }}">
                </label>
                <?php ++$col_count; ?>

            @endforeach
        </div>
    </div>


    <div class="size-area">
        <div class="short_description form-group">
            <h4>Size:</h4>
        </div>
        <div class="size-area-warp">
            <div class="cc-selector" id="xhmx_size">

            </div>
        </div>
    </div>
@endif