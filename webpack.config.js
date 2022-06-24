const MODE = "development";

// ファイル出力時の絶対パス指定に使用
const path = require('path');

const { VueLoaderPlugin } = require("vue-loader");
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
    entry: [
        path.join(__dirname, 'resources/js/app.js'),
        path.join(__dirname, 'resources/sass/app.scss'),
    ],
    // ファイルの出力設定
    output: {
        path: path.join(__dirname, 'public/dist'),
        filename: 'js/app.js'
    },
    mode: MODE,
    // ソースマップ有効
    devtool: 'source-map',
    module: {
        rules: [{
                test: /\.(sa|sc|c)ss$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    {
                        loader: 'css-loader',
                        options: {
                            url: false,
                            minimize: true,
                        }
                    },
                    {
                        loader: 'sass-loader'
                    }
                ]
            },
            {
                test: /\.vue$/,
                use: [{
                    loader: "vue-loader"
                }]
            },
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: [{
                    loader: "babel-loader",
                    options: {
                        presets: [
                            "@babel/preset-env"
                        ]
                    }
                }]
            }
        ]
    },
    resolve: {
        // Webpackで利用するときの設定
        alias: {
            vue$: "vue/dist/vue.esm.js"
        },
        extensions: ["*", ".js", ".vue", ".json"]
    },
    plugins: [
        // Vueを読み込めるようにするため
        new VueLoaderPlugin(),
        new MiniCssExtractPlugin({
            filename: './css/app.css',
            minimize: true,
        })
    ],
    optimization: {
        minimize: true,
    }
};