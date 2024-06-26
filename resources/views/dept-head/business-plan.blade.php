<div class="col-lg-12">
    <div class="ibox float-e-margins" style="margin-top: 10px;">
        <div class="ibox-content">
            <div class="table-responsive">
                <p><b>III:</b> <span class="period">Business Plan (Accomplished)</span></p>
                @if (Session::has('bpErrors'))
                    <div class="alert alert-danger">
                        @foreach (Session::get('bpErrors') as $errors)
                            {{ $errors }}<br>
                        @endforeach
                    </div>
                @endif
                @if($businessPlanCount < 3)
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addBusinessPlanModal">Add Business Plan</button>
                @endif
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th rowspan="2">Activities</th>
                            <th colspan="2">Base On Planned</th>
                            <th rowspan="2">Proof of Completion</th>
                            <th rowspan="2">Start Date</th>
                            <th rowspan="2">Actual Date of Completion</th>
                            <th rowspan="2">Actions</th>
                        </tr>
                        <tr>
                            <th>Yes</th>
                            <th>No</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($businessPlanList) > 0)
                            @foreach ($businessPlanList as $businessPlanData)
                                @foreach ($businessPlanData->businessPlans as $item)
                                    <tr>
                                        <td>{{ $item->activities }}</td>
                                        <td>{!! $item->isBasedOnPlanned == 1 ? '<i class="fa fa-check"></i>' : '' !!}</td>
                                        <td>{!! $item->isBasedOnPlanned == 0 ? '<i class="fa fa-check"></i>' : '' !!}</td>
                                        <td>{{ $item->proof_of_completion }}</td>
                                        <td>{{ date('F d, Y', strtotime($item->start_date )) }}</td>
                                        <td>{{ date('F d, Y', strtotime($item->end_date )) }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#businessPlanEdit-{{ $item->id }}">
                                                <i class="fa fa-pencil"></i>
                                            </button>
        
                                            <form action="deleteBusinessPlan/{{ $item->id }}" method="post">
                                                @csrf
        
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center">No data available</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addBusinessPlanModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title">Add Business Plans</h1>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form action="/addBusinessPlan" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="activities">Activities</label>
                                <input type="text" name="activities" id="activities" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <label for="baseOnPlanned">Base on Planned</label>
                                <select name="baseOnPlanned" id="baseOnPlanned" class="form-control input-sm">
                                    <option value="">- Is Base on Planned ? -</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="proofOfCompletion">Proof of Completion</label>
                                <input type="text" name="proofOfCompletion" id="proofOfCompletion" class="form-control input-sm">
                            </div>
                            <div class="form-group" id="startDate">
                                <label for="startDate">Start Date</label>
                                <div class="input-group date">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control input-sm" name="startDate" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group" id="actualDate">
                                <label for="actualDate">Actual Date</label>
                                <div class="input-group date">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control input-sm" name="actualDate" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-sm btn-primary btn-block">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach ($businessPlanList as $businessPlanData)
    @foreach ($businessPlanData->businessPlans as $item)
        <div class="modal fade" id="businessPlanEdit-{{ $item->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title">Edit Business Plan</h1>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <form action="/updateBusinessPlan/{{ $item->id }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="activities">Activities</label>
                                        <input type="text" name="activities" id="activities" class="form-control input-sm" value="{{ $item->activities }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="baseOnPlanned">Base on Planned</label>
                                        <select name="baseOnPlanned" id="baseOnPlanned" class="form-control input-sm">
                                            <option value="">- Is Base on Planned ? -</option>
                                            <option value="1" {{ $item->isBasedOnPlanned == 1 ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ $item->isBasedOnPlanned == 0 ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="proofOfCompletion">Proof of Completion</label>
                                        <input type="text" name="proofOfCompletion" id="proofOfCompletion" class="form-control input-sm" value="{{ $item->proof_of_completion }}">
                                    </div>
                                    <div class="form-group" id="startDate">
                                        <label for="startDate">Start Date</label>
                                        <div class="input-group date">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" class="form-control input-sm" name="startDate" autocomplete="off" value="{{ $item->start_date }}">
                                        </div>
                                    </div>
                                    <div class="form-group" id="actualDate">
                                        <label for="actualDate">Actual Date</label>
                                        <div class="input-group date">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" class="form-control input-sm" name="actualDate" autocomplete="off" value="{{ $item->end_date }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-sm btn-primary btn-block">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endforeach

@push('scripts')
<script src="js/plugins/datapicker/bootstrap-datepicker.js"></script>
<script>
    $(document).ready(function() {
        var dateToday = new Date();

        $('#startDate .input-group.date').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
            startDate: dateToday,
        });

        $('#actualDate .input-group.date').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
            startDate: dateToday,
        });
    })
</script>
@endpush