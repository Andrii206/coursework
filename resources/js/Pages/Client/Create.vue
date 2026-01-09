<script setup>
import { Link, useForm } from '@inertiajs/vue3';

defineProps({
    categories: Array,
    authors: Array,
    tags: Array,
});

const form = useForm({
    title: '',
    description: '',
    note: '',
    category_id: '',
    author_id: '',
    tags: [],
    preview_image: null,
    book_images: [],
    is_published: false,
});

const submit = () => {
    form.post('/books', {
        forceFormData: true,
        onSuccess: () => form.reset(),
    });
};

const handlePreviewImage = (e) => {
    form.preview_image = e.target.files[0];
};

const handleGalleryImage = (e) => {
    if (e.target.files[0]) {
        form.book_images.push(e.target.files[0]);
    }
};
</script>

<template>

    <div class="container-fluid py-4">
        <div class="row mb-4 align-items-center">
            <div class="col-sm-6">
                <h1 class="h3 mb-0 text-gray-800">Додати книжку</h1>
            </div>
            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb float-sm-end bg-transparent p-0 m-0">
                        <li class="breadcrumb-item">
                            <Link href="/">Головна</Link>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Додати книжку</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="mb-0 fw-bold text-primary">Нова публікація</h5>
                    </div>
                    <div class="card-body p-4">

                        <form @submit.prevent="submit">

                            <div class="mb-3">
                                <label for="title" class="form-label fw-bold">Заголовок книги</label>
                                <input type="text" class="form-control" :class="{ 'is-invalid': form.errors.title }"
                                    id="title" v-model="form.title" placeholder="Введіть назву...">
                                <div v-if="form.errors.title" class="invalid-feedback">{{ form.errors.title }}</div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">Короткий опис</label>
                                <input type="text" class="form-control"
                                    :class="{ 'is-invalid': form.errors.description }" id="description"
                                    v-model="form.description" placeholder="Про що ця книга?">
                                <div v-if="form.errors.description" class="invalid-feedback">{{ form.errors.description
                                    }}</div>
                            </div>

                            <div class="mb-3">
                                <label for="note" class="form-label fw-bold">Детальна примітка / Контент</label>
                                <textarea class="form-control" :class="{ 'is-invalid': form.errors.note }" id="note"
                                    rows="5" v-model="form.note" placeholder="Введіть текст..."></textarea>
                                <div v-if="form.errors.note" class="invalid-feedback">{{ form.errors.note }}</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Категорія</label>
                                    <select class="form-select" :class="{ 'is-invalid': form.errors.category_id }"
                                        v-model="form.category_id">
                                        <option value="" disabled>Оберіть категорію</option>
                                        <option v-for="category in categories" :key="category.id" :value="category.id">
                                            {{ category.title }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors.category_id" class="invalid-feedback">{{
                                        form.errors.category_id }}</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Автор</label>
                                    <select class="form-select" :class="{ 'is-invalid': form.errors.author_id }"
                                        v-model="form.author_id">
                                        <option value="" disabled>Оберіть автора</option>
                                        <option v-for="author in authors" :key="author.id" :value="author.id">
                                            {{ author.name }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors.author_id" class="invalid-feedback">{{ form.errors.author_id
                                        }}</div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Теги (Затисніть Ctrl для вибору декількох)</label>
                                <select class="form-select" multiple v-model="form.tags" style="height: 120px;">
                                    <option v-for="tag in tags" :key="tag.id" :value="tag.id">
                                        {{ tag.title }}
                                    </option>
                                </select>
                                <div v-if="form.errors.tags" class="text-danger small mt-1">{{ form.errors.tags }}</div>
                            </div>

                            <hr class="my-4">

                            <h6 class="fw-bold mb-3">Медіа файли</h6>

                            <div class="mb-3">
                                <label for="preview_image" class="form-label">Головна обкладинка <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" :class="{ 'is-invalid': form.errors.preview_image }"
                                    type="file" id="preview_image" @input="handlePreviewImage">
                                <div v-if="form.errors.preview_image" class="invalid-feedback">{{
                                    form.errors.preview_image }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Галерея (додаткові фото)</label>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <input class="form-control" type="file" @input="handleGalleryImage">
                                    </div>
                                    <div class="col-md-4">
                                        <input class="form-control" type="file" @input="handleGalleryImage">
                                    </div>
                                    <div class="col-md-4">
                                        <input class="form-control" type="file" @input="handleGalleryImage">
                                    </div>
                                </div>
                                <div v-if="form.errors.book_images" class="text-danger small mt-1">Помилка у файлі зображення</div>
                            </div>
                            <hr class="my-4">
                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" id="is_published"
                                    v-model="form.is_published">
                                <label class="form-check-label" for="is_published">Опублікувати книгу відразу</label>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm" :disabled="form.processing">
                                    <span v-if="form.processing" class="spinner-border spinner-border-sm me-2"></span>
                                    <i v-else class="bi bi-plus-circle me-2"></i>
                                    Створити книгу
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>