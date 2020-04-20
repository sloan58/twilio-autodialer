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
                                        <a class="navbar-brand" href="#">Users</a>
                                    </div>
                                </div>
                            </nav>
                        </div>
                        <div class="content">
                            <div class="content table-responsive table-full-width">
                                <table class="table table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email Address</th>
                                        <th class="td-actions text-right" style="" data-field="actions">
                                            <div class="th-inner ">Actions</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($users as $item)
                                        <tr>
                                            <td>
                                                <a href="{{ url("/users/" . $item->id . "/edit")}}" >
                                                    {{ $item->name }}
                                                </a>
                                            </td>
                                            <td>{{ $item->email }} </td>
                                            <td class="td-actions text-right">
                                                <a href="{{ url('/users', ['id' => $item->id]) }}"
                                                   rel="tooltip"
                                                   class="btn btn-simple btn-danger btn-icon"
                                                   data-method="delete"
                                                   data-original-title="Delete User"
                                                   data-remote="false"
                                                   data-confirm="Are you sure you want to delete {{ $item->name }}?">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="pagination-wrapper"> {!! $users->render() !!} </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    @stack('modal_scripts')

@endsection