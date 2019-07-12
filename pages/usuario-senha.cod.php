<?php

// add style in header
$header_extra_styles = '<link href="/assets/css/usuario.css" rel="stylesheet" />';

$footer_extra_scripts = '<script src="/assets/js/usuario-senha.js"></script>';

//cabeçario
$_head_title = "Alterar Senha - ".$_head_title;
$_meta_description = "Alterar Senha - Compre ingressos para os melhores eventos com o Zé do Ingresso. ".$_meta_description;

$user = null; // new Usuario();

$msg = "";
$erro = "1";

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

$user = $global_usuario;

if( $_SERVER["REQUEST_METHOD"] == "POST" ){

	if( $_POST["as"] != ""){
		header("Location: /404");
		die();
	}

	if( isset( 
		$_POST["senha_atual"],
		$_POST["senha"],
		$_POST["senha_confirmar"]
	) ){
		
		// $user = new Usuario();

		$user->senha = $_POST["senha_atual"];
		// $_POST["senha_confirmar"]

		if( 
			trim( $_POST["senha"] ) == "" ||
			trim( $_POST["senha_confirmar"] ) == "" ||
			trim( $user->senha ) == "" 
		 ){
			$msg .= utf8_encode( " * Preencha todos os campos marcados com * por favor. \n" );
		}

		$teste = Usuario::_logar( $user->email, $user->senha );

		if( $teste == null ){
			$msg .= "* Senha atual informada incorreta\n";
		}

		if(	trim( $_POST["senha"] ) != trim( $_POST["senha_confirmar"] ) ){
			$msg .= "* A confirma&ccedil;&atilde;o da senha n&atilde;o est&aacute; correta. Digite novamente por favor...\n";
		}

		// não tem erro, vamos cadastrar
		if( $msg == "" ){
			$user->senha = $_POST["senha"];
			if( $user->atualizar_senha() ){
				
				// gravar log com cadastro feito
				LogUsuario::_salvar( "Usuario $user->id/$user->email/$user->nome atualizou sua senha.", "Atualizou Dados", $user->id );
				$msg .= "* Dados atualizados com sucesso!\n";
				$footer_extra_scripts .= '<script>$(document).ready(function(){ alert( $(".msg").text() ) } );</script>';
				$erro = "0";

			}else{
				$msg .= "* Houve um erro ao salvar as altera&ccedil;&otilde;es.\n";
			}

		}

	}else{
		$msg .= "* Preencha todos os campos por favor.\n";
	}

	// inserir as quebras html
	if( $msg != "" && $erro == 1 ){
		$footer_extra_scripts .= '<script>$(document).ready(function(){ alert( $(".msg").text() ) } );</script>';
		LogGeral::_salvar( "Tentativa de atualizar a senha #$user->id/$user->email/$user->nome.", "Erro Cadastro" );
	}
}


?>