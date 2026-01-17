
@extends('layouts.admin')

@section('content')

<style>
/* ===== Animations ===== */
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes pulse {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(255,193,7,0.7);
    }
    50% {
        box-shadow: 0 0 0 10px rgba(255,193,7,0);
    }
}

@keyframes glow {
    0%, 100% {
        box-shadow: 0 0 5px rgba(255,193,7,0.3);
    }
    50% {
        box-shadow: 0 0 20px rgba(255,193,7,0.6);
    }
}

@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-5px);
    }
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-10px);
    }
}

@keyframes scaleIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes shimmer {
    0% {
        box-shadow: 0 0 0 0 rgba(255,193,7,0.7);
    }
    50% {
        box-shadow: 0 0 30px 10px rgba(255,193,7,0.3);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(255,193,7,0);
    }
}

/* ===== Card Glass Modern ===== */
.card-glass {
    background: rgba(30,30,30,0.85);
    border-radius: 20px;
    padding: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.35);
    border: 1px solid rgba(255,193,7,0.2);
    transition: all 0.3s ease;
    animation: fadeInDown 0.6s ease;
}
.card-glass:hover {
    animation: shimmer 0.8s ease, float 3s ease-in-out infinite;
}

/* Chat Card Right Side Animation */
.col-md-8 .card-glass:hover {
    animation: shimmer 0.8s ease, float 3s ease-in-out infinite !important;
}

/* ===== Conversation List ===== */
.list-group-item {
    border-radius: 25px !important;
    margin-bottom: 10px;
    transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
    border: 1px solid rgba(255,193,7,0.3) !important;
    animation: slideInLeft 0.5s ease;
}

.list-group-item:hover {
    transform: translateX(8px) scale(1.02);
    background: rgba(255,193,7,0.15) !important;
    border: 1px solid rgba(255,193,7,0.5) !important;
    box-shadow: 0 5px 15px rgba(255,193,7,0.2);
}
.list-group-item.active {
    background: linear-gradient(90deg, #ffc107, #ffecb3);
    color: #111;
    font-weight: bold;
    border: 1px solid #ffc107 !important;
    box-shadow: 0 0 20px rgba(255,193,7,0.5);
    animation: pulse 2s infinite;
}

/* ===== Messages Container ===== */
.messages-container {
    display: flex;
    flex-direction: column-reverse; /* الحديث في الأعلى */
    overflow-y: auto;
    padding: 15px;
    height: 500px;
    scroll-behavior: smooth;
}
.messages-container::-webkit-scrollbar {
    width: 8px;
}
.messages-container::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.05);
    border-radius: 10px;
}
.messages-container::-webkit-scrollbar-thumb {
    background: rgba(255,193,7,0.3);
    border-radius: 10px;
}
.messages-container::-webkit-scrollbar-thumb:hover {
    background: rgba(255,193,7,0.6);
}

/* ===== Message Bubbles ===== */
.message-bubble {
    max-width: 70%;
    border-radius: 20px;
    padding: 12px 18px;
    margin-bottom: 12px;
    position: relative;
    word-break: break-word;
    transition: all 0.3s ease;
}

/* Sender / Receiver Colors */
.message-bubble.sender {
    background: linear-gradient(135deg, #0d6efd, #0a58ca);
    color: #fff;
    align-self: flex-end;
    animation: slideInRight 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
}
.message-bubble.sender:hover {
    transform: translateX(-5px) scale(1.02);
    box-shadow: 0 6px 20px rgba(13, 110, 253, 0.5);
}

.message-bubble.receiver {
    background: linear-gradient(135deg, rgba(108,117,125,0.95), rgba(80,90,100,0.95));
    color: #fff;
    align-self: flex-start;
    animation: slideInLeft 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    box-shadow: 0 4px 12px rgba(108,117,125,0.3);
}
.message-bubble.receiver:hover {
    transform: translateX(5px) scale(1.02);
    box-shadow: 0 6px 20px rgba(108,117,125,0.5);
}

/* Message Meta */
.message-meta {
    font-size: 10px;
    opacity: 0.7;
    display: flex;
    justify-content: space-between;
    margin-top: 5px;
}

/* ===== Input Field ===== */
.input-group {
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.input-group:hover,
.input-group:has(input:focus),
.input-group:has(button:hover) {
    animation: slideInRight 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}

.input-group input {
    border-radius: 25px;
    padding: 12px 20px;
    background: #ffffff !important;
    color: #333 !important;
    border: 2px solid #ffc107;
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.1);
}
.input-group input::placeholder {
    color: rgba(0,0,0,0.5);
    transition: all 0.3s ease;
}
.input-group input:focus {
    background: #ffffff !important;
    box-shadow: 0 0 20px rgba(255,193,7,0.6);
    border-color: #ffca2c;
    color: #333 !important;
    transform: scale(1.02);
}
.input-group input:focus::placeholder {
    color: rgba(0,0,0,0.2);
}

.input-group button {
    border-radius: 25px;
    padding: 10px 22px;
    background: #ffc107 !important;
    border: 2px solid #ffc107 !important;
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    color: #111 !important;
    font-weight: bold;
}
.input-group button:hover {
    transform: scale(1.08);
    box-shadow: 0 6px 20px rgba(255,193,7,0.5);
    background: #ffca2c !important;
    animation: shimmer 0.6s ease;
}
.input-group button:active {
    animation: bounce 0.3s ease;
}
</style>

<div class="container">
    <div class="row">

        <!-- Conversation List -->
        <div class="col-md-4 mb-4">
            <div class="card-glass h-100 p-3">
                <h5 class="mb-3 text-warning"><i class="bi bi-chat-dots-fill"></i> المحادثات</h5>

                @php
                    $allConversations = \App\Models\Conversation::where('manager_id', auth()->id())
                        ->orWhere('employee_id', auth()->id())
                        ->with(['manager', 'employee'])
                        ->latest('updated_at')
                        ->get();
                @endphp

                @if($allConversations->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($allConversations as $conv)
                            <a href="{{ route('chat.show', $conv) }}"
                               class="list-group-item {{ $conversation && $conv->id == $conversation->id ? 'active' : 'bg-dark border-warning text-light' }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6>{{ $conv->otherParticipant(auth()->id())->name }}</h6>
@php $lastMessage = $conv->messages()->latest()->first(); @endphp
                                        <small class="text-muted">{{ $lastMessage ? Str::limit($lastMessage->message, 35) : 'لا توجد رسائل' }}</small>
                                    </div>
                                    @php $unread = $conv->unreadMessagesCount(auth()->id()); @endphp
                                    @if($unread > 0)
                                        <span class="badge bg-danger">{{ $unread }}</span>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info text-center mt-3">
                        لا توجد محادثات بعد.
                    </div>
                @endif
            </div>
        </div>

        <!-- Chat Area -->
        <div class="col-md-8">
            <div class="card-glass d-flex flex-column" style="height: 600px; animation: scaleIn 0.6s ease;">

                <!-- Chat Header -->
                <div class="border-bottom border-warning pb-3 mb-3 d-flex justify-content-between align-items-center" style="animation: fadeInDown 0.6s ease;">
                    <h5 class="mb-0">
                        <i class="bi bi-person-fill"></i> {{ $conversation->otherParticipant(auth()->id())->name }}
                    </h5>
                    <form action="{{ route('chat.destroy', $conversation) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" style="transition: all 0.3s ease; border-radius: 20px;">
                            <i class="bi bi-trash"></i> حذف
                        </button>
                    </form>
                </div>

                <!-- Messages -->
                <div class="messages-container flex-grow-1" style="animation: fadeInDown 0.8s ease 0.3s both;">
                    @foreach($messages as $message) <!-- الأقدم أولًا -->
                        <div class="message-bubble {{ $message->sender_id == auth()->id() ? 'sender ms-auto' : 'receiver me-auto' }}" style="animation-delay: 0.1s;">
                            @if($message->sender_id != auth()->id())
                                <small class="fw-bold">{{ $message->sender->name }}</small>
                            @endif
                            <p class="mb-0">{{ $message->message }}</p>
                            <div class="message-meta">
                                <small>{{ $message->created_at->format('H:i') }}</small>
                                @if($message->is_read && $message->sender_id == auth()->id())
                                    <i class="bi bi-check2-all"></i>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Send Message -->
                <form action="{{ route('chat.send', $conversation) }}" method="POST" class="mt-2">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="message" placeholder="اكتب رسالة..." class="form-control" required>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const messagesContainer = document.querySelector('.messages-container');
    if(messagesContainer) {
        messagesContainer.scrollTop = 0; // الأعلى (الرسائل الحديثة)
    }
});
</script>

@endsection