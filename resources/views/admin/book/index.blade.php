@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Книги</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Книги</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-2 mb-3">
                    <a href="{{ route('book.create') }}" class="btn btn-block btn-primary w-10">Створити</a>
                </div>
            </div>
          
            <div class="row">
                <div class="col-12">
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Заголовок</th>
                                    <th>Опис</th>
                                    <th>Примітка</th>
                                    <th>Фото</th>
                                    <th>Автор</th>
                                    <th>Виклав</th>
                                    <th>Категорія</th>
                                    <th>Опублікованість</th>
                                    <th class="text-center" colspan="3">Дія</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($books as $book)
                                <tr>
                                    <td>{{ $book -> id }}</td>
                                    <td>{{ $book -> title }}</td>
                                    <td style="overflow-wrap: break-word">{{ $book -> description }}</td>
                                    <td style="word-wrap: break-word;">{{ $book -> note }}</td>
                                    <td>{{ $book -> preview_image }}</td>
                                    <td>{{ $book -> author_id}}</td>
                                    <td>{{ $book -> category_id}}</td>
                                    <td>{{ $book -> user_id}}</td>
                                    <td>{{ $book -> is_published ? 'Так' : 'Ні'}}</td>
                                    <td>
                                        <a href="{{ route('book.show', $book->id) }}"><i class="bi bi-eye fa-lg mx-3"></i></a>
                                    </td>
                                    <td>    
                                        <a href="{{ route('book.edit', $book->id) }}"><i class="bi bi-pencil fa-lg mx-3 text-success"></i></a>
                                    </td>
                                    <td>
                                        <form  action="{{ route('book.delete', $book->id) }}" method="post">
                                            @method('delete')
                                            @csrf
                                            <button type="submit" class="border-0 bg-transparent">
                                                <i class="bi bi-trash fa-lg mx-3 text-danger" role="button"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach    
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>        
        </div><!-- /.container-fluid -->
    </section>
@endsection
