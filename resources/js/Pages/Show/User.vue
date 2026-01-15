<script setup>
// Додано імпорт router
import { useForm, usePage, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    user: Object,
    averageRating: [String, Number], // Може прийти як рядок з контролера
    pageCurrentUser: Boolean
});

const getImageUrl = (path) => {
    return path ? `/storage/${path}` : '/images/no-image.png';
};

// Функція форматування дати
const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('uk-UA');
};

const startChat = () => {
    router.post('/chats/check', {
        recipient_id: props.user.id // Виправлено з props.book на props.user
    });
};

// Ініціалізація форми
const form = useForm({
    rating: 5,
    text: '',
});

const submit = () => {
    // Використовуємо props.user.id
    form.post(`/users/${props.user.id}/reviews`, {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <div class="container py-5">
        
        <div class="card border-0 shadow-sm mb-5">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto"
                            style="width: 100px; height: 100px; font-size: 2rem;">
                            {{ user.name.substring(0, 1) }}
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h1 class="mb-1">{{ user.name }}</h1>
                        <p class="text-muted mb-2">{{ user.email }}</p>

                        <div class="d-flex align-items-center">
                            <span class="badge bg-warning text-dark me-2 fs-6">
                                ★ {{ averageRating }} / 5
                            </span>
                            <span class="text-muted">({{ user.reviews_received ? user.reviews_received.length : 0 }} відгуків)</span>
                        </div>
                    </div>
                    
                    <div class="col-md-2 text-end">
                         <Link v-if="pageCurrentUser" href="/logout" method="post" as="button" type="button" class="btn btn-danger">
                            Logout
                        </Link>
                        <button v-else @click="startChat" class="btn btn-outline-primary">
                            Написати
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <section class="py-5">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="bootstrap-tabs product-tabs">
                            
                            <div class="tabs-header border-bottom my-5">
                                <nav>
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                        <button class="nav-link text-uppercase fs-6 active" id="nav-seller-books-tab" 
                                            data-bs-toggle="tab" data-bs-target="#nav-seller-books" type="button" role="tab">
                                            Книги продавця
                                        </button>
                                        <button class="nav-link text-uppercase fs-6" id="nav-reviews-tab" 
                                            data-bs-toggle="tab" data-bs-target="#nav-reviews" type="button" role="tab">
                                            Відгуки
                                        </button>
                                        <button v-if="!pageCurrentUser" class="nav-link text-uppercase fs-6" id="nav-leave-a-review-tab" 
                                            data-bs-toggle="tab" data-bs-target="#nav-leave-a-review" type="button" role="tab">
                                            Залишити відгук
                                        </button>
                                    </div>
                                </nav>
                            </div>

                            <div class="tab-content" id="nav-tabContent">
                                
                                <div class="tab-pane fade show active" id="nav-seller-books" role="tabpanel">
                                    <h3 class="mb-4">Книги {{ user.name }}</h3>
                                    
                                    <div v-if="user.books && user.books.length > 0" class="row g-4">
                                        <div v-for="book in user.books" :key="book.id" class="col-md-6">
                                            <div class="card h-100 shadow-sm border-0">
                                                <div class="row g-0">
                                                    <div class="col-4">
                                                        <img :src="getImageUrl(book.preview_image)"
                                                            class="img-fluid rounded-start h-100"
                                                            style="object-fit: cover;" :alt="book.title">
                                                    </div>
                                                    <div class="col-8">
                                                        <div class="card-body">
                                                            <h5 class="card-title text-truncate">{{ book.title }}</h5>
                                                            <p class="card-text text-success fw-bold">
                                                                {{ book.price ? book.price + ' ₴' : 'Безкоштовно' }}
                                                            </p>
                                                            <Link :href="`/book/${book.id}`"
                                                                class="btn btn-sm btn-outline-primary stretched-link">
                                                                Детальніше
                                                            </Link>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else class="alert alert-light text-center">
                                        У цього користувача поки немає активних оголошень.
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="nav-reviews" role="tabpanel">
                                    <h4 class="mb-3">Відгуки</h4>
                                    <div class="reviews-list mb-5" style="max-height: 500px; overflow-y: auto;">
                                        <div v-if="user.reviews_received && user.reviews_received.length > 0"> 
                                            <div v-for="review in user.reviews_received" :key="review.id" class="card mb-3 border-0 bg-light">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <strong class="small">{{ review.sender ? review.sender.name : 'Анонім' }}</strong>
                                                        <span style="color: #ffc107"> <span v-for="n in 5" :key="n">
                                                                <i class="bi" :class="n <= review.rating ? 'bi-star-fill' : 'bi-star'"></i>
                                                                <span v-if="n <= review.rating">★</span>
                                                                <span v-else>☆</span>
                                                            </span>
                                                        </span>
                                                    </div>
                                                    <p class="mb-0">{{ review.text }}</p>
                                                    <small class="text-muted" style="font-size: 0.7rem;">
                                                        {{ formatDate(review.created_at) }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <p v-else class="text-muted small">Ще немає відгуків. Будьте першим!</p>
                                    </div>
                                </div>

                                <div v-if="!pageCurrentUser" class="tab-pane fade" id="nav-leave-a-review" role="tabpanel">
                                    <h5 class="card-title mb-3">Залишити відгук</h5>

                                    <div v-if="$page.props.auth.user">
                                        <div v-if="$page.props.auth.user.id !== user.id">
                                            <form @submit.prevent="submit">
                                                <div class="mb-3">
                                                    <label class="form-label small">Оцінка</label>
                                                    <select v-model="form.rating" class="form-select" :class="{ 'is-invalid': form.errors.rating }">
                                                        <option :value="5">⭐⭐⭐⭐⭐ (Відмінно)</option>
                                                        <option :value="4">⭐⭐⭐⭐ (Добре)</option>
                                                        <option :value="3">⭐⭐⭐ (Нормально)</option>
                                                        <option :value="2">⭐⭐ (Погано)</option>
                                                        <option :value="1">⭐ (Жахливо)</option>
                                                    </select>
                                                    <div v-if="form.errors.rating" class="invalid-feedback">{{ form.errors.rating }}</div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label small">Ваш коментар</label>
                                                    <textarea v-model="form.text" class="form-control" :class="{ 'is-invalid': form.errors.text }"
                                                        rows="3" placeholder="Напишіть ваші враження..." required></textarea>
                                                    <div v-if="form.errors.text" class="invalid-feedback">{{ form.errors.text }}</div>
                                                </div>

                                                <button type="submit" class="btn btn-primary w-100" :disabled="form.processing">
                                                    <span v-if="form.processing" class="spinner-border spinner-border-sm me-1"></span>
                                                    Надіслати
                                                </button>
                                            </form>
                                        </div>
                                        <div v-else class="alert alert-info small mb-0">
                                            Ви не можете залишити відгук самому собі.
                                        </div>
                                    </div>
                                    <div v-else class="alert alert-warning small mb-0">
                                        Будь ласка, <Link :href="route('login')">увійдіть</Link>, щоб залишити відгук.
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>