<?php

// add style in header
$header_extra_styles = '';

$footer_extra_scripts = '';

//cabeçario
$_head_title = "Nova Senha - ".$_head_title;
$_meta_description = "Nova Senha - Compre ingressos para os melhores eventos com o Zé do Ingresso. ".$_meta_description;

$user = null; // new Usuario();

$msg = "";
$erro = "1";

if( $global_usuario != null ){
	header("Location: /usuario-home");
	die();
}

if( ! isset( $_GET["email"] , $_GET["codigo"] ) ){
	header("Location: /404");		
	die();
}

$user = Usuario::_getByEmail( str_replace("''", "", $_GET["email"] ) );
if($user == null){
	header("Location: /404");		
	die();
}

if( $user->hash != $_GET["codigo"]  ){
	header("Location: /404");		
	die();
}

if( $_SERVER["REQUEST_METHOD"] == "POST" ){

	if( $_POST["as"] != ""){
		header("Location: /404");
		die();
	}

	$senha = $_POST["senha"];
	$senha_confirmar = $_POST["senha_confirmar"];

	if( strlen( $senha ) < 5 ){
		$msg .= "* A senha deve ter no m&iacute;nimo 6 caracteres...";
	}

	if( $senha != $senha_confirmar ){
		$msg .= "* A confirma&ccedil;&atilde;o de senha n&atilde;o est&aacute; correta, verifique por favor.\n";
	}

	if( $msg == "" ){
		$user->senha = $_POST["senha"];
		if( $user->atualizar_senha() ){
			
			// gravar log com cadastro feito
			LogUsuario::_salvar( "Usuario $user->id/$user->email/$user->nome atualizou sua senha.", "Atualizou Dados", $user->id );
			$_SESSION[S_MENSAGEM_OK] = "Senha alterada com sucesso.";
			header("Location: /login");
			die();

		}else{
			$msg .= "* Houve um erro ao salvar as altera&ccedil;&otilde;es.\n";
		}

		$erro = "0";
	}

	// inserir as quebras html
	if( $msg != "" ){
		// $footer_extra_scripts .= '<script>$(document).ready(function(){ alert( $(".msg").text() ) } );</script>';
		LogGeral::_salvar( "Tentativa de recuperar senha '".$_GET["email"].". Msg: $msg", "Acao" );
	}
}


?>