<?php 

// valida permissão
if( !in_array( "banners", $permissoes_admin ) ){
	include("pgs/404.pg.php") ;
	return;
}

$menu_destaque = "banners";

$page = new StdAdminPage();
$page->title = "Banners";
$page->page = "Banner";

$r = new Repeater();
$r->campos = "descricao;tipo;imagem;id;status;tip";
$r->sql = "
	SELECT 
		t.nome as tipo,
		b.imagem, 
		b.descricao, 
		getstatus(b.entrar_em, b.data_sair, b.visivel) as status,
		CONCAT( DATE_FORMAT( b.entrar_em, '%d/%m/%Y' ) , ' - ' , DATE_FORMAT( b.data_sair, '%d/%m/%Y' ) ) as tip, 
		b.id 
	FROM 
		banner as b,
		banner_tipo t
	WHERE 
		b.cod_banner_tipo = t.id
		and b.codprojeto = ".CODPROJETO." 
		and t.ativo = 1 
		and b.ativo = 1 
	order by 
		datacadastro DESC;";
$r->txtItem = "
	<tr>
		<td>#descricao</td>
		<td>#tipo</td>
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
$page->table_header = "<th>Descri&ccedil;&atilde;o</th><th>Tipo</th><th class=\"status\">Status</th>";
$page->table_content = $r->html;

$page->render();

?>
