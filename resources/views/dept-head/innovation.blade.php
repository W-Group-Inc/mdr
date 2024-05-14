@if($departmentKpiData->name == "Innovations (Accomplished)")
<div class="col-lg-12">
    <div class="ibox float-e-margins" style="margin-top: 10px;">
        <div class="ibox-content">
            @if (Session::has('errors'))
                <div class="alert alert-danger">
                    @foreach (Session::get('errors') as $errors)
                        {{ $errors }}<br>
                    @endforeach
                </div>
            @endif
            <div class="table-responsive">
                <p><b>II:</b> <span class="period">{{ $departmentKpiData->name }}</span></p>

                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal">Add Innovation</button>

                <table class="table table-bordered table-hover" id="innovationTable">
                    <thead>
                        <tr>
                            <th>Innovations / Projects</th>
                            <th>Project Summary</th>
                            <th>Job / Work Order Number</th>
                            <th>Start Date</th>
                            <th>Target Date of Completion</th>
                            <th>Actual Date of Completion</th>
                            <th>Remarks</th>
                            <th>Attachments</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $innovationList = $departmentKpiData->innovation()
                                ->where('department_id', auth()->user()->department_id)
                                ->where('year', date('Y'))
                                ->where('month', date('m'))
                                ->get();
                        @endphp
                        @foreach ($innovationList as $innovationData)
                            <tr>
                                <td>{{ $innovationData->projects }}</td>
                                <td>{{ $innovationData->project_summary }}</td>
                                <td>{{ $innovationData->work_order_number }}</td>
                                <td>{{ date('F d, Y', strtotime($innovationData->start_date)) }}</td>
                                <td>{{ date('F d, Y', strtotime($innovationData->target_date)) }}</td>
                                <td>{{ date('F d, Y', strtotime($innovationData->actual_date)) }}</td>
                                <td>{{ $innovationData->remarks }}</td>
                                <td width="100">

                                    @foreach ($innovationData->innovationAttachments as $file)
                                        <a href="{{ asset('file/' . $file->filename) }}" class="btn btn-sm btn-info" target="_blank">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        
                                        <button class="btn btn-sm btn-danger" name="deleteAttachments[]" type="button" data-id="{{ $file->id }}" id="deleteAttachments" {{ $innovationData->status_level == 1 ? 'disabled' : '' }}>
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @endforeach
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal-{{ $innovationData->id }}" {{ $innovationData->status_level == 1 ? 'disabled' : '' }}>
                                        <i class="fa fa-pencil"></i>
                                    </button>

                                    <form action="{{ url('deleteInnovation/' . $innovationData->id) }}" method="post">
                                        @csrf

                                        <input type="hidden" name="department_id" value="{{ $innovationData->department_id }}">
                                        <input type="hidden" name="year" value="{{ $innovationData->year }}">
                                        <input type="hidden" name="month" value="{{ $innovationData->month }}">

                                        <button type="submit" class="btn btn-sm btn-danger" {{ $innovationData->status_level == 1 ? 'disabled' : '' }}>
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title">Add Innovation</h1>
            </div>
            <div class="modal-body p-4" >
                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{ url('addInnovation') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                            @csrf

                            <input type="hidden" name="department_group_id" value="{{ $departmentKpiData->id }}">

                            <div class="form-group">
                                <label for="innovationProjects">Innovation Projects</label>
                                <input type="text" name="innovationProjects" id="innovationProjects" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <label for="projectSummary">Project Summary</label>
                                <textarea name="projectSummary" cols="30" rows="10" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="jobOrWorkNum">Job / Work Number</label>
                                <input type="text" name="jobOrWorkNum" id="jobOrWorkNum" class="form-control input-sm">
                            </div>
                            <div class="form-group" id="startDate">
                                <label for="startDate">Start Date</label>
                                <div class="input-group date">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control input-sm" name="startDate">
                                </div>
                            </div>
                            <div class="form-group" id="targetDate">
                                <label for="targetDate">Target Date</label>
                                <div class="input-group date">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control input-sm" name="targetDate">
                                </div>
                            </div>
                            <div class="form-group" id="actualDate">
                                <label for="actualDate">Actual Date</label>
                                <div class="input-group date">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control input-sm" name="actualDate">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="monthOf">Month Of</label>
                                <input type="month" name="monthOf" id="monthOf" class="form-control input-sm" max="{{ date('Y-m') }}">
                            </div>
                            <div class="form-group">
                                <label for="file">File</label>
                                <input type="file" name="file[]" id="file" class="form-control" multiple>
                            </div>
                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <textarea name="remarks" id="remarks" class="form-control input-sm" cols="30" rows="10"></textarea>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-sm btn-primary btn-block" type="submit">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach ($innovationList as $innovationData)
<div class="modal fade" id="editModal-{{ $innovationData->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title">Edit Innovations</h1>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form action="/updateInnovation/{{ $innovationData->id }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="department_group_id" value="{{ $departmentKpiData->id }}">

                            <div class="form-group">
                                <label for="innovationProjects">Innovation Projects</label>
                                <input type="text" name="innovationProjects" id="innovationProjects" class="form-control input-sm" value="{{ $innovationData->projects }}">
                            </div>
                            <div class="form-group">
                                <label for="projectSummary">Project Summary</label>
                                <textarea name="projectSummary" cols="30" rows="10" class="form-control">{{ $innovationData->project_summary }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="jobOrWorkNum">Job / Work Number</label>
                                <input type="text" name="jobOrWorkNum" id="jobOrWorkNum" class="form-control input-sm" value="{{ $innovationData->work_order_number }}">
                            </div>
                            <div class="form-group" id="startDate">
                                <label for="startDate">Start Date</label>
                                <div class="input-group date">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control input-sm" name="startDate" value="{{ $innovationData->start_date }}">
                                </div>
                            </div>
                            <div class="form-group" id="targetDate">
                                <label for="targetDate">Target Date</label>
                                <div class="input-group date">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control input-sm" name="targetDate" value="{{ $innovationData->target_date }}">
                                </div>
                            </div>
                            <div class="form-group" id="actualDate">
                                <label for="actualDate">Actual Date</label>
                                <div class="input-group date">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control input-sm" name="actualDate" value="{{ $innovationData->actual_date }}">
                                </div>
                            </div>
                            {{-- <div class="form-group">
                                <label for="monthOf">Month Of</label>
                                <input type="month" name="monthOf" id="monthOf" class="form-control input-sm" max="{{ date('Y-m') }}">
                            </div> --}}
                            <div class="form-group">
                                <label for="file">File</label>
                                <input type="file" name="file[]" id="file" class="form-control" multiple>
                            </div>
                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <textarea name="remarks" id="remarks" class="form-control input-sm" cols="30" rows="10">{{ $innovationData->remarks }}</textarea>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-sm btn-primary btn-block" type="submit">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif

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

        $('#targetDate .input-group.date').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
            startDate: dateToday
        });

        $('#actualDate .input-group.date').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
            startDate: dateToday
        });

        $("[name='deleteAttachments[]']").on('click', function() {
            var id = $(this).data('id');

            swal({
                title: "Are you sure?",
                text: "You will not be able to recover your file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function () {
                $.ajax({
                    type: "POST",
                    url: "{{ url('deleteAttachments') }}",
                    data: {
                        file_id: id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        swal("Deleted!", response.message, "success");

                        setTimeout(() => {
                            location.reload()
                        }, 1000);
                    }
                })
            });

            
        })
    })
</script>
@endpush