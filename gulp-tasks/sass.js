/**
*
* Plugins
*
**/

var gulp = require('gulp'),
	concat = require('gulp-concat'),
	sass = require('gulp-sass'),
	autoprefixer = require('gulp-autoprefixer'),
	combineMQ = require('gulp-combine-media-queries'),
	minifyCSS = require('gulp-minify-css'),
	size = require('gulp-size');

/**
 * Variables
 */

 var appFiles = [
 	'src/assets/sass/style.scss'
 ]

/**
 * Gulp Tasks
 */

// function concatCSS(src) {

// }

function sassCompiler(src, filename) {
	return gulp.src(src)
		.pipe(concat(filename))
		.pipe(sass())
		.pipe(autoprefixer({ browsers: ['last 2 versions'] }))
		.pipe(combineMQ())
		.pipe(minifyCSS({ keepSpecialComments: 0 }))
		.pipe(size())
		.pipe(gulp.dest('public/css'));
}

gulp.task('sass', function() {
	sassCompiler(appFiles, 'admin.min.css')
});