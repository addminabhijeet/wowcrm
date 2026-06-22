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
            <span class="icon">
                <iconify-icon icon="iconoir:search"></iconify-icon>
            </span>
            <input type="text" name="#0" autocomplete="off" placeholder="Search...">
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
                        alt="{{ $chatUser->name }}"
                        class="w-40-px h-40-px rounded-circle object-fit-cover">
                </div>

                <div class="info">
                    <h6 class="text-sm mb-1">
                        {{ $chatUser->name }}
                    </h6>

                    <p class="mb-0 text-xs">
                        {{ $chatUser->lastChat?->message ?? 'No messages yet' }}
                    </p>
                </div>

                <div class="action text-end">
                    <p class="mb-0 text-neutral-400 text-xs lh-1">
                        {{ $chatUser->lastChat?->formatted_time ?? '' }}
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

        <form class="chat-message-box">

            <div class="border rounded p-2 w-100">

                <!-- Toolbar -->
                <div class="btn-toolbar gap-1 mb-2">

                    <button type="button"
                        id="boldBtn"
                        class="btn btn-light btn-sm">
                        <iconify-icon icon="mdi:format-bold"></iconify-icon>
                    </button>

                    <button type="button"
                        id="italicBtn"
                        class="btn btn-light btn-sm">
                        <iconify-icon icon="mdi:format-italic"></iconify-icon>
                    </button>

                    <button type="button"
                        id="strikeBtn"
                        class="btn btn-light btn-sm">
                        <iconify-icon icon="mdi:format-strikethrough"></iconify-icon>
                    </button>

                    <button type="button"
                        id="bulletBtn"
                        class="btn btn-light btn-sm">
                        <iconify-icon icon="mdi:format-list-bulleted"></iconify-icon>
                    </button>

                </div>

                <!-- Editor -->
                <div id="editor"
                    class="form-control border-0 shadow-none"
                    style="min-height:100px">
                </div>

            </div>

            <!-- Hidden input sent to Laravel -->
            <input type="hidden"
                name="chatMessage"
                id="chatMessage">

            <div class="chat-message-box-action">

                <button type="button" class="text-xl">
                    <iconify-icon icon="ph:link"></iconify-icon>
                </button>

                <button type="button" class="text-xl">
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

@endsection