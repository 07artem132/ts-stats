@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Список TeamSpeak 3 инстансов</div>

                    <div class="card-body">
                        <a href="{{route('add')}}">
                            <button type="button" class="btn btn-success float-right" style="margin-bottom: 10px">
                                Добавить
                                инстанс
                            </button>
                        </a>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">IP</th>
                                <th scope="col">Дата создания</th>
                                <th scope="col">Дата последнего изменения</th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($instances as $instance)
                                <tr>
                                    <th scope="row">{{ $instance->id }}  </th>
                                    <td>   {{ $instance->ipaddress }}</td>
                                    <td>   {{ $instance->created_at }}</td>
                                    <td>   {{ $instance->updated_at }}</td>
                                    <td><a href="/{{$instance->id}}/edit">Редактировать</a></td>
                                    <td><a href="/{{$instance->id}}/delete">Удалить</a></td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        {{ $instances->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
