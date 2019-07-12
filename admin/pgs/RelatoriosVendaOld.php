<?php 

// valida permissão
// print_r($permissoes_admin);

if( !in_array( "relatorios_vendas_old", $permissoes_admin ) ){
	include("pgs/404.pg.php") ;
	return;
}

$tipo_relatorio = "";

$menu_destaque = "relatorios_vendas";

$page = new StdAdminPage();
$page->title = "Relat&oacute;rios de Vendas <small>(Padr&atilde;o Antigo)</small>";
$page->page = "Relatorio";
$page->form = false;
$page->cadastrar = false;

$r = new Repeater();
$r->campos = "id;descricao;link";
$r->sql = "
select 
	r.id,
	r.descricao,
	case when r.filtro = 1 
		then concat( '?pg=RelatorioFiltro&rpt=', r.id ) 
		else concat( 'Relatorio.php?rpt=', r.id ) 
	end as link
from 
	relatorio_admin r 
	inner join grupo_relatorio_admin gr on gr.cod_relatorio_admin = r.id
	and tipo = 'Venda'
where
	gr.cod_grupo_admin = ".$grupo_admin->id."
	and r.ativo = 1
	and codprojeto = ".CODPROJETO." 
order by
	r.posicao asc;
";
$r->txtItem = "
	<tr>
		<td>#id</td>
		<td>#descricao</td>
		<td class=\"td-controls\">
			<a class=\"controle\" title=\"Gerar\" href=\"#link\" target=\"_blank\"><img heigth=\"18\" src=\"/admin/img/search.png\" /></a>
		</td>
	</tr>
";
$r->exec();

$page->table = true;
$page->table_header = "
	<th>#</th>
	<th>Relat&oacute;rio</th>
";
$page->table_content = $r->html;

$page->render();

?>
