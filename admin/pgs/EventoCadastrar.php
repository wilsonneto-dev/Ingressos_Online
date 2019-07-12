<?php
// valida permissão
if( !in_array( "eventos", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$menu_destaque = "eventos";

$tags_string = "";

$p = new Evento();

if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
	
	$categoria = Categoria::_get( $_POST[ "cod_categoria" ] );
	$genero = Genero::_get( $_POST[ "cod_genero" ] );
	$local = Local::_get( $_POST[ "cod_local" ] );
	$promoter = Promoter::_get( $_POST[ "cod_promoter" ] );

	$p->cod_categoria = $categoria->id;
	$p->cod_genero = $genero->id;
	$p->cod_local = $local->id;
	$p->cod_promoter = $promoter->id;
	
	$p->titulo = $_POST["titulo"];
	$p->descricao = $_POST["descricao"];
	$p->retirada = $_POST["retirada"];
	
	$p->data_entrar = data( $_POST["data_entrar"] );
	$p->data_encerrar_vendas = data( $_POST["data_encerrar_vendas"] );
	$p->data = data( $_POST["data"] );
	$p->data_final = data( $_POST["data_final"] );
	$p->data_mostrar = $_POST["data_mostrar"];

	if ( $_FILES["imagem"]["error"] == 0 ){
		$img_name = gerar_hash();
		$p->imagem = Upload::salvaArq( "eventos/img" . $img_name, $_FILES["imagem"] );
		// Imagem::MiniaturaNaMedida( "../".$p->imagem, $tipo->altura, $tipo->largura );
	}
	
	
	if ( $_FILES["imagem_facebook"]["error"] == 0 ){
		$img_name = gerar_hash();
		$p->imagem_facebook = Upload::salvaArq( "eventos/fb" . $img_name, $_FILES["imagem_facebook"] );
		// Imagem::MiniaturaNaMedida( "../".$p->imagem, $tipo->altura, $tipo->largura );
	}
	/*

	if ( $_FILES["imagem_topo"]["error"] == 0 ){
		$img_name = gerar_hash();
		$p->imagem_topo = Upload::salvaArq( "eventos/topo" . $img_name, $_FILES["imagem_topo"] );
		// Imagem::MiniaturaNaMedida( "../".$p->imagem, $tipo->altura, $tipo->largura );
	}
	*/

	if ( $_FILES["imagem_capa"]["error"] == 0 ){
		$img_name = gerar_hash();
		$p->capa = Upload::salvaArq( "eventos/capa" . $img_name, $_FILES["imagem_capa"] );
		// Imagem::MiniaturaNaMedida( "../".$p->imagem, $tipo->altura, $tipo->largura );
	}

	if ( $_FILES["imagem_mapa"]["error"] == 0 ){
		$img_name = gerar_hash();
		$p->imagem_mapa = Upload::salvaArq( "eventos/mapa" . $img_name, $_FILES["imagem_mapa"] );
		// Imagem::MiniaturaNaMedida( "../".$p->imagem, $tipo->altura, $tipo->largura );
	}

	if ( $_FILES["imagem_flyer"]["error"] == 0 ){
		$img_name = gerar_hash();
		$p->imagem_flyer = Upload::salvaArq( "eventos/flyer" . $img_name, $_FILES["imagem_flyer"] );
		// Imagem::MiniaturaNaMedida( "../".$p->imagem, $tipo->altura, $tipo->largura );
	}

	$p->link_video = $_POST["link_video"];
	$p->link_site = $_POST["link_site"];
	$p->link_facebook = $_POST["link_facebook"];

	$visivel = 0;
	if ( isset( $_POST["visivel"] ) ) 
		if( $_POST["visivel"] == "ativo" ) 
			$visivel = 1; 
	$p->visivel = $visivel;
	
	$oculto = 0;
	if ( isset( $_POST["oculto"] ) ) 
		if( $_POST["oculto"] == "ativo" ) 
			$oculto = 1; 
	$p->oculto = $oculto;

	$venda_suspensa = 0;
	if ( isset( $_POST["venda_suspensa"] ) ) 
		if( $_POST["venda_suspensa"] == "ativo" ) 
			$venda_suspensa = 1; 
	$p->venda_suspensa = $venda_suspensa;

	SEO::id_url( $p->titulo.' '.str_replace("/", "-", $_POST["data"]), $p );

	if( $p->cadastrar() ){
		LogAdmin::_salvar( "Evento Cadastrado", "Evento" , $admin->id , "", json_encode( $p ) );
		addOnloadScript("message('Cadastrado com sucesso.','sucess');");
	} else {
		LogAdmin::_salvar( "Erro ao cadastrar Evento", "Evento" , $admin->id , "", json_encode( $p ) );
		addOnloadScript("message('Ocorreu um erro ao cadastrar.','error');");
	}

	// tags
	$tags = $_POST["tags"];
	$arr_tags = preg_split("/,/", $tags);
	foreach ($arr_tags as $tag) {
		$existente = Tag::_getByTexto( $tag );
		if( $existente == null ){
			// nova tag, cadastrar
			$nova = new Tag();
			$nova->texto = trim($tag);
			$nova->cadastrar();
			$existente = $nova;
		}
		$relacao = new EventoTag();
		$relacao->cod_tag = $existente->id;
		$relacao->cod_evento = $p->id;
		$relacao->cadastrar();
	}
	
}

$p = new Evento();

$page = new StdAdminPage();

$page->title = "Cadastrar Evento";
$page->page = "Evento";
$page->back_link = true;
$page->title_back = "Eventos";

/*
$sel_tipo = new SqlSelect("SELECT id as valor, nome as texto FROM banner_tipo WHERE codprojeto = ".CODPROJETO." AND ativo = 1 ORDER BY nome"); 
$sel_tipo->nome = "cod_tipo";
if( $p != "" ) 
	$sel_tipo->valorSelecionado = $p->cod_banner_tipo; 
$sel_tipo->exec();
*/

// select * from genero where ativo = 1;
$sel_categoria = new SqlSelect("SELECT id as valor, nome as texto FROM categoria WHERE codprojeto = ".CODPROJETO." AND ativo = 1 ORDER BY nome"); 
$sel_categoria->nome = "cod_categoria";
if( $p != "" ) 
	$sel_categoria->valorSelecionado = $p->cod_categoria; 
$sel_categoria->exec();

$sel_genero = new SqlSelect("SELECT id as valor, nome as texto FROM genero WHERE codprojeto = ".CODPROJETO." AND ativo = 1 ORDER BY nome"); 
$sel_genero->nome = "cod_genero";
if( $p != "" ) 
	$sel_genero->valorSelecionado = $p->cod_genero; 
$sel_genero->exec();

$sel_local = new SqlSelect("SELECT id as valor, nome as texto FROM local WHERE codprojeto = ".CODPROJETO." AND ativo = 1 ORDER BY nome"); 
$sel_local->nome = "cod_local";
if( $p != "" ) 
	$sel_local->valorSelecionado = $p->cod_local; 
$sel_local->exec();

$sel_promoter = new SqlSelect("SELECT id as valor, razao_social as texto FROM promoter WHERE codprojeto = ".CODPROJETO." AND ativo = 1 ORDER BY razao_social"); 
$sel_promoter->nome = "cod_promoter";
if( $p != "" ) 
	$sel_promoter->valorSelecionado = $p->cod_promoter; 
$sel_promoter->exec();


$page->form = true;
$page->form_fields = array(

	/* combos */
	array( "label" => "Categoria", "type" => "html-field", "html" => $sel_categoria->html ),
	array( "label" => "Genero", "type" => "html-field", "html" => $sel_genero->html ),
	array( "label" => "Local", "type" => "html-field", "html" => $sel_local->html ),
	array( "label" => "Organizador", "type" => "html-field", "html" => $sel_promoter->html ),
	
	/* título */
	array( "value" => $p->titulo, "name" => "titulo", "label" => "Titulo", "required" => true),

	/* datas */
	array( "value" => $p->data_entrar, "type" => "data", "name" => "data_entrar", "label" => "Data Entrar", "required" => true ),
	array( "value" => $p->data_encerrar_vendas, "type" => "data", "name" => "data_encerrar_vendas", "label" => "Data Encerrar ( as vendas ser&atilde;o encerradas ao final do dia informado)", "required" => true ),
	array( "value" => $p->data, "type" => "data", "name" => "data", "label" => "Data do Evento ( Para ordenação, primeiro dia )", "required" => true ),
	array( "value" => $p->data_final, "type" => "data", "name" => "data_final", "label" => "Data Final do Evento ( Sair da listagem, último dia )", "required" => true ),
	array( "value" => $p->data_mostrar, "name" => "data_mostrar", "label" => "Mostrar Data", "required" => true ),

	/* imagens */
	array( "label" => "Imagem Atual", "type" => "image-view", "value" => ( "/" . $p->imagem ) ),
	array( "name" => "imagem", "label" => "Imagem ".EVENTO_IMAGEM_WIDTH."x".EVENTO_IMAGEM_HEIGHT, "type" => "file", "required" => true ),
	
	// array( "label" => "Imagem Topo Atual", "type" => "image-view", "value" => ( "/" . $p->imagem_topo ) ),
	// array( "name" => "imagem_topo", "label" => "Imagem Topo", "type" => "file" ),
	
	array( "label" => "Imagem Capa Atual", "type" => "image-view", "value" => ( "/" . $p->capa ) ),
	array( "name" => "imagem_capa", "label" => "Imagem Capa ".EVENTO_CAPA_WIDTH."x".EVENTO_CAPA_HEIGHT, "type" => "file", "required" => true ),
	
	array( "label" => "Imagem Mapa", "type" => "image-view", "value" => ( "/" . $p->imagem_mapa ) ),
	array( "name" => "imagem_mapa", "label" => "Imagem Mapa", "type" => "file" ),
	
	array( "label" => "Imagem Flyer", "type" => "image-view", "value" => ( "/" . $p->imagem_mapa ) ),
	array( "name" => "imagem_flyer", "label" => "Imagem Flyer", "type" => "file" ),
	
	array( "label" => "Imagem Facebook", "type" => "image-view", "value" => ( "/" . $p->imagem_facebook ) ),
	array( "name" => "imagem_facebook", "label" => "Imagem Facebook ".FACEBOOK_EVENTO_WIDTH."x".FACEBOOK_EVENTO_HEIGHT, "type" => "file" ),

	/* descrição */
	array( "value" => $p->descricao, "name" => "descricao", "type" => "editor", "label" => "Descri&ccedil;&atilde;o *", "required" => true ),

	/* retirada dos ingressos */
	array( "name" => "retirada", "type" => "textarea", "label" => "Retirada dos ingressos *", "required" => true, 
		"value" => 'Haver&aacute; uma equipe realizando as trocas na portaria do evento, ser&aacute; necess&aacute;rio apresentar CPF no qual o pedido foi feito e c&oacute;pia do pedido.' 
	),

	/* links */
	array( "value" => $p->link_video, "name" => "link_video", "label" => "Link V&iacute;deo", "icon" => "fa-chain" ),
	array( "value" => $p->link_site, "name" => "link_site", "label" => "Link Site", "icon" => "fa-chain" ),
	array( "value" => $p->link_video, "name" => "link_facebook", "label" => "Link Facebook", "icon" => "fa-chain" ),

	/* visivel */
	array( "value" => $p->visivel, "name" => "visivel", "label" => "Vis&iacute;vel ( acess&iacute;vel )", "type" => "checkbox" ),
	array( "value" => 0, "name" => "oculto", "label" => "Oculto ( n&atilde;o mostrar na lista nem na busca )", "type" => "checkbox" ),
	array( "value" => 0, "name" => "venda_suspensa", "label" => "Vendas Suspensas ( desativar vendas )", "type" => "checkbox" ),

	/* tags */
	array( "value" => $tags_string, "name" => "tags", "label" => "Tags ( separadas por virgula )", "icon" => "fa-tags" )

);

$page->render();


?>