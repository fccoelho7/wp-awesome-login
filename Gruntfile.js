module.exports = function(grunt) {
	var config = {
		package : grunt.file.readJSON( 'package.json' ),		

  		concat : {
		    options : {
				separator : ';'
		    },
		    admin : {
				src : [
					'<%= package.webroot %>/admin/dispatcher.js',
					'<%= package.webroot %>/admin/component-form.js',
					'<%= package.webroot %>/admin/component-upload.js',
					'<%= package.webroot %>/admin/app.js'
				],
				dest : '<%= package.webroot %>/admin/built.js'
		    },
  		},

  		jshint: {
			options : {
				jshintrc : true
			},
			files : {
				src : ['<%= package.webroot %>/login/app.js']
			},
			beforeconcat : '<%= concat.admin.src %>'
  		},

  		uglify : {
			admin : {
				files : {
					'<%= package.webroot %>/admin.built.js' : '<%= concat.admin.dest %>'
				}
			},
			login : {
				files : {
					'<%= package.webroot %>/login.built.js' : '<%= package.webroot %>/login/app.js'
				}
			},
    	},

    	sass: {
		    all: {
				options: {
					noCache: true,
					style: 'compressed'
				},
		    	files: {
		    		'login.style.css' : '<%= package.stylesheetroot %>/login/style.scss',
		    		'admin.style.css' : '<%= package.stylesheetroot %>/admin/style.scss'
		    	}
		    },
		},

		watch: {
		    css : {
		      files : ['assets/stylesheets/**/*.scss'],
		      tasks : ['sass']
		    },
		    admin : {
		    	files : '<%= concat.admin.src %>',
		    	tasks : ['jshint', 'concat', 'uglify:admin']
		    },
		    login : {
		    	files : '<%= package.webroot %>/login/app.js',
		    	tasks : ['jshint', 'uglify:login']
		    }
  		},
	};

	grunt.initConfig( config );

	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );
	grunt.loadNpmTasks( 'grunt-contrib-concat' );
	grunt.loadNpmTasks( 'grunt-contrib-jshint' );
	grunt.loadNpmTasks( 'grunt-contrib-sass' );

	grunt.registerTask( 'js', ['jshint', 'uglify'] );
	grunt.registerTask( 'deploy', ['js', 'sass'] );
};