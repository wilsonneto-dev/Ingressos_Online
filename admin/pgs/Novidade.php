<?php 

// valida permissão
if( !in_array( "novidades", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$menu_destaque = "novidades";

$page = new StdAdminPage();
$page->title = "Novidades";
$page->page = "Novidade";

$r = new Repeater();
$r->campos = "titulo;data;id";
$r->sql = "
	SELECT 
		titulo,
		id,
		DATE_FORMAT(datacadastro, '%d/%m/%Y') as data
	FROM 
		novidade 
	WHERE 
		codprojeto = ".CODPROJETO." 
		AND ativo = 1 
	order by 
		datacadastro DESC;";
$r->txtItem = "
	<tr>
		<td>#titulo</td>
		<td>#data</td>
		<td class=\"td-controls\">
			<a class=\"controle\" title=\"Editar\" href=\"/admin/?pg=" . $page->page . "Editar&id=#id\"><img src=\"/admin/img/edt.png\" /></a>
			<a class=\"controle\" title=\"Excluir\" onclick=\"return confirm('Excluir?');\" href=\"/admin/?pg=" . $page->page . "Excluir&id=#id\"><img src=\"/admin/img/del.png\" /></a>
		</td>
	</tr>
";
$r->exec();

$page->table = true;
$page->table_header = "<th>Novidade</th><th>Publicado em</th>";
$page->table_content = $r->html;

$page->render();

?>
