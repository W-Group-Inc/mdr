@extends('layouts.app')
@section('content')

@section('css')
<link href="css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">
<link href="css/plugins/dropzone/basic.css" rel="stylesheet">
<link href="css/plugins/dropzone/dropzone.css" rel="stylesheet">
<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">

<!-- Sweet Alert -->
<link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">

<style>
    .period {
        margin-left: 5px;
    }
</style>
@endsection

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        @foreach ($departmentKpiGroup as $dptGroupData)
            @if($dptGroupData->name == "Departmental Goals")
                <div class="col-lg-12">
                    <div class="ibox float-e-margins" style="margin-top: 10px;">
                        <div class="ibox-content">
                            <div class="table-responsive">
                                <p><strong>I.</strong>Departmental Goals</p>
                                <div class="alert alert-info">
                                    <strong>Note: </strong> Attach a file first before submitting a KPI
                                </div>

                                <form action="{{ url('update_mdr') }}" method="post">
                                    @csrf
                                    
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>KPI</th>
                                                <th>Target</th>
                                                <th>Actual</th>
                                                <th>Grade</th>
                                                <th>Remarks</th>
                                                <th>Attachments</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dptGroupData->departmentalGoals as $dptGoals)
                                                <input type="hidden" name="department_kpi_id[]" value="{{ $dptGoals->department_kpi_id }}">
                                                <input type="hidden" name="yearAndMonth" value="{{ $yearAndMonth }}">

                                                <tr>
                                                    <td width="300">{!! nl2br($dptGoals->kpi_name) !!}</td>
                                                    <td width="300">{!! nl2br($dptGoals->target) !!}</td>
                                                    <td>
                                                        <textarea name="actual[]" id="actual" cols="30" rows="10" class="form-control" placeholder="Input an actual" {{ $dptGoals->status_level != 0 ? 'disabled' : '' }} required>{{ $dptGoals->actual }}</textarea>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="grade[]" id="grade" class="form-control input-sm" value="{{ $dptGoals->grade }}" placeholder="Input grade (use percentage)" {{ $dptGoals->status_level != 0 ? 'disabled' : '' }}  required>
                                                    </td>
                                                    <td>
                                                        <textarea name="remarks[]" id="remarks" cols="30" rows="10" class="form-control" placeholder="Input a remarks" {{ $dptGoals->status_level != 0 ? 'disabled' : '' }} required>{{ $dptGoals->remarks }}</textarea>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#uploadModal-{{ $dptGoals->department_kpi_id }}" {{ $dptGoals->status_level != 0 ? 'disabled' : '' }} >
                                                            <i class="fa fa-upload"></i>
                                                        </button>
                                                        
                                                        @foreach ($dptGroupData->departmentKpi as $dptKpi)
                                                            @foreach ($dptKpi->attachments as $attachment)
                                                                @if($dptGoals->department_kpi_id == $attachment->department_kpi_id)
                                                                    <div>
                                                                        <a href="{{ url($attachment->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                                                                            <i class="fa fa-eye"></i>
                                                                        </a>
    
                                                                        <button type="button" class="btn btn-sm btn-danger" name="deleteAttachments" data-id="{{ $attachment->id }}" {{ $dptGoals->status_level != 0 ? 'disabled' : '' }} >
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <button class="btn btn-sm btn-primary pull-right" type="submit">Update KPI</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @foreach ($dptGroupData->departmentKpi as $item)
                    <div class="modal fade uploadModal" id="uploadModal-{{ $item->id }}">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title">Add Attachments</h1>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label>File Upload</label>
                                            <form action="/uploadAttachments/{{ $item->id }}" class="dropzone" id="dropzoneForm" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="yearAndMonth" value="{{ $yearAndMonth }}">
                                                <div class="fallback">
                                                    <input name="file" type="file" multiple />
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

            @if($dptGroupData->name == "Innovations (Accomplished)")
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
                                <p><b>II:</b> <span class="period">{{ $dptGroupData->name }}</span></p>
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
                                        @foreach ($dptGroupData->innovation as $innovationData)
                                            
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
                                                        
                                                        <button class="btn btn-sm btn-danger" name="deleteAttachments[]" type="button" data-id="{{ $file->id }}" id="deleteAttachments" {{ $innovationData->status_level != 0 ? 'disabled' : '' }}>
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal-{{ $innovationData->id }}" {{ $innovationData->status_level != 0 ? 'disabled' : '' }}>
                                                        <i class="fa fa-pencil"></i>
                                                    </button>

                                                    <form action="{{ url('deleteInnovation/' . $innovationData->id) }}" method="post">
                                                        @csrf

                                                        <input type="hidden" name="department_id" value="{{ $innovationData->department_id }}">
                                                        <input type="hidden" name="year" value="{{ $innovationData->year }}">
                                                        <input type="hidden" name="month" value="{{ $innovationData->month }}">

                                                        <button type="submit" class="btn btn-sm btn-danger" {{ $innovationData->status_level != 0 ? 'disabled' : '' }}>
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
                                        <form action="{{ url('addInnovation') }}" method="post" enctype="multipart/form-data" autocomplete="off" id="innovationForm">
                                            @csrf

                                            <input type="hidden" name="department_group_id" value="{{ $dptGroupData->id }}">
                                            <input type="hidden" name="yearAndMonth" value="{{ $yearAndMonth }}">

                                            <div class="form-group">
                                                <label for="innovationProjects">Innovation Projects</label>
                                                <input type="text" name="innovationProjects" id="innovationProjects" class="form-control input-sm" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="projectSummary">Project Summary</label>
                                                <textarea name="projectSummary" cols="30" rows="10" class="form-control" required></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="jobOrWorkNum">Job / Work Number</label>
                                                <input type="text" name="jobOrWorkNum" id="jobOrWorkNum" class="form-control input-sm" required>
                                            </div>
                                            <div class="form-group" id="startDate">
                                                <label for="startDate">Start Date</label>
                                                <div class="input-group date">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                    <input type="text" class="form-control input-sm" name="startDate" required>
                                                </div>
                                            </div>
                                            <div class="form-group" id="targetDate">
                                                <label for="targetDate">Target Date</label>
                                                <div class="input-group date">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                    <input type="text" class="form-control input-sm" name="targetDate" required>
                                                </div>
                                            </div>
                                            <div class="form-group" id="actualDate">
                                                <label for="actualDate">Actual Date</label>
                                                <div class="input-group date">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                    <input type="text" class="form-control input-sm" name="actualDate" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="file">Supporting Documents</label>
                                                <input type="file" name="file[]" id="file" class="form-control" multiple>
                                            </div>
                                            <div class="form-group">
                                                <label for="remarks">Remarks</label>
                                                <textarea name="remarks" id="remarks" class="form-control input-sm" cols="30" rows="10" required></textarea>
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

                @foreach ($dptGroupData->innovation as $innovationData)
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
                                            
                                            <input type="hidden" name="department_group_id" value="{{ $dptGroupData->id }}">

                                            <div class="form-group">
                                                <label for="innovationProjects">Innovation Projects</label>
                                                <input type="text" name="innovationProjects" id="innovationProjects" class="form-control input-sm" value="{{ $innovationData->projects }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="projectSummary">Project Summary</label>
                                                <textarea name="projectSummary" cols="30" rows="10" class="form-control" required>{{ $innovationData->project_summary }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="jobOrWorkNum">Job / Work Number</label>
                                                <input type="text" name="jobOrWorkNum" id="jobOrWorkNum" class="form-control input-sm" value="{{ $innovationData->work_order_number }}" required>
                                            </div>
                                            <div class="form-group" id="startDate">
                                                <label for="startDate">Start Date</label>
                                                <div class="input-group date">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                    <input type="text" class="form-control input-sm" name="startDate" value="{{ $innovationData->start_date }}" required>
                                                </div>
                                            </div>
                                            <div class="form-group" id="targetDate">
                                                <label for="targetDate">Target Date</label>
                                                <div class="input-group date">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                    <input type="text" class="form-control input-sm" name="targetDate" value="{{ $innovationData->target_date }}" required>
                                                </div>
                                            </div>
                                            <div class="form-group" id="actualDate">
                                                <label for="actualDate">Actual Date</label>
                                                <div class="input-group date">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                    <input type="text" class="form-control input-sm" name="actualDate" value="{{ $innovationData->actual_date }}" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="file">Supporting Documents</label>
                                                <input type="file" name="file[]" id="file" class="form-control" multiple>
                                            </div>
                                            <div class="form-group">
                                                <label for="remarks">Remarks</label>
                                                <textarea name="remarks" id="remarks" class="form-control input-sm" cols="30" rows="10" required>{{ $innovationData->remarks }}</textarea>
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

            @if($dptGroupData->name == "Process Development")
                <div class="col-lg-12">
                    <div class="ibox float-e-margins" style="margin-top: 10px;">
                        <div class="ibox-content">
                            <div class="table-responsive">
                                <p><b>III:</b> <span class="period">{{ $dptGroupData->name }}</span></p>
                                @if (Session::has('pdError'))
                                    <div class="alert alert-danger">
                                        @foreach (Session::get('pdError') as $errors)
                                            {{ $errors }}<br>
                                        @endforeach
                                    </div>
                                @endif
                                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addProcessDevelopment">Add Process Development</button>

                                <table class="table table-bordered table-hover" id="processDevelopmentTable">
                                    <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th>Accomplished Date</th>
                                            <th>Remarks</th>
                                            <th>Attachments</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dptGroupData->processDevelopment as $processDevelopmentData)
                                            <tr>
                                                <td>{{ $processDevelopmentData->description }}</td>
                                                <td>{{ date('F d, Y', strtotime($processDevelopmentData->accomplished_date )) }}</td>
                                                <td>{{ $processDevelopmentData->remarks }}</td>
                                                <td width="10">
                                                    @foreach ($processDevelopmentData->pdAttachments as $pdFile)
                                                        <div>
                                                            <a href="{{ $pdFile->filepath }}" class="btn btn-sm btn-info" target="_blank">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                            
                                                            <button type="button" class="btn btn-sm btn-danger deletePdAttachments" data-id="{{ $pdFile->id }}" {{ $processDevelopmentData->status_level != 0 ? 'disabled' : '' }}>
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </td>
                                                <td width="10">
                                                    <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editPdModal-{{ $processDevelopmentData->id }}" {{ $processDevelopmentData->status_level != 0 ? 'disabled' : '' }}>
                                                        <i class="fa fa-pencil"></i>
                                                    </button>

                                                    <form action="{{ url('deleteProcessDevelopment/' . $processDevelopmentData->id) }}" method="post">
                                                        @csrf

                                                        <input type="hidden" name="department_id" value="{{ $processDevelopmentData->department_id }}">
                                                        <input type="hidden" name="year" value="{{ $processDevelopmentData->year }}">
                                                        <input type="hidden" name="month" value="{{ $processDevelopmentData->month }}">

                                                        <button type="submit" class="btn btn-sm btn-danger" {{ $processDevelopmentData->status_level != 0 ? 'disabled' : '' }}>
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

                    <div class="modal fade" id="addProcessDevelopment">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title">Add Process Development</h1>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <form action="{{ url('addProcessDevelopment') }}" method="post" enctype="multipart/form-data">
                                                @csrf
                    
                                                <input type="hidden" name="dptGroup" value="{{ $dptGroupData->id }}">
                                                <input type="hidden" name="yearAndMonth" value="{{ $yearAndMonth }}">

                                                <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <input type="text" name="description" id="description" class="form-control input-sm" required>
                                                </div>
                                                <div class="form-group" id="accomplishedDate">
                                                    <label for="accomplishedDate">Accomplished Date</label>
                                                    <div class="input-group date">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </span>
                                                        <input type="text" class="form-control input-sm" name="accomplishedDate" autocomplete="off" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="file">Upload an Attachments</label>
                                                    <input type="file" name="file[]" id="file" class="form-control" multiple required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="remarks">Remarks</label>
                                                    <textarea name="remarks" id="remarks" class="form-control" cols="30" rows="10" required></textarea>
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
                    @foreach ($dptGroupData->processDevelopment as $pd)
                        <div class="modal fade" id="editPdModal-{{ $pd->id }}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title">Edit Process Development</h1>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <form action="{{ url('updateProcessDevelopment/' . $pd->id) }}" method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    
                                                    <input type="hidden" name="pd_id" value="{{ $pd->id }}">

                                                    <div class="form-group">
                                                        <label for="description">Description</label>
                                                        <input type="text" name="description" id="description" class="form-control input-sm" value="{{ $pd->description }}" required>
                                                    </div>
                                                    <div class="form-group" id="accomplishedDate">
                                                        <label for="accomplishedDate">Accomplished Date</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-calendar"></i>
                                                            </span>
                                                            <input type="text" class="form-control input-sm" name="accomplishedDate" autocomplete="off" value="{{ $pd->accomplished_date }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="file">Upload an Attachments</label>
                                                        <input type="file" name="file[]" id="file" class="form-control" multiple>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="remarks">Remarks</label>
                                                        <textarea name="remarks" id="remarks" class="form-control" cols="30" rows="10" required>{{ $pd->remarks }}</textarea>
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
                </div>
            @endif
        @endforeach

        @if(auth()->user()->account_role == 2)
            <div class="col-lg-12">
                <div class="ibox float-e-margins" style="margin-top: 10px;">
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="processDevelopmentTable">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>MDR Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td> {{ auth()->user()->name  }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#mdrStatusModal">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <form action="{{ url('approveMdr') }}" method="post">
                                                @csrf

                                                <input type="hidden" name="yearAndMonth" value="{{ $yearAndMonth }}">

                                                <button class="btn btn-sm btn-primary" type="submit">Approve</button>
                                            </form>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="mdrStatusModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title">MDR Status</h1>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>Approver</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($approver as $approverData)
                                                @foreach ($approverData->mdrStatus as $item)
                                                    <tr>
                                                        <td>{{ $item->users->name }}</td>
                                                        <td>{{ $item->status == 1 ? 'APPROVED' : 'WAITING'}}</td>
                                                        <td>{{ $item->start_date }}</td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/plugins/dataTables/datatables.min.js') }}"></script>

<script src="{{ asset('js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
<!-- DROPZONE -->
<script src="{{ asset('js/plugins/dropzone/dropzone.js') }}"></script>
<!-- Jasny -->
<script src="{{ asset('js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>
<!-- Sweet alert -->
<script src="{{ asset('js/plugins/sweetalert/sweetalert.min.js') }}"></script>

<script src="{{ asset('js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>

<script>
    $(document).ready(function() {
        $("[name='deleteAttachments']").on('click', function() {
            var id = $(this).data('id')
            
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
                    url: "{{ url('deleteKpiAttachments') }}",
                    data: {
                        id: id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        swal("Deleted!", "Your file has been deleted.", "success");

                        setTimeout(() => {
                            location.reload()
                        }, 1000);
                    }
                })
            });
        })

        $("[name='grade[]']").keypress(function(event) {
            if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 37) {
            }
            else {
                if (event.keyCode < 48 || event.keyCode > 57) {
                    event.preventDefault(); 
                }   
            }
        });

        $('#innovationTable').DataTable({
            pageLength: 10,
            ordering: false,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [],
        });

        $('#processDevelopmentTable').DataTable({
            pageLength: 10,
            ordering: false,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [],
        });

        var dateToday = new Date();

        $('#startDate .input-group.date').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
            // startDate: dateToday,
        });

        $('#targetDate .input-group.date').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
            // startDate: dateToday
        });

        $('#actualDate .input-group.date').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
            // startDate: dateToday
        });

        $('#accomplishedDate .input-group.date').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
            startDate: dateToday,
        });

        $(".deletePdAttachments").on('click', function() {

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
                    url: "{{ url('deletePdAttachments') }}",
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