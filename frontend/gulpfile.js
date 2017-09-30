var gulp = require("gulp"),
    csslint = require("gulp-csslint"),
    minifier = require("gulp-minify-css"),
    concat = require("gulp-concat"),
    sourcemaps = require("gulp-sourcemaps"),
    notify = require("gulp-notify"),
    less = require("gulp-less"),
    jshint = require("gulp-jshint"),
    uglify = require("gulp-uglify"),
    jsStylish = require("jshint-stylish");

gulp.task("css", function () {
    gulp.src("./styles/less/*.less")
        .pipe(less())
        .pipe(csslint({'ids': false}))
        .pipe(sourcemaps.init())
        .pipe(minifier())
        .pipe(concat("style.min.css"))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest("../views/public/css/"))
        .pipe(notify({message: "css successfully built!"}));
});

gulp.task("js", function () {
    gulp.src(["./config/*.js", "./controllers/*.js", "./services/*.js"])
        .pipe(jshint())
        .pipe(jshint.reporter(jsStylish))
        .pipe(sourcemaps.init())
        .pipe(concat("script.min.js"))
        .pipe(uglify())
        .pipe(sourcemaps.write())
        .pipe(gulp.dest("../views/public/script"))
        .pipe(notify({message: "Javascript successfully built!"}));
});

gulp.task("default", function () {
    gulp.watch("./styles/less/*.less", ["css"]);
    gulp.watch(["./config/*.js", "./controllers/*.js", "./services/*.js"], ["js"]);
});

gulp.task("run", function(){
  gulp.start("css", "js");
});
