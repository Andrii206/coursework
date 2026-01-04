@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Добавить користувача</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Добавить користувача</li>
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
                <div class="col-12">
                    <span class="mb-6">Створить користувача</span>
                    <form action="{{ route('user.store') }}" method="post" class="w-50">
                        @csrf
                        <div class="form-group">
                            <input type="email" class="form-control" value="{{ old('email') }}" name="email" placeholder="Email"> 
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" value="{{ old('password') }}" name="password" placeholder="Пароль"> 
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" value="{{ old('password_confirmation') }}" name="password_confirmation" placeholder="Повторіть пароль"> 
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{ old('name') }}" name="name" placeholder="Ім'я"> 
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{ old('surname') }}" name="surname" placeholder="Прізвище"> 
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{ old('telephone') }}" name="telephone" placeholder="Телефон"> 
                        </div>
                        <div class="form-group">
                            <select name="role" class="custom-select from-control" id="exampleSelectBorder">
                                <option disabled selected>Роль</option>
                                <option {{ old('role') == 1 ? 'selected' : ''}} value="1">Адмін</option>
                                <option {{ old('role') == 0 ? 'selected' : ''}} value="2">Користувач</option>
                            </select>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Створити">
                    </form>
                </div>
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
@endsection
