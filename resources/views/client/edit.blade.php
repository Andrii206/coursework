@extends('layouts.main')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Заголовок --}}
    <div class="row mb-4 align-items-center">
        <div class="col-sm-6">
            <h1 class="h3 mb-0 text-gray-800">Редагувати книжку</h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="{{ route('front-page') }}">Головна</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $book->title }}</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Основна форма --}}
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold text-primary">Редагування публікації</h5>
                </div>
                <div class="card-body p-4">
                    
                    <form action="{{ route('book.update', $book->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        
                        {{-- 1. Заголовок --}}
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold">Заголовок книги</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $book->title) }}" placeholder="Введіть назву...">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- 2. Опис --}}
                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Короткий опис</label>
                            <input type="text" class="form-control @error('description') is-invalid @enderror" 
                                   id="description" name="description" value="{{ old('description', $book->description) }}" placeholder="Про що ця книга?">
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- 3. Контент (Note) --}}
                        <div class="mb-3">
                            <label for="note" class="form-label fw-bold">Детальна примітка</label>
                            <textarea class="form-control @error('note') is-invalid @enderror" 
                                      id="note" name="note" rows="5" placeholder="Введіть текст...">{{ old('note', $book->note) }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            {{-- 4. Категорія --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Категорія</label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                    <option disabled>Оберіть категорію</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" 
                                            {{ $category->id == old('category_id', $book->category_id) ? 'selected' : '' }}>
                                            {{ $category->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- 5. Автор --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Автор</label>
                                <select name="author_id" class="form-select @error('author_id') is-invalid @enderror">
                                    <option disabled>Оберіть автора</option>
                                    @foreach ($authors as $author)
                                        <option value="{{ $author->id }}" 
                                            {{ $author->id == old('author_id', $book->author_id) ? 'selected' : '' }}>
                                            {{ $author->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('author_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- 6. Теги --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Теги</label>
                            <select name="tags[]" class="select2 form-select" multiple="multiple" style="width: 100%;">
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->id }}" 
                                        {{ (is_array(old('tags')) && in_array($tag->id, old('tags'))) || (is_null(old('tags')) && $book->tags->contains($tag->id)) ? 'selected' : '' }}>
                                        {{ $tag->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tags')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        {{-- 7. Зображення (Preview Image) --}}
                        <h6 class="fw-bold mb-3">Медіа файли</h6>
                        
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label text-muted small">Поточна обкладинка:</label>
                                <div class="mb-2">
                                    @if($book->preview_image)
                                        <img src="{{ asset('storage/' . $book->preview_image) }}" alt="img" class="img-thumbnail" style="height: 150px;">
                                    @else
                                        <div class="bg-light border rounded d-flex align-items-center justify-content-center" style="height: 150px; width: 100px;">
                                            <span class="text-muted small">Немає</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-8">
                                <label for="preview_image" class="form-label">Змінити обкладинку</label>
                                <input class="form-control @error('preview_image') is-invalid @enderror" type="file" id="preview_image" name="preview_image">
                                <div class="form-text">Завантажте файл, якщо хочете замінити старий.</div>
                                @error('preview_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- 8. Галерея (Book Images) --}}
                        <div class="mb-3">
                            <label class="form-label d-block">Поточна галерея:</label>
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @forelse($book->images ?? [] as $image)
                                    <img src="{{ asset('storage/' . $image->file_path) }}" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                                @empty
                                    <span class="text-muted small">Галерея порожня</span>
                                @endforelse
                            </div>

                            <label class="form-label">Додати нові фото до галереї</label>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <input class="form-control" type="file" name="book_images[]">
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control" type="file" name="book_images[]">
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control" type="file" name="book_images[]">
                                </div>
                            </div>
                            @error('book_images.*')
                                <div class="text-danger small mt-1">Помилка у файлі зображення</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        {{-- 9. Опубліковано --}}
                        <div class="form-check form-switch mb-4">
                            <input type="hidden" name="is_published" value="0">
                            <input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1" 
                                {{ old('is_published', $book->is_published) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_published">Книга опублікована</label>
                            @error('is_published')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Кнопка --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm">
                                <i class="bi bi-save me-2"></i> Оновити книгу
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection