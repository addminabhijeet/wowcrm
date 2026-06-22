@extends('layout.layout')

@php
$title='Chat';
$subTitle = 'Chat';
@endphp

@section('content')

<div class="chat-wrapper">
    <div class="chat-sidebar card">
        <div class="chat-sidebar-single active top-profile">
            <div class="img">
                <img
                    src="{{ $activeUser && $activeUser->image
                        ? asset('storage/app/public/' . $activeUser->image)
                        : asset('assets/images/user-grid/user-grid-bg1.png') }}"
                    onerror="this.src='/assets/images/user-grid/user-grid-bg1.png'"
                    alt="{{ $activeUser->name ?? 'User' }}"
                    class="w-40-px h-40-px rounded-circle object-fit-cover">
            </div>
            <div class="info">
                <h6 class="text-md mb-0">
                    {{ $activeUser->name ?? '' }}
                </h6>

                <p class="mb-0">
                    Available
                </p>
            </div>

        </div><!-- chat-sidebar-single end -->
        <div class="chat-search">

            <form id="searchForm"
                method="GET"
                action="{{ route('chat.junior') }}"
                class="d-flex align-items-center position-relative">

                @if(request('user'))
                <input type="hidden" name="user" value="{{ request('user') }}">
                @endif

                <span class="icon position-absolute ms-3 d-flex align-items-center">
                    <iconify-icon icon="iconoir:search"></iconify-icon>
                </span>

                <input
                    type="text"
                    id="searchInput"
                    name="search"
                    value="{{ request('search') }}"
                    autocomplete="off"
                    class="form-control ps-5"
                    placeholder="Search by name, email">

            </form>

        </div>
        <div class="chat-all-list">

            @foreach($users as $chatUser)

            <a href="{{ route('chat.junior', ['user' => $chatUser->id]) }}"
                class="chat-sidebar-single text-decoration-none">

                <div class="img">
                    <img
                        src="{{ $chatUser->image
                            ? asset('storage/app/public/' . $chatUser->image)
                            : asset('assets/images/user-grid/user-grid-bg1.png') }}"
                        onerror="this.src='/assets/images/user-grid/user-grid-bg1.png'"
                        alt="{{ $chatUser->name }}"
                        class="w-40-px h-40-px rounded-circle object-fit-cover">
                </div>

                <div class="info">
                    <h6 class="text-sm mb-1">
                        {{ $chatUser->name }}
                    </h6>

                    <p class="mb-0 text-xs text-truncate">
                        {{ $chatUser->lastChat?->message ?? 'No messages yet' }}
                    </p>
                </div>

                <div class="action text-end">

                    <p class="mb-0 text-neutral-400 text-xs lh-1">
                        {{ $chatUser->lastChatDisplay }}
                    </p>

                </div>

            </a>

            @endforeach

        </div>
    </div>
    <div class="chat-main card">
        <div class="chat-sidebar-single active">
            <div class="img">
                <img
                    src="{{ $activeUser && $activeUser->image
                        ? asset('storage/app/public/' . $activeUser->image)
                        : asset('assets/images/user-grid/user-grid-bg1.png') }}"
                    onerror="this.src='/assets/images/user-grid/user-grid-bg1.png'"
                    alt="{{ $activeUser->name ?? 'User' }}"
                    class="w-40-px h-40-px rounded-circle object-fit-cover">
            </div>
            <div class="info">
                <h6 class="text-md mb-0">
                    {{ $activeUser->name ?? '' }}
                </h6>

                <p class="mb-0">
                    Available
                </p>
            </div>
        </div><!-- chat-sidebar-single end -->
        <div class="chat-message-list">
            @foreach($messages as $message)

            @if($message->sender_id == auth()->id())

            <div class="chat-single-message right">
                <div class="chat-message-content">

                    <p class="mb-3">
                        {!! $message->message !!}
                    </p>

                    <p class="chat-time mb-0">
                        <span>
                            {{ $message->formatted_time }}
                        </span>
                    </p>

                </div>
            </div>

            @else

            <div class="chat-single-message left">

                <img
                    src="{{ $activeUser && $activeUser->image
                        ? asset('storage/app/public/' . $activeUser->image)
                        : asset('assets/images/user-grid/user-grid-bg1.png') }}"
                    onerror="this.src='/assets/images/user-grid/user-grid-bg1.png'"
                    alt="{{ $activeUser->name ?? 'User' }}"
                    class="avatar-lg object-fit-cover rounded-circle">

                <div class="chat-message-content">

                    <p class="mb-3">
                        {!! $message->message !!}
                    </p>

                    <p class="chat-time mb-0">
                        <span>
                            {{ $message->formatted_time }}
                        </span>
                    </p>

                </div>

            </div>

            @endif

            @endforeach
        </div>

        <form
            class="chat-message-box"
            method="POST"
            action="{{ route('chat.send') }}"
            enctype="multipart/form-data">

            @csrf

            <input
                type="hidden"
                name="receiver_id"
                value="{{ $activeUser->id ?? '' }}">

            <div class="border rounded p-2 w-100">

                <!-- Editor -->
                <div id="editor"
                    class="form-control border-0 shadow-none"
                    style="
                        min-height:100px;
                        overflow-y:auto;
                    ">
                </div>

                <input
                    type="hidden"
                    name="chatMessage"
                    id="chatMessage">

            </div>

            <input
                type="file"
                id="fileInput"
                name="attachment"
                hidden>

            <input
                type="file"
                id="imageInput"
                name="attachment"
                accept="image/*"
                hidden>

            <div class="chat-message-box-action">

                <button
                    type="button"
                    id="fileBtn"
                    class="text-xl">

                    <iconify-icon icon="ph:link"></iconify-icon>

                </button>

                <button
                    type="button"
                    id="imageBtn"
                    class="text-xl">

                    <iconify-icon icon="solar:gallery-linear"></iconify-icon>

                </button>

                <button type="submit"
                    class="btn btn-sm btn-primary-600 radius-8 d-inline-flex align-items-center gap-1">
                    Send
                    <iconify-icon icon="f7:paperplane"></iconify-icon>
                </button>

            </div>

        </form>

    </div>
</div>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    let searchTimeout;

    document.getElementById('searchInput').addEventListener('keyup', function() {

        clearTimeout(searchTimeout);

        searchTimeout = setTimeout(() => {
            document.getElementById('searchForm').submit();
        }, 300);

    });
</script>
<script>
    document.getElementById('fileBtn').addEventListener('click', function() {

        document.getElementById('fileInput').click();

    });

    document.getElementById('imageBtn').addEventListener('click', function() {

        document.getElementById('imageInput').click();

    });

    document.getElementById('fileInput').addEventListener('change', function() {

        if (this.files.length > 0) {

            document.querySelector('.chat-message-box').submit();

        }

    });

    document.getElementById('imageInput').addEventListener('change', function() {

        if (this.files.length > 0) {

            document.querySelector('.chat-message-box').submit();

        }

    });
</script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'))
        .then(editor => {

            window.chatEditor = editor;

            editor.model.document.on('change:data', () => {

                document.getElementById('chatMessage').value =
                    editor.getData();

            });

            document.querySelector('.chat-message-box')
                ?.addEventListener('submit', function() {

                    document.getElementById('chatMessage').value =
                        editor.getData();

                });

        })
        .catch(error => {

            console.error(error);

        });
</script>
@endsection