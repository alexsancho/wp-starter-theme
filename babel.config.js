module.exports = (api) => {
	api.cache(false);

	return {
		presets: [
			['@babel/preset-env', { modules: false }],
			'@babel/preset-react',
		],
		plugins: [
			'@babel/plugin-transform-modules-commonjs',
			'@babel/plugin-proposal-class-properties',
			'@babel/plugin-transform-destructuring',
			'@babel/plugin-proposal-object-rest-spread',
		],
	};
};
