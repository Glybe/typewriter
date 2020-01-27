const merge = require("webpack-merge");
const path = require("path");

const base = require("./webpack-config");

module.exports = merge(base, {
	entry: {
		admin: "./resource/admin/js/index.js",
		front: "./resource/front/js/index.js"
	},
	externals: {
		"@wordpress/blocks": {this: ["wp", "blocks"]},
		"@wordpress/block-editor": {this: ["wp", "blockEditor"]},
		"@wordpress/components": {this: ["wp", "components"]},
		"@wordpress/compose": {this: ["wp", "compose"]},
		"@wordpress/data": {this: ["wp", "data"]},
		"@wordpress/date": {this: ["wp", "date"]},
		"@wordpress/editor": {this: ["wp", "editor"]},
		"@wordpress/edit-post": {this: ["wp", "editPost"]},
		"@wordpress/element": {this: ["wp", "element"]},
		"@wordpress/hooks": {this: ["wp", "hooks"]},
		"@wordpress/i18n": {this: ["wp", "i18n"]},
		"@wordpress/plugins": {this: ["wp", "plugins"]},
		moment: "moment",
		jquery: "jQuery"
	},
	output: {
		filename: "[name].js",
		libraryTarget: "this",
		path: path.resolve(__dirname, "../../public/tw/dist"),
		publicPath: "/public/tw/dist/"
	}
});
