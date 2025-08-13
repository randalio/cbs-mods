const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
  ...defaultConfig,
  entry: {
    // 'bubble-cluster/index': path.resolve(process.cwd(), 'src/blocks/bubble-cluster/index.js'),
    // 'bubble/index': path.resolve(process.cwd(), 'src/blocks/bubble/index.js'),
    // Add your main SCSS file
    'main': path.resolve(process.cwd(), 'src/main.scss'),
  },
  output: {
    ...defaultConfig.output,
    path: path.resolve(process.cwd(), 'build'),
    filename: '[name].js',
  },
  plugins: [
    ...defaultConfig.plugins.filter(
      plugin => plugin.constructor.name !== 'MiniCssExtractPlugin'
    ),
    new MiniCssExtractPlugin({
      filename: '[name].css',
    }),
  ],
};
// const defaultConfig = require('@wordpress/scripts/config/webpack.config');
// const path = require('path');
// const MiniCssExtractPlugin = require('mini-css-extract-plugin');

// module.exports = {
//   ...defaultConfig,
//   entry: {
//     // 'hp-tabbed-content/index': path.resolve(process.cwd(), 'src/hp-tabbed-content/index.js'),
//     'main': path.resolve(process.cwd(), 'src/main.scss'), 
//     'admin': path.resolve(process.cwd(), 'src/admin.scss'), 
//   },
//   output: {
//     ...defaultConfig.output,
//     path: path.resolve(process.cwd(), 'build'),
//     filename: '[name].js',
//   },
//   // Use a simpler approach to customize plugins
//   plugins: [
//     ...defaultConfig.plugins.filter(
//       plugin => plugin.constructor.name !== 'MiniCssExtractPlugin'
//     ),
//     new MiniCssExtractPlugin({
//       filename: '[name].css',
//     }),
//   ],
// };