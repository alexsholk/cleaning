var gulp = require('gulp'),
    uglify = require('gulp-uglify'),
    concat = require('gulp-concat'),
    minifyCSS = require('gulp-minify-css'),
    sass = require('gulp-sass'),
    sourcemaps = require('gulp-sourcemaps');

/**
 * Build vendors
 */
gulp.task('vendor', function () {
    // images
    gulp.src([
        'node_modules/fancybox/dist/img/**/*'
    ])
        .pipe(gulp.dest('public/build/img'));

    // css
    gulp.src([
        'node_modules/animate.css/animate.min.css',
        'node_modules/jquery-datetimepicker/build/jquery.datetimepicker.min.css',
        'node_modules/fancybox/dist/jquery.fancybox.min.css',
        'node_modules/icheck/skins/minimal/minimal.css'
    ])
        .pipe(minifyCSS())
        .pipe(concat('vendor.min.css'))
        .pipe(gulp.dest('public/build/css'));

    // js
    gulp.src([
        'node_modules/pace-js/pace.min.js',
        'node_modules/jquery/dist/jquery.min.js',
        'node_modules/jquery-datetimepicker/build/jquery.datetimepicker.full.min.js',
        'node_modules/jQuery-viewport-checker/dist/jquery.viewportchecker.min.js',
        'node_modules/fancybox/dist/jquery.fancybox.min.js',
        'node_modules/icheck/icheck.min.js',
        'node_modules/jquery-form/dist/jquery.form.min.js',
        'node_modules/jquery-mask-plugin/dist/jquery.mask.min.js',
        'node_modules/bootstrap/js/tooltip.js',
        'node_modules/devbridge-autocomplete/dist/jquery.autocomplete.min.js'
    ])
        .pipe(uglify())
        .pipe(concat('vendor.min.js'))
        .pipe(gulp.dest('public/build/js'));
});

/**
 * Build app
 */
gulp.task('app', function () {
    // images
    gulp.src([
        'assets/images/**/*'
    ])
        .pipe(gulp.dest('public/build/images'));

    // css
    gulp.src([
        'assets/scss/app.scss'
    ])
        .pipe(sourcemaps.init())
        .pipe(sass({outputStyle: 'compressed'}))
        .pipe(concat('app.min.css'))
        .pipe(sourcemaps.write('../maps'))
        .pipe(gulp.dest('public/build/css'));

    // js
    gulp.src([
        'assets/js/app.js'
    ])
        .pipe(uglify())
        .pipe(concat('app.min.js'))
        .pipe(gulp.dest('public/build/js'));
});

gulp.task('default', function() {
    gulp.run('vendor', 'app');
});