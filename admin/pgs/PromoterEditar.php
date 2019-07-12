<?php

// valida permissão
if( !in_array( "promoters", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$menu_destaque = "promoters";

$p = new Promoter();

if( isset($_GET["id"]) ){
	$p->id = $_GET["id"];
	if($p->get()){
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
		
			$clone = clone $p;

			$p->razao_social = $_POST["razao_social"];
			$p->responsavel = $_POST["responsavel"];
			$p->email = $_POST["email"];
			
			$p->senha = $_POST["senha"];

			if( $p->senha != "" ){
				$p->atualizar_senha();
			}

			$bloqueado = 0;
			if ( isset( $_POST["bloqueado"] ) ) 
				if( $_POST["bloqueado"] == "ativo" ) 
					$bloqueado = 1; 
			
			$p->bloqueado = $bloqueado;

			if ( $_FILES["imagem"]["error"] == 0 ){
				$img_name = gerar_hash();
				$p->imagem = Upload::salvaArq( "promoters/" . $img_name, $_FILES["imagem"] );
				Imagem::MiniaturaProporcional( "../".$p->imagem, ADMIN_HEIGHT, ADMIN_WIDTH );
			}
			
			if($p->atualizar()){
				LogAdmin::_salvar( "Promoter Editado", "Promoter" , $admin->id, json_encode( $clone ), json_encode( $p ) );
				addOnloadScript("message('Atualizado com sucesso.','sucess');");
			} else {
				LogAdmin::_salvar( "Erro ao editar banner topo", "Promoter" , $admin->id, json_encode( $clone ), json_encode( $p ) );
				addOnloadScript("message('Erro ao atualizar.','error');");
			}
		}
	}
}

$page = new StdAdminPage();

$page->title = "Cadastrar Promoter";
$page->page = "Promoter";
$page->back_link = true;
$page->title_back = "Promoters";

$page->form = true;
$page->form_fields = array(
	array( "value" => $p->razao_social, "name" => "razao_social", "label" => "Empresa *", "required" => true, "autofocus" => true ),
	array( "value" => $p->responsavel, "name" => "responsavel", "label" => "Respons&aacute;vel *", "required" => true ),
	array( "label" => "Imagem Atual", "type" => "image-view", "value" => ( "/" . $p->imagem ) ),
	array( "name" => "imagem", "label" => "Imagem", "type" => "file" ),
	array( "value" => $p->email, "name" => "email", "label" => "E-mail", "required" => true ),
	array( "name" => "senha", "label" => "Alterar Senha?" ),
	array( "value" => $p->bloqueado, "name" => "bloqueado", "label" => "Bloqueado", "type" => "checkbox" )
);
$page->render();

?>