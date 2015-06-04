'use strict';
module.exports = function(grunt) {

	// load all tasks
	require('load-grunt-tasks')(grunt, {scope: 'devDependencies'});

    grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		watch: {
			files: ['scss/*.scss'],
			tasks: 'sass',
			options: {
				livereload: true,
			},
		},
		sass: {
			default: {
		  		options : {
			  		style : 'expanded'
			  	},
			  	files: {
					'css/style.css':'scss/style.scss',
				}
			}
		},
	    autoprefixer: {
            options: {
				browsers: ['> 1%', 'last 2 versions', 'Firefox ESR', 'Opera 12.1', 'ie 9']
			},
			single_file: {
				src: 'css/style.css',
				dest: 'css/style.css'
			}
		},
		cssmin: {
		    target: {
		        files: [{
		            expand: true,
		            cwd: 'css',
					src: ['*.css', '!*.min.css'],
					dest: 'css',
					ext: '.min.css'
		        }],
		        options: {
		        }
		    }
		},
		concat: {
		    build: {
		        src: [
		            'js/skip-link-focus-fix.js',
		            'js/jquery.fastclick.js',
		            'js/jquery.navigation.js',
		            'js/jquery.fitvids.js',
		            'js/theme.js'
		        ],
		        dest: 'js/luminate.min.js',
		    }
		},
		uglify: {
		    build: {
		        src: 'js/luminate.min.js',
		        dest: 'js/luminate.min.js'
		    }
		},
	    // https://www.npmjs.org/package/grunt-wp-i18n
	    makepot: {
	        target: {
	            options: {
	                domainPath: '/languages/',
	                potFilename: 'luminate.pot',
	                potHeaders: {
	                poedit: true, // Includes common Poedit headers.
                    'x-poedit-keywordslist': true // Include a list of all possible gettext functions.
                },
		        type: 'wp-theme',
		        updateTimestamp: false,
		        processPot: function( pot, options ) {
					pot.headers['report-msgid-bugs-to'] = 'https://devpress.com/';
		        	pot.headers['language'] = 'en_US';
		        	return pot;
					}
				}
			}
		},
	    replace: {
			styleVersion: {
				src: [
					'scss/style.scss',
					'style.css'
				],
				overwrite: true,
				replacements: [{
					from: /Version:.*$/m,
					to: 'Version: <%= pkg.version %>'
				}]
			},
			functionsVersion: {
				src: [
					'functions.php'
				],
				overwrite: true,
				replacements: [ {
					from: /^define\( 'CLASSIC_VERSION'.*$/m,
					to: 'define( \'CLASSIC_VERSION\', \'<%= pkg.version %>\' );'
				} ]
			},
		},
		cssjanus: {
			theme: {
				options: {
					swapLtrRtlInUrl: false
				},
				files: [
					{
						src: 'css/style.css',
						dest: 'css/style-rtl.css'
					},
					{
						src: 'css/style.min.css',
						dest: 'css/style-rtl.min.css'
					},
				]
			}
		}
	});

	grunt.registerTask( 'default', [
		'sass',
		'autoprefixer',
    ]);

    grunt.registerTask( 'release', [
    	'replace',
    	'sass',
    	'autoprefixer',
    	'cssmin',
    	'concat:build',
		'uglify:build',
		'makepot',
		'cssjanus'
	]);

};
