<?php

// add style in header
$header_extra_styles = '
';

$footer_extra_scripts = '';

//cabeçario
$_head_title = "Recuperar Senha - ".$_head_title;
$_meta_description = "Recuperar Senha - Compre ingressos para os melhores eventos com o Zé do Ingresso. ".$_meta_description;

$user = null; // new Usuario();

$msg = "";
$erro = "1";

if( $global_usuario != null ){
	header("Location: /usuario-home");
	die();
}

if( $_SERVER["REQUEST_METHOD"] == "POST" ){

	if( $_POST["as"] != ""){
		header("Location: /404");
		die();
	}

	if( isset( 
		$_POST["email"]
	) ){
		
		$user = new Usuario();

		$user->email = $_POST["email"];
		
		// não tem erro, vamos cadastrar
		if( $msg == "" ){

			$user = Usuario::_getByEmail( $user->email );

			if( $user != null ) {

				// gravar log
				LogUsuario::_salvar( "Usuario #$user->id/$user->email/$user->nome acionou o \"esqueceu a senha\".", "Acao", $user->id );

				// enviar e-mail com recuperar senha
				$msg .= "Foi enviado um e-mail com as instruções para recuperar a senha para o endere&ccedil;o $user->email. Por favor, verifique o e-mail para prosseguir :)";
				$erro = "0";
				
				$user->hash = gerar_hash(20);
				$user->atualizar_hash();

				$email_txt = file_get_contents( "recursos/emails-padroes/email-recuperar-senha.txt" );
				$email_txt = str_replace('$[NOME]', "$user->nome", $email_txt);
				$email_txt = str_replace('$[EMAIL]', "$user->email", $email_txt);
				$email_txt = str_replace('$[HASH]', "$user->hash", $email_txt);
				// $msg .= "<br /><br />$email_txt";

				Email::EnviaPedido( 
					$email_txt					
					, "Recuperar Senha - Ze do Ingresso" , 
					$user->email 
				);

				LogGeral::_salvar( "Recurar senha utiliado '".$_POST["email"].". Msg: $msg", "Acao" );

			} else {
				$msg .= "* E-mail \"".$_POST["email"]."\" n&atilde;o encontrado em nossa base de dados.\n";
			}

		}

	}else{
		$msg .= "* Preencha todos os campos por favor.\n";
	}

	// inserir as quebras html
	if( $msg != "" && $erro != "0" ){
		// $footer_extra_scripts .= '<script>$(document).ready(function(){ alert( $(".msg").text() ) } );</script>';
		LogGeral::_salvar( "Tentativa de recuperar senha '".$_POST["email"].". Msg: $msg", "Acao" );
	}
}


?>