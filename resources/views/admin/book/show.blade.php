@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Книги</h1>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row my-3">
                <div class="col-12">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('book.create') }}" class="btn btn-primary btn-sm mr-2">Створити</a>
                        <a href="{{ route('book.edit', $book->id) }}" class="btn btn-success btn-sm mr-2"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('book.delete', $book->id) }}" method="post">
                            @method('delete')
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>                   
            <div class="row">
                <div class="col-6">
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <tbody>
                                <tr>
                                    <td>ID</td>
                                    <td>{{ $book->id }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>{{ $book->title }}</td>
                                </tr>
                                <tr>
                                    <td>Опис</td>
                                    <td>{{ wordwrap($book->description, 255, "<br>", true) }}</td>
                                </tr>
                                <tr>
                                    <td>Контент</td>
                                    <td>{{ wordwrap($book->none, 255, "<br>", true) }}</td>
                                </tr>
                                <tr>
                                    <td>Фото</td>
                                    <td>{{ $book->preview_image }}</td>
                                </tr>                               
                                <tr>
                                    <td>Категорія</td>
                                    <td>{{ $book -> category_id}}</td>
                                </tr>
                                <tr>
                                    <td>Група</td>
                                    <td>{{ $book -> group_id}}</td>
                                </tr>
                                <tr>
                                    <td>Група</td>
                                    <td>{{ $book -> user_id}}</td>
                                </tr>
                                <tr>
                                    <td>Теги</td>
                                    <td>
                                        @foreach($book -> tags as $tag)
                                            {{$tag->title}}
                                        @endforeach                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td>Опубліковано</td>
                                    <td>{{ $book->is_published ? 'Так' : 'Ні' }}</td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </section>
@endsection
