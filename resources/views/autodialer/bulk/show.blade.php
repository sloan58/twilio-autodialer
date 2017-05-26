@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <nav class="navbar navbar-default">
                                <div class="container-fluid">
                                    <div class="navbar-header">
                                        <a class="navbar-brand" href="#">Bulk Dialer Processing</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="{{ route('autodialer.bulk.process', ['id' => $bulk->id]) }}" class="btn btn-success btn-wd" title="Add New CUCM Server"><b>Process Call Logs</b></a>
                                    </div>
                                </div>
                            </nav>
                        </div>
                        <div class="content">
                            <ul role="tablist" class="nav nav-tabs">
                                <li role="presentation" class="active">
                                    <a href="#icon-bulk" data-toggle="tab"><i class="fa fa-file-excel-o"></i>Upload Log Files Here</a>
                                </li>
                                <li>
                                    <a href="#icon-logs" data-toggle="tab"><i class=""></i>Your Log Files</a>
                                </li>
                                <li>
                                    <a href="#icon-info" data-toggle="tab"><i class="fa fa-info"></i>Info</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div id="icon-bulk" class="tab-pane active">
                                    {!! Form::open(['url' => route('autodialer.bulk.logfile', ['id' => $bulk->id]), 'class' => 'dropzone', 'files'=>true, 'id'=>'real-dropzone']) !!}

                                    <div class="dz-message">
                                    </div>

                                    <div class="fallback">
                                        <input name="file" type="file" />
                                    </div>

                                    <div class="dropzone-previews" id="dropzonePreview"></div>

                                    <h4 style="text-align: center;color:#428bca;">Drop log files in this area  <i class="fa fa-upload" aria-hidden="true"></i></h4>

                                    {!! Form::close() !!}
                                </div>
                                <div id="icon-logs" class="tab-pane">
                                    <div class="content table-responsive">
                                        <table class="table table-hover table-striped">
                                            <thead>
                                            <tr>
                                                <th>File Name</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if($bulk->logFiles->count())
                                                @foreach($bulk->logFiles as $item)
                                                    <tr>
                                                        <td>{{$item->path}}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div id="icon-info" class="tab-pane">
                                    <ul>
                                        <li>
                                            <p>
                                                The Bulk Dialer Processing interface accepts your gateway log files to confirm whether the call was received inbound on your network.
                                            </p>
                                        </li>
                                        <li>
                                            <p>
                                                The list of steps are:
                                            <ol>
                                                <li>Load your log files in the 'Upload Log Files Here' tab. <small>(Load as many as you'd like)</small></li>
                                                <li>Click on 'Process Call Logs'</li>
                                                <li>After the process is completed, a report will be downloaded for you.</li>
                                            </ol>
                                            </p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <nav class="navbar navbar-default">
                                <div class="container-fluid">
                                    <div class="navbar-header">
                                        <a class="navbar-brand" href="#">Auto Dialer Bulk Dial Calls</a>
                                    </div>
                                    <div class="collapse navbar-collapse">

                                    </div>
                                </div>
                            </nav>
                        </div>
                        <div class="content table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                <tr>
                                    <th>Dialed Number</th>
                                    <th>Caller ID</th>
                                    <th>Call Type</th>
                                    <th>Message</th>
                                    <th>Result</th>
                                    <th>User</th>
                                    <th>Fail Reason</th>
                                    <th>Timestamp</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($cdrs)
                                    @foreach($cdrs as $item)
                                        <tr>
                                            <td>{{$item->dialednumber}}</td>
                                            <td>{{$item->callerid}}</td>
                                            <td>{{$item->calltype}}</td>
                                            <td>{{$item->message}}</td>
                                            <td>{{$item->successful ? 'Success' : 'Fail'}}</td>
                                            <td>{{$item->user->name}}</td>
                                            <td>{{$item->failurereason}}</td>
                                            <td>{{$item->created_at->format('m-d-Y H:i:s')}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $cdrs->render() !!} </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        // Change php.ini settings to support large uploads
        // upload_max_filesize = 1000M
        // post_max_size = 1000M
        Dropzone.options.realDropzone = {
            // Set the max file upload size
            maxFilesize: 1000,

            // Handle upload errors
            error: function(file, response) {
                if($.type(response) === "string")
                    var message = response; //dropzone sends it's own error messages in string
                else
                    var message = response.message;
                file.previewElement.classList.add("dz-error");
                _ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
                _results = [];
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i];
                    _results.push(node.textContent = message);
                }
                $.notify({
                    icon: "pe-7s-bell",
                    message: "<b>Sorry, there was a problem uploading your file....</b>"

                },{
                    type: 'danger',
                    timer: 4000,
                    placement: {
                        from: 'top',
                        align: 'right'
                    }
                });
                return _results;
            },
            // Handle upload success
            success: function(file,done) {
                $.notify({
                    icon: "pe-7s-bell",
                    message: "<b>File Uploaded Succesfully!</b>"

                },{
                    type: 'success',
                    timer: 4000,
                    placement: {
                        from: 'top',
                        align: 'right'
                    }
                });
            }
        }
    </script>
    <!-- Include Modal scripts -->
    @stack('modal_scripts')
@stop