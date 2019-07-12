<?php 

// valida permissão
if( !in_array( "administradores", $permissoes_admin ) ){
	include("pgs/404.pg.php") ;
	return;
}

$menu_destaque = "administradores";

$page = new StdAdminPage();
$page->title = "Administradores";
$page->page = "Administrador";

$r = new Repeater();
$r->campos = "grupo;nome;ultimo_acesso;id;status";
$r->sql = "
	SELECT 
		g.nome as grupo,
		u.nome, 
		case when u.bloqueado = 1 then 'Bloqueado' else 'Ativo' end as status, 
		case when ultimo_acesso is null then '' else date_format(ultimo_acesso, '%d/%m/%y %H:%i') end as ultimo_acesso,
		u.id
	FROM 
		admin as u,
		grupo_admin as g
	WHERE 
		u.codprojeto = ".CODPROJETO." 
		and g.id = u.cod_grupo_admin 
		and g.ativo = 1 
		and u.ativo = 1 
	order by 
		u.ultimo_acesso DESC;";
$r->txtItem = "
	<tr>
		<td>#nome</td>
		<td>#grupo</td>
		<td>#ultimo_acesso</td>
		<td class=\"#status status\">#status</td>
		<td class=\"td-controls\">
			<a class=\"controle\" title=\"Editar\" href=\"/admin/?pg=" . $page->page . "Editar&id=#id\"><img src=\"/admin/img/edt.png\" /></a>
		</td>
	</tr>
";
$r->exec();

$page->table = true;
$page->table_header = "<th>Descri&ccedil;&atilde;o</th><th>Tipo</th><th>&Uacute;ltimo Acesso</th><th class=\"status\">Status</th>";
$page->table_content = $r->html;

$page->render();

?>
