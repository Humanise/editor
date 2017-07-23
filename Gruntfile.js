module.exports = function(grunt) {

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
      custom: {
        files: ['style/custom/scss/**/*.scss'],
        tasks: ['sass:custom'],
        options: {
          spawn: false,
        }
      },
      humanise: {
        files: ['style/humanise/scss/**/*.scss'],
        tasks: ['sass:humanise'],
        options: {
          spawn: false,
        }
      },
      karenslyst: {
        files: ['style/karenslyst/sass/**/*.scss'],
        tasks: ['compass:karenslyst'],
        options: {
          spawn: false,
        }
      },
      fynbogaarden: {
        files: ['style/fynbogaarden/sass/**/*.scss'],
        tasks: ['compass:fynbogaarden'],
        options: {
          spawn: false,
        }
      },
      lottemunk: {
        files: ['style/lottemunk/scss/**/*.scss'],
        tasks: ['compass:lottemunk'],
        options: {
          spawn: false,
        }
      },
      jonasmunk: {
        files: ['style/jonasmunk/scss/**/*.scss'],
        tasks: ['sass:jonasmunk'],
        options: {
          spawn: false,
        }
      },
      janemunk: {
        files: ['style/janemunk/scss/**/*.scss'],
        tasks: ['sass:janemunk'],
        options: {
          spawn: false,
        }
      }
    },
    compass: {
      karenslyst: {
        options: {
          sassDir: "style/karenslyst/sass",
          cssDir: "style/karenslyst/css",
          noLineComments: true,
        }
      },
      fynbogaarden: {
        options: {
          sassDir: "style/fynbogaarden/sass",
          cssDir: "style/fynbogaarden/css",
          noLineComments: true,
        }
      },
      lottemunk: {
        options: {
          sassDir: "style/lottemunk/scss",
          cssDir: "style/lottemunk/css",
          noLineComments: true,
        }
      }
    },
    sass : {
      custom: {
        options : {sourcemap:'none'},
        files: [{
          expand: true,
          cwd: 'style/custom/scss',
          src: ['*.scss'],
          dest: 'style/custom/css',
          ext: '.css'
        }]
      },
      humanise: {
        options : {sourcemap:'none'},
        files: [{
          expand: true,
          cwd: 'style/humanise/scss',
          src: ['*.scss'],
          dest: 'style/humanise/css',
          ext: '.css'
        }]
      },
      jonasmunk: {
        options : {sourcemap:'none'},
        files: [{
          expand: true,
          cwd: 'style/jonasmunk/scss',
          src: ['*.scss'],
          dest: 'style/jonasmunk/css',
          ext: '.css'
        }]
      },
      janemunk: {
        options : {sourcemap:'none'},
        files: [{
          expand: true,
          cwd: 'style/janemunk/scss',
          src: ['*.scss'],
          dest: 'style/janemunk/css',
          ext: '.css'
        }]
      }
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

  var designs = [{name:'dalleruphallerne'}];

  designs.forEach(function(design) {

    config.watch[design.name] = {
      files: ['style/' + design.name + '/scss/**/*.scss'],
      tasks: ['sass:' + design.name],
      options: {
        spawn: false,
      }
    }
    config.sass[design.name] = {
      options : {sourcemap:'none'},
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
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-symlink');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-shell');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-compass');
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-jsdoc');

  grunt.registerTask('default', 'Standard tasks', ['sass','compass','watch']);

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