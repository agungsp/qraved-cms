@extends('layouts.main')

{{-- META --}}
@section('meta')

@endsection

{{-- CSS --}}
@section('css')
    <link rel="stylesheet" href="{{ secure_asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

{{-- TITLE --}}
@section('title', 'Restaurants')

{{-- TITLE CONTENT --}}
@section('title_content', 'Restaurants')

{{-- CONTENT --}}
@section('content')
    @include('includes.nav-page')

    {{-- Data Wrapper --}}
    <div id="resto_list"></div>
    <button id="btnMore" class="btn qraved-btn-primary btn-block d-none">
        more..
    </button>
    @include('includes.loading')
@endsection

{{-- MODAL --}}
@section('modal')
    <!-- Modal -->
    <div class="modal fade" id="modalRestaurant" tabindex="-1" role="dialog" aria-labelledby="modalRestaurantLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRestaurantLabel">Add/Edit Restaurant</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formRestaurant">
                        <input type="hidden" name="id" id="id" value="">
                        <div class="form-group">
                            <label for="qraved_mapping_id">Qraved ID <span class="small text-muted font-italic">(optional)</span></label>
                            <input type="text" name="qraved_mapping_id" id="qraved_mapping_id" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <span id="name_invalid" class="invalid-feedback" role="alert"></span>
                        </div>
                        <div class="form-group">
                            <label for="alias">Alias</label>
                            <input type="text" name="alias" id="alias" class="form-control">
                            <span id="alias_invalid" class="invalid-feedback" role="alert"></span>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" name="address" id="address" class="form-control">
                            <span id="address_invalid" class="invalid-feedback" role="alert"></span>
                        </div>
                        <div class="form-group">
                            <label for="contact">Contact</label>
                            <input type="text" pattern="[0-9]" name="contact" id="contact" class="form-control">
                            <span id="contact_invalid" class="invalid-feedback" role="alert"></span>
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

    <!-- Modal -->
    <div class="modal fade" id="modalLink" tabindex="-1" role="dialog" aria-labelledby="modalLinkLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLinkLabel">Connect to QR</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formLink" method="POST">
                        <div class="form-group">
                            <label for="linkResto">Resto</label>
                            <input type="text" name="linkRestoName" id="linkRestoName" class="form-control bg-white" disabled>
                            <input type="hidden" name="linkRestoId" id="linkRestoId">
                        </div>
                        <div class="form-group">
                            <label>Available QR Code</label>
                            <select class="form-control select2" name="linkCode" id="linkCode" style="width: 100%;"></select>
                            <span id="linkCode_invalid" class="invalid-feedback d-block" role="alert"></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" id="btnClose" class="btn btn-light" data-dismiss="modal">Close</button>
                    <button type="submit" form="formLink" class="btn qraved-btn-primary">
                        <i class="fa fa-save"></i> Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modelQRCodePreview" tabindex="-1" role="dialog" aria-labelledby="modelQRCodePreviewLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelQRCodePreviewLabel">QR Code Preview</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="qr_code_preview_wrapper"></div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" id="btnClose" class="btn btn-light" data-dismiss="modal">Close</button>
                    <a href="" target="_blank" type="button" id="btnExport" class="btn qraved-btn-primary">
                        <i class="fas fa-file-pdf"></i> Export
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- JS --}}
@section('js')
    <script src="{{ secure_asset('assets/plugins/cleave/cleave.min.js') }}"></script>
    <script src="{{ secure_asset('assets/plugins/cleave/addons/cleave-phone.id.js') }}"></script>
    <script src="{{ secure_asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        let lastId = 0;
        let hasNext = true;


        function loading() {
            $('#loading').toggleClass('d-none');
        }

        $('body').on('click', '#btnNew', function () {
            $('#id').val('');
            $('#btnClose').removeClass('d-none');
            $('#btnDelete').addClass('d-none');
            $('#formRestaurant').trigger('reset');
            $('#modalRestaurantLabel').html('Add Restaurant');
            $('#modalRestaurant').modal('show');
        });

        $('body').on('click', '#btnLink', function () {
            const id = $(this).attr('data-id');
            const name = $(this).attr('data-name');
            $('#linkRestoId').val(id);
            $('#linkRestoName').val(name);
            $('#linkCode').val('');
            $('#modalLink').modal('show');
        });

        $('#modalRestaurant').on('shown.bs.modal', function () {
            $('#qraved_mapping_id').focus();
        });

        $('body').on('click', '#btnEdit', function () {
            const id = $(this).attr('data-id');
            $.get(`{{ secure_url(route('cms.restaurant.index', [], false)) }}/get-restaurant/${id}`, function (res) {
                $('#id').val(res.id);
                $('#qraved_mapping_id').val(res.qraved_resto_mapping_id == 0 ? '' : res.qraved_resto_mapping_id);
                $('#name').val(res.name);
                $('#alias').val(res.alias);
                $('#address').val(res.address);
                $('#contact').val(res.contact);
                $('#modalRestaurantLabel').html('Edit Restaurant');
                $('#btnClose').addClass('d-none');
                $('#btnDelete').removeClass('d-none');
                $('#modalRestaurant').modal('show');
            });
        });

        $('body').on('click', '#btnSave', function () {
            $.ajax({
                type: "POST",
                url: "{{ secure_url(route('cms.restaurant.store', [], false)) }}",
                data: $('#formRestaurant').serialize(),
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
                            $('#modalRestaurant').modal('hide');
                            loadList();
                        }
                        else if (result.isDismissed) {
                            $('#modalRestaurant').modal('hide');
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

        $('#formLink').submit(function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: "{{ route('cms.restaurant.qr-connect') }}",
                data: formData,
                contentType: false,
                processData: false,
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
                            $('#modalLink').modal('hide');
                            loadList();
                        }
                        else if (result.isDismissed) {
                            $('#modalLink').modal('hide');
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
            const name = $('#name').val();
            Swal.fire({
                title: `You are sure to delete restaurant "${name}"?`,
                showDenyButton: true,
                showConfirmButton: false,
                showCancelButton: true,
                denyButtonText: `Delete`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isDenied) {
                    $.ajax({
                        type: "POST",
                        url: "{{ secure_url(route('cms.restaurant.delete', [], false)) }}",
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
                                    $('#modalRestaurant').modal('hide');
                                    loadList();
                                }
                                else if (result.isDismissed) {
                                    $('#modalRestaurant').modal('hide');
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
            });
        });

        $('body').on('click', '#btnShowQR', function () {
            const id = $(this).attr('data-id');
            $.get(`{{ route('cms.restaurant.index') }}/qr-code-preview/${id}`, function (res) {
                $('#qr_code_preview_wrapper').html(res);
                $('#btnExport').attr('href', `restaurants/export/${id}`);
                $('#modelQRCodePreview').modal('show');
            });
        });

        $('body').on('click', '#btnSearch', function () {
            searching();
        });

        $('body').on('click', '#btnMore', function () {
            loadList();
        });

        function searching() {
            const search = $('#search').val();
            let query = search === '' ? '' : `?search=${search}`;
            lastId = 0;
            hasNext = false;
            loadList(query);
        }

        function showValidation(errors) {
            for (const error in errors) {
                $(`#${error}`).addClass('is-invalid');
                $('#'+error+'_invalid').html(errors[error][0]);
            }
        }

        function loadList(query = '') {
            loading();
            $.get(`{{ secure_url(route('cms.restaurant.index', [], false)) }}/get-restaurants/${lastId}`, function (res) {
                if (lastId == 0) {
                    $('#resto_list').html(res.html);
                }
                else {
                    $('#resto_list').append(res.html);
                }
                lastId = res.lastId;
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

        function qrData() {
            $.get("{{ route('cms.restaurant.available-qr') }}", function (res) {
                let html = '';
                html += `<option></option>`;
                res.forEach(row => {
                    let text = row.text.split("{{ SettingHelper::getAll()['qr_prefix'] }}");
                    html += `<option value="${row.id}">${text[1] ?? row.text}</option>`;
                });
                $('#linkCode').html(html);
            });
        }

        $(document).ready(() => {
            loadList();
            $('.select2').select2({
                dropdownParent: $('#modalLink'),
                placeholder: "Select a code",
            });
        });

        const contact = new Cleave('#contact', {
            phone: true,
            phoneRegionCode: 'id'
        });
    </script>
@endsection
