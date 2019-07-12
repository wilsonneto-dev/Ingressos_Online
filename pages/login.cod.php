<?php

// add style in header
$header_extra_styles = '
		<!--  Page SignUp CSS -->
';

$footer_extra_scripts = '';

//cabeçario
$_head_title = "Login - ".$_head_title;
$_meta_description = "Entre e aproveite! Compre ingressos para os melhores eventos com o Zé do Ingresso. ".$_meta_description;

$user = null; // new Usuario();

$msg_erro = "";
$erro = "1";

if( $global_usuario != null ){
	header("Location: /usuario-home");
	die();
}

// REDIRECIONAR A UMA PÁGINA ESPECIFICA
$flag = isset( $_SESSION[S_FLAG] ) ? $_SESSION[S_FLAG] : "";
				
// verificar se há mensagem que veio de outra tela para mostrar
if ( $_SERVER["REQUEST_METHOD"] != "POST" ){
	if ( isset( $_SESSION[S_MENSAGEM_ERRO] ) ){
	
		$msg_erro = $_SESSION[S_MENSAGEM_ERRO];
		unset( $_SESSION[S_MENSAGEM_ERRO] );
	
	}
	if ( isset( $_SESSION[S_MENSAGEM_OK] ) ){
	
		$msg_erro = $_SESSION[S_MENSAGEM_OK];
		$erro = "0";
		unset( $_SESSION[S_MENSAGEM_OK] );
	
	}else if( $flag == "LOGIN_PEDIDO" ){

		$msg_erro = "Fa&ccedil;a o login para continuar a compra. Caso n&atilde;o tenha cadastro ainda clique em \"criar conta\".";
		$erro = "0";
		unset( $_SESSION[S_MENSAGEM_OK] );

	}
}


if( $_SERVER["REQUEST_METHOD"] == "POST" ){

	if( $_POST["as"] != ""){
		header("Location: /404");
		die();
	}

	if( isset( 
		$_POST["email"],
		$_POST["pass"]
	) ){
		
		$user = new Usuario();

		$user->email = $_POST["email"];
		$user->senha = $_POST["pass"];
		
		// não tem erro, vamos cadastrar
		if( $msg_erro == "" ){

			$user = Usuario::_logar( $user->email, $user->senha );

			if( $user != null ) {
				// atualizar último acesso
				$user->atualizar_ultimo_acesso();

				/* gravar na sessão o usuário */
				if( isset( $_SESSION[S_USUARIO] ) ) {
					unset( $_SESSION[S_USUARIO] );
				}
				$_SESSION[S_USUARIO] = $user;

				// gravar log com cadastro feito
				LogUsuario::_salvar( "Usuario #$user->id/$user->email/$user->nome logou.", "Login", $user->id );

				
				if( isset( $_SESSION[S_REDIRECIONAR] ) ){

					// cadastro feito e redireciona para a página que estava na sessao
					$pagina_redirecionar = $_SESSION[S_REDIRECIONAR];
					unset( $_SESSION[S_REDIRECIONAR] );
					header( "Location: ". $pagina_redirecionar );
					die();
				
				} else if ( $flag == "LOGIN_PEDIDO" ){
				
					unset( $_SESSION[S_FLAG] );
					header( "Location: /pedido" );
					die();
					
				} else {
					// cadastro feito
					header("Location: /");
					die();
				}
			}else{
				$msg_erro .= "* Usu&aacute;rio ou senha incorretos.\n";
			}

		}

	}else{
		$msg_erro .= "* Preencha todos os campos por favor.\n";
	}

	// inserir as quebras html
	if( $msg_erro != "" ){
		// $footer_extra_scripts .= '<script>$(document).ready(function(){ alert( $(".msg").text() ) } );</script>';
		LogGeral::_salvar( "Tentativa de login '".$_POST["email"]." nao permitida", "Login Errado" );
	}
}


?>