@extends('layouts.main')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-sm rounded-4 overflow-hidden" style="height: 85vh;">
                
                {{-- 1. Шапка чату --}}
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center sticky-top">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('chats.index') }}" class="btn btn-light btn-sm rounded-circle me-3"><i class="bi bi-arrow-left"></i></a>
                        <div>
                            <h5 class="mb-0 fw-bold">{{ $interlocutor->name }}</h5>
                            <small class="text-muted">{{ $chat->blocked ? 'Чат заблоковано' : 'Онлайн' }}</small>
                        </div>
                    </div>

                    {{-- Кнопка блокування --}}
                    <form action="{{ route('chats.block', $chat->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm {{ $chat->blocked ? 'btn-danger' : 'btn-outline-secondary' }} rounded-pill px-3">
                            <i class="bi {{ $chat->blocked ? 'bi-lock-fill' : 'bi-unlock' }} me-1"></i>
                            {{ $chat->blocked ? 'Розблокувати' : 'Заблокувати' }}
                        </button>
                    </form>
                </div>

                {{-- 2. Область повідомлень (Скрол) --}}
                <div class="card-body p-4 overflow-auto bg-light" id="chat-container" style="flex: 1;">
                    @foreach($messages as $msg)
                        @php
                            $isMyMessage = $msg->sender_id === auth()->id();
                        @endphp
                        
                        <div class="d-flex mb-3 {{ $isMyMessage ? 'justify-content-end' : 'justify-content-start' }}">
                            <div class="message-bubble p-3 shadow-sm {{ $isMyMessage ? 'bg-primary text-white rounded-bottom-right-0' : 'bg-white text-dark rounded-bottom-left-0' }}" 
                                 style="max-width: 75%; border-radius: 15px;">
                                
                                {{-- Текст --}}
                                @if($msg->text)
                                    <p class="mb-1 text-break">{{ $msg->text }}</p>
                                @endif

                                {{-- Файл / Зображення --}}
                                @if($msg->file)
                                    <div class="mt-2">
                                        @php
                                            $extension = pathinfo($msg->file, PATHINFO_EXTENSION);
                                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                        @endphp

                                        @if($isImage)
                                            <a href="{{ asset('storage/' . $msg->file) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $msg->file) }}" alt="image" class="img-fluid rounded border" style="max-height: 200px;">
                                            </a>
                                        @else
                                            <a href="{{ asset('storage/' . $msg->file) }}" target="_blank" class="d-flex align-items-center p-2 rounded {{ $isMyMessage ? 'bg-white bg-opacity-25 text-white' : 'bg-light text-dark' }} text-decoration-none">
                                                <i class="bi bi-file-earmark-fill fs-4 me-2"></i>
                                                <div class="small text-truncate" style="max-width: 150px;">
                                                    {{ basename($msg->file) }}
                                                </div>
                                                <i class="bi bi-download ms-2"></i>
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                <div class="text-end mt-1">
                                    <small class="{{ $isMyMessage ? 'text-white-50' : 'text-muted' }}" style="font-size: 0.7rem;">
                                        {{ $msg->created_at->format('H:i') }}
                                        @if($isMyMessage)
                                            <i class="bi bi-check2-all ms-1"></i>
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- 3. Форма відправки --}}
                <div class="card-footer bg-white py-3 border-top">
                    @if($chat->blocked)
                        <div class="alert alert-secondary mb-0 text-center rounded-pill">
                            <i class="bi bi-lock-fill me-2"></i> Чат заблоковано. Ви не можете писати повідомлення.
                        </div>
                    @else
                        {{-- Форма відправки повідомлень --}}
                        <form id="message-form" enctype="multipart/form-data">
                            @csrf
                            
                            {{-- Прев'ю файлу (Javascript покаже це при виборі) --}}
                            <div id="file-preview-area" class="mb-2 d-none">
                                <div class="badge bg-light text-dark border p-2 d-inline-flex align-items-center">
                                    <span id="file-name">file.jpg</span>
                                    <button type="button" class="btn-close ms-2" onclick="clearFile()"></button>
                                </div>
                            </div>

                            <div class="input-group align-items-end">
                                {{-- Кнопка Файл --}}
                                <label class="btn btn-light rounded-circle me-2 text-primary" data-bs-toggle="tooltip" title="Прикріпити файл">
                                    <i class="bi bi-paperclip fs-5"></i>
                                    <input type="file" name="file" id="file-input" class="d-none" onchange="showFilePreview(this)">
                                </label>

                                {{-- Поле тексту --}}
                                <textarea name="text" id="text-input" class="form-control rounded-pill bg-light border-0 px-3" rows="1" placeholder="Напишіть повідомлення..." style="resize: none;"></textarea>
                                
                                {{-- Кнопка відправки --}}
                                <button type="submit" class="btn btn-primary rounded-circle ms-2 shadow-sm" style="width: 45px; height: 45px;">
                                    <i class="bi bi-send-fill"></i>
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Стилі для бульбашок --}}
<style>
    .rounded-bottom-right-0 { border-bottom-right-radius: 0 !important; }
    .rounded-bottom-left-0 { border-bottom-left-radius: 0 !important; }
    
    /* Красивий скрол */
    #chat-container::-webkit-scrollbar { width: 6px; }
    #chat-container::-webkit-scrollbar-thumb { background-color: rgba(0,0,0,0.2); border-radius: 4px; }
</style>

{{-- Скрипт для AJAX відправки (щоб сторінка не перезавантажувалась) --}}
{{-- ПІДКЛЮЧАЄМО AXIOS --}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script type="module">
    
    const chatId = "{{ $chat->id }}";
    const currentUserId = {{ auth()->id() }}; 
    const chatContainer = document.getElementById('chat-container');

    
    if(chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    
    window.appendMessageToChat = function(message) {
        const isMyMessage = message.sender_id === currentUserId;
        
        
        const date = new Date(message.created_at);
        const time = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

        
        let fileHtml = '';
        if (message.file) {
            const isImage = message.file.match(/\.(jpg|jpeg|png|gif|webp)$/i);
            const filePath = `/storage/${message.file}`; 
            
            if (isImage) {
                fileHtml = `
                    <div class="mt-2">
                        <a href="${filePath}" target="_blank">
                            <img src="${filePath}" alt="image" class="img-fluid rounded border" style="max-height: 200px;">
                        </a>
                    </div>`;
            } else {
                fileHtml = `
                    <div class="mt-2">
                        <a href="${filePath}" target="_blank" class="d-flex align-items-center p-2 rounded ${isMyMessage ? 'bg-white bg-opacity-25 text-white' : 'bg-light text-dark'} text-decoration-none">
                            <i class="bi bi-file-earmark-fill fs-4 me-2"></i>
                            <div class="small text-truncate" style="max-width: 150px;">
                                ${message.file.split('/').pop()}
                            </div>
                            <i class="bi bi-download ms-2"></i>
                        </a>
                    </div>`;
            }
        }

        
        const html = `
            <div class="d-flex mb-3 ${isMyMessage ? 'justify-content-end' : 'justify-content-start'}">
                <div class="message-bubble p-3 shadow-sm ${isMyMessage ? 'bg-primary text-white rounded-bottom-right-0' : 'bg-white text-dark rounded-bottom-left-0'}" 
                     style="max-width: 75%; border-radius: 15px;">
                    
                    ${message.text ? `<p class="mb-1 text-break">${message.text}</p>` : ''}
                    ${fileHtml}

                    <div class="text-end mt-1">
                        <small class="${isMyMessage ? 'text-white-50' : 'text-muted'}" style="font-size: 0.7rem;">
                            ${time}
                            ${isMyMessage ? '<i class="bi bi-check2-all ms-1"></i>' : ''}
                        </small>
                    </div>
                </div>
            </div>
        `;

        
        chatContainer.insertAdjacentHTML('beforeend', html);
        chatContainer.scrollTop = chatContainer.scrollHeight;
    };

    
    
    setTimeout(() => {
        if (window.Echo) {
            console.log('✅ Echo підключено, слухаємо канал...');
            
            window.Echo.private(`chat.${chatId}`)
                .listen('MessageSent', (e) => {
                    console.log('📩 Нове повідомлення через WebSocket:', e.message);
                    window.appendMessageToChat(e.message);
                });
        } else {
            console.error('❌ Laravel Echo не знайдено. Перевірте npm run dev');
        }
    }, 500);


    
    window.showFilePreview = function(input) {
        if (input.files && input.files[0]) {
            document.getElementById('file-preview-area').classList.remove('d-none');
            document.getElementById('file-name').textContent = input.files[0].name;
        }
    };
    
    window.clearFile = function() {
        document.getElementById('file-input').value = '';
        document.getElementById('file-preview-area').classList.add('d-none');
    };

    const form = document.getElementById('message-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            let formData = new FormData(this);
            let text = document.getElementById('text-input').value;
            let file = document.getElementById('file-input').files[0];

            if (!text.trim() && !file) return;

            
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;

            axios.post("{{ route('messages.store', $chat->id) }}", formData)
                .then(response => {
                    
                    document.getElementById('text-input').value = '';
                    window.clearFile();
                    
                    
                    console.log('Моє повідомлення збережено:', response.data.message);
                    window.appendMessageToChat(response.data.message); 
                })
                .catch(error => {
                    console.error(error);
                    alert('Помилка відправки');
                })
                .finally(() => {
                    
                    btn.disabled = false;
                    
                    document.getElementById('text-input').focus();
                });
        });
    }
</script>
@endsection