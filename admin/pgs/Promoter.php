<?php 

// valida permissão
if( !in_array( "promoters", $permissoes_admin ) ){
	include("pgs/404.pg.php") ;
	return;
}

$menu_destaque = "promoters";

$page = new StdAdminPage();
$page->title = "Promoters";
$page->page = "Promoter";

$r = new Repeater();
$r->campos = "razao_social;responsavel;id;status;ultimo_acesso";
$r->sql = "
	SELECT 
		razao_social,
		responsavel, 
		case when bloqueado = 1 then 'Bloqueado' else 'Ativo' end as status, 
		case when ultimo_acesso is null then '' else date_format(ultimo_acesso, '%d/%m/%y %H:%i') end as ultimo_acesso ,
		id
	FROM 
		promoter
	WHERE 
		codprojeto = ".CODPROJETO." 
		and ativo = 1 
	order by 
		ultimo_acesso DESC;";
$r->txtItem = "
	<tr>
		<td>#razao_social</td>
		<td>#responsavel</td>
		<td>#ultimo_acesso</td>
		<td class=\"#status status\">#status</td>
		<td class=\"td-controls\">
			<a class=\"controle\" title=\"Editar\" href=\"/admin/?pg=" . $page->page . "Editar&id=#id\"><img src=\"/admin/img/edt.png\" /></a>
		</td>
	</tr>
";
$r->exec();

$page->table = true;
$page->table_header = "<th>Empresa</th><th>Responsavel</th><th>&Uacute;ltimo Acesso</th><th class=\"status\">Status</th>";
$page->table_content = $r->html;

$page->render();

?>
