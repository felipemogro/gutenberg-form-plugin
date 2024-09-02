const path = require('path');

module.exports = {
    entry: './src/block.js',
    output: {
        path: path.resolve(__dirname, 'dist'),
        filename: 'block.bundle.js',
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env', '@wordpress/babel-preset-default'],
                    },
                },
            },
        ],
    },
    externals: {
        react: 'React',
        'react-dom': 'ReactDOM',
        '@wordpress/blocks': 'wp.blocks',
        '@wordpress/element': 'wp.element',
        '@wordpress/block-editor': 'wp.blockEditor',
        '@wordpress/components': 'wp.components',
        '@wordpress/i18n': 'wp.i18n',
    },
    mode: 'development',
};
