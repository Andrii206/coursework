@extends('layouts.main') {{-- Або ваш головний лейаут --}}

@section('content')
    <div class="container py-5">

        {{-- Хлібні крихти --}}
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('front-page') }}"
                        class="text-decoration-none text-muted">Головна</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $book->title }}</li>
            </ol>
        </nav>

        <div class="row g-5">
            {{-- Ліва колонка: Обкладинка --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden book-cover-card">
                    @if ($book->preview_image)
                        @if ($book->preview_image)
                            <img src="{{ asset('storage/' . $book->preview_image) }}" alt="Обкладинка" width="200"
                                class="img-fluid w-100 book-image">
                        @else
                            <img src="{{ asset('assets/no-image.png') }}" alt="Немає фото" width="200"
                                class="img-fluid w-100 book-image">
                        @endif
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 500px;">
                            <span class="text-muted">Немає зображення</span>
                        </div>
                    @endif

                    {{-- Бейджі на зображенні --}}
                    <div class="position-absolute top-0 start-0 p-3">
                        @if ($book->category)
                            <span class="badge bg-primary bg-opacity-75 backdrop-blur shadow-sm">
                                {{ $book->category->title }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Права колонка: Інформація --}}
            <div class="col-lg-7">
                <div class="book-details h-100 d-flex flex-column">

                    {{-- Заголовок та Автор --}}
                    <h1 class="display-5 fw-bold text-dark mb-2">{{ $book->title }}</h1>
                    <div class="mb-4">
                        <span class="text-muted fs-5">Автор:</span>
                        <a href="#" class="fs-5 fw-semibold text-primary text-decoration-none ms-1">
                            {{ $book->author->name ?? 'Невідомий автор' }}
                        </a>
                    </div>

                    {{-- Ціна та Статус --}}
                    <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-3">
                        <div class="me-auto">
                            @if ($book->price)
                                <span class="display-6 fw-bold text-success">{{ number_format($book->price, 0, ',', ' ') }}
                                    ₴</span>
                            @else
                                <span class="fs-3 fw-bold text-success">Безкоштовно</span>
                            @endif
                        </div>
                        <div>
                            @if ($book->is_published)
                                <span
                                    class="badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill">
                                    <i class="bi bi-check-circle me-1"></i> В наявності
                                </span>
                            @else
                                <span
                                    class="badge bg-secondary-subtle text-secondary border border-secondary px-3 py-2 rounded-pill">
                                    Немає в наявності
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Короткі характеристики (теги) --}}
                    <div class="row mb-4 text-muted small">
                        <div class="col-6 mb-2">
                            <i class="bi bi-calendar3 me-2"></i> Опубліковано: {{ $book->created_at->format('d.m.Y') }}
                        </div>
                        <div class="col-6 mb-2">
                            <i class="bi bi-person-circle me-2"></i> Продавець: <a
                                href="{{ route('main.users.show', $book->user->id) }}">{{ $book->user->name ?? 'Admin' }}
                        </div>
                        <div class="col-6 mb-2">
                            <i class="bi bi-hash me-2"></i> ID: #{{ $book->id }}
                            <section class="py-5">
                                <div class="container-fluid">
                                    @foreach ($book->tags as $tag)
                                        <a href="{{ route('front-page', array_merge(request()->query(), ['tags' => $tag->id, 'page' => null])) }}"
                                            class="btn btn-warning me-2 mb-2">{{ $tag->title }}</a>
                                    @endforeach
                                </div>
                            </section>
                        </div>
                    </div>

                    {{-- Блок "Note" (Важлива примітка) --}}
                    @if ($book->note)
                        <div class="alert alert-warning d-flex align-items-start shadow-sm border-0 mb-4" role="alert">
                            <i class="bi bi-info-circle-fill flex-shrink-0 me-3 fs-4"></i>
                            <div>
                                <div class="fw-bold mb-1">Примітка від автора:</div>
                                {{ $book->note }}
                            </div>
                        </div>
                    @endif

                    {{-- Кнопки дії --}}
                    <div class="d-grid gap-2 d-md-flex mt-auto">
                        {{-- Форма потрібна для POST запиту --}}
                        <form action="{{ route('chats.check') }}" method="POST">
                            @csrf

                            {{-- Обов'язково передаємо ID продавця (отримувача) --}}
                            <input type="hidden" name="recipient_id" value="{{ $book->user_id }}">

                            <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm hover-scale">
                                <i class="bi bi-chat-text me-2"></i> Написати продавцю
                            </button>
                        </form>
                        <button class="btn btn-outline-dark btn-lg px-4 rounded-pill" type="button">
                            <i class="bi bi-heart"></i>
                        </button>
                    </div>

                </div>
            </div>
        </div>

        {{-- Секція повного опису --}}
        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-lg-5">
                        <h3 class="fw-bold mb-4 border-bottom pb-3">Опис книги</h3>
                        <div class="book-description lh-lg">
                            {{-- Використовуємо nl2br для збереження абзаців --}}
                            {!! nl2br(e($book->description)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
