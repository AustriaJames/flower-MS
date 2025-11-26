<?php
// Admin can initiate a chat with any user by user_id
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatInitiateController extends Controller
{
    // Show form to select user and send message
    public function create()
    {
        $users = User::where('is_admin', false)->get();
        return view('admin.chats.initiate', compact('users'));
    }

    // Store: create chat if not exists, send message
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        $user = User::findOrFail($request->user_id);
        // Find or create chat, always provide subject
        $chat = Chat::firstOrCreate(
            ['user_id' => $user->id],
            [
                'status' => 'open',
                'subject' => 'Support Chat with ' . ($user->name ?? $user->email)
            ]
        );
        // Send message as admin
        $chat->messages()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_admin' => true,
            'is_read' => false,
        ]);
        return redirect()->route('admin.chats.show', $chat)->with('success', 'Message sent to user!');
    }
}
