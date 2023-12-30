<?php

defined('ABSPATH') or die();
//response to update in vittfiles-sub-menu-attribute-config.php
add_action( 'rest_api_init', 'vittfiles_rest_api_update_attributes' );
function vittfiles_rest_api_update_attributes() {
    register_rest_route( 'accion/', 'update-attributes', array(
        'methods'  => 'POST',
        'callback' => 'vittfiles_update_attributes',
        'permission_callback' => function ( WP_REST_Request $request ) {
            return current_user_can( 'publish_posts' );
        },
    ) );
}

function vittfiles_update_attributes( $request ) {
    /* return $request->get_params(); */
    $params = $request->get_params();
    $slug = $params["slug"];
    $sub_slug = $params["sub_slug"];
    $json = $params["result"];
    
    //old values to compare if they have changes
    $ej_terms = get_terms( array(
        'taxonomy' => 'pa_color',
        'hide_empty' => false,
    ) );
    $prev_terms = array();
    foreach ( $ej_terms as $ej_term ){
        $prev_terms[$ej_term->term_id] = [$ej_term->parent,$ej_term->name];
    }
    //get the name of the colors that had changes
    $colors = array();
    foreach ( $json as $term ){
        if((int)$term[1] != $prev_terms[(int)$term[0]][0]){
            $colors[] = $prev_terms[(int)$term[0]][1];
        }
    }
    //find all product that contain at least one of changed colors
    $args = array(
        'post_type' => 'product',
        'tax_query' => array(
            array(
                'taxonomy' => 'pa_color',
                'field' => 'name',
                'terms' => $colors,
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
            vittfiles_save_attributes_to_base_attribute($product);
            $product->save();
        }
        wp_reset_postdata();
    }
    $base_terms = get_terms(array(
		'taxonomy' => "pa_".$slug,
		'hide_empty' => false,
	) );
    
    if($params){
        return array('message' => "actualizado ".implode(",", $colors)." en ".$productos_total." productos",
            "data" => $base_terms);
    }else{
        return array('message' => "El mensaje no se pudo enviar");
    }
}

//create a new connection in menu-attribte-config.php
add_action( 'admin_post_create_attribute_conection', 'vittfiles_create_attribute_conection' );

function vittfiles_create_attribute_conection() {
	if(!wp_verify_nonce($_POST['create_attribute_conection_nonce'], 'create_attribute_conection_name')){
		wp_redirect( get_admin_url()."admin.php?page=vittfiles-menu-attribute-config.php"."?sent=".false);
		return;
	}

	$slug = trim(sanitize_text_field($_POST['attribute_name']));
	$sub_slug = trim(sanitize_text_field($_POST['sub_attribute_name']));
	$result = get_option( "attribute_connection") . $slug . "," . $sub_slug . "|";
	$ok = update_option( "attribute_connection", $result);

	wp_redirect(get_admin_url()."admin.php?page=vittfiles-menu-attribute-config.php"."&sent=".$ok); //asumiendo que existe esta url
}
//delete a connection in menu-attribte-config.php
function vittfiles_delete_attribute_connection_page(){
    $slug = trim(sanitize_text_field($_GET['slug']));
	$sub_slug = trim(sanitize_text_field($_GET['sub-slug']));
	$replace = $slug . "," . $sub_slug . "|";
    $result = str_replace($replace,"",get_option( "attribute_connection"));
    $ok = update_option( "attribute_connection", $result);
    wp_redirect(get_admin_url()."admin.php?page=vittfiles-menu-attribute-config.php"."&is-delete=".$ok);
}