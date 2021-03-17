let webpack = require("webpack");

const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const TerserJSPlugin = require('terser-webpack-plugin');

const postcssFocusWithin = require("postcss-focus-within");

module.exports = {
    output: {
        pathinfo: false
    },
    optimization: {
        runtimeChunk: "single",
        usedExports: true,
        minimizer: [
            new TerserJSPlugin({
                parallel: true,
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
                        reduceIdents: true,
                        zindex: false
                    }]
                }
            })
        ]
    },
    module: {
        rules: [
            {
                test: /\.m?js/,
                resolve: {
                    fullySpecified: false
                }
            },
            {
                test: /\.jsx?$/,
                resolve: {
                    fullySpecified: false
                },
                exclude: /node_modules/,
                loader: "babel-loader",
                options: {
                    presets: ["@wordpress/default"],
                    plugins: [
                        ["@babel/plugin-proposal-class-properties", {loose: false}],
                        ["@babel/plugin-transform-react-jsx", {pragma: "wp.element.createElement"}]
                    ]
                }
            },
            {
                test: /\.jsx?$/,
                resolve: {
                    fullySpecified: false
                },
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
                    MiniCssExtractPlugin.loader,
                    "css-loader?sourceMap",
                    {
                        loader: "postcss-loader",
                        options: {
                            sourceMap: true,
                            postcssOptions: {
                                ident: "postcss",
                                plugins: () => [
                                    postcssFocusWithin()
                                ]
                            }
                        }
                    },
                    {
                        loader: "resolve-url-loader"
                    },
                    {
                        loader: "sass-loader",
                        options: {
                            sourceMap: true
                        }
                    }
                ]
            }
        ]
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: "[name].css",
            chunkFilename: "[id].css"
        })
    ],
    resolve: {
        extensions: [".ts", ".js", ".json"]
    },
    devServer: {
        hot: true,
        host: "0.0.0.0",
        port: 8000,
        contentBase: "./public",
        publicPath: "/",
        writeToDisk: true,
        proxy: {
            "/": {
                target: "http://0.0.0.0:8001",
                secure: false,
                changeOrigin: true,
                autoRewrites: true
            }
        }
    },
    performance: {
        hints: false
    },
    devtool: "eval-cheap-module-source-map",
    mode: process.env.NODE_ENV === "production" ? "production" : "development"
};

if (process.env.NODE_ENV === "development") {
    module.exports.plugins = (module.exports.plugins || []).concat([
        new webpack.HotModuleReplacementPlugin()
    ]);
}

if (process.env.NODE_ENV === "production") {
    module.exports.devtool = false;
    module.exports.plugins = (module.exports.plugins || []).concat([
        new webpack.DefinePlugin({
            "process.env": {
                NODE_ENV: '"production"'
            }
        }),
        new webpack.LoaderOptionsPlugin({
            minimize: true
        })
    ]);
}
