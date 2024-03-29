@extends('layouts.main')

{{-- META --}}
@section('meta')

@endsection

{{-- CSS --}}
@section('css')

@endsection

{{-- TITLE --}}
@section('title', 'Settings')

{{-- TITLE CONTENT --}}
@section('title_content', 'Settings')

{{-- CONTENT --}}
@section('content')
    {{-- @include('includes.nav-page') --}}

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-5">
                    <form id="formSetting" method="POST">
                        <div class="form-group">
                            <label for="url">QR URL</label>
                            <input type="text" name="url" id="url" class="form-control" value="{{ $settings['qr_url'] ?? url('/resto') }}">
                            <span id="url_invalid" class="invalid-feedback" role="alert"></span>
                        </div>
                        {{-- <div class="form-group">
                            <label for="qr_length">
                                QR Length
                                <span class="small text-muted">length = [prefix + random string]</span>
                            </label>
                            <input type="number" name="qr_length" id="qr_length" class="form-control" value="{{ $settings['qr_length'] ?? '255' }}">
                            <span id="qr_length_invalid" class="invalid-feedback" role="alert"></span>
                        </div> --}}
                        <div class="form-group">
                            <label for="qr_prefix">
                                QR Prefix
                            </label>
                            <input type="text" name="qr_prefix" id="qr_prefix" class="form-control" value="{{ $settings['qr_prefix'] ?? '?res=' }}">
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-auto">
                                <button type="submit" id="btnSave" class="btn qraved-btn-primary">
                                    <i class="fa fa-save"></i> Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col border-top border-bottom bg-light">
                    <span class="small text-muted">Example QR</span>
                    <span class="d-block text-center pt-1" id="qr_display"></span>
                    <span class="d-block text-center pt-1 pb-3" style="font-size: 20px;">
                        <span id="full_text_display"></span>
                    </span>
                </div>
            </div>
        </div>
    </div>

@endsection

{{-- MODAL --}}
@section('modal')

@endsection

{{-- JS --}}
@section('js')
    <script>
        let randomStringExample = "ThI5Is_R4nD0mSt12iN6";
        let full_text = '';

        $('body').on('keyup', '#url', function () {
            setFullText();
            getQRCode(full_text);
        });

        $('body').on('keyup', '#qr_prefix', function () {
            setFullText();
        });

        $('body').on('keyup', '#qr_length', function () {
            if ($(this).val() > 20) return false;
            setFullText();
        });

        function setFullText() {
            full_text = $('#url').val() + ($('#qr_prefix').val() + randomStringExample).substr(0, $('#qr_length').val());
            $('#full_text_display').html(full_text);
            getQRCode(full_text);
        }

        function getQRCode(text) {
            $.get(`{{ secure_url(route('cms.setting.index', [], false)) }}/get-qrcode/${btoa(text)}`, function (res) {
                $('#qr_display').html(res);
            });
        }

        $('#formSetting').submit(function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                type       : 'POST',
                url        : '{{ secure_url(route('cms.setting.store', [], false)) }}',
                data       : formData,
                contentType: false,
                processData: false,
                success    : (response) => {
                    Swal.fire({
                        icon : response.success ? 'success' : 'error',
                        title: response.success ? 'Success' : 'Failed',
                        text : response.message,
                        timer: response.success ? 3000 : undefined,
                        timerProgressBar: response.success,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            //
                        }
                        else if (result.isDismissed) {
                            //
                        }
                    });
                },
                error: function(response){
                    if (response.status) {
                        showValidation(response.responseJSON.errors);
                    }
                    else {
                        Swal.fire({
                            icon : response.success ? 'success' : 'error',
                            title: response.success ? 'Success' : 'Failed',
                            text : response.message,
                        });
                    }
                }
            });

        });

        function showValidation(errors) {
            for (const error in errors) {
                $(`#${error}`).addClass('is-invalid');
                $('#'+error+'_invalid').html(errors[error][0]);
            }
        }

        $(document).ready(() => {
            setFullText();
            getQRCode(full_text);
        });


    </script>
@endsection
