@extends('admin.layouts.app')
@section('title')
    Dashboard
@endsection
@section('content')
    <div class="container-xl py-3">
        <div class="h1 mb-3">Dashboard</div>
        <table class="table table-bordered table-striped table-hover table-sm">
            <thead>
            <tr>
                <th>ID</th>
                <th>IP address</th>
                <th>User agent</th>
                <th>Username</th>
                <th>Event</th>
                <th>Created at</th>
            </tr>
            </thead>
            <tbody>
            @foreach($authAttempts as $obj)
                <tr>
                    <td>{{ $obj->id }}</td>
                    <td>
                        {{ $obj->ipAddress->getIp() }}
                        <div class="small text-secondary">{{ $obj->ipAddress->ip_address }}</div>
                    </td>
                    <td>
                        {{ $obj->userAgent->getUa() }}
                        <div class="small text-secondary">{{ $obj->userAgent->user_agent }}</div>
                    </td>
                    <td>{{ $obj->username }}</td>
                    <td>{{ $obj->event }}</td>
                    <td>{{ $obj->created_at }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
