@php
    $proofs = $order_master->proofs()->with(['image', 'creator'])->latest()->get();
@endphp

<div class="row">
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Add Proofs (Image / PDF)</h3>
            </div>
            <div class="box-body">
                {!! Form::open(['url' => route('order.proofs.store'), 'class' => 'dropzone', 'files' => true, 'id' => 'proofs-dropzone']) !!}
                <input type="hidden" name="order_id" value="{{ $order_master->id }}"/>
                <div class="dz-message"></div>
                <div class="fallback"><input name="file" type="file" multiple/></div>
                <div class="dropzone-previews" id="proofsDropzonePreview"></div>
                <h4 style="text-align: center;color:#428bca;">
                    Drop files in this area (Images or PDF)
                    <span class="glyphicon glyphicon-hand-down"></span>
                </h4>
                {!! Form::close() !!}
                <div class="jumbotron how-to-create">
                    <ul>
                        <li>Files are uploaded as soon as you drop them</li>
                        <li>Maximum allowed size is 8MB per file</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Existing Proofs</h3>
            </div>
            <div class="box-body" id="proofs_list">
                @if($proofs->isEmpty())
                    <div class="callout callout-info">
                        No proofs uploaded yet.
                    </div>
                @else
                    <div class="row">
                        @foreach($proofs as $proof)
                            @php
                                $isPdf = optional($proof->image)->file_extension === 'pdf';
                            @endphp
                            <div class="col-sm-6">
                                <div class="box box-default">
                                    <div class="box-body">
                                        <div class="text-center" style="margin-bottom: 10px;">
                                            @if($isPdf)
                                                <i class="fa fa-file-pdf-o" style="font-size: 48px; color: #d9534f;"></i>
                                            @else
                                                <img class="img-responsive" style="max-height: 150px; display: inline-block;"
                                                     src="{{ url(optional($proof->image)->icon_size_directory) }}"/>
                                            @endif
                                        </div>
                                        <div class="small text-center">
                                            <div><strong>File:</strong> {{ optional($proof->image)->original_name }}</div>
                                            <div><strong>Type:</strong> {{ strtoupper(optional($proof->image)->file_extension) }}</div>
                                            <div><strong>Uploaded:</strong> {{ $proof->created_at }}</div>
                                            @if($proof->creator)
                                                <div><strong>By:</strong> {{ $proof->creator->name }}</div>
                                            @endif
                                        </div>
                                        <div class="text-center" style="margin-top: 10px;">
                                            @if(optional($proof->image)->full_size_directory)
                                                <a class="btn btn-xs btn-success" target="_blank"
                                                   href="{{ url($proof->image->full_size_directory) }}">
                                                    <i class="fa fa-external-link"></i> View
                                                </a>
                                            @endif
                                            <form method="POST" action="{{ route('order.proofs.delete', $proof->id) }}" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-xs btn-danger" type="submit" onclick="return confirm('Delete this proof?');">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Proofs Dropzone Preview Template -->
<div id="proofs-preview-template" style="display: none;">
    <div class="dz-preview dz-file-preview">
        <div class="dz-image"><img data-dz-thumbnail=""></div>
        <div class="dz-details">
            <div class="dz-size"><span data-dz-size=""></span></div>
            <div class="dz-filename"><span data-dz-name=""></span></div>
        </div>
        <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress=""></span></div>
        <div class="dz-error-message"><span data-dz-errormessage=""></span></div>
        <div class="dz-success-mark">
            <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg">
                <title>Check</title>
                <path d="M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z" fill="#FFFFFF"></path>
            </svg>
        </div>
        <div class="dz-error-mark">
            <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg">
                <title>error</title>
                <path d="M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z" fill="#FFFFFF"></path>
            </svg>
        </div>
    </div>
</div>
<!-- End Proofs Dropzone Preview Template -->
