<?php

// valida permissão
if( !in_array( "newsletter", $permissoes_admin ) ){
	return;
}

$r = new Repeater();
$r->campos = "nome;email;id;data_cancelamento;data";
$r->sql = "
	SELECT 
		id, 
		nome, 
		email, 
		DATE_FORMAT( datacadastro, '%k:%i - %d/%m/%Y' ) as data,
		case 
			when data_cancelamento is null then ''
			else DATE_FORMAT(data_cancelamento, '%k:%i - %d/%m/%Y') 
		end as data_cancelamento
	FROM 
		newsletter 
	WHERE 
		codprojeto = ".CODPROJETO." 
		AND ativo = 1 
		ORDER BY 
			email ASC;";
$r->txtVazio = "";

$tipo = $_GET["tipo"];

if($tipo == "txt"){
	$r->txtItem = "#nome - #email
";
} else if($tipo == "csv") {
	$r->txtItem = "#nome;#email;#data;#data_cancelamento
";
}
$r->exec();

header("Content-Type: application/save");
header("Content-Disposition: attachment; filename=newsletter-".date("d-m-y").".".$tipo);
echo $r->html;
flush();

?>