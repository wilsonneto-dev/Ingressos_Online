<?php

// valida permissão
if( !in_array( "pedidos", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$menu_destaque = "pedidos";

$p = new Pedido();
if( isset($_GET["cod"]) ){
	$p->codigo = $_GET["cod"];
	$p->get();
}

$usuario = Usuario::_get( $p->cod_usuario );
$evento = Evento::_get( $p->cod_evento );
$cidade_selecionada = BrasilCidade::_get( $usuario->cod_brasil_cidade );

$page = new StdAdminPage();

$page->title = "Detalhes do Pedido: ".$p->codigo;
$page->page = "Pedidos";
$page->back_link = true;
$page->title_back = "Pedidos";

$items = PedidoItem::_getListaByPedido( $p->codigo );
$items_html = "";
foreach ( $items as $index => $item ) {
	$ingresso = Ingresso::_get( $item->cod_ingresso );
	$items_html .= " - qtd: <b>$item->quantidade - $ingresso->descricao</b> ( $ingresso->id ) valor: $item->valor_total<br />"; 
}
$page->botoes_extras = [];
$page->html_content = "
	<b>Pedido</b>: $p->codigo<br />
	<b>Transa&ccedil;&atilde;o</b>: $p->transacao<br /> 
	<b>Status</b>: $p->status <small>(".$p->data_status->format("d/m/Y H:i:s").")</small><br /> 
	<b>Valor</b>: $p->valor_pedido <small>".($p->valor_liquido - $p->valor_ingressos)."</small><br /> 
	<b>Data</b>: ".$p->data->format("d/m/Y H:i:s")."<br />
	<b>Ref/Campanha</b>: ".$p->ref."<br />
	<br />
	<b>Comprador</b>: $usuario->nome $usuario->sobrenome ( <a href=\"/admin/?pg=ClientesDetalhes&id=$usuario->id\" target=\"_blank\">#$usuario->id</a> )<br />
	<b>CPF</b>: $usuario->cpf<br />
	<b>E-mail</b>: $usuario->email<br />
	<b>Telefone</b>: $usuario->ddd $usuario->telefone<br />
	<b>Cidade</b>: $cidade_selecionada->cidade - $cidade_selecionada->uf
	<br /><br />
	Evento: <b>$evento->titulo - $evento->data_mostrar</b><br />
	$items_html	

";

$page->render();

?>