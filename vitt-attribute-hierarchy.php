<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/vittfiles
 * @since             1.0.0
 * @package           Vitt_Attribute_Hierarchy_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Vitt Attribute Hierarchy for WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/vitt-attribute-hierarchy-for-woocommerce
 * Description:       You can define attributes and subattributes to simulate their behavior like categories and subcategories
 * Version:           1.2.1
 * Author:            Vittfiles
 * Author URI:        https://github.com/vittfiles/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       vitt-attribute-hierarchy-for-woocommerce
 * Requires at least: 6.4
 * Tested up to: 6.4.2
 * WC requires at least: 7.0
 * WC tested up to: 8.4
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'VITT_ATTRIBUTE_HIERARCHY_FOR_WOOCOMMERCE_VERSION', '1.2.1' );


/* Declare HPOS compatibility */
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-vitt-attribute-hierarchy-for-woocommerce-activator.php
 */
function activate_vitt_attribute_hierarchy_for_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-vitt-attribute-hierarchy-for-woocommerce-activator.php';
	Vitt_Attribute_Hierarchy_For_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-vitt-attribute-hierarchy-for-woocommerce-deactivator.php
 */
function deactivate_vitt_attribute_hierarchy_for_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-vitt-attribute-hierarchy-for-woocommerce-deactivator.php';
	Vitt_Attribute_Hierarchy_For_Woocommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_vitt_attribute_hierarchy_for_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_vitt_attribute_hierarchy_for_woocommerce' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-vitt-attribute-hierarchy-for-woocommerce.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.2.0
 */
function run_vitt_attribute_hierarchy_for_woocommerce() {

	$plugin = new Vitt_Attribute_Hierarchy_For_Woocommerce();
	$plugin->run();

}
run_vitt_attribute_hierarchy_for_woocommerce();
