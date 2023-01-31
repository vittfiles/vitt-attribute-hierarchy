<?php
//filtering elements with a valid color value
function vittfiles_filter_colors($var){
    if($var->meta_value != "0" && mb_substr($var->meta_value, 0, 1) == "#" )
    return $var;
}
function vittfiles_getColors($slug){
    global $wpdb;

    $results = $wpdb->prefix . 'term_taxonomy';
    $results_color = $wpdb->prefix . 'termmeta';
    $results_nombre = $wpdb->prefix . 'terms';
    
    $join = $wpdb->get_results("SELECT rn.name , rc.meta_value FROM ($results_nombre AS rn ,$results_color AS rc) INNER JOIN $results AS r1 ON (r1.taxonomy = 'pa_".$slug."' AND r1.term_id = rn.term_id AND r1.term_id = rc.term_id)");
    
    $join_res = array_filter($join,'vittfiles_filter_colors');
    $colors = array();
    foreach($join_res as $col){
        $colors[$col->name] = $col->meta_value;
    }
    return $colors;
}

function vittfiles_test_odd($var){
	return ($var != "");
}
function vittfiles_get_attribute_connection_list(){
    $list = get_option( "attribute_connection");
	$list = explode("|",$list);
	return array_filter($list,"vittfiles_test_odd");
}
//save the base attributes in the product that contain the corresponding sub-attribute
function vittfiles_save_attributes_to_base_attribute($product){
	$attributes = $product->get_attributes();
	$list = vittfiles_get_attribute_connection_list();
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
		}
	}
}