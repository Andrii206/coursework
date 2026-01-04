@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Додати книжку</h1>
                </div><div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Додати книжку</li>
                    </ol>
                </div></div></div></div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <span class="mb-6">Створить книжку</span>
                    <form action="{{ route('book.store') }}" method="post" class="w-50" enctype="multipart/form-data">
                        @csrf
                        
                        {{-- Заголовок --}}
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{ old('title') }}" name="title"
                                placeholder="Заголовок">
                            @error('title')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Опис --}}
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{ old('description') }}"
                                name="description" placeholder="Опис">
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Контент (Note) --}}
                        <div class="form-group">
                            <textarea cols="30" rows="10" class="form-control" name="note" placeholder="Контент">{{ old('note') }}</textarea>
                            @error('note')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Головне фото --}}
                        <div class="form-group">
                            <label for="exampleInputFile">Вставити головне фото</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="exampleInputFile" name="preview_image">
                                    <label class="custom-file-label">Оберіть фото</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Встановить</span>
                                </div>
                            </div>
                            @error('preview_image')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Галерея фото 1 --}}
                        <div class="form-group">
                            <label>Вставити фото галереї</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="book_images[]">
                                    <label class="custom-file-label">Оберіть фото</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Встановить</span>
                                </div>
                            </div>
                            @error('book_images')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Галерея фото 2 --}}
                        <div class="form-group">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="book_images[]">
                                    <label class="custom-file-label">Оберіть фото</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Встановить</span>
                                </div>
                            </div>
                        </div>

                        {{-- Галерея фото 3 --}}
                        <div class="form-group">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="book_images[]">
                                    <label class="custom-file-label">Оберіть фото</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Встановить</span>
                                </div>
                            </div>
                            {{-- Виводимо помилку, якщо є проблема з будь-яким файлом у масиві --}}
                            @error('book_images.*')
                                <div class="text-danger">Помилка у файлі зображення</div>
                            @enderror
                        </div>

                        {{-- Теги --}}
                        <div class="form-group">
                            <select name="tags[]" multiple="multiple" class="select2" style="width: 100%;"
                                id="exampleSelectBorder">
                                <option disabled>Тег</option>
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->id }}" {{ is_array(old('tags')) && in_array($tag->id, old('tags')) ? 'selected' : '' }}>
                                        {{ $tag->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tags')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Категорія --}}
                        <div class="form-group">
                            <select name="category_id" class="custom-select form-control" id="exampleSelectBorder">
                                <option disabled selected>Категорія</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ $category->id == old('category_id') ? ' selected' : '' }}>
                                        {{ $category->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Автор --}}
                        <div class="form-group">
                            <select name="author_id" class="custom-select form-control" id="exampleSelectBorder">
                                <option disabled selected>Автор</option>
                                @foreach ($authors as $author)
                                    <option value="{{ $author->id }}" {{ $author->id == old('author_id') ? ' selected' : '' }}>
                                        {{ $author->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('author_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Опубліковано --}}
                        <div class="form-group form-check-inline">
                            <input type="hidden" name="is_published" value="0">
                            <input type="checkbox" class="form-check-input" {{ old('is_published') ? 'checked' : '' }}
                                name="is_published" id="is_published" value="1">
                            <label class="form-check-label" for="is_published">Опубліковано</label>
                        </div>
                        @error('is_published')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                        <div class="form-group mt-3">
                            <input type="submit" class="btn btn-primary" value="Створити">
                        </div>
                    </form>

                </div>
            </div>
            </div></section>
@endsection