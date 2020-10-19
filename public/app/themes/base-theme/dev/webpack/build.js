const {readFileSync} = require("fs");
const {resolve} = require("path");
const {sync} = require("glob-all");
const {merge} = require("webpack-merge");

const webpack = require("webpack");
const purgeCSSPlugin = require("purgecss-webpack-plugin");

const base = require("../../../../../../dev/webpack/webpack-config");

module.exports = merge(base, {
    entry: {
        theme: "./resource/js/main.js",
        vendors: "./resource/js/vendors.js"
    },
    externals: {
        moment: "moment",
        jquery: "jQuery"
    },
    output: {
        filename: "[name].js",
        libraryTarget: "this",
        path: resolve(__dirname, "../../dist"),
        publicPath: "/app/themes/base-theme/dist/"
    },
    plugins: [
        new webpack.BannerPlugin({
            banner: readFileSync("dev/webpack/license-header.txt", "utf8").trim(),
            test: /\.(css|js)$/
        })
    ]
});

if (process.env.NODE_ENV === "production") {
    module.exports.plugins = (module.exports.plugins || []).concat([
        new purgeCSSPlugin({
            paths: sync([
                "./**/*.twig",
                "./**/*.html",
                "./**/*.php"
            ]),
            whitelistPatterns: [
                /is-contained/
            ],
            defaultExtractor: content => {
                const broadMatches = content.match(/[^<>"'`\s]*[^<>"'`\s:]/g) || []
                const innerMatches = content.match(/[^<>"'`\s.()]*[^<>"'`\s.():]/g) || []

                return broadMatches.concat(innerMatches)
            }
        })
    ]);
}
