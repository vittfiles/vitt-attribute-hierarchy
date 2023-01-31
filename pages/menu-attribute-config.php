<?php

function vittfiles_attributes_config_page(){
    /* update_option( "attribute_connection", ""); */
    $list = vittfiles_get_attribute_connection_list();
    $print_text = '
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
    ';
    print($print_text);
                    $text = '';
                    $dark = true;
                    foreach( $list as $li){
                        $info = explode(",",$li);
                        $alternate = "";
                        if($dark){
                            $alternate = "alternate";
                        }
                        $href = get_admin_url()."admin.php?page=vittfiles-sub-menu-attribute-config.php&slug=".$info[0]."&sub-slug=".$info[1];
                        $delete = get_admin_url()."admin.php?page=vittfiles-delete-attribute-connection-page.php&slug=".$info[0]."&sub-slug=".$info[1];
                        $text .= '<tr class="'.$alternate.'">
                            <td>'.$info[0].'
                                <div class="row-actions">
                                    <span class="delete">
                                        <a class="delete" href="'.$delete.'">Eliminar</a>
                                    </span>
                                </div>
                            </td>
                            <td>'.$info[1].'</td>
                            <td class="attribute-terms">
                                <a href="'.$href.'" class="configure-terms">Configure terms</a>
                            </td>
                        </tr>';
                        $dark = !$dark;
                    }
                    print($text);

                    $print_text = '
                    <script type="text/javascript">
                    /* <![CDATA[ */

                        jQuery( "a.delete" ).on( "click", function() {
                            if ( window.confirm( "¿Seguro de que querés eliminar este atributo?" ) ) {
                                return true;
                            }
                            return false;
                        });

                    /* ]]> */
                    </script>
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
                <form method="post" action="'.admin_url( 'admin-post.php' ).'" >
	                ';
                print($print_text);
                wp_nonce_field('create_attribute_conection_name','create_attribute_conection_nonce');
                
                $print_text = '

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
                    <input type="hidden" name="action" value="create_attribute_conection">
                    <p class="submit"><button type="submit" name="add_new_attribute" id="submit"
                            class="button button-primary" value="Add connection">Add connection</button></p>
                </form>
            </div>
        </div>
    </div>';
    print($print_text);
}