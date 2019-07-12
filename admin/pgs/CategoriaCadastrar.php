<?php
// valida permissão
if( !in_array( "categorias", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$menu_destaque = "categorias";

$p = new Categoria();

if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
	
	$p->descricao = $_POST["descricao"];
	$p->nome = $_POST["nome"];
	
	SEO::id_url( $p->nome, $p );

	if( $p->cadastrar() ){
		LogAdmin::_salvar( "Categoria \"$p->nome\" cadastrada", "Categoria" , $admin->id , "", json_encode( $p ) );
		addOnloadScript("message('Cadastrado com sucesso.','sucess');");
	} else {
		LogAdmin::_salvar( "Erro ao cadastrar categoria", "Categoria" , $admin->id , "", json_encode( $p ) );
		addOnloadScript("message('Ocorreu um erro ao cadastrar.','error');");
	}
}

$p = new Categoria();

$page = new StdAdminPage();

$page->title = "Cadastrar Categoria";
$page->page = "Categoria";
$page->back_link = true;
$page->title_back = "Categorias";

$page->form = true;
$page->form_fields = array(
	array( "value" => $p->nome, "name" => "nome", "label" => "Categoria *", "required" => true, "autofocus" => true ),
	array( "value" => $p->descricao, "name" => "descricao", "label" => "Descri&ccedil;&atilde;o *", "required" => true, "autofocus" => true )
);

$page->render();


?>