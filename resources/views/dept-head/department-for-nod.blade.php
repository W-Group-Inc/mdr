@extends('layouts.app')
@section('content')
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Notice of Disciplinary</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($mdrSummary)}}</h1>
                    <small>Total NOD</small>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="table-responsive">
                        @if (Session::has('errors'))
                            <div class="alert alert-danger">
                                @foreach (Session::get('errors') as $errors)
                                    {{ $errors }}<br>
                                @endforeach
                            </div>
                        @endif

                        <table class="table table-striped table-bordered table-hover" id="penaltiesTable">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Department Head</th>
                                    <th>Month</th>
                                    <th>Total Rating</th>
                                    <th>Uploaded By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mdrSummary as $mdrSummaryData)
                                    @if(!empty($mdrSummaryData->nodAttachments))
                                    <tr>
                                        <td>{{ $mdrSummaryData->departments->dept_name }}</td>
                                        <td>{{ $mdrSummaryData->departments->user->name }}</td>
                                        <td>{{ date('F Y', strtotime($mdrSummaryData->year.'-'.$mdrSummaryData->month)) }}</td>
                                        <td>{{ $mdrSummaryData->rate }}</td>
                                        <td>{{ !empty($mdrSummaryData->nodAttachments->users->name) ? $mdrSummaryData->nodAttachments->users->name : '' }}</td>
                                        <td width="100">
                                            <button class="btn btn-sm btn-warning" type="button" data-toggle="modal" data-target="#uploadNodModal-{{ $mdrSummaryData->id }}">
                                                <i class="fa fa-upload"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#viewModal-{{$mdrSummaryData->id}}">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @foreach ($mdrSummary as $mdrSummaryData)
                        <div class="modal" id="uploadNodModal-{{ $mdrSummaryData->id }}">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title">Upload NOD Attachments</h1>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <form action="{{ url('upload_nod/'.$mdrSummaryData->id) }}" method="post" enctype="multipart/form-data" onsubmit="show()">
                                                    @csrf
                                                    
                                                    <input type="hidden" name="yearAndMonth" value="{{ $mdrSummaryData->year.'-'.$mdrSummaryData->month }}">
                                                    <input type="hidden" name="departmentId" value="{{ $mdrSummaryData->department_id }}">
                                                    <input type="hidden" name="mdrSummaryId" value="{{ $mdrSummaryData->id }}">

                                                    <div class="form-group">
                                                        <label for="files">Upload NOD Attachment</label>
                                                        <input type="file" name="files" id="files" class="form-control" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <button class="btn btn-sm btn-primary btn-block">Upload</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if(!empty($mdrSummaryData->nodAttachments))
                            <div class="modal" id="viewModal-{{$mdrSummaryData->id}}">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title">View Status</h1>
                                        </div>
                                        <form action="{{url('nte_status/'.$mdrSummaryData->nodAttachments->id)}}" method="post" onsubmit="show()">
                                            @csrf

                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        View Files :
                                                        <span>
                                                            <a href="{{$mdrSummaryData->nodAttachments->filepath}}" target="_blank">{{$mdrSummaryData->nodAttachments->filename}}</a>
                                                        </span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        Acknowledge by :
                                                        <span>{{isset($mdrSummaryData->nodAttachments->acknowledge->name)?$mdrSummaryData->nodAttachments->acknowledge->name:''}}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        Status :
                                                        <span>{{$mdrSummaryData->nodAttachments->status}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="js/plugins/dataTables/datatables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#penaltiesTable').DataTable({
                pageLength: 10,
                ordering: false,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [],
            });

        })
    </script>
@endpush