<?
include "custom_post_oshito/custom-oshito.php";

$argumentos[0] = array(
	'nombre_custom'		=>	'prueba',
	'label'				=>	'asi se llama',
	'taxonomia_propia'	=>	array('nombre'=>"genero", "descripcion"=>"esto sería lo que sale en el label"),
	'metabox'			=>	array(
								array('nombre'=>"nombre", "tipo"=>"texto_simple", "descripcion"=>"esto sería lo que sale en el label"),
								array('nombre'=>"nombre2", "tipo"=>"imagen", "descripcion"=>"aquí podríamos poner lo que queramos"),
								array('nombre'=>"nombre3", "tipo"=>"texto_complejo", "descripcion"=>"aquí podríamos poner lo que queramos"),
							)
);



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

function elemento_listado_custom($nombre){
	?>
	<div class="contenedor">
		<div class="imagen"><?php the_post_thumbnail();?></div>
		<div class="titulo"><h2><? the_title(); ?></h2></div>
		<div class="contenido"><? the_content(); ?></div>
	</div>
	<?
    }
?>


