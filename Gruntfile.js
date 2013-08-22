module.exports = function(grunt) {
    
    var php_config = require('./php.config.js'),
        js_config = require('./build.config.js'),
        task_config = require('./task.config.js');

    grunt.initConfig(grunt.util._.extend(php_config, js_config, task_config));
    
    grunt.registerTask('default', ['build', 'compile']);
    
}