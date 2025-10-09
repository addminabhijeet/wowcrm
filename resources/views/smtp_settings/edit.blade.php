@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <h2>Edit SMTP Settings</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('smtp.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Mailer</label>
            <input type="text" name="mailer" class="form-control" value="{{ old('mailer', $smtp->mailer ?? 'smtp') }}">
        </div>

        <div class="mb-3">
            <label>Host</label>
            <input type="text" name="host" class="form-control" value="{{ old('host', $smtp->host ?? '') }}">
        </div>

        <div class="mb-3">
            <label>Port</label>
            <input type="number" name="port" class="form-control" value="{{ old('port', $smtp->port ?? 465) }}">
        </div>

        <div class="mb-3">
            <label>Username</label>
            <input type="email" name="username" class="form-control" value="{{ old('username', $smtp->username ?? '') }}">
        </div>

        <div class="mb-3">
            <label>Password <small>(Leave blank to keep current)</small></label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="mb-3">
            <label>Encryption</label>
            <input type="text" name="encryption" class="form-control" value="{{ old('encryption', $smtp->encryption ?? 'ssl') }}">
        </div>

        <div class="mb-3">
            <label>From Address</label>
            <input type="email" name="from_address" class="form-control" value="{{ old('from_address', $smtp->from_address ?? '') }}">
        </div>

        <div class="mb-3">
            <label>From Name</label>
            <input type="text" name="from_name" class="form-control" value="{{ old('from_name', $smtp->from_name ?? '') }}">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection