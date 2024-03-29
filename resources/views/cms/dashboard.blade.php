@extends('layouts.main')

{{-- META --}}
@section('meta')

@endsection

{{-- CSS --}}
@section('css')
    <style>
        .chart-container {
            position: relative;
            margin: auto;
            height: 80vh;
            width: 80vw;
        }
    </style>
@endsection

{{-- TITLE --}}
@section('title', 'Dashboard')

{{-- TITLE CONTENT --}}
@section('title_content', 'Dashboard')

{{-- CONTENT --}}
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row mb-5">
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="date_start">Date Start</label>
                        <input type="date" name="date_start" id="date_start" class="form-control" value="{{ now()->toDateString() }}">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="date_end">Date End</label>
                        <input type="date" name="date_end" id="date_end" class="form-control" value="{{ now()->toDateString() }}" min="{{ now()->toDateString() }}">
                    </div>
                </div>
                <div class="col-lg-auto">
                    <button id="btnApply" class="btn qraved-btn-primary @mobile btn-block @endmobile"
                        @tablet
                            style="margin-top: 2rem;"
                        @endtablet
                        @desktop
                            style="margin-top: 2rem;"
                        @endtablet
                        >
                        Apply
                    </button>
                </div>
            </div>
            <div class="row justify-content-center mb-5">
                <div class="col-12">
                    <div class="form-group" @if (!Browser::isMobile())  style="width: 10rem;" @endif>
                        <label for="resto">Restaurant</label>
                        <select name="resto" id="resto" class="form-control">
                            <option value="0">All</option>
                            @foreach ($restaurants as $resto)
                                <option value="{{ $resto->id }}">{{ $resto->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 chart-container">
                    <canvas id="pie_chart" width="400"></canvas>
                </div>
            </div>

            <hr>

            <div class="row mt-5 justify-content-between">
                <div class="col-xl-7 col-md-7 col-xs-12">
                    <div class="form-group">
                        <label for="action">Cari berdasarkan action</label>
                        <select name="action" id="action" class="form-control">
                            @foreach ($actions as $action)
                                <option value="{{ $action }}">{{ $action }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-2 col-md-3 col-xs-12">
                    <a href="javascript:void(0)" id="btnExport" class="btn btn-success btn-block" @if (!Browser::isMobile()) style="margin-top: 2rem;" @endif>
                        <i class="fas fa-file-csv"></i> Export
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <table class="table table-stripped table-hover">
                        <thead>
                            <tr>
                                <th>Restauran</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody id="table">

                        </tbody>
                    </table>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
    <script>
        let date_start = $('#date_start'),
            date_end = $('#date_end'),
            filter_resto = $('#resto'),
            filter_action = $('#action'),
            query = '';
        let pie_chart_ctx = null;
        let pie_chart = null;
        let config = {
            type: 'pie',
            data: {
                labels: [],
                datasets: []
            },
            options: {
                maintainAspectRatio: false,
                legend: {
                    position: '{{ Browser::isMobile() ? 'bottom' : 'left' }}'
                },
                tooltips: {
                    enabled: false
                },
                plugins: {
                    datalabels: {
                        formatter: (value, ctx) => {
                                let sum = 0;
                                let dataArr = ctx.chart.data.datasets[0].data;
                            dataArr.map(data => {
                                sum += data;
                            });
                            let percentage = (value*100 / sum).toFixed(2)+"% | "+value;
                            return percentage;
                        },
                        color: '#fff',
                    }
                }
            }
        }

        function queryFilterBuilder() {
            if (date_start.val() !== '') {
                if (query !== '') query += '&';
                query += `date_start=${date_start.val()}`;
            }

            if (date_end.val() !== '') {
                if (query !== '') query += '&';
                query += `date_end=${date_end.val()}`;
            }

            if (filter_resto.val() !== '') {
                if (query !== '') query += '&';
                query += `filter_resto=${filter_resto.val()}`;
            }

            if (filter_action.val() !== '') {
                if (query !== '') query += '&';
                query += `filter_action=${filter_action.val()}`;
            }

            return query;
        }

        function get_pie_chart() {
            if (pie_chart_ctx == null) pie_chart_ctx = $('#pie_chart')[0].getContext('2d');
            if (pie_chart == null) pie_chart = new Chart(pie_chart_ctx, config);

            $.get(`{{ route('cms.dashboard.get-chart') }}?${queryFilterBuilder()}`, function (result) {
                config.data.labels = result.labels;
                config.data.datasets = result.datasets;
                pie_chart.update();
            });
        }

        function get_table() {
            $.get(`{{ route('cms.dashboard.get-table') }}?${queryFilterBuilder()}`, function (result) {
                $('#table').html(result);
            });
            $('#btnExport').attr('href', `{{ route('cms.dashboard.export-table') }}?${queryFilterBuilder()}`);
        }

        $('body').on('click', '#btnApply', function () {
            get_pie_chart();
            get_table();
        });

        $('body').on('change', '#date_start', function () {
            if ($(this).val() > $('#date_end').val()) {
                $('#date_end').val($(this).val());
            }
            $('#date_end').attr('min', $(this).val());
        });

        $('body').on('change', '#action', function () {
            get_table();
        });

        $('body').on('change', '#resto', function () {
            get_pie_chart();
        });

        $(document).ready(() => {
            get_pie_chart();
            get_table();
        });
    </script>
@endsection
