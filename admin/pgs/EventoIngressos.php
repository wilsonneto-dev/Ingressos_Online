<?php 

// valida permissão
if( !in_array( "eventos", $permissoes_admin ) ){
	include("pgs/404.pg.php") ;
	return;
}

$p = new Evento();
$menu_destaque = "eventos";

// verifica se passou o id
if( !isset( $_GET["id"] ) ) {
	include("pgs/404.pg.php") ;
	return;
}

// verifica se o evento existe de fato
$p->id = $_GET["id"];
if(!$p->get()){
	include("pgs/404.pg.php") ;
	return;
}

$page = new StdAdminPage();
$page->title = "Ingressos do Evento: " . $p->titulo;
$page->page = "EventoIngressos";
$page->cadastrar_parametro = "evento=".$p->id;

$r = new Repeater();
$r->campos = "descricao;id;status;cod_evento;valor";
$r->sql = "
	SELECT 
		id,
		descricao,
		cod_evento,
		valor,
		getstatus( data_entrar, data_sair, visivel ) as status
	FROM 
		ingresso
	WHERE 
		ativo = 1 
		and cod_evento = '".str_replace("'", "", $p->id)."'
	order by 
		ordem asc;";
$r->txtItem = "
	<tr>
		<td>#descricao</td>
		<td>R$ #valor</td>
		<td class=\"#status status\">#status</td>
		<td class=\"td-controls\">
			<a class=\"controle\" title=\"Editar\" href=\"/admin/?pg=" . $page->page . "Editar&id=#id&evento=#cod_evento\"><img src=\"/admin/img/edt.png\" /></a>
			<a class=\"controle\" title=\"Excluir\" onclick=\"return confirm('Excluir?');\" href=\"/admin/?pg=" . $page->page . "Excluir&id=#id&evento=#cod_evento\"><img src=\"/admin/img/del.png\" /></a>
		</td>
	</tr>
";
$r->exec();

$page->table = true;
$page->table_header = "<th>Ingresso</th><th class=\"status\">Valor</th><th class=\"status\">Status</th>";
$page->table_content = $r->html;

$page->render();

?>
