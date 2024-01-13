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
class Vittfiles_Ahfw_Sub_Menu_Responses{
    public $action;
    //create a new connection in menu-attribte-config.php
    public function __construct(){
        $this->action = 'rest_api_init';

        $this->load_dependencies();
    }

    private function load_dependencies(){
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-vitt-attribute-hierarchy-helper.php';

    }

    public function rest_api_update_attributes() {
        register_rest_route( 'accion/', 'update-attributes', array(
            'methods'  => 'POST',
            'callback' => array($this,'update_attributes'),
            'permission_callback' => function ( WP_REST_Request $request ) {
                return current_user_can( 'manage_categories' );
            },
        ) );
    }

    public function update_attributes( $request ) {
        /* return $request->get_params(); */
        $params = $request->get_params();
        $slug = $params["slug"];
        $sub_slug = $params["sub_slug"];
        $json = $params["result"];
        
        //old values to compare if they have changes
        $old_value_sub_terms = get_terms( array(
            'taxonomy' => 'pa_'.$sub_slug,
            'hide_empty' => false,
        ) );

        $prev_terms = array();
        foreach ( $old_value_sub_terms as $old_sub_term ){
            $prev_terms[$old_sub_term->term_id] = [$old_sub_term->parent,$old_sub_term->name];
        }
        
        //get the name of the sub-terms that had changes
        $sub_terms_chanched = array();
        foreach ( $json as $term ){
            if((int)$term[1] != $prev_terms[(int)$term[0]][0]){
                $sub_terms_chanched[] = $prev_terms[(int)$term[0]][1];
            }
        }

        //find all product that contain at least one of changed sub-terms
        $args = array(
            'post_type' => 'product',
            'tax_query' => array(
                array(
                    'taxonomy' => 'pa_'.$sub_slug,
                    'field' => 'name',
                    'terms' => $sub_terms_chanched,
                    'operator' => 'IN'
                )
            )
        );

        foreach ( $json as $term ) {
            $term_data = array(
                'parent' => (int)$term[1]
            );
            wp_update_term( (int)$term[0], 'pa_'.$sub_slug, $term_data );
        }
        $query = new WP_Query( $args );
        //update attributes state from each product
        $productos_total = 0;
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $product = wc_get_product( get_the_ID() );
                $productos_total += 1;
                Vitt_Attribute_Hierarchy_Helper::save_attributes_to_base_attribute($product);
                $product->save();
            }
            wp_reset_postdata();
        }
        
        $base_terms = get_terms(array(
            'taxonomy' => "pa_".$slug,
            'hide_empty' => false,
        ) );
        
        if($params){
            return array('message' => "Updated ".implode(",", $sub_terms_chanched)." in ".$productos_total." products",
                "data" => $base_terms);
        }else{
            return array('message' => "The message could not be sent");
        }
    }
    
}