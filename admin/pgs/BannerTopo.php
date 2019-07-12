<?php 

// valida permissão
if( !in_array( "banners_topo", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$menu_destaque = "banners_topo";

$page = new StdAdminPage();
$page->title = "Banners";
$page->page = "BannerTopo";

$r = new Repeater();
$r->campos = "descricao;imagem;id;status;tip";
$r->sql = "
	SELECT 
		imagem, 
		descricao, 
		getstatus(entrar_em, data_sair, visivel) as status,
		CONCAT( DATE_FORMAT( entrar_em, '%d/%m/%Y' ) , ' - ' , DATE_FORMAT( data_sair, '%d/%m/%Y' ) ) as tip, 
		id 
	FROM 
		banner_topo 
	WHERE 
		codprojeto = ".CODPROJETO." AND ativo = 1 order by datacadastro DESC;";
$r->txtItem = "
	<tr>
		<td>#descricao</td>
		<td class=\"#status status\" title=\"#tip\">#status</td>
		<td class=\"td-controls\">
			<a class=\"controle\" title=\"Editar\" href=\"/admin/?pg=" . $page->page . "Editar&id=#id\"><img src=\"/admin/img/edt.png\" /></a>
			<a class=\"controle\" title=\"Ver Imagem\" href=\"#imagem\" onclick=\" $.fancybox.open( { href : '/#imagem' } ); return false;\"><img src=\"/admin/img/img.png\" /></a>
			<a class=\"controle\" title=\"Excluir\" onclick=\"return confirm('Excluir?');\" href=\"/admin/?pg=" . $page->page . "Excluir&id=#id\"><img src=\"/admin/img/del.png\" /></a>
		</td>
	</tr>
";
$r->exec();

$page->table = true;
$page->table_header = "<th>Descri&ccedil;&atilde;o</th><th class=\"status\">Status</th>";
$page->table_content = $r->html;

$page->render();

?>
