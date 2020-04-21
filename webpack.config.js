const path = require('path');
const webpack = require('webpack');
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

function resolve(dir) {
  return path.join(
    __dirname,
    '/resources/js',
    dir
  );
}

const rawArgv = process.argv.slice(2);
const args = rawArgv.join(' ');
const report = rawArgv.includes('--report');
let plugins = [];
if (report) {
  plugins.push(new BundleAnalyzerPlugin({
    openAnalyzer: true,
  }));
}
module.exports = {
  resolve: {
    extensions: ['.js', '.vue', '.json'],
    alias: {
      vue$: 'vue/dist/vue.esm.js',
      '@': path.join(__dirname, '/resources/js'),
    },
  },
  module: {
    rules: [
      {
        test: /\.svg$/,
        loader: 'svg-sprite-loader',
        include: [resolve('icons')],
        options: {
          symbolId: 'icon-[name]',
        },
      },
    ],
  },
  optimization: {
    splitChunks: {
      chunks: 'all',
      cacheGroups: {
        elementUI: {
          name: 'js/chunk-elementUI', // split elementUI into a single package
          priority: 100, // the weight needs to be larger than libs and app or it will be packaged into libs or app
          test: /[\\/]node_modules[\\/]_?element-ui(.*)/, // in order to adapt to cnpm
        },
        echarts: {
          name: 'js/chunk-echarts', // split elementUI into a single package
          priority: 90, // the weight needs to be larger than libs and app or it will be packaged into libs or app
          test: /[\\/]node_modules[\\/]_?echarts(.*)/, // in order to adapt to cnpm
        },
      },
    },
  },
  plugins: plugins,
};
