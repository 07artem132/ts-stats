@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Список TeamSpeak 3 инстансов</div>

                    <div class="card-body">
                        <a href="{{route('add')}}">
                        <button type="button" class="btn btn-success float-right" style="margin-bottom: 10px">Добавить
                            инстанс
                        </button></a>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Название</th>
                                <th scope="col">IP</th>
                                <th scope="col">Hostname</th>
                                <th scope="col">Статус</th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                @foreach ($Instanses as $Instanse)
                                    <th scope="row">{{ $Instanse->id }}  </th>
                                    <td>     {{ $Instanse->name }}</td>
                                    <td>   {{ $Instanse->ipaddress }}</td>
                                    <td>   {{ $Instanse->hostname }}</td>
                                    @if ($Instanse->is_enabled == 1)
                                        <td><a href="/{{$Instanse->id}}/deactivate"
                                               title="Нажмите для деактивации">Активен</a></td>
                                    @else
                                        <td><a href="/{{$Instanse->id}}/activate" title="Нажмите для активации">Отключен</a>
                                        </td>
                                    @endif
                                    <td><a href="/{{$Instanse->id}}/delete">Удалить</a></td>
                                    <td><a href="/{{$Instanse->id}}/edit">Редактировать</a></td>

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
