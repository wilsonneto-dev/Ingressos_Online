<?php 

// valida permissão
if( !in_array( "parceiros", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$menu_destaque = "parceiros";

$page = new StdAdminPage();
$page->title = "Parceiros";
$page->page = "Parceiro";

$r = new Repeater();
$r->campos = "nome;id";
$r->sql = "
	SELECT 
		nome,
		id
	FROM 
		parceiro 
	WHERE 
		codprojeto = ".CODPROJETO." 
		and ativo = 1 
	order by 
		nome DESC;";
$r->txtItem = "
	<tr>
		<td>#nome</td>
		<td class=\"td-controls\">
			<a class=\"controle\" title=\"Editar\" href=\"/admin/?pg=" . $page->page . "Editar&id=#id\"><img src=\"/admin/img/edt.png\" /></a>
			<a class=\"controle\" title=\"Excluir\" onclick=\"return confirm('Excluir?');\" href=\"/admin/?pg=" . $page->page . "Excluir&id=#id\"><img src=\"/admin/img/del.png\" /></a>
		</td>
	</tr>
";
$r->exec();

$page->table = true;
$page->table_header = "<th>Parceiro</th>";
$page->table_content = $r->html;

$page->render();

?>
