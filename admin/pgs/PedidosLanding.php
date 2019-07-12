<?php 

// valida permissão
// print_r($permissoes_admin);

if( !in_array( "pedidos", $permissoes_admin ) ){
	include("pgs/404.pg.php") ;
	return;
}

$menu_destaque = "pedidos";

$page = new StdAdminPage();
$page->title = "Pedidos <small>( &uacute;ltimos 5 dias )</small>";
$page->page = "PedidosLanding";
$page->form = false;
$page->cadastrar = false;

$r = new Repeater();
$r->campos = "id;cod_pedido;data;nome;status;cod_status;evento;valor_pedido";
$r->sql = "
	select 
		id,
		cod_pedido,
		concat(nome, ' ', sobrenome) as nome,
		DATE_FORMAT( data_cadastro, '%d/%m %h:%i' ) as data,
		evento,
		status,
		cod_status,
		valor_pedido
	from 
		temp_pedido 
	where 
		ativo = 1
		and data_cadastro > DATE_ADD( CURRENT_TIMESTAMP, interval -5 day ) 
		and codprojeto = ".CODPROJETO." 
	order by 
		data_cadastro desc;
";
$r->txtItem = "
	<tr class=\"pedido_row\">
		<td>#id</td>
		<td>#cod_pedido</td>
		<td>#nome</td>
		<td>#data</td>
		<td>#evento</td>
		<td class=\"status_#cod_status\">#status</td>
		<td>#valor_pedido</td>
		<td class=\"td-controls\">
			<a class=\"controle\" title=\"Detalhes\" href=\"/admin/?pg=" . $page->page . "Detalhes&cod=#cod_pedido\"><img heigth=\"18\" src=\"/admin/img/search.png\" /></a>
		</td>
	</tr>
";
$r->exec();

$page->table = true;
$page->table_header = "
	<th>#</th>
	<th>C&oacute;d</th>
	<th>Nome</th>
	<th>Data</th>
	<th>Evento</th>
	<th>Status</th>
	<th>Valor</th>
";
$page->table_content = $r->html;

$page->render();

?>
