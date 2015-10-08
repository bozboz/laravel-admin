/**
 * Plugins
 */

var gulp = require('gulp'),
	concat = require('gulp-concat'),
	minifyCSS = require('gulp-minify-css');

/**
 * Paths
 */

var vendorCSSFiles = [
	'bower_components/summernote/dist/summernote.css',

	// jQuery UI
	'bower_components/jquery-ui/themes/base/core.css',
	'bower_components/jquery-ui/themes/base/accordion.css',
	'bower_components/jquery-ui/themes/base/autocomplete.css',
	'bower_components/jquery-ui/themes/base/button.css',
	'bower_components/jquery-ui/themes/base/datepicker.css',
	'bower_components/jquery-ui/themes/base/dialog.css',
	'bower_components/jquery-ui/themes/base/draggable.css',
	'bower_components/jquery-ui/themes/base/menu.css',
	'bower_components/jquery-ui/themes/base/progressbar.css',
	'bower_components/jquery-ui/themes/base/resizable.css',
	'bower_components/jquery-ui/themes/base/selectable.css',
	'bower_components/jquery-ui/themes/base/selectmenu.css',
	'bower_components/jquery-ui/themes/base/sortable.css',
	'bower_components/jquery-ui/themes/base/slider.css',
	'bower_components/jquery-ui/themes/base/spinner.css',
	'bower_components/jquery-ui/themes/base/tabs.css',
	'bower_components/jquery-ui/themes/base/tooltip.css',
	'bower_components/jquery-ui/themes/base/theme.css',
	'bower_components/jquery-ui/themes/base/datepicker.css',
	'bower_components/jquery-ui/themes/base/slider.css',

	'bower_components/jqueryui-timepicker-addon/dist/jquery-ui-timepicker-addon.css',
	'bower_components/select2/dist/css/select2.css',
];

var fontAwesome = [
	'bower_components/font-awesome/fonts/**.*'
];

/**
 * Tasks
 */

gulp.task('copies', function(){
	gulp.src(vendorCSSFiles)
		.pipe(concat('_vendors.scss'))
		.pipe(gulp.dest('resources/assets/sass'));

	gulp.src(fontAwesome)
		.pipe(gulp.dest('public/css/fonts'))
});