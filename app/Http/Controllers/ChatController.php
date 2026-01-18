<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Notifications\NewChatMessage;
use App\Http\Requests\SendMessageRequest;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ChatController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display all conversations for the authenticated user
     */
    public function index()
    {
        $user = auth()->user();

        $conversations = Conversation::where('manager_id', $user->id)
            ->orWhere('employee_id', $user->id)
            ->with(['manager', 'employee', 'messages' => function($query) {
                $query->latest()->limit(1);
            }])
            ->latest('updated_at')
            ->get();

        return view('chat.index', compact('conversations'));
    }

    /**
     * Show a specific conversation with all messages
     */
    public function show(Conversation $conversation)
    {
        // Authorize that user is part of this conversation
        $this->authorize('view', $conversation);

        // Mark messages as read
        $conversation->messages()
            ->where('sender_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $conversation->messages()
            ->with('sender')
            ->latest()
            ->get();

        return view('chat.show', compact('conversation', 'messages'));
    }

    /**
     * Send a message in a conversation
     */
    public function send(SendMessageRequest $request, Conversation $conversation)
    {
        // Authorize that user is part of this conversation
        $this->authorize('view', $conversation);

        // Get validated data
        $validated = $request->validated();

        // Create the message
        $msg = $conversation->messages()->create([
            'sender_id' => auth()->id(),
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        // Determine the receiver
        $receiver = auth()->id() == $conversation->manager_id
            ? $conversation->employee
            : $conversation->manager;

        // Send notification to receiver
        if ($receiver) {
            $receiver->notify(new NewChatMessage($msg));
        }

        // Update conversation timestamp
        $conversation->touch();

        return redirect()->route('chat.show', $conversation)
            ->with('success', 'تم إرسال الرسالة بنجاح');
    }

    /**
     * Create a new conversation
     */
    public function create()
    {
        // Get all users except the current user
        $users = \App\Models\User::where('id', '!=', auth()->id())->get();
        return view('chat.create', compact('users'));
    }

    /**
     * Store a new conversation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:users,id|different:' . auth()->id(),
        ], [
            'employee_id.required' => 'يجب اختيار مستخدم',
            'employee_id.exists' => 'المستخدم غير موجود',
            'employee_id.different' => 'لا يمكنك المحادثة مع نفسك',
        ]);

        // Check if conversation already exists
        $existingConversation = Conversation::where(function ($query) use ($validated) {
            $query->where('manager_id', auth()->id())
                  ->where('employee_id', $validated['employee_id']);
        })->orWhere(function ($query) use ($validated) {
            $query->where('manager_id', $validated['employee_id'])
                  ->where('employee_id', auth()->id());
        })->first();

        if ($existingConversation) {
            return redirect()->route('chat.show', $existingConversation)
                ->with('info', 'المحادثة موجودة بالفعل');
        }

        // Create new conversation
        $conversation = Conversation::create([
            'manager_id' => auth()->id(),
            'employee_id' => $validated['employee_id'],
        ]);

        return redirect()->route('chat.show', $conversation)
            ->with('success', 'تم إنشاء محادثة جديدة بنجاح');
    }

    /**
     * Delete a conversation
     */
    public function destroy(Conversation $conversation)
    {
        // Authorize that user is part of this conversation
        $this->authorize('delete', $conversation);

        // Delete all messages first
        $conversation->messages()->delete();
        
        // Delete the conversation
        $conversation->delete();

        return redirect()->route('chat.index')
            ->with('success', 'تم حذف المحادثة بنجاح');
    }
}