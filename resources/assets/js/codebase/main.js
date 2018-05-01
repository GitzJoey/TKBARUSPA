window._ = require('lodash');
window.Popper = require('popper.js').default;

try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
    require('jquery-slimscroll/jquery.slimscroll');
    require('jquery-scroll-lock/jquery-scrollLock');
    require('jquery.appear/jquery.appear');
    require('jquery-countto/jquery.countTo');
    require('jquery.cookie/jquery.cookie');
    window.moment = require('moment/moment');
    window.momentDurationFormat = require('moment-duration-format');
    require('jquery-gotop/src/jquery.gotop');
    require('fullcalendar/dist/fullcalendar');
    require('fullcalendar/dist/locale/id');
} catch (e) {
    console.error(e.message);
}

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');
let language = document.documentElement.lang;

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

if (language) {
    window.axios.defaults.headers.common['X-localization'] = language;
} else {
    console.error('X-localization not found.');
}

window.Vue = require('vue');
window.VeeValidate = require('vee-validate');
window.VeeValidateID = require('vee-validate/dist/locale/id');

Vue.use(VeeValidate, {
    delay: 100,
    locale: $('html').attr('lang'),
    dictionary: VeeValidateID
});

window.flatPickr = require('vue-flatpickr-component');
Vue.use(flatPickr);

//Vue.component('passport-clients', require('./components/passport/Clients.vue'));
//Vue.component('passport-authorized-clients', require('./components/passport/AuthorizedClients.vue'));
//Vue.component('passport-personal-access-tokens',require('./components/passport/PersonalAccessTokens.vue'));
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('vue-autonumeric', require('vue-autonumeric'));

Vue.mixin({
    methods: {
        handleErrors: function(e) {
            //Catch For Laravel Validation
            if (e.response.data.errors != undefined && Object.keys(e.response.data.errors).length > 0) {
                for (var key in e.response.data.errors) {
                    for (var i = 0; i < e.response.data.errors[key].length; i++) {
                        this.$validator.errors.add('', e.response.data.errors[key][i], '', null);
                    }
                }
            } else {
                //Catch From Controller
                this.$validator.errors.add('', e.response.data.message + ' (' +e.response.status + ' ' + e.response.statusText + ')', '', null);
            }
        },
        generateQueryStrings: function(arrQuery) {
            if (arrQuery.length == 0) return '';

            if (arrQuery.length == 1) {
                return '?' + arrQuery[0]['key'] + '=' + arrQuery[0]['value'];
            } else {
                var result = '?' + arrQuery[0]['key'] + '=' + arrQuery[0]['value'];
                for (var i = 1; i < arrQuery.length; i++) {
                    result = result + '&' + arrQuery[i]['key'] + '=' + arrQuery[i]['value']
                }
                return result;
            }
        },
        getCurrentUrl: function(optionalPath) {
            if (typeof(optionalPath) !== 'undefined') {
                return location.protocol + '//' + location.hostname + (location.port ? ':' + location.port:'') + optionalPath;
            } else {
                return location.protocol + '//' + location.hostname + (location.port ? ':' + location.port:'');
            }
        },
        route: function() {
            if (typeof(route) !== 'undefined') return route;
            else return null;
        },
        loadingPanel: function(elementId, state) {
            switch (state.toUpperCase()) {
                case "ON":
                    break;
                case "OFF":
                    break;
                case "TOGGLE":
                default:
                    Codebase.blocks(elementId, 'state_toggle');
                    break;
            }
        },
        contentPanel: function(elementId, state) {
            switch (state.toUpperCase()) {
                case "OPEN":
                    Codebase.blocks(elementId, 'open');
                    break;
                case "CLOSE":
                    Codebase.blocks(elementId, 'close');
                    break;
                case "TOGGLE":
                default:
                    break;
            }
        }
    },
    computed: {
        defaultFlatPickrConfig: function() {
            var conf = document.getElementById("appSettings").value.split('|');
            var flatPickrTimeFormat = '';
            switch (conf[2]) {
                case "G:H:s": flatPickrTimeFormat = 'H:i:S'; break;
                case "g:i A": flatPickrTimeFormat = ' h:i K'; break;
                default: break;
            }

            return {
                enableTime: true,
                dateFormat: conf[1] + ' ' + flatPickrTimeFormat,
                plugins: [new confirmDatePlugin({
                    confirmIcon: "<i class='fa fa-check'></i>",
                    confirmText: ""
                }), new scrollPlugin()],
                minuteIncrement: 15
            }
        },
        defaultCurrencyConfig: function() {
            var conf = document.getElementById("appSettings").value.split('|');
            return {
                digitGroupSeparator: conf[3],
                decimalCharacter: conf[4],
                decimalCharacterAlternative: '.',
                decimalPlaces: 0,
                currencySymbol: ' Rp',
                currencySymbolPlacement: 's',
                roundingMethod: 'U',
                minimumValue: '0',
                unformatOnSubmit: true,
                caretPositionOnFocus: 'start'
            }
        },
        defaultNumericConfig: function() {
            var conf = document.getElementById("appSettings").value.split('|');
            return {
                digitGroupSeparator: conf[3],
                decimalCharacter: conf[4],
                decimalCharacterAlternative: '.',
                decimalPlaces: 1,
                roundingMethod: 'U',
                minimumValue: '0',
                unformatOnSubmit: true,
                caretPositionOnFocus: 'start'
            }
        },
        defaultPercentageConfig: function() {
            var conf = document.getElementById("appSettings").value.split('|');
            return {
                digitGroupSeparator: conf[3],
                allowDecimalPadding: false,
                suffixText: ' %',
                roundingMethod: 'U',
                minimumValue: '0',
                maximumValue: '100',
                unformatOnSubmit: true,
                showWarnings: false,
                caretPositionOnFocus: 'start'
            }
        },
        defaultDateFormat: function() {
            var conf = document.getElementById("appSettings").value.split('|');
            return conf[1];
        },
        defaultTimeFormat: function() {
            var conf = document.getElementById("appSettings").value.split('|');
            return conf[2];
        },
        defaultDateTimeFormat: function() {
            return conf[1] + ' ' + conf[2];
        },
        databaseDateFormat: function() {
            var conf = document.getElementById("appSettings").value.split('|');
            return conf[6];
        },
        databaseTimeFormat: function() {
            var conf = document.getElementById("appSettings").value.split('|');
            return conf[7];
        }
    }
});

