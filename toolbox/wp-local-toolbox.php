<?php

if (defined('WP_ENV') && WP_ENV) {
	/*
	You can edit this to do certain things depending on your how you've
	defined the WP_ENV constant. This can be very useful if
	you want to perform certain actions depending on which server you're
	using.

	If you come up with something cool I'd love a pull request!
	 */
	if (strtoupper(WP_ENV) != 'LIVE' && strtoupper(WP_ENV) != 'PRODUCTION') {
		/**
		 * Everything except PRODUCTION/LIVE Environment
		 *
		 * Hide from robots
		 */
		add_filter('pre_option_blog_public', '__return_zero');

	} else {
		/**
		 * PRODUCTION/LIVE Environment
		 */
	}

/**
 * =======================================
 * ===============Admin Bar===============
 * =======================================
 */
	function environment_notice() {
		$env_text = ucwords(WP_ENV);

		$admin_notice = array(
			'parent' => 'top-secondary', /** puts it on the right side. */
			'id' => 'environment-notice',
			'title' => '<span>' . $env_text . ' Server</span>',
		);
		global $wp_admin_bar;
		$wp_admin_bar->add_menu($admin_notice);
	}

	/**
	 * Style the admin bar
	 */
	function environment_notice_css() {

		if (defined('WPLT_COLOR') && WPLT_COLOR) {
			$env_color = strtolower(WPLT_COLOR);
		} else {
			$env = strtoupper(WP_ENV);

			if ($env == 'LIVE' or $env == 'PRODUCTION') {
				$env_color = 'red';

			} elseif ($env == 'STAGING' or $env == 'TESTING') {
				$env_color = '#FD9300';

			} elseif ($env == 'LOCAL' or $env == 'DEVELOPMENT') {
				$env_color = 'green';

			} else {
				$env_color = 'red';
			}

		}
		/**
		 * Some nice readable CSS so no one wonder's what's going on
		 * when inspecting the head. I think it's best to just jack
		 * these styles into the head and not bother loading another
		 * stylesheet.
		 */
		echo "
<!-- WPLT Admin Bar Notice -->
<style type='text/css'>
	#wp-admin-bar-environment-notice>div,
	#wpadminbar { background-color: $env_color!important }
	#wp-admin-bar-environment-notice { display: none }
	@media only screen and (min-width:1030px) {
	    #wp-admin-bar-environment-notice { display: block }
	    #wp-admin-bar-environment-notice>div>span {
	        color: #EEE!important;
	        font-size: 20px!important;
	    }
	}
	#adminbarsearch:before,
	.ab-icon:before,
	.ab-item:before { color: #EEE!important }
</style>";
	}

	/**
	 * Literally cannot even
	 */
	function goodbye_howdy($wp_admin_bar) {
		$my_account = $wp_admin_bar->get_node('my-account');
		$newtitle = str_replace('Howdy,', '', $my_account->title);
		$wp_admin_bar->add_node(array(
			'id' => 'my-account',
			'title' => $newtitle,
		));
	}

	function wplt_server_init() {

		/**
		 * Control the frontend admin bar
		 */
		if (defined('WPLT_ADMINBAR') && WPLT_ADMINBAR) {
			if (strtoupper(WPLT_ADMINBAR) == 'FALSE') {
				add_filter('show_admin_bar', '__return_false');
			} elseif (strtoupper(WPLT_ADMINBAR) == 'TRUE' or strtoupper(WPLT_ADMINBAR) == 'ALWAYS') {
				add_filter('show_admin_bar', '__return_true');
			}
			if (strtoupper(WPLT_ADMINBAR) == 'ALWAYS') {
				/**
				 * @author Jeff Star (https://twitter.com/perishable)
				 * @link http://digwp.com/2011/04/admin-bar-tricks/
				 */
				function always_show_adminbar($wp_admin_bar) {
					if (!is_user_logged_in()) {
						$wp_admin_bar->add_menu(array('title' => __('Log In'), 'href' => wp_login_url()));
					}
				}
				add_action('admin_bar_menu', 'always_show_adminbar');
				add_filter('show_admin_bar', '__return_true', 1000);
			}
		}

		if (is_admin_bar_showing()) {
			/**
			 * Add the environment to the admin panel
			 */
			add_action('admin_bar_menu', 'environment_notice');

			/**
			 * Add CSS to admin and wp head
			 */
			add_action('admin_head', 'environment_notice_css');
			add_action('wp_head', 'environment_notice_css');

			/**
			 * Cannot. Even.
			 */
			add_filter('admin_bar_menu', 'goodbye_howdy', 25);
		}
	}
	add_action('init', 'wplt_server_init');
}

/**
 * Disable plugins regardless of environment
 */
if (defined('WP_DISABLED_PLUGINS') && WP_DISABLED_PLUGINS) {

	/**
	 * Include
	 */
	require_once __DIR__ . '/inc/WP_Disable_Plugins.php';
	new WP_Disable_Plugins(unserialize(WP_DISABLED_PLUGINS));
}
