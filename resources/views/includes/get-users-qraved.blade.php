@foreach ($users as $user)
    <div class="card mb-3 shadow-sm">
        <div class="card-body p-2">
            <div class="row">
                <div class="col pt-2">
                    {{ $user->email }}
                </div>
                <div class="col-auto p-2">
                    <a class="mr-3" id="btnDetail" href="javascript:void(0)" data-id="{{ $user->id }}" title="Detail">
                        <i class="fas fa-info-circle text-secondary"></i>
                    </a>
                </div>
                <div class="col-auto p-2">
                    <a class="mr-3" id="btnDelete" href="javascript:void(0)" data-id="{{ $user->id }}" data-email="{{ $user->email }}" title="Delete">
                        <i class="fa fa-trash text-danger"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endforeach
