// Requires
var gulp = require('gulp');
var sass = require('gulp-sass');
var browserSync = require('browser-sync').create();
var useref = require('gulp-useref');
var uglify = require('gulp-uglify');
var gulpIf = require('gulp-if');
var cssnano = require('gulp-cssnano');
var imagemin = require('gulp-imagemin');
var cache = require('gulp-cache');
var del = require('del');
var runSequence = require('run-sequence');

// Main Functions

// watches files for any changes and then compiles sass and syncs the browser. The tasks name is default so you can just type 'gulp' into the terminal to start watching
gulp.task('default', function (callback) {
  runSequence(['sass','browserSync', 'watch'], callback)
});

// builds for production (cleans the dist folder, compiles sass, minifies files, and optimizes images). Just type 'gulp build' into the terminal to build for production.
gulp.task('build', function(callback) {
  runSequence('clean:dist', ['sass', 'useref', 'images'], callback)
});



// Helper Functions

// compiles sass files
gulp.task('sass', function(){
  return gulp.src('sass/**/*.scss')
    .pipe(sass())
    .pipe(gulp.dest('css'))
    .pipe(browserSync.reload({
      stream: true
    }))
});

// syncs the browser with the server
gulp.task('browserSync', function() {
  browserSync.init({
    proxy: 'localhost/MetricFitnessLabWebsite/',
    online: true
  });
});

// watches files for changes and syncs with the browser
gulp.task('watch', ['browserSync', 'sass'], function () {
  gulp.watch('sass/**/*.scss', ['sass']);
  // Reloads the browser whenever php or JS files change
  gulp.watch('templates/*.php', browserSync.reload);
  gulp.watch('js/**/*.js', browserSync.reload);
});

// minifies files
gulp.task('useref', function(){
  return gulp.src('templates/*.php')
    .pipe(useref())
    // Minifies only if it's a JavaScript file
    .pipe(gulpIf('*.js', uglify()))
    // Minifies only if it's a CSS file
    .pipe(gulpIf('*.css', cssnano()))
    .pipe(gulp.dest('dist'))
});

// optimizes images
gulp.task('images', function(){
  return gulp.src('img/**/*.+(png|jpg|jpeg|gif|svg)')
  // Caching images that ran through imagemin
  .pipe(cache(imagemin({
      interlaced: true
    })))
  .pipe(gulp.dest('dist/images'))
});

// deletes distribution file so you can build a new one
gulp.task('clean:dist', function() {
  return del.sync('dist');
});
