var basePaths = {
	src: 'src/assets/',
	dest: 'public/',
	bower: 'bower_components/'
};
var paths = {
	images: {
		src: basePaths.src + 'images/',
		dest: basePaths.dest + 'images/min/'
	},
	scripts: {
		src: basePaths.src + 'js/',
		dest: basePaths.dest + 'js/'
	},
	styles: {
		src: basePaths.src + 'sass/',
		dest: basePaths.dest + 'css/'
	},
	sprite: {
		src: basePaths.src + 'sprite/*'
	}
};

var appFiles = {
	styles: paths.styles.src + '**/*.scss',
	scripts: [paths.scripts.src + 'scripts.js']
};

var vendorFiles = {
	styles: [
		basePaths.bower + 'summernote/dist/summernote.css'
	],
	scripts: [
		basePaths.bower + 'bootstrap/dist/js/bootstrap.min.js',
		basePaths.bower + 'summernote/dist/summernote.min.js',
		basePaths.bower + 'jquery-sortable/source/js/jquery-sortable-min.js',
		basePaths.bower + 'imagesloaded/imagesloaded.pkgd.min.js',
		basePaths.bower + 'masonry/dist/masonry.pkgd.min.js',
		basePaths.bower + 'handlebars/handlebars.min.js'
	]
};



/*
	Let the magic begin
*/

var gulp = require('gulp');

var es = require('event-stream');
var gutil = require('gulp-util');

var plugins = require("gulp-load-plugins")({
	pattern: ['gulp-*', 'gulp.*'],
	replaceString: /\bgulp[\-.]/
});

// Allows gulp --dev to be run for a more verbose output
var isProduction = true;
var sassStyle = 'compressed';
var sourceMap = false;

if(gutil.env.dev === true) {
	sassStyle = 'expanded';
	sourceMap = true;
	isProduction = false;
}

var changeEvent = function(evt) {
	gutil.log('File', gutil.colors.cyan(evt.path.replace(new RegExp('/.*(?=/' + basePaths.src + ')/'), '')), 'was', gutil.colors.magenta(evt.type));
};

gulp.task('compile-css', function(){

	var sassFiles = gulp.src(appFiles.styles)
	.pipe(plugins.rubySass({
		style: sassStyle, sourcemap: sourceMap, precision: 2
	}))
	.on('error', function(err){
		new gutil.PluginError('CSS', err, {showStack: true});
	});

	return es.concat(gulp.src(vendorFiles.styles), sassFiles)
		.pipe(plugins.concat('admin.min.css'))
		.pipe(plugins.autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
		.pipe(isProduction ? plugins.combineMediaQueries({
			log: true
		}) : gutil.noop())
		.pipe(isProduction ? plugins.cssmin() : gutil.noop())
		.pipe(plugins.size())
		.pipe(gulp.dest(paths.styles.dest));
});
gulp.task('css', ['compile-css']);

gulp.task('compile-scripts', function(){

	gulp.src(vendorFiles.scripts.concat(appFiles.scripts))
		.pipe(plugins.concat('admin.min.js'))
		.pipe(gulp.dest(paths.scripts.dest))
		.pipe(isProduction ? plugins.uglify({outSourceMap: false}) : gutil.noop())
		.pipe(plugins.size())
		.pipe(gulp.dest(paths.scripts.dest));

});
gulp.task('scripts', ['compile-scripts']);

gulp.task('watch', ['css', 'scripts'], function(){
	gulp.watch(appFiles.styles, ['css']).on('change', function(evt) {
		changeEvent(evt);
	});
	gulp.watch(paths.scripts.src + '*.js', ['scripts']).on('change', function(evt) {
		changeEvent(evt);
	});
});

gulp.task('default', ['css', 'scripts']);
gulp.task('debug', function(){
	console.log(plugins)
});

