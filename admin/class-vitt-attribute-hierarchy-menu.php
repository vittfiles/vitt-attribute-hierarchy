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
class Vitt_Attribute_Hierarchy_Menu {

    
	private $plubin_name;

    private $page_title;
    private $menu_title;
    private $capability;
    private $menu_slug;
    private $icon;

	public function __construct( $plugin_name ) {

		$this->plugin_name = $plugin_name;
        
        $this->page_title = 'Vittfiles Attributes configuration';
        $this->menu_title = 'Vitt Attributes';
        $this->capability = 'manage_categories';
        $this->menu_slug = 'vittfiles-menu-attribute-config.php';
        $this->icon = 'dashicons-editor-ol';

        $this->load_dependencies();
    }

    private function load_dependencies(){
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-vitt-attribute-hierarchy-helper.php';

    }

    public function add_menu(){
        add_menu_page($this->page_title, $this->menu_title,$this->capability ,
		$this->menu_slug, array( $this ,"display_menu"),$this->icon);
    }

    public function display_menu(){
        $list_connections = Vitt_Attribute_Hierarchy_Helper::get_attribute_connection_list();
        
        ?>
        <div class="wrap">
            <h1>Generate connections between attributes</h1>
            <div id="col-container">
                <div id="col-right">
                    <div class="col-wrap">
                        <table class="widefat attributes-table wp-list-table ui-sortable" style="width:100%">
                            <thead>
                                <tr>
                                    <th scope="col">Attribute</th>
                                    <th scope="col">Sub-attribute</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php echo $this->create_rows($list_connections); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="col-left">
                    <div class="col-wrap">
                        <div class="form-wrap">
                            <h2>Add new hierarchy</h2>
                            <p>Attribute hierarchies work by simulating behavior like categories and their sub-categories.
                            </p>
                            <form method="post" action="<?= admin_url( 'admin-post.php' ); ?>" >
                                <?= wp_nonce_field('create_attribute_connection_name','create_attribute_connection_nonce'); ?>
                                <div class="form-field">
                                    <label for="attribute_name">Attribute slug</label>
                                    <input name="attribute_name" id="attribute_name" type="text" value="">
                                    <p class="description">Slug of the "father" attribute.</p>
                                </div>

                                <div class="form-field">
                                    <label for="sub_attribute_name">Sub-attribute slug</label>
                                    <input name="sub_attribute_name" id="sub_attribute_name" type="text" value="" maxlength="28">
                                    <p class="description">Slug of the "child" attribute.
                                    </p>
                                </div>
                                <input type="hidden" name="action" value="create_attribute_connection">
                                <p class="submit">
                                    <button type="submit" name="add_new_attribute" id="submit"
                                        class="button button-primary" value="Add connection">Add connection</button>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $faltante = '<script type="text/javascript">
            jQuery( "a.delete" ).on( "click", function() {
                if ( window.confirm( "¿Seguro de que querés eliminar este atributo?" ) ) {
                    return true;
                }
                return false;
            });
        </script>';
    }

    private function create_rows($list){
        $text = '';
        $dark = true;
        foreach( $list as $li){
            $info = explode(',',$li);
            $slug = $info[0];
            $sub_slug = $info[1];

            $alternate = '';
            if($dark){
                $alternate = "alternate";
            }

            $href = get_admin_url().'admin.php?page=vittfiles-sub-menu-attribute-config.php&slug='.$slug.'&sub-slug='.$sub_slug;
            $delete_href = get_admin_url().'admin.php?page=vittfiles-delete-attribute-connection-page.php&slug='.$slug.'&sub-slug='.$sub_slug;
            
            $text .= '<tr class="'.$alternate.'">
                <td>'.$slug.'
                    <div class="row-actions">
                        <span class="delete">
                            <a class="delete" href="'.$delete_href.'">Eliminar</a>
                        </span>
                    </div>
                </td>
                <td>'.$sub_slug.'</td>
                <td class="attribute-terms">
                    <a href="'.$href.'" class="configure-terms">Configure terms</a>
                </td>
            </tr>';
            $dark = !$dark;
        }
        return $text;
    }

    /**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.2.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/vitt-ahfw-menu.js', array( 'jquery' ), '1.2.0', false );

	}

    public function get_menu_slug(){
        return $this->menu_slug;
    }
}