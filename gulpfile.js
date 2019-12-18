/**
 * Gulp mise à jour pour Symfony
 * @type {Gulp}
 */


var gulp = require("gulp");
var path = require("path");
var sass = require("gulp-sass");
var autoprefixer = require("gulp-autoprefixer");
var sourcemaps = require("gulp-sourcemaps");
var open = require("gulp-open");
var ugligy = require("gulp-uglify");
var pipeline = require("readable-stream").pipeline;
var purgecss = require('gulp-purgecss');

var Paths = {
  HERE: "./",
  DIST: "dist/",
  CSS: "./public/assets/css/",
  SCSS_TOOLKIT_SOURCES: "./public/assets/scss/vroomiz.scss",
  SCSS: "./public/assets/scss/**/**",
  JS: "./public/assets/js/",
  JS_SCRIPT: "./public/assets/scripts/*.js"
};

function scss() {
  return gulp
    .src(Paths.SCSS_TOOLKIT_SOURCES)
    .pipe(sourcemaps.init())
    .pipe(sass().on("error", sass.logError))
    .pipe(autoprefixer())
    .pipe(sourcemaps.write(Paths.HERE))
    .pipe(gulp.dest(Paths.CSS));
}

function js() {
  return pipeline(gulp.src(Paths.JS_SCRIPT), ugligy(), gulp.dest(Paths.JS));
}

function watch() {
  gulp.watch(Paths.SCSS, scss);
  //gulp.watch(Paths.JS_SCRIPT, js);
}

function openPage() {
  gulp.src("index.html").pipe(open());
}

/*
  function purgecss() {
  console.log('CSS a été purgé !');
  return gulp.src('./public/assets/css/*.css')
    .pipe(purgecss({
      content: ['*.html']
    }))
    .pipe(gulp.dest('./'));
}

 */

var build = gulp.series(scss, gulp.parallel(watch));
// var openApp = gulp.parallel(openPage, watch);

exports.scss = scss;
exports.js = js;
exports.watch = watch;
exports.openPage = openPage;

//exports.purgecss = purgecss;

exports.default = build;