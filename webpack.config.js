/**
 * @see https://developer.wordpress.org/block-editor/tutorials/javascript/js-build-setup/
 */

const defaultConfig = require('@wordpress/scripts/config/webpack.config.js');
const path = require('path');
const del = require('del');
const pack = require('./package.json');
const isProduction = process.env.NODE_ENV === 'production';

const IgnoreEmitPlugin = require('ignore-emit-webpack-plugin');
const TerserJSPlugin = require('terser-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const ManifestPlugin = require('webpack-assets-manifest');

/**
 * Given a string, returns a new string with dash separators converted to
 * camel-case equivalent. This is not as aggressive as `_.camelCase`, which
 * which would also upper-case letters following numbers.
 *
 * @param {string} string Input dash-delimited string.
 *
 * @return {string} Camel-cased string.
 */
const camelCaseDash = (string) =>
	string.replace(/-([a-z])/g, (match, letter) => letter.toUpperCase());

const outputPath = path.resolve(process.cwd(), 'dist');

/**
 * Remove project files here when webpack is loaded. Be sure it is not async.
 */
del.sync([path.resolve(outputPath, '**/*')]);

/**
 * Define externals to load components through the wp global.
 */
const externals = [
	'api-fetch',
	'block-editor',
	'blocks',
	'components',
	'compose',
	'data',
	'date',
	'htmlEntities',
	'hooks',
	'edit-post',
	'element',
	'editor',
	'i18n',
	'plugins',
	'viewport',
	'ajax',
	'codeEditor',
	'rich-text',
].reduce(
	(externals, name) => ({
		...externals,
		[`@wordpress/${name}`]: `wp.${camelCaseDash(name)}`,
	}),
	{
		wp: 'wp',
		jquery: 'jQuery',
		lodash: 'lodash', // WP loads lodash already.
	}
);

const rules = [
	...defaultConfig.module.rules,
	{
		test: /\.(sc|sa|c)ss$/,
		use: [
			{ loader: MiniCssExtractPlugin.loader },
			{ loader: 'css-loader' },
			{
				loader: 'postcss-loader',
				options: {
					ident: 'postcss',
					plugins: (loader) => [
						require('postcss-import')({
							root: loader.resourcePath,
						}),
						require('postcss-preset-env')({
							browsers: pack.browserslist,
						}),
						require('postcss-reporter')({
							clearReportedMessages: true,
						}),
					],
				},
			},
			{ loader: 'sass-loader' },
		],
	},
	{
		test: /\.(png|jpg|gif)$/,
		use: [
			{
				loader: 'file-loader',
				options: {
					outputPath: 'images/', // Dump images in dist/images.
					publicPath: 'images', // URLs point to dist/images.
					regExp: /\/([^\/]+)\/([^\/]+)\/images\/(.+)\.(.*)?$/, // Gather strings for the output filename.
					name: '[1]-[2]-[3].[hash:hex:7].[ext]', // Filename e.g. block-accordion-basic.1b659fc.png
				},
			},
		],
	},
	{
		test: /\.(woff(2)?|ttf|eot)(\?v=\d+\.\d+\.\d+)?$/,
		use: [
			{
				loader: 'file-loader',
				options: {
					name: '[name].[ext]',
					outputPath: 'fonts/',
					publicPath: 'fonts', // URLs point to dist/fonts.
				},
			},
		],
	},
];

const output = {
	filename: '[name].[hash].js',
	publicPath: 'dist/',
};

module.exports = [
	{
		...defaultConfig,
		entry: {
			'admin.min': path.resolve(
				process.cwd(),
				'assets/src/back',
				'main.js'
			),
			'bundle.min': path.resolve(
				process.cwd(),
				'assets/src/front',
				'main.js'
			),
		},
		externals,
		optimization: {
			minimize: isProduction,
			minimizer: [
				new TerserJSPlugin({}),
				new OptimizeCSSAssetsPlugin({}),
			],
			noEmitOnErrors: isProduction,
		},
		stats: {
			all: false,
			assets: true,
			colors: true,
			errors: true,
			performance: true,
			timings: true,
			warnings: true,
		},
		module: {
			...defaultConfig.module,
			rules,
		},
		plugins: [
			...defaultConfig.plugins,

			new MiniCssExtractPlugin({
				filename: '[name].[hash].css',
			}),

			new IgnoreEmitPlugin(/\.asset.php$/),

			new ManifestPlugin({
				publicPath: true,
			}),
		],
		output,
	},
];
