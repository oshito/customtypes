<?
include "custom_post_oshito/custom-oshito.php";

$argumentos[0] = array(
	'nombre_custom'		=>	'bandas',
	'editor'			=>	true,
	'imagen_destacada'	=>	true,
	'resumen'			=>	true,
	'label'				=>	'Bandas',
	'metabox'			=>	array(
								array('nombre'=>"subtitulo", "tipo"=>"texto_simple", "descripcion"=>"Sutítulo"),
								array('nombre'=>"web", "tipo"=>"texto_simple", "descripcion"=>"Web"),
								array('nombre'=>"facebook", "tipo"=>"texto_simple", "descripcion"=>"Facebook"),
								array('nombre'=>"youtube", "tipo"=>"texto_simple", "descripcion"=>"Youtube"),
								array('nombre'=>"bandcamp", "tipo"=>"texto_simple", "descripcion"=>"Bandcamp"),
								array('nombre'=>"instagram", "tipo"=>"texto_simple", "descripcion"=>"Instagram"),
								array('nombre'=>"categoria_evento", "tipo"=>"texto_simple", "descripcion"=>"Categoría para la agenda de eventos"),
								array('nombre'=>"son", "tipo"=>"texto_complejo", "descripcion"=>"La Banda son:"),
								array('nombre'=>"discografia", "tipo"=>"texto_complejo", "descripcion"=>"Discografía"),
								array('nombre'=>"video_destacado", "tipo"=>"texto_simple", "descripcion"=>"Enlace youtube vídeo destacado"),
							)
);

$argumentos[1] = array(
	'nombre_custom'		=>	'event',
	'label'				=>	'Fechas',
	'metabox'			=>	array(
								array('nombre'=>"entradas", "tipo"=>"texto_simple", "descripcion"=>"Entradas"),
								array('nombre'=>"masinfo", "tipo"=>"texto_simple", "descripcion"=>"Más Información"),
							)
);

crear_custom($argumentos);

function elemento_listado_custom($nombre){
	?>
	<div class="contenedor" onclick="location.href='<? the_permalink(); ?>';" >
		<div class="imagen"><?php the_post_thumbnail();?></div>
		<div class="titulo"><h2><? the_title(); ?></h2></div>
	</div>
	<?
    }

add_shortcode('listar_custom', 'listar_custom_funcion');

function filterEventOutputCondition($replacement, $condition, $match, $EM_Event){
    if (is_object($EM_Event)) {

        switch ($condition) {

            // replace LF with HTML line breaks
            case 'nl2br':
                // remove conditional
                $replacement = preg_replace('/\{\/?nl2br\}/', '', $match);
                // process any placeholders and replace LF
                $replacement = nl2br($EM_Event->output($replacement));
                break;

            // #_ATT{Website}
            case 'hay_entradas':
                if (is_array($EM_Event->event_attributes) && !empty($EM_Event->event_attributes['entradas']))
                    $replacement = preg_replace('/\{\/?hay_entradas\}/', '', $match);
                else
                    $replacement = '';
                break;

        }

    }

    return $replacement;
}

add_filter('em_event_output_condition', 'filterEventOutputCondition', 10, 4);

function listar_fechas($categoria, $titulo){
	if($categoria != ""):
	?>
	<div class="fechas">

	<? if($titulo !=""): ?>
		<h1><? echo $titulo; ?></h1>
	<? endif;
	$comunicanroll='<span class="titulo">#_EVENTNAME</span>';
	if($categoria=="90")$comunicanroll="";
	echo do_shortcode('
			[events_list category="'.$categoria.'" limit="99999"]
			<div class="contenedor_fecha">
				{has_image}<div class="imagen">#_EVENTIMAGE</div>{/has_image}
				<div class="fecha_izquierda">
					<span class="dia">#d/#m</span>'.$comunicanroll.'<span class="info"><b>#_LOCATIONNAME</b> · #_LOCATIONTOWN</span>
				</div>
				<div class="fecha_derecha">
					<span class="masinfo"><a href="#_ATT{masinfo}" target="_blank"><i class="fas fa-info-circle"></i></a></span>
					{hay_entradas}
					<span class="entradas"><a href="#_ATT{entradas}" target="_blank"><i class="fas fa-ticket-alt fa-1x"></i></a></span>
					{/hay_entradas}
				</div>
			</div>
			[/events_list]
		');
	?>

	</div>

	<?
	endif;
}

function fechas_banda_function($atts){
	extract(shortcode_atts(array(
		'categoria'		=>	'',
		'titulo'		=>	'',
	), $atts));

	ob_start();

	listar_fechas($categoria, $titulo);

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

add_shortcode('fechas_banda', 'fechas_banda_function');

?>
