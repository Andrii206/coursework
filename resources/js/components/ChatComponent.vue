<template>
    <div class="d-flex flex-column h-100">
        
        <div ref="messagesContainer" class="card-body p-4 overflow-auto bg-light" id="chat-container" style="flex: 1; height: 65vh;">
            <div v-for="msg in messages" :key="msg.id || msg.temp_id" 
                 class="d-flex mb-3" 
                 :class="isMyMessage(msg.sender_id) ? 'justify-content-end' : 'justify-content-start'">
                
                <div class="message-bubble p-3 shadow-sm" 
                     :class="isMyMessage(msg.sender_id) ? 'bg-primary text-white rounded-bottom-right-0' : 'bg-white text-dark rounded-bottom-left-0'"
                     style="max-width: 75%; border-radius: 15px;">
                    
                    <p v-if="msg.text" class="mb-1 text-break">{{ msg.text }}</p>

                    <div v-if="msg.file" class="mt-2">
                        <div v-if="isImage(msg.file)">
                            <a :href="getFileUrl(msg.file)" target="_blank">
                                <img :src="getFileUrl(msg.file)" alt="image" class="img-fluid rounded border" style="max-height: 200px;">
                            </a>
                        </div>
                        <div v-else>
                            <a :href="getFileUrl(msg.file)" target="_blank" 
                               class="d-flex align-items-center p-2 rounded text-decoration-none"
                               :class="isMyMessage(msg.sender_id) ? 'bg-white bg-opacity-25 text-white' : 'bg-light text-dark'">
                                <i class="bi bi-file-earmark-fill fs-4 me-2"></i>
                                <div class="small text-truncate" style="max-width: 150px;">
                                    {{ getFileName(msg.file) }}
                                </div>
                                <i class="bi bi-download ms-2"></i>
                            </a>
                        </div>
                    </div>

                    <div class="text-end mt-1">
                        <small :class="isMyMessage(msg.sender_id) ? 'text-white-50' : 'text-muted'" style="font-size: 0.7rem;">
                            {{ formatTime(msg.created_at) }}
                            <i v-if="isMyMessage(msg.sender_id)" class="bi bi-check2-all ms-1"></i>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer bg-white py-3 border-top">
            <div v-if="isBlocked" class="alert alert-secondary mb-0 text-center rounded-pill">
                <i class="bi bi-lock-fill me-2"></i> Чат заблоковано. Ви не можете писати повідомлення.
            </div>

            <form v-else @submit.prevent="sendMessage" enctype="multipart/form-data">
                
                <div v-if="uploadFile" class="mb-2">
                    <div class="badge bg-light text-dark border p-2 d-inline-flex align-items-center">
                        <span>{{ uploadFile.name }}</span>
                        <button type="button" class="btn-close ms-2" @click="clearFile"></button>
                    </div>
                </div>

                <div class="input-group align-items-end">
                    <label class="btn btn-light rounded-circle me-2 text-primary" :class="{ 'disabled': isSending }" style="cursor: pointer;">
                        <i class="bi bi-paperclip fs-5"></i>
                        <input type="file" class="d-none" @change="handleFileUpload" :disabled="isSending">
                    </label>

                    <textarea v-model="newMessageText" 
                              class="form-control rounded-pill bg-light border-0 px-3" 
                              rows="1" 
                              placeholder="Напишіть повідомлення..." 
                              style="resize: none;"
                              @keydown.enter.exact.prevent="sendMessage"
                              :disabled="isSending"></textarea>
                    
                    <button type="submit" class="btn btn-primary rounded-circle ms-2 shadow-sm" style="width: 45px; height: 45px;" :disabled="isSending || (!newMessageText && !uploadFile)">
                        <span v-if="isSending" class="spinner-border spinner-border-sm"></span>
                        <i v-else class="bi bi-send-fill"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, nextTick, onUnmounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    chatId: { type: Number, required: true },
    currentUserId: { type: Number, required: true },
    initialMessages: { type: Array, default: () => [] },
    isBlocked: { type: Boolean, default: false }
});

const messages = ref([...props.initialMessages]);
const newMessageText = ref('');
const uploadFile = ref(null);
const isSending = ref(false);
const messagesContainer = ref(null);


const isMyMessage = (senderId) => senderId === props.currentUserId;


const isImage = (filename) => {
    if (!filename) return false;
    return /\.(jpg|jpeg|png|gif|webp)$/i.test(filename);
};


const getFileUrl = (path) => {
    return path.startsWith('http') ? path : `/storage/${path}`;
};

const getFileName = (path) => path.split('/').pop();


const formatTime = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};


const scrollToBottom = async () => {
    await nextTick();
    if (messagesContainer.value) {
        messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
    }
};


const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) uploadFile.value = file;
};

const clearFile = () => {
    uploadFile.value = null;
    
    const inputs = document.querySelectorAll('input[type="file"]');
    inputs.forEach(input => input.value = '');
};


const sendMessage = async () => {
    if ((!newMessageText.value.trim() && !uploadFile.value) || isSending.value) return;

    isSending.value = true;
    
    const formData = new FormData();
    formData.append('text', newMessageText.value);
    if (uploadFile.value) {
        formData.append('file', uploadFile.value);
    }

    try {
        
        
        const response = await axios.post(`/chats/${props.chatId}/messages`, formData);
        
        
        messages.value.push(response.data.message);
        
        
        newMessageText.value = '';
        clearFile();
        scrollToBottom();
    } catch (error) {
        console.error("Помилка:", error);
        alert('Помилка відправки повідомлення');
    } finally {
        isSending.value = false;
        
        const textarea = document.querySelector('textarea');
        if(textarea) textarea.focus();
    }
};



onMounted(() => {
    scrollToBottom();

    
    if (window.Echo) {
        console.log(`📡 Підключення до каналу chat.${props.chatId}`);
        window.Echo.private(`chat.${props.chatId}`)
            .listen('MessageSent', (e) => {
                console.log('📩 Нове повідомлення:', e.message);
                messages.value.push(e.message);
                scrollToBottom();
            });
    }
});

onUnmounted(() => {
    if (window.Echo) {
        window.Echo.leave(`chat.${props.chatId}`);
    }
});
</script>

<style scoped>
/* Стилі для скролбару */
.overflow-auto::-webkit-scrollbar { width: 6px; }
.overflow-auto::-webkit-scrollbar-thumb { background-color: rgba(0,0,0,0.2); border-radius: 4px; }
.rounded-bottom-right-0 { border-bottom-right-radius: 0 !important; }
.rounded-bottom-left-0 { border-bottom-left-radius: 0 !important; }
</style>