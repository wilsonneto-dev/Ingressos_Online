<?php 

// valida permissão
if( !in_array( "locais", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$menu_destaque = "locais";

$page = new StdAdminPage();
$page->title = "Locais";
$page->page = "Local";

$r = new Repeater();
$r->campos = "nome;cidade;id";
$r->sql = "
	SELECT 
		l.nome as nome,
		concat( c.nome, ' / ', c.uf ) as cidade,
		l.id as id
	FROM 
		local as l,
		cidade as c
	WHERE 
		l.cod_cidade = c.id
		and l.codprojeto = ".CODPROJETO." 
		and c.codprojeto = ".CODPROJETO." 
		and l.ativo = 1 
		and c.ativo = 1 
	order by 
		l.nome DESC;";
$r->txtItem = "
	<tr>
		<td>#nome</td>
		<td>#cidade</td>
		<td class=\"td-controls\">
			<a class=\"controle\" title=\"Editar\" href=\"/admin/?pg=" . $page->page . "Editar&id=#id\"><img src=\"/admin/img/edt.png\" /></a>
			<a class=\"controle\" title=\"Excluir\" onclick=\"return confirm('Excluir?');\" href=\"/admin/?pg=" . $page->page . "Excluir&id=#id\"><img src=\"/admin/img/del.png\" /></a>
		</td>
	</tr>
";
$r->exec();

$page->table = true;
$page->table_header = "<th>Local</th><th>Cidade / UF</th>";
$page->table_content = $r->html;

$page->render();

?>
