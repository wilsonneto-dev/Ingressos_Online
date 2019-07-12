<?php

// add style in header
$header_extra_styles = '
<meta property="og:image" content="http://zedoingresso.com.br/imgs/face-image.jpg">
';

// add script in header
$footer_extra_scripts = '';

// banners no topo
$r_pub = new Repeater();
$r_pub->campos = "pub";
$r_pub->sql = "
	SELECT
		CONCAT('arr_campaign.push( { img: \"', imagem, '\", link: \"', link, '\", btn: \"', botao, '\" });' ) AS pub
	FROM 
		banner_topo
	WHERE 
		is_enabled( entrar_em, DATE_ADD( data_sair , INTERVAL +1 DAY ), visivel ) = 'yes'
		AND ativo = 1 
		AND codprojeto = ".CODPROJETO." 
	ORDER BY ordem";
$r_pub->txtVazio = "";
$r_pub->txtItem = '#html_pub'."\n";
$r_pub->exec();

if( $r_pub->contador > 0 ){
	$html_script_banner = "\n<script>var arr_campaign = []; ".$r_pub->html."</script>";
	$footer_extra_scripts .= '<script src="/assets/js/home-banner.js"></script>';
}

$id_local = intval( isset( $_GET[ "local" ] ) ? $_GET[ "local" ] : "0" );
$id_promoter = intval( isset( $_GET[ "promoter" ] ) ? $_GET[ "promoter" ] : "0" );

// eventos
$r_eventos = new Repeater();
$r_eventos->campos = "capa;id_url;titulo;local;cidade;dia;mes";
$r_eventos->sql = "
	SELECT
		ev.capa,
		ev.id_url,
		ev.titulo,
		l.nome AS local,
		c.nome AS cidade,
		DAY(data) AS dia,
		CASE MONTH(data)
			WHEN 1 THEN 'JAN'
			WHEN 2 THEN 'FEV'
			WHEN 3 THEN 'MAR'
			WHEN 4 THEN 'ABR'
			WHEN 5 THEN 'MAI'
			WHEN 6 THEN 'JUN'
			WHEN 7 THEN 'JUL'
			WHEN 8 THEN 'AGO'
			WHEN 9 THEN 'SET'
			WHEN 10 THEN 'OUT'
			WHEN 11 THEN 'NOV'
			WHEN 12 THEN 'DEZ'
		END AS mes			
	FROM 
		evento AS ev
	INNER JOIN local AS l on l.id = ev.cod_local
	INNER JOIN cidade AS c on c.id = l.cod_cidade
	WHERE 
		is_enabled( 
			ev.data_entrar, 
			DATE_ADD( ev.data_final , INTERVAL +1 DAY ), 
			visivel 
		) = 'yes'
		AND ev.ativo = 1 
		AND ev.oculto = 0 
		AND $id_local in ( ev.cod_local, 0 )
		AND $id_promoter in ( ev.cod_promoter, 0 )
		AND ev.codprojeto = ".CODPROJETO." 
	ORDER BY 
		ev.data ASC";
$r_eventos->txtVazio = "";
$r_eventos->txtItem = 
'		<div class="item">
			<div class="image-wrapper">
				<a href="/evento/#id_url">
					<img src="/#capa" alt="#titulo" />
				</a>
			</div>
			<div class="description-wrapper"><span class="date"><span>#dia</span><span>#mes</span>
				</span><h2 class="details">
					<span class="title">#titulo</span>
					<span class="location">#local - #cidade</span>
				</h2><span class="button">
					<a href="/evento/#id_url">Comprar</a>
				</span>
			</div>
		</div>
'."\n";

/*
$r_eventos->txtItem = '<div class="item">
			<div class="image-wrapper">
				<a href="/evento/#id_url">
					<img src="/#capa" alt="#titulo" />
				</a>
			</div>
			<div class="description-wrapper">
				<span class="date">
					<span>#dia</span>
					<span>#mes</span>
				</span><h2 class="details">
					<span class="event">#titulo</span>
					<span class="location">#local - #cidade</span>
				</h2><span class="button">
					<a href="/evento/#id_url">Comprar</a>
				</span>
			</div>
		</div>
'."\n";
*/

$r_eventos->exec();



?>