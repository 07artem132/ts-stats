@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">API токены</div>

                    <div class="card-body">
                        <a href="{{route('AddApiToken')}}">
                            <button type="button" class="btn btn-success float-right" style="margin-bottom: 10px">Добавить
                                токен
                            </button></a>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Название</th>
                                <th scope="col">id пользователя</th>
                                <th scope="col">токен</th>
                                <th scope="col">Тип приложения</th>
                                <th scope="col">Статус</th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                @foreach ($ApiTokens as $ApiToken)
                                    <th scope="row">{{ $ApiToken->id }}  </th>
                                    <td>     {{ $ApiToken->user_id }}</td>
                                    <td>   {{ $ApiToken->token }}</td>
                                    <td>   {{ $ApiToken->app_type }}</td>
                                    @if ($ApiToken->blocked == 1)
                                        <td><a href="/{{$Instanse->id}}/deactivate"
                                               title="Нажмите для деактивации">Заблокирован</a></td>
                                    @else
                                        <td><a href="/{{$ApiToken->id}}/activate" title="Нажмите для активации">Не заблокирован</a>
                                        </td>
                                    @endif
                                    <td><a href="/{{$ApiToken->id}}/delete">Удалить</a></td>
                                    <td><a href="/{{$ApiToken->id}}/edit">Редактировать</a></td>

                                @endforeach

                            </tr>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
