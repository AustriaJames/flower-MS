@extends('layouts.admin')

@section('page-title', 'Chat Support')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Chat Support</h1>
                <p class="text-muted mb-0">Manage customer support conversations</p>
            </div>
            <div>
                <a href="{{ route('admin.chats.statistics') }}" class="btn btn-info">
                    <i class="bi bi-graph-up"></i> Statistics
                </a>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="search-filters">
        <form method="GET" action="{{ route('admin.chats.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="priority" class="form-label">Priority</label>
                <select name="priority" id="priority" class="form-select">
                    <option value="">All Priorities</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="assigned_to" class="form-label">Assigned To</label>
                <select name="assigned_to" id="assigned_to" class="form-select">
                    <option value="">All Admins</option>
                    @foreach(\App\Models\User::where('is_admin', true)->orderBy('name')->get() as $admin)
                        <option value="{{ $admin->id }}" {{ request('assigned_to') == $admin->id ? 'selected' : '' }}>
                            {{ $admin->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.chats.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Chats List -->
    <div class="content-section">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Support Conversations</h6>
            </div>
            <div class="card-body">
                @if($chats->count() > 0)
                    <div class="row">
                        @foreach($chats as $chat)
                            <div class="col-lg-6 col-xl-4 mb-4">
                                <div class="chat-card card h-100 {{ $chat->status === 'open' ? 'border-primary' : '' }}">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">{{ $chat->subject }}</h6>
                                            <small class="text-muted">#{{ $chat->id }}</small>
                                        </div>
                                        <div class="d-flex gap-1">
                                            <span class="badge {{ $chat->status_badge_class }}">
                                                {{ ucfirst(str_replace('_', ' ', $chat->status)) }}
                                            </span>
                                            <span class="badge {{ $chat->priority_badge_class }}">
                                                {{ ucfirst($chat->priority) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <strong>Customer:</strong> {{ $chat->user->name ?? 'Guest' }}<br>
                                            <small class="text-muted">{{ $chat->user->email ?? 'No email' }}</small>
                                        </div>

                                        <div class="mb-3">
                                            <strong>Last Message:</strong><br>
                                            <small class="text-muted">
                                                @if($chat->messages->count() > 0)
                                                    {{ Str::limit($chat->messages->last()->message, 80) }}
                                                @else
                                                    No messages yet
                                                @endif
                                            </small>
                                        </div>

                                        <div class="mb-3">
                                            <strong>Assigned To:</strong><br>
                                            <small class="text-muted">
                                                @if($chat->assignedAdmin)
                                                    {{ $chat->assignedAdmin->name }}
                                                @else
                                                    Unassigned
                                                @endif
                                            </small>
                                        </div>

                                        <div class="mb-3">
                                            <strong>Created:</strong><br>
                                            <small class="text-muted">{{ $chat->created_at->format('M d, Y g:i A') }}</small>
                                        </div>

                                        @if($chat->messages->count() > 0)
                                            <div class="mb-3">
                                                <strong>Last Activity:</strong><br>
                                                <small class="text-muted">{{ $chat->messages->last()->created_at->format('M d, Y g:i A') }}</small>
                                            </div>
                                        @endif

                                        @if($chat->messages->where('is_read', false)->count() > 0)
                                            <div class="mb-3">
                                                <span class="badge bg-danger">{{ $chat->messages->where('is_read', false)->count() }} new messages</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-footer">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('admin.chats.show', $chat) }}"
                                                   class="btn btn-primary btn-sm">
                                                    <i class="bi bi-chat"></i> Open Chat
                                                </a>
                                                @if($chat->status === 'closed')
                                                    <button type="button" class="btn btn-success btn-sm"
                                                            onclick="reopenChat({{ $chat->id }})">
                                                        <i class="bi bi-arrow-clockwise"></i> Reopen
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-secondary btn-sm"
                                                            onclick="closeChat({{ $chat->id }})">
                                                        <i class="bi bi-x-circle"></i> Close
                                                    </button>
                                                @endif
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle"
                                                        type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.chats.export', $chat) }}">
                                                            <i class="bi bi-download"></i> Export
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <button type="button" class="dropdown-item text-danger"
                                                                onclick="deleteChat({{ $chat->id }})">
                                                            <i class="bi bi-trash"></i> Delete
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="bi bi-chat"></i>
                        <h5>No Chats Found</h5>
                        <p>There are no support conversations matching your criteria.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Close Chat Modal -->
<div class="modal fade" id="closeChatModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Close Chat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="closeChatForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <p>Are you sure you want to close this chat?</p>
                    <div class="mb-3">
                        <label for="admin_notes" class="form-label">Resolution Notes</label>
                        <textarea name="admin_notes" id="admin_notes" class="form-control"
                                  rows="3" placeholder="Brief summary of how the issue was resolved..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-secondary">Close Chat</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reopen Chat Modal -->
<div class="modal fade" id="reopenChatModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reopen Chat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="reopenChatForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <p>Are you sure you want to reopen this chat?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Reopen Chat</button>
                </div>
            </form>
        </div>
    </div>
</div>



@push('scripts')
<script>
function closeChat(chatId) {
    const modal = new bootstrap.Modal(document.getElementById('closeChatModal'));
    const form = document.getElementById('closeChatForm');
    form.action = `/admin/chats/${chatId}/close`;
    modal.show();
}

function reopenChat(chatId) {
    const modal = new bootstrap.Modal(document.getElementById('reopenChatModal'));
    const form = document.getElementById('reopenChatForm');
    form.action = `/admin/chats/${chatId}/reopen`;
    modal.show();
}

function deleteChat(chatId) {
    confirmDelete('Delete Chat?', 'Are you sure you want to delete this chat? This action cannot be undone and will remove all messages.')
    .then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/chats/${chatId}`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';

            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';

            form.appendChild(csrfToken);
            form.appendChild(method);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush
@endsection
