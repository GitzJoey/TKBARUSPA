let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.disableNotifications();

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');

mix.js('resources/assets/js/codebase/main.js', 'public/js/codebase')
    .scripts([
        'public/js/codebase/main.js',
        'resources/assets/js/codebase/codebase.js',
        'resources/assets/js/codebase/app.js'
    ], 'public/js/codebase/main.js')
    .copy('node_modules/popper.js/dist/umd/popper.js.map', 'public/js/codebase')
    .copy('node_modules/vue-select/dist/vue-select.js.map', 'public/js/codebase')
    .sass('resources/assets/sass/codebase/main.scss', 'public/css/codebase')
    .sass('resources/assets/sass/codebase/codebase/themes/corporate.scss', 'public/css/codebase/themes')
    .sass('resources/assets/sass/codebase/codebase/themes/earth.scss', 'public/css/codebase/themes')
    .sass('resources/assets/sass/codebase/codebase/themes/elegance.scss', 'public/css/codebase/themes')
    .sass('resources/assets/sass/codebase/codebase/themes/flat.scss', 'public/css/codebase/themes')
    .sass('resources/assets/sass/codebase/codebase/themes/pulse.scss', 'public/css/codebase/themes')
    .styles([
        'node_modules/fullcalendar/dist/fullcalendar.css',
        'public/css/codebase/main.css',
        'node_modules/animate.css/animate.css',
        'node_modules/flatpickr/dist/flatpickr.css'
    ], 'public/css/codebase/main.css')
    .version();

mix.copy('resources/assets/js/apps/company.js', 'public/js/apps')
    .copy('resources/assets/js/apps/unit.js', 'public/js/apps')
    .copy('resources/assets/js/apps/product.js', 'public/js/apps')
    .copy('resources/assets/js/apps/supplier.js', 'public/js/apps')
    .copy('resources/assets/js/apps/customer.js', 'public/js/apps')
    .copy('resources/assets/js/apps/po.js', 'public/js/apps')
    .copy('resources/assets/js/apps/warehouse.js', 'public/js/apps')
    .copy('resources/assets/js/apps/price_level.js', 'public/js/apps')
    .copy('resources/assets/js/apps/vendor_trucking.js', 'public/js/apps')
    .copy('resources/assets/js/apps/phone_provider.js', 'public/js/apps')
    .copy('resources/assets/js/apps/user.js', 'public/js/apps')
    .minify('public/js/apps/company.js')
    .minify('public/js/apps/unit.js')
    .minify('public/js/apps/product.js')
    .minify('public/js/apps/supplier.js')
    .minify('public/js/apps/customer.js')
    .minify('public/js/apps/po.js')
    .minify('public/js/apps/warehouse.js')
    .minify('public/js/apps/price_level.js')
    .minify('public/js/apps/vendor_trucking.js')
    .minify('public/js/apps/phone_provider.js')
    .minify('public/js/apps/user.js')
    .version();