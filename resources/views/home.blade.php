@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Сводка</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <script src=//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js charset=utf-8></script>
                        {!! $realTimeCharts->script() !!}
                        {!! $usageClientVersion->script() !!}
                        {!! $ClientByContry->script() !!}
                        <div>{!! $realTimeCharts->container() !!}</div>
                        <div>{!! $usageClientVersion->container() !!}</div>
                        <div>{!! $ClientByContry->container() !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
