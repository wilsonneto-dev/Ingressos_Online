<?php
// valida permissão
if( !in_array( "cidades", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$menu_destaque = "cidades";

$p = new Cidade();

if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
	
	$p->uf = $_POST["uf"];
	$p->nome = $_POST["nome"];
	
	if( $p->cadastrar() ){
		LogAdmin::_salvar( "Cidade \"$p->nome\" cadastrada", "Cidade" , $admin->id , "", json_encode( $p ) );
		addOnloadScript("message('Cadastrado com sucesso.','sucess');");
	} else {
		LogAdmin::_salvar( "Erro ao cadastrar categoria", "Cidade" , $admin->id , "", json_encode( $p ) );
		addOnloadScript("message('Ocorreu um erro ao cadastrar.','error');");
	}
}

$p = new Cidade();

$page = new StdAdminPage();

$page->title = "Cadastrar Cidade";
$page->page = "Cidade";
$page->back_link = true;
$page->title_back = "Cidades";

$page->form = true;
$page->form_fields = array(
	array( "value" => $p->nome, "name" => "nome", "label" => "Cidade *", "required" => true, "autofocus" => true ),
	array( "value" => $p->uf, "name" => "uf", "label" => "UF *", "required" => true )
);

$page->render();


?>