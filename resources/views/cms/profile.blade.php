@extends('layouts.main')

{{-- META --}}
@section('meta')

@endsection

{{-- CSS --}}
@section('css')
    <style>
        #change-avatar {
            background-color: #dd0000;
            height: 30px;
            width: 30px;
            text-align: center;
            border-style: solid;
            border-color: white;
            color: #fff;
            border-radius: 50%;
            position: absolute;
            bottom: 0px;
            right: 10px;
            cursor: pointer;
        }

        #change-avatar:hover {
            border-color: rgba(225, 83, 97, 0.5);
            box-shadow: 0 0 0 0.2rem rgba(225, 83, 97, 0.5);
        }

        .custom-border {
            border-style: solid;
            border-width: 0.25px;
            border-color: #d3d3d3;
        }
    </style>
@endsection

{{-- TITLE --}}
@section('title', 'Profile')

{{-- TITLE CONTENT --}}
@section('title_content', 'Profile')

{{-- CONTENT --}}
@section('content')
    <div class="row">
        <div class="col-lg-5">
            <div class="card">
                <div class="card-body">
                    <form id="formProfile" method="POST">
                        <div class="row justify-content-center">
                            <div class="col-auto p-0">
                                <img id="preview_avatar" class="img-thumbnail rounded-circle shadow-sm" width="150"
                                     src="{{ empty(auth()->user()->avatar_path) ?
                                             'https://ui-avatars.com/api/?name=' . Str::slug(auth()->user()->name) :
                                             route('cms.profile.avatar', auth()->user()->avatar_path) }}"
                                     alt="Avatar">
                                <label for="avatar" id="change-avatar">
                                    <i class="fas fa-pencil-alt"></i>
                                </label>
                                <input type="file" name="avatar" id="avatar" style="display: none;" onchange="readURL(this, 'avatar');">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ auth()->user()->name }}">
                            <span id="name_invalid" class="invalid-feedback d-block" role="alert"></span>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ auth()->user()->email }}">
                            <span id="email_invalid" class="invalid-feedback d-block" role="alert"></span>
                        </div>
                        <div class="form-group">
                            <label for="password">New Password</label>
                            <div class="input-group mb-3">
                                <input type="password" name="password" id="password" class="form-control">
                                <div class="input-group-append">
                                    <button class="btn btn-light custom-border" type="button" id="view-password">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <span id="password_invalid" class="invalid-feedback d-block" role="alert"></span>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <div class="input-group mb-3">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                                <div class="input-group-append">
                                    <button class="btn btn-light custom-border" type="button" id="view-password-confirm">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="button" id="btnCancel" class="btn btn-light">
                                Cancel
                            </button>
                            <button type="submit" id="btnSave" class="btn qraved-btn-primary">
                                <i class="fa fa-save"></i> Save
                            </button>
                        </div>
                    </form>
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
    <script src="{{ asset('assets/js/showImgFromFileInput.js') }}"></script>
    <script>
        let view_password = false;
        let view_password_confirm = false;

        function showValidation(errors) {
            for (const error in errors) {
                $(`#${error}`).addClass('is-invalid');
                $('#'+error+'_invalid').html(errors[error][0]);
            }
        }

        $('body').on('click', '#view-password', function () {
            view_password = !view_password;
            $(this).html(view_password ? '<i class="fa fa-eye-slash"></i>' : '<i class="fa fa-eye"></i>');
            $('#password').attr('type', view_password ? 'text' : 'password');

        });

        $('body').on('click', '#view-password-confirm', function () {
            view_password_confirm = !view_password_confirm;
            $(this).html(view_password_confirm ? '<i class="fa fa-eye-slash"></i>' : '<i class="fa fa-eye"></i>');
            $('#password_confirmation').attr('type', view_password_confirm ? 'text' : 'password');
        });

        $('#formProfile').submit(function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{ route('cms.profile.store') }}",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    Swal.fire({
                        icon : response.success ? 'success' : 'error',
                        title: response.success ? `Success` : 'Failed',
                        text : response.message,
                        timer: 3000,
                        timerProgressBar: true,
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
    </script>
@endsection
