<?php
defined('ABSPATH') or die();
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/vittfiles
 * @since      1.2.0
 *
 * @package    Vitt_Attribute_Hierarchy_For_Woocommerce
 * @subpackage Vitt_Attribute_Hierarchy_For_Woocommerce/admin
 */
class Vittfiles_Ahfw_Menu_Responses{
    public $action;
    //create a new connection in menu-attribte-config.php
    public function __construct(){
        $this->action = 'admin_post_create_attribute_connection';
    }

    public function create_attribute_connection() {
        if(!wp_verify_nonce($_POST['create_attribute_connection_nonce'], 'create_attribute_connection_name')){
            wp_redirect( get_admin_url()."admin.php?page=vittfiles-menu-attribute-config.php"."?sent=".false);
            return;
        }

        $slug = trim(sanitize_text_field($_POST['attribute_name']));
        $sub_slug = trim(sanitize_text_field($_POST['sub_attribute_name']));
        $result = get_option( "attribute_connection") . $slug . "," . $sub_slug . "|";
        $ok = update_option( "attribute_connection", $result);

        wp_redirect(get_admin_url()."admin.php?page=vittfiles-menu-attribute-config.php"."&sent=".$ok); //asumiendo que existe esta url
    }
}