<?php
$emis = \App\Emi::where('main_pid', $pro->id)->groupBy('bank_id')->get();
$emis_nulls = \App\Emi::where('main_pid', $pro->id)->whereRaw('interest IS NULL')->groupBy('bank_id')->get()->first();
//dump($emis_nulls);
?>
<div class="">
    {{ (!empty($emis_nulls->interest) ? $emis_nulls->interest : 0) }}%
    <?php $each_month = $pro->local_selling_price / $emis_nulls->month_range; ?>
    EMI AVAILABLE: STARTS FROM Tk. <b>{{ sprintf ("%.2f", $each_month) }}</b>/month
    <br/>

    <?php
    $emi_data = Session::get('emi_data');
    //dump($emi_data);
    ?>

    <button class="btn btn-mega btn-xs" type="button" data-toggle="modal" style="background: #fb3b4e; color: #FFFFFF;"
            data-target=".bd-example-modal-lg2">
        @if($emi_data['value'] == 'on' && $emi_data['main_pid'] == $pro->id)
            Modify your EMI plan
        @else
            EMI Add to cart
        @endif
    </button>
    <!-- modal strat -->
    <div class="modal fade bd-example-modal-lg2" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel2" aria-hidden="true">
        <div class="modal-dialog modal-lg2">
            {{ Form::open(array('url' => 'set_emi', 'method' => 'post', 'value' => 'PATCH', 'id' => 'set_emi', 'files' => true, 'autocomplete' => 'off')) }}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel2">
                        VIEW EMI PLANS AND SELECT ONE
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php if(Session::has('emi_data')) { ?>
                    <a class="pull-right btn btn-danger btn-xs" href="{{ url('unset_emi') }}">
                        Unset EMI
                    </a>
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="bank-list">
                                <h4>NO Cost &amp; Standard EMIs</h4>
                                <div class="">
                                    <ul class="nav nav-tabs bank-list-one">
                                        <?php $i = 1; ?>
                                        @foreach($emis as $e)
                                            <li <?php echo ($i == 1) ? 'class="active"' : null ?>>
                                                <a data-toggle="tab" href="#tab-n<?php echo $i; ?>">
                                                    <?php $bank = \App\Bank::where('id', $e->bank_id)->first(); ?>
                                                    {{ $bank->name }}
                                                </a>
                                            </li>
                                            <?php $i++; ?>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="tab-content tab-content_one">

                                <?php $i = 1; ?>
                                @foreach($emis as $e)
                                    <div id="tab-n<?php echo $i; ?>"
                                         class="tab-pane fade <?php echo ($i == 1) ? 'active' : null ?> in">
                                        <div class="category-style">
                                            <div class="modcontent">
                                                <div class="box-category">
                                                    <div class="modl-cont-title">
                                                        <?php $bank = \App\Bank::where('id', $e->bank_id)->first(); ?>
                                                        <h4>
                                                            {{ $bank->name }} EMI PLANS
                                                        </h4>
                                                    </div>

                                                    <?php $interest = \App\Emi::where('bank_id', $e->bank_id)->where('main_pid', $pro->id)->orderBy('month_range', 'ASC')->get(); //dd($interest); ?>

                                                    <ul id="cat_accordion" class="list-group">

                                                        @foreach($interest as $in)
                                                            <?php //dd($in); ?>
                                                            <li class="cutom-parent-li">
                                                                <input id="emi_checkbox"
                                                                       style="margin-top: 10px;margin-right: 10px;"
                                                                       class="pull-left"
                                                                       type="checkbox"
                                                                       name="emi_available"
                                                                       data-id="{{ $in->id }}"
                                                                       data-bank="{{ $in->bank_id }}"
                                                                       data-main_pid="{{ $pro->id }}"
                                                                        @php
                                                                            if ($emi_data['plan_id'] == $in->id && $in->bank_id == $emi_data['bank_id']) {
                                                                                echo 'checked="checked"';
                                                                            }
                                                                        @endphp
                                                                />
                                                                <a class="cutom-parent">
                                                                    {{ (!empty($in->interest) ? $in->interest : 0) }}%
                                                                    <?php $each_month = $pro->local_selling_price / $in->month_range; ?>
                                                                    EMI AVAILABLE:
                                                                    TK <b>{{ sprintf ("%.2f", $each_month) }}</b>/month
                                                                    [<b>{{ $in->month_range }}</b> months]
                                                                    <span class="dcjq-icon"></span>
                                                                </a>
                                                                <span class="button-view fa fa-plus-square-o"></span>

                                                                <ul class="back-bg">
                                                                    <li>
                                                                        <a href="">
                                                                            Price
                                                                            <span class="badge badge-secondary_one">TK {{ number_format($pro->local_selling_price) }}</span>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="">
                                                                            Interest paid to bank
                                                                            <span class="badge badge-secondary_one">
                                                                                <?php
                                                                                if (!empty($in->interest)) {
                                                                                    $lsp = $pro->local_selling_price;
                                                                                    $int = $in->interest;

                                                                                    $total_interest = ($lsp * $int / 100);
                                                                                } else {
                                                                                    $total_interest = 0;
                                                                                }
                                                                                $payable = $pro->local_selling_price + $total_interest;
                                                                                ?>
                                                                                TK {{ $total_interest }}
                                                                            </span>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="border-top-area" href="">
                                                                            Total amount payable
                                                                            <span class="badge badge-secondary_one">TK {{ number_format($payable) }}</span>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </li>
                                                        @endforeach

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $i++; ?>
                                @endforeach

                            </div>
                        </div>
                    </div>
                    <div class="showMessage"></div>
                </div>
                <div class="modal-footer">
                    <div class="cart cart_back" id="add_to_cart_btn">
                        {{ Form::button('EMI Add to cart', ['class' => 'btn btn-mega btn-xs pull-right', 'id' => 'emi_pur', 'data-price' => $regularprice,'style' => 'background: #fb3b4e;border-radius: 0;color: #fff;text-transform: uppercase;font-size: 13px;font-weight: bold;padding: 5px 16px;']) }}
                    </div>
                    <p style="color: red;">
                        If you would like to purchase through EMI then choose a plan above and hit
                        <strong>
                            "EMI Add to cart"
                        </strong>
                    </p>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
    <!-- modal end -->
</div>