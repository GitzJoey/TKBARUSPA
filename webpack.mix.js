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

mix.js('resources/assets/js/codebase.js', 'public/js/codebase')
    .scripts([
        'public/js/codebase/codebase.js',
        'resources/assets/js/codebase/codebase.js',
        'resources/assets/js/codebase/app.js'
    ], 'public/js/codebase/codebase.js')
    .copy('node_modules/popper.js/dist/umd/popper.js.map', 'public/js/codebase')
    .copy('resources/assets/js/apps/company.js', 'public/js/apps')
    .copy('resources/assets/js/apps/unit.js', 'public/js/apps')
    .minify('public/js/apps/company.js')
    .sass('resources/assets/sass/codebase.scss', 'public/css/codebase')
    .styles([
        'node_modules/fullcalendar/dist/fullcalendar.css',
        'public/css/codebase/codebase.css',
        'node_modules/animate.css/animate.css'
    ], 'public/css/codebase/codebase.css')
    .copyDirectory('resources/assets/css/codebase/themes', 'public/css/codebase/themes')
    .version();