const webpack = require('webpack')
const path = require('path');
const ManifestPlugin = require('webpack-manifest-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const TerserPlugin = require('terser-webpack-plugin');

module.exports = {
	entry: {
		'front': ["./www/src/front/js/app.js", "./www/src/front/styles/style.scss"],
		'admin': ["./www/src/admin/js/app_admin.js", "./www/src/admin/styles/style.scss"],
	},
	mode: (process.env.NODE_ENV === 'production') ? 'production' : 'development',
	resolve: {
		extensions: ['*', '.js', '.jsx']
	},
	output: {
		filename: '[contenthash].[name].js',
		path: path.join(__dirname, 'www', 'dist'),
	},
	optimization: {
		minimizer: [
			new TerserPlugin({
				terserOptions: {
					parallel: true,
					output: {
						comments: false,
					},
				},
			}),
		]
	},
	plugins: [
		new CleanWebpackPlugin({
			verbose: false,
			dry: false
		}),
		new MiniCssExtractPlugin({
			filename: "[contenthash].[name].css"
		}),
		new ManifestPlugin({
			fileName: '../dist/manifest.json',
			writeToFileEmit: true
		}),
		new webpack.ProvidePlugin({
			'window.jQuery': 'jquery',
			jQuery: 'jquery',
			$: 'jquery'
		})
	],
	module: {
		rules: [
			{
				test: /\.scss$/,
				use: [
					{
						loader: MiniCssExtractPlugin.loader
					},
					"css-loader",
					{
						loader: 'resolve-url-loader',
					},
					{
						loader: 'sass-loader',
					}
				]
			},
			{
				test: /\.css$/,
				use: [
					{
						loader: MiniCssExtractPlugin.loader
					},
					"css-loader",
					{
						loader: 'resolve-url-loader',
					},
					{
						loader: 'sass-loader',
					}
				]
			},
			{
				test: /\.(gif|png|jpe?g|svg|webp)$/i,
				use: [
					{
						loader: 'file-loader',
						options: {
							name: 'images/[name].[ext]'
						}
					},

					{
						loader: 'image-webpack-loader',
						options: {
							disable: true,
							gifsicle: {
								enabled: false
							}
						}
					}
				]
			},
			{
				test: /\.(woff|woff2|eot|ttf|otf)$/,
				use: {
					loader: 'file-loader',
					options: {
						name: 'fonts/[name].[hash].[ext]'
					}
				}
			}
		]
	},
	devServer: {
		publicPath: '/dist',
	},
};
