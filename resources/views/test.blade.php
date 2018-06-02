@extends('layouts.codebase.blank')

@section('title')
@endsection

@section('custom_css')
@endsection

@section('content')
    <div id="test1">
        <div>
            <label class="typo__label" for="ajax">Async multiselect</label>
            <multiselect v-model="selectedCountries"
                         id="ajax"
                         label="name"
                         track-by="hId"
                         placeholder="Type to search"
                         open-direction="bottom"
                         :options="countries"
                         :multiple="false"
                         :searchable="true"
                         :loading="isLoading"
                         :internal-search="false"
                         :clear-on-select="false"
                         :close-on-select="true"
                         :options-limit="300"
                         :limit="3"
                         :limit-text="limitText"
                         :max-height="600"
                         :show-no-results="false"
                         :hide-selected="false"
                         @search-change="asyncFind"
                        @input="clearAll">
            </multiselect>
            <pre class="language-json"><code>@{{ selectedCountries  }}</code></pre>
        </div>
    </div>

    <br><br><br>

    <div id="test2">
    </div>

    <br><br><br>

    <div id="test3">

    </div>
@endsection

@section('ziggy')
    @routes('customer')
@endsection

@section('custom_js')
    <script type="application/javascript">
        var test1 = new Vue({
            el: '#test1',
            data: {
                selectedCountries: [],
                countries: [],
                isLoading: false
            },
            methods: {
                limitText (count) {
                    return `and ${count} other countries`
                },
                asyncFind (query) {
                    this.isLoading = true;
                    axios.get('/api/get/customer/search/c').then(
                        response => {
                            console.log(response.data);
                            this.countries = response.data;
                            this.isLoading = false;
                        }
                    );
                },
                clearAll () {
                    this.selectedCountries = '';
                }
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