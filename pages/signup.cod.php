<?php

// add style in header
$header_extra_styles = '
		<!--  Page SignUp CSS -->
';

$footer_extra_scripts = '<script src="/assets/third_party/jquery.maskedinput-1.1.4.pack.js"></script>
<script src="/assets/js/signup.js"></script>';

//cabeçario
$_head_title = "Cadastre-se e Aproveite - ".$_head_title;
$_meta_description = "Cadastre-se e compre ingressos para os melhores eventos com o Zé do Ingresso. ".$_meta_description;

$user = null; // new Usuario();

$msg_erro = "";
$erro = "1";

// REDIRECIONAR A UMA PÁGINA ESPECIFICA
$flag = isset( $_SESSION[S_FLAG] ) ? $_SESSION[S_FLAG] : "";

if( $global_usuario != null ){
	header("Location: /usuario-home");
	die();
}

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

		$msg_erro = "Fa&ccedil;a seu cadastro para continuar com a compra.";
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
		$_POST["nome"],
		$_POST["sobrenome"],
		$_POST["cpf"],
		$_POST["data_nascimento"],
		$_POST["sexo"],
		$_POST["ddd"],
		$_POST["telefone"],
		$_POST["uf"],
		$_POST["cidade"],
		$_POST["email"],
		$_POST["como_conheceu"],
		$_POST["senha"],
		$_POST["senha_confirmar"]
	) ){
		
		$user = new Usuario();

		$user->nome = $_POST["nome"];
		$user->sobrenome = $_POST["sobrenome"];
		$user->cpf = $_POST["cpf"];
		// $user->data_nascimento = $_POST["data_nascimento"];
		$user->sexo = $_POST["sexo"];
		$user->ddd = $_POST["ddd"];
		$user->telefone = $_POST["telefone"];
		$user->cod_brasil_cidade = $_POST["cidade"];
		$user->email = $_POST["email"];
		$user->como_conheceu = $_POST["como_conheceu"];
		$user->senha = $_POST["senha"];
		// $_POST["senha_confirmar"]

		$data_valida = 0;
		try{
			$user->data_nascimento = data( $_POST["data_nascimento"] ); 
			$data_valida = 1;
		}catch(Exception $e){}

		if( ! filter_var($user->email, FILTER_VALIDATE_EMAIL)){
			$msg_erro .= "* Email inserido inv&aacute;lido\n";
		}
		if( 
			trim( $user->nome ) == "" || 
			trim( $user->sobrenome ) == "" || 
			trim( $user->cpf ) == "" || 
			trim( $_POST["data_nascimento"] ) == "" ||
			trim( $user->sexo ) == "" ||
			trim( $user->ddd ) == "" ||
			trim( $user->telefone ) == "" ||
			trim( $user->cod_brasil_cidade ) == "" ||
			trim( $user->email ) == "" ||
			trim( $user->como_conheceu ) == "" ||
			trim( $user->senha ) == "" 
		 ){
			$msg_erro .= utf8_encode( " * Preencha todos os campos marcados com * por favor. \n" );
		}
		if(strlen( $user->senha ) < 6 ){
			$msg_erro .= "* Desculpe, mas para sua seguran&ccedil;a a senha deve conter pelo menos 6 caracteres.\n";
		}
		if( $data_valida == 0 ){
			$msg_erro .= "* A Data de Nascimento est&aacute; em um formato incorreto \"".$_POST["data_nascimento"]."\".\n";
		}
		if( strlen( $user->telefone ) < 8 ){
			$msg_erro .= "* Telefone em um formato incorreto \"".$_POST["telefone"]."\".\n";
		}
		if( !Cpf::validar( $user->cpf ) ){
			$msg_erro .= "* CPF inv&aacute;lido, verifique por favor...\n";
		}
		// verificar se o e-mail já existe
		if( Usuario::_getByEmail($user->email) ){
			$msg_erro .= "* O e-mail \"".$_POST["email"]."\" j&aacute; consta em nossa base de usu&aacute;rios. Para recuperar a senha acesse: <a href=\"/recuperar-senha\">Recuperar Senha</a>. \n";
		}
		// verificar se o cpf já existe
		if( Usuario::_getByCpf($user->cpf) ){
			$msg_erro .= "* O CPF digitado j&aacute; consta em nossa base de usu&aacute;rios. Para recuperar a senha acesse: <a href=\"/recuperar-senha\">Recuperar Senha</a>. \n";
		}
		// verificar se as senhas são realmente iguais
		if( $_POST["senha"] != $_POST["senha_confirmar"] ){
			$msg_erro .= "* As senhas digitadas s&atilde;o diferentes, digite novamente por favor. \n";
		}

		// não tem erro, vamos cadastrar
		if( $msg_erro == "" ){

			if( $user->cadastrar() ){

				$_SESSION[S_USUARIO] = $user;

				$_SESSION[S_FLAG] = "Novo Usuario";

				// gravar log com cadastro feito
				LogUsuario::_salvar( "Usuario ".$user->id." se cadastrou.", "Novo Cadastro", $user->id );

				// REDIRECIONAR A UMA PÁGINA ESPECIFICA
				if( isset( $_SESSION[S_REDIRECIONAR] ) ){

					// cadastro feito e redireciona para a página que estava na sessao
					$pagina_redirecionar = $_SESSION[S_REDIRECIONAR];
					unset( $_SESSION[S_REDIRECIONAR] );
					header( "Location: /". $pagina_redirecionar );
					die();
				} else if ( $flag == "LOGIN_PEDIDO" ){

					unset( $_SESSION[S_FLAG] );
					header( "Location: /pedido" );
					die();

				} else {
					// cadastro feito
					header("Location: /cadastro-efetuado");
					die();
				}
			}else{
				$msg_erro .= "* Houve um erro ao tentar cadastrar, verifique os dados.\n";
			}

		}

	}else{
		$msg_erro .= "* Preencha todos os campos por favor.\n";
	}

	// inserir as quebras html
	if( $msg_erro != "" ){
		$footer_extra_scripts .= '<script>$(document).ready(function(){ alert( $(".msg").text() ) } );</script>';
		LogGeral::_salvar( "Tentativa de cadastro de '".$_POST["nome"]." ".$_POST["sobrenome"]."'. Msg: ".$msg_erro, "Erro Cadastro" );
	}
}


?>