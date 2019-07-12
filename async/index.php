<?php
	
include_once '../php/config/constantes.php';
if(isset($_GET["uf"])){
	
	$uf = anti_sqli( $_GET["uf"] );

	$r_cidades = new Repeater();
$r_cidades->campos = "codigo_cidade;cidade";
$r_cidades->sql = "
	SELECT
		codigo_cidade,
		cidade
	FROM 
		brasil_cidade
	WHERE 
		uf = '".$uf."' 
	ORDER BY 
		cidade ASC";
$r_cidades->txtVazio = "";
$r_cidades->txtItem = '<option value="#codigo_cidade">#cidade</option>';

$r_cidades->exec();
echo $r_cidades->html;

}

?>
