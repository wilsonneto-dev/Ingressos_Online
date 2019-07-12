<?php
// valida permissão
if( !in_array( "locais", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$menu_destaque = "locais";

$p = new Local();

if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
	
	$p->cod_cidade = $_POST["cod_cidade"];
	$p->nome = $_POST["nome"];
	$p->descricao = $_POST["descricao"];
	$p->endereco = $_POST["endereco"];
	$p->localizacao_latitude = $_POST["localizacao_latitude"];
	$p->localizacao_longitude = $_POST["localizacao_longitude"];
	
	SEO::id_url( $p->nome, $p );

	if ( $_FILES["capa"]["error"] == 0 ){
		$img_name = gerar_hash();
		$p->capa = Upload::salvaArq( "locais/" . $img_name, $_FILES["capa"] );
		Imagem::MiniaturaNaMedida( "../".$p->capa, LOCAL_CAPA_HEIGHT, LOCAL_CAPA_WIDTH );
	}
	if ( $_FILES["logo"]["error"] == 0 ){
		$img_name = gerar_hash();
		$p->logo = Upload::salvaArq( "locais/" . $img_name, $_FILES["logo"] );
		Imagem::MiniaturaNaMedida( "../".$p->logo, LOCAL_LOGO_HEIGHT, LOCAL_LOGO_WIDTH );
	}
	
	if( $p->cadastrar() ){
		LogAdmin::_salvar( "Local Cadastrado", "Local" , $admin->id , "", json_encode( $p ) );
		addOnloadScript("message('Cadastrado com sucesso.','sucess');");
	} else {
		LogAdmin::_salvar( "Erro ao cadastrar Local", "Local" , $admin->id , "", json_encode( $p ) );
		addOnloadScript("message('Ocorreu um erro ao cadastrar.','error');");
	}
}

$p = new Local();

$page = new StdAdminPage();

$page->title = "Cadastrar Local";
$page->page = "Local";
$page->back_link = true;
$page->title_back = "Locais";

$sel_tipo = new SqlSelect("SELECT id as valor, concat( nome, ' / ', uf ) as texto FROM cidade WHERE codprojeto = ".CODPROJETO." AND ativo = 1 ORDER BY nome ASC"); 
$sel_tipo->nome = "cod_cidade";
if( $p != "" ) $sel_tipo->valorSelecionado = $p->cod_cidade; 
$sel_tipo->exec();

$page->form = true;
$page->form_fields = array(
	array( "label" => "Cidade", "type" => "html-field", "html" => $sel_tipo->html ),
	array( "value" => $p->nome, "name" => "nome", "label" => "Local *", "required" => true, "autofocus" => true ),
	array( "label" => "Capa Atual", "type" => "image-view", "value" => ( "/" . $p->capa ) ),
	array( "name" => "capa", "label" => "Imagem (".LOCAL_CAPA_WIDTH."x".LOCAL_CAPA_HEIGHT.")", "type" => "file", "required" => true ),
	array( "label" => "Logo Atual", "type" => "image-view", "value" => ( "/" . $p->logo ) ),
	array( "name" => "logo", "label" => "Logo (".LOCAL_LOGO_WIDTH."x".LOCAL_LOGO_HEIGHT.")", "type" => "file", "required" => true ),
	array( "value" => $p->descricao, "name" => "descricao", "label" => "Descri&ccedil;&atilde;o *", "type" => "editor", "required" => true ),
	array( "value" => $p->endereco, "name" => "endereco", "label" => "Endere&ccedil;o *", "type" => "textarea", "required" => true ),
	array( "value" => ( $p->localizacao_latitude.";".$p->localizacao_longitude ), "name" => "localizacao", "label" => "Localiza&ccedil;&atilde;o", "type" => "location" )
);

$page->render();


?>