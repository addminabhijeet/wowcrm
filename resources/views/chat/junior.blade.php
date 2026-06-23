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
                    id="active-user-image-left"
                    src="{{ $activeUser && $activeUser->image
                    ? asset('storage/app/public/' . $activeUser->image)
                    : asset('assets/images/user-grid/user-grid-bg1.png') }}"
                    onerror="this.src='/assets/images/user-grid/user-grid-bg1.png'"
                    alt="{{ $activeUser->name ?? 'User' }}"
                    class="w-40-px h-40-px rounded-circle object-fit-cover">
            </div>
            <div class="info">
                <h6 class="text-md mb-0" id="active-user-name-left">
                    {{ $activeUser->name ?? '' }}
                </h6>

                <p class="mb-0" id="active-user-status-left">
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
                class="chat-sidebar-single text-decoration-none d-flex align-items-center">

                <div class="d-flex align-items-center w-100">

                    <!-- Profile Image -->
                    <div class="img me-2 flex-shrink-0">
                        <img
                            src="{{ $chatUser->image
                ? asset('storage/app/public/' . $chatUser->image)
                : asset('assets/images/user-grid/user-grid-bg1.png') }}"
                            onerror="this.src='/assets/images/user-grid/user-grid-bg1.png'"
                            alt="{{ $chatUser->name }}"
                            class="w-40-px h-40-px rounded-circle object-fit-cover">
                    </div>

                    <!-- Name + Last Message -->
                    <div class="flex-grow-1 overflow-hidden">

                        <h6 class="text-sm mb-1">
                            {{ $chatUser->name }}
                        </h6>

                        <p class="mb-0 text-xs text-truncate"
                            id="last-message-{{ $chatUser->id }}">

                            @if(!empty($chatUser->lastChat?->message))

                            {{ \Illuminate\Support\Str::limit(trim(html_entity_decode(strip_tags($chatUser->lastChat->message))),50) }}

                            @elseif(!empty($chatUser->lastChat?->file_name))

                            {{ $chatUser->lastChat->file_name }}

                            @else

                            No messages yet

                            @endif

                        </p>

                    </div>

                    <!-- Time + Unread Count -->
                    <div class="ms-2 text-end flex-shrink-0">

                        <small class="d-block text-neutral-400"
                            id="last-time-{{ $chatUser->id }}">
                            {{ $chatUser->lastChatDisplay }}
                        </small>

                        <span
                            id="unread-count-{{ $chatUser->id }}"
                            class="badge rounded-pill bg-success mt-1"
                            @if(!$chatUser->unreadCount)
                            style="min-width:22px; display:none;"
                            @else
                            style="min-width:22px;"
                            @endif>
                            {{ $chatUser->unreadCount }}
                        </span>

                    </div>

                </div>

            </a>

            @endforeach

        </div>
    </div>
    <div class="chat-main card">
        <div class="chat-sidebar-single active">
            <div class="img">
                <img
                    id="active-user-image-right"
                    src="{{ $activeUser && $activeUser->image
                        ? asset('storage/app/public/' . $activeUser->image)
                        : asset('assets/images/user-grid/user-grid-bg1.png') }}"
                    onerror="this.src='/assets/images/user-grid/user-grid-bg1.png'"
                    alt="{{ $activeUser->name ?? 'User' }}"
                    class="w-40-px h-40-px rounded-circle object-fit-cover">
            </div>
            <div class="info">
                <h6 class="text-md mb-0" id="active-user-name-right">
                    {{ $activeUser->name ?? '' }}
                </h6>

                <p class="mb-0" id="active-user-status-right">
                    Available
                </p>
            </div>
        </div>

        <input
            type="hidden"
            id="lastMessageId"
            value="{{ $messages->last()?->id }}">

        <div class="chat-message-list" id="chatMessageList">

            @foreach($messages as $message)

            @if($message->sender_id == auth()->id())

            <div class="chat-single-message right">

                <div class="chat-message-content">

                    @if(!empty($message->message))
                    <div class="mb-2">
                        {!! $message->message !!}
                    </div>
                    @endif

                    @foreach($message->replies as $reply)

                    @if($reply->message_type == 'image' && $reply->file_path)

                    <div class="mb-2">

                        <img
                            src="{{ asset('storage/app/public/'.$reply->file_path) }}"
                            class="img-fluid rounded"
                            style="max-width:300px;cursor:pointer"
                            onclick="window.open(this.src,'_blank')">

                        <div class="mt-2">

                            <a
                                href="{{ asset('storage/app/public/'.$reply->file_path) }}"
                                download="{{ $reply->file_name }}"
                                class="btn btn-sm btn-primary">

                                Download Image

                            </a>

                        </div>

                    </div>

                    @elseif($reply->message_type == 'pdf' && $reply->file_path)

                    <div class="mb-2">

                        <a
                            href="{{ asset('storage/app/public/'.$reply->file_path) }}"
                            target="_blank">

                            <iframe
                                src="{{ asset('storage/app/public/'.$reply->file_path) }}"
                                width="100%"
                                height="500"
                                class="border rounded">
                            </iframe>

                        </a>

                        <div class="mt-2">

                            <a
                                href="{{ asset('storage/app/public/'.$reply->file_path) }}"
                                download="{{ $reply->file_name }}"
                                class="btn btn-sm btn-primary">

                                Download PDF

                            </a>

                        </div>

                    </div>

                    @elseif(in_array($reply->message_type,['file','document']) && $reply->file_path)

                    <div class="border rounded p-2 mb-2">

                        📄 {{ $reply->file_name }}

                        <br>

                        <small>
                            {{ $reply->file_size_formatted }}
                        </small>

                        <br>

                        <a
                            href="{{ asset('storage/app/public/'.$reply->file_path) }}"
                            download
                            class="btn btn-sm btn-primary mt-2">

                            Download

                        </a>

                    </div>

                    @endif

                    @endforeach

                    <p class="chat-time mb-0 text-white">
                        <span class="text-white">
                            {{ $message->formatted_time }}
                        </span>

                        @if($message->sender_id == auth()->id())

                        @if($message->is_read)

                        <br>
                        <small class="text-white">
                            Seen {{ $message->read_time }}
                        </small>

                        @else

                        <br>
                        <small class="text-white">
                            Delivered
                        </small>

                        @endif

                        @endif
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

                    @foreach($message->replies as $reply)

                    @if($reply->message_type == 'image' && $reply->file_path)

                    <div class="mb-2">

                        <img
                            src="{{ asset('storage/app/public/'.$reply->file_path) }}"
                            class="img-fluid rounded"
                            style="max-width:300px;cursor:pointer"
                            onclick="window.open(this.src,'_blank')">

                        <div class="mt-2">

                            <a
                                href="{{ asset('storage/app/public/'.$reply->file_path) }}"
                                download="{{ $reply->file_name }}"
                                class="btn btn-sm btn-primary">

                                Download Image

                            </a>

                        </div>

                    </div>

                    @elseif($reply->message_type == 'pdf' && $reply->file_path)

                    <div class="mb-2">

                        <a
                            href="{{ asset('storage/app/public/'.$reply->file_path) }}"
                            target="_blank">

                            <iframe
                                src="{{ asset('storage/app/public/'.$reply->file_path) }}"
                                width="100%"
                                height="500"
                                class="border rounded">
                            </iframe>

                        </a>

                        <div class="mt-2">

                            <a
                                href="{{ asset('storage/app/public/'.$reply->file_path) }}"
                                download="{{ $reply->file_name }}"
                                class="btn btn-sm btn-primary">

                                Download PDF

                            </a>

                        </div>

                    </div>

                    @elseif(in_array($reply->message_type,['file','document']) && $reply->file_path)

                    <div class="border rounded p-2 mb-2">

                        📄 {{ $reply->file_name }}

                        <br>

                        <small>
                            {{ $reply->file_size_formatted }}
                        </small>

                        <br>

                        <a
                            href="{{ asset('storage/app/public/'.$reply->file_path) }}"
                            download
                            class="btn btn-sm btn-primary mt-2">

                            Download

                        </a>

                    </div>

                    @endif

                    @endforeach

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

            <input
                type="hidden"
                id="activeUserId"
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


            <div class="mb-2">

                <button
                    type="button"
                    id="emojiBtn"
                    class="text-xl">

                    <iconify-icon
                        icon="fluent-emoji-high-contrast:grinning-face">
                    </iconify-icon>

                </button>

            </div>
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

    let pdfFiles = new DataTransfer();

    document.getElementById('fileInput').addEventListener('change', function() {

        Array.from(this.files).forEach(file => {
            pdfFiles.items.add(file);

            attachmentPreview.style.display = 'block';

            attachmentPreview.insertAdjacentHTML(
                'beforeend',
                `
            <div class="border rounded p-2 mb-2">
                📄 ${file.name}
                <br>
                <small>${(file.size / 1024).toFixed(2)} KB</small>
            </div>
            `
            );
        });

        this.files = pdfFiles.files;
    });
    let imageFiles = new DataTransfer();

    document.getElementById('imageInput').addEventListener('change', function() {

        Array.from(this.files).forEach(file => {

            imageFiles.items.add(file);

            let url = URL.createObjectURL(file);

            attachmentPreview.style.display = 'block';

            attachmentPreview.insertAdjacentHTML(
                'beforeend',
                `
            <div class="border rounded p-2 mb-2">
                <img
                    src="${url}"
                    class="rounded"
                    style="max-width:200px;max-height:200px">

                <div class="mt-2">${file.name}</div>
            </div>
            `
            );
        });

        this.files = imageFiles.files;
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@4.6.4/dist/index.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'))
        .then(editor => {

            window.chatEditor = editor;
            console.log(EmojiButton);
            const picker = new EmojiButton.EmojiButton();

            document
                .getElementById('emojiBtn')
                .addEventListener('click', () => {

                    picker.togglePicker(
                        document.getElementById('emojiBtn')
                    );

                });

            picker.on('emoji', selection => {

                editor.model.change(writer => {

                    writer.insertText(
                        selection.emoji,
                        editor.model.document.selection.getFirstPosition()
                    );

                });

            });

            // Preserve sidebar height
            document.querySelector('.chat-sidebar').style.height = '100%';
            document.querySelector('.chat-all-list').style.overflowY = 'auto';
            editor.editing.view.change(writer => {

                writer.setStyle(
                    'height',
                    '300px',
                    editor.editing.view.document.getRoot()
                );

            });

            editor.ui.view.editable.element.style.height = '300px';
            editor.ui.view.editable.element.style.overflowY = 'auto';

            // Send message on Enter
            editor.editing.view.document.on('enter', (evt, data) => {

                if (data.isSoft) {
                    return;
                }

                data.preventDefault();
                evt.stop();

                document.getElementById('chatMessage').value =
                    editor.getData();

                document.querySelector('.chat-message-box').submit();
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
<script>
    window.addEventListener('load', function() {

        const chatBox = document.getElementById('chatMessageList');

        if (chatBox) {

            // Scroll to bottom only once when page first opens
            chatBox.scrollTop = chatBox.scrollHeight;

        }

    });
</script>

<script>
    const authUserId = parseInt("{{ Auth::id() }}");
    let refreshRunning = false;

    function refreshUsers() {
        if (refreshRunning) {
            return;
        }
        refreshRunning = true;
        $.ajax({
            url: "{{ route('chat.refreshUsers') }}",
            type: "GET",
            data: {
                active_user_id: $("#activeUserId").val()
            },
            success: function(response) {
                let users = response.users;
                let messages = response.messages;
                let activeUser = response.activeUser;

                // Update active user hidden field
                if (activeUser && activeUser.id) {
                    $("#activeUserId").val(activeUser.id);
                    $("#active-user-name-left").text(activeUser.name);
                    $("#active-user-name-right").text(activeUser.name);
                    $("input[name='receiver_id']").val(activeUser.id);
                    let image = activeUser.image ?
                        "/storage/app/public/" + activeUser.image :
                        "/assets/images/user-grid/user-grid-bg1.png";

                    $("#active-user-image-left").attr("src", image);
                    $("#active-user-image-right").attr("src", image);
                }

                users.forEach(function(user) {
                    // Update last message
                    let lastMessage = "No messages yet";

                    if (user.lastChat) {
                        if (user.lastChat.message_preview) {
                            lastMessage = user.lastChat.message_preview;
                        } else if (user.lastChat.file_name) {
                            lastMessage = user.lastChat.file_name;
                        }
                    }

                    $("#last-message-" + user.id).text(lastMessage);
                    $("#last-time-" + user.id).text(user.lastChatDisplay);

                    // Update unread badge
                    let badge = $("#unread-count-" + user.id);

                    if (user.unreadCount > 0) {
                        badge.text(user.unreadCount);
                        badge.show();
                    } else {
                        badge.hide();
                    }
                });

                // Update active conversation with COMPLETE message rendering
                let html = '';

                messages.forEach(function(message) {
                    if (message.sender_id == authUserId) {
                        // RIGHT SIDE (sent by current user)
                        html += `
                        <div class="chat-single-message right">
                            <div class="chat-message-content">
                    `;

                        // Main message text
                        if (message.message) {
                            html += `<div class="mb-2">${message.message}</div>`;
                        }

                        // Render all replies (attachments)
                        if (message.replies && message.replies.length > 0) {
                            message.replies.forEach(function(reply) {
                                if (reply.message_type === 'image' && reply.file_path) {
                                    html += `
                                        <div class="mb-2">

                                            <img
                                                src="/storage/app/public/${reply.file_path}"
                                                class="img-fluid rounded"
                                                style="max-width:300px;cursor:pointer"
                                                onclick="window.open(this.src,'_blank')">

                                            <div class="mt-2">

                                                <a
                                                    href="/storage/app/public/${reply.file_path}"
                                                    download="${reply.file_name}"
                                                    class="btn btn-sm btn-primary">

                                                    Download Image

                                                </a>

                                            </div>

                                        </div>
                                        `;
                                } else if (reply.message_type === 'pdf' && reply.file_path) {
                                    html += `
                                        <div class="mb-2">

                                            <a
                                                href="/storage/app/public/${reply.file_path}"
                                                target="_blank">

                                                <iframe
                                                    src="/storage/app/public/${reply.file_path}"
                                                    width="100%"
                                                    height="500"
                                                    class="border rounded">
                                                </iframe>

                                            </a>

                                            <div class="mt-2">

                                                <a
                                                    href="/storage/app/public/${reply.file_path}"
                                                    download="${reply.file_name}"
                                                    class="btn btn-sm btn-primary">

                                                    Download PDF

                                                </a>

                                            </div>

                                        </div>
                                        `;
                                } else if ((reply.message_type === 'file' || reply.message_type === 'document') && reply.file_path) {
                                    html += `
                                    <div class="border rounded p-2 mb-2">
                                        📄 ${reply.file_name}
                                        <br>
                                        <small>${reply.file_size_formatted}</small>
                                        <br>
                                        <a 
                                            href="/storage/app/public/${reply.file_path}" 
                                            download 
                                            class="btn btn-sm btn-primary mt-2">
                                            Download
                                        </a>
                                    </div>
                                `;
                                }
                            });
                        }

                        // Timestamp and read status
                        html += `
                                <p class="chat-time mb-0 text-white">
                                    <span class="text-white">${message.formatted_time}</span>
                                    <br>
                                    <small class="text-white">
                                        ${message.is_read ? 'Seen ' + message.read_time : 'Delivered'}
                                    </small>
                                </p>
                            </div>
                        </div>
                    `;

                    } else {
                        // LEFT SIDE (received from other user)
                        html += `
                        <div class="chat-single-message left">
                            <img 
                            src="${activeUser && activeUser.image
                                ? '/storage/app/public/' + activeUser.image
                                : '/assets/images/user-grid/user-grid-bg1.png'}"
                                onerror="this.src='/assets/images/user-grid/user-grid-bg1.png'"
                                alt="${activeUser ? activeUser.name : 'User'}"
                                class="avatar-lg object-fit-cover rounded-circle">
                            <div class="chat-message-content">
                    `;

                        // Main message text
                        if (message.message) {
                            html += `<div class="mb-2">${message.message}</div>`;
                        }

                        // Render all replies (attachments)
                        if (message.replies && message.replies.length > 0) {
                            message.replies.forEach(function(reply) {
                                if (reply.message_type === 'image' && reply.file_path) {
                                    html += `
                                        <div class="mb-2">

                                                <img
                                                    src="/storage/app/public/${reply.file_path}"
                                                    class="img-fluid rounded"
                                                    style="max-width:300px;cursor:pointer"
                                                    onclick="window.open(this.src,'_blank')">

                                                <div class="mt-2">

                                                    <a
                                                        href="/storage/app/public/${reply.file_path}"
                                                        download="${reply.file_name}"
                                                        class="btn btn-sm btn-primary">

                                                        Download Image

                                                    </a>

                                                </div>

                                            </div>
                                `;
                                } else if (reply.message_type === 'pdf' && reply.file_path) {
                                    html += `
                                        <div class="mb-2">

                                            <a
                                                href="/storage/app/public/${reply.file_path}"
                                                target="_blank">

                                                <iframe
                                                    src="/storage/app/public/${reply.file_path}"
                                                    width="100%"
                                                    height="500"
                                                    class="border rounded">
                                                </iframe>

                                            </a>

                                            <div class="mt-2">

                                                <a
                                                    href="/storage/app/public/${reply.file_path}"
                                                    download="${reply.file_name}"
                                                    class="btn btn-sm btn-primary">

                                                    Download PDF

                                                </a>

                                            </div>

                                        </div>
                                        `;
                                } else if ((reply.message_type === 'file' || reply.message_type === 'document') && reply.file_path) {
                                    html += `
                                    <div class="border rounded p-2 mb-2">
                                        📄 ${reply.file_name}
                                        <br>
                                        <small>${reply.file_size_formatted}</small>
                                        <br>
                                        <a 
                                            href="/storage/app/public/${reply.file_path}" 
                                            download 
                                            class="btn btn-sm btn-primary mt-2">
                                            Download
                                        </a>
                                    </div>
                                `;
                                }
                            });
                        }

                        // Timestamp
                        html += `
                                <p class="chat-time mb-0">
                                    <span>${message.formatted_time}</span>
                                </p>
                            </div>
                        </div>
                    `;
                    }
                });
                let chatBox = document.getElementById('chatMessageList');

                // Check whether user is near bottom before refresh
                let shouldScroll =
                    chatBox.scrollHeight - chatBox.scrollTop - chatBox.clientHeight < 100;

                // Save current scroll position
                let currentScrollTop = chatBox.scrollTop;

                // Get current last message id
                let currentLastMessageId =
                    $("#lastMessageId").val();

                // Update chat only if a new message exists
                if (response.lastMessageId != currentLastMessageId) {

                    $("#lastMessageId")
                        .val(response.lastMessageId);

                    $("#chatMessageList").html(html);

                    if (shouldScroll) {

                        chatBox.scrollTop =
                            chatBox.scrollHeight;

                    } else {

                        chatBox.scrollTop =
                            currentScrollTop;
                    }
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            },
            complete: function() {
                refreshRunning = false;
            }
        });
    }

    setInterval(refreshUsers, 6000);
</script>


@endsection