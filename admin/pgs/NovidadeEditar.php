<?php

// valida permissão
if( !in_array( "novidades", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$menu_destaque = "novidades";

$p = new Novidade();

if( isset($_GET["id"]) ){
	$p->id = $_GET["id"];
	if($p->get()){
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
		
			$clone = clone $p;

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

			if($p->atualizar()){
				LogAdmin::_salvar( "Novidade Editado", "Novidade" , $admin->id, json_encode( $clone ), json_encode( $p ) );
				addOnloadScript("message('Atualizado com sucesso.','sucess');");
			} else {
				LogAdmin::_salvar( "Erro ao editar Novidade", "Novidade" , $admin->id, json_encode( $clone ), json_encode( $p ) );
				addOnloadScript("message('Erro ao atualizar.','error');");
			}
		}
	}
}

$page = new StdAdminPage();

$page->title = "Cadastrar Novidade";
$page->page = "Novidade";
$page->back_link = true;
$page->title_back = "Novidades";

$page->form = true;
$page->form_fields = array(
	array( "value" => $p->titulo, "name" => "titulo", "label" => "Novidade *", "required" => true, "autofocus" => true ),
	array( "value" => $p->intro, "name" => "intro", "label" => "Intro *", "type" => "textarea", "required" => true ),
	array( "label" => "Imagem Atual", "type" => "image-view", "value" => ( "/" . $p->imagem ) ),
	array( "name" => "imagem", "label" => "Imagem (".NOVIDADE_WIDTH."x".NOVIDADE_HEIGHT.")", "type" => "file", "required" => true ),
	array( "label" => "Miniatura Atual", "type" => "image-view", "value" => ( "/" . $p->thumb ) ),
	array( "name" => "thumb", "label" => "Miniatura (".NOVIDADE_THUMB_WIDTH."x".NOVIDADE_THUMB_HEIGHT.")", "type" => "file", "required" => true ),
	array( "value" => $p->texto, "name" => "texto", "label" => "Texto *", "type" => "editor", "required" => true )
);
$page->render();

?>