<?php

// add style in header
$header_extra_styles = '
			<link href="/assets/css/usuario.css" rel="stylesheet" />';
$footer_extra_scripts = '';

//cabeçario
$_head_title = "Meus Pedidos - ".$_head_title;
$_meta_description = "Meus pedidos. Compre ingressos para os melhores eventos com o Zé do Ingresso. ".$_meta_description;

// validar usuário
if( $global_usuario == null ){
	// mostrar mensagem na tela de login
	$_SESSION[S_MENSAGEM_ERRO] = "Efetue o login para continuar";
	// voltar para esta página ao logar
	$_SESSION[S_REDIRECIONAR] = $_SERVER["REQUEST_URI"];
	// redirecionar para a tela de login
	header("Location: /login");
	die();
}


$r_pedidos = new Repeater();
$r_pedidos->campos = "codigo;cod_status;data_mostrar;status;data;titulo;qtd_item;label_qtd;data;capa;passado";
$r_pedidos->sql = "
select 
	ped.codigo,
	ped.status,
	ped.cod_status, 
	date_format( ped.data, '%d/%m %k:%i' ) as data,
	ev.titulo,
	ev.data_mostrar,
	sum(item.quantidade) as qtd_item,
	case when count(item.id) = 1 then 'ingresso' else 'ingressos' end as label_qtd,
	ev.data as data_evento,
	ev.capa,
	case when current_timestamp > date_add( ev.data_final, interval +1 day ) then '<span class=\"passado\">* Evento já aconteceu</span><br />' else '' end as passado
from pedido ped
inner join evento ev on ped.cod_evento = ev.id
inner join pedido_item item on item.cod_pedido = ped.codigo
where
	ped.cod_usuario = $global_usuario->id
	and ped.cod_status <> 0
	and ped.codprojeto = 1
	and ev.data_final > date_add( current_timestamp, interval -6 month ) -- pega apenas de 6 meses para hoje
group by 
	ped.codigo
order by
	ped.data desc;";
$r_pedidos->txtVazio = '
<section class="default_page_content">
	<br />
	<div class="msg status0">
		N&atilde;o h&aacute; pedidos seus ainda :(
	</div>
	<br />
</section>				
';
$r_pedidos->txtItem = '
		<section class="pedido_item_lista">
			<div class="imagem">
				<img src="/#capa" alt="pedido-capa" />
			</div><div class="texto">
				<p>
					<label>Status do Pedido: </label><b class="status_ped_#cod_status">#status</b><br />
					<label>Pedido: </label><b>#codigo</b> - #data<br />
					<br />
					<b>#qtd_item #label_qtd</b> para <b>#titulo</b><br />
					<label>Data do Evento: </label>#data_mostrar<br />
					#html_passado
				</p>
				<div class="botoes">
					<a href="/usuario-pedido-impressao?codigo=#codigo" class="btn_default btn_cadastrar">
						Comprovante / Imprimir
					</a>
				</div>
			</div>
		</section>	
'."\n";
$r_pedidos->exec();



?>