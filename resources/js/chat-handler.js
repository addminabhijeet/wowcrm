class ChatHandler {
    constructor() {
        this.currentUserId = null;
        this.messagesContainer = document.querySelector('.chat-message-list');
        this.editor = document.querySelector('#editor');
        this.form = document.querySelector('.chat-message-box');
        this.chatInput = document.querySelector('#chatMessage');
        this.init();
    }

    init() {
        this.attachEventListeners();
        this.loadUsers();
        this.setupTiptap();
    }

    attachEventListeners() {
        // Send message
        this.form?.addEventListener('submit', (e) => {
            e.preventDefault();
            this.sendMessage();
        });

        // File upload buttons
        document.querySelector('button[title*="gallery"]')?.addEventListener('click', () => {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip';
            input.multiple = false;
            input.onchange = (e) => this.handleFileUpload(e.target.files[0]);
            input.click();
        });

        // Link upload
        document.querySelector('button[title*="link"]')?.addEventListener('click', () => {
            const input = document.createElement('input');
            input.type = 'file';
            input.onchange = (e) => this.handleFileUpload(e.target.files[0]);
            input.click();
        });
    }

    setupTiptap() {
        // Initialize Tiptap editor if not already done
        if (!window.tiptapEditor) {
            console.log('Initialize Tiptap with rich text formatting');
        }
    }

    loadUsers() {
        fetch('/api/chat/users')
            .then(res => res.json())
            .then(users => {
                this.renderUsersList(users);
            });
    }

    renderUsersList(users) {
        const usersList = document.querySelector('.chat-all-list');
        usersList.innerHTML = '';

        users.forEach(user => {
            const userEl = document.createElement('div');
            userEl.className = 'chat-sidebar-single' + (this.currentUserId === user.id ? ' active' : '');
            userEl.innerHTML = `
                <div class="img">
                    <img src="${user.image || '/assets/images/avatar.png'}" alt="${user.name}">
                </div>
                <div class="info">
                    <h6 class="text-sm mb-1">${user.name}</h6>
                    <p class="mb-0 text-xs">${user.last_message || 'No messages yet'}</p>
                </div>
                <div class="action text-end">
                    <p class="mb-0 text-neutral-400 text-xs lh-1">${user.last_message_time || ''}</p>
                    ${user.unread_count > 0 ? `
                        <span class="w-16-px h-16-px text-xs rounded-circle bg-warning-main text-white d-inline-flex align-items-center justify-content-center">
                            ${user.unread_count}
                        </span>
                    ` : ''}
                </div>
            `;

            userEl.addEventListener('click', () => {
                this.selectUser(user.id, user.name);
            });

            usersList.appendChild(userEl);
        });
    }

    selectUser(userId, userName) {
        this.currentUserId = userId;

        // Update UI
        document.querySelectorAll('.chat-sidebar-single').forEach(el => {
            el.classList.remove('active');
        });
        event.currentTarget.classList.add('active');

        // Update header
        document.querySelector('.chat-main .chat-sidebar-single h6').textContent = userName;

        // Load conversation
        this.loadConversation(userId);

        // Mark as read
        this.markAsRead(userId);
    }

    loadConversation(userId) {
        fetch(`/api/chat/conversation/${userId}`)
            .then(res => res.json())
            .then(data => {
                this.renderMessages(data.messages);
            });
    }

    renderMessages(messages) {
        this.messagesContainer.innerHTML = '';

        messages.forEach(msg => {
            const messageEl = this.createMessageElement(msg);
            this.messagesContainer.appendChild(messageEl);
        });

        // Scroll to bottom
        this.messagesContainer.scrollTop = this.messagesContainer.scrollHeight;
    }

    createMessageElement(msg) {
        const isOwn = msg.sender_id === this.getCurrentUserId();
        const div = document.createElement('div');
        div.className = `chat-single-message ${isOwn ? 'right' : 'left'}`;
        div.dataset.messageId = msg.id;
        div.innerHTML = `
            ${!isOwn ? `<img src="${msg.sender.image || '/assets/images/avatar.png'}" alt="${msg.sender.name}" class="avatar-lg object-fit-cover rounded-circle">` : ''}
            <div class="chat-message-content">
                ${this.renderMessageContent(msg)}
                <p class="chat-time mb-0">
                    <span>${msg.formatted_time}</span>
                    ${isOwn ? `<span class="ms-2">${msg.is_read ? '✓✓' : '✓'}</span>` : ''}
                </p>
                <div class="chat-message-actions">
                    ${isOwn ? `
                        <button class="btn-delete" data-id="${msg.id}">Delete</button>
                        ${msg.message_type === 'text' ? `<button class="btn-edit" data-id="${msg.id}">Edit</button>` : ''}
                    ` : ''}
                </div>
            </div>
        `;

        // Add event listeners
        div.querySelector('.btn-delete')?.addEventListener('click', () => this.deleteMessage(msg.id));
        div.querySelector('.btn-edit')?.addEventListener('click', () => this.editMessage(msg.id, msg.message));

        return div;
    }

    renderMessageContent(msg) {
        switch (msg.message_type) {
            case 'image':
                return `<img src="/storage/${msg.file_path}" class="chat-image" style="max-width: 300px; border-radius: 8px;">`;
            case 'video':
                return `<video controls style="max-width: 300px; border-radius: 8px;"><source src="/storage/${msg.file_path}"></video>`;
            case 'audio':
                return `<audio controls><source src="/storage/${msg.file_path}"></audio>`;
            case 'pdf':
            case 'document':
            case 'zip':
            case 'file':
                return `
                    <div class="file-message">
                        <p class="mb-2">${msg.file_name}</p>
                        <small>${msg.file_size_formatted}</small>
                        <a href="/api/chat/download/${msg.id}" class="btn btn-sm btn-primary mt-2">Download</a>
                    </div>
                `;
            default:
                return `<p class="mb-3">${msg.message}</p>`;
        }
    }

    sendMessage() {
        const message = this.editor.innerHTML;
        
        if (!message.trim() || !this.currentUserId) return;

        const formData = new FormData();
        formData.append('receiver_id', this.currentUserId);
        formData.append('message', message);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

        this.showLoading(true);

        fetch('/api/chat/send', {
            method: 'POST',
            body: formData,
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                this.editor.innerHTML = '';
                this.loadConversation(this.currentUserId);
            } else {
                alert('Error: ' + data.error);
            }
        })
        .finally(() => this.showLoading(false));
    }

    handleFileUpload(file) {
        if (!file || !this.currentUserId) return;

        const formData = new FormData();
        formData.append('file', file);
        formData.append('receiver_id', this.currentUserId);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

        this.showLoading(true);

        fetch('/api/chat/send', {
            method: 'POST',
            body: formData,
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                this.loadConversation(this.currentUserId);
            } else {
                alert('Error: ' + data.error);
            }
        })
        .finally(() => this.showLoading(false));
    }

    editMessage(messageId, currentMessage) {
        const newMessage = prompt('Edit message:', currentMessage);
        if (newMessage === null) return;

        fetch(`/api/chat/messages/${messageId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ message: newMessage }),
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                this.loadConversation(this.currentUserId);
            }
        });
    }

    deleteMessage(messageId) {
        if (!confirm('Delete message?')) return;

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
            }
        });
    }

    markAsRead(userId) {
        fetch('/api/chat/mark-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ sender_id: userId }),
        });
    }

    showLoading(show) {
        // Add loading state to send button
        const sendBtn = this.form.querySelector('button[type="submit"]');
        if (show) {
            sendBtn.disabled = true;
            sendBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';
        } else {
            sendBtn.disabled = false;
            sendBtn.innerHTML = 'Send <iconify-icon icon="f7:paperplane"></iconify-icon>';
        }
    }

    getCurrentUserId() {
        return document.querySelector('meta[name="user-id"]').content;
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new ChatHandler();
});