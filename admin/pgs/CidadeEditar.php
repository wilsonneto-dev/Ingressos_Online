<?php

// valida permissão
if( !in_array( "cidades", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$menu_destaque = "cidades";

$p = new Cidade();

if( isset( $_GET["id"] ) ){
	$p->id = $_GET[ "id" ];
	if($p->get()){
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
		
			$clone = clone $p;

			$p->uf = $_POST["uf"];
			$p->nome = $_POST["nome"];
		
			if($p->atualizar()){
				LogAdmin::_salvar( "Cidade Editada", "Cidade" , $admin->id, json_encode( $clone ), json_encode( $p ) );
				addOnloadScript("message('Atualizado com sucesso.','sucess');");
			} else {
				LogAdmin::_salvar( "Erro ao editar genero", "Cidade" , $admin->id, json_encode( $clone ), json_encode( $p ) );
				addOnloadScript("message('Erro ao atualizar.','error');");
			}
		}
	}
}

$page = new StdAdminPage();

$page->title = "Cadastrar Cidade";
$page->page = "Cidade";
$page->back_link = true;
$page->title_back = "Cidades";

$page->form = true;
$page->form_fields = array(
	array( "value" => $p->nome, "name" => "nome", "label" => "Cidade *", "required" => true, "autofocus" => true ),
	array( "value" => $p->uf, "name" => "uf", "label" => "UF *", "required" => true, "autofocus" => true )
);
$page->render();

?>