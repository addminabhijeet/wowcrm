@extends('layout.layout')
@section('content')
<div class="mt-5">
    @foreach($seniors as $senior)
    <div class="card mb-3">
        <div class="card-header">
            <strong>Team - {{ $senior->name }}</strong>
        </div>

        <div class="card-body">
            @if($senior->juniors->count())
            <table class="table table-bordered mb-0">
                <tbody>
                    @foreach($senior->juniors as $junior)
                    <tr>
                        <td>{{ $junior->name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="mb-0 text-muted">No juniors assigned.</p>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endsection