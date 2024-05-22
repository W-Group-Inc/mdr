@extends('layouts.app')
@section('css')
    <link href="css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
@endsection

@section('content')
    @if (Auth::user()->account_role == 0)
        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Users</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins">{{ $totalUsers }}</h1>
                            <small>Total Users</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Departments</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins">{{ $totalDepartments }}</h1>
                            <small>Total Departments</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @elseif(Auth::user()->account_role == 1)
        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            @if (Session::has('errors'))
                                <div class="alert alert-danger">
                                    @foreach (Session::get('errors') as $errors)
                                        {{ $errors }}<br>
                                    @endforeach
                                </div>
                            @endif
        
                            <form action="" method="get" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <input type="month" name="yearAndMonth" id="yearAndMonth" class="form-control input-sm" max="{{ date('Y-m') }}" value="{{ $yearAndMonth }}">
                                    </div>
                                    <div class="col-lg-3">
                                        <button class="btn btn-sm btn-primary">Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <canvas id="lineChart" height="70"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            
                            <table class="table table-striped table-bordered table-hover" id="mdrStatusTable">
                                <thead>
                                    <tr>
                                        <th>Department</th>
                                        <th>Action</th>
                                        <th>Status</th>
                                        <th>Due Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mdrStatus as $key=>$data)
                                        <tr>
                                            <td>{{ $data['department'] }}</td>
                                            <td>{{ $data['action'] }}</td>
                                            <td>{{ $data['status'] }}</td>
                                            <td>{{ $data['deadline'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif(Auth::user()->account_role == 2)
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <form action="" method="get" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-lg-3">
                                    <input type="text" name="year" id="year" class="form-control input-sm" maxlength="4" value="{{ $years }}" placeholder="Enter a year">
                                </div>
                                <div class="col-lg-3">
                                    <button class="btn btn-sm btn-primary">Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <div>
                            <canvas id="barChart" height="140"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection

@push('scripts')
<!-- Mainly scripts -->
<script src="js/plugins/dataTables/datatables.min.js"></script>
<!-- ChartJS-->
<script src="{{ asset('js/plugins/chartJs/Chart.min.js') }}"></script>
{{-- chosen --}}
<script src="js/plugins/chosen/chosen.jquery.js"></script>
@if(Auth::user()->account_role == 1)
<script>
    $(document).ready(function() {
        var dept = {!! json_encode(array_keys($dashboardData)) !!}
        var data = {!! json_encode(array_values($dashboardData)) !!}

        var ctx = document.getElementById("lineChart").getContext("2d");
        
        var lineData = {
            labels: dept,
            datasets: [
                {
                    label: "Total Rating",
                    borderColor: "rgba(26,179,148,0.7)",
                    data: data
                },
            ]
        };

        var lineOptions = {
            responsive: true
        };

        new Chart(
            ctx, 
            {
                type: 'line', 
                data: lineData, 
                options:lineOptions
            }
        );

        $('#mdrSummaryTable').DataTable({
            pageLength: 10,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                // { extend: 'copy'},
                // {extend: 'csv'},
                // {extend: 'excel', title: 'ExampleFile'},
                {extend: 'pdf', title: 'MDR Summary'},

                {extend: 'print',
                    customize: function (win){
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');

                        $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                }
                }
            ]
        });
        
        $('#mdrStatusTable').DataTable({
            pageLength: 10,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                // { extend: 'copy'},
                // {extend: 'csv'},
                // {extend: 'excel', title: 'ExampleFile'},
                {extend: 'pdf', title: 'MDR Summary'},

                {extend: 'print',
                    customize: function (win){
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');

                        $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                }
                }
            ]
        });

        $("[name='department']").chosen({width: "100%"});
    })
</script>
@endif
@if(Auth::user()->account_role == 2)
<script>
    var month = {!! json_encode($month) !!}

    var data = {!! json_encode($data) !!}

    var barData = {
        labels: month,
        datasets: [
            {
                label: "Total Rating",
                backgroundColor: 'rgba(26,179,148,0.5)',
                borderColor: "rgba(26,179,148,0.7)",
                pointBackgroundColor: "rgba(26,179,148,1)",
                pointBorderColor: "#fff",
                data: data
            }
        ]
    };

    var barOptions = {
        responsive: true
    };

    var ctx2 = document.getElementById("barChart").getContext("2d");
    new Chart(ctx2, {type: 'bar', data: barData, options:barOptions});
</script>
@endif
@endpush
