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
                                        <a class="navbar-brand" href="#">Auto Dialer CDR's</a>
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
                                    {{--<th>Message</th>--}}
                                    <th>Result</th>
                                    <th>User</th>
                                    <th>Fail Reason</th>
                                    <th>Bulk File</th>
                                    <th>Timestamp</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($cdrs))
                                    @foreach($cdrs as $item)
                                        <tr>
                                            <td>{{$item->dialednumber}}</td>
                                            <td>{{$item->callerid}}</td>
                                            <td>{{$item->calltype}}</td>
                                            {{--<td>{{$item->message}}</td>--}}
                                            <td>{{$item->successful ? 'Success' : 'Fail'}}</td>
                                            <td>{{$item->user->name}}</td>
                                            <td>{{$item->failurereason}}</td>
                                            <td>{{$item->autoDialerBulkFile->file_name or ''}}</td>
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
    <!-- Include Modal scripts -->
    @stack('modal_scripts')
@stop