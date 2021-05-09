@forelse ($qrcodes as $qr)
    <div class="card mb-3 shadow-sm">
        <div class="card-body p-2 card-hover">
            <div class="row">
                <div class="col">
                    Code: <strong>{{ $qr->code }}</strong>
                </div>
                <div class="col-auto">
                    <a id="cardQrCode" href="javascript:void(0)" class="float-right text-muted stretched-link m-1"
                       data-id="{{ $qr->id }}"
                       data-code="{{ $qr->code }}"
                       data-restaurant="{{ $qr->restaurant->name ?? '' }}">
                        <i class="fa fa-qrcode
                            @if (!empty($qr->restaurant->name))
                                text-danger
                            @endif
                        "></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@empty
    @include('includes.no-data')
@endforelse
