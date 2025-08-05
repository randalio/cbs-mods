const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
  ...defaultConfig,
  entry: {
    'hp-content-slider/index': path.resolve(process.cwd(), 'src/hp-content-slider/index.js'),
    'main': path.resolve(process.cwd(), 'src/main.scss'), 
    'admin': path.resolve(process.cwd(), 'src/admin.scss'), 
  },
  output: {
    ...defaultConfig.output,
    path: path.resolve(process.cwd(), 'build'),
    filename: '[name].js',
  },
  // Use a simpler approach to customize plugins
  plugins: [
    ...defaultConfig.plugins.filter(
      plugin => plugin.constructor.name !== 'MiniCssExtractPlugin'
    ),
    new MiniCssExtractPlugin({
      filename: '[name].css',
    }),
  ],
};