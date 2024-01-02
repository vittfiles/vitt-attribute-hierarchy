<?php
defined('ABSPATH') or die();
/**
 * Page to delete a connection between attributes and
 * then redirect to menu again
 *
 * @link       https://github.com/vittfiles
 * @since      1.2.0
 *
 * @package    Vitt_Attribute_Hierarchy_For_Woocommerce
 * @subpackage Vitt_Attribute_Hierarchy_For_Woocommerce/admin
 */
class Vittfiles_Ahfw_Delete_Connection_Page {

    private $parent_slug;
    private $page_title;
    private $menu_title;
    private $capability;
    private $menu_slug;
    private $icon;

    public function __construct(){
        $this->parent_slug = null;
        $this->page_title = 'Delete connection';
        $this->menu_title = 'Del attr conn';
        $this->capability = 'manage_options';
        $this->menu_slug = 'vittfiles-delete-attribute-connection-page.php';
    }

    public function add_sub_menu(){
        add_submenu_page($this->parent_slug,$this->page_title, $this->menu_title,$this->capability ,
		$this->menu_slug, array( $this ,"display_menu"));
    }

    public function display_menu(){
        $slug = trim(sanitize_text_field($_GET['slug']));
        $sub_slug = trim(sanitize_text_field($_GET['sub-slug']));
        $replace = $slug . "," . $sub_slug . "|";
        $result = str_replace($replace,"",get_option( "attribute_connection"));
        $ok = update_option( "attribute_connection", $result);
        wp_redirect(get_admin_url()."admin.php?page=vittfiles-menu-attribute-config.php"."&is-delete=".$ok);
    }
}