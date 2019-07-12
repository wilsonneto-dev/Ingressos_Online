<?php 

// valida permissão
if( !in_array( "categorias", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$menu_destaque = "categorias";

$page = new StdAdminPage();
$page->title = "Categorias";
$page->page = "Categoria";

$r = new Repeater();
$r->campos = "descricao;nome;id";
$r->sql = "
	SELECT 
		nome, 
		descricao, 
		id 
	FROM 
		categoria 
	WHERE 
		codprojeto = ".CODPROJETO." 
		and ativo = 1 
	order by 
		datacadastro DESC;";
$r->txtItem = "
	<tr>
		<td>#nome</td>
		<td>#descricao</td>
		<td class=\"td-controls\">
			<a class=\"controle\" title=\"Editar\" href=\"/admin/?pg=" . $page->page . "Editar&id=#id\"><img src=\"/admin/img/edt.png\" /></a>
			<a class=\"controle\" title=\"Excluir\" onclick=\"return confirm('Excluir?');\" href=\"/admin/?pg=" . $page->page . "Excluir&id=#id\"><img src=\"/admin/img/del.png\" /></a>
		</td>
	</tr>
";
$r->exec();

$page->table = true;
$page->table_header = "<th>Nome</th><th>Descri&ccedil;&atilde;o</th>";
$page->table_content = $r->html;

$page->render();

?>
