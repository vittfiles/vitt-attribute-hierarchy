<?php
defined('ABSPATH') or die();
/**
 * Helper with static functions
 *
 * @link       https://github.com/vittfiles
 * @since      1.2.0
 *
 * @package    Vitt_Attribute_Hierarchy_For_Woocommerce
 * @subpackage Vitt_Attribute_Hierarchy_For_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Vitt_Attribute_Hierarchy_For_Woocommerce
 * @subpackage Vitt_Attribute_Hierarchy_For_Woocommerce/admin
 * @author     Vittfiles <email@email.com>
 */
class Vitt_Attribute_Hierarchy_Helper {

    private static function vittfiles_test_odd($var){
        return ($var != "");
    }

    public static function get_attribute_connection_list(){
        $list = get_option( "attribute_connection");
        $list = explode("|",$list);
        return array_filter($list,array(__CLASS__,"vittfiles_test_odd"));
    }

    //save the base attributes in the product that contain the corresponding sub-attribute
    public static function save_attributes_to_base_attribute($product){
        $attributes = $product->get_attributes();
        $list = self::get_attribute_connection_list();
        foreach( $list as $li){
            $info = explode(",",$li);
            if(isset($attributes["pa_".$info[1]])){
                $parent_terms = get_terms(array(
                    'taxonomy' => "pa_".$info[0],
                    'hide_empty' => false,
                ) );
                $terms = get_the_terms( $product->get_id(), 'pa_'.$info[1] );
                $values = array();
                foreach ( $terms as $term ) {
                    foreach($parent_terms as $parent_term){
                        if($parent_term->term_id == $term->parent){
                            $values[] = $parent_term->name;
                        }
                    }
                }

                $attribute = new WC_Product_Attribute();
                $attribute->set_id(wc_attribute_taxonomy_id_by_name( 'pa_'.$info[0] ));
                $attribute->set_name( 'pa_'.$info[0] );
                $attribute->set_options($values);
                $attribute->set_position( 0 );
                $attribute->set_visible( 0 );
                $attribute->set_variation( 0 );
                $attributes[] = $attribute;
                $product->set_attributes($attributes);
                /* $product->save(); */
            }else{
                $attribute = new WC_Product_Attribute();
                $attribute->set_id(wc_attribute_taxonomy_id_by_name( 'pa_'.$info[0] ));
                $attribute->set_name( 'pa_'.$info[0] );
                $attribute->set_options(array());
                $attribute->set_position( 0 );
                $attribute->set_visible( 0 );
                $attribute->set_variation( 0 );
                $attributes[] = $attribute;
                $product->set_attributes($attributes);
                /* $product->save(); */
            }
        }
    }
}