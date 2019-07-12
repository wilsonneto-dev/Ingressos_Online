<?php
// valida permissão
if( !in_array( "duvidas", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$menu_destaque = "duvidas";

$p = new Duvida();

if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
	
	$p->texto = $_POST["texto"];
	$p->titulo = $_POST["titulo"];
	$p->ordem = $_POST["ordem"];
	
	if( $p->cadastrar() ){
		LogAdmin::_salvar( "Duvida \"$p->titulo\" cadastrada", "Duvida" , $admin->id , "", json_encode( $p ) );
		addOnloadScript("message('Cadastrado com sucesso.','sucess');");
	} else {
		LogAdmin::_salvar( "Erro ao cadastrar categoria", "Duvida" , $admin->id , "", json_encode( $p ) );
		addOnloadScript("message('Ocorreu um erro ao cadastrar.','error');");
	}
}

$p = new Duvida();

$page = new StdAdminPage();

$page->title = "Cadastrar Duvida";
$page->page = "Duvida";
$page->back_link = true;
$page->title_back = "Duvidas";

$page->form = true;
$page->form_fields = array(
	array( "value" => $p->titulo, "name" => "titulo", "label" => "Duvida *", "required" => true, "autofocus" => true ),
	array( "value" => $p->ordem, "name" => "ordem", "label" => "Ordem *", "required" => true, "type" => "num" ),
	array( "value" => $p->texto, "name" => "texto", "type" => "editor", "label" => "Descri&ccedil;&atilde;o *", "required" => true )
);

$page->render();


?>