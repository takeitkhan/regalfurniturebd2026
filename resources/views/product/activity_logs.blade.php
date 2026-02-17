@php
    $productId = $product->id ?? null;
    $activity_logs = \App\Models\ActivityLog::with('user')
        ->when($productId, function ($q) use ($productId) {
            $q->where('entity_type', 'product')->where('entity_id', $productId);
        })
        ->orderBy('id', 'desc')
        ->take(100)
        ->get();
@endphp

<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Product Activity Logs</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Time</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Entity</th>
                            <th>Old</th>
                            <th style="width: 240px;">New</th>
                            <th>Note</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($activity_logs as $log)
                            <tr>
                                <td>{{ $log->created_at }}</td>
                                <td>{{ $log->user->name ?? 'System' }}</td>
                                <td>{{ $log->action }}</td>
                                <td>{{ $log->entity_type }}{{ $log->entity_id ? ' #' . $log->entity_id : '' }}</td>
                                <td>{{ \Illuminate\Support\Str::limit(json_encode($log->old_values), 160) }}</td>
                                <td style="width: 240px; max-width: 240px; word-break: break-word;">
                                    @php $newValues = (array) ($log->new_values ?? []); @endphp
                                    @if(!empty($newValues))
                                        @foreach($newValues as $k => $v)
                                            <div><strong>{{ $k }}:</strong> {{ is_array($v) ? implode(', ', $v) : $v }}</div>
                                        @endforeach
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ \Illuminate\Support\Str::limit($log->note, 160) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">No activity logs found for this product.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
