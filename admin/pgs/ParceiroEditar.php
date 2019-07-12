<?php

// valida permissão
if( !in_array( "parceiros", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$menu_destaque = "parceiros";

$p = new Parceiro();

if( isset($_GET["id"]) ){
	$p->id = $_GET["id"];
	if($p->get()){
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
		
			$clone = clone $p;

			$p->nome = $_POST["nome"];
			$p->link = $_POST["link"];
			$p->intro = $_POST["intro"];
			$p->texto = $_POST["texto"];
			
			if ( $_FILES["foto"]["error"] == 0 ){
				$img_name = gerar_hash();
				$p->foto = Upload::salvaArq( "parceiros/" . $img_name, $_FILES["foto"] );
				Imagem::MiniaturaNaMedida( "../".$p->foto, LOCAL_CAPA_HEIGHT, LOCAL_CAPA_WIDTH );
			}
			
			if($p->atualizar()){
				LogAdmin::_salvar( "Parceiro Editado", "Parceiro" , $admin->id, json_encode( $clone ), json_encode( $p ) );
				addOnloadScript("message('Atualizado com sucesso.','sucess');");
			} else {
				LogAdmin::_salvar( "Erro ao editar Parceiro", "Parceiro" , $admin->id, json_encode( $clone ), json_encode( $p ) );
				addOnloadScript("message('Erro ao atualizar.','error');");
			}
		}
	}
}

$page = new StdAdminPage();

$page->title = "Cadastrar Parceiro";
$page->page = "Parceiro";
$page->back_link = true;
$page->title_back = "Locais";

$page->form = true;
$page->form_fields = array(
	array( "value" => $p->nome, "name" => "nome", "label" => "Parceiro *", "required" => true, "autofocus" => true ),
	array( "label" => "Imagem Atual", "type" => "image-view", "value" => ( "/" . $p->foto ) ),
	array( "name" => "foto", "label" => "Imagem (".PARCEIRO_WIDTH."x".PARCEIRO_HEIGHT.")", "type" => "file" ),
	array( "value" => $p->link, "name" => "link", "label" => "Link *" ),
	array( "value" => $p->intro, "name" => "intro", "label" => "Intro *", "type" => "textarea", "required" => true ),
	array( "value" => $p->texto, "name" => "texto", "label" => "Texto *", "type" => "textarea", "required" => true )
);
$page->render();

?>