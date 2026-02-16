<!-- /.content-wrapper -->

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b>
        {{ env('APP_VERSION') }}
    </div>
    <strong>
        Copyright © {!! date('Y') !!}
        <a href="https://activesoftware.biz" target="_blank">ActiveSoftware Ltd</a>.</strong> All rights reserved.
</footer>

<div class="modal fade" id="getDataModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body" id="getData" style="overflow-x: auto;">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getCodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body" id="getCode" style="overflow-x: auto;">
            </div>
        </div>
    </div>
</div>
