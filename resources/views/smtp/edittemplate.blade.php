@extends('layout.layout')

@php
$title = 'SMTP';
$subTitle = 'Settings - SMTP';
$script = '<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>';
@endphp

@section('content')

<div class="card p-4">
    <h4>Edit Email Template</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('template.update', $template->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Subject</label>
            <input type="text" name="subject" class="form-control" value="{{ old('subject', $template->subject) }}">
        </div>

        <div class="mb-3">
            <label>Body</label>
            <textarea name="body" id="editor" class="form-control" rows="10">{{ old('body', $template->body) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save Template</button>
    </form>
</div>

{{-- Load CKEditor --}}
<!-- Load latest CKEditor 4 LTS (4.25.1) -->
<script src="https://cdn.ckeditor.com/4.25.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('editor', {
        height: 250,
        removeButtons: 'PasteFromWord'
    });
</script>

@endsection