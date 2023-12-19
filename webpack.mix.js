const mix = require('laravel-mix');

mix.js('resources/js/attendance.js', 'public/js')
   .setResourceRoot('/public');