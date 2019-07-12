<?php

// add style in header
$header_extra_styles = '
';

$footer_extra_scripts = '<script src="/assets/third_party/jquery-placeholder-gh-pages/jquery-placeholder-gh-pages/jquery.placeholder.min.js"></script>'
.'<script src="/assets/js/trabalhe-conosco.js"></script>';

//cabeçario
$_head_title = "Trabalhe Conosco - ".$_head_title;
$_meta_description = "Envie-nos seu CV. ".$_meta_description;

$msg_contato = "";
$erro = 1;

/*variáveis para manter o estado dos inputs*/
$_nome = "";
$_email = "";
$_telefone = "";
$_linkedin = "";
$_especialidade = "";
$_sobre = "";

if( $_SERVER["REQUEST_METHOD"] == "POST" ){

	extract($_POST);
	if( isset( $nome, $email, $sobre, $linkedin ) ){
		$_nome = $nome;
		$_telefone = isset($telefone) ? $telefone : "";
		$_email = $email;
		$_linkedin = $linkedin;
		$_especialidade = $especialidade;
		$_sobre = $sobre;
		$_cv = "";

		if( ! filter_var($email, FILTER_VALIDATE_EMAIL)){
			$msg_contato = "Email inserido inv&aacute;lido :/";
		}
		else if( trim( $nome ) == "" || trim( $email ) == "" || trim( $sobre ) == "" ){
			$msg_contato = utf8_encode( "Preencha todos os campos marcados com * por favor..." );
		}else{

			if ( $_FILES["cv"]["error"] == 0 ){
				$img_name = gerar_hash();
				$_cv = Upload::salvaArqDaRaiz( "trabalhe-conosco/" . $img_name, $_FILES["cv"] );
			}

			// salvar
			$c = new TrabalheConosco();
			$c->nome = $_nome;
			$c->email = $_email;
			$c->linkedin = $_linkedin;
			$c->telefone = $_telefone;
			$c->sobre = $_sobre;
			$c->especialidade = $_especialidade;
			$c->curriculum = $_cv;
			$c->cadastrar();

			if(Email::enviaContato(
				$nome,
				$email,
					"<b>Nome:</b> ".$_nome."<br />".
					"<b>E-mail:</b> ".$_email."<br />".
					"<b>Telefone:</b> ".$_telefone."<br />".
					"<b>Especialidade:</b> ".$_especialidade."<br />".
					"<b>linkedin:</b> ".$_linkedin."<br />".
					"<b>CV:</b> <a href=\"http://www.zedoingresso.com.br/".$_cv."\">".$_cv."</a><br />".
					"<b>sobre:</b><br />".htmlspecialchars($_sobre)
			)) {
				$msg_contato = 'Enviado com sucesso. Muito obrigado, retornaremos assim que surgir uma oportunidade! :)';
				$erro = 0;

				// apagar o campo
				$_nome = "";
				$_email = "";
				$_sobre = "";
				$_linkedin = "";
				$_telefone = "";
				$_especialidade = "";
				$_especialidade = "";
		
			} else {
				$msg_contato = 'Desculpe, ocorreu um erro ao enviar a sobre :/';
			}
		}
	}else{
		$msg_contato = utf8_encode('Preencha todos os campos marcados com * por favor...');
	}
}

if( $msg_contato != "" ){
	// $footer_extra_scripts .= "<script> $(document).ready( function(){ alert($(\".msg\").text()); } ); </script>";	
}

?>