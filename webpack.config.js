const path = require("path");
const webpack = require("webpack");
const CopyWebPackPlugin = require('copy-webpack-plugin');
const ExtractTextPlugin =require('extract-text-webpack-plugin');
const ManifestPlugin = require('webpack-manifest-plugin');
const WebpackChunkHash = require('webpack-chunk-hash');
const CleanWebPackPlugin = require('clean-webpack-plugin');

const isProduction = process.env.NODE_ENV === 'production';
const useSourceMap = !isProduction;
const useVersioning = true;

const styleLoader = {
    loader: 'style-loader',
    options: {
        sourceMap: useSourceMap
    }
};

const cssLoader = {
    loader: 'css-loader',
    options: {
        sourceMap: useSourceMap,
        minimize: isProduction
    }
};

const sassLoader = {
    loader: 'sass-loader',
    options: {
        sourceMap: true
    }
};

const resolveUrlLoader = {
    loader: 'resolve-url-loader',
    options: {
        sourceMap: useSourceMap
    }
};

const webpackConfig = {
    entry: {
        layout: "./frontend/js/layout.js",
        lesson_list: "./frontend/js/lesson_list.js",
        lesson_show: "./frontend/js/lesson_show.js"
    },
    output: {
        path: path.resolve(__dirname, "web", "assets"),
        filename: useVersioning ? "[name].[chunkhash:6].js" : "[name].js",
        publicPath: "/assets/"
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: "babel-loader",
                    options: {
                        cacheDirectory: true
                    }
                }
            },
            {
                test: /\.css$/,
                use: ExtractTextPlugin.extract({
                    use: [
                        cssLoader
                    ],
                    fallback: styleLoader
                })
            },
            {
                test: /\.scss$/,
                use: ExtractTextPlugin.extract({
                    use: [
                        cssLoader,
                        resolveUrlLoader,
                        sassLoader
                    ],
                    fallback: styleLoader
                })
            },
            {
                test: /\.(png|jpg|jpeg|gif|ico|svg)$/,
                use: [
                    {
                        loader: "file-loader",
                        options: {
                            name: '[name]-[hash:6].[ext]'
                        }
                    }
                ]
            },
            {
                test: /\.(woff|woff2|eot|ttf|otf)$/,
                use: [
                    {
                        loader: "file-loader",
                        options: {
                            name: '[name]-[hash:6].[ext]'
                        }
                    }
                ]
            }
        ]
    },
    plugins: [
        new webpack.ProvidePlugin({
            jQuery: "jquery",
            $: "jquery"
        }),
        new CopyWebPackPlugin([
            {
                from: './frontend/static',
                to: 'static'
            }
        ]),
        new webpack.optimize.CommonsChunkPlugin({
            name: [
                'layout',
                'manifest'
            ],
            minChunks: Infinity
        }),
        new ExtractTextPlugin(
            useVersioning ? '[name].[contenthash:6].css' : '[name].css'
        ),
        new ManifestPlugin({
            writeToFileEmit: true,
            basePath: 'assets/'
        }),
        new WebpackChunkHash(),
        new CleanWebPackPlugin('web/assets/*.*')
    ],
    devtool: useSourceMap ? 'inline-source-map' : false
};

if (isProduction) {
    webpackConfig.plugins.push(
        new webpack.optimize.UglifyJsPlugin()
    );

    webpackConfig.plugins.push(
        new webpack.LoaderOptionsPlugin({
            minimize: true,
            debug: false
        })
    );

    webpackConfig.plugins.push(
        new webpack.DefinePlugin({
            'process.evn.NODE_ENV': JSON.stringify('production')
        })
    )
}

module.exports = webpackConfig;
