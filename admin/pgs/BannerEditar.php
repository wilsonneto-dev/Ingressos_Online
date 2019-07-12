<?php

// valida permissão
if( !in_array( "banners", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$menu_destaque = "banners";

$p = new Banner();

if( isset($_GET["id"]) ){
	$p->id = $_GET["id"];
	if($p->get()){
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
		
			$clone = clone $p;

			$tipo = BannerTipo::_get( $_POST[ "cod_tipo" ] );

			$p->cod_tipo = $tipo->id;
			$p->descricao = $_POST["descricao"];
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
				Imagem::MiniaturaNaMedida( "../".$p->imagem, $tipo->altura, $tipo->largura );
			}
			
			if($p->atualizar()){
				LogAdmin::_salvar( "Banner Topo Editado", "Banner" , $admin->id, json_encode( $clone ), json_encode( $p ) );
				addOnloadScript("message('Atualizado com sucesso.','sucess');");
			} else {
				LogAdmin::_salvar( "Erro ao editar banner topo", "Banner" , $admin->id, json_encode( $clone ), json_encode( $p ) );
				addOnloadScript("message('Erro ao atualizar.','error');");
			}
		}
	}
}

$page = new StdAdminPage();

$page->title = "Cadastrar Banner";
$page->page = "Banner";
$page->back_link = true;
$page->title_back = "Banners";

$sel_tipo = new SqlSelect("SELECT id as valor, nome as texto FROM banner_tipo WHERE codprojeto = ".CODPROJETO." AND ativo = 1 ORDER BY nome"); 
$sel_tipo->nome = "cod_tipo";
if( $p != "" ) 
	$sel_tipo->valorSelecionado = $p->cod_banner_tipo; 
$sel_tipo->exec();

$page->form = true;
$page->form_fields = array(
	array( "label" => "Tipo", "type" => "html-field", "html" => $sel_tipo->html ),
	array( "value" => $p->descricao, "name" => "descricao", "label" => "Descri&ccedil;&atilde;o *", "required" => true, "autofocus" => true ),
	array( "label" => "Imagem Atual", "type" => "image-view", "value" => ( "/" . $p->imagem ) ),
	array( "name" => "imagem", "label" => "Imagem", "type" => "file" ),
	array( "value" => $p->ordem, "name" => "ordem", "label" => "Ordem", "type" => "num", "required" => true ),
	array( "value" => $p->link, "name" => "link", "label" => "Link", "required" => true),
	array( "value" => $p->entrar_em->format("d/m/Y"), "name" => "entrar_em", "label" => "Entrar em", "type" => "data", "required" => true),
	array( "value" => $p->data_sair->format("d/m/Y"), "name" => "data_sair", "label" => "Sair em", "type" => "data", "required" => true),
	array( "value" => $p->visivel, "name" => "visivel", "label" => "Vis&iacute;vel", "type" => "checkbox" )
);
$page->render();

?>