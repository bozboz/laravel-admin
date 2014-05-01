var gulp = require('gulp');
var plugins = require("gulp-load-plugins")({
	replaceString: /\bgulp[\-.]/
});

var basePaths = {
	public: './public/',
	src: './src/assets/',
	bower: './bower_components/'
}
var paths = {
	styles: {
		src: basePaths.src + 'sass/*.scss',
		dest: basePaths.public + 'css/'
	},
	scripts: {
		src: basePaths.src + 'js/',
		dest: basePaths.public + 'js/'
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

var changeEvent = function(evt) {
	console.log(
		'[watcher] File ' + evt.path.replace(/.*(?=assets)/,'') + ' was ' + evt.type + ', compiling...'
	);
}

gulp.task('publish', plugins.shell.task([
		'cd ../../../ && php artisan asset:publish bozboz/admin',
	])
)

gulp.task('sass', function (){
	gulp.src(paths.styles.src)
	.pipe(plugins.rubySass({
		style: 'compressed',
		sourcemap: true,
		precision: 2
	}))
	.on('error', function(err){
		displayError(err);
	})
	.pipe(plugins.autoprefixer(
		'last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'
	))
	.pipe(gulp.dest(paths.styles.dest))
});

gulp.task('css', ['sass'], function(){
	gulp.src([
		basePaths.bower + 'bootstrap/dist/css/bootstrap.min.css',
		basePaths.bower + 'summernote/dist/summernote.css',
		paths.styles.dest + 'style.css'
	])
	.pipe(plugins.concat('admin.min.css'))
	.pipe(gulp.dest(paths.styles.dest))
});

gulp.task('scripts', function(){
	gulp.src([

		basePaths.bower + 'jquery/dist/jquery.min.js',
		basePaths.bower + 'bootstrap/dist/js/bootstrap.min.js',
		basePaths.bower + 'summernote/dist/summernote.min.js',
		basePaths.bower + 'jquery-sortable/source/js/jquery-sortable-min.js',
		basePaths.bower + 'imagesloaded/imagesloaded.pkgd.min.js',
		basePaths.bower + 'masonry/dist/masonry.pkgd.min.js',

		paths.scripts.src + 'scripts.js',
	])
	.pipe(plugins.concat('admin.js'))
    .pipe(gulp.dest(paths.scripts.dest))
    .pipe(plugins.rename('admin.min.js'))
    .pipe(plugins.uglify({outSourceMap: true}))
    .pipe(gulp.dest(paths.scripts.dest))
});

gulp.task('watch', ['scripts', 'css', 'publish'], function(){
	gulp.watch(paths.styles.src, ['css', 'publish'])
	.on('change', function(evt) {
		changeEvent(evt)
	});
	gulp.watch(paths.scripts.src + '*.js', ['scripts', 'publish'])
	.on('change', function(evt) {
		changeEvent(evt)
	});
})

gulp.task('default', ['scripts', 'css', 'publish']);
