<?php
defined('ABSPATH') or die();
/**
 * The sub-menu to drag-n-drop elements.
 *
 * @link       https://github.com/vittfiles
 * @since      1.2.0
 *
 * @package    Vitt_Attribute_Hierarchy_For_Woocommerce
 * @subpackage Vitt_Attribute_Hierarchy_For_Woocommerce/admin
 */
class Vitt_Attribute_Hierarchy_Sub_Menu{
    /**     
	 * The version of this plugin.
	 *
	 * @since    1.2.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	private $plubin_name;

    private $parent_slug;
    private $page_title;
    private $menu_title;
    private $capability;
    private $menu_slug;
    private $icon;

	public function __construct( $plugin_name ) {

		$this->plugin_name = $plugin_name;

        $this->parent_slug = null;
        $this->page_title = 'Attribute conn';
        $this->menu_title = 'Del attr conn';
        $this->capability = 'manage_options';
        $this->menu_slug = 'vittfiles-sub-menu-attribute-config.php';
    }

    public function add_sub_menu(){
        add_submenu_page($this->parent_slug,$this->page_title, $this->menu_title,$this->capability ,
		$this->menu_slug, array( $this ,"display_menu"));
    }

    public function display_menu(){
        //parent slug
        $slug = trim(sanitize_text_field($_GET['slug']));
        //child slug
	    $sub_slug = trim(sanitize_text_field($_GET['sub-slug']));

        //get terms from slug
        $terms = $this->get_terms_from_slug($slug);
        //get terms from sub-slug
        $sub_terms = $this->get_terms_from_slug($sub_slug);

        //get sub-terms sorted below their parent terms
        $res_terms = $this->get_sorted_terms($terms,$sub_terms);
        //get terms without parent
        $lonely_terms = $this->get_lonely_terms($sub_terms);

        ?>
            <div style="position: relative;">
                <h1>Configure terms</h1>
                <div id="loader" class="none" >
                <p>loading data, this can take a long time</p>
                <p>please wait!!!</p>
                <img src="<?= plugin_dir_url(__FILE__); ?>/img/loader.gif"/>
            </div>
            <div class="col-wrap">
                <a href="<?= get_admin_url(); ?>admin.php?page=vittfiles-menu-attribute-config.php">&larr; Back to config page</a>
                <div class="tablenav top">
                    <button id="update" class="button action">Update</button>
                    <br class="clear">
                </div>
                <h2 class="screen-reader-text">Lista de etiquetas</h2>
                <table class="wp-list-table widefat fixed striped table-view-list tags ui-sortable sortable-list">
                    <thead>
                        <tr>
                            <th scope="col" id="posts" class="manage-column column-posts num sortable desc"><span>Hierarchy</span><span
                                        class="sorting-indicator"></span></th>
                            <th scope="col" id="wvs-meta-preview" class="manage-column column-wvs-meta-preview"></th>
                            <th scope="col" id="name" class="manage-column column-name column-primary sortable desc"><span>Name</span><span
                                        class="sorting-indicator"></span></th>
                            <th scope="col" id="description" class="manage-column column-description sortable desc"><span>Description</span><span
                                        class="sorting-indicator"></span></th>
                            <th scope="col" id="slug" class="manage-column column-slug sortable desc"><span>Slug</span><span
                                        class="sorting-indicator"></span></th>
                            <th scope="col" id="posts" class="manage-column column-posts num sortable desc"><span>Count</span><span
                                        class="sorting-indicator"></span></th>
                            <td class="column-handle" style="display: table-cell;"></td>
                        </tr>
                        <tr  class=" alone">
                                <td class="slug column-slug" colspan="6">&nbsp;&nbsp; Without parent</td>
                            </tr>
                    </thead>
                    <tbody id="the-list" class="ui-sortable" data-wp-lists="list:tag">
                        <?= $this->print_lonely_terms($lonely_terms); ?>
                        <?= $this->print_terms($res_terms,$slug); ?>
                    </tbody>
                    <tfoot>
                        <tr>
                        <th scope="col" id="wvs-meta-preview" class="manage-column column-wvs-meta-preview"></th>
                        </tr>
                    </tfoot>
                </table>
                <input type="hidden" value="<?= wp_create_nonce( 'wp_rest' ); ?>" id="nonce-vittfiles-ahfw" >
                <input type="hidden" value="<?= rest_url( '/accion/update-attributes' ); ?>" id="url-vittfiles-ahfw" >
            </div>
        <?php
    }
    
    /* Get terms from database for the slug */
    private function get_terms_from_slug($slug){
        return get_terms(array(
            'taxonomy' => "pa_".$slug,
            'hide_empty' => false,
        ) );
    }

    /* Order the terms like this:
     * term parent 1
     * term child of parent 1
     * term child of parent 1
     * term parent 2
     * term child of parent 2
    */
    private function get_sorted_terms($terms,$sub_terms){
        $res_terms = array();
        foreach($terms as $term){
            $res_terms[] = $term;
            foreach($sub_terms as $sub_term){
                if($term->term_id == $sub_term->parent){
                    $res_terms[] = $sub_term;
                }
            }
        }
        return $res_terms;
    }

    /* Get sub-terms without parents */
    private function get_lonely_terms($sub_terms){
        $lonely_terms = array();
        foreach($sub_terms as $sub_term){
            if($sub_term->parent == 0){
                $lonely_terms[] = $sub_term;
            }
        }
        return $lonely_terms;
    }

    /* Pront sub-terms without parent */
    private function print_lonely_terms($lonely_terms){
        $sub_colors_result = "";
        /* if(isset($sub_colors[$term->name])){
            $sub_colors_result = $sub_colors[$term->name];
        } */
        $text = '';
        //add without parent
        foreach ( $lonely_terms as $term ) {
            $text .= '<tr draggable="true"  class=" ui-sortable-handle child-attr" data-index="'.$term->term_id.'" data-parent="'.$term->parent.'">
                <td class="slug column-slug">&nbsp;&nbsp;&nbsp;&nbsp; &#8627;</td>
                <td class="wvs-meta-preview column-wvs-meta-preview" data-colname="">
                    <div class="wvs-preview wvs-color-preview" style="background-color:'.$sub_colors_result.';"></div>
                </td>
                <td class="name column-name has-row-actions column-primary" data-colname="Nombre"><strong>'.$term->name.'</strong><br>
                    <div class="hidden" id="inline_24">
                        <div class="name">'.$term->name.'</div>
                        <div class="slug">'.$term->slug.'</div>
                        <div class="parent">'.$term->parent.'</div>
                    </div>
                </td>
                <td class="description column-description" data-colname="Description"><span
                        aria-hidden="true">—</span><span class="screen-reader-text">'.$term->description.'</span></td>
                <td class="slug column-slug" data-colname="Slug">'.$term->slug.'</td>
                <td class="posts column-posts" data-colname="quantity">'.$term->count.'</td>
                <td class="column-handle ui-sortable-handle" style="display: table-cell;"></td>
            </tr>';
        }
        return $text;
    }

    /* print terms */
    private function print_terms($res_terms,$slug){
        $text = "";
        foreach ( $res_terms as $term ) {
            $sub_colors_result = "";
            /* if(isset($sub_colors[$term->name])){
                $sub_colors_result = $sub_colors[$term->name];
            } */
            $colors_result = "";
           /*  if(isset($sub_colors[$term->name])){
                $colors_result = $colors[$term->name];
            } */

            $info = '<div class="parent">'.$term->parent.'</div>';
            $type = "&nbsp;&nbsp;&nbsp;&nbsp; &#8627;";
            $color = $sub_colors_result;
            if($term->taxonomy == "pa_".$slug){
                $type = "&nbsp;&nbsp; Base";
                $text .= '<tr draggable="true"  class="ui-sortable-handle base" data-index="'.$term->term_id.'">';
                $info = '<div class="id">'.$term->term_id.'</div>';
                $color = $colors_result;
            }else{
                $text .= '<tr draggable="true" class="ui-sortable-handle child-attr" data-index="'.$term->term_id.'" data-parent="'.$term->parent.'">';
            }

            $text .= '
                <td class="slug column-slug">'.$type.'</td>
                <td class="wvs-meta-preview column-wvs-meta-preview" data-colname="">
                    <div class="wvs-preview wvs-color-preview" style="background-color:'.$color.';"></div>
                </td>
                <td class="name column-name has-row-actions column-primary" data-colname="Nombre"><strong>'.$term->name.'</strong><br>
                    <div class="hidden" id="inline_24">
                        <div class="name">'.$term->name.'</div>
                        <div class="slug">'.$term->slug.'</div>
                        '.$info.'
                    </div>
                </td>
                <td class="description column-description" data-colname="Description"><span
                        aria-hidden="true">—</span><span class="screen-reader-text">'.$term->description.'</span></td>
                <td class="slug column-slug" data-colname="Slug">'.$term->slug.'</td>
                <td class="posts column-posts" data-colname="quantity">'.$term->count.'</td>
                <td class="column-handle ui-sortable-handle" style="display: table-cell;"></td>
            </tr>';
        }
        return $text;
    }
    /**
	 * Register the stylesheets for the sub menu admin area.
	 *
	 * @since    1.2.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/vitt-ahfw-sub-menu.css', array(), '1.2.0', 'all' );
	}

    /**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.2.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/vitt-ahfw-sub-menu.js', array( 'jquery' ), '1.2.0', false );

	}

    /* getter of menu_slug */
    public function get_menu_slug(){
        return $this->menu_slug;
    }
} 