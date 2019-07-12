<?php

// valida permissão
if( !in_array( "duvidas", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$menu_destaque = "duvidas";

$p = new Duvida();

if( isset( $_GET["id"] ) ){
	$p->id = $_GET[ "id" ];
	if($p->get()){
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
		
			$clone = clone $p;

			$p->texto = str_replace( "", "", $_POST["texto"] ); // $_POST["texto"];
			$p->titulo = $_POST["titulo"];
			$p->ordem = $_POST["ordem"];
		
			if($p->atualizar()){
				LogAdmin::_salvar( "Duvida Editada", "Duvida" , $admin->id, json_encode( $clone ), json_encode( $p ) );
				addOnloadScript("message('Atualizado com sucesso.','sucess');");
			} else {
				LogAdmin::_salvar( "Erro ao editar genero", "Duvida" , $admin->id, json_encode( $clone ), json_encode( $p ) );
				addOnloadScript("message('Erro ao atualizar.','error');");
			}
		}
	}
}

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