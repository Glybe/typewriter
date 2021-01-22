let webpack = require("webpack");

const BrowserSyncPlugin = require("browser-sync-webpack-plugin");
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const TerserJSPlugin = require('terser-webpack-plugin');

const postcssFocusWithin = require("postcss-focus-within");

module.exports = {
    optimization: {
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
        }),

        new BrowserSyncPlugin({
            cwd: __dirname.replace("/dev/webpack", "/public"),
            excludedFileTypes: [],
            files: [
                {
                    match: [
                        "**/*.css",
                        "**/*.gif",
                        "**/*.jpg",
                        "**/*.jpeg",
                        "**/*.png",
                        "**/*.webp"
                    ],
                    fn: (event, file) => {
                        if (event !== "change") {
                            return;
                        }

                        require("browser-sync")
                            .get("bs-webpack-plugin")
                            .reload(file);
                    }
                },

                {
                    match: [
                        "**/*.json",
                        "**/*.php",
                        "**/*.twig"
                    ],
                    fn: (event) => {
                        if (event !== "change") {
                            return;
                        }

                        require("browser-sync")
                            .get("bs-webpack-plugin")
                            .reload();
                    }
                }
            ],
            injectChanges: true,
            injectNotification: true,
            logPrefix: "TypeWriter",
            host: "0.0.0.0",
            port: 8001,
            notify: true,
            open: false,
            proxy: "http://0.0.0.0:8000",
            reload: false,
            reloadDelay: 0,
            serveStatic: [
                {
                    route: "/app/uploads",
                    dir: "./public/app/uploads"
                }
            ],
            ui: false
        }, {
            injectCss: true,
            reload: false
        })
    ],
    resolve: {
        extensions: [".ts", ".js", ".json"]
    },
    devServer: {
        historyApiFallback: true,
        hot: true,
        noInfo: true,
        openPage: ""
    },
    performance: {
        hints: false
    },
    devtool: "source-map",
    mode: process.env.NODE_ENV === "production" ? "production" : "development"
};

if (process.env.NODE_ENV === "production") {
    module.exports.devtool = "source-map";
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
