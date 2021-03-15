const {readFileSync} = require("fs");
const {resolve} = require("path");
const {merge} = require("webpack-merge");

const webpack = require("webpack");

const base = require("./webpack-config");

module.exports = merge(base, {
    optimization: {
        runtimeChunk: false
    },
	entry: {
		admin: "./resource/admin/js/index.js",
		front: "./resource/front/js/index.js"
	},
	externals: {
		"@wordpress/blocks": ["wp", "blocks"],
		"@wordpress/block-editor": ["wp", "blockEditor"],
		"@wordpress/components": ["wp", "components"],
		"@wordpress/compose": ["wp", "compose"],
		"@wordpress/data": ["wp", "data"],
		"@wordpress/date": ["wp", "date"],
		"@wordpress/editor": ["wp", "editor"],
		"@wordpress/edit-post": ["wp", "editPost"],
		"@wordpress/element": ["wp", "element"],
		"@wordpress/hooks": ["wp", "hooks"],
		"@wordpress/html-entities": ["wp", "htmlEntities"],
		"@wordpress/i18n": ["wp", "i18n"],
		"@wordpress/plugins": ["wp", "plugins"],
		"moment": "moment",
		"jquery": "jQuery",
		"react": "React",
		"react-dom": "ReactDOM"
	},
    externalsType: "global",
	output: {
		filename: "[name].js",
		libraryTarget: "this",
		path: resolve(__dirname, "../../public/tw/dist"),
		publicPath: "/public/tw/dist/"
	},
	plugins: [
		new webpack.BannerPlugin(readFileSync("dev/webpack/license-header.txt", "utf8").trim())
	]
});
