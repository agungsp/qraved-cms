@extends('layouts.main')

{{-- META --}}
@section('meta')

@endsection

{{-- CSS --}}
@section('css')

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
                <div class="col-2">
                    <div class="form-group">
                        <label for="date_start">Date Start</label>
                        <input type="date" name="date_start" id="date_start" class="form-control" value="{{ now()->toDateString() }}">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label for="date_end">Date End</label>
                        <input type="date" name="date_end" id="date_end" class="form-control" value="{{ now()->toDateString() }}" max="{{ now()->toDateString() }}">
                    </div>
                </div>
                <div class="col-auto">
                    <button id="btnApply" class="btn qraved-btn-primary" style="margin-top: 2rem;">
                        Apply
                    </button>
                </div>
            </div>
            <div class="row justify-content-center mb-5">
                <div class="col-12">
                    <div class="form-group" style="width: 10rem;">
                        <label for="resto">Restaurant</label>
                        <select name="resto" id="resto" class="form-control">
                            <option value="0">All</option>
                            @foreach ($restaurants as $resto)
                                <option value="{{ $resto->id }}">{{ $resto->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-9">
                    <canvas id="pie_chart" width="400" height="200"></canvas>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-12">
                    <h5>
                        Berdasarkan action "User memulai memainkan game"
                    </h5>
                </div>
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
        let pie_chart_ctx = null;
        let pie_chart = null;
        let config = {
            type: 'pie',
            data: {
                labels: [],
                datasets: []
            },
            options: {
                responsive: true,
                legend: {
                    position: 'left'
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
            let date_start = $('#date_start'),
                date_end = $('#date_end'),
                filter_resto = $('#resto'),
                query = '';

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
        }

        $('body').on('click', '#btnApply', function () {
            get_pie_chart();
            get_table();
        });

        $('body').on('change', '#date_start', function () {
            if ($(this).val() > $('#date_end').val()) {
                $('#date_end').val($(this).val());
            }
            $('#date_end').attr('max', $(this).val());
        });

        $('body').on('change', '#resto', function () {
            get_pie_chart();
        });

        $(document).ready(() => {
            // Chart.register(ChartDataLabels);
            get_pie_chart();
            get_table();
        });
    </script>
@endsection
