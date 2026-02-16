<div class="row">
    <div class="col-md-9">
        <?php $title = 'Add EMI combination'; ?>

        <table class="table table-bordered" id="add_more_bank">
            <tbody style="position: relative;">
            <button class="btn btn-xs btn-more" style="position: absolute; bottom: -5px; right: 15px;"
                    onclick="add_more_bank('<?php echo $product->id; ?>');">
                Add More
            </button>
            <tr id="append_to_thiss">
                <th style="width: 15%;">Bank Name</th>
                <th style="width: 20%;">Month Range</th>
                <th style="width: 20%;">
                    Interest
                    <small>(%)</small>
                </th>
            </tr>
            
            <?php $emi_datas = \App\Emi::where('main_pid', $product->id)->orderBy('id', 'desc')->get(); ?>

            @foreach($emi_datas as $emi)
                <tr>
                    <td>
                        <?php $bank = \App\Bank::where('id', $emi->bank_id)->get()->first(); ?>
                        {{ $bank->name }}
                    </td>
                    <td>{{ $emi->month_range }}</td>
                    <td style="position: relative;">
                        {{ $emi->interest }}
                        <div class="small_btn"
                             style="position: absolute; top: 6px; bottom: 0; right: -47px;">
                            <a href="{{ url('delete_emi/' . $emi->id) }}"
                               class="btn btn-xs btn-danger"
                               onclick="return confirm('Are you Sure ?')">
                                <i class="fa fa-times-circle" aria-hidden="true"></i> Del
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
</div>
