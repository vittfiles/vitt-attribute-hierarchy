<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Vitt Attribute Hierarchy for WooCommerce
 * Plugin URI:        https://github.com/vittfiles
 * Description:       You can define attributes and subattributes to simulate their behavior like categories and subcategories
 * Version:           1.0.1
 * Author:            Matias Martinez
 */

function activate_vittfiles_attribute_hierarchy(){
	add_option( "attribute_connection", "");
};
register_activation_hook( __FILE__, 'activate_vittfiles_attribute_hierarchy');



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