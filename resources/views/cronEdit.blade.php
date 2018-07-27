@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Редактирование задачи</div>

                    <div class="card-body">
                        <form method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="frequency">Расписание</label>
                                <input type="text" class="form-control" name="frequency" id="frequency" value="{{$frequency}}">
                            </div>
                            <button type="submit" class="btn btn-success">Изменить</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
