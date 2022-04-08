import gulp from 'gulp';
import gulpLoadPlugins from 'gulp-load-plugins';

var execSync = require('child_process').execSync;
const routes = execSync('php artisan route:list').toString();

const plugins = gulpLoadPlugins();

const basepaths = {
  build: 'build/docs',
  src:   'docs',
};

gulp.task('task:move-assets', () =>
  gulp.src([`${basepaths.src}/src/assets/**/*`])
      .pipe(plugins.plumber())
      .pipe(gulp.dest(`${basepaths.build}`))
);

gulp.task('task:aglio', () =>
  gulp.src(`${basepaths.src}/index.md`)
      .pipe(plugins.replace('{{ROUTES_LIST}}', routes))
      .pipe(plugins.aglio({
        themeTemplate: `${basepaths.src}/src/template.jade`,
        themeStyle:    `${basepaths.src}/src/style.less`,
      }))
      .pipe(plugins.rename('index.html'))
      .pipe(plugins.replace('{{API_URL}}', 'https://api2.fleet.scorpiontrack.com'))
      .pipe(gulp.dest(`${basepaths.build}`))
);

gulp.task('default', ['task:move-assets', 'task:aglio']);

gulp.task('watch', () => {
  gulp.watch([`${basepaths.src}/src/assets`], ['task:move-assets']);
  gulp.watch([`${basepaths.src}/**/*.md`, `${basepaths.src}/src/*`], ['task:aglio']);
});
