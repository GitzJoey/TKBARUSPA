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
    window.accountingJS = require('accounting-js/dist/accounting.es6');
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
        formatMoneyToString: function(value) {
            let settings = document.getElementById("appSettings").value.split('|');
            let thousandSeparator = settings[3];
            let decimalSeparator = settings[4];
            let decimalDigit = parseInt(settings[5]);
            let currencySymbol = 'Rp';

            return accountingJS.formatMoney(value, {
                symbol : currencySymbol,
                decimal : decimalSeparator,
                thousand: thousandSeparator,
                precision : decimalDigit,
                format: {
                    pos: "%v %s",
                    neg: "(%v) %s",
                    zero: "-- %s"
                } });
        },
        formatNumberToString: function(value) {
            let settings = document.getElementById("appSettings").value.split('|');
            let thousandSeparator = settings[3];
            let decimalSeparator = settings[4];
            let decimalDigit = parseInt(settings[5]);

            return accountingJS.formatNumber(value, {
                decimal : decimalSeparator,
                thousand: thousandSeparator,
                precision : decimalDigit
            });
        }
    }
});

