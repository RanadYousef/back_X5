
@extends('layouts.admin')

@section('content')

<style>

.fade-in {
    animation: fadeIn .5s ease forwards;
}

@keyframes fadeIn {
    from {opacity:0; transform: translateY(10px);}
    to {opacity:1; transform: translateY(0);}
}

.conversation-item{
    transition: all .25s ease;
    border: 1px solid transparent;
}

.conversation-item:hover{
    background: rgba(255,193,7,0.08);
    transform: translateX(6px);
    border-color: rgba(255,193,7,0.4);
    box-shadow: 0 0 12px rgba(255,193,7,0.15);
}

.avatar-circle{
    width:42px;
    height:42px;
    border-radius:50%;
    background: linear-gradient(45deg,#ffc107,#ff9800);
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:bold;
    color:#000;
    font-size:18px;
    transition: .3s;
}

.conversation-item:hover .avatar-circle{
    transform: scale(1.08) rotate(3deg);
}

.badge{
    animation: pop .3s ease;
}

@keyframes pop{
    0%{transform:scale(.6);}
    100%{transform:scale(1);}
}

.empty-chat{
    animation: fadeIn 0.6s ease;
}

</style>

<div class="container-fluid fade-in">
    <div class="row">

        <!-- القائمة -->
        <div class="col-md-4">
            <div class="card-glass mb-4 h-100 fade-in">

                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                    <h5 class="mb-0">
                        <i class="bi bi-chat-dots-fill text-warning"></i> المحادثات
                    </h5>

                    <a href="{{ route('chat.create') }}" class="btn btn-sm btn-warning rounded-pill">
                        <i class="bi bi-plus-circle"></i>
                    </a>
                </div>

                @if($conversations->count())

                    <div class="list-group list-group-flush">

                        @foreach($conversations as $conversation)

                            @php
                                $user = $conversation->otherParticipant(auth()->id());
                                $lastMessage = $conversation->messages()->latest()->first();
                                $unread = $conversation->unreadMessagesCount(auth()->id());
                            @endphp

                            <div class="list-group-item bg-transparent border-0 px-0 fade-in">
                                <div class="conversation-item d-flex align-items-center p-2 rounded">

                                    <div class="me-3">
                                        <div class="avatar-circle">
                                            {{ strtoupper(substr($user->name,0,1)) }}
                                        </div>
                                    </div>

                                    <a href="{{ route('chat.show',$conversation) }}"
                                       class="flex-grow-1 text-decoration-none text-light">

                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-1">{{ $user->name }}</h6>

                                            @if($lastMessage)
                                                <small class="text-muted">
                                                    {{ $lastMessage->created_at->diffForHumans() }}
                                                </small>
                                            @endif
                                        </div>

                                        <small class="text-muted">
                                            {{ $lastMessage ? Str::limit($lastMessage->message,40) : 'لا توجد رسائل بعد' }}
                                        </small>
                                    </a>

                                    <div class="ms-2 d-flex align-items-center gap-2">

                                        @if($unread)
                                            <span class="badge bg-danger rounded-pill">{{ $unread }}</span>
                                        @endif
<form action="{{ route('chat.destroy',$conversation) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger rounded-circle"
                                                    onclick="return confirm('حذف المحادثة؟')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>

                                    </div>

                                </div>
                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="text-center mt-4 empty-chat">
                        <i class="bi bi-chat-dots text-muted" style="font-size:2rem"></i>
                        <p class="text-muted mt-2">لا توجد محادثات</p>
                        <a href="{{ route('chat.create') }}" class="btn btn-warning btn-sm">
                            ابدأ محادثة جديدة
                        </a>
                    </div>

                @endif
            </div>
        </div>

        <!-- يمين -->
        <div class="col-md-8">
            <div class="card-glass text-center py-5 h-100 d-flex flex-column justify-content-center empty-chat">
                <i class="bi bi-chat-dots text-muted" style="font-size:3rem;"></i>
                <h5 class="mt-3">اختر محادثة</h5>
                <p class="text-muted">ابدأ بالتواصل مع الموظفين أو المدراء</p>

                <a href="{{ route('chat.create') }}" class="btn btn-warning rounded-pill px-4">
                    <i class="bi bi-plus-circle"></i> محادثة جديدة
                </a>
            </div>
        </div>

    </div>
</div>

@endsection