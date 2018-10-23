module.exports = function(grunt) {

  const sass = require('node-sass');

  var clients = {};

  (function() {
    var dev = grunt.file.readJSON('Config/dev.json');
    clients = dev.clients;
  })();

  // Project configuration.
  var config = {
    pkg: grunt.file.readJSON('package.json'),
    jshint: {
      all: ['Grunfile.js', 'drupal-common/src/*']
    },
    watch: {
    },
    sass : {
    },
    shell: {
      transfer : {
        command : function(client) {
          if (!clients[client]) {
            grunt.log.error('Client not found'); return;
          }
          return 'Config/scripts/transfer.sh '
            + clients[client].production.database + ' '
            + clients[client].production.folder + ' '
            + clients[client].local.database + ' '
            + clients[client].local.data;
        }
      },
      transfer_staging : {
        command : function(client) {
          if (!clients[client]) {
            grunt.log.error('Client not found'); return;
          }
          return 'Config/scripts/transfer.sh '
            + clients[client].staging.database + ' '
            + clients[client].staging.folder + ' '
            + clients[client].local.database + ' '
            + clients[client].local.data;
        }
      },
      'stage' : {
        command : function(client) {
          if (clients[client] && clients[client].staging) {
            return 'Config/scripts/deploy.sh ' + clients[client].staging.folder;
          } else {
            console.error('Client not found: ' + client)
          }
          return '';
        }
      },
      'deploy' : {
        command : function(client) {
          if (clients[client] && clients[client].production) {
            return 'Config/scripts/deploy.sh ' + clients[client].production.folder;
          }
          return '';
        }
      },
      format : {
        command : 'php php-cs-fixer-v2.phar fix --config Editor/Info/CodeStyle.php'
      }
    }
  };

  var designs = [
    {name: 'dalleruphallerne'},
    {name: 'karenslyst'},
    {name: 'fynbogaarden'},
    {name: 'lottemunk'},
    {name: 'custom'},
    {name: 'humanise'},
    {name: 'jonasmunk'},
    {name: 'janemunk'},
    {name: 'psykologiskklinik'}
  ];

  designs.forEach(function(design) {

    config.watch[design.name] = {
      files: ['style/' + design.name + '/scss/**/*.scss'],
      tasks: ['sass:' + design.name],
      options: {
        spawn: false,
      }
    }
    config.sass[design.name] = {
      options: {
        implementation: sass,
        sourceMap: false,
        outputStyle: 'nested'
      },
      files: [{
        expand: true,
        cwd: 'style/' + design.name + '/scss',
        src: ['*.scss'],
        dest: 'style/' + design.name + '/css',
        ext: '.css'
      }]
    }
  })

  grunt.initConfig(config);

  // Load plugins.
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-shell');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-sass');

  grunt.registerTask('default', 'Standard tasks', ['sass','watch']);

  grunt.registerTask('format', 'Format code', ['shell:format']);

  grunt.registerTask('stage', 'Stage a client', function(client) {
    grunt.task.run('shell:stage:' + client);
  });

  grunt.registerTask('deploy', 'Deploy a client', function(client) {
    grunt.task.run('shell:deploy:' + client);
  });

  grunt.registerTask('put', 'Deploy a client', function(client) {
    grunt.task.run('shell:deploy:' + client);
  });

  grunt.registerTask('get', 'Transfer from production to local', function(client) {
    grunt.task.run('shell:transfer:'+client);
  });

  grunt.registerTask('get-staging', 'Transfer from production to local', function(client) {
    grunt.task.run('shell:transfer_staging:'+client);
  });

  grunt.registerTask('get-test', 'Transfer from production to local', function(client) {
    grunt.task.run('shell:transfer_staging:'+client);
  });

  grunt.registerTask('get-stage', 'Transfer from production to local', function(client) {
    grunt.task.run('shell:transfer_staging:'+client);
  });

};