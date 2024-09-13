const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');

module.exports = {
    ...defaultConfig,
    ...{
        entry: {
            'js/editor': path.resolve(process.cwd(), 'src/js', 'editor.js')
        },
        plugins: [
            ...defaultConfig.plugins,

            new RemoveEmptyScriptsPlugin({
                stage: RemoveEmptyScriptsPlugin.STAGE_AFTER_PROCESS_PLUGINS
            }),
        ]
    }
};