@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Редактирование инстанса</div>
                    <div class="card-body">

                        <form method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="ip">IP адрес</label>
                                <input type="text" class="form-control" name="ip" id="ip"
                                       value="{{$config->ipaddress}}">
                            </div>
                            <div class="form-group">
                                <label for="Login">Логин</label>
                                <input type="text" class="form-control" name="Login" id="Login"
                                       value="{{$config->username}}">
                            </div>
                            <div class="form-group">
                                <label for="passwd">Пароль</label>
                                <input type="text" class="form-control" name="passwd" id="passwd"
                                       value="{{$config->password}}">
                            </div>
                            <div class="form-group">
                                <label for="port">Порт для запросов</label>
                                <input type="number" class="form-control" name="port" id="port"
                                       value="{{$config->port}}">
                            </div>
                            <div class="form-check">
                                @if($config->is_enabled == 1)
                                    <input type="checkbox" class="form-check-input" name="is_enable" id="is_enable"
                                           checked>
                                @else
                                    <input type="checkbox" class="form-check-input" name="is_enable" id="is_enable">

                                @endif
                                <label class="form-check-label" for="is_enable">Активировать ?</label>
                            </div>
                            <button type="submit" class="btn btn-success" style="margin-top: 10px">Изменить</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
