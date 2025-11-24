@extends('layouts.admin')

@section('page-title', 'Chat Conversation')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Chat Conversation</h1>
                <p class="text-muted mb-0">Support conversation with {{ $chat->user->name ?? 'Guest' }}</p>
            </div>
            <div>
                <a href="{{ route('admin.chats.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Chats
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Chat Messages -->
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="m-0 font-weight-bold text-primary">{{ $chat->subject }}</h6>
                            <small class="text-muted">#{{ $chat->id }}</small>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="badge {{ $chat->status_badge_class }}">
                                {{ ucfirst(str_replace('_', ' ', $chat->status)) }}
                            </span>
                            
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chat-messages" id="chatMessages" style="height: 400px; overflow-y: auto;">
                        @foreach($chat->messages as $message)
                            <div class="message {{ $message->is_admin ? 'admin-message' : 'customer-message' }} mb-3">
                                <div class="message-content">
                                    <div class="message-header">
                                        <strong>{{ $message->sender_name }}</strong>
                                        <small class="text-muted">{{ $message->readable_time }}</small>
                                    </div>
                                    <div class="message-text">
                                        {{ $message->message }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Send Message Form -->
                    <form method="POST" action="{{ route('admin.chats.send-message', $chat) }}" class="mt-3">
                        @csrf
                        <div class="input-group">
                            <textarea name="message" class="form-control" rows="2"
                                      placeholder="Type your message..." required></textarea>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Send
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Chat Info Sidebar -->
        <div class="col-lg-4">
            <!-- Customer Information -->
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Customer Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Name:</strong> {{ $chat->user->name ?? 'Guest' }}
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong> {{ $chat->user->email ?? 'No email' }}
                    </div>
                    <div class="mb-3">
                        <strong>Created:</strong> {{ $chat->created_at->format('M d, Y h:i A') }}
                    </div>
                    @if($chat->assignedAdmin)
                        <div class="mb-3">
                            <strong>Assigned to:</strong> {{ $chat->assignedAdmin->name }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Chat Actions -->
            <div class="card shadow mt-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Chat Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <!-- Status Update -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Update Status</label>
                            <form method="POST" action="{{ route('admin.chats.update-status', $chat) }}">
                                @csrf
                                @method('PATCH')
                                <div class="input-group">
                                    <select name="status" class="form-select">
                                        <option value="open" {{ $chat->status === 'open' ? 'selected' : '' }}>Open</option>
                                        <option value="in_progress" {{ $chat->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="resolved" {{ $chat->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                        <option value="closed" {{ $chat->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>

                        <!-- Assign Chat -->
                        <div class="mb-3">
                            <label for="assigned_to" class="form-label">Assign to Admin</label>
                            <form method="POST" action="{{ route('admin.chats.assign', $chat) }}">
                                @csrf
                                @method('PATCH')
                                <div class="input-group">
                                    <select name="assigned_to" class="form-select">
                                        <option value="">Unassigned</option>
                                        @foreach(\App\Models\User::where('is_admin', true)->get() as $admin)
                                            <option value="{{ $admin->id }}" {{ $chat->assigned_to == $admin->id ? 'selected' : '' }}>
                                                {{ $admin->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-warning">Assign</button>
                                </div>
                            </form>
                        </div>

                        <!-- Quick Actions -->
                        @if($chat->status !== 'closed')
                            <form method="POST" action="{{ route('admin.chats.close', $chat) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-check-circle"></i> Close Chat
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.chats.reopen', $chat) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="bi bi-arrow-clockwise"></i> Reopen Chat
                                </button>
                            </form>
                        @endif

                        <button type="button" class="btn btn-danger" onclick="deleteChat({{ $chat->id }})">
                            <i class="bi bi-trash"></i> Delete Chat
                        </button>
                    </div>
                </div>
            </div>

            <!-- Chat Statistics -->
            <div class="card shadow mt-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Chat Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h4 class="text-primary">{{ $chat->messages->count() }}</h4>
                            <small class="text-muted">Total Messages</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success">{{ $chat->messages->where('is_admin', false)->count() }}</h4>
                            <small class="text-muted">Customer Messages</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <h4 class="text-info">{{ $chat->messages->where('is_admin', true)->count() }}</h4>
                            <small class="text-muted">Admin Messages</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-warning">{{ $chat->messages->where('is_read', false)->count() }}</h4>
                            <small class="text-muted">Unread Messages</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteChatModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this chat conversation? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteChatForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.chat-messages {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
}

.message {
    display: flex;
    margin-bottom: 15px;
}

.admin-message {
    justify-content: flex-end;
}

.customer-message {
    justify-content: flex-start;
}

.message-content {
    max-width: 70%;
    padding: 10px 15px;
    border-radius: 15px;
    position: relative;
}

.admin-message .message-content {
    background: #007bff;
    color: white;
    border-bottom-right-radius: 5px;
}

.customer-message .message-content {
    background: white;
    color: #333;
    border: 1px solid #e9ecef;
    border-bottom-left-radius: 5px;
}

.message-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 5px;
    font-size: 12px;
}

.message-text {
    word-wrap: break-word;
}

.admin-message .message-header {
    color: rgba(255, 255, 255, 0.8);
}

.customer-message .message-header {
    color: #6c757d;
}
</style>
@endpush

@push('scripts')
<script>
// Auto-scroll to bottom of chat messages
document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
});

function deleteChat(chatId) {
    const modal = new bootstrap.Modal(document.getElementById('deleteChatModal'));
    const form = document.getElementById('deleteChatForm');
    form.action = `/admin/chats/${chatId}`;
    modal.show();
}
</script>
@endpush
@endsection
