<?php

// valida permissão
if( !in_array( "pedidos", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$menu_destaque = "pedidos";

$p = new TempPedido();
if( isset($_GET["cod"]) ){
	$p->cod_pedido = $_GET["cod"];
	$p->get_by_cod_pedido();
}

$page = new StdAdminPage();

$page->title = "Detalhes do Pedido: ".$p->cod_pedido;
$page->page = "PedidosLanding";
$page->back_link = true;
$page->title_back = "Pedidos";

$items = TempPedidoItem::_get_by_cod_pedido( $p->cod_pedido );
$items_html = "";
foreach ($items as $index => $item) {
	$items_html .= " - qtd: <b>$item->quantidade - $item->descricao</b> ( $item->codigo ) valor: $item->valor_total<br />"; 
}
$page->botoes_extras = [];
$page->html_content = "
	<b>Pedido</b>: $p->cod_pedido <small>(sec: $p->codigo_seguranca)</small><br />
	<b>PagSeguro</b>: $p->cod_pagseguro<br /> 
	<b>Status</b>: $p->status<br /> 
	<b>Valor</b>: $p->valor_pedido <small> / ingressos: $p->valor_ingressos, gateway: $p->valor_taxa_gateway</small><br />
	<b>Data</b>: ".$p->data_cadastro->format("d/m/Y H:i:s")."<br />
	<br />
	<b>Comprador</b>: $p->nome $p->sobrenome<br />
	<b>CPF</b>: $p->cpf<br />
	<b>E-mail</b>: $p->email<br />
	<b>Telefone</b>: $p->telefone<br />
	<b>Cidade</b>: $p->cidade - $p->uf
	<br /><br />
	Evento: <b>$p->evento</b><br />
	$items_html	
";

$page->render();

?>