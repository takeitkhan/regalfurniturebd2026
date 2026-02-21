<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-7">

        <div class="bg-navy color-palette">
            Product Images for {{ $product->title }}
        </div>

        <div class="box box-warning">
            <div class="box-header">
                <h3 class="box-title">Selected Product Images</h3>
            </div>
            
            <div id="product_added">
                <?php
                if (!empty($product->id)) :

                    $added = App\Models\ProductImages::where('main_pid', $product->id)->groupBy('media_id')->get();
                    //dump($added);

                    echo '<table class="table table-bordered" style="width: 100%">';
                    echo '<tr>';
                    echo '<th style="width: 25%;">Image</th>';
                    echo '<th style="width: 60%;">Image Information</th>';
                    echo '<th style="width: 10%;">Image Type</th>';
                    echo '<th style="width: 5%;" class="text-center">Action</th>';
                    echo '</tr>';

                    foreach ($added as $pro) :
                        echo '<tr>';

                        echo '<td>';
                        echo '<img src="' . url($pro->icon_size_directory) . '" class="img-responsive" style="max-height: 60px;"/>';
                        echo '</td>';

                        echo '<td>';
                        echo '<small>';
                        echo '<strong>FN: </strong>' . $pro->filename . '<br/>';
                        echo '<strong>Full: </strong>' . $pro->full_size_directory . '<br/>';
                        echo '<strong>Icon: </strong>' . $pro->icon_size_directory . '<br/>';
                        echo '</small>';
                        echo '</td>';

                        echo '<td class="text-center">';

                        echo "
                        <select onchange='changeImageId($pro->id)' id='main_image_setup_$pro->id'>
                            <option value='0' ".($pro->is_main_image == 0?'selected':'').">Default</option>
                            <option value='1' ".($pro->is_main_image == 1?'selected':'').">Primary</option>
                            <option value='2' ".($pro->is_main_image == 2?'selected':'').">Secondary</option>
                        </select>
                        ";
                        // if ($pro->is_main_image == 1) {
                        //     echo '<a id="unset_main_image" data-id="' . $pro->id . '" data-value="0" data-type="unset" href="javascript:void(0)" class="btn btn-danger btn-xs">Unset</a>';
                        // } else {
                        //     echo '<a id="set_main_image"  data-id="' . $pro->id . '" data-value="1" data-type="set" href="javascript:void(0)" class="btn btn-success btn-xs">Set</a>';
                        // }
                        echo '</td>';

                        echo '<td class="text-center">';
                        echo '<a onclick="return confirm(\'Are you Sure ?\')" href="' . url('delete_productimage/' . $pro->id) . '"><i class="fa fa-times"></i></a>';
                        echo '</td>';

                        echo '</tr>';
                    endforeach;
                    echo '</table>';
                endif;
                ?>
            </div>
        </div>

    </div>

    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-5">

        @component('component.dropzone')
        @endcomponent
        <div class="form-group" style="margin-top: 10px;">
            <label for="media_search_input">Search images</label>
            <input type="text" id="media_search_input" class="form-control"
                   placeholder="Search by filename or type" autocomplete="off">
            <small id="media_search_status" class="text-muted"></small>
        </div>
        @if(!empty($medias))
            <div class="" id="reload_me">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Media Informations</th>
                        <th style="width: 20%;">Image</th>
                        <th style="width: 5%;">Action</th>
                    </tr>
                    </thead>
                    <tbody id="media_results_body">
                    @foreach($medias as $media)
                        <tr>
                            <td>{{ $media->id }}</td>
                            <td>
                                <small>
                                    <strong>FN: </strong>{{ $media->filename }}<br/>
                                    <strong>Full: </strong>{{ $media->full_size_directory }}<br/>
                                    <strong>Icon: </strong>{{ $media->icon_size_directory }}
                                </small>
                            </td>
                            <td>
                                <img src="{{ url($media->icon_size_directory) }}"
                                     class="img-responsive"
                                     style="max-height: 60px;"/>
                            </td>
                            <td>
                                <a
                                        href="javascript:void(0);"
                                        data-userid="{!! (!empty(\Auth::user()->id) ? \Auth::user()->id : NULL) !!}"
                                        data-mainpid="{{ !empty($product->id) ? $product->id : null }}"
                                        data-id="{{ $media->id }}"
                                        data-filename="{{ $media->filename }}"
                                        data-fullsize="{{ $media->full_size_directory }}"
                                        data-iconsize="{{ $media->icon_size_directory }}"
                                        class="btn btn-xs btn-success"
                                        id="use_product_image"
                                        role="button">
                                    Use
                                </a>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="text-center" id="media_pagination" style="margin-top: 10px;">
                    @if(is_object($medias) && method_exists($medias, 'lastPage'))
                        @php
                            $current = $medias->currentPage();
                            $last = $medias->lastPage();
                            $window = 5;
                            $start = max(1, $current - intdiv($window, 2));
                            $end = min($last, $start + $window - 1);
                            if (($end - $start + 1) < $window) {
                                $start = max(1, $end - $window + 1);
                            }
                        @endphp
                        @if($last > 1)
                            <ul class="pagination pagination-sm" style="margin:0;">
                                <li class="{{ $current <= 1 ? 'disabled' : '' }}">
                                    <a href="{{ $current <= 1 ? 'javascript:void(0);' : $medias->appends(request()->except('page'))->url($current - 1) }}">«</a>
                                </li>

                                @if($start > 1)
                                    <li><a href="{{ $medias->appends(request()->except('page'))->url(1) }}">1</a></li>
                                    @if($start > 2)
                                        <li class="disabled"><span>…</span></li>
                                    @endif
                                @endif

                                @for($i = $start; $i <= $end; $i++)
                                    <li class="{{ $i == $current ? 'active' : '' }}">
                                        <a href="{{ $medias->appends(request()->except('page'))->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor

                                @if($end < $last)
                                    @if($end < $last - 1)
                                        <li class="disabled"><span>…</span></li>
                                    @endif
                                    <li><a href="{{ $medias->appends(request()->except('page'))->url($last) }}">{{ $last }}</a></li>
                                @endif

                                <li class="{{ $current >= $last ? 'disabled' : '' }}">
                                    <a href="{{ $current >= $last ? 'javascript:void(0);' : $medias->appends(request()->except('page'))->url($current + 1) }}">»</a>
                                </li>
                            </ul>
                        @endif
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>


<script>

    function changeImageId(id)
    {

        const val = document.getElementById('main_image_setup_'+ id).value,
              product_id = '{{$product->id}}';


              jQuery.ajax({
                        url: baseurl + '/update_image_setup',
                        method: 'get',
                        data: {
                            id: id,
                            val: val,
                            product_id: product_id
                        },
                        success: function (status) {
                            window.location.reload(true);
                        }
                    });


        console.log(id,parseInt(val), parseInt(product_id))

    }

    (function ($) {
        var searchTimer = null;
        var $input = $('#media_search_input');
        var $status = $('#media_search_status');
        var $tbody = $('#media_results_body');
        var $pagination = $('#media_pagination');
        var searchUrl = "{{ route('medias.search') }}";
        var userId = "{!! (!empty(\Auth::user()->id) ? \Auth::user()->id : NULL) !!}";
        var mainPid = "{{ !empty($product->id) ? $product->id : null }}";
        var currentPage = 1;

        if ($input.length === 0 || $tbody.length === 0) {
            return;
        }

        function escapeHtml(text) {
            return String(text)
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        function renderRows(items) {
            var html = '';
            if (!items || items.length === 0) {
                $tbody.html('');
                return;
            }

            items.forEach(function (media) {
                var filename = escapeHtml(media.filename || '');
                var fullsize = escapeHtml(media.full_size_directory || '');
                var iconsize = escapeHtml(media.icon_size_directory || '');
                var imgUrl = "{{ url('') }}/" + (media.icon_size_directory || '');

                html += '<tr>'
                    + '<td>' + escapeHtml(media.id) + '</td>'
                    + '<td><small>'
                    + '<strong>FN: </strong>' + filename + '<br/>'
                    + '<strong>Full: </strong>' + fullsize + '<br/>'
                    + '<strong>Icon: </strong>' + iconsize
                    + '</small></td>'
                    + '<td><img src="' + imgUrl + '" class="img-responsive" style="max-height: 60px;"/></td>'
                    + '<td>'
                    + '<a href="javascript:void(0);"'
                    + ' data-userid="' + escapeHtml(userId) + '"'
                    + ' data-mainpid="' + escapeHtml(mainPid) + '"'
                    + ' data-id="' + escapeHtml(media.id) + '"'
                    + ' data-filename="' + filename + '"'
                    + ' data-fullsize="' + fullsize + '"'
                    + ' data-iconsize="' + iconsize + '"'
                    + ' class="btn btn-xs btn-success" id="use_product_image" role="button">Use</a>'
                    + '</td>'
                    + '</tr>';
            });

            $tbody.html(html);
        }

        function renderPagination(meta) {
            if (!meta || meta.last_page <= 1) {
                $pagination.html('');
                return;
            }

            var current = parseInt(meta.current_page, 10) || 1;
            var last = parseInt(meta.last_page, 10) || 1;
            var windowSize = 5;
            var start = Math.max(1, current - Math.floor(windowSize / 2));
            var end = Math.min(last, start + windowSize - 1);
            if (end - start + 1 < windowSize) {
                start = Math.max(1, end - windowSize + 1);
            }

            var html = '<ul class="pagination pagination-sm" style="margin:0;">';

            var prevDisabled = current <= 1 ? ' disabled' : '';
            html += '<li class="' + prevDisabled + '">'
                + '<a class="media-page-btn" href="javascript:void(0);" data-page="' + (current - 1) + '">«</a>'
                + '</li>';

            if (start > 1) {
                html += '<li><a class="media-page-btn" href="javascript:void(0);" data-page="1">1</a></li>';
                if (start > 2) {
                    html += '<li class="disabled"><span>…</span></li>';
                }
            }

            for (var i = start; i <= end; i++) {
                var active = i === current ? ' active' : '';
                html += '<li class="' + active + '">'
                    + '<a class="media-page-btn" href="javascript:void(0);" data-page="' + i + '">' + i + '</a>'
                    + '</li>';
            }

            if (end < last) {
                if (end < last - 1) {
                    html += '<li class="disabled"><span>…</span></li>';
                }
                html += '<li><a class="media-page-btn" href="javascript:void(0);" data-page="' + last + '">' + last + '</a></li>';
            }

            var nextDisabled = current >= last ? ' disabled' : '';
            html += '<li class="' + nextDisabled + '">'
                + '<a class="media-page-btn" href="javascript:void(0);" data-page="' + (current + 1) + '">»</a>'
                + '</li>';

            html += '</ul>';
            $pagination.html(html);
        }

        function doSearch(page) {
            var query = $.trim($input.val());
            var pageNo = page || 1;
            currentPage = pageNo;
            $status.text('Searching...');
            $.ajax({
                url: searchUrl,
                method: 'GET',
                data: { q: query, limit: 20, page: pageNo },
                success: function (res) {
                    renderRows(res.medias || []);
                    renderPagination({
                        current_page: res.current_page || 1,
                        last_page: res.last_page || 1
                    });
                    if (res && typeof res.count !== 'undefined') {
                        var totalText = res.total ? (res.total + ' total') : (res.count + ' items');
                        $status.text(res.count > 0 ? totalText : (query ? 'No results' : ''));
                    } else {
                        $status.text('');
                    }
                },
                error: function () {
                    $status.text('Failed to load');
                }
            });
        }

        $input.on('keyup', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                doSearch(1);
            }, 400);
        });

        $pagination.on('click', '.media-page-btn', function () {
            var page = parseInt($(this).data('page'), 10) || 1;
            doSearch(page);
        });
    })(jQuery);
</script>