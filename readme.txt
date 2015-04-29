=== WP Local Toolbox ===

Contributors: joeguilmette,jb510, davenaylor
Tags: admin,administration,responsive,dashboard,notification,simple, develop, developer, developing, development
Tested up to: 4.2.1
Stable tag: 1.2
License: GPL v2+
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A simple plugin to help manage development over local, staging and production servers.

== Description ==

Through defined constants, you can disable plugins, disable the  loading of external files, set search engine visibility, display or hide the admin bar, display the server name and change the color of the admin bar, or literally anything else you can think of. All without touching the database, so you can push and pull without worrying.

= Constants =

* **WP_ENV**: The name of your server environment. It will be displayed in the admin bar at browser widths greater than 1030px. If left undefined, the plugin will make no changes to the admin bar. 

	If not defined as `PRODUCTION` or `LIVE`, the plugin will enable 'Discourage search engines from indexing this site' to prevent your development and staging servers from being indexed. This option is not stored in the database, so your production server will still look to the actual setting on the Reading page.

* **WP_COLOR**: Determines the color of the admin bar. You can set this to any CSS color. If left undefined, will use the following defaults: 
	
	* PRODUCTION / LIVE: red
	* STAGING / TESTING: orange
	* LOCAL / DEVELOPMENT: green

* **WP_DISABLED_PLUGINS**: An array of plugins to disable. This does not store any data in the database, so plugins that are manually deactivated or activated will stay so when undefined in this constant.

= Example defines =

`
define('WP_COLOR', 'purple');

// show the admin bar even when logged out
define('WP_ADMINBAR', 'always');

// deactivate a set of plugins
define('WP_DISABLED_PLUGINS', serialize(
	array(
		'w3-total-cache/w3-total-cache.php',
		'updraftplus/updraftplus.php',
		'nginx-helper/nginx-helper.php,
		'wpremote/plugin.php',
		'wordpress-https/wordpress-https.php',
	)
));

`

= Modification =

You can add code that will be executed depending on server name by modifying the following in wp-local-toolbox.php.

I'd love a pull request if you come up with something useful.

`
if (strtoupper(WP_ENV) != 'LIVE' && strtoupper(WP_ENV) != 'PRODUCTION') {
	// Everything except PRODUCTION/LIVE SERVER

	// Hide from robots
	add_filter( 'pre_option_blog_public', '__return_zero' );

} else {
	// PRODUCTION/LIVE SERVER

}
`

= Credit =

* Plugin disabling from [Mark Jaquith](https://twitter.com/markjaquith): https://gist.github.com/markjaquith/1044546

* Using this fork from [Andrey Savchenko](https://twitter.com/rarst): https://gist.github.com/Rarst/4402927

* Airplane Mode from [Andrew Norcross](https://twitter.com/norcross): https://github.com/norcross/airplane-mode

* Always showing the admin bar from [Jeff Star](https://twitter.com/perishable): http://digwp.com/2011/04/admin-bar-tricks/

* A healthy refactoring from [Jon Brown](https://twitter.com/jb510) of [9seeds](http://9seeds.com/)

== Installation ==
After installation, you must define constants in the wp-config.php or other configuration file.
