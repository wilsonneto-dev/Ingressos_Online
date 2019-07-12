<?php

// add style in header
$header_extra_styles = '
			<link href="/assets/css/usuario.css" rel="stylesheet"></link>
			<link href="/assets/css/pedido-detalhe.css" rel="stylesheet"></link>
			<link href="/assets/css/pedido-detalhe-print.css" rel="stylesheet" media="print"></link>';
$footer_extra_scripts = '';

//cabeçario
$_head_title = "Imprimir Pedido - ".$_head_title;
$_meta_description = "Imprimir Pedido. Compre ingressos para os melhores eventos com o Zé do Ingresso. ".$_meta_description;

$html_pedido = "";

$pedido = null;

try{
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

	if( isset( $_GET["codigo"] ) ){
		$pedido = Pedido::_get( intval( $_GET["codigo"] ) );
	}else{
		throw new Exception("Falta o parametro [codigo]", 1);		
	}

	// validar usuário
	if( $pedido == null ){
		throw new Exception("Pedido $_GET[codigo] nao encontrado...", 1);
	}

	if( $pedido->cod_usuario != $global_usuario->id ){
		throw new Exception("Pedido $pedido->cod_usuario nao pertence a este usuário...", 1);
	}

	$cid = BrasilCidade::_get( $global_usuario->cod_brasil_cidade );

	$evento = Evento::_get( $pedido->cod_evento );

	$items_html = "";
	$itens = PedidoItem::_getListaByPedido( $pedido->codigo );
	foreach ( $itens as $cod => $item ) {

		$ingresso = Ingresso::_get( $item->cod_ingresso );

		$items_html .= "
			&nbsp;&nbsp;-
			<b>$ingresso->descricao</b> 
			&nbsp;&nbsp;&nbsp;&nbsp;
			qtd.: $item->quantidade<br />
		";
	}
	$mensagem = "";
	if ( $pedido->cod_status != "3" &&  $pedido->cod_status != "4" ) {
		$mensagem = '<div><span class="status_1"><center>* Pagamento n&atilde;o confirmado<br />Ap&oacute;s efetuar o pagamento gere novamente esta c&oacute;pia</center></span></div><br /><br />';
	}

		$html_pedido = '
<div class="pedido_wrapper">
	<div class="wrap">
		<div class="logo02"><center><img src="/imgs/logo-black.png"></center><br /></div>
		'.$mensagem.'
		<section class="dados">
			<div class="a_right">
				<b>Pedido C&oacute;digo: </b>
				<span class="status_'.$pedido->cod_status.'">'.$pedido->codigo.'</span> /
				<span class="status_'.$pedido->cod_status.'">'.$pedido->status.'</span> /
				'.date_format($pedido->data, 'd/m/Y').'
			</div>
			<b>Comprador: </b>'.$global_usuario->nome.' '.$global_usuario->sobrenome.'<br />
			<b>CPF</b>:'.$global_usuario->cpf.' <br />
			<b>Cidade: </b>'.$cid->cidade.'-'.$cid->uf.' <br />
			<br />
			<b>Evento: </b><br /> '.$evento->titulo.' <br />'.$evento->data_mostrar.' <br />
		</section>
		<img class="logo01" style="float: right" src="/imgs/logo-black.png">
		<section class="itens">
			<br />Ingressos:<br /><br />
			'.$items_html.'
		</section>
		<div class="clear"><br /></div>
		<center><img class="barras" src="/barcode.php?filetype=PNG&dpi=72&scale=2&rotation=0&font_family=0&font_size=0&text=123456789012345678&thickness=30&start=NULL&code=BCGcode128&order='.$pedido->codigo.'&sec='.$global_usuario->id.'&email='.$global_usuario->email.'"></center>
	</div>
</div>
		';
	


	/*
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
		count(item.id) as qtd_item,
		case when count(item.id) = 1 then 'ingresso' else 'ingressos' end as label_qtd,
		ev.data as data_evento,
		ev.capa,
		case when current_timestamp > ev.data_final then '<span class=\"passado\">* Evento já aconteceu</span><br />' else '' end as passado
	from pedido ped
	inner join evento ev on ped.cod_evento = ev.id
	inner join pedido_item item on item.cod_pedido = ped.codigo
	where
		ped.cod_usuario = $global_usuario->id
		-- and ped.cod_status <> 0
		and ped.codprojeto = 1
		and ev.data_final > date_add( current_timestamp, interval -6 month ) -- pega apenas de 6 meses para hoje
	group by 
		ped.codigo
	order by
		ev.data desc,
		ped.data desc;";
	$r_pedidos->txtVazio = "nao :/";
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
							imprimir
						</a>
					</div>
				</div>
			</section>	
	'."\n";
	$r_pedidos->exec();
	*/

}
catch(Exception $ex){

	if( $global_usuario == null ){
		LogGeral::_salvar( "Erro ao gerar pedido para impressao. ".$ex->getMessage(), "Impressao Erro"  );
	}else{
		LogGeral::_salvar( "Erro ao gerar pedido para impressao #$global_usuario->id/$global_usuario->email/$global_usuario->nome ".( $pedido == null ? "" : $pedido->codigo )." ".$ex->getMessage(), "Impressao Erro" );
	}  

	header("Location: /404");
	die();

}
?>