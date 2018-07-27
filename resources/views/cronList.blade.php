@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Планировщик задач</div>

                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Задание</th>
                                <th scope="col">Статус</th>
                                <th scope="col">Расписание</th>
                                <th scope="col">Последний запуск</th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($tasks as $line)
                                <tr>
                                    <th scope="row">{{ $line->id }}  </th>
                                    <td>     {{ $line->name }}</td>
                                    @if ($line->is_enabled == 1)
                                        <td>
                                            <a href="{{route('CronDeactivate',['id'=>$line->id])}}"
                                               title="Нажмите для деактивации">Включено</a>
                                        </td>
                                    @else
                                        <td>
                                            <a href="{{route('CronActivate',['id'=>$line->id])}}"
                                               title="Нажмите для активации">Выключено</a>
                                        </td>
                                    @endif
                                    <td>   {{ $line->frequency }}</td>
                                    <td>   {{ $line->last_run }}</td>
                                    <td><a href="{{route('CronLog',['id'=>$line->id])}}">лог</a></td>
                                    <td><a href="{{route('CronEdit',['id'=>$line->id])}}">изменить</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
