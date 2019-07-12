<?php

// add style in header
$header_extra_styles = '
		<!--  Page SignUp CSS -->
';

$footer_extra_scripts = '';

//cabeçario
$_head_title = "Logout - ".$_head_title;
$_meta_description = "Encerrando sessao... Compre ingressos para os melhores eventos com o Zé do Ingresso. ".$_meta_description;

$user = null; // new Usuario();

$msg_erro = "";
$erro = "1";

if( isset( $_SESSION[S_USUARIO] ) ){
	unset($_SESSION[S_USUARIO]);
	$global_usuario = null;
}

header("Location: /");
die();

?>