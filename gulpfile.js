const browsersync = require('browser-sync').create();
const cached = require('gulp-cached');
const cssnano = require('gulp-cssnano');
const del = require('del');
const fileinclude = require('gulp-file-include');
const gulp = require('gulp');
const gulpif = require('gulp-if');
const npmdist = require('gulp-npm-dist');
const replace = require('gulp-replace');
const uglify = require('gulp-uglify');
const useref = require('gulp-useref-plus');
const rename = require('gulp-rename');
const sass = require('gulp-sass');
const autoprefixer = require("gulp-autoprefixer");
const sourcemaps = require("gulp-sourcemaps");    
const cleanCSS = require('gulp-clean-css');
const rtlcss = require('gulp-rtlcss');

const paths = {
  base:   {
    base:         {
      dir:    './'
    },
    node:         {
      dir:    './node_modules'
    },
    packageLock:  {
      files:  './package-lock.json'
    }
  },
  public:   {
    base:   {
      dir:    './public',
      files:  './public/**/*'
    },
    libs:   {
      dir:    './public/libs'
    },
    css:    {
      dir:    './public/css',
    },
    js:    {
      dir:    './public/js',
      files:  './public/js/pages',
    },
    images:{
      dir:    './public/images',
      files:  './public/images/**/*',
    }
  },
  src:    {
    base:   {
      dir:    './assets',
      files:  './assets/**/*'
    },
    css:    {
      dir:    './assets/css',
      files:  './assets/css/**/*'
    },
    img:    {
      dir:    './assets/images',
      files:  './assets/images/**/*',
    },
    js:     {
      dir:    './assets/js',
      pages:  './assets/js/pages',
      files:  './assets/js/pages/*.js',
      main:   './assets/js/*.js',
    },
    scss:   {
      dir:    './assets/scss',
      files:  './assets/scss/**/*',
      main:   './assets/scss/*.scss'
    }
  }
};

gulp.task('browsersyncReload', function(callback) {
  browsersync.reload();
  callback();
});

gulp.task('watch', function() {
  gulp.watch(paths.src.scss.files, gulp.series('scss'));
  gulp.watch([paths.src.js.dir], gulp.series('js'));
  gulp.watch([paths.src.js.pages], gulp.series('jsPages'));
});

gulp.task('js', function() {
  return gulp
    .src(paths.src.js.main)
    .pipe(uglify())
    .pipe(gulp.dest(paths.public.js.dir));
});

gulp.task('jsPages', function() {
  return gulp
    .src(paths.src.js.files)
    .pipe(uglify())
    .pipe(gulp.dest(paths.public.js.files));
});

gulp.task('scss', function () {
  // generate ltr  
  gulp
    .src(paths.src.scss.main)
    .pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    .pipe(
      autoprefixer()
    )
    .pipe(gulp.dest(paths.public.css.dir))
    .pipe(cleanCSS())
    .pipe(
      rename({
        // 
        suffix: ".min"
      })
    )
    .pipe(sourcemaps.write("./")) 
    .pipe(gulp.dest(paths.public.css.dir));

  // generate rtl
  return gulp
    .src(paths.src.scss.main)
    .pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    .pipe(
      autoprefixer()
    )
    .pipe(rtlcss())
    .pipe(gulp.dest(paths.public.css.dir))
    // .pipe(cleanCSS())
    .pipe(
      rename({
        // 
        suffix: "-rtl.min"
      })
    )
    .pipe(sourcemaps.write("./")) 
    .pipe(gulp.dest(paths.public.css.dir));
});

gulp.task('clean:packageLock', function(callback) {
  del.sync(paths.base.packageLock.files);
  callback();
});

gulp.task('clean:public', function(callback) {
  del.sync(paths.public.base.dir);
  callback();
});

gulp.task('copy:all', function() {
  return gulp
    .src([
      paths.src.base.files,
      '!' + paths.src.scss.dir, '!' + paths.src.scss.files,
      '!' + paths.src.js.dir, '!' + paths.src.js.files, '!' + paths.src.js.main,
    ])
    .pipe(gulp.dest(paths.public.base.dir));
});

gulp.task('copy:libs', function() {
  return gulp
    .src(npmdist(), { base: paths.base.node.dir })
    .pipe(rename(function(path) {
        path.dirname = path.dirname.replace(/\/public/, '').replace(/\\public/, '');
    }))
    .pipe(gulp.dest(paths.public.libs.dir));
});

gulp.task('copy', function () {
    gulp.src('./assets/index.php')
        .pipe(gulp.dest('./public/'));
});

gulp.task('build', gulp.series(gulp.parallel('clean:packageLock', 'clean:public', 'copy:all', 'copy:libs'), 'scss'));

gulp.task('default', gulp.series(gulp.parallel('clean:packageLock', 'clean:public', 'copy:all', 'copy:libs', 'scss', 'js', 'jsPages'), gulp.parallel('watch')));