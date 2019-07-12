<?php

// add style in header
$header_extra_styles = '';

// add script in header
$footer_extra_scripts = '<script src="/assets/js/order.js"></script>';

$id_url = isset($_GET["evento"]) ? $_GET["evento"] : "";
$obj = Evento::_getByUrl( $id_url );

if( $obj== null ){
	header("Location: /");
	die();
}

if( $obj->visivel == 0 ){
	header("Location: /");
	die();
}

if( $obj->data_entrar > (new DateTime()) ){
	header("Location: /");
	die();
}

if( $obj->imagem_facebook != "" ){
	// add style in header
	$header_extra_styles .= '
	<meta property="og:image" content="http://zedoingresso.com.br/'.$obj->imagem_facebook.'">
	';
}

$_head_title .=  htmlentities( " - $obj->titulo" ) ;
$_meta_keywords .= htmlentities(", $obj->titulo");
$_meta_description .= htmlentities(" Compre ingressos para $obj->titulo.");

$local = Local::_get( $obj->cod_local );
$cidade = Cidade::_get( $local->cod_cidade );

// ingressos
$r_ingressos = new Repeater();
$r_ingressos->campos = "id;descricao;valor_total;valor_str;valor";
$r_ingressos->sql = "
	SELECT
		id,
		descricao,
		valor,
		( ( valor * (taxa_percentual/100) ) + taxa_fixa ) AS taxa,
		ROUND( ( valor + ( ( valor * (taxa_percentual/100) ) + taxa_fixa ) ) , 2 ) AS valor_total,
		REPLACE( 
				ROUND( 
						( valor + ( ( valor * (taxa_percentual/100) ) + taxa_fixa ) ) 
				, 2 ) 
		, '.',',' ) AS valor_str
	FROM 
		ingresso
	WHERE 
		is_enabled( data_entrar, DATE_ADD( data_sair , INTERVAL +1 DAY ), visivel ) = 'yes'
		AND ativo = 1 
		AND cod_evento =  $obj->id
		AND codprojeto = ".CODPROJETO." 
	ORDER BY ordem";

$r_ingressos->txtVazio = "";
$r_ingressos->txtItem = '
						<tr>
							<td class="descricao">
								#descricao
							</td>
							<td class="valor">
								R$ #valor_str
							</td>
							<td class="quantidade">
								<select class="quantidade update_value" data-value="#valor_total" name="t#id">
									<option>0</option>
									<option>1</option>
									<option>2</option>
									<option>3</option>
									<option>4</option>
									<option>5</option>
									<option>6</option>
									<option>7</option>
									<option>8</option>
									<option>9</option>
									<option>10</option>
								</select>
							</td>
						</tr>'."\n";
$r_ingressos->exec();


?>