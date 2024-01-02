<?php
defined('ABSPATH') or die();
/**
 * The custom sitting for woocommerce products
 *
 * @link       https://github.com/vittfiles
 * @since      1.2.0
 *
 * @package    Vitt_Attribute_Hierarchy_For_Woocommerce
 * @subpackage Vitt_Attribute_Hierarchy_For_Woocommerce/admin
 */
class Vittfiles_Ahfw_Custom_Sittings_Products{
    public $action;
    //create a new connection in menu-attribte-config.php
    public function __construct(){
        $this->action = 'woocommerce_admin_process_product_object';

        $this->load_dependencies();
    }

    private function load_dependencies(){
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-vitt-attribute-hierarchy-helper.php';
    }

    public function save_my_custom_settings( $product ){
        Vitt_Attribute_Hierarchy_Helper::save_attributes_to_base_attribute($product);
    }

}