<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Chat::with(['user', 'messages', 'assignedAdmin']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

     

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        $chats = $query->latest()->get();

        return view('admin.chats.index', compact('chats'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Chat $chat)
    {
        $chat->load(['user', 'messages.user']);

        // Mark messages as read
        $chat->messages()->where('user_id', '!=', Auth::id())->update(['is_read' => true]);

        return view('admin.chats.show', compact('chat'));
    }

    /**
     * Send a message in the chat
     */
    public function sendMessage(Request $request, Chat $chat)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $chat->messages()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_admin' => true,
            'is_read' => false,
        ]);

        // Update chat status if it was closed
        if ($chat->status === 'closed') {
            $chat->update(['status' => 'in_progress']);
        }

        return redirect()->back()->with('success', 'Message sent successfully.');
    }

    /**
     * Update chat status
     */
    public function updateStatus(Request $request, Chat $chat)
    {
        $request->validate([
            'status' => 'required|string|in:open,in_progress,resolved,closed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $chat->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'resolved_at' => $request->status === 'resolved' ? now() : null,
            'closed_at' => $request->status === 'closed' ? now() : null,
        ]);

        return redirect()->back()->with('success', 'Chat status updated successfully.');
    }

    /**
     * Assign chat to admin
     */
    public function assign(Request $request, Chat $chat)
    {
        $request->validate([
            'admin_id' => 'required|exists:users,id',
        ]);

        $chat->update([
            'assigned_to' => $request->admin_id,
            'assigned_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Chat assigned successfully.');
    }

    /**
     * Close chat
     */
    public function close(Request $request, Chat $chat)
    {
        $request->validate([
            'resolution_notes' => 'nullable|string|max:1000',
        ]);

        $chat->update([
            'status' => 'closed',
            'admin_notes' => $request->resolution_notes,
            'closed_at' => now(),
            'closed_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Chat closed successfully.');
    }

    /**
     * Reopen chat
     */
    public function reopen(Chat $chat)
    {
        $chat->update([
            'status' => 'open',
            'closed_at' => null,
            'closed_by' => null,
        ]);

        return redirect()->back()->with('success', 'Chat reopened successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chat $chat)
    {
        // Delete all messages first
        $chat->messages()->delete();
        $chat->delete();

        return redirect()->route('admin.chats.index')
            ->with('success', 'Chat deleted successfully.');
    }

    /**
     * Export chat history
     */
    public function export(Chat $chat)
    {
        $chat->load(['user', 'messages.user']);

        $filename = 'chat_' . $chat->id . '_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($chat) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, [
                'Chat ID', 'Customer', 'Status', 'Created At', 'Resolved At', 'Closed At'
            ]);

            // Add chat info
            fputcsv($file, [
                $chat->id,
                $chat->user->name ?? 'Guest',
                $chat->status,
                $chat->created_at->format('Y-m-d H:i:s'),
                $chat->resolved_at ? $chat->resolved_at->format('Y-m-d H:i:s') : 'N/A',
                $chat->closed_at ? $chat->closed_at->format('Y-m-d H:i:s') : 'N/A',
            ]);

            // Add separator
            fputcsv($file, ['']);

            // Add message headers
            fputcsv($file, [
                'Message ID', 'From', 'Message', 'Is Admin', 'Is Read', 'Created At'
            ]);

            // Add messages
            foreach ($chat->messages as $message) {
                fputcsv($file, [
                    $message->id,
                    $message->user->name ?? 'Guest',
                    $message->message,
                    $message->is_admin ? 'Yes' : 'No',
                    $message->is_read ? 'Yes' : 'No',
                    $message->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get chat statistics
     */
    public function statistics()
    {
        $stats = [
            'total_chats' => Chat::count(),
            'open_chats' => Chat::where('status', 'open')->count(),
            'in_progress_chats' => Chat::where('status', 'in_progress')->count(),
            'resolved_chats' => Chat::where('status', 'resolved')->count(),
            'closed_chats' => Chat::where('status', 'closed')->count(),
            'total_messages' => ChatMessage::count(),
            'unread_messages' => ChatMessage::where('is_read', false)
                ->where('is_admin', false)
                ->count(),
        ];

        // Derived metrics used in the view (basic placeholders / computed values)
        $stats['avg_response_time'] = 'N/A';
        $stats['resolution_rate'] = $stats['total_chats'] > 0
            ? round(($stats['resolved_chats'] / $stats['total_chats']) * 100, 1)
            : 0;
        $stats['satisfaction_score'] = 0; // Placeholder until explicit ratings are implemented
        $stats['avg_messages_per_chat'] = $stats['total_chats'] > 0
            ? round($stats['total_messages'] / $stats['total_chats'], 1)
            : 0;
        $stats['peak_hours'] = 'N/A';

        // Get recent activity
        $recentChats = Chat::with(['user', 'lastMessage'])
            ->latest()
            ->take(10)
            ->get();

        // Get chat volume by day (last 30 days)
        $chatVolume = Chat::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $chatVolumeLabels = $chatVolume->pluck('date')->map(function ($date) {
            return Carbon::parse($date)->format('M d');
        });

        $chatVolumeData = $chatVolume->pluck('count');

        return view('admin.chats.statistics', [
            'stats' => $stats,
            'recentChats' => $recentChats,
            'chatVolumeLabels' => $chatVolumeLabels,
            'chatVolumeData' => $chatVolumeData,
        ]);
    }
}
