<?php 

// valida permissão
if( !in_array( "cidades", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$menu_destaque = "cidades";

$page = new StdAdminPage();
$page->title = "Cidades";
$page->page = "Cidade";

$r = new Repeater();
$r->campos = "uf;nome;id";
$r->sql = "
	SELECT 
		nome, 
		uf, 
		id 
	FROM 
		cidade 
	WHERE 
		codprojeto = ".CODPROJETO." 
		and ativo = 1 
	order by 
		datacadastro DESC;";
$r->txtItem = "
	<tr>
		<td>#nome</td>
		<td>#uf</td>
		<td class=\"td-controls\">
			<a class=\"controle\" title=\"Editar\" href=\"/admin/?pg=" . $page->page . "Editar&id=#id\"><img src=\"/admin/img/edt.png\" /></a>
			<a class=\"controle\" title=\"Excluir\" onclick=\"return confirm('Excluir?');\" href=\"/admin/?pg=" . $page->page . "Excluir&id=#id\"><img src=\"/admin/img/del.png\" /></a>
		</td>
	</tr>
";
$r->exec();

$page->table = true;
$page->table_header = "<th>Cidade</th><th>UF</th>";
$page->table_content = $r->html;

$page->render();

?>
