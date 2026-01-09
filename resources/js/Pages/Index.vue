<script setup>
import { Link } from '@inertiajs/vue3';
const props = defineProps({
    books: Object,
    tags: Array,
});
const getImageUrl = (path) => {
    return path ? `/storage/${path}` : '/images/no-image.png';
};
</script>
<template>
    <div>
        <section class="py-5 overflow-hidden">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="section-header d-flex flex-wrap justify-content-between mb-5">
                            <h2 class="section-title">Newly Arrived Brands</h2>
                        </div>
                    </div>
                </div>
                <div class="row g-2">
                    <div v-for="book in books.data" :key="book.id"
                        class="card col-lg-4 col-md-6 col-sm-12 mb-3 p-3 rounded-4 shadow border-0">
                        <div class="row">
                            <div class="col-md-4">
                                <img :src="getImageUrl(book.preview_image)" class="img-fluid rounded" :alt="book.title">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body py-0">
                                    <p class="text-muted mb-0">
                                        {{ book.author?.name || 'Автор невідомий' }}
                                    </p>
                                    <h5 class="card-title">
                                        <Link :href="`/book/${book.id}`">
                                            {{ book.title }}
                                        </Link>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-4">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <li v-for="(link, index) in books.links" :key="index" class="page-item"
                                    :class="{ 'active': link.active, 'disabled': !link.url }">
                                    <Link v-if="link.url" class="page-link" :href="link.url" v-html="link.label">
                                    </Link>
                                    <span v-else class="page-link" v-html="link.label"></span>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </section>
        <section class="py-5">
            <div class="container-fluid">
                <h2 class="my-5">People are also looking for</h2>

                <div class="d-flex flex-wrap">
                    <Link v-for="tag in tags" :key="tag.id" :href="`/?tags=${tag.id}`"
                        class="btn btn-warning me-2 mb-2">
                        {{ tag.title }}
                    </Link>
                </div>
            </div>
        </section>
    </div>
</template>