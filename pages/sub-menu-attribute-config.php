<?php

function vittfiles_attributes_config_sub_page(){
	$slug = trim(sanitize_text_field($_GET['slug']));
	$sub_slug = trim(sanitize_text_field($_GET['sub-slug']));
    print('<div style="position: relative;">
	<h1>Sub página</h1>');
    
	$sub_terms = get_terms(array(
		'taxonomy' => "pa_".$sub_slug,
		'hide_empty' => false,
	) );
    $terms = get_terms(array(
		'taxonomy' => "pa_".$slug,
		'hide_empty' => false,
	) );

    $res_terms = array();
    foreach($terms as $term){
        $res_terms[] = $term;
        foreach($sub_terms as $sub_term){
            if($term->term_id == $sub_term->parent){
                $res_terms[] = $sub_term;
            }
        }
    }
    $alone_terms = array();
    foreach($sub_terms as $sub_term){
        if($sub_term->parent == 0){
            $alone_terms[] = $sub_term;
        }
    }
    //get color attributes
    $sub_colors = vittfiles_getColors($sub_slug);
    $colors = vittfiles_getColors($slug);
    print('<script>console.log(`');
    /* var_dump($join_res); */
    var_dump($sub_colors);
    print('`);</script>');

    $print_text = '
        <div id="loader" class="none" >
            <p>loading data, this can take a long time</p>
            <p>please wait!!!</p>
            <img src="'.plugin_dir_url(__FILE__).'/loader.gif"/>
        </div>
        <div class="col-wrap">
            <a href="'.get_admin_url().'admin.php?page=vittfiles-menu-attribute-config.php">&larr; Back to config page</a>
        <div class="tablenav top">
            <button id="update" class="button action">Update</button>
            <br class="clear">
        </div>
        <h2 class="screen-reader-text">Lista de etiquetas</h2>
        <table class="wp-list-table widefat fixed striped table-view-list tags ui-sortable">
            <thead>
                <tr>
                    <th scope="col" id="posts" class="manage-column column-posts num sortable desc"><span>hierarchy</span><span
                                class="sorting-indicator"></span></th>
                    <th scope="col" id="wvs-meta-preview" class="manage-column column-wvs-meta-preview"></th>
                    <th scope="col" id="name" class="manage-column column-name column-primary sortable desc"><span>Nombre</span><span
                                class="sorting-indicator"></span></th>
                    <th scope="col" id="description" class="manage-column column-description sortable desc"><span>Descripción</span><span
                                class="sorting-indicator"></span></th>
                    <th scope="col" id="slug" class="manage-column column-slug sortable desc"><span>Slug</span><span
                                class="sorting-indicator"></span></th>
                    <th scope="col" id="posts" class="manage-column column-posts num sortable desc"><span>Cantidad</span><span
                                class="sorting-indicator"></span></th>
                    <td class="column-handle" style="display: table-cell;"></td>
                </tr>
                <tr  class=" alone">
                        <td class="slug column-slug" colspan="6">&nbsp;&nbsp; Without parent</td>
                    </tr>
            </thead>
            <tbody id="the-list" class="ui-sortable" data-wp-lists="list:tag">
            ';
            print($print_text);
                $text = '';
                //add without parent
                foreach ( $alone_terms as $term ) {
                    $text .= '<tr  class="ui-sortable-handle child-attr" data-index="'.$term->term_id.'" data-parent="'.$term->parent.'">
                        <td class="slug column-slug">&nbsp;&nbsp;&nbsp;&nbsp; &#8627;</td>
                        <td class="wvs-meta-preview column-wvs-meta-preview" data-colname="">
                            <div class="wvs-preview wvs-color-preview" style="background-color:'.$sub_colors[$term->name].';"></div>
                        </td>
                        <td class="name column-name has-row-actions column-primary" data-colname="Nombre"><strong>'.$term->name.'</strong><br>
                            <div class="hidden" id="inline_24">
                                <div class="name">'.$term->name.'</div>
                                <div class="slug">'.$term->slug.'</div>
                                <div class="parent">'.$term->parent.'</div>
                            </div>
                        </td>
                        <td class="description column-description" data-colname="Descripción"><span
                                aria-hidden="true">—</span><span class="screen-reader-text">'.$term->description.'</span></td>
                        <td class="slug column-slug" data-colname="Slug">'.$term->slug.'</td>
                        <td class="posts column-posts" data-colname="Cantidad">'.$term->count.'</td>
                        <td class="column-handle ui-sortable-handle" style="display: table-cell;"></td>
                    </tr>';
                }
                foreach ( $res_terms as $term ) {
                    $info = '<div class="parent">'.$term->parent.'</div>';
                    $type = "&nbsp;&nbsp;&nbsp;&nbsp; &#8627;";
                    $color = $sub_colors[$term->name];
                    if($term->taxonomy == "pa_".$slug){
                        $type = "&nbsp;&nbsp; Base";
                        $text .= '<tr  class="ui-sortable-handle base" data-index="'.$term->term_id.'">';
                        $info = '<div class="id">'.$term->term_id.'</div>';
                        $color = $colors[$term->name];
                    }else{
                        $text .= '<tr  class="ui-sortable-handle child-attr" data-index="'.$term->term_id.'" data-parent="'.$term->parent.'">';
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
                        <td class="description column-description" data-colname="Descripción"><span
                                aria-hidden="true">—</span><span class="screen-reader-text">'.$term->description.'</span></td>
                        <td class="slug column-slug" data-colname="Slug">'.$term->slug.'</td>
                        <td class="posts column-posts" data-colname="Cantidad">'.$term->count.'</td>
                        <td class="column-handle ui-sortable-handle" style="display: table-cell;"></td>
                    </tr>';
                }
                
                print($text);
                $print_text = '
            </tbody>
            <tfoot>
                <tr>
                <th scope="col" id="wvs-meta-preview" class="manage-column column-wvs-meta-preview"></th>
                </tr>
            </tfoot>

        </table>

    </div>
    </div>
    <style>
        #loader{
            position: absolute;
            top = 0px;
            text-align: center;
            color: #000000;
            width: 100%;
            height: 100%;
            background: #FFFFFF99;
        }
        #loader p{
            font-size: 1.7rem;
            font-weight: bold;
        }
        .none{
            display:none;
        }
    </style>
    <script language="JavaScript" type="text/javascript">
            function orderUsers() 
            {
                var newOrder = jQuery(".ui-sortable").sortable("toArray");
                console.log( newOrder );
            }
            function myuserorderaddloadevent()
            {
                jQuery(".ui-sortable").sortable({ 
                    placeholder: "sortable-placeholder", 
                    revert: false,
                    tolerance: "pointer" 
                });
            };
            addLoadEvent( myuserorderaddloadevent );

            const dir = "'.rest_url( '/accion/update-attributes' ).'";
            const nonce = "'.wp_create_nonce( 'wp_rest' ).'";
            let bot = document.getElementById("update");
            bot.addEventListener("click",e => {
                e.preventDefault();
                let theList = document.querySelectorAll("#the-list .child-attr")
                if(theList){
                    let result = [];
                    theList.forEach(li => {
                        let base = prev(li);
                        if(base){
                            result.push([li.getAttribute("data-index"),base.getAttribute("data-index")]);
                        }else{
                            result.push([li.getAttribute("data-index"),"0"]);
                        }
                    });
                    let res = JSON.stringify({result: result, slug: "'.$slug.'", sub_slug: "'.$sub_slug.'"});
                    var formData = new FormData();
                    formData.append( "json", res );
                    console.log(res);
                    
                    document.getElementById("loader").classList.remove("none");
                    fetch(dir,{
                        method: "POST",
                        headers: {
                            "X-WP-Nonce": nonce,
                            "Content-Type": "application/json"
                        },
                        body: res
                    })
                    .then(res=>{return res.ok? res.json(): Promise.reject(res)})
                    .then(res=>{
                        console.log(res);
                        alert(res.message);
                        document.getElementById("loader").classList.add("none");
                        let baseList = document.querySelectorAll("#the-list .base");
                        if(baseList){
                            baseList.forEach(li => {
                                res.data.forEach(dat => {
                                    if(dat.term_id === parseInt(li.getAttribute("data-index"))){
                                    base = li.querySelector(".posts");
                                    base.innerHTML = dat.count;
                                    }
                                });
                            });
                        }
                    })
                    .catch(err=>{
                        console.log(err);
                        alert("Error");
                        document.getElementById("loader").classList.add("none");
                    });
                }
                console.log("    -----   ----- ---- ");
            });
            function prev(element){
                let previous = element.previousElementSibling;
                while (previous) {
                if (previous.classList.contains("base")) {
                    return previous;
                }
                previous = previous.previousElementSibling;
                }
                return null;
            }

        </script>';
        print($print_text);
}