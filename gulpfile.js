/**
 * Plugins
 */

var requireDir = require('require-dir'),
	dir = requireDir('gulp-tasks'), // Load gulp-tasks/*.js
	gulp = require('gulp');

/**
 * Tasks
 */

gulp.task('watch', function () {
	gulp.watch('resources/assets/sass/**/*.scss', ['sass']);
	gulp.watch('resources/assets/js/**/*.js', ['scripts']);
});

gulp.task('default', ['copies', 'sass', 'scripts']);
