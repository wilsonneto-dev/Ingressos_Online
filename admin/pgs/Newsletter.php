<?php 

// valida permissão
if( !in_array( "newsletter", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$r_total = new Repeater();
$r_total->campos = "total";
$r_total->sql = "
	SELECT 
		count( distinct email ) as total
	FROM 
		newsletter 
	WHERE 
		codprojeto = ".CODPROJETO." 
		and ativo = 1 
	order by 
		datacadastro DESC;";
$r_total->txtItem = "#total";
$r_total->exec();


$menu_destaque = "newsletter";

$page = new StdAdminPage();
$page->title = "Newsletter <small>(Listagem com os últimos 30 dias - Total: $r_total->html)</small>";
$page->page = "Newsletter";

$r = new Repeater();
$r->campos = "nome;email;classe;id;data";
$r->sql = "
	SELECT 
		nome,
		email,
		id,
		DATE_FORMAT(datacadastro, '%d/%m/%Y %h:%i') as data,
		case when data_cancelamento is null then 'inativo' else 'ativo' end as classe
	FROM 
		newsletter 
	WHERE 
		codprojeto = ".CODPROJETO." 
		and ativo = 1 
		and datacadastro > ( CURDATE() - INTERVAL 30 DAY )
	order by 
		datacadastro DESC;";
$r->txtItem = "
	<tr>
		<td>#nome</td>
		<td>#email</td>
		<td>#data</td>
		<td class=\"td-controls\">
		</td>
	</tr>
";
$r->exec();

$page->table = true;
$page->table_header = "<th>Nome</th><th>E-mail</th><th>Data</th>";
$page->table_content = $r->html;

$page->cadastrar = false;

$page->botoes_extras[] = array( 
	'url' => '/admin/dinamico.php?src=NewsletterExportar&tipo=csv', 
	'legenda' => 'Exportar .CSV',
	'target' => '_blank'
);

$page->render();

?>
