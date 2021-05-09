@extends('layouts.main')

{{-- META --}}
@section('meta')

@endsection

{{-- CSS --}}
@section('css')

@endsection

{{-- TITLE --}}
@section('title', 'QR Codes')

{{-- TITLE CONTENT --}}
@section('title_content', 'QR Codes')

{{-- CONTENT --}}
@section('content')
    @include('includes.nav-page')

    {{-- Data Wrapper --}}
    <div id="qrcode_list"></div>
    <button id="btnMore" class="btn qraved-btn-primary btn-block d-none">
        more..
    </button>
    @include('includes.loading')
@endsection

{{-- MODAL --}}
@section('modal')
    <!-- Modal -->
    <div class="modal fade" id="modalQrCode" tabindex="-1" role="dialog" aria-labelledby="modalQrCodeLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalQrCodeLabel">Add/Edit Restaurant</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formQrCode">
                        <input type="hidden" name="id" id="id" value="">
                        <div id="qrDisplay" class="form-group d-flex justify-content-center"></div>
                        <div class="form-group">
                            <label for="code">Code</label>
                            <div class="input-group mb-3">
                                <input type="text" name="code" id="code" class="form-control" placeholder="Type the unique code or click generate button">
                                <div class="input-group-append">
                                    <button type="button" id="btnGenerateCode" class="btn qraved-btn-primary" title="Generate unique code">
                                        <i class="fa fa-sync"></i>
                                    </button>
                                </div>
                            </div>
                            <span id="code_invalid" class="invalid-feedback" role="alert"></span>
                        </div>
                        <div id="make_a_few_wrapper">
                            <hr>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="makeAFew" name="makeAFew">
                                <label class="custom-control-label" for="makeAFew">Make a few</label>
                            </div>
                            <div class="form-group">
                                <label for="total">Total</label>
                                <input type="number" name="total" id="total" class="form-control" min="1" value="1" disabled>
                                <span id="total_invalid" class="invalid-feedback" role="alert"></span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" id="btnClose" class="btn btn-light" data-dismiss="modal">Close</button>
                    <button type="button" id="btnDelete" class="btn qraved-btn-danger d-none">
                        <i class="fa fa-trash"></i> Delete
                    </button>
                    <button type="button" id="btnSave" class="btn qraved-btn-primary">
                        <i class="fa fa-save"></i> Save
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- JS --}}
@section('js')
    <script>
        let lastId = 0;
        let hasNext = true;

        function loading() {
            $('#loading').toggleClass('d-none');
        }

        $('body').on('click', '#btnMore', function () {
            loadList();
        });

        $('body').on('click', '#btnGenerateCode', function () {
            let code = generateRandomString({{ SettingHelper::getAll()['qr_length'] }}, "{{ SettingHelper::getAll()['qr_prefix'] ?? '' }}");
            $.get(`{{ route('cms.qr-code.index') }}/qr-builder/${btoa(code)}`, function (res) {
                $('#code').val(res);
                getQR(res);
            });
        });

        $('body').on('change', '#makeAFew', function () {
            const checked = $(this).prop('checked');
            $('#code').prop('disabled', checked);
            $('#code').val('');
            $('#total').prop('disabled', !checked);
            $('#total').val(1);
            $('#total').focus();
            $('#total').select();
        });

        $('body').on('click', '#btnNew', function () {
            $('#btnClose').removeClass('d-none');
            $('#btnDelete').addClass('d-none');
            $('#formQrCode').trigger('reset');
            $('#qrDisplay').html('');
            $('#make_a_few_wrapper').removeClass('d-none');
            $('#modalQrCodeLabel').html('Add QR Code');
            $('#modalQrCode').modal('show');
        });

        $('#modalQrCode').on('shown.bs.modal', function () {
            $('#code').focus();
        });

        $('body').on('click', '#cardQrCode', function () {
            const id = $(this).attr('data-id');
            const code = $(this).attr('data-code');

            $('#make_a_few_wrapper').addClass('d-none');

            $('#id').val(id);
            $('#code').val(code);
            getQR(code);
            $('#modalQrCodeLabel').html('Edit QR Code');
            $('#btnClose').addClass('d-none');
            $('#btnDelete').removeClass('d-none');
            $('#modalQrCode').modal('show');
        });

        $('body').on('click', '#btnSave', function () {
            $.ajax({
                type: "POST",
                url: "{{ route('cms.qr-code.store') }}",
                data: $('#formQrCode').serialize(),
                success: function(response) {
                    lastId = 0;
                    hasNext = true;
                    Swal.fire({
                        icon : response.success ? 'success' : 'error',
                        title: response.success ? `Success` : 'Failed',
                        text : response.message,
                        timer: 3000,
                        timerProgressBar: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#modalQrCode').modal('hide');
                            loadList();
                        }
                        else if (result.isDismissed) {
                            $('#modalQrCode').modal('hide');
                            loadList();
                        }
                    });
                },
                error: function(response) {
                    if (response.status) {
                        showValidation(response.responseJSON.errors);
                    }
                    else {
                        Swal.fire({
                            icon : response.success ? 'success' : 'error',
                            title: response.success ? 'Success' : 'Failed',
                            text : response.message,
                            // timer: 2000,
                            // timerProgressBar: true,
                        });
                    }
                }
            });
        });

        $('body').on('click', '#btnDelete', function () {
            const id = $('#id').val();
            const code = $('#code').val();
            Swal.fire({
                title: `You are sure to delete QR Code "${code}"?`,
                showDenyButton: true,
                showConfirmButton: false,
                showCancelButton: true,
                denyButtonText: `Delete`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isDenied) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('cms.qr-code.delete') }}",
                        data: {
                            id: id
                        },
                        success: function(response) {
                            lastId = 0;
                            hasNext = true;
                            Swal.fire({
                                icon : response.success ? 'success' : 'error',
                                title: response.success ? `Success` : 'Failed',
                                text : response.message,
                                timer: 3000,
                                timerProgressBar: true,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('#modalQrCode').modal('hide');
                                    loadList();
                                }
                                else if (result.isDismissed) {
                                    $('#modalQrCode').modal('hide');
                                    loadList();
                                }
                            });
                        },
                        error: function(response) {
                            Swal.fire({
                                    icon : response.success ? 'success' : 'error',
                                    title: response.success ? 'Success' : 'Failed',
                                    text : response.message,
                                    // timer: 2000,
                                    // timerProgressBar: true,
                                });
                        }
                    });
                }
            })
        });

        function showValidation(errors) {
            for (const error in errors) {
                $(`#${error}`).addClass('is-invalid');
                $('#'+error+'_invalid').html(errors[error][0]);
            }
        }

        function loadList() {
            loading();
            $.get(`{{ route('cms.qr-code.index') }}/get-qrcodes/${lastId}`, function (res) {
                if (lastId == 0) {
                    $('#qrcode_list').html(res.html);
                }
                else {
                    $('#qrcode_list').append(res.html);
                }
                lastId += 50;
                hasNext = res.hasNext;
                loading();
                if (hasNext) {
                    $('#btnMore').removeClass('d-none');
                }
                else {
                    $('#btnMore').addClass('d-none');
                }
            });
        }

        function getQR(code) {
            $.get(`{{ route('cms.qr-code.index') }}/get-qrcode/${btoa(code)}`, function (res) {
                $('#qrDisplay').html(res);
            });
        }

        const generateRandomString = function (length, randomString="") {
            randomString += Math.random().toString(20).substr(2, length);
            if (randomString.length > length) return randomString.slice(0, length);
            return generateRandomString(length, randomString);
        };

        $(document).ready(() => {
            loadList();
        });


    </script>
@endsection
