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
                                        <a class="navbar-brand" href="#">Audio Messages</a>
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
                                        <th class="td-actions text-right" style="" data-field="actions"><div class="th-inner ">Actions</div><div class="fht-cell"></div></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {{-- */$x=0;/* --}}
                                    @foreach($audioMessages as $item)
                                        {{-- */$x++;/* --}}
                                        <tr>
                                            <td>
                                                <a href="{{ url('/audio-messages', ['id' => $item->id]) }}">
                                                    {{ $item->file_name }}
                                                </a>
                                            </td>
                                            <td>{{ $item->created_at->toDateTimeString() }}</td>
                                            <td class="td-actions text-right">
                                                <a href="{{ url('/audio-messages', ['id' => $item->id]) }}"
                                                   rel="tooltip"
                                                   class="btn btn-simple btn-danger btn-icon"
                                                   data-method="delete"
                                                   data-original-title="Delete Audio Message"
                                                   data-remote="false"
                                                   data-confirm="Are you sure you want to delete {{ $item->file_name }}?">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="pagination-wrapper"> {!! $audioMessages->render() !!} </div>
                            </div>
                            {!! Form::open( ['route' => 'audio-messages.store', 'files' => true] ) !!}
                            <div class="form-group{{ $errors->has('file') ? ' has-error' : '' }}">
                                {!! Form::label('file','Add New Audio Message File') !!}
                                {!! Form::file('file', null, ['class' => 'form-control']) !!}
                                @if ($errors->has('file'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('file') }}</strong>
                                    </span>
                                @endif
                            </div>
                            {!! Form::submit('Submit', ['class' => 'btn btn-primary btn-fill']) !!}
                            {!! Form::close() !!}
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
@stop