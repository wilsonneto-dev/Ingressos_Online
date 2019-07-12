<?php
// valida permissão
if( !in_array( "novidades", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$menu_destaque = "novidades";

$p = new Novidade();

if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
	
	$p->titulo = $_POST["titulo"];
	$p->intro = $_POST["intro"];
	$p->texto = $_POST["texto"];
	
	SEO::id_url( $p->nome, $p );

	if ( $_FILES["imagem"]["error"] == 0 ){
		$img_name = gerar_hash();
		$p->imagem = Upload::salvaArq( "novidades/" . $img_name, $_FILES["imagem"] );
		Imagem::MiniaturaNaMedida( "../".$p->imagem, NOVIDADE_HEIGHT, NOVIDADE_WIDTH );
	}
	if ( $_FILES["thumb"]["error"] == 0 ){
		$img_name = gerar_hash();
		$p->thumb = Upload::salvaArq( "novidades/" . $img_name, $_FILES["thumb"] );
		Imagem::MiniaturaNaMedida( "../".$p->thumb, NOVIDADE_THUMB_HEIGHT, NOVIDADE_THUMB_WIDTH );
	}
	
	if( $p->cadastrar() ){
		LogAdmin::_salvar( "Novidade CadastradA", "Novidade" , $admin->id , "", json_encode( $p ) );
		addOnloadScript("message('Cadastrado com sucesso.','sucess');");
	} else {
		LogAdmin::_salvar( "Erro ao cadastrar Novidade", "Novidade" , $admin->id , "", json_encode( $p ) );
		addOnloadScript("message('Ocorreu um erro ao cadastrar.','error');");
	}
}

$p = new Novidade();

$page = new StdAdminPage();

$page->title = "Cadastrar Novidade";
$page->page = "Novidade";
$page->back_link = true;
$page->title_back = "Novidades";

$page->form = true;
$page->form_fields = array(
	array( "value" => $p->titulo, "name" => "titulo", "label" => "Local *", "required" => true, "autofocus" => true ),
	array( "value" => $p->intro, "name" => "intro", "label" => "Intro *", "type" => "textarea", "required" => true ),
	array( "label" => "Imagem Atual", "type" => "image-view", "value" => ( "/" . $p->imagem ) ),
	array( "name" => "imagem", "label" => "Imagem (".NOVIDADE_WIDTH."x".NOVIDADE_HEIGHT.")", "type" => "file", "required" => true ),
	array( "label" => "Miniatura Atual", "type" => "image-view", "value" => ( "/" . $p->thumb ) ),
	array( "name" => "thumb", "label" => "Miniatura (".NOVIDADE_THUMB_WIDTH."x".NOVIDADE_THUMB_HEIGHT.")", "type" => "file", "required" => true ),
	array( "value" => $p->texto, "name" => "texto", "label" => "Texto *", "type" => "editor", "required" => true )
	
);

$page->render();


?>