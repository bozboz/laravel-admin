var gulp = require('gulp');

var sass = require('gulp-ruby-sass');
var prefix = require('gulp-autoprefixer');
var shell = require('gulp-shell')

var paths = {

	styles: {
		src: './src/assets/sass/*.scss',
		dest: './public/css/'
	}

}


var displayError = function(error) {

	var errorString = '[' + error.plugin + ']';
	errorString += ' ' + error.message.replace("\n",'');

	if(error.fileName)
		errorString += ' in ' + error.fileName;

	if(error.lineNumber)
		errorString += ' on line ' + error.lineNumber;

	console.error(errorString);
}

gulp.task('sass', function (){
	gulp.src(paths.styles.src)
	.pipe(sass({
		style: 'compressed',
		sourcemap: true,
		precision: 2
	}))
	.on('error', function(err){
		displayError(err);
	})
	.pipe(prefix(
		'last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'
	))
	.pipe(gulp.dest(paths.styles.dest))
	.pipe(shell([
		'cd ../../../ && php artisan asset:publish --path=/vendor/bozboz/admin/public && cd -',
	]))
});

gulp.task('watch', ['sass'], function(){
	gulp.watch(paths.styles.src, ['sass'])
	.on('change', function(evt) {
		console.log(
			'[watcher] File ' + evt.path.replace(/.*(?=sass)/,'') + ' was ' + evt.type + ', compiling...'
		);
	});
})

gulp.task('default', ['sass']);
