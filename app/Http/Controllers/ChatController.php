<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'trainer') {
            $conversations = Conversation::where('trainer_id', Auth::id())
                ->with('member')
                ->orderBy('last_message_at', 'desc')
                ->get();
        } else {
            $conversations = Conversation::where('member_id', Auth::id())
                ->with('trainer')
                ->orderBy('last_message_at', 'desc')
                ->get();
        }
        return view('chat.index', compact('conversations'));
    }

    public function show($conversationId)
    {
        $conversation = Conversation::with('messages.sender', 'member', 'trainer')
            ->findOrFail($conversationId);
        return view('chat.show', compact('conversation'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message' => 'required_without:exercise_data|string|nullable',
            'exercise_data' => 'nullable|json',
        ]);

        $message = Message::create([
            'conversation_id' => $request->conversation_id,
            'sender_id' => Auth::id(),
            'message' => $request->message,
            'type' => $request->exercise_data ? 'exercise' : 'text',
            'exercise_data' => $request->exercise_data ? json_decode($request->exercise_data, true) : null,
        ]);

        $conversation = Conversation::find($request->conversation_id);
        $conversation->update(['last_message_at' => now()]);

        return response()->json($message->load('sender'));
    }

    public function sendExercise(Request $request)
    {
        return $this->sendMessage($request);
    }
}
