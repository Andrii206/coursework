@extends('layouts.main')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3">
                    <h4 class="mb-0 fw-bold">Ваші повідомлення</h4>
                </div>
                
                <div class="list-group list-group-flush">
                    @forelse($chats as $chat)
                        @php
                            // Визначаємо, хто співрозмовник
                            $interlocutor = $chat->sender_id === auth()->id() ? $chat->recipient : $chat->sender;
                            // Останнє повідомлення
                            $lastMessage = $chat->messages->first(); 
                        @endphp

                        <a href="{{ route('chats.show', $chat->id) }}" class="list-group-item list-group-item-action py-3 border-0 hover-bg-light">
                            <div class="d-flex align-items-center">
                                {{-- Аватарка (заглушка) --}}
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                        {{ substr($interlocutor->name, 0, 1) }}
                                    </div>
                                </div>
                                
                                <div class="flex-grow-1 ms-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="mb-0 fw-bold text-dark">{{ $interlocutor->name }}</h6>
                                        @if($lastMessage)
                                            <small class="text-muted">{{ $lastMessage->created_at->format('H:i') }}</small>
                                        @endif
                                    </div>
                                    
                                    <p class="mb-0 text-muted small text-truncate" style="max-width: 80%;">
                                        @if($lastMessage)
                                            @if($lastMessage->sender_id === auth()->id())
                                                <span class="text-primary">Ви:</span>
                                            @endif
                                            
                                            @if($lastMessage->file)
                                                <i class="bi bi-paperclip"></i> [Файл]
                                            @else
                                                {{ $lastMessage->text }}
                                            @endif
                                        @else
                                            <span class="text-info">Новий чат</span>
                                        @endif
                                    </p>
                                </div>
                                
                                {{-- Індикатор блокування --}}
                                @if($chat->blocked)
                                    <span class="badge bg-danger ms-2"><i class="bi bi-lock-fill"></i></span>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-chat-square-text display-1 text-muted opacity-25"></i>
                            <p class="mt-3 text-muted">У вас поки немає повідомлень.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-bg-light:hover { background-color: #f8f9fa; }
</style>
@endsection