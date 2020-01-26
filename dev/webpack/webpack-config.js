let fs = require("fs");
let webpack = require("webpack");

const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const TerserJSPlugin = require('terser-webpack-plugin');

const postcssFocusWithin = require("postcss-focus-within");

module.exports = {
	optimization: {
		minimizer: [
			new TerserJSPlugin({
				cache: true,
				parallel: true,
				sourceMap: true,
				terserOptions: {
					compress: {
						drop_console: true,
						keep_fargs: false,
						toplevel: true,
						unsafe: true,
						unsafe_proto: true,
						unsafe_undefined: true
					}
				}
			}),
			new OptimizeCSSAssetsPlugin({
				canPrint: false,
				cssProcessor: require("cssnano"),
				cssProcessorPluginOptions: {
					preset: ["advanced", {
						autoprefixer: {
							add: true
						},
						discardComments: {
							removeAll: true
						},
						mergeIdents: true,
						reduceIdents: true
					}]
				}
			})
		]
	},
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /node_modules/,
				loader: "babel-loader",
				options: {
					presets: [["@babel/preset-env", {targets: "defaults"}]],
					plugins: [["@babel/plugin-proposal-class-properties", {loose: false}]]
				}
			},
			{
				test: /\.js$/,
				exclude: /node_modules/,
				use: [
					"uglify-template-string-loader"
				]
			},
			{
				test: /\.(png|jpg|gif|svg|eot|woff|woff2|ttf|otf)$/,
				loader: "file-loader",
				options: {
					name: "[name].[ext]",
					publicPath: "./"
				}
			},
			{
				test: /\.(sa|sc|c)ss$/,
				use: [
					{
						loader: MiniCssExtractPlugin.loader,
						options: {
							sourceMap: true
						}
					},
					'css-loader?sourceMap',
					{
						loader: "postcss-loader",
						options: {
							ident: "postcss",
							plugins: () => [
								postcssFocusWithin()
							]
						}
					},
					'postcss-loader?sourceMap',
					'sass-loader?sourceMap'
				]
			}
		]
	},
	plugins: [
		new MiniCssExtractPlugin({
			filename: "[name].css",
			canPrint: false,
			chunkFilename: "[id].css",
			publicPath: "./"
		})
	],
	resolve: {
		extensions: [".ts", ".js", ".json"]
	},
	devServer: {
		historyApiFallback: true,
		noInfo: true
	},
	performance: {
		hints: false
	},
	devtool: "source-map",
	mode: process.env.NODE_ENV === "production" ? "production" : "development"
};

if (process.env.NODE_ENV === "production")
{
	module.exports.devtool = "source-map";
	module.exports.plugins = (module.exports.plugins || []).concat([
		new webpack.DefinePlugin({
			"process.env": {
				NODE_ENV: '"production"'
			}
		}),
		new webpack.LoaderOptionsPlugin({
			minimize: true
		}),
		new webpack.BannerPlugin({
			banner: fs.readFileSync("dev/webpack/license-header.txt", "utf8").trim(),
			test: /\.(css|js)$/
		})
	]);
}