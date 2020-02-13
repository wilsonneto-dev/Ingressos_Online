<?php 

// valida permissão
if( !in_array( "usuarios", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$menu_destaque = "usuarios";

$page = new StdAdminPage();
$page->page = "Usuario";

$r = new Repeater();
$r->campos = "nome;email;cadastro_t;acesso_t;ultimo_pedido_t;cadastro;id;acesso;ultimo_pedido;faturamento;fat6m;qtd_pedidos;ped6m";
$r->sql = "
SELECT 
	concat( u.nome, ' ', u.sobrenome ) as nome,
	u.email,
	u.id,
	DATE_FORMAT(u.datacadastro, '%d/%m/%y') as cadastro,
	DATE_FORMAT(u.ultimo_acesso, '%d/%m/%y') as acesso,
	DATE_FORMAT(max(p.data), '%d/%m/%y') as ultimo_pedido,
	DATE_FORMAT(u.datacadastro, '%d/%m/%Y %h:%i') as cadastro_t,
	DATE_FORMAT(u.ultimo_acesso, '%d/%m/%Y %h:%i') as acesso_t,
	DATE_FORMAT(max(p.data), '%d/%m/%Y %h:%i') as ultimo_pedido_t,
	ifnull( sum(p.valor_pedido) , 0) as faturamento,
	sum(
		case when p.data > DATE_ADD( CURRENT_TIMESTAMP, interval -6 month ) 
		then p.valor_pedido
		else 0 end
	) as fat6m,
	count(p.id) as qtd_pedidos,
	count(
		case when p.data > DATE_ADD( CURRENT_TIMESTAMP, interval -6 month ) 
		then p.id
		else null end
	) as ped6m
FROM usuario u
	left join pedido p on u.id = p.cod_usuario and p.cod_status in ( 3, 4 )
WHERE 
	u.codprojeto = 1 
	and u.ativo = 1 
group by 
	u.id
order by 
	u.datacadastro DESC;";
$r->txtItem = "
	<tr>
		<td>#id</td>
		<td>#nome</td>
		<td>#email</td>
		<td><span title=\"#cadastro_t\">#cadastro</span></td>
		<td><span title=\"#acesso_t\">#acesso</span></td>
		<td><span title=\"#ultimo_pedido_t\">#ultimo_pedido</span></td>
		<td>#faturamento</td>
		<td>#fat6m</td>
		<td>#qtd_pedidos</td>
		<td>#ped6m</td>
		<td class=\"td-controls\">
			<a class=\"controle\" title=\"Detalhes\" href=\"/admin/?pg=" . $page->page . "Detalhes&cod=#id\"><img heigth=\"18\" src=\"/admin/img/search.png\" /></a>
			<a class=\"controle\" title=\"Editar\" href=\"/admin/?pg=" . $page->page . "Editar&id=#id\"><img src=\"/admin/img/edt.png\" /></a>
		</td>
	</tr>
";
$r->exec();

$r_total = new Repeater();
$r_total->campos = "qtd";
$r_total->sql = "
	SELECT 
		count(*) as qtd
	FROM 
		usuario 
	WHERE 
		codprojeto = ".CODPROJETO." 
		and ativo = 1";
$r_total->txtItem = "#qtd";
$r_total->exec();

$page->table = true;
$page->table_header = "<th>#</th><th>Nome</th><th>E-mail</th><th>Cadastro</th><th>&Uacute;lt. Acesso</th><th>&Uacute;lt. Ped.</th><th>Fat</th><th>Fat 6m</th><th>Peds</th><th>Peds 6m</th>";
$page->table_content = $r->html;

$page->cadastrar = false;

$page->title = "Usu&aacute;rios <small>( total:".$r_total->html." )</small>";


$page->render();

?>
