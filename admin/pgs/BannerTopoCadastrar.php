<?php
// valida permissão
if( !in_array( "banners_topo", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$menu_destaque = "banners_topo";

$p = new BannerTopo();

if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
	
	$p->descricao = $_POST["descricao"];
	$p->botao = $_POST["botao"];
	$p->link = $_POST["link"];
	$p->ordem = $_POST["ordem"];
	if( trim($p->ordem) == "" ) $p->ordem = 10;

	$p->data_sair = data( $_POST["data_sair"] );
	$p->entrar_em = data( $_POST["entrar_em"] );

	$visivel = 0;
	if ( isset( $_POST["visivel"] ) ) 
		if( $_POST["visivel"] == "ativo" ) 
			$visivel = 1; 
	
	$p->visivel = $visivel;

	if ( $_FILES["imagem"]["error"] == 0 ){
		$img_name = gerar_hash();
		$p->imagem = Upload::salvaArq( "banners/" . $img_name, $_FILES["imagem"] );
		// sem thumbs -- Imagem::MiniaturaProporcional( "../".$p->imagem, BANNER_TOPO_HEIGHT, BANNER_TOPO_WIDTH );
	}
	
	if( $p->cadastrar() ){
		LogAdmin::_salvar( "Banner Topo Cadastrado", "Banner" , $admin->id , "", json_encode( $p ) );
		addOnloadScript("message('Cadastrado com sucesso.','sucess');");
	} else {
		LogAdmin::_salvar( "Erro ao cadastrar Banner Topo", "Banner" , $admin->id , "", json_encode( $p ) );
		addOnloadScript("message('Ocorreu um erro ao cadastrar.','error');");
	}
}

$p = new BannerTopo();

$page = new StdAdminPage();

$page->title = "Cadastrar Banner";
$page->page = "BannerTopo";
$page->back_link = true;
$page->title_back = "Banners";

$page->form = true;
$page->form_fields = array(
	array( "value" => $p->descricao, "name" => "descricao", "label" => "Descri&ccedil;&atilde;o *", "required" => true, "autofocus" => true ),
	array( "label" => "Imagem Atual", "type" => "image-view", "value" => ( "/" . $p->imagem ) ),
	array( "name" => "imagem", "label" => "Imagem "  . BANNER_TOPO_WIDTH. "x" . BANNER_TOPO_HEIGHT, "type" => "file", "required" => true ),
	array( "value" => $p->ordem, "name" => "ordem", "label" => "Ordem", "type" => "num", "required" => true ),
	array( "value" => $p->link, "name" => "link", "label" => "Link" ),
	array( "value" => $p->botao, "name" => "botao", "label" => "Chamada *" ),
	array( "value" => $p->entrar_em, "name" => "entrar_em", "label" => "Entrar em", "type" => "data", "required" => true),
	array( "value" => $p->data_sair, "name" => "data_sair", "label" => "Sair em", "type" => "data", "required" => true),
	array( "value" => $p->visivel, "name" => "visivel", "label" => "Vis&iacute;vel", "type" => "checkbox" )
);

$page->render();


?>