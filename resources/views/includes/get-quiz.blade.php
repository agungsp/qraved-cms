@forelse ($questions as $quiz)
    <div class="card mb-3 shadow-sm">
        <div class="card-body p-2">
            <div class="row">
                <div class="col text-truncate">
                    {{ $quiz->question }}
                </div>
                <div class="col-1 text-right">
                    {{-- <a id="btnView" class="mr-3" href="javascript:void(0)" data-id="{{ $quiz->id }}" title="View">
                        <i class="fa fa-eye text-secondary"></i>
                    </a> --}}
                    <a id="btnEdit" class="mr-4" href="javascript:void(0)" data-id="{{ $quiz->id }}" title="Edit">
                        <i class="fa fa-edit text-warning"></i>
                    </a>
                    <a id="btnDelete" class="mr-2" href="javascript:void(0)" data-id="{{ $quiz->id }}" title="Delete">
                        <i class="fa fa-trash text-danger"></i>
                    </a>
                </div>
            </div>
            <div class="row justify-content-between">
                <div class="col-auto">
                    <span class="small text-muted font-italic">
                        @if ($quiz->answer_type == 1)
                            Multiple Choice
                        @elseif ($quiz->answer_type == 2)
                            Essay
                        @endif
                    </span>
                </div>
                <div class="col-auto">
                    <span class="small text-muted">
                        {{ $quiz->updated_at->diffForHumans() }}
                    </span>
                </div>
            </div>
        </div>
    </div>
@empty
    @include('includes.no-data')
@endforelse
