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

                <div class="d-flex align-items-center flex-grow-1">

                    <div class="img me-2">
                        <img
                            src="{{ $chatUser->image
                ? asset('storage/app/public/' . $chatUser->image)
                : asset('assets/images/user-grid/user-grid-bg1.png') }}"
                            onerror="this.src='/assets/images/user-grid/user-grid-bg1.png'"
                            alt="{{ $chatUser->name }}"
                            class="w-40-px h-40-px rounded-circle object-fit-cover">
                    </div>

                    <div class="info flex-grow-1 overflow-hidden">

                        <h6 class="text-sm mb-1">
                            {{ $chatUser->name }}
                        </h6>

                        <p class="mb-0 text-xs text-truncate">

                            @if(!empty($chatUser->lastChat?->message))

                            {{ \Illuminate\Support\Str::limit(trim(html_entity_decode(strip_tags($chatUser->lastChat->message))), 50) }}

                            @elseif(!empty($chatUser->lastChat?->file_name))

                            {{ $chatUser->lastChat->file_name }}

                            @else

                            No messages yet

                            @endif

                        </p>

                    </div>

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

                    @if(!empty($message->message))
                    <div class="mb-2">
                        {!! $message->message !!}
                    </div>
                    @endif

                    @if($message->message_type == 'image' && $message->file_path)

                    <div class="mb-2">
                        <img
                            src="{{ asset('storage/app/public/'.$message->file_path) }}"
                            class="img-fluid rounded"
                            style="max-width:300px;cursor:pointer"
                            onclick="window.open(this.src,'_blank')">
                    </div>

                    @elseif($message->message_type == 'pdf' && $message->file_path)

                    <div class="mb-2">

                        <iframe
                            src="{{ asset('storage/app/public/'.$message->file_path) }}"
                            width="100%"
                            height="500"
                            class="border rounded">
                        </iframe>

                        <div class="mt-2">
                            <a
                                href="{{ asset('storage/app/public/'.$message->file_path) }}"
                                target="_blank"
                                class="btn btn-sm btn-primary">

                                Download PDF

                            </a>
                        </div>

                    </div>

                    @elseif(in_array($message->message_type,['file','document']) && $message->file_path)

                    <div class="border rounded p-2 mb-2">

                        📄 {{ $message->file_name }}

                        <br>

                        <small>
                            {{ $message->file_size_formatted }}
                        </small>

                        <br>

                        <a
                            href="{{ asset('storage/app/public/'.$message->file_path) }}"
                            download
                            class="btn btn-sm btn-primary mt-2">

                            Download

                        </a>

                    </div>

                    @endif

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

                    @if(!empty($message->message))
                    <div class="mb-2">
                        {!! $message->message !!}
                    </div>
                    @endif

                    @if($message->message_type == 'image' && $message->file_path)

                    <div class="mb-2">
                        <img
                            src="{{ asset('storage/app/public/'.$message->file_path) }}"
                            class="img-fluid rounded"
                            style="max-width:300px;cursor:pointer"
                            onclick="window.open(this.src,'_blank')">
                    </div>

                    @elseif($message->message_type == 'pdf' && $message->file_path)

                    <div class="mb-2">

                        <iframe
                            src="{{ asset('storage/app/public/'.$message->file_path) }}"
                            width="100%"
                            height="500"
                            class="border rounded">
                        </iframe>

                        <div class="mt-2">
                            <a
                                href="{{ asset('storage/app/public/'.$message->file_path) }}"
                                target="_blank"
                                class="btn btn-sm btn-primary">

                                Download PDF

                            </a>
                        </div>

                    </div>

                    @elseif(in_array($message->message_type,['file','document']) && $message->file_path)

                    <div class="border rounded p-2 mb-2">

                        📄 {{ $message->file_name }}

                        <br>

                        <small>
                            {{ $message->file_size_formatted }}
                        </small>

                        <br>

                        <a
                            href="{{ asset('storage/app/public/'.$message->file_path) }}"
                            download
                            class="btn btn-sm btn-primary mt-2">

                            Download

                        </a>

                    </div>

                    @endif

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
                    min-height:250px;
                    max-height:400px;
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
                name="attachment[]"
                accept=".pdf"
                multiple
                hidden>

            <input
                type="file"
                id="imageInput"
                name="image_attachment[]"
                accept="image/*"
                multiple
                hidden>



            <div class="chat-message-box-action">
                <div id="attachmentPreview"
                    class="mb-2"
                    style="display:none">
                </div>

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

        let html = '';

        Array.from(this.files).forEach(file => {

            html += `
        <div class="border rounded p-2 mb-2">
            📄 ${file.name}
            <br>
            <small>${(file.size/1024).toFixed(2)} KB</small>
        </div>`;
        });

        if (html !== '') {

            document.getElementById('attachmentPreview').style.display = 'block';
            document.getElementById('attachmentPreview').innerHTML += html;
        }
    });

    document.getElementById('imageInput').addEventListener('change', function() {

        if (this.files.length > 0) {

            let html = '';

            Array.from(this.files).forEach(file => {

                let url = URL.createObjectURL(file);

                html += `
                <div class="border rounded p-2 mb-2">
                    <img src="${url}"
                        class="rounded"
                        style="max-width:200px;max-height:200px">

                    <div class="mt-2">${file.name}</div>
                </div>`;
            });

            document.getElementById('attachmentPreview').style.display = 'block';
            document.getElementById('attachmentPreview').innerHTML += html;

            let url = URL.createObjectURL(file);

            document.getElementById('attachmentPreview').style.display = 'block';

            document.getElementById('attachmentPreview').innerHTML =
                `
            <div class="border rounded p-2">
                <img src="${url}"
                     style="max-width:200px;max-height:200px"
                     class="rounded">
                <div class="mt-2">${file.name}</div>
            </div>
            `;
        }

    });
</script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'))
        .then(editor => {

            window.chatEditor = editor;

            // Increase editor height
            editor.editing.view.change(writer => {

                writer.setStyle(
                    'min-height',
                    '250px',
                    editor.editing.view.document.getRoot()
                );

            });

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