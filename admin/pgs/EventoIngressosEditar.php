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

$p = new Ingresso();

if( isset( $_GET["id"] ) ) {

	$p->id = $_GET["id"];
	if($p->get()){

		if( $p->cod_evento != $evento->id ){
			include("pgs/404.pg.php") ;
			return;
		}else{

			if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
				
				if( is_numeric( $_POST["valor"] ) && is_numeric( $_POST["taxa_adicional"] ) && is_numeric( $_POST["ordem"]  )  ){

					$p->descricao = $_POST["descricao"];
					
					$p->valor = floatval( $_POST["valor"] );
					
					$p->cod_evento = $evento->id;
					
					$p->taxa_percentual = intval( $_POST["taxa_percentual"] );
					$p->taxa_fixa = floatval( $_POST["taxa_adicional"] );
					$p->ordem = intval( $_POST["ordem"] );
					
					$p->data_entrar = data( $_POST["data_entrar"] );
					$p->data_sair = data( $_POST["data_sair"] );

					$visivel = 0;
					if ( isset( $_POST["visivel"] ) ) 
						if( $_POST["visivel"] == "ativo" ) 
							$visivel = 1; 
					$p->visivel = $visivel;
					
					if( $p->atualizar() ){
						LogAdmin::_salvar( "Ingresso Atualizado - ".$evento->titulo, "Ingresso" , $admin->id , "", json_encode( $p ) );
						addOnloadScript("alert('Atualizado com sucesso.');document.location = '?pg=EventoIngressos&id=".$evento->id."';");
					} else {
						LogAdmin::_salvar( "Erro ao atualizar Ingresso - ".$evento->titulo, "Ingresso" , $admin->id , "", json_encode( $p ) );
						addOnloadScript("message('Ocorreu um erro ao atualizar.','error');");
					}

				} else {
					LogAdmin::_salvar( "Erro ao Atualizar Ingresso- ".$evento->titulo, "Ingresso" , $admin->id , "", json_encode( $p ) );
					addOnloadScript("message('Insira numeros validos.','error');");		
				}

			}

		}

	}
}

$menu_destaque = "eventos";

$tags_string = "";

$page = new StdAdminPage();

$page->title = "Editar Ingressos: " . $evento->titulo;
$page->page = "Evento";
$page->back_link = true;
$page->title_back = "Eventos";


$page->form = true;
$page->form_fields = array(

	/* descrição */
	array( "value" => $p->descricao, "name" => "descricao", "label" => "Descri&ccedil;&atilde;o", "required" => true),
	
	/* valores */
	array( "value" => $p->valor, "name" => "valor", "label" => "Valor R$ (ex: 80.00)", "required" => true),
	array( "value" => $p->taxa_percentual, "name" => "taxa_percentual", "label" => "Taxa %", "required" => true),
	array( "value" => $p->taxa_fixa, "name" => "taxa_adicional", "label" => "Taxa Adicional R$", "required" => true),
	
	/* ordem */
	array( "value" => $p->ordem, "name" => "ordem", "label" => "Ordem", "required" => true),

	/* datas */
	array( "value" => date_format( $p->data_entrar , 'd/m/Y' ), "type" => "data", "name" => "data_entrar", "label" => "Data Entrar", "required" => true ),
	array( "value" => date_format( $p->data_sair , 'd/m/Y' ), "type" => "data", "name" => "data_sair", "label" => "Data Sair", "required" => true ),

	/* visivel */
	array( "value" => $p->visivel, "name" => "visivel", "label" => "Vis&iacute;vel", "type" => "checkbox" )	
	
);

$page->render();


?>