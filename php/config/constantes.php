<?php
#Arquivo de configurações gerais

# sem warnings
// error_reporting( 15 );

# constantes
define( 'DOMINIO' , 'www.zedoingresso.com.br' );
define( 'EMAIL' , 'contato@zedoingresso.com.br' );

define('EMAIL_PARA','contato@zedoingresso.com.br'); 
define('EMAIL_SMTP','mail.zedoingresso.com.br');
define('EMAIL_SENHA','euroset805s');
define('EMAIL_USUARIO','contato@zedoingresso.com.br');

define( 'TELEFONE' , '17 98813-8299' );
define( 'CODPROJETO' , '1' );

/* sessões */
define( 'S_USUARIO' , 'sessao_usuario_logado' );
define( 'S_REDIRECIONAR' , 'sessao_redirocinar_pagina' );
define( 'S_POST' , 'sessao_post' );
define( 'S_FLAG' , 'sessao_flags' );
define( 'S_MENSAGEM_ERRO' , 'sessao_mensagem_erro' );
define( 'S_MENSAGEM_OK' , 'sessao_mensagem_ok' );
define( 'S_REF' , 'sessao_ref' );

	
# cabeçario
$_head_title = ( "Z&eacute; do Ingresso" );
$_meta_keywords = ("Ingressos, Eventos, Baladas, Festas, Teatros, Rio Preto, Vendas Online, Comprar ingressos, convites, Ze do Ingresso");
$_meta_description = ("Ingressos para os melhores eventos e baladas de Rio Preto e Regi&atilde;o! Vendas online de ingressos, compre pela internet sem sair de casa!");
$_meta_author = ("");
$_fb_admins = ("");
$_fb_app_id = ("");
$_og_type = ("");
$_og_image = "/imgs/og-logo.png";
	
	
#FUNÇÕES GERAIS
	
#protege contra sql injection substituindo ' por ''
function anti_sqli($s){ return str_replace("'","''",$s); }
	
#limpa a string para apresentar nos inputs do painel
function bd_limpa($s){ return stripcslashes(htmlspecialchars($s)); }
	
#tira as \
function bd_limpa_barra($s){ return stripcslashes($s); }

#tira as \
function _post( $s ){ return ( isset( $_POST[ $s ] ) ? $_POST[ $s ] : "" ); }
function _get( $s ){ return ( isset( $_GET[ $s ] ) ? $_GET[ $s ] : "" ); }

// função que pega a URL da página atual
function pg(){ return ( $_SERVER["REQUEST_URI"] ); }

// direto do bd para a tela
function _echo($str){ echo nl2br(htmlspecialchars($str)); }
	
// função de log
function _log( $str, $tipo = "log", $arq  = "relatorio" ){
	$f = fopen($arq.".".$tipo, "a+");
	$data = date("d/m/Y H.i.s");
	$info = $_SERVER['REMOTE_ADDR'].":".$_SERVER['REQUEST_URI'].":".$_SERVER['REQUEST_METHOD'] ;
	fwrite($f, $data." : ".$info." : [".$tipo."] ".$str."\n");
	fclose($f);
}

function erro_bd( $exption , $conexao_erro ){
	$errMsg = "exceção: ". $exption->getMessage()."\n<br />error: ".
		$conexao_erro ."\n<br />data:". time().
		"\n<br />ip: ". $_SERVER["REMOTE_ADDR"] ;
	// echo $errMsg;
	// quando ocorre erro na base de dados
}
	
function addOnloadScript($script){
	if(isset($_SESSION["OnloadScript"])) 
		$_SESSION["OnloadScript"] .= $script;
	else	
		$_SESSION["OnloadScript"] = $script;
}

function getOnloadScript($append_script){
	if(isset( $_SESSION["OnloadScript"] )){
		$script = $_SESSION["OnloadScript"];
		if( $append_script == true)
			$script = '<script> $(document).ready(function(){ ' . $script . ' }); </script>';
		unset( $_SESSION["OnloadScript"] );
		return ( $script );
	}
}
	
// pegar string de thumb passando a imagem
function getThumb( $str, $prefixo = "thumb_" ){
	$caminho = substr( $str,0,strrpos( $str,"/" ) + 1 );
	$nome = substr( $str,strrpos( $str,"/" ) + 1 );
	$thumb = $caminho.$prefixo.$nome;
	return $thumb;
}

function gerar_hash( $length = 23 ){
	$today = microtime(true);
	$out = substr(hash('md5', $today), 0, $length);
	return $out;
}

function data( $string ){
	$d = new DateTime();
	$dia = substr ( $string , 0 , 2 );
	$mes = substr ( $string , 3 , 2 );
	$ano = substr ( $string , 6, 4 );
	// echo ( "$dia / $mes / $ano" );
	$d->setDate ( $ano , $mes , $dia );
	$d->setTime ( 0 , 0 , 0 );
	// echo $d->format('d/m/Y');
	// strtotime( str_replace( "/" , "-", $_POST["data_sair"] ) ) )
	return $d;
}

function cpf_num( $string ){
	return (str_replace(".", "", str_replace("-", "", $string)));
}

#função q carrega as classes automaticamente
function __autoload( $classe ) {
	if(file_exists("../php/abstract/".$classe.".classe.php"))
		require_once("../php/abstract/".$classe.".classe.php");
	else if(file_exists("php/abstract/".$classe.".classe.php"))
		require_once("php/abstract/".$classe.".classe.php");
	else if(file_exists("../../php/abstract/".$classe.".classe.php"))
		require_once("../../php/abstract/".$classe.".classe.php");
	else if(file_exists("php/config/".$classe.".classe.php"))
		require_once("php/config/".$classe.".classe.php");
	else if(file_exists("../php/config/".$classe.".classe.php"))
		require_once("../php/config/".$classe.".classe.php");	
	else if(file_exists("../../php/config/".$classe.".classe.php"))
		require_once("../../php/config/".$classe.".classe.php");	
	else if(file_exists("../php/util/".$classe.".classe.php"))
		require_once("../php/util/".$classe.".classe.php");	
	else if(file_exists("../../php/util/".$classe.".classe.php"))
		require_once("../../php/util/".$classe.".classe.php");	
	else if(file_exists("php/util/".$classe.".classe.php"))
		require_once("php/util/".$classe.".classe.php");	
}



?>