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
    autpprefixer = require('gulp-autoprefixer'),
    jshint = require('gulp-jshint'),
    uglify = require('gulp-uglify'),
    imagemin = require('gulp-imagemin'),
    gcache = require('gulp-caceh'),
    livereload = require('gulp-livereload');

gulp.task('style', function () {
    gulp.src('./src/less/*.less', function () {

    });
});

gulp.task('script', function () {

});

gulp.task('img', function () {

});

gulp.task('default', ['clean'], function () {
    gulp.run('style', 'script', 'img', 'watch');
});

gulp.task('watch', function () {
    gulp.watch('', function () {
        gulp.run();
    });
});

