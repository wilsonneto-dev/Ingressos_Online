<?php 

// valida permissão
// print_r($permissoes_admin);

if( !in_array( "pedidos", $permissoes_admin ) ){
	include("pgs/404.pg.php") ;
	return;
}

$menu_destaque = "pedidos";

$page = new StdAdminPage();
$page->title = "Pedidos <small>( &uacute;ltimos 5 dias )</small>";
$page->page = "Pedidos";
$page->form = false;
$page->cadastrar = false;

$r = new Repeater();
$r->campos = "id;codigo;data;nome;status;cod_status;evento;valor_pedido;cod_usuario;ref";
$r->sql = "
select 
	p.id,
	p.codigo,
	concat( u.nome , ' ', u.sobrenome ) as nome,
	u.id as cod_usuario,
	DATE_FORMAT( p.data, '%d/%m %H:%i' ) as data,
	ev.titulo as evento,
	p.status,
	p.cod_status,
	p.ref,
	p.valor_pedido
from pedido p
inner join usuario u
	on p.cod_usuario = u.id
inner join evento ev
	on ev.id = p.cod_evento
where
	p.data > DATE_ADD( CURRENT_TIMESTAMP, interval -5 day ) 
order by 
	data desc;
";
$r->txtItem = "
	<tr class=\"pedido_row\">
		<td>#id</td>
		<td>#codigo</td>
		<td>#nome <small>( #cod_usuario )</small> </td>
		<td>#data</td>
		<td>#evento</td>
		<td class=\"status_#cod_status\">#status</td>
		<td>#valor_pedido</td>
		<td>#ref</td>
		<td class=\"td-controls\">
			<a class=\"controle\" title=\"Detalhes\" href=\"/admin/?pg=" . $page->page . "Detalhes&cod=#codigo\"><img heigth=\"18\" src=\"/admin/img/search.png\" /></a>
		</td>
	</tr>
";
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
	<th>Camp.</th>
";
$page->table_content = $r->html;

$page->render();

?>
