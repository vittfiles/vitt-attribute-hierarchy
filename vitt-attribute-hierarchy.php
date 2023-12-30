<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Vitt Attribute Hierarchy for WooCommerce
 * Plugin URI:        https://github.com/vittfiles
 * Description:       You can define attributes and subattributes to simulate their behavior like categories and subcategories
 * Version:           1.0.2
 * Author:            Matias Martinez
 * Requires at least: 6.4
 * Tested up to: 6.4.2
 * WC requires at least: 7.0
 * WC tested up to: 8.4
 */
defined('ABSPATH') or die();

function activate_vittfiles_attribute_hierarchy(){
	add_option( "attribute_connection", "");
};

register_activation_hook( __FILE__, 'activate_vittfiles_attribute_hierarchy');

/* Declare HPOS compatibility */
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
	\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
	} );

include_once 'include/helper.php' ;
include_once 'include/responses.php';
include 'pages/menu-attribute-config.php';
include 'pages/sub-menu-attribute-config.php';

//add menu and config page to the admin page
function vittfiles_menu_attribute_config(){
    add_menu_page("Vittfiles Attributes configuration", "Vitt Attributes", "manage_categories",
	"vittfiles-menu-attribute-config.php", "vittfiles_attributes_config_page","dashicons-editor-ol");
	add_submenu_page( null, 'Attribute conn',
	'Sub Level Menu', 'manage_options', 'vittfiles-sub-menu-attribute-config.php', 'vittfiles_attributes_config_sub_page' );
	add_submenu_page( null, 'Delete connection',
	'Del attr conn', 'manage_options', 'vittfiles-delete-attribute-connection-page.php', 'vittfiles_delete_attribute_connection_page' );
}

add_action("admin_menu","vittfiles_menu_attribute_config");

//update product
add_action( 'woocommerce_admin_process_product_object', 'vittfiles_save_my_custom_settings' );

function vittfiles_save_my_custom_settings( $product ){
	vittfiles_save_attributes_to_base_attribute($product);
}