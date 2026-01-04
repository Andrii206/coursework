@extends('layouts.main')

@section('content')

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
                @foreach ($books as $book)
                    <div class="card col-lg-4 col-md-6 col-sm-12 mb-3 p-3 rounded-4 shadow border-0">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="{{ asset('storage/' . $book->preview_image) }}" class="img-fluid rounded" alt="Card title">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body py-0">
                                    <p class="text-muted mb-0">{{ $book->author->name ?? 'Автор невідомий' }}</p>
                                    <h5 class="card-title"><a href="{{ route('main.book.show', $book->id) }}">{{ $book->title }}</a></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-md-12">
                    {{ $books->withQueryString()->Links() }}
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container-fluid">
            <h2 class="my-5">People are also looking for</h2>
            @foreach ($tags as $tag)
                <a href="{{ request()->fullUrlWithQuery(['tags' => $tag->id, 'page' => null]) }}"
                    class="btn btn-warning me-2 mb-2">{{ $tag->title }}</a>
            @endforeach
        </div>
    </section>
@endsection
