@extends('layouts.main')

{{-- META --}}
@section('meta')

@endsection

{{-- CSS --}}
@section('css')
    <style>
        #filter {
            position: fixed;
            right: 1.5rem;
            width: 21rem;
            z-index: 1020;
        }

        #under_develop {
            position: fixed;
            right: 1rem;
            width: 22rem;
            height: 19rem;
            z-index: 1030;
            background-color: #ffffffbf;
            text-align: center;
            font-weight: 900;
            font-size: 2rem;
            color: #dd3333;
            padding-top: 5rem;
            background-size: 80%;
            background-image: url("{{ asset('assets/img/undraw_under_construction_46pa.svg') }}");
            background-repeat: no-repeat;
            background-position: center;
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


        <div id="under_develop">
            Under Development
        </div>

        <div class="col-lg-4">
            <div class="accordion shadow" id="filter">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <a class="text-dark text-decoration-none d-flex justify-content-between" href="javascript:void(0)" data-toggle="collapse"
                           data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Search
                            <i class="fas fa-chevron-down mt-1"></i>
                        </a>
                    </div>

                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                        data-parent="#filter">
                        <div class="card-body">
                            <select class="form-control mb-2" name="search_field" id="search_field">
                                <option value="user">User</option>
                                <option value="resto">Resto</option>
                                <option value="datetime">Date time</option>
                                <option value="action">Action</option>
                            </select>
                            <input type="search" class="form-control" name="search" id="search" placeholder="Search..." style="width: 100%;" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <a class="text-dark text-decoration-none d-flex justify-content-between" href="javascript:void(0)" data-toggle="collapse"
                           data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                            Sort
                            <i class="fas fa-chevron-down mt-1"></i>
                        </a>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#filter">
                        <div class="card-body row">
                            <div class="col-8">
                                <select class="form-control mb-2" name="sort_field" id="sort_field">
                                    <option value="user">User</option>
                                    <option value="resto">Resto</option>
                                    <option value="datetime" selected>Date time</option>
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
                        <h2 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Collapsible Group Item #3
                            </button>
                        </h2>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#filter">
                        <div class="card-body">
                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.
                            3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt
                            laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin
                            coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes
                            anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings
                            occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard
                            of them accusamus labore sustainable VHS.
                        </div>
                    </div>
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
        let lastId = 0;
        let hasNext = true;

        $('body').on('change', '#search_field', function () {
            const value = $(this).val();
            if (value === 'datetime') {
                $('#search').attr('type', 'datetime-local');
            }
            else {
                $('#search').attr('type', 'search');
            }
            $('#search').focus();
        });

        $('body').on('click', '#btnMore', function () {
            loadList();
        });

        function queryFilterBuilder() {
            let sort = $('#sort'),
                search = $('#search'),
                sort_field = $('#sort_field'),
                search_field = $('#search_field'),
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
            return query;
        }

        function loading() {
            $('#loading').toggleClass('d-none');
        }

        function loadList() {
            loading();
            $.get(`{{ route('cms.log.index') }}/get-logs/${lastId}?${queryFilterBuilder()}`, function (res) {
                if (lastId == 0) {
                    $('#log_list').html(res.html);
                }
                else {
                    $('#log_list').append(res.html);
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

        $(document).ready(() => {
            loadList();
        });
    </script>
@endsection
