@extends('layouts.main')

{{-- META --}}
@section('meta')

@endsection

{{-- CSS --}}
@section('css')
    <style>

    </style>
@endsection

{{-- TITLE --}}
@section('title', 'Qraved Users')

{{-- TITLE CONTENT --}}
@section('title_content', 'Qraved Users')

{{-- CONTENT --}}
@section('content')
    @include('includes.nav-page', ['showBtnNew' => false, 'showBtnExport' => true])

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
    <div class="modal fade" id="modalDetailUser" tabindex="-1" role="dialog" aria-labelledby="modalDetailUserLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailUserLabel">Detail User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="contact">Contact</label>
                        <input type="text" name="contact" id="contact" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <input type="text" name="gender" id="gender" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="birth_date">Birth Date</label>
                        <input type="date" name="birth_date" id="birth_date" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="interest">Interest</label>
                        <textarea name="interest" id="interest" cols="30" rows="5" class="form-control" readonly style="resize: none"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="job">Job</label>
                        <input type="text" name="job" id="job" class="form-control" readonly>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" id="btnClose" class="btn btn-light" data-dismiss="modal" tabindex="6">Close</button>
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
            $.get(`{{ route('cms.user.qraved.index') }}/get-users/${lastId}${query}`, function (res) {
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
                    $('#btnMore').removeClass('d-none');
                }
                else {
                    $('#btnMore').addClass('d-none');
                }
            });
        }

        $('body').on('click', '#btnExport', function () {
            location.href = "{{ route('cms.user.qraved.export-to-csv') }}";
        });

        $('body').on('click', '#btnDetail', function () {
            const id = $(this).attr('data-id');
            $.get(`{{ route('cms.user.qraved.index') }}/get-user/${id}`, function (res) {
                $('#email').val(res.email);
                $('#contact').val(res.contact);
                $('#gender').val(res.gender);
                $('#birth_date').val(res.birth_date);
                $('#interest').val(res.interest);
                $('#job').val(res.job);
                $('#modalDetailUser').modal('show');
            });
        });

        $('body').on('click', '#btnDelete', function () {
            const id = $(this).attr('data-id');
            const email = $(this).attr('data-email');
            Swal.fire({
                title: `You are sure to delete user "${email}"?`,
                showDenyButton: true,
                showConfirmButton: false,
                showCancelButton: true,
                denyButtonText: `Delete`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isDenied) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('cms.user.qraved.delete') }}",
                        data: {
                            id: id
                        },
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

        $('body').on('click', '#btnSearch', function () {
            searching();
        });

        $(document).ready(() => {
            loadList();
        });


    </script>
@endsection
