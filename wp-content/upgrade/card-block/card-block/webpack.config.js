var ExtractText = require('extract-text-webpack-plugin');

var extractEditorSCSS = new ExtractText({
    filename: './css/blocks.editor.build.css'
});

var extractBlockSCSS = new ExtractText({
    filename: './css/blocks.style.build.css'
});

var plugins = [extractEditorSCSS, extractBlockSCSS];

var scssConfig = {
    use: [
        {
            loader: 'css-loader'
        },
        {
            loader: 'sass-loader',
            options: {
                outputStyle: 'compressed'
            }
        }
    ]
};

module.exports = {
    mode: 'development',
    entry: './js/block.js',
    output: {
        path: __dirname,
        filename: 'js/block.build.js',
    },
    module: {
        rules: [
            {
                test: /.js$/,
                loader: 'babel-loader',
                exclude: /node_modules/,
            },
            {
                test: /editor\.scss$/,
                exclude: /node_modules/,
                use: extractEditorSCSS.extract(scssConfig)
            },
            {
                test: /style\.scss$/,
                exclude: /node_modules/,
                use: extractBlockSCSS.extract(scssConfig)
            }
        ],
    },
    plugins: plugins
};