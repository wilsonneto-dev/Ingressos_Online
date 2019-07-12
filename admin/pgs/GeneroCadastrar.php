<?php
// valida permissão
if( !in_array( "generos", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$menu_destaque = "generos";

$p = new Genero();

if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
	
	$p->descricao = $_POST["descricao"];
	$p->nome = $_POST["nome"];

	SEO::id_url( $p->nome, $p );

	if( $p->cadastrar() ){
		LogAdmin::_salvar( "Genero \"$p->nome\" cadastrada", "Genero" , $admin->id , "", json_encode( $p ) );
		addOnloadScript("message('Cadastrado com sucesso.','sucess');");
	} else {
		LogAdmin::_salvar( "Erro ao cadastrar categoria", "Genero" , $admin->id , "", json_encode( $p ) );
		addOnloadScript("message('Ocorreu um erro ao cadastrar.','error');");
	}
}

$p = new Genero();

$page = new StdAdminPage();

$page->title = "Cadastrar Genero";
$page->page = "Genero";
$page->back_link = true;
$page->title_back = "Generos";

$page->form = true;
$page->form_fields = array(
	array( "value" => $p->nome, "name" => "nome", "label" => "Genero *", "required" => true, "autofocus" => true ),
	array( "value" => $p->descricao, "name" => "descricao", "label" => "Descri&ccedil;&atilde;o *", "required" => true, "autofocus" => true )
);

$page->render();


?>