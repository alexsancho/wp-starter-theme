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

Translations and the theme textdomain are loaded from under the `/lang` directory. Replace the `textdomain` string with your theme textdomain within all project files with a case ***sensitive*** search and replace. Then rename the `.pot` file under the `/lang` directory with the new theme textdomain.

## Assets and Webpack

Assets are compiled with [Webpack](https://webpack.github.io/docs/what-is-webpack.html). Use [yarn](https://yarnpkg.com/) to install packages and require JavaScript files in [assets/src/front/main.js](https://github.com/alexsancho/wp-starter-theme/blob/master/assets/src/front/main.js) and import Sass files in [assets/src/front/scss/main.scss](https://github.com/alexsancho/wp-starter-theme/blob/master/assets/src/front/scss/main.scss). The directory under which the assets are build is `dist`.

***You should only enqueue files that are under `dist`!***

The URL to be used with the assets is defined in `/lib/setup.php` with the `ASSET_URI` constant. It points to `https://{site_domain}/{path_to_themes_folder}/themename/dist` by default. To use another source for assets this value can be changed by defining the constant for example in the `wp-config.php` file with a custom URI.

## Asset versioning

The style and script files are automatically enqueued with the current theme version. To bust browser cache on asset updates change the theme version in the `style.css` file comments.

### Development

Run webpack in the theme root in your local environment.

Run with the npm script:

```
npm run start
```

These commands will compile *unminified* versions of your assets.

### Production

Build _minified_ versions for production with the npm script:

```
npm run build
```

These commands will compile *minified* versions of your assets.

## JavaScript development guide

The theme's Webpack config uses [Babel](https://babeljs.io/) to compile [ES6](https://en.wikipedia.org/wiki/ECMAScript#6th_Edition_-_ECMAScript_2015) into [ES5](https://en.wikipedia.org/wiki/ECMAScript#5th_Edition). Thus we use [classes](http://es6-features.org/#ClassDefinition) and other cool features introduced in ES6. See the full list of ES6 features [here](http://es6-features.org/).

### Enable Babel compiling

If you add *npm packages* using ES6 features, remember to include them for the Babel loader in the `webpack.config.js` file!
