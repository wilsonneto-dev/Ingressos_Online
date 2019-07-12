<?php 

// valida permissão
if( !in_array( "eventos", $permissoes_admin ) ){
	include("pgs/404.pg.php") ;
	return;
}

$menu_destaque = "eventos";

$page = new StdAdminPage();
$page->title = "Eventos";
$page->page = "Evento";

$r = new Repeater();
$r->campos = "titulo;data;imagem;id;status";
$r->sql = "
	SELECT 
		id,
		titulo,
		DATE_FORMAT( data, '%d/%m/%Y' ) as data,  
		imagem, 
		getstatus( data_entrar, DATE_ADD( data_encerrar_vendas , INTERVAL +1 DAY ), ( NOT venda_suspensa ) ) as status
	FROM 
		evento
	WHERE 
		ativo = 1 
	order by 
		data_encerrar_vendas DESC;";
$r->txtItem = "
	<tr>
		<td>#titulo</td>
		<td>#data</td>
		<td class=\"#status status\">#status</td>
		<td class=\"td-controls\">
			<a class=\"controle\" title=\"Ingressos\" href=\"/admin/?pg=" . $page->page . "Ingressos&id=#id\"><img src=\"/admin/img/ticket.png\" /></a>
			<a class=\"controle\" title=\"Editar\" href=\"/admin/?pg=" . $page->page . "Editar&id=#id\"><img src=\"/admin/img/edt.png\" /></a>
			<a class=\"controle\" title=\"Excluir\" onclick=\"return confirm('Excluir?');\" href=\"/admin/?pg=" . $page->page . "Excluir&id=#id\"><img src=\"/admin/img/del.png\" /></a>
		</td>
	</tr>
";
$r->exec();

$page->table = true;
$page->table_header = "<th>Evento</th><th>Data</th><th class=\"status\">Status</th>";
$page->table_content = $r->html;

$page->render();

?>
