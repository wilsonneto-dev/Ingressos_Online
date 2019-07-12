<?php

// add style in header
$header_extra_styles = '
		<link href="/assets/css/usuario.css" rel="stylesheet" />
';

$footer_extra_scripts = '<script src="/assets/third_party/jquery.maskedinput-1.1.4.pack.js"></script>
<script src="/assets/js/usuario-dados.js"></script>';

//cabeçario
$_head_title = "Meus Dados - ".$_head_title;
$_meta_description = "Alterar Dados - Compre ingressos para os melhores eventos com o Zé do Ingresso. ".$_meta_description;

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
$old = clone $global_usuario;
$cidade_selecionada = BrasilCidade::_get( $user->cod_brasil_cidade );

if( $_SERVER["REQUEST_METHOD"] == "POST" ){

	if( $_POST["as"] != ""){
		header("Location: /404");
		die();
	}

	if( isset( 
		// $_POST["nome"],
		// $_POST["sobrenome"],
		// $_POST["cpf"],
		// $_POST["data_nascimento"],
		// $_POST["sexo"],
		$_POST["ddd"],
		$_POST["telefone"],
		$_POST["uf"],
		$_POST["cidade"]
		// $_POST["email"],
		// $_POST["como_conheceu"],
		// $_POST["senha"],
		// $_POST["senha_confirmar"]
	) ){
		
		// $user = new Usuario();

		// $user->nome = $_POST["nome"];
		// $user->sobrenome = $_POST["sobrenome"];
		// $user->cpf = $_POST["cpf"];
		// $user->data_nascimento = $_POST["data_nascimento"];
		// $user->sexo = $_POST["sexo"];
		$user->ddd = $_POST["ddd"];
		$user->telefone = $_POST["telefone"];
		$user->cod_brasil_cidade = $_POST["cidade"];
		// $user->email = $_POST["email"];
		// $user->como_conheceu = $_POST["como_conheceu"];
		// $user->senha = $_POST["senha"];
		// $_POST["senha_confirmar"]

		if( 
			// trim( $user->nome ) == "" || 
			// trim( $user->sobrenome ) == "" || 
			// trim( $user->cpf ) == "" || 
			// trim( $_POST["data_nascimento"] ) == "" ||
			// trim( $user->sexo ) == "" ||
			trim( $user->ddd ) == "" ||
			trim( $user->telefone ) == "" ||
			trim( $user->cod_brasil_cidade ) == ""
			// trim( $user->email ) == "" ||
			// trim( $user->como_conheceu ) == "" ||
			// trim( $user->senha ) == "" 
		 ){
			$msg .= utf8_encode( " * Preencha todos os campos marcados com * por favor. \n" );
		}
		if( strlen( $user->telefone ) < 8 ){
			$msg .= "* Telefone em um formato incorreto \"".$_POST["telefone"]."\".\n";
		}

		// não tem erro, vamos cadastrar
		if( $msg == "" ){

			if( $user->atualizar() ){
				
				// gravar log com cadastro feito
				LogUsuario::_salvar( "Usuario #$user->id/$user->email/$user->nome atualizou os dados. Dados: ddd $old->ddd > $user->ddd, telefone $old->telefone > $user->telefone, cidade $old->cod_brasil_cidade > $user->cod_brasil_cidade.", "Atualizou Dados", $user->id );
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
		LogGeral::_salvar( "Tentativa de atualizar dados #$user->id/$user->email/$user->nome. Dados: ddd $old->ddd > $user->ddd,  telefone $old->telefone > $user->telefone, cidade $old->cod_brasil_cidade > $user->cod_brasil_cidade. Msg: ,".$msg, "Erro Cadastro" );
	}
}


?>