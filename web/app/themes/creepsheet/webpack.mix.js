let mix = require('laravel-mix');

mix.js('assets/js/main.js', 'dist/main.js')
	.sass('assets/scss/main.scss', '')
	.setPublicPath('dist/')
	.setResourceRoot('.');