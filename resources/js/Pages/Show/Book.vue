<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const props = defineProps({
    book: Object,
});

const getImageUrl = (path) => {
    return path ? `/storage/${path}` : '/images/no-image.png';
};

const formatPrice = (price) => {
    return new Intl.NumberFormat('uk-UA', {
        style: 'decimal',
        minimumFractionDigits: 0
    }).format(price);
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('uk-UA');
};

const startChat = () => {
    router.post('/chats/check', {
        recipient_id: props.book.user_id
    });
};

const isLiked = computed(() => {
    const likedBooks = page.props.global?.likedBooks || [];
    return likedBooks.some(likedBook => likedBook.id === props.book.id);
});

const toggleLike = () => {
    router.post('/likes/toggle', {
        book_id: props.book.id
    }, {
        preserveScroll: true,
        onSuccess: () => {
            console.log('Лайк успішно оновлено');
        }
    });
};
</script>

<template>
    <div class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <Link href="/" class="text-decoration-none text-muted">Головна</Link>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ book.title }}</li>
            </ol>
        </nav>

        <div class="row g-5">
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden book-cover-card position-relative">

                    <img v-if="book.preview_image" :src="getImageUrl(book.preview_image)" alt="Обкладинка"
                        class="img-fluid w-100 book-image">

                    <div v-else class="bg-light d-flex align-items-center justify-content-center"
                        style="height: 500px;">
                        <span class="text-muted">Немає зображення</span>
                    </div>

                    <div class="position-absolute top-0 start-0 p-3">
                        <span v-if="book.category" class="badge bg-primary bg-opacity-75 backdrop-blur shadow-sm">
                            {{ book.category.title }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="book-details h-100 d-flex flex-column">
                    <h1 class="display-5 fw-bold text-dark mb-2">{{ book.title }}</h1>

                    <div class="mb-4">
                        <span class="text-muted fs-5">Автор:</span>
                        <span class="fs-5 fw-semibold text-primary ms-1">
                            {{ book.author?.name || 'Невідомий автор' }}
                        </span>
                    </div>

                    <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-3">
                        <div class="me-auto">
                            <span v-if="book.price" class="display-6 fw-bold text-success">
                                {{ formatPrice(book.price) }} ₴
                            </span>
                            <span v-else class="fs-3 fw-bold text-success">Безкоштовно</span>
                        </div>
                        <div>
                            <span v-if="book.is_published"
                                class="badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill">
                                <i class="bi bi-check-circle me-1"></i> В наявності
                            </span>
                            <span v-else
                                class="badge bg-secondary-subtle text-secondary border border-secondary px-3 py-2 rounded-pill">
                                Немає в наявності
                            </span>
                        </div>
                    </div>

                    <div class="row mb-4 text-muted small">
                        <div class="col-6 mb-2">
                            <i class="bi bi-calendar3 me-2"></i> Опубліковано: {{ formatDate(book.created_at) }}
                        </div>
                        <div class="col-6 mb-2">
                            <i class="bi bi-person-circle me-2"></i> Продавець:
                            <Link :href="`/users/${book.user?.id}`">
                                {{ book.user?.name || 'Admin' }}
                            </Link>
                        </div>
                        <div class="col-6 mb-2">
                            <i class="bi bi-hash me-2"></i> ID: #{{ book.id }}
                        </div>
                    </div>

                    <section class="mb-4" v-if="book.tags && book.tags.length">
                        <div class="">
                            <Link v-for="tag in book.tags" :key="tag.id" :href="`/?tags=${tag.id}`"
                                class="btn btn-warning me-2 mb-2 btn-sm">
                                {{ tag.title }}
                            </Link>
                        </div>
                    </section>

                    <div v-if="book.note" class="alert alert-warning d-flex align-items-start shadow-sm border-0 mb-4"
                        role="alert">
                        <i class="bi bi-info-circle-fill flex-shrink-0 me-3 fs-4"></i>
                        <div>
                            <div class="fw-bold mb-1">Примітка від автора:</div>
                            {{ book.note }}
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex mt-auto">
                        <button @click="startChat"
                            class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm hover-scale">
                            <i class="bi bi-chat-text me-2"></i> Написати продавцю
                        </button>

                        <button class="btn btn-lg px-4 rounded-pill transition-colors" :class="isLiked ? 'btn-dark ' : 'btn-outline-dark'" type="button" @click="toggleLike">
                            <i class="bi" :class="isLiked ? 'bi-heart-fill' : 'bi-heart'"></i>
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-lg-5">
                        <h3 class="fw-bold mb-4 border-bottom pb-3">Опис книги</h3>
                        <div class="book-description lh-lg" style="white-space: pre-wrap;">
                            {{ book.description }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<style scoped>
/* Додаткові стилі, якщо потрібно */
.book-image {
    object-fit: cover;
    /* max-height: 500px; Можна розкоментувати, якщо картинки занадто високі */
}
</style>