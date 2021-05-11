@forelse ($logs as $log)
    <div class="card mb-3 shadow-sm">
        <div class="card-body p-2">
            <div class="row mb-2" style="font-size: 9pt;">
                <div class="col-auto text-muted">
                    <i class="fa fa-user"></i> {{ Str::limit($log->user->email, 20, '...') }}
                </div>
                <div class="col-auto text-muted">
                    <i class="fas fa-map-marker-alt"></i> {{ Str::limit($log->restaurant->name ?? '', 20, '...') }}
                </div>
                <div class="col-auto text-muted">
                    <i class="far fa-clock"></i> {{ $log->created_at->toDateTimeString() }}
                </div>
            </div>
            <div class="row">
                <div class="col text-truncate">
                    {{ $log->action }}
                </div>
                @if (!empty($log->answer))
                    @if ($log->answer->status)
                        <div class="col-auto">
                            <span class="badge badge-success rounded-pill">Correct</span>
                        </div>
                    @else
                        <div class="col-auto">
                            <span class="badge badge-danger rounded-pill">Incorrect</span>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
@empty
    @include('includes.no-data')
@endforelse

