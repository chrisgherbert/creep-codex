import gulp from 'gulp';
import gulpLoadPlugins from 'gulp-load-plugins';
import browserSync from 'browser-sync';
import del from 'del';
import yargs from 'yargs';
import projectVars from './gulpvars.js';

const $ = gulpLoadPlugins();
const reload = browserSync.reload;
const plumberHandler = {
	errorHandler: $.notify.onError({
		title: 'Gulp',
		message: 'Error: ${error.message}'
	})
};

//////////////////////
// Helper Functions //
//////////////////////

function getDevVars() {

	let argv = yargs
		.usage('Usage: gulp <command> -port [num] -proxy [string] -devvars [string]')
		.example('gulp serve -devvars ./devvars.js')
		.alias('devvars', 'devVars')
		.argv

	let devVars = {};

	if (argv.port) {
		devVars.port = argv.port;
	}

	if (argv.proxy) {
		devVars.proxy = argv.proxy;
	}

	if (argv.devvars) {
		devVars = require(argv.devvars);
	}

	// Check and see if devVars is not empty
	if (Object.getOwnPropertyNames(devVars).length > 0) {
		return devVars;
	} else {
		console.warn('You must pass in a port number, proxy name, or devvars file to use this task');
	}


}

//////////////////
// Helper tasks //
//////////////////

gulp.task('minifycss', ['styles'], () => {
	return gulp.src('theme_dist/main.css')
		.pipe($.cssnano({
			discardComments: {removeAll: true}
		}))
		.pipe(gulp.dest('theme_dist'));
});

gulp.task('minifyjs', ['scripts'], () => {
	return gulp.src('theme_dist/main.js')
		.pipe($.uglify())
		.pipe(gulp.dest('theme_dist'));
});

gulp.task('bower', () => {
	return $.bower();
});

////////////////
// Main tasks //
////////////////

/**
 * Main SASS task - parses, autoprefixes, and writes sourcemaps
 */
gulp.task('styles', () => {
	return gulp.src( 'assets/scss/main.scss' )
		.pipe($.plumber(plumberHandler))
		.pipe($.sourcemaps.init())
		.pipe($.sass.sync({
			outputStyle: 'expanded',
			precision: 10,
			includePaths: projectVars.scssPaths
		}).on('error', $.sass.logError))
		.pipe($.autoprefixer())
		.pipe($.sourcemaps.write())
		.pipe(gulp.dest('theme_dist'))
		.pipe(reload({stream: true}));
});

/**
 * Main javascript task - writes sourcemaps, lints, and concatenates
 */
gulp.task('scripts', () => {
	return gulp.src( projectVars.jsFiles )
	.pipe($.plumber(plumberHandler))
	.pipe($.sourcemaps.init())
	.pipe($.eslint({
		rules: {
			"eqeqeq": 0, // turn off errors about "==" vs "==="
			"quotes": 0, // turn off errors about using doublequotes for strings,
			"comma-spacing": 0, // turn off errors about spaces after commas
			"camelcase": 0 // turn off errors about camelcase
		}
	}))
	.pipe($.eslint.format())
	.pipe($.concat('main.js', {
		newLine: '\n;' // needed in case the file ends with a line comment or last line not terminated
	}))
	.pipe(gulp.dest('theme_dist'))
	.pipe(reload({stream: true}));
});

/**
 * Main watch task
 * Watches styles and scripts and compiles
 */
gulp.task('watch', ['styles', 'scripts'], () => {
	gulp.watch(['assets/scss/**/*.scss'], ['styles']);
	gulp.watch(['assets/js/**/*.js'], ['scripts']);
});

/**
 * Main clean task - deletes 'dist' folder and contents
 */
gulp.task('clean', del.bind(null, ['theme_dist/']));

/**
 * Main serve task
 * Starts a browserSync session, watches for changes in .twig, .php, .scss, and .js
 * Runs appropriate task and reloads
 */
gulp.task('serve', ['styles', 'scripts'], () => {

	let devVars = getDevVars();

	browserSync({
		notify: false,
		proxy: devVars.proxy,
		port: devVars.port
	});

	gulp.watch([
		'**/*.php',
		'**/*.twig'
	]).on('change', reload);

	gulp.watch(['assets/scss/**/*.scss'], ['styles']);
	gulp.watch(['assets/js/**/*.js'], ['scripts']);
});

/**
 * Serve task for javascript testing
 */
gulp.task('serve:test', () => {
	browserSync({
		notify: false,
		port: 9000,
		ui: false,
		server: {
			baseDir: 'tests/js',
			routes: {
				'/bower_components': 'assets/bower_components',
				'/js': 'assets/js',
				'/dist': 'theme_dist'
			}
		}
	});

	gulp.watch('tests/js/spec/**/*.js').on('change', reload);
	gulp.watch(['assets/js/**/*.js'], ['scripts']);
});

/**
 * Build serve task
 * Starts a browserSync session, watches for changes in .twig, .php, .scss, and .js
 * Runs appropriate build tasks and reloads
 */
gulp.task('serve:build', ['build'], () =>{

	let devVars = getDevVars();

	browserSync({
		notify: false,
		proxy: devVars.proxy,
		port: devVars.port
	});

	gulp.watch([
		'**/*.php',
		'**/*.twig'
	]).on('change', reload);

	gulp.watch(['assets/scss/**/*.scss'], ['styles', 'minifycss']);
	gulp.watch(['assets/js/**/*.js'], ['scripts', 'minifyjs']);
});

/**
 * Build task
 * Runs `bower install`, concatenates and minifies JS & SASS
 */
gulp.task('build', ['bower', 'minifycss', 'minifyjs']);