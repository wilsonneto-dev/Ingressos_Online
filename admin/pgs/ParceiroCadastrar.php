<?php
// valida permissão
if( !in_array( "parceiros", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$menu_destaque = "parceiros";

$p = new Parceiro();

if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
	
	$p->nome = $_POST["nome"];
	$p->link = $_POST["link"];
	$p->intro = $_POST["intro"];
	$p->texto = $_POST["texto"];
	
	if ( $_FILES["foto"]["error"] == 0 ){
		$img_name = gerar_hash();
		$p->foto = Upload::salvaArq( "parceiros/" . $img_name, $_FILES["foto"] );
		Imagem::MiniaturaNaMedida( "../".$p->foto, LOCAL_CAPA_HEIGHT, LOCAL_CAPA_WIDTH );
	}
	
	if( $p->cadastrar() ){
		LogAdmin::_salvar( "Parceiro Cadastrado", "Parceiro" , $admin->id , "", json_encode( $p ) );
		addOnloadScript("message('Cadastrado com sucesso.','sucess');");
	} else {
		LogAdmin::_salvar( "Erro ao cadastrar Parceiro", "Parceiro" , $admin->id , "", json_encode( $p ) );
		addOnloadScript("message('Ocorreu um erro ao cadastrar.','error');");
	}
}

$p = new Parceiro();

$page = new StdAdminPage();

$page->title = "Cadastrar Parceiro";
$page->page = "Parceiro";
$page->back_link = true;
$page->title_back = "Parceiros";

$page->form = true;
$page->form_fields = array(
	array( "value" => $p->nome, "name" => "nome", "label" => "Parceiro *", "required" => true, "autofocus" => true ),
	array( "label" => "Imagem Atual", "type" => "image-view", "value" => ( "/" . $p->foto ) ),
	array( "name" => "foto", "label" => "Imagem (".PARCEIRO_WIDTH."x".PARCEIRO_HEIGHT.")", "type" => "file", "required" => true ),
	array( "value" => $p->link, "name" => "link", "label" => "Link *" ),
	array( "value" => $p->intro, "name" => "intro", "label" => "Intro *", "type" => "textarea", "required" => true ),
	array( "value" => $p->texto, "name" => "texto", "label" => "Texto *", "type" => "textarea", "required" => true )
);

$page->render();


?>