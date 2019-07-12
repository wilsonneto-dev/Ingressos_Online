<?php 

// valida permissão
// print_r($permissoes_admin);

if( !in_array( "procurar_pedido", $permissoes_admin ) ){
	include("pgs/404.pg.php") ;
	return;
}

$menu_destaque = "procurar_pedido";

$page = new StdAdminPage();
$page->title = "Procurar por Pedido";
$page->page = "Pedidos";
$page->form = false;
$page->cadastrar = false;

$q = "";
if( isset($_POST["q"]) ){
	$q = anti_sqli($_POST["q"]);
}

$r = new Repeater();
$r->campos = "id;cod_pedido;data;nome;status;cod_status;evento;valor_pedido";
$r->sql = "
	select 
		p.id,
		p.codigo as cod_pedido,
		concat(u.nome, ' ', u.sobrenome) as nome,
		DATE_FORMAT( p.data, '%d/%m %H:%i' ) as data,
		ev.titulo as evento,
		p.status,
		p.cod_status,
		p.valor_pedido
	from pedido as p
	inner join evento ev on ev.id = p.cod_evento
	inner join usuario u on u.id = p.cod_usuario 
	where 
		p.codprojeto = ".CODPROJETO." 
		and (
			( concat( u.nome, ' ', u.sobrenome ) ) like '%$q%'
			or u.cpf like '%$q%'
			or u.id like '%$q%'
			or u.email like '%$q%'
			or ev.titulo like '%$q%'
			or p.codigo like '%$q%'
			or p.transacao like '%$q%'
		)
	order by 
		p.data desc;
";
$r->txtItem = "
	<tr class=\"pedido_row\">
		<td>#id</td>
		<td>#cod_pedido</td>
		<td>#nome</td>
		<td>#data</td>
		<td>#evento</td>
		<td class=\"status_#cod_status\">#status</td>
		<td>#valor_pedido</td>
		<td class=\"td-controls\">
			<a class=\"controle\" title=\"Detalhes\" href=\"/admin/?pg=" . $page->page . "Detalhes&cod=#cod_pedido\"><img heigth=\"18\" src=\"/admin/img/search.png\" /></a>
		</td>
	</tr>
";
if($q != ""){
	$r->exec();

	$page->table = true;
	$page->table_header = "
		<th>#</th>
		<th>C&oacute;d</th>
		<th>Nome</th>
		<th>Data</th>
		<th>Evento</th>
		<th>Status</th>
		<th>Valor</th>
	";
	$page->table_content = $r->html;

}

$page->form = true;
$page->form_fields = array(
	array( "value" => $q, "name" => "q", "label" => "Buscar por", "required" => true )
);

$page->form_button_text = "Buscar";

$page->render();

?>
