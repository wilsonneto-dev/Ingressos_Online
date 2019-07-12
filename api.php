<?php

include_once 'php/config/constantes.php';
$entidade = "";

class WebService {

	private $this->params 

	function __construct( $params = [] ){
		
		echo "here";
		// verifica onde esta os parametros
		$params = ( isset( $_GET[ "token" ] ) ? $_GET : $_POST );
 		
 		// pegar as varaveis da url
		if ( isset( $_GET["entidade"] ) ) {
			$entidade = ($_GET["entidade"]);
			$entidade = str_replace("/", "", $entidade);
			$entidade = str_replace("\\", "", $entidade);
			$entidade = str_replace(".", "", $entidade);
			if ( file_exists("api/".$entidade.".api.php") ){
				include("api/".$entidade.".api.php");
			} else if ( file_exists( "api/404.api.php" ) ){
				include("api/404.api.php");
			}
		} else if( file_exists( "api/default.api.php" ) ){
			include( "api/default.api.php" );
		}

		print_r( $params );
		print_r(  $_GET );


	}

	function executar(){
		echo "oopa";
	}

}  



$ws = new WebService();
$ws["executar"]();

?>