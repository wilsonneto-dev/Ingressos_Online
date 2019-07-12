<?php

// add style in header
$header_extra_styles = '';

$footer_extra_scripts = '';

//cabeçario
$_head_title = "Newsletter Cadastro - ".$_head_title;
$_meta_description = "Cadastre-se e receba nossas novidades. ".$_meta_description;

$msg_contato = "";
$erro = 1;

/*variáveis para manter o estado dos inputs*/
$_nome = "";
$_email = "";
$n = new Newsletter();

?>