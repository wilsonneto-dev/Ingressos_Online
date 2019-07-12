<?php
// valida permissão
if( !in_array( "promoters", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$menu_destaque = "promoters";

$p = new Promoter();

if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
	
	$p->razao_social = $_POST["razao_social"];
	$p->responsavel = $_POST["responsavel"];
	$p->email = $_POST["email"];
	$p->senha = $_POST["senha"];

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
	
	if( $p->cadastrar() ){
		LogAdmin::_salvar( "Promoter Cadastrado", "Promoter" , $admin->id , "", json_encode( $p ) );
		addOnloadScript("message('Cadastrado com sucesso.','sucess');");
	} else {
		LogAdmin::_salvar( "Erro ao cadastrar Promoter", "Promoter" , $admin->id , "", json_encode( $p ) );
		addOnloadScript("message('Ocorreu um erro ao cadastrar.','error');");
	}
}

$p = new Promoter();
$p->bloqueado = 0;

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
	array( "name" => "imagem", "label" => "Imagem", "type" => "file", "required" => true ),
	array( "value" => $p->email, "name" => "email", "label" => "E-mail", "required" => true ),
	array( "value" => $p->senha, "name" => "senha", "label" => "Pass", "required" => true),
	array( "value" => $p->bloqueado, "name" => "bloqueado", "label" => "Bloqueado", "type" => "checkbox" )
);

$page->render();


?>