<?
// 		CUSTOM POST BY OSHITO	//
// 		versión 1.5				//

/* LISTADOS */

function listar_custom_funcion($atts){
	
	extract(shortcode_atts(array(
		'nombre' 	=> 'campanas',
		'cantidad'	=>	10,
		'columnas'	=>	4,
		'orderby'	=>	'date',
		'order' 	=> "ASC",
		
	), $atts));
	
	if($orderby == "title"){
		$order = "DESC";
	}

	$args = array(
		'post_type' 	=> 	$nombre,
		'posts_per_page'	=>	$cantidad,
		'orderby'	=>	$orderby,
		'order'   => $order,
	);
	
	$the_query = new WP_Query( $args );
	ob_start(); 
	if ( $the_query->have_posts() ) : 
		$elemento = 1;
		
		?>
    	<div class="<? echo $nombre;?> columnas_<? echo $columnas;?>">
		<? while ( $the_query->have_posts() ) : $the_query->the_post();
			if($elemento==1){
				echo "<div class='fila'>"; //se inicia una fila nueva
			}
			elemento_listado_custom($nombre);
			$elemento++;
			if($elemento==$columnas+1){
				echo "</div>";
				$elemento = 1; //se reinicializa el elemento
			}
		endwhile;
		if($elemento!=$columnas)echo "</div>"; //esto querrá decir que no se ha completado la última fila
		echo '</div>';
	wp_reset_postdata();

	else : ?>
		<p><?php esc_html_e( 'Ups, no hay contenido aún...' ); ?></p>
<?
	endif;
	$posts = ob_get_contents();
	
	

	ob_end_clean();

	$class = " et_pb_bg_layout_{$background_layout}";

	$output = sprintf(
		'<div%5$s class="%1$s%3$s%6$s">
			%2$s
		%4$s',
		( 'on' === $fullwidth ? 'et_pb_posts' : 'et_pb_blog_grid clearfix' ),
		$posts,
		esc_attr( $class ),
		( ! $container_is_closed ? '</div> <!-- .et_pb_posts -->' : '' ),
		( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
		( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' )
	);

	

	return $output;
}

function mostrar_custom_funcion($atts){
	
	extract(shortcode_atts(array(
		'nombre' => 'entradilla',
	), $atts));
	
	echo extraer_custom($nombre);
}

add_shortcode('mostrar_custom', 'mostrar_custom_funcion');

function extraer_custom($nombre){
	$meta = get_post_meta( get_the_ID(), $nombre);
	if($meta[0]!= "") return $meta[0];
	}

/* CUSTOM */
function crear_custom($argumentos){

	add_action( 'init', 'iniciar_custom',10,99 );
	add_action( 'add_meta_boxes', 'add_events_metaboxes' );
	add_action('new_to_publish', 'oshito_guardar_meta', 10, 2);
	add_action('save_post', 'oshito_guardar_meta', 10, 2);
	add_action( 'init', 'crear_taxonomias_propias',10,99 );
}


function iniciar_custom() {

	global $argumentos;
	
	foreach($argumentos as $argumento){
		if($argumento["nombre_custom"] != "page"):
		
		$labels = array(
			'name' => _x( $argumento["label"], 'post type general name' )
		);

		$args = array( 'labels' => $labels,
			'public' => true,
			'supports' => array( 'title', 'revisions'),	  
			//'taxonomies' => array('category') si lo quieres meter en las categorías globales
		);
		
		if($argumento["editor"]){array_push($args['supports'], "editor");}
		
		if($argumento["imagen_destacada"]){array_push($args['supports'], "thumbnail");}
		
		if($argumento["mostrar_custom"]){array_push($args['supports'], "custom-fields");}
		
		if($argumento["resumen"]){array_push($args['supports'], "excerpt");}
		
		register_post_type( $argumento["nombre_custom"], $args ); /* Registramos y a funcionar */
		
		endif;
	}
	
}


function add_events_metaboxes() {
	global $argumentos;
	foreach($argumentos as $argumento){
    	add_meta_box('oshito_meta_'.$argumento["nombre_custom"], 'Metabox by Oshito', 'oshito_metabox', $argumento["nombre_custom"], 'normal', 'default', $argumento);
	}
}


function oshito_metabox( $post, $argumento ) {	 
	if(!is_null($argumento['args']['metabox'])):
		?>
        <div id="meta_oshito">
		<?
		//var_dump($argumento['args']);
		
		foreach ($argumento['args']['metabox'] as $meta) { ?>

   			<? if ($meta["tipo"] == "texto_simple"):
				titulo_meta($meta["descripcion"]); ?>
				<input name="<? echo $meta["nombre"]?>" type="text" id="<? echo $meta["nombre"]?>" value="<? echo get_post_meta($post->ID, $meta["nombre"], true); ?>" size="80">
        	<? endif; ?>
            
        	<? if ($meta["tipo"] == "texto_lineas"):
				titulo_meta($meta["descripcion"]);?>
					<textarea name="<? echo $meta["nombre"]?>" id="<? echo $meta["nombre"]?>" rows="5" cols="80"><? echo get_post_meta($post->ID, $meta["nombre"], true); ?></textarea>
        	<? endif; ?>

         <? }
         
         foreach ($argumento['args']['metabox'] as $meta) {
			 if ($meta["tipo"] == "imagen"):
				titulo_meta($meta["descripcion"]);
				campo_imagen_wordpress($post, $meta["nombre"]);
        	endif;
         }
         
         foreach ($argumento['args']['metabox'] as $meta) {
        	if ($meta["tipo"] == "texto_complejo"):
				titulo_meta($meta["descripcion"]); 
				campo_editor_wordpress($post, $meta["nombre"]);
        	endif;
         }
	echo "</div>";
	endif;
 }

function titulo_meta($titulo){
	$output = '<h2>'.$titulo.'</h2>';
	echo $output;
	}


function oshito_guardar_meta( $post_id, $post ){
	global $argumentos;
	foreach($argumentos as $argumento){
		if(!is_null($argumento['metabox'])):
			foreach ($argumento['metabox'] as $meta) {
				update_post_meta( $post_id, $meta["nombre"], $_POST[$meta["nombre"]]);
			}
		endif;
	}
}


function crear_taxonomias_propias() {
	global $argumentos;
	
	foreach($argumentos as $argumento){
		if($argumento["taxonomia_propia"] != ""):
		register_taxonomy(
			$argumento["taxonomia_propia"]["nombre"],
			$argumento["nombre_custom"],
			array(
				'label' => $argumento["taxonomia_propia"]["descripcion"],
				'hierarchical' => true,
			)
		);
		endif;
		
		if($argumento["tag_propio"] != ""):
		register_taxonomy(
			$argumento["tag_propio"]["nombre"],
			$argumento["nombre_custom"],
			array(
				'label' => $argumento["tag_propio"]["descripcion"],
				'hierarchical' => false,
			)
		);
		
		endif;
	}
}

function campo_editor_wordpress($post, $variable) {
    $contenido = get_post_meta($post->ID, $variable, TRUE);
    if (!$contenido) $contenido = '';
    wp_editor( $contenido, $variable, array('textarea_rows' => '10', media_buttons => true));
}

function campo_imagen_wordpress($post, $variable){
    $stored_meta = get_post_meta( $post->ID ); ?>

    <p>
	<img style="max-width:200px;height:auto;" id="<? echo $variable; ?>-preview" src="<?php if ( isset ( $stored_meta[$variable] ) ){ echo $stored_meta[$variable][0]; } ?>" />
        <input type="button" id="<? echo $variable; ?>-button" class="button" value="Sube una imagen" />
        <input type="text" name="<? echo $variable; ?>" id="<? echo $variable; ?>" class="<? echo $variable; ?>" value="<?php if ( isset ( $stored_meta[$variable] ) ){ echo $stored_meta[$variable][0]; } ?>" style="visibility:hidden"/>
    </p>
<script>
jQuery('#<? echo $variable; ?>-button').click(function() {

    var send_attachment_bkp = wp.media.editor.send.attachment;

    wp.media.editor.send.attachment = function(props, attachment) {

        jQuery('#<? echo $variable; ?>').val(attachment.url);
		jQuery('#<? echo $variable; ?>-preview').attr('src',attachment.url);
        wp.media.editor.send.attachment = send_attachment_bkp;
    }

    wp.media.editor.open();

    return false;
});
</script>
<?
	}



/* EJEMPLO USO */

/*
$argumentos[0] = array(
	'nombre_custom'		=>	'documentacion_femp',
	'label'				=>	'Documentación FEMP',
	'editor'			=>	false,
	'imagen_destacada'	=>	false,
	'taxonomia_propia'	=>	array('nombre'=>"tipo_documento", "descripcion"=>"Tipo de documento"),
	'metabox'			=>	array(
								array('nombre'=>"archivos", "tipo"=>"texto_complejo", "descripcion"=>"Archivos para descarga (formato lista)"),
							)
);

$argumentos[1] = array(
		'nombre_custom'		=>	'leg_municipal',
		'label'				=>	'Legislación Municipal',
		'editor'			=>	false,
		'imagen_destacada'	=>	false,
		'taxonomia_propia'	=>	array('nombre'=>"tipo_datos", "descripcion"=>"Tipo de datos"),
		'metabox'			=>	array(
									array('nombre'=>"archivos", "tipo"=>"texto_complejo", "descripcion"=>"Archivos para descarga (formato lista)"),
								)
	);


$argumentos[2] = array(
		'nombre_custom'		=>	'experiencias',
		'label'				=>	'Experiencias Transformadoras',
		'editor'			=>	false,
		'imagen_destacada'	=>	true,
		'metabox'			=>	array(
									array('nombre'=>"entidad_local", "tipo"=>"texto_simple", "descripcion"=>"Entidad local"),
									array('nombre'=>"datos", "tipo"=>"texto_lineas", "descripcion"=>"Datos"),
									array('nombre'=>"descripcion", "tipo"=>"texto_lineas", "descripcion"=>"Descripción"),
									array('nombre'=>"objetivos", "tipo"=>"texto_lineas", "descripcion"=>"Objetivos"),
									array('nombre'=>"resultados", "tipo"=>"texto_lineas", "descripcion"=>"Resultados"),
									array('nombre'=>"archivos", "tipo"=>"texto_complejo", "descripcion"=>"Documentos Adjuntos (formato lista)"),
								)
	);



crear_custom($argumentos);


EJEMPLO SHORTCODE


function oshito_shortcode_informacion( $atts ) {
	$atts = shortcode_atts( array(
		'comensales' => "si",
		'tiempo' => "si"
	), $atts, 'recetas' );
	
	$output = "<div class='informacion'>";
	
	if($atts['tiempo']=="si"){
		$meta = get_post_meta( get_the_ID(), 'tiempo_receta');
		$output.= $meta[0]." min. / ";
	}
	
	if($atts['comensales']=="si"){
		$meta = get_post_meta( get_the_ID(), 'comensales_receta');
		$output.= $meta[0].' personas';
	}
	
	
	
	$output.="</div>";
	return $output;
	
}

add_shortcode( 'receta_informacion', 'oshito_shortcode_informacion' );

*/

?>