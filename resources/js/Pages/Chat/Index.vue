<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    chats: Array,
    userId: Number,
});

const getImageUrl = (path) => {
    return path ? `/storage/${path}` : '/images/no-image.png';
};

const formatTime = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleTimeString('uk-UA', {
        hour: '2-digit',
        minute: '2-digit'
    });
};

const getInterlocutor = (chat) => {
    return chat.sender_id === props.userId ? chat.recipient : chat.sender;
};

const getLastMessage = (chat) => {
    if (chat.messages && chat.messages.length > 0) {
        return chat.messages[0];
    }
    return null;
};
</script>

<template>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h4 class="mb-0 fw-bold">Ваші повідомлення</h4>
                    </div>

                    <div v-if="chats && chats.length > 0" class="list-group list-group-flush">
                        
                        <Link v-for="chat in chats" :key="chat.id" 
                            :href="`/chats/${chat.id}`"
                            class="list-group-item list-group-item-action py-3 border-0 hover-bg-light">
                            
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                        style="width: 50px; height: 50px; font-size: 1.2rem;">
                                        {{ getInterlocutor(chat).name.substring(0, 1) }}
                                    </div>
                                </div>

                                <div class="flex-grow-1 ms-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="mb-0 fw-bold text-dark">{{ getInterlocutor(chat).name }}</h6>
                                        
                                        <small v-if="getLastMessage(chat)" class="text-muted">
                                            {{ formatTime(getLastMessage(chat).created_at) }}
                                        </small>
                                    </div>

                                    <p v-if="getLastMessage(chat)" class="mb-0 text-muted small text-truncate"
                                        style="max-width: 80%;">
                                        
                                        <span v-if="getLastMessage(chat).sender_id === userId" class="text-primary">Ви:</span>
                                        
                                        <i v-if="getLastMessage(chat).file" class="bi bi-paperclip">[Файл]</i>
                                        <span v-else>{{ getLastMessage(chat).text }}</span>
                                    </p>
                                    <span v-else class="text-info">Новий чат</span>
                                </div>
                                <span v-if="chat.blocked" class="badge bg-danger ms-2">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                            </div>
                        </Link>
                    </div>

                    <div v-else class="text-center py-5">
                        <i class="bi bi-chat-square-text display-1 text-muted opacity-25"></i>
                        <p class="mt-3 text-muted">У вас поки немає повідомлень.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.hover-bg-light:hover {
    background-color: #f8f9fa;
}
</style>