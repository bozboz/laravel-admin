/**
 * Plugins
 */

var gulp = require('gulp'),
	concat = require('gulp-concat'),
	minify = require('gulp-uglify'),
	size = require('gulp-size');

/**
 * Paths
 */

var appFiles = [

	// jQuery UI
	'bower_components/jquery-ui/ui/core.js',
	'bower_components/jquery-ui/ui/widget.js',
	'bower_components/jquery-ui/ui/datepicker.js',
	'bower_components/jquery-ui/ui/mouse.js',
	'bower_components/jquery-ui/ui/sortable.js',

	// Misc
	'bower_components/bootstrap/dist/js/bootstrap.min.js',
	'bower_components/summernote/dist/summernote.min.js',
	'bower_components/imagesloaded/imagesloaded.pkgd.min.js',
	'bower_components/masonry/dist/masonry.pkgd.min.js',
	'bower_components/handlebars/handlebars.min.js',
	'bower_components/jqueryui-timepicker-addon/dist/jquery-ui-timepicker-addon.js',
	'bower_components/jqueryui-timepicker-addon/dist/jquery-ui-sliderAccess.js',
	'bower_components/select2/dist/js/select2.min.js',
	'bower_components/nestedSortable/jquery.ui.nestedSortable.js',
	'resources/assets/js/knockout.js',

	// Bluimp
	'bower_components/blueimp-file-upload/jquery.iframe-transport.js',
	'bower_components/blueimp-file-upload/jquery.fileupload.js',

	// Custom
	'resources/assets/js/scripts.js',
	'resources/assets/js/media-browser.js'
];

var mediaScripts = [
	'bower_components/blueimp-tmpl/js/tmpl.js',
	'bower_components/blueimp-load-image/js/load-image.all.min.js',
	'bower_components/blueimp-canvas-to-blob/js/canvas-to-blob.js',
	'bower_components/blueimp-file-upload/js/jquery.fileupload-process.js',
	'bower_components/blueimp-file-upload/js/jquery.fileupload-image.js',
	'bower_components/blueimp-file-upload/js/jquery.fileupload-validate.js',
	'bower_components/blueimp-file-upload/js/jquery.fileupload-ui.js'
];

/**
 * Functions
 */

function jsCompiler(files, filename) {
	return gulp.src(files)
		.pipe(concat(filename))
		.pipe(minify())
		.pipe(size({'title': filename}))
		.pipe(gulp.dest('public/js'))
}

/**
 * Gulp Tasks
 */

gulp.task('scripts', function(){
	jsCompiler(appFiles, 'admin.min.js');
	jsCompiler(mediaScripts, 'upload-ui.min.js');
});
