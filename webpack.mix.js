const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

 mix.js('resources/js/app.js', 'public/js')
 .vue() // <- Add this
 .postCss('resources/css/app.css', 'public/css', [
    require('tailwindcss'),
    require('autoprefixer'),
 ]);


mix.styles([
    'public/assets/css/bootstrap.min.css',
    'public/assets/css/font-awesome.min.css',
    'public/assets/css/fonts.googleapis.com.css',
    'public/assets/css/ace.min.css',
    'public/assets/css/ace-skins.min.css',
    'public/assets/css/ace-part2.min.css',
    'public/assets/css/ace-rtl.min.css',
    'public/assets/css/ace-ie.min.css',
    'public/assets/css/sweetalert2.min.css',
    'public/assets/css/responsive.css',
    'public/assets/css/style.css',
], 'public/assets/css/app.css');

mix.scripts([
    'public/assets/js/jquery.min.js',
    'public/assets/js/bootstrap.min.js',
    'public/assets/js/ace-elements.min.js',
    'public/assets/js/ace.min.js',
    'public/assets/js/typed.js',
    'public/assets/js/sweetalert2.all.min.js',
], 'public/assets/js/app.js');
