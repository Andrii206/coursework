<script setup>
import { Link } from '@inertiajs/vue3';
import ChatComponent from '@/components/ChatComponent.vue';

const props = defineProps({
    chat: Object,
    messages: Array,
    interlocutor: Object,
    userId: Number
});
</script>

<template>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card shadow-sm rounded-4 overflow-hidden" style="height: 85vh;">
                    
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center sticky-top">
                        <div class="d-flex align-items-center">
                            <Link href="/chats" class="btn btn-light btn-sm rounded-circle me-3">
                                <i class="bi bi-arrow-left"></i>
                            </Link>
                            <div>
                                <h5 class="mb-0 fw-bold">{{ interlocutor.name }}</h5>
                                <small class="text-muted">{{ chat.blocked ? 'Чат заблоковано' : 'Онлайн' }}</small>
                            </div>
                        </div>
                        
                        <Link 
                            :href="`/chats/${chat.id}/block`" 
                            method="post" 
                            as="button" 
                            type="button" 
                            :preserve-scroll="true" 
                            class="btn btn-sm rounded-pill px-3" 
                            :class="chat.blocked ? 'btn-danger' : 'btn-outline-secondary'"
                        >
                            <i class="bi me-1" :class="chat.blocked ? 'bi-lock-fill' : 'bi-unlock'"></i>
                            {{ chat.blocked ? 'Розблокувати' : 'Заблокувати' }}
                        </Link>
                    </div>

                    <div class="chat-wrapper" style="flex: 1; overflow: hidden; display: flex; flex-direction: column;">
                        
                        <chat-component 
                            :chat-id="chat.id" 
                            :current-user-id="userId" 
                            :initial-messages="messages" 
                            :is-blocked="Boolean(chat.blocked)"
                        ></chat-component>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>