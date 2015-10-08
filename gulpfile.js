/**
* Plugins
**/

var requireDir = require('require-dir'),
	dir = requireDir('gulp-tasks'), // Load gulp-tasks/*.js
	gulp = require('gulp');

/**
* Tasks
**/

gulp.task('watch', function () {
	gulp.watch('app/assets/sass/**/*.scss', ['sass']);
	gulp.watch('src/assets/js/**/*.js', ['scripts']);
});

gulp.task('default', ['sass', 'scripts']);
