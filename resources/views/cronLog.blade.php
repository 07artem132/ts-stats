@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Лог планировщика задач</div>

                    <div class="card-body">
                        <table  class="table table-hover">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Статус</th>
                                <th scope="col">Сообщение</th>
                                <th scope="col">Выполнено за</th>
                                <th scope="col">Время запуска</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($logs as $line)
                                <tr>
                                    <th scope="row">{{ $line->id }}  </th>
                                    @if ($line->status == 1)
                                        <td>Успех</td>
                                    @else
                                        <td>Неудача</td>
                                    @endif
                                    <td style="word-break: break-all;">   {{ $line->message }}</td>
                                    <td>   {{ sprintf("%02.2f",$line->run_time) }} сек</td>
                                    <td>   {{ $line->created_at }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
