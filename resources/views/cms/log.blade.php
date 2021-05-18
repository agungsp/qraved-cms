@extends('layouts.main')

{{-- META --}}
@section('meta')

@endsection

{{-- CSS --}}
@section('css')
    <style>
        #filter {
            position: sticky;
            right: 1.5rem;
            top: 75px;
            /* width: 21rem; */
            z-index: 1020;
        }
    </style>
@endsection

{{-- TITLE --}}
@section('title', 'Logs')

{{-- TITLE CONTENT --}}
@section('title_content', 'Logs')

{{-- CONTENT --}}
@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div id="log_list"></div>
            <button id="btnMore" class="btn qraved-btn-primary btn-block d-none">
                more..
            </button>
            @include('includes.loading')
        </div>

        <div class="col-lg-4">
            <div id="filter">
                <div class="accordion shadow">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <a class="text-dark text-decoration-none d-flex justify-content-between" href="javascript:void(0)" data-toggle="collapse"
                               data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <span><i class="fa fa-search mr-1"></i> Search</span>
                                <i class="fas fa-chevron-down mt-1"></i>
                            </a>
                        </div>

                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                            data-parent="#filter">
                            <div class="card-body">
                                <select class="form-control mb-2" name="search_field" id="search_field">
                                    <option value="user.email">User</option>
                                    <option value="restaurant.name">Resto</option>
                                    <option value="action">Action</option>
                                </select>
                                <div class="row">
                                    <div class="col pr-1">
                                        <input type="search" class="form-control" name="search" id="search" placeholder="Search..." autocomplete="off">
                                    </div>
                                    <div class="col-auto pl-1">
                                        <button id="btnSearch" class="btn qraved-btn-primary">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <a class="text-dark text-decoration-none d-flex justify-content-between" href="javascript:void(0)" data-toggle="collapse"
                               data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                <span><i class="fas fa-sort mr-2"></i> Sort</span>
                                <i class="fas fa-chevron-down mt-1"></i>
                            </a>
                        </div>
                        <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#filter">
                            <div class="card-body row">
                                <div class="col-8">
                                    <select class="form-control mb-2" name="sort_field" id="sort_field">
                                        <option value="user.email">User</option>
                                        <option value="restaurant.name">Resto</option>
                                        <option value="created_at" selected>Date time</option>
                                        <option value="action">Action</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <select class="form-control mb-2" name="sort" id="sort">
                                        <option value="asc">Asc</option>
                                        <option value="desc" selected>Desc</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingThree">
                            <a class="text-dark text-decoration-none d-flex justify-content-between" href="javascript:void(0)" data-toggle="collapse"
                               data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                <span><i class="fas fa-calendar-alt mr-1"></i> Date Range</span>
                                <i class="fas fa-chevron-down mt-1"></i>
                            </a>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                            data-parent="#filter">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="date_start">Start</label>
                                    <input type="date" value="{{ now()->subDays(7)->toDateString() }}" class="form-control" name="date_start" id="date_start">
                                </div>
                                <div class="form-group">
                                    <label for="date_end">End</label>
                                    <input type="date" value="{{ now()->toDateString() }}" max="{{ now()->subDays(7)->toDateString() }}" class="form-control" name="date_end" id="date_end">
                                </div>
                                <button id="btnApply" class="btn qraved-btn-primary btn-block">
                                    Apply
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <a id="btnExport" href="#" class="btn btn-success btn-block mt-3 shadow">
                    <i class="fas fa-file-csv"></i> Export to CSV
                </a>
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
        let lastId = 0;
        let hasNext = false;
        let refreshCache = false;

        $('body').on('click', '#btnMore', function () {
            loadList();
        });

        $('body').on('change', '#sort_field', function () {
            loadList();
        });

        $('body').on('change', '#sort', function () {
            loadList();
        });

        $('body').on('click', '#btnSearch', function () {
            loadList();
        });

        $('body').on('change', '#date_start', function () {
            $('#date_end').attr('max', $(this).val());
        });

        $('body').on('click', '#btnApply', function () {
            refreshCache = true;
            loadList();
            refreshCache = false;
        });

        function queryFilterBuilder() {
            let sort = $('#sort'),
                search = $('#search'),
                sort_field = $('#sort_field'),
                search_field = $('#search_field'),
                date_start = $('#date_start'),
                date_end = $('#date_end'),
                query = '';

            if (search.val() !== '') {
                query += `search=${search.val()}`;
                query += `&search_field=${search_field.val()}`;
            }

            if (sort_field.val() !== '') {
                if (query !== '') query += '&';
                query += `sort_field=${sort_field.val()}`;
            }

            if (sort_field.val() !== '') {
                if (query !== '') query += '&';
                query += `sort=${sort.val()}`;
            }

            if (date_start.val() !== '') {
                if (query !== '') query += '&';
                query += `date_start=${date_start.val()}`;
            }

            if (date_end.val() !== '') {
                if (query !== '') query += '&';
                query += `date_end=${date_end.val()}`;
            }

            if (query !== '') query += '&';
            query += `refresh_cache=${refreshCache}`;

            return query;
        }

        function loading() {
            $('#loading').toggleClass('d-none');
        }

        function loadList() {
            $('#log_list').html('');
            loading();
            $.get(`{{ route('cms.log.index') }}/get-logs?${queryFilterBuilder()}`, function (res) {
                if (lastId == 0) {
                    $('#log_list').html(res.html);
                }
                else {
                    $('#log_list').append(res.html);
                }

                loading();

                if (hasNext) {
                    $('#btnMore').removeClass('d-none');
                }
                else {
                    $('#btnMore').addClass('d-none');
                }

                exportCsvUrl();
            });
        }

        function exportCsvUrl() {
            $('#btnExport').attr('href', `{{ route('cms.log.index') }}/export-to-csv?${queryFilterBuilder()}`);
        }

        $(document).ready(() => {
            loadList();
        });
    </script>
@endsection
