@extends('layouts.main')

@section('content')
    <div class="container py-5">


        <div class="card border-0 shadow-sm mb-5">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">

                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto"
                            style="width: 100px; height: 100px; font-size: 2rem;">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h1 class="mb-1">{{ $user->name }}</h1>
                        <p class="text-muted mb-2">{{ $user->email }}</p>

                        <div class="d-flex align-items-center">
                            <span class="badge bg-warning text-dark me-2 fs-6">
                                ★ {{ number_format($averageRating, 1) }} / 5
                            </span>
                            <span class="text-muted">({{ $user->reviewsReceived->count() }} відгуків)</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <form method="POST" action="{{ route('logout') }}">
                             @csrf
                            <button class="btn btn-denger">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <section class="py-5">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-md-12">

                        <div class="bootstrap-tabs product-tabs">
                            <div class="tabs-header d-flex justify-content-between border-bottom my-5">
                                <div></div>
                                <nav>
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                        <a href="#" class="nav-link text-uppercase fs-6 active"
                                            id="nav-seller-books-tab" data-bs-toggle="tab"
                                            data-bs-target="#nav-seller-books">Книги продавця</a>
                                        <a href="#" class="nav-link text-uppercase fs-6" id="nav-reviews-tab"
                                            data-bs-toggle="tab" data-bs-target="#nav-reviews">Відгуки</a>
                                            @if (!$pageCurrentUser)
                                                <a href="#" class="nav-link text-uppercase fs-6" id="nav-leave-a-review-tab"
                                            data-bs-toggle="tab" data-bs-target="#nav-leave-a-review">Залишити відгук</a>
                                            @endif                        
                                    </div>
                                </nav>
                            </div>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-seller-books" role="tabpanel"
                                    aria-labelledby="nav-seller-books-tab">


                                    <h3 class="mb-4">Книги {{$user->name}}</h3>

                                    @if ($user->books->count() > 0)
                                        <div class="row g-4">
                                            @foreach ($user->books as $book)
                                                <div class="col-md-6">
                                                    <div class="card h-100 shadow-sm border-0">
                                                        <div class="row g-0">
                                                            <div class="col-4">

                                                                <img src="{{ asset('storage/' . $book->preview_image) }}"
                                                                    class="img-fluid rounded-start h-100"
                                                                    style="object-fit: cover;" alt="{{ $book->title }}">
                                                            </div>
                                                            <div class="col-8">
                                                                <div class="card-body">
                                                                    <h5 class="card-title text-truncate">
                                                                        {{ $book->title }}</h5>
                                                                    <p class="card-text text-success fw-bold">
                                                                        {{ $book->price ? $book->price . ' ₴' : 'Безкоштовно' }}
                                                                    </p>
                                                                    <a href="{{ route('main.book.show', $book->id) }}"
                                                                        class="btn btn-sm btn-outline-primary stretched-link">Детальніше</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-light text-center">
                                            У цього користувача поки немає активних оголошень.
                                        </div>
                                    @endif



                                </div>

                                <div class="tab-pane fade" id="nav-reviews" role="tabpanel"
                                    aria-labelledby="nav-reviews-tab">

                                    <h4 class="mb-3">Відгуки</h4>
                                    <div class="reviews-list mb-5" style="max-height: 500px; overflow-y: auto;">
                                        @forelse($user->reviewsReceived as $review)
                                            <div class="card mb-3 border-0 bg-light">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <strong
                                                            class="small">{{ $review->sender->name ?? 'Анонім' }}</strong>
                                                        <span style="color: yellow">

                                                            @for ($i = 0; $i < $review->rating; $i++)
                                                                ★
                                                            @endfor
                                                            @for ($i = $review->rating; $i < 5; $i++)
                                                                <span class="text-muted">★</span>
                                                            @endfor
                                                        </span>
                                                    </div>
                                                    <p class="mb-0 ">{{ $review->text }}</p>
                                                    <small class="text-muted"
                                                        style="font-size: 0.7rem;">{{ $review->created_at->format('d.m.Y') }}</small>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-muted small">Ще немає відгуків. Будьте першим!</p>
                                        @endforelse

                                    </div>


                                </div>
                                @if (!$pageCurrentUser)
                                    <div class="tab-pane fade" id="nav-leave-a-review" role="tabpanel"
                                        aria-labelledby="nav-leave-a-review-tab">



                                        <h5 class="card-title mb-3">Залишити відгук</h5>

                                        @auth
                                            @if (auth()->id() !== $user->id)
                                                <form action="{{ route('users.reviews.store', $user->id) }}" method="POST">
                                                    @csrf


                                                    <div class="form-group mb-3">
                                                        <label class="form-label small">Оцінка</label>
                                                        <select name="rating" class="form-select">
                                                            <option value="5">⭐⭐⭐⭐⭐ (Відмінно)</option>
                                                            <option value="4">⭐⭐⭐⭐ (Добре)</option>
                                                            <option value="3">⭐⭐⭐ (Нормально)</option>
                                                            <option value="2">⭐⭐ (Погано)</option>
                                                            <option value="1">⭐ (Жахливо)</option>
                                                        </select>
                                                    </div>


                                                    <div class="form-group mb-3">
                                                        <label class="form-label small">Ваш коментар</label>
                                                        <textarea name="text" class="form-control" rows="3" required placeholder="Напишіть ваші враження..."></textarea>
                                                    </div>

                                                    <button type="submit" class="btn btn-primary w-100">Надіслати</button>
                                                </form>
                                            @else
                                                <div class="alert alert-info small mb-0">
                                                    Ви не можете залишити відгук самому собі.
                                                </div>
                                            @endif
                                        @else
                                            <div class="alert alert-warning small mb-0">
                                                Будь ласка, <a href="{{ route('login') }}">увійдіть</a>, щоб залишити відгук.
                                            </div>
                                        @endauth
                                        <!-- / product-grid -->

                                    </div>
                                @endif

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>




        <div class="col-lg-4">





            <div class="card shadow-sm border-0 sticky-top" style="top: 20px; z-index: 1;">
                <div class="card-body">

                </div>
            </div>

        </div>
    </div>
    </div>
@endsection
