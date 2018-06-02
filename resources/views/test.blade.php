@extends('layouts.codebase.blank')

@section('title')
@endsection

@section('custom_css')
@endsection

@section('content')
    <div id="test1">
            <multiselect v-model="value" :options="options"></multiselect>
    </div>

    <br><br><br>

    <div id="test2">
    </div>

    <br><br><br>

    <div id="test3">

    </div>
@endsection

@section('custom_js')
    <script type="application/javascript">
        var test1 = new Vue({
            el: '#test1',
            data: {
                options: ['list', 'of', 'options'],
                value: ''
            }
        });

        var test2 = new Vue({
            el: '#test2',
            data: {

            }
        });

        var test3 = new Vue({
            el: '#test3',
            data: {

            }
        });
    </script>
@endsection