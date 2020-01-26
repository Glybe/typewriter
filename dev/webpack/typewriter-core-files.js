const merge = require("webpack-merge");
const path = require("path");

const base = require("./webpack-config");

module.exports = merge(base, {
	entry: {
		admin: "./resource/admin/js/index.js",
		front: "./resource/front/js/index.js"
	},
	externals: {
		moment: "moment",
		jquery: "jQuery"
	},
	output: {
		path: path.resolve(__dirname, "../../public/tw/dist"),
		publicPath: "/public/tw/dist/",
		filename: "[name].js"
	}
});
