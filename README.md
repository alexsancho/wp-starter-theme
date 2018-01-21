# WordPress Starter Theme

This repository contains the starter theme for projects using WordPress.

## Installation

Install the theme manually by cloning the repository under your WordPress themes directory and then removing the git tracking:

```
git clone git@github.com:alexsancho/wp-starter-theme.git your-theme-name
rm -rf your-theme-name/.git
```

## Setup

First you need to install required npm packages to start developing. In your theme root run:

```
yarn install
```

Replace the `THEMENAME` string with your theme name within all project files with a case ***sensitive*** search and replace. This is used for namespacing the theme function files.

## Translations and textdomain

Translations and the theme textdomain are loaded from under the `/lang` directory. Replace the `themename` string with your theme textdomain within all project files with a case ***sensitive*** search and replace. Then rename the `.pot` file under the `/lang` directory with the new theme textdomain.

## Assets and Webpack

Assets are compiled with [Webpack](https://webpack.github.io/docs/what-is-webpack.html). Use [yarn](https://yarnpkg.com/) to install packages and require JavaScript files in [assets/scripts/main.js](https://github.com/alexsancho/wp-starter-theme/blob/master/assets/scripts/main.js) and import Sass files in [assets/styles/main.scss](https://github.com/alexsancho/wp-starter-theme/blob/master/assets/styles/main.scss). The directory under which the assets are build is `assets/dist`.

***You should only enqueue files that are under `assets/dist`!***

The URL to be used with the assets is defined in `/lib/setup.php` with the `ASSET_URI` constant. It points to `https://{site_domain}/{path_to_themes_folder}/themename/dist` by default. To use another source for assets this value can be changed by defining the constant for example in the `wp-config.php` file with a custom URI.

## Asset versioning

The style and script files are automatically enqueued with the current theme version. To bust browser cache on asset updates change the theme version in the `style.css` file comments.

### Development

Run webpack in the theme root in your local environment.

Run with the npm script:

```
npm run dev/watch
```

or run with Webpack:

```
webpack (--watch)
```

These commands will compile *unminified* versions of your assets.

### Production

Build _minified_ versions for production with the npm script:

```
npm run build
```

or with Webpack:

```
webpack -p
```

These commands will compile *minified* versions of your assets.

## JavaScript development guide

The theme's Webpack config uses [Babel](https://babeljs.io/) to compile [ES6](https://en.wikipedia.org/wiki/ECMAScript#6th_Edition_-_ECMAScript_2015) into [ES5](https://en.wikipedia.org/wiki/ECMAScript#5th_Edition). Thus we use [classes](http://es6-features.org/#ClassDefinition) and other cool features introduced in ES6. See the full list of ES6 features [here](http://es6-features.org/).

### Enable Babel compiling

If you add *npm packages* using ES6 features, remember to include them for the Babel loader in the `webpack.config.js` file!

```
// List paths to packages using ES6 to enable Babel compiling.
include: [
    path.resolve(__dirname, 'assets/scripts'),
    path.resolve(__dirname, 'node_modules/foundation-sites')
],
```

*UglifyJS will most likely produce an error when trying to minify an ES6 script that is not included for the Babel loader while running `webpack -p`!*
