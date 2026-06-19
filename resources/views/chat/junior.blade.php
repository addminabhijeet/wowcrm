@extends('layout.layout')

@php
$title='Chat';
$subTitle = 'Chat';
@endphp

@section('content')

<div class="chat-wrapper">
    <!-- Sidebar -->
    <div class="chat-sidebar card">
        <!-- Current User Profile -->
        <div class="chat-sidebar-single active top-profile">
            <div class="img">
                <img src="{{ auth()->user()->image ?? asset('assets/images/chat/1.png') }}" alt="image" class="rounded-circle object-fit-cover" style="width: 50px; height: 50px;">
            </div>
            <div class="info">
                <h6 class="text-md mb-0">{{ auth()->user()->name }}</h6>
                <p class="mb-0 text-success small">Available</p>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="chat-search">
            <span class="icon">
                <iconify-icon icon="iconoir:search"></iconify-icon>
            </span>
            <input type="text" id="userSearch" autocomplete="off" placeholder="Search..." class="form-control border-0">
        </div>

        <!-- Users List (Loaded via AJAX) -->
        <div class="chat-all-list" id="usersList">
            <div class="text-center py-5">
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Chat Area -->
    <div class="chat-main card">
        <!-- Chat Header -->
        <div class="chat-sidebar-single active border-bottom d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center flex-grow-1">
                <div class="img me-2">
                    <img src="{{ asset('assets/images/chat/11.png') }}" alt="image" id="chatUserImage" class="rounded-circle object-fit-cover" style="width: 45px; height: 45px;">
                </div>
                <div class="info">
                    <h6 class="text-md mb-0" id="chatUserName">Select a chat</h6>
                    <p class="mb-0 text-success small" id="chatUserStatus">Available</p>
                </div>
            </div>
            <div class="d-flex gap-2 me-3">
                <button type="button" class="btn btn-sm btn-outline-secondary" title="Call">
                    <iconify-icon icon="ph:phone"></iconify-icon>
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" title="Video call">
                    <iconify-icon icon="ph:video-camera"></iconify-icon>
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" title="Info">
                    <iconify-icon icon="ph:info"></iconify-icon>
                </button>
            </div>
        </div>

        <!-- Messages Container -->
        <div class="chat-message-list" id="chatMessageList" style="height: 500px; overflow-y: auto;">
            <div class="d-flex align-items-center justify-content-center h-100">
                <p class="text-muted">Select a user to start chatting</p>
            </div>
        </div>

        <!-- Message Input Form -->
        <form class="chat-message-box" id="chatForm">
            @csrf
            <input type="hidden" id="receiverId" name="receiver_id">

            <!-- Rich Text Editor Container -->
            <div class="border rounded p-2 w-100">
                <!-- Toolbar -->
                <div class="btn-toolbar gap-1 mb-2 flex-wrap">
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" id="boldBtn" class="btn btn-light" title="Bold">
                            <iconify-icon icon="mdi:format-bold"></iconify-icon>
                        </button>
                        <button type="button" id="italicBtn" class="btn btn-light" title="Italic">
                            <iconify-icon icon="mdi:format-italic"></iconify-icon>
                        </button>
                        <button type="button" id="strikeBtn" class="btn btn-light" title="Strikethrough">
                            <iconify-icon icon="mdi:format-strikethrough"></iconify-icon>
                        </button>
                    </div>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" id="bulletBtn" class="btn btn-light" title="Bullet list">
                            <iconify-icon icon="mdi:format-list-bulleted"></iconify-icon>
                        </button>
                        <button type="button" id="numberBtn" class="btn btn-light" title="Numbered list">
                            <iconify-icon icon="mdi:format-list-numbered"></iconify-icon>
                        </button>
                    </div>
                    <div class="btn-group btn-group-sm ms-auto" role="group">
                        <button type="button" id="undoBtn" class="btn btn-light" title="Undo">
                            <iconify-icon icon="mdi:undo"></iconify-icon>
                        </button>
                        <button type="button" id="redoBtn" class="btn btn-light" title="Redo">
                            <iconify-icon icon="mdi:redo"></iconify-icon>
                        </button>
                    </div>
                </div>

                <!-- Editor -->
                <div id="editor"
                    class="form-control border-0 shadow-none p-2"
                    style="min-height: 100px; outline: none; overflow-y: auto;">
                </div>
            </div>

            <!-- Hidden input for message -->
            <input type="hidden" name="message" id="chatMessage">

            <!-- File Input (Hidden) -->
            <input type="file" id="fileInput" class="d-none" accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar">

            <!-- Action Buttons -->
            <div class="chat-message-box-action d-flex gap-2 mt-2 align-items-center">
                <!-- File Upload -->
                <button type="button" id="linkBtn" class="btn text-muted" title="Attach file">
                    <iconify-icon icon="ph:link" class="fs-5"></iconify-icon>
                </button>

                <!-- Gallery/Image Upload -->
                <button type="button" id="galleryBtn" class="btn text-muted" title="Upload image/video">
                    <iconify-icon icon="solar:gallery-linear" class="fs-5"></iconify-icon>
                </button>

                <!-- Emoji Picker (Optional) -->
                <button type="button" id="emojiBtn" class="btn text-muted" title="Emoji">
                    <iconify-icon icon="mdi:emoticon-happy-outline" class="fs-5"></iconify-icon>
                </button>

                <!-- Send Button -->
                <button type="submit" class="btn btn-sm btn-primary-600 radius-8 ms-auto d-inline-flex align-items-center gap-1">
                    Send
                    <iconify-icon icon="f7:paperplane"></iconify-icon>
                </button>
            </div>

            <!-- Upload Progress -->
            <div id="uploadProgress" class="progress mt-2 d-none" style="height: 25px;">
                <div id="uploadProgressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%">0%</div>
            </div>
        </form>
    </div>
</div>

<!-- Emoji Picker Modal (Optional) -->
<div class="modal fade" id="emojiModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Select Emoji</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="emojiList" style="max-height: 300px; overflow-y: auto;">
                <!-- Populated by JS -->
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@tiptap/core@2.0.0/dist/tiptap.umd.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@tiptap/starter-kit@2.0.0/dist/starter-kit.umd.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatHandler = new ChatHandler();
    });

    class ChatHandler {
        constructor() {
            this.currentUserId = null;
            this.currentUser = null;
            this.editorInstance = null;
            this.lastMessageId = 0;

            this.init();
        }

        init() {
            this.initTiptap();
            this.loadUsers();
            this.attachEventListeners();
        }

        /**
         * Initialize Tiptap Rich Text Editor
         */
        initTiptap() {
            const editor = document.querySelector('#editor');

            this.editorInstance = new Tiptap.Editor({
                element: editor,
                extensions: [
                    Tiptap.StarterKit,
                ],
                content: '',
            });
        }

        /**
         * Attach all event listeners
         */
        attachEventListeners() {
            // Form submit
            document.getElementById('chatForm').addEventListener('submit', (e) => {
                e.preventDefault();
                this.sendMessage();
            });

            // File upload buttons
            document.getElementById('linkBtn').addEventListener('click', () => this.openFileDialog());
            document.getElementById('galleryBtn').addEventListener('click', () => this.openGalleryDialog());

            // File input change
            document.getElementById('fileInput').addEventListener('change', (e) => {
                this.handleFileSelect(e.target.files[0]);
                e.target.value = '';
            });

            // User search
            document.getElementById('userSearch').addEventListener('input', (e) => {
                this.searchUsers(e.target.value);
            });

            // Editor toolbar
            document.getElementById('boldBtn').addEventListener('click', (e) => {
                e.preventDefault();
                this.editorInstance.chain().focus().toggleBold().run();
            });

            document.getElementById('italicBtn').addEventListener('click', (e) => {
                e.preventDefault();
                this.editorInstance.chain().focus().toggleItalic().run();
            });

            document.getElementById('strikeBtn').addEventListener('click', (e) => {
                e.preventDefault();
                this.editorInstance.chain().focus().toggleStrike().run();
            });

            document.getElementById('bulletBtn').addEventListener('click', (e) => {
                e.preventDefault();
                this.editorInstance.chain().focus().toggleBulletList().run();
            });

            document.getElementById('numberBtn').addEventListener('click', (e) => {
                e.preventDefault();
                this.editorInstance.chain().focus().toggleOrderedList().run();
            });

            document.getElementById('undoBtn').addEventListener('click', (e) => {
                e.preventDefault();
                this.editorInstance.chain().focus().undo().run();
            });

            document.getElementById('redoBtn').addEventListener('click', (e) => {
                e.preventDefault();
                this.editorInstance.chain().focus().redo().run();
            });

            // Emoji button
            document.getElementById('emojiBtn').addEventListener('click', (e) => {
                e.preventDefault();
                this.showEmojiPicker();
            });
        }

        /**
         * Load users from API
         */
        loadUsers() {
            fetch('/api/chat/users')
                .then(res => res.json())
                .then(users => {
                    this.renderUsersList(users);
                })
                .catch(err => {
                    console.error('Error loading users:', err);
                    document.getElementById('usersList').innerHTML = '<p class="text-danger p-3">Error loading users</p>';
                });
        }

        /**
         * Render users list
         */
        renderUsersList(users) {
            const container = document.getElementById('usersList');

            if (users.length === 0) {
                container.innerHTML = '<p class="text-muted p-3 text-center">No users found</p>';
                return;
            }

            container.innerHTML = users.map(user => `
            <div class="chat-sidebar-single cursor-pointer" data-user-id="${user.id}" onclick="chatHandler.selectUser(${user.id}, '${user.name}', '${user.image}')">
                <div class="img position-relative">
                    <img src="${user.image || '/assets/images/chat/1.png'}" alt="${user.name}" class="rounded-circle object-fit-cover" style="width: 45px; height: 45px;">
                    <span class="position-absolute bottom-0 end-0 p-1 bg-${user.status === 'Available' ? 'success' : 'secondary'} rounded-circle" style="width: 12px; height: 12px;"></span>
                </div>
                <div class="info flex-grow-1 min-w-0">
                    <h6 class="text-sm mb-1 text-truncate">${user.name}</h6>
                    <p class="mb-0 text-xs text-truncate text-muted">${user.last_message || 'No messages yet'}</p>
                </div>
                <div class="action text-end flex-shrink-0">
                    <p class="mb-0 text-muted text-xs lh-1">${user.last_message_time || ''}</p>
                    ${user.unread_count > 0 ? `
                        <span class="badge bg-warning text-dark">${user.unread_count}</span>
                    ` : ''}
                </div>
            </div>
        `).join('');
        }

        /**
         * Search users
         */
        searchUsers(query) {
            if (!query.trim()) {
                this.loadUsers();
                return;
            }

            // Simple client-side filtering (can be enhanced with server-side search)
            const usersList = document.getElementById('usersList');
            const items = usersList.querySelectorAll('.chat-sidebar-single');

            items.forEach(item => {
                const name = item.querySelector('.info h6').textContent.toLowerCase();
                item.style.display = name.includes(query.toLowerCase()) ? '' : 'none';
            });
        }

        /**
         * Select a user to chat with
         */
        selectUser(userId, userName, userImage) {
            this.currentUserId = userId;
            this.currentUser = {
                id: userId,
                name: userName,
                image: userImage
            };

            // Update hidden input
            document.getElementById('receiverId').value = userId;

            // Update UI
            document.querySelectorAll('.chat-sidebar-single[data-user-id]').forEach(el => {
                el.classList.remove('active');
            });
            event.currentTarget?.classList.add('active');

            // Update header
            document.getElementById('chatUserName').textContent = userName;
            document.getElementById('chatUserImage').src = userImage || '/assets/images/chat/1.png';

            // Load conversation
            this.loadConversation(userId);

            // Mark as read
            this.markAsRead(userId);
        }

        /**
         * Load conversation with a user
         */
        loadConversation(userId) {
            fetch(`/api/chat/conversation/${userId}`)
                .then(res => res.json())
                .then(data => {
                    this.renderMessages(data.messages);
                })
                .catch(err => {
                    console.error('Error loading conversation:', err);
                    document.getElementById('chatMessageList').innerHTML = '<p class="text-danger p-3">Error loading messages</p>';
                });
        }

        /**
         * Render messages
         */
        renderMessages(messages) {
            const container = document.getElementById('chatMessageList');

            if (messages.length === 0) {
                container.innerHTML = '<p class="text-muted p-3 text-center">No messages yet. Start the conversation!</p>';
                return;
            }

            container.innerHTML = messages.map(msg => this.createMessageElement(msg)).join('');

            // Scroll to bottom
            container.scrollTop = container.scrollHeight;
        }

        /**
         * Create message HTML element
         */
        createMessageElement(msg) {
            const isOwn = msg.sender_id == this.getAuthUserId();
            const messageClass = isOwn ? 'right' : 'left';

            let contentHTML = this.getMessageContent(msg);

            return `
            <div class="chat-single-message ${messageClass} mb-3" data-message-id="${msg.id}">
                ${!isOwn ? `
                    <img src="${msg.sender.image || '/assets/images/chat/1.png'}" 
                         alt="${msg.sender.name}" 
                         class="avatar-lg object-fit-cover rounded-circle me-2"
                         style="width: 40px; height: 40px; flex-shrink: 0;">
                ` : ''}
                <div class="chat-message-content">
                    ${contentHTML}
                    <div class="d-flex align-items-center gap-2 mt-2">
                        <small class="text-muted">${msg.formatted_time}</small>
                        ${isOwn ? `
                            <small class="text-muted">${msg.is_read ? '✓✓' : '✓'}</small>
                        ` : ''}
                    </div>
                    ${isOwn ? `
                        <div class="btn-group btn-group-sm mt-2" role="group">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="chatHandler.editMessage(${msg.id}, '${msg.message.replace(/'/g, "\\'")}')">
                                Edit
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="chatHandler.deleteMessage(${msg.id})">
                                Delete
                            </button>
                        </div>
                    ` : ''}
                </div>
            </div>
        `;
        }

        /**
         * Get message content based on type
         */
        getMessageContent(msg) {
            switch (msg.message_type) {
                case 'image':
                    return `<img src="/storage/${msg.file_path}" class="img-fluid rounded" style="max-width: 300px;">`;

                case 'video':
                    return `
                    <video width="300" height="200" controls class="rounded">
                        <source src="/storage/${msg.file_path}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                `;

                case 'audio':
                    return `
                    <audio controls class="w-100">
                        <source src="/storage/${msg.file_path}">
                        Your browser does not support the audio element.
                    </audio>
                `;

                case 'pdf':
                case 'document':
                case 'zip':
                case 'file':
                    return `
                    <div class="border rounded p-2 bg-light" style="max-width: 300px;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <iconify-icon icon="mdi:file" class="fs-5"></iconify-icon>
                            <div class="flex-grow-1 min-w-0">
                                <p class="mb-0 text-truncate small"><strong>${msg.file_name}</strong></p>
                                <small class="text-muted">${msg.file_size_formatted}</small>
                            </div>
                        </div>
                        <a href="/api/chat/download/${msg.id}" class="btn btn-sm btn-primary w-100">
                            Download
                        </a>
                    </div>
                `;

                default:
                    return `<p class="mb-0">${msg.message}</p>`;
            }
        }

        /**
         * Send message
         */
        sendMessage() {
            if (!this.currentUserId) {
                alert('Please select a user first');
                return;
            }

            const messageContent = this.editorInstance.getHTML();

            if (!messageContent.trim() || messageContent === '<p></p>') {
                alert('Please type a message');
                return;
            }

            const formData = new FormData();
            formData.append('receiver_id', this.currentUserId);
            formData.append('message', messageContent);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            this.setLoading(true);

            fetch('/api/chat/send', {
                    method: 'POST',
                    body: formData,
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.editorInstance.commands.clearContent();
                        this.loadConversation(this.currentUserId);
                    } else {
                        alert('Error: ' + (data.error || 'Unable to send message'));
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('Error sending message');
                })
                .finally(() => this.setLoading(false));
        }

        /**
         * Handle file selection
         */
        handleFileSelect(file) {
            if (!file) return;

            if (!this.currentUserId) {
                alert('Please select a user first');
                return;
            }

            // Validate file size
            const maxSize = 50 * 1024 * 1024; // 50MB
            if (file.size > maxSize) {
                alert('File size exceeds 50MB limit');
                return;
            }

            const formData = new FormData();
            formData.append('receiver_id', this.currentUserId);
            formData.append('file', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            this.showUploadProgress(true);
            this.setLoading(true);

            const xhr = new XMLHttpRequest();

            xhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable) {
                    const percentComplete = (e.loaded / e.total) * 100;
                    this.updateProgressBar(percentComplete);
                }
            });

            xhr.addEventListener('load', () => {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        this.loadConversation(this.currentUserId);
                    } else {
                        alert('Error: ' + (response.error || 'Failed to upload'));
                    }
                } else {
                    alert('Upload failed');
                }
                this.showUploadProgress(false);
                this.setLoading(false);
            });

            xhr.addEventListener('error', () => {
                alert('Upload error');
                this.showUploadProgress(false);
                this.setLoading(false);
            });

            xhr.open('POST', '/api/chat/send');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
            xhr.send(formData);
        }

        /**
         * Edit message
         */
        editMessage(messageId, currentMessage) {
            if (confirm('Edit this message?')) {
                const newMessage = prompt('Edit message:', currentMessage.replace(/<[^>]*>/g, ''));
                if (newMessage === null) return;

                fetch(`/api/chat/messages/${messageId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            message: newMessage
                        }),
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.loadConversation(this.currentUserId);
                        } else {
                            alert('Error: ' + (data.error || 'Failed to edit'));
                        }
                    });
            }
        }

        /**
         * Delete message
         */
        deleteMessage(messageId) {
            if (!confirm('Delete this message?')) return;

            fetch(`/api/chat/messages/${messageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.loadConversation(this.currentUserId);
                    } else {
                        alert('Error: ' + (data.error || 'Failed to delete'));
                    }
                });
        }

        /**
         * Mark messages as read
         */
        markAsRead(userId) {
            fetch('/api/chat/mark-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    sender_id: userId
                }),
            });
        }

        /**
         * Open file dialog
         */
        openFileDialog() {
            document.getElementById('fileInput').click();
        }

        /**
         * Open gallery dialog
         */
        openGalleryDialog() {
            const input = document.getElementById('fileInput');
            input.accept = 'image/*,video/*';
            input.click();
        }

        /**
         * Show emoji picker
         */
        showEmojiPicker() {
            const emojis = ['😀', '😃', '😄', '😁', '😆', '😅', '🤣', '😂', '🙂', '🙃', '😉', '😊', '😇', '🥰', '😍', '🤩', '😘', '😗', '😚', '😙', '🥲', '😋', '😛', '😜', '🤪', '😌', '😔', '😑', '😐', '😶', '🤐', '🤨', '🤔', '🤫', '🤬', '🤥', '😌', '😔', '😪', '🤤', '😴', '😷', '🤒', '🤕', '🤮', '🤢', '🤮', '🤮'];

            const modal = new bootstrap.Modal(document.getElementById('emojiModal'));
            const emojiList = document.getElementById('emojiList');

            emojiList.innerHTML = emojis.map(emoji => `
            <button type="button" class="btn btn-light m-1" onclick="chatHandler.insertEmoji('${emoji}')">
                ${emoji}
            </button>
        `).join('');

            modal.show();
        }

        /**
         * Insert emoji
         */
        insertEmoji(emoji) {
            this.editorInstance.chain().focus().insertContent(emoji).run();
            bootstrap.Modal.getInstance(document.getElementById('emojiModal')).hide();
        }

        /**
         * Update progress bar
         */
        updateProgressBar(percent) {
            const bar = document.getElementById('uploadProgressBar');
            bar.style.width = percent + '%';
            bar.textContent = Math.round(percent) + '%';
        }

        /**
         * Show/hide upload progress
         */
        showUploadProgress(show) {
            const progress = document.getElementById('uploadProgress');
            if (show) {
                progress.classList.remove('d-none');
            } else {
                progress.classList.add('d-none');
                this.updateProgressBar(0);
            }
        }

        /**
         * Set loading state
         */
        setLoading(loading) {
            const btn = document.querySelector('#chatForm button[type="submit"]');
            if (loading) {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';
            } else {
                btn.disabled = false;
                btn.innerHTML = 'Send <iconify-icon icon="f7:paperplane"></iconify-icon>';
            }
        }

        /**
         * Get authenticated user ID
         */
        getAuthUserId() {
            return document.querySelector('meta[name="user-id"]').content;
        }
    }

    // Make chatHandler globally accessible
    let chatHandler;
    document.addEventListener('DOMContentLoaded', function() {
        chatHandler = new ChatHandler();
    });
</script>
<style>
    /* Make editor areas scrollable */
    #editor {
        max-height: 150px;
        overflow-y: auto;
    }

    #chatMessageList {
        display: flex;
        flex-direction: column;
        overflow-y: auto;
    }

    .chat-sidebar-single {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .chat-sidebar-single:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .chat-sidebar-single.active {
        background-color: rgba(0, 0, 0, 0.1);
    }

    .chat-message-content {
        max-width: 70%;
        word-break: break-word;
    }

    .chat-single-message.right .chat-message-content {
        background-color: #007bff;
        color: white;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
    }

    .chat-single-message.left .chat-message-content {
        background-color: #e9ecef;
        color: #000;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
    }

    .chat-single-message {
        display: flex;
        margin-bottom: 1rem;
    }

    .chat-single-message.right {
        justify-content: flex-end;
    }

    .chat-single-message.left {
        justify-content: flex-start;
    }

    .btn-group-sm .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
</style>
@endpush