@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Добавление инстанса</div>

                    <div class="card-body">
                        <form method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Имя</label>
                                <input type="text" class="form-control" name="name" id="name">
                            </div>
                            <div class="form-group">
                                <label for="ip">IP адрес</label>
                                <input type="text" class="form-control" name="ip" id="ip">
                            </div>
                            <div class="form-group">
                                <label for="hostname">hostname инстанса</label>
                                <input type="text" class="form-control" name="hostname" id="hostname">
                            </div>
                            <div class="form-group">
                                <label for="Login">Логин</label>
                                <input type="text" class="form-control" name="Login" id="Login">
                            </div>
                            <div class="form-group">
                                <label for="passwd">Пароль</label>
                                <input type="text" class="form-control" name="passwd" id="passwd">
                            </div>
                            <div class="form-group">
                                <label for="port">Порт для запросов</label>
                                <input type="number" class="form-control" name="port" id="port">
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_enable" id="is_enable">
                                <label class="form-check-label" for="is_enable">Активировать ?</label>
                            </div>
                            <button type="submit" class="btn btn-success" style="margin-top: 10px">Добавить</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
