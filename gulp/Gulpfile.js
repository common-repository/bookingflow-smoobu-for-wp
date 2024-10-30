var gulp = require('gulp');
var sass = require('gulp-sass');
var minifyCSS = require('gulp-minify-css');
var minifyJquery = require('gulp-minify');

gulp.task('sass', function (done) {
	gulp.src('../assets/sass/admin/main.scss')
		.pipe(sass().on('error', sass.logError))
		.pipe(gulp.dest('../assets/css/admin'));
	done();
});

gulp.task('watch', function (done) {
	gulp.watch('../assets/sass/**/*.scss', gulp.series('sass'));
	done();
});

gulp.task('default', gulp.series('sass', 'watch'));

gulp.task('sass:prod', function () {
	return gulp.src('../assets/css/admin/main.css')
		.pipe(minifyCSS())
		.pipe(gulp.dest('../build/css/admin'))
});

gulp.task('js:prod', function () {
	return gulp.src('../assets/js/admin/main.js')
		.pipe(minifyJquery())
		.pipe(gulp.dest('../build/js/admin'))
});

gulp.task('sass-user:prod', function () {
	return gulp.src('../assets/css/main.css')
		.pipe(minifyCSS())
		.pipe(gulp.dest('../build/css'))
});

gulp.task('js-user:prod', function () {
	return gulp.src('../assets/js/main.js')
		.pipe(minifyJquery())
		.pipe(gulp.dest('../build/js'))
});

gulp.task("images", () => {
	const imagemin = require("gulp-imagemin");

	return gulp
		.src("../assets/img/**")
		.pipe(
			imagemin([
				imagemin.gifsicle({ interlaced: true }),
				imagemin.jpegtran({ progressive: true }),
				imagemin.optipng({ optimizationLevel: 5 }),
				imagemin.svgo({
					plugins: [{ removeViewBox: true }, { cleanupIDs: false }]
				})
			])
		)
		.pipe(gulp.dest("../build/img"));
});
