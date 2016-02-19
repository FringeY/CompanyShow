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
    browserSync = require('browser-sync').create(),
    reload = browserSync.reload;

var url = {
    dev: {
        less: 'src/less/',
        css: 'src/css/',
        font: 'src/font/',
        img: 'src/img/',
        js: 'src/js/'
    },
    lib: {
        css: 'lib/css/',
        js: 'lib/js/',
        img: 'lib/img/'
    },
    build: {
        css: 'Public/css/',
        font: 'Public/font/',
        img: 'Public/img/',
        js: 'Public/js/'
    },
    watch: {
        less: 'src/less/*.less',
        css: 'src/css/*.css',
        font: 'src/font/*.*',
        img: 'src/img/*.*',
        js: 'src/js/*.js',
        libjs: 'lib/js/*.js'
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
        .pipe(reload({stream: true}));
});

gulp.task('font', function () {
    gulp.src(url.dev.font + '*.*')
        .pipe(gulp.dest(url.build.font))
        .pipe(notify({message: 'font task complete'}))
        .pipe(reload({stream: true}));
});

gulp.task('lib-js', function () {
   gulp.src(url.lib.js + '*.*')
       .pipe(gulp.dest(url.build.js))
       .pipe(notify({message: 'js-lib task complete'}))
       .pipe(reload({stream: true}));
});

gulp.task('lib-img', function () {
    gulp.src(url.lib.img + '*.*')
        .pipe(gulp.dest(url.build.img))
        .pipe(notify({message: 'img-lib task complete'}))
        .pipe(reload({stream: true}));
});

gulp.task('lib-css', function () {
    gulp.src(url.lib.css + '*.*')
        .pipe(gulp.dest(url.build.css))
        .pipe(notify({message: 'css-lib task complete'}))
        .pipe(reload({stream: true}));
});

gulp.task('script', function () {
    gulp.src(url.dev.js + '*.js')
        .pipe(plumber())
        .pipe(jshint())
        .pipe(jshint.reporter('default'))
        //.pipe(uglify())
        .pipe(gulp.dest(url.build.js))
        .pipe(notify({message: 'script task complete'}))
        .pipe(reload({stream: true}));
});

gulp.task('img', function () {
    gulp.src(url.dev.img + '**/*.*')
        .pipe(imagemin())
        .pipe(gulp.dest(url.build.img))
        .pipe(notify({message: 'img task complete'}))
        .pipe(reload({stream: true}));
});

gulp.task('default', ['clean'], function () {
    gulp.run(['less', 'lib-js', 'lib-img', 'lib-css', 'script', 'img', 'font', 'watch', 'browser-sync']);
});

gulp.task('clean', function () {
   gulp.src([url.build.css + '*.*', url.build.js + '*.*', url.build.img + '*.*', url.build.font + '*.*'], {read: false})
       .pipe(clean())
       .pipe(notify({message: 'clean task complete'}));
});

gulp.task('browser-sync', function() {
    browserSync.init({
        files: "**",
        server: {
            baseDir: "./"
        }
    });
});

gulp.task('watch', function () {
    gulp.watch(url.watch.less, ['less']);
    gulp.watch(url.watch.img, ['img']);
    gulp.watch(url.watch.font, ['font']);
    gulp.watch(url.watch.js, ['script']);
    gulp.watch(url.watch.libjs, ['lib-js']);
    gulp.watch(url.watch.libimg, ['lib-img']);
    gulp.watch(url.watch.libcss, ['lib-css']);
    gulp.watch("**/*.html").on('change', reload);
});

