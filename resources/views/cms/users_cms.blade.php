@extends('layouts.main')

{{-- META --}}
@section('meta')

@endsection

{{-- CSS --}}
@section('css')
    <style>
        .custom-border {
            border-style: solid;
            border-width: 0.25px;
            border-color: #d3d3d3;
        }
    </style>
@endsection

{{-- TITLE --}}
@section('title', 'CMS Users')

{{-- TITLE CONTENT --}}
@section('title_content', 'CMS Users')

{{-- CONTENT --}}
@section('content')
    @include('includes.nav-page')

    {{-- Data Wrapper --}}
    <div id="user_list"></div>
    <button id="btnMore" class="btn qraved-btn-primary btn-block d-none">
        more..
    </button>
    @include('includes.loading')
@endsection

{{-- MODAL --}}
@section('modal')
    <!-- Modal -->
    <div class="modal fade" id="modalUser" tabindex="-1" role="dialog" aria-labelledby="modalUserLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUserLabel">Add / Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formUser" method="POST">
                        <input type="hidden" name="id" id="id" value="">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="" tabindex="1">
                            <span id="name_invalid" class="invalid-feedback" role="alert"></span>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" name="email" id="email" class="form-control" value="" tabindex="2">
                            <span id="email_invalid" class="invalid-feedback" role="alert"></span>
                        </div>
                        <div id="password-wrapper" class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group mb-3">
                                <input type="password" name="password" id="password" class="form-control" tabindex="3">
                                <div class="input-group-append">
                                    <button class="btn btn-light custom-border" type="button" id="view-password">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <span id="password_invalid" class="invalid-feedback d-block" role="alert"></span>
                        </div>
                        <div id="password-confirm-wrapper" class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <div class="input-group mb-3">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" tabindex="4">
                                <div class="input-group-append">
                                    <button class="btn btn-light custom-border" type="button" id="view-password-confirm">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" id="btnClose" class="btn btn-light" data-dismiss="modal" tabindex="6">Close</button>
                    <button type="button" id="btnDelete" class="btn qraved-btn-danger d-none">
                        <i class="fa fa-trash"></i> Delete
                    </button>
                    <button type="submit" id="btnSave" form="formUser" class="btn qraved-btn-primary" tabindex="5">
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
        let view_password = false;
        let view_password_confirm = false;

        function loading() {
            $('#loading').toggleClass('d-none');
        }

        function showValidation(errors) {
            for (const error in errors) {
                $(`#${error}`).addClass('is-invalid');
                $('#'+error+'_invalid').html(errors[error][0]);
            }
        }

        function searching() {
            const search = $('#search').val();
            let query = search === '' ? '' : `?search=${search}`;
            lastId = 0;
            hasNext = false;
            loadList(query);
        }

        function loadList(query = '') {
            loading();
            $.get(`{{ route('cms.user.cms.index') }}/get-users/${lastId}${query}`, function (res) {
                if (lastId == 0) {
                    $('#user_list').html(res.html);
                }
                else {
                    $('#user_list').append(res.html);
                }
                lastId = res.lastId;
                hasNext = res.hasNext;
                loading();

                if (hasNext) {
                    $.get(`{{ secure_url(route('cms.user.cms.index', [], false)) }}/get-users/${lastId}`, function (res) {
                        if (lastId == 0) {
                            $('#user_list').html(res.html);
                        }
                    });
                }
            });
        });

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

        $('#formUser').submit(function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{ route('cms.user.cms.store') }}",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    lastId = 0;
                    hasNext = false;
                    Swal.fire({
                        icon : response.success ? 'success' : 'error',
                        title: response.success ? `Success` : 'Failed',
                        text : response.message,
                        timer: 3000,
                        timerProgressBar: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#modalUser').modal('hide');
                            loadList();
                        }
                        else if (result.isDismissed) {
                            $('#modalUser').modal('hide');
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

        $('body').on('click', '#btnSearch', function () {
            searching();
        });

        $('body').on('click', '#btnMore', function () {
            loadList();
        });

        $(document).ready(() => {
            loading();
            $.get(`{{ secure_url(route('cms.user.cms.index', [], false)) }}/get-users/${lastId}`, function (res) {
                if (lastId == 0) {
                    $('#user_list').html(res.html);
                }
                else {
                    $('#user_list').append(res.html);
                }
                lastId += res.lastId;
                hasNext = res.hasNext;
                loading();
            });
        });


    </script>
@endsection
