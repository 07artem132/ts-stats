@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Статистика по инстансу</div>
                    <div class="card-body">
                        <form method="get">
                            <select
                                    name="stats-type-id"
                                    onchange="this.form.submit()"
                                    class="selectpicker show-tick float-left"
                                    data-width="260px"
                                    data-style="show-menu-arrow  btn-white"
                                    data-header="Период отображения статистики"
                                    title="Период отображения статистики"
                            >
                                <option value="1" {{request()->get('stats-type-id',1)  == 1? 'selected="selected"' : '' }}>
                                    За час
                                </option>
                                <option value="2" {{request()->get('stats-type-id',1)  == 2? 'selected="selected"' : '' }}>
                                   За день
                                </option>
                                <option value="3" {{request()->get('stats-type-id',1)  == 3? 'selected="selected"' : '' }}>
                                   За неделю
                                </option>
                                <option value="4" {{request()->get('stats-type-id',1)  == 4? 'selected="selected"' : '' }}>
                                   За месяц
                                </option>
                                <option value="5" {{request()->get('stats-type-id',1)  == 5? 'selected="selected"' : '' }}>
                                    За год
                                </option>
                            </select>
                            <select
                                    name="instance-id"
                                    onchange="this.form.submit()"
                                    data-live-search="true"
                                    class="selectpicker show-tick float-right"
                                    data-width="350px"
                                    data-style="show-menu-arrow  btn-white"
                                    title="По какому инстансу отображать статистику ?"
                                    data-header="По какому инстансу отображать статистику ?"
                            >
                                @foreach ($instances as $instance)
                                    <option value="{{ $instance->id }}" {{request()->get('instance-id',null) == $instance->id? 'selected="selected"' : '' }}>{{ $instance->ipaddress }}</option>
                                @endforeach
                            </select>
                        </form>
                        <div id="wrapper" style="position: relative; height: 400px; padding-top: 20px">
                            {!! $InstanceStatisticsCharts->script() !!}
                            {!! $InstanceStatisticsCharts->container() !!}
                        </div>
                        <div id="wrapper" style="position: relative; height: 400px ;padding-top: 20px">
                            {!! $usageClientVersionInstanceCharts->script() !!}
                            {!! $usageClientVersionInstanceCharts->container() !!}
                        </div>
                        <div id="wrapper" style="position: relative; height: 400px;padding-top: 20px">
                            {!! $ClientByCountryInstanceCharts->script() !!}
                            {!! $ClientByCountryInstanceCharts->container() !!}
                        </div>
                        <div id="wrapper" style="position: relative; height: 400px;padding-top: 20px">
                            {!! $PopularServerConfigSlotsInstanceCharts->script() !!}
                            {!! $PopularServerConfigSlotsInstanceCharts->container() !!}
                        </div>
                        <div id="wrapper" style="position: relative; height: 400px;padding-top: 20px">
                            {!! $TopPlatformClientInstanceCharts->script() !!}
                            {!! $TopPlatformClientInstanceCharts->container() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
