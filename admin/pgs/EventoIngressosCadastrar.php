<?php
// valida permissão
if( !in_array( "eventos", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$evento = new Evento();

// verifica se passou o id
if( !isset( $_GET["evento"] ) ) {
	include("pgs/404.pg.php") ;
	return;
}

// verifica se o evento existe de fato
$evento->id = $_GET["evento"];
if(!$evento->get()){
	include("pgs/404.pg.php") ;
	return;
}

$menu_destaque = "eventos";

$tags_string = "";

$p = new Ingresso();

if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
	
	if( is_numeric( $_POST["valor"] ) && is_numeric( $_POST["taxa_adicional"] ) && is_numeric( $_POST["ordem"]  )  ){

		$p->descricao = $_POST["descricao"];
		
		$p->valor = floatval( $_POST["valor"] );
		
		$p->cod_evento = $evento->id;

		$p->data_entrar = data( $_POST["data_entrar"] );
		$p->data_sair = data( $_POST["data_sair"] );

		$p->taxa_percentual = intval( $_POST["taxa_percentual"] );
		$p->taxa_fixa = floatval( $_POST["taxa_adicional"] );
		$p->ordem = intval( $_POST["ordem"] );
		
		$visivel = 0;
		if ( isset( $_POST["visivel"] ) ) 
			if( $_POST["visivel"] == "ativo" ) 
				$visivel = 1; 
		$p->visivel = $visivel;
		
		if( $p->cadastrar() ){
			LogAdmin::_salvar( "Ingresso Cadastrado - ".$evento->titulo, "Ingresso" , $admin->id , "", json_encode( $p ) );
			addOnloadScript("message('Cadastrado com sucesso.','sucess');");
		} else {
			LogAdmin::_salvar( "Erro ao cadastrar Ingresso- ".$evento->titulo, "Ingresso" , $admin->id , "", json_encode( $p ) );
			addOnloadScript("message('Ocorreu um erro ao cadastrar.','error');");
		}

	} else {
		LogAdmin::_salvar( "Erro ao cadastrar Ingresso- ".$evento->titulo, "Ingresso" , $admin->id , "", json_encode( $p ) );
		addOnloadScript("message('Insira numeros validos.','error');");		
	}

}

$p = new Ingresso();

$page = new StdAdminPage();

$page->title = "Cadastrar Ingressos: " . $evento->titulo;
$page->page = "Evento";
$page->back_link = true;
$page->title_back = "Eventos";


$page->form = true;
$page->form_fields = array(

	/* descrição */
	array( "value" => $p->descricao, "name" => "descricao", "label" => "Descri&ccedil;&atilde;o", "required" => true),
	
	/* valores */
	array( "value" => $p->valor, "name" => "valor", "label" => "Valor R$ (ex: 80.00)", "required" => true),
	array( "value" => Parametro::_getByIdentificacao("taxa_percentual")->valor, "name" => "taxa_percentual", "label" => "Taxa %", "required" => true),
	array( "value" => Parametro::_getByIdentificacao("taxa_adicional")->valor, "name" => "taxa_adicional", "label" => "Taxa Adicional R$", "required" => true),
	
	/* ordem */
	array( "value" => $p->ordem, "name" => "ordem", "label" => "Ordem", "required" => true),

	/* datas */
	array( "value" => "", "type" => "data", "name" => "data_entrar", "label" => "Data Entrar", "required" => true ),
	array( "value" => "", "type" => "data", "name" => "data_sair", "label" => "Data Sair", "required" => true ),
	
	/* visivel */
	array( "value" => $p->visivel, "name" => "visivel", "label" => "Vis&iacute;vel", "type" => "checkbox" )	
	
);

$page->render();


?>