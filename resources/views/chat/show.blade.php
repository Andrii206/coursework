@extends('layouts.main')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-sm rounded-4 overflow-hidden" style="height: 85vh;">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center sticky-top">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('chats.index') }}" class="btn btn-light btn-sm rounded-circle me-3">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div>
                            <h5 class="mb-0 fw-bold">{{ $interlocutor->name }}</h5>
                            <small class="text-muted">{{ $chat->blocked ? 'Чат заблоковано' : 'Онлайн' }}</small>
                        </div>
                    </div>
                    <form action="{{ route('chats.block', $chat->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm {{ $chat->blocked ? 'btn-danger' : 'btn-outline-secondary' }} rounded-pill px-3">
                            <i class="bi {{ $chat->blocked ? 'bi-lock-fill' : 'bi-unlock' }} me-1"></i>
                            {{ $chat->blocked ? 'Розблокувати' : 'Заблокувати' }}
                        </button>
                    </form>
                </div>
                <div id="app" style="flex: 1; overflow: hidden; display: flex; flex-direction: column;">
                    <chat-component 
                        :chat-id="{{ $chat->id }}"
                        :current-user-id="{{ auth()->id() }}"
                        :initial-messages='@json($messages)'
                        :is-blocked="{{ $chat->blocked ? 'true' : 'false' }}"
                    ></chat-component>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection