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
                                        <a class="navbar-brand" href="#">Auto Dialer - Bulk Load</a>
                                    </div>
                                </div>
                            </nav>
                        </div>
                        <div class="content">
                            <ul role="tablist" class="nav nav-tabs">
                                <li role="presentation" class="active">
                                    <a href="#icon-bulk" data-toggle="tab"><i class="fa fa-file-excel-o"></i>Bulk Loader</a>
                                </li>
                                <li role="presentation" class="">
                                    <a href="#icon-info" data-toggle="tab"><i class="fa fa-info"></i>Info</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div id="icon-bulk" class="tab-pane active">
                                    {!! Form::open( ['route' => 'autodialer.bulk.store', 'files' => true, 'id' => 'app'] ) !!}
                                    <div class="form-group{{ $errors->has('caller_id') ? ' has-error' : '' }}">
                                        {!! Form::label('Caller ID','Your Caller ID') !!}
                                        {!! Form::select('caller_id', $verifiedPhoneNumbers, null, ['class' => 'selectpicker form-control', 'data-style' => 'btn-default btn-block']) !!}
                                        @if ($errors->has('caller_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('caller_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                                        {!! Form::label('Call Type','Call Type') !!}
                                        @if(\Auth::user()->audioMessages()->count())
                                        {!! Form::select('type', ['voice' => 'Voice', 'text' => 'Text Message', 'audio' => 'Audio File'], 'S', ['v-model' => 'selected', 'class' => 'selectpicker form-control', 'data-style' => 'btn-default btn-block']) !!}
                                        @else
                                        {!! Form::select('type', ['voice' => 'Voice', 'text' => 'Text Message'], 'S', ['v-model' => 'selected', 'class' => 'selectpicker form-control', 'data-style' => 'btn-default btn-block']) !!}
                                        @endif
                                        @if ($errors->has('type'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('type') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group{{ $errors->has('say') ? ' has-error' : '' }}" v-if="selected === 'audio'">
                                        {!! Form::label('audio_file','Audio Messages') !!}
                                        {!! Form::select('say', $audioMessages, 'S', ['class' => 'selectpicker form-control', 'data-style' => 'btn-default btn-block']) !!}
                                        @if ($errors->has('say'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('say') }}</strong>
                                            </span>
                                        @endif
                                        {!! Form::hidden('audioMessage', true) !!}
                                    </div>
                                    <div class="form-group{{ $errors->has('say') ? ' has-error' : '' }}" v-else>
                                        {!! Form::label('What Should We Say?','What would you like to say?') !!}
                                        {!! Form::textarea('say', null, ['class' => 'form-control']) !!}
                                        @if ($errors->has('say'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('say') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group{{ $errors->has('file') ? ' has-error' : '' }}">
                                        {!! Form::label('file','Bulk Update File') !!}
                                        {!! Form::file('file', null, ['class' => 'form-control']) !!}
                                        @if ($errors->has('file'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('file') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    {!! Form::submit('Submit', ['class' => 'btn btn-primary btn-fill form-control']) !!}
                                    {!! Form::close() !!}
                                </div>
                                <div id="icon-info" class="tab-pane">
                                    <ul>
                                        <li>
                                            <p>
                                                The Auto Dialer bulk interface accepts a csv formatted list of phone numbers to call, along with some other call data filled out in the form.
                                            </p>
                                        </li>
                                        <li>
                                            <p>
                                                All form data must be filled out, and a csv of phone numbers supplied:
                                            <ol>
                                                <li>Select a caller ID to use from your Twilio account</li>
                                                <li>Select whether the calls should be voice or text message</li>
                                                <li>Enter the text which will be sent via SMS or phone call</li>
                                                <li>Supply a list of phone numbers to call.  Each call wil take the right-most 10 digits.</li>
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
                                        <a class="navbar-brand" href="#">Auto Dialer Bulk Jobs Status</a>
                                    </div>
                                </div>
                            </nav>
                        </div>
                        <div class="content">
                            <div class="content table-responsive table-full-width">
                                <table class="table table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th>Filename</th>
                                        <th>Submitted On</th>
                                        <th>Status</th>
                                        <th class="td-actions text-right" style="" data-field="actions"><div class="th-inner ">Actions</div><div class="fht-cell"></div></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {{-- */$x=0;/* --}}
                                    @foreach($bulkFiles as $item)
                                        {{-- */$x++;/* --}}
                                        <tr>
                                            <td>
                                                <a href="{{ url('/autodialer/bulk', ['id' => $item->id]) }}">
                                                    {{ $item->file_name }}
                                                </a>
                                            </td>
                                            <td>{{ $item->created_at->toDateTimeString() }}</td>
                                            @if($item->status == 'Processing')
                                                <td>{{ $item->status }}<i class="fa fa-spinner fa-pulse fa-2x fa-fw"  style="color:green"></i></td>
                                            @else
                                                <td>{{ $item->status }}</td>
                                            @endif
                                            <td class="td-actions text-right">
                                                <a href="{{ url('/autodialer/bulk', ['id' => $item->id]) }}"
                                                   rel="tooltip"
                                                   class="btn btn-simple btn-danger btn-icon"
                                                   data-method="delete"
                                                   data-original-title="Delete Bulk File"
                                                   data-remote="false"
                                                   data-confirm="Are you sure you want to delete {{ $item->file_name }}?">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="pagination-wrapper"> {!! $bulkFiles->render() !!} </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <!-- Include Modal scripts -->
    @stack('modal_scripts')

    <script>
        Echo.private(`bulk-process.${id}`)
            .listen('BulkProcessUpdated', (e) => {
                console.log(e.update);
            });
    </script>
@stop