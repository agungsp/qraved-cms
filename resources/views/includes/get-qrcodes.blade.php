@forelse ($qrcodes as $qr)
    <div id="" class="card mb-3 shadow-sm" data-id="{{ $qr->id }}" data-code="{{ $qr->code }}">
        <div class="card-body p-2 card-hover">
            <div class="row">
                <div class="col">
                    Code: <strong>{{ $qr->code }}</strong>
                </div>
                <div class="col-auto">
                    <a id="cardQrCode" href="javascript:void(0)" class=" float-right text-danger stretched-link m-1">
                        <i class="fa fa-qrcode"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@empty
    @include('includes.no-data')
@endforelse
