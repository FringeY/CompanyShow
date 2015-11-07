/**
 * Created by yaorui on 15/11/5.
 */
var gulp = require('gulp'),
    clean = require('gulp-clean'),
    less = require('gulp-less'),
    rename = require('gulp-rename'),
    gutil = require('gulp-util'),
    concat = require('gulp-concat'),
    notify = require('gulp-notify'),
    plumber = require('gulp-plumber'),
    mincss = require('gulp-minify-css'),
    less = require('gulp-less'),
    autoprefixer = require('gulp-autoprefixer'),
    jshint = require('gulp-jshint'),
    uglify = require('gulp-uglify'),
    imagemin = require('gulp-imagemin'),
    browserSync = require('browser-sync');

var url = {
    dev: {
        less: 'src/less/',
        css: 'src/css/',
        img: 'src/images/',
        js: 'src/js/'
    },
    build: {
        css: 'Public/css',
        img: 'Public/img',
        js: 'Public/js'
    },
    watch: {
        less: 'src/less/*.less',
        css: 'src/css/*.css',
        img: 'src/img/**/*.*',
        js: 'src/js/*.js'
    }
};

gulp.task('less', function () {
    gulp.src(url.dev.less + '*.less')
        .pipe(plumber())
        .pipe(less())
        .pipe(autoprefixer())
        .pipe(mincss())
        .pipe(gulp.dest(url.build.css))
        .pipe(notify({message: 'less task complete'}))
        .pipe(livereload());
});

gulp.task('script', function () {
    gulp.src(url.dev.js + '*.js')
        .pipe(plumber())
        .pipe(jshint())
        .pipe(jshint.reporter('default'))
        .pipe(uglify())
        .pipe(gulp.dest(url.build.js))
        .pipe(notify({message: 'script task complete'}));
});

gulp.task('img', function () {
    gulp.src(url.dev.img + '**/*.*')
        .pipe(imagemin())
        .pipe(gulp.dest(url.build.img))
        .pipe(notify({message: 'img task complete'}));
});

gulp.task('default', ['clean', 'browser-sync'], function () {
    gulp.run(['less', 'script', 'img', 'watch']);
});

gulp.task('clean', function () {
   gulp.src([url.build.css, url.build.js, url.build.img])
       .pipe(clean())
       .pipe(notify({message: 'clean task complete'}));
});

gulp.task('browser-sync', function() {
    browserSync({
        files: "**",
        server: {
            baseDir: "./"
        },
        proxy: "localhost:2333"
    });
});

gulp.task('watch', function () {
    livereload.listen();
    gulp.watch(url.watch.less, ['less']);
    gulp.watch(url.watch.img, ['img']);
    gulp.watch(url.watch.js, ['script']);
});

