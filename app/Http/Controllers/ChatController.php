<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $chat = Chat::where('user_id', Auth::id())->first();

        if (!$chat) {
            $chat = Chat::create([
                'user_id' => Auth::id(),
                'subject' => 'Customer Support Request',
                'status' => 'open'
            ]);
        }

        $messages = $chat->messages()->orderBy('created_at', 'asc')->get();

        return view('customer.chat.index', compact('chat', 'messages'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $chat = Chat::where('user_id', Auth::id())->first();

        if (!$chat) {
            $chat = Chat::create([
                'user_id' => Auth::id(),
                'subject' => 'Customer Support Request',
                'status' => 'open'
            ]);
        }

        $message = ChatMessage::create([
            'chat_id' => $chat->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_from_admin' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully!',
            'data' => [
                'id' => $message->id,
                'message' => $message->message,
                'created_at' => $message->created_at->format('g:i A'),
                'is_from_admin' => false
            ]
        ]);
    }

    public function getNewMessages(Request $request)
    {
        $chat = Chat::where('user_id', Auth::id())->first();

        if (!$chat) {
            return response()->json([
                'success' => false,
                'message' => 'No chat found'
            ]);
        }

        $lastMessageId = $request->input('last_message_id', 0);

        $newMessages = $chat->messages()
            ->where('id', '>', $lastMessageId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'created_at' => $message->created_at->format('g:i A'),
                    'is_from_admin' => $message->is_from_admin,
                    'user_id' => $message->user_id
                ];
            });

        return response()->json([
            'success' => true,
            'new_messages' => $newMessages,
            'last_message_id' => $newMessages->last() ? $newMessages->last()['id'] : $lastMessageId
        ]);
    }

    public function markAsRead(Request $request)
    {
        $chat = Chat::where('user_id', Auth::id())->first();

        if (!$chat) {
            return response()->json([
                'success' => false,
                'message' => 'No chat found'
            ]);
        }

        // Mark all messages as read
        $chat->messages()->where('is_from_admin', true)->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Messages marked as read'
        ]);
    }
}
