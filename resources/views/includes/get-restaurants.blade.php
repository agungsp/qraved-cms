@forelse ($restaurants as $restaurant)
    <div class="card mb-3 shadow-sm">
        <div class="card-body p-2 card-hover">
            <div class="row">
                <div class="col">
                    <span class="text-dark">
                        {{ $restaurant->name }}
                    </span>
                </div>
                <div class="col-auto">
                    <a href="javascript:void(0)" id="btnEdit" class="float-right text-warning stretched-link m-1" data-id="{{ $restaurant->id }}">
                        <i class="fa fa-edit"></i>
                    </a>
                </div>
                @if (!empty($restaurant->qr_id))
                    <div class="col-auto">
                        <a href="javascript:void(0)" class="float-right text-danger stretched-link m-1">
                            <i class="fa fa-qrcode"></i>
                        </a>
                    </div>
                @endif
                <div class="col-auto">
                    <a href="javascript:void(0)"
                       id="btnLink"
                       class="float-right
                             @if (empty($restaurant->qr_id))
                                text-secondary
                             @else
                                text-danger
                             @endif
                             stretched-link m-1" data-id="{{ $restaurant->id }}" data-name="{{ $restaurant->name }}">
                        <i class="fa fa-link"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@empty
    @include('includes.no-data')
@endforelse

