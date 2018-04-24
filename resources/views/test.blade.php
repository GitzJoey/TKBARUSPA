@extends('layouts.codebase.blank')

@section('title')
@endsection

@section('custom_css')
@endsection

@section('content')
    <div id="test1">
        <div class="input-group">
            <flat-pickr v-model="date" class="form-control"></flat-pickr>
        </div>
    </div>

    <br><br><br>

    <div id="test2">
        <div>
            <label class="typo__label">Single select</label>
            <multiselect v-model="value" :options="options" :searchable="false" :close-on-select="false" :show-labels="false" placeholder="Pick a value"></multiselect>
            <pre class="language-json"><code>@{{ value  }}</code></pre>
        </div>
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
                date:''
            }
        });

        var test2 = new Vue({
            components: {
                Multiselect: window.VueMultiselect.default
            },
            el: '#test2',
            data: {
                value: 0,
                options:[2,3,4,5,6]
            }
        });

        var test3 = new Vue({
            el: '#test3',
            data: {

            }
        });


    </script>
@endsection