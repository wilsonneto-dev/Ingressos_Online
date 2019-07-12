<?php

// add style in header
$header_extra_styles = '
			<link href="/assets/css/usuario.css" rel="stylesheet" />';
$footer_extra_scripts = '';

//cabeçario
$_head_title = "&Aacute;rea do Usu&aacute;rio - ".$_head_title;
$_meta_description = "Meus dados. Compre ingressos para os melhores eventos com o Zé do Ingresso. ".$_meta_description;

// validar usuário
if( $global_usuario == null ){
	// mostrar mensagem na tela de login
	$_SESSION[S_MENSAGEM_ERRO] = "Efetue o login para continuar";
	// voltar para esta página ao logar
	$_SESSION[S_REDIRECIONAR] = $_SERVER["REQUEST_URI"];
	// redirecionar para a tela de login
	header("Location: /login");
	die();
}

?>