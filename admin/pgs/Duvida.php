<?php 

// valida permissão
if( !in_array( "duvidas", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$menu_destaque = "duvidas";

$page = new StdAdminPage();
$page->title = "Duvidas";
$page->page = "Duvida";

$r = new Repeater();
$r->campos = "titulo;id";
$r->sql = "
	SELECT 
		titulo, 
		id 
	FROM 
		duvida 
	WHERE 
		codprojeto = ".CODPROJETO." 
		and ativo = 1 
	order by 
		ordem ASC;";
$r->txtItem = "
	<tr>
		<td>#titulo</td>
		<td class=\"td-controls\">
			<a class=\"controle\" title=\"Editar\" href=\"/admin/?pg=" . $page->page . "Editar&id=#id\"><img src=\"/admin/img/edt.png\" /></a>
			<a class=\"controle\" title=\"Excluir\" onclick=\"return confirm('Excluir?');\" href=\"/admin/?pg=" . $page->page . "Excluir&id=#id\"><img src=\"/admin/img/del.png\" /></a>
		</td>
	</tr>
";
$r->exec();

$page->table = true;
$page->table_header = "<th>Nome</th>";
$page->table_content = $r->html;

$page->render();

?>
