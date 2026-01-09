<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import VueMultiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.css';

const props = defineProps({
    book: Object,
    categories: Array,
    authors: Array,
    tags: Array,
    currentTagIds: Array,
});

const selectedTagsObjects = props.tags.filter(tag =>
    props.currentTagIds.includes(tag.id)
);

const form = useForm({
    _method: 'PUT',
    title: props.book.title,
    description: props.book.description,
    note: props.book.note,
    category_id: props.book.category_id,
    author_id: props.book.author_id,
    tags: selectedTagsObjects,
    is_published: Boolean(props.book.is_published),
    preview_image: null,
    book_images: [],
});

const getImageUrl = (path) => path ? `/storage/${path}` : null;

const submit = () => {

    form.transform((data) => ({
        ...data,
        tags: data.tags.map(tag => tag.id),
    }))
        .post(`/books/${props.book.id}`, {
            forceFormData: true,
            onSuccess: () => console.log('Updated!'),
        });
};

const handlePreviewImage = (e) => { form.preview_image = e.target.files[0]; };
const handleGalleryImage = (e) => { if (e.target.files[0]) form.book_images.push(e.target.files[0]); };
</script>

<template>
    <div class="container-fluid py-4">
        <div class="row mb-4 align-items-center">
            <div class="col-sm-6">
                <h1 class="h3 mb-0 text-gray-800">Редагувати книжку</h1>
            </div>
            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb float-sm-end bg-transparent p-0 m-0">
                        <li class="breadcrumb-item">
                            <Link href="/">Головна</Link>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ book.title }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="mb-0 fw-bold text-primary">Редагування публікації</h5>
                    </div>
                    <div class="card-body p-4">

                        <form @submit.prevent="submit">

                            <div class="mb-3">
                                <label class="form-label fw-bold">Заголовок книги</label>
                                <input type="text" class="form-control" :class="{ 'is-invalid': form.errors.title }"
                                    v-model="form.title" placeholder="Введіть назву...">
                                <div v-if="form.errors.title" class="invalid-feedback">{{ form.errors.title }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Короткий опис</label>
                                <input type="text" class="form-control"
                                    :class="{ 'is-invalid': form.errors.description }" v-model="form.description"
                                    placeholder="Про що ця книга?">
                                <div v-if="form.errors.description" class="invalid-feedback">{{ form.errors.description
                                    }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Детальна примітка</label>
                                <textarea class="form-control" :class="{ 'is-invalid': form.errors.note }" rows="5"
                                    v-model="form.note" placeholder="Введіть текст..."></textarea>
                                <div v-if="form.errors.note" class="invalid-feedback">{{ form.errors.note }}</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Категорія</label>
                                    <select class="form-select" :class="{ 'is-invalid': form.errors.category_id }"
                                        v-model="form.category_id">
                                        <option disabled value="">Оберіть категорію</option>
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
                                        <option disabled value="">Оберіть автора</option>
                                        <option v-for="author in authors" :key="author.id" :value="author.id">
                                            {{ author.name }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors.author_id" class="invalid-feedback">{{ form.errors.author_id
                                        }}</div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Теги</label>

                                <VueMultiselect v-model="form.tags" :options="tags" :multiple="true"
                                    :close-on-select="false" :clear-on-select="false" placeholder="Оберіть теги"
                                    label="title" track-by="id">
                                    <template #selection="{ values, isOpen }">
                                        <span class="multiselect__single" v-if="values.length && !isOpen">
                                            Вибрано: {{ values.length }}
                                        </span>
                                    </template>
                                </VueMultiselect>

                                <div v-if="form.errors.tags" class="text-danger small mt-1">
                                    {{ form.errors.tags }}
                                </div>
                            </div>

                            <hr class="my-4">
                            <h6 class="fw-bold mb-3">Медіа файли</h6>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label class="form-label text-muted small">Поточна обкладинка:</label>
                                    <div class="mb-2">
                                        <img v-if="book.preview_image" :src="getImageUrl(book.preview_image)"
                                            class="img-thumbnail" style="height: 150px;">
                                        <div v-else
                                            class="bg-light border rounded d-flex align-items-center justify-content-center"
                                            style="height: 150px; width: 100px;">
                                            <span class="text-muted small">Немає</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">Змінити обкладинку</label>
                                    <input class="form-control" :class="{ 'is-invalid': form.errors.preview_image }"
                                        type="file" @change="handlePreviewImage">
                                    <div class="form-text">Завантажте файл, якщо хочете замінити старий.</div>
                                    <div v-if="form.errors.preview_image" class="invalid-feedback">{{
                                        form.errors.preview_image }}</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label d-block">Поточна галерея:</label>
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <div v-for="image in book.images" :key="image.id">
                                        <img :src="getImageUrl(image.file_path)" class="img-thumbnail"
                                            style="width: 80px; height: 80px; object-fit: cover;">
                                    </div>
                                    <span v-if="!book.images || book.images.length === 0"
                                        class="text-muted small">Галерея порожня</span>
                                </div>

                                <label class="form-label">Додати нові фото до галереї</label>
                                <div class="row g-2">
                                    <div class="col-md-4"><input class="form-control" type="file"
                                            @change="handleGalleryImage"></div>
                                    <div class="col-md-4"><input class="form-control" type="file"
                                            @change="handleGalleryImage"></div>
                                    <div class="col-md-4"><input class="form-control" type="file"
                                            @change="handleGalleryImage"></div>
                                </div>
                                <div v-if="form.errors.book_images" class="text-danger small mt-1">Помилка у файлі
                                    зображення</div>
                            </div>

                            <hr class="my-4">

                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" id="is_published"
                                    v-model="form.is_published">
                                <label class="form-check-label" for="is_published">Книга опублікована</label>
                                <div v-if="form.errors.is_published" class="text-danger small">{{
                                    form.errors.is_published }}</div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm"
                                    :disabled="form.processing">
                                    <span v-if="form.processing" class="spinner-border spinner-border-sm me-2"></span>
                                    <i v-else class="bi bi-save me-2"></i>
                                    Оновити книгу
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>