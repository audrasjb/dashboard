<?php

/**
 * @link              http://whodunit.fr
 * @since             1.0
 * @package           Whodunit Dashboard
 *
 * @wordpress-plugin
 * Plugin Name:       Whodunit Dashboard
 * Plugin URI:        http://whodunit.fr
 * Description:       Whodunit Custom Dashboard for WordPress.
 * Version:           1.0
 * Author:            Whodunit
 * Author URI:        http://whodunit.fr
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       whodunit-dashboard
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * i18n (to come shortly)
 */
//load_plugin_textdomain( 'whodunit-dashboard', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 

/**
 * Admin
 */
if (is_admin()) {
	require_once plugin_dir_path( __FILE__ ) . 'admin/whodunit-dashboard-admin.php';
}