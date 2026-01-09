<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';

// Отримуємо дані, які передаються з бекенду (Shared Data)
const page = usePage();
const user = computed(() => page.props.auth.user);
const categories = computed(() => page.props.global?.categories || []); // Безпечний доступ
const authors = computed(() => page.props.global?.authors || []);
const myBooks = computed(() => page.props.global?.myBooks || []);

// Функція для зображень
const getImageUrl = (path) => path ? `/storage/${path}` : '/assets/no-image.png';
</script>

<template>
    <div>
        <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
            <defs>
                <symbol xmlns="http://www.w3.org/2000/svg" id="cart" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M8.5 19a1.5 1.5 0 1 0 1.5 1.5A1.5 1.5 0 0 0 8.5 19ZM19 16H7a1 1 0 0 1 0-2h8.491a3.013 3.013 0 0 0 2.885-2.176l1.585-5.55A1 1 0 0 0 19 5H6.74a3.007 3.007 0 0 0-2.82-2H3a1 1 0 0 0 0 2h.921a1.005 1.005 0 0 1 .962.725l.155.545v.005l1.641 5.742A3 3 0 0 0 7 18h12a1 1 0 0 0 0-2Zm-1.326-9l-1.22 4.274a1.005 1.005 0 0 1-.963.726H8.754l-.255-.892L7.326 7ZM16.5 19a1.5 1.5 0 1 0 1.5 1.5a1.5 1.5 0 0 0-1.5-1.5Z" />
                </symbol>
                <symbol xmlns="http://www.w3.org/2000/svg" id="search" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M21.71 20.29L18 16.61A9 9 0 1 0 16.61 18l3.68 3.68a1 1 0 0 0 1.42 0a1 1 0 0 0 0-1.39ZM11 18a7 7 0 1 1 7-7a7 7 0 0 1-7 7Z" />
                </symbol>
                <symbol xmlns="http://www.w3.org/2000/svg" id="user" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M15.71 12.71a6 6 0 1 0-7.42 0a10 10 0 0 0-6.22 8.18a1 1 0 0 0 2 .22a8 8 0 0 1 15.9 0a1 1 0 0 0 1 .89h.11a1 1 0 0 0 .88-1.1a10 10 0 0 0-6.25-8.19ZM12 12a4 4 0 1 1 4-4a4 4 0 0 1-4 4Z" />
                </symbol>
            </defs>
        </svg>

        <div class="preloader-wrapper">
            <div class="preloader"></div>
        </div>

        <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasCart"
            aria-labelledby="My Cart">
            <div class="offcanvas-header justify-content-center">
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="order-md-last">
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-primary">Your book</span>
                        <span class="badge bg-primary rounded-pill">{{ myBooks.length }}</span>
                    </h4>
                    <ul class="list-group mb-3">
                        <li v-for="book in myBooks" :key="book.id"
                            class="list-group-item border-0 shadow-sm rounded-3 mb-3 p-3 book-item-hover">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-4">
                                    <div class="position-relative" style="width: 80px; height: 110px;">
                                        <img :src="getImageUrl(book.preview_image)" :alt="book.title"
                                            class="w-100 h-100 rounded shadow-sm" style="object-fit: cover;">
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-1 fw-bold">
                                        <Link :href="`/books/${book.id}`"
                                            class="text-dark text-decoration-none stretched-link-custom">
                                            {{ book.title }}
                                        </Link>
                                    </h5>
                                    <div class="text-muted small mb-2">
                                        <i class="bi bi-person-circle me-1"></i> {{ book.author?.name }}
                                    </div>
                                    <div>
                                        <span v-if="book.is_published"
                                            class="badge bg-success bg-opacity-10 text-success rounded-pill px-2">Опубліковано</span>
                                        <span v-else
                                            class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2">Чернетка</span>
                                    </div>
                                </div>
                                <div class="flex-shrink-0 ms-3">
                                    <div class="d-flex flex-column gap-2">
                                        <Link :href="`/books/${book.id}/edit`"
                                            class="btn btn-light btn-sm text-success shadow-sm" title="Редагувати">
                                            <i class="bi bi-pencil"></i>
                                        </Link>

                                        <Link :href="`/books/${book.id}`" method="delete" as="button"
                                            class="btn btn-light btn-sm shadow-sm" title="Видалити"
                                            @click="confirm('Ви впевнені?')">
                                            <i class="bi bi-trash text-danger"></i>
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <Link href="/books/create" class="w-100 btn btn-primary btn-lg">Place an ad</Link>
                </div>
            </div>
        </div>

        <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasSearch"
            aria-labelledby="Search">
            <div class="offcanvas-header justify-content-center">
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="order-md-last">
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-primary">Search</span>
                    </h4>
                    <form action="/" method="get" class="d-flex mt-3 gap-0">
                        <input class="form-control rounded-start rounded-0 bg-light" name="search"
                            placeholder="What are you looking for?">
                        <button class="btn btn-dark rounded-end rounded-0" type="submit">Search</button>
                    </form>
                </div>
            </div>
        </div>

        <header>
            <div class="container-fluid">
                <div class="row py-3 border-bottom">
                    <div class="col-sm-4 col-lg-3 text-center text-sm-start">
                        <div class="main-logo">
                            <Link href="/">
                                <img src="/images/Logo-removebg-preview.png" alt="logo" class="img-fluid">
                            </Link>
                        </div>
                    </div>

                    <div class="col-sm-6 offset-sm-2 offset-md-0 col-lg-5 d-none d-lg-block">
                        <div class="search-bar row bg-light p-2 my-2 rounded-4">
                            <div class="col-md-4 d-none d-md-block">
                                <select class="form-select border-0 bg-transparent"
                                    @change="(e) => $inertia.get(`/?category_id=${e.target.value}`)">
                                    <option value="">All Categories</option>
                                    <option v-for="cat in categories.slice(0, 10)" :key="cat.id" :value="cat.id">
                                        {{ cat.title }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-11 col-md-7">
                                <form id="search-form" class="text-center" action="/" method="get">
                                    <input type="text" class="form-control border-0 bg-transparent"
                                        placeholder="Search products" name="search" />
                                </form>
                            </div>
                            <div class="col-1">
                                <svg width="24" height="24">
                                    <use xlink:href="#search"></use>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div
                        class="col-sm-8 col-lg-4 d-flex justify-content-end gap-5 align-items-center mt-4 mt-sm-0 justify-content-center justify-content-sm-end">
                        <ul class="d-flex justify-content-end list-unstyled m-0">
                            <li>
                                <Link :href="user ? `/users/${user.id}` : '/login'"
                                    class="rounded-circle bg-light p-2 mx-1">
                                    <svg width="30" height="30">
                                        <use xlink:href="#user"></use>
                                    </svg>
                                </Link>
                            </li>
                            <li class="">
                                <a href="#" class="rounded-circle bg-light p-2 mx-1" data-bs-toggle="offcanvas"
                                    data-bs-target="#offcanvasCart">
                                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                        </g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path
                                                d="M15.7 4C18.87 4 21 6.98 21 9.76C21 15.39 12.16 20 12 20C11.84 20 3 15.39 3 9.76C3 6.98 5.13 4 8.3 4C10.12 4 11.31 4.91 12 5.71C12.69 4.91 13.88 4 15.7 4Z"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                        </g>
                                    </svg>
                                </a>
                            </li>
                            <li class="">
                                <Link href="/chats" class="rounded-circle bg-light p-2 mx-1">
                                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                        </g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path
                                                d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 13.5997 2.37562 15.1116 3.04346 16.4525C3.22094 16.8088 3.28001 17.2161 3.17712 17.6006L2.58151 19.8267C2.32295 20.793 3.20701 21.677 4.17335 21.4185L6.39939 20.8229C6.78393 20.72 7.19121 20.7791 7.54753 20.9565C8.88837 21.6244 10.4003 22 12 22Z"
                                                stroke="currentColor" stroke-width="1.5"></path>
                                        </g>
                                    </svg>
                                </Link>
                            </li>
                        </ul>

                        <div class="cart text-end d-none d-lg-block dropdown">
                            <button class="border-0 bg-transparent d-flex flex-column gap-2 lh-1" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart">
                                <span class="fs-6 text-muted dropdown-toggle">Your Book</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid" v-if="categories.length > 0">
                <div class="row py-3">
                    <div class="d-flex justify-content-center justify-content-sm-between align-items-center">
                        <nav class="main-menu d-flex navbar navbar-expand-lg">
                            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasNavbar">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar">
                                <div class="offcanvas-header justify-content-center">
                                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
                                </div>
                                <div class="offcanvas-body">
                                    <select class="filter-categories border-0 mb-0 me-5"
                                        @change="(e) => $inertia.get(`/?category_id=${e.target.value}`)">
                                        <option>Shop by Departments</option>
                                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.title }}
                                        </option>
                                    </select>
                                    <ul
                                        class="navbar-nav justify-content-end menu-list list-unstyled d-flex gap-md-3 mb-0">
                                        <li v-for="author in authors.slice(0, 4)" :key="author.id"
                                            class="nav-item active">
                                            <Link :href="`/?author_id=${author.id}`" class="nav-link">{{ author.name }}
                                            </Link>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </header>

        <main>
            <slot />
        </main>

        <footer class="py-5">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="footer-menu">
                            <img src="/images/Logo-removebg-preview.png" alt="logo">
                            <div class="social-links mt-5">
                                <ul class="d-flex list-unstyled gap-2">
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <div class="footer-menu">
                            <h5 class="widget-title">Customer Service</h5>
                            <ul class="menu-list list-unstyled">
                                <li class="menu-item"><a href="#" class="nav-link">FAQ</a></li>
                                <li class="menu-item"><a href="#" class="nav-link">Contact</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <div id="footer-bottom">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 copyright">
                        <p>© 2023 Foodmart. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>