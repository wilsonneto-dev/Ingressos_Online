<?php

// add style in header
$header_extra_styles = '
		<!--  Page Contato CSS --><link href="assets/css/contato.css" rel="stylesheet" />
';

$footer_extra_scripts = '';

//cabeçario
$_head_title = "Contato - ".$_head_title;
$_meta_description = "Envie-nos sua mensagem. ".$_meta_description;

$msg_contato = "";
$erro = 1;

/*variáveis para manter o estado dos inputs*/
$_nome = "";
$_email = "";
$_telefone = "";
$_assunto = "";
$_mensagem = "";

if( $_SERVER["REQUEST_METHOD"] == "POST" ){

	extract($_POST);
	if( isset( $nome, $email, $mensagem, $assunto ) ){
		$_nome = $nome;
		$_telefone = isset($telefone) ? $telefone : "";
		$_email = $email;
		$_assunto = $assunto;
		$_mensagem = $mensagem;
		
		if( ! filter_var($email, FILTER_VALIDATE_EMAIL)){
			$msg_contato = "Email inserido inv&aacute;lido :/";
		}
		else if( trim( $nome ) == "" || trim( $email ) == "" || trim( $mensagem ) == "" || trim( $assunto ) == "" ){
			$msg_contato = utf8_encode( "Preencha todos os campos marcados com * por favor..." );
		}else{
			if(Email::enviaContato(
				$nome,
				$email,
					"<b>Nome:</b> ".$_nome."<br />".
					"<b>E-mail:</b> ".$_email."<br />".
					"<b>Telefone:</b> ".$_telefone."<br />".
					"<b>Assunto:</b> ".$_assunto."<br />".
					"<b>Mensagem:</b><br />".htmlspecialchars($_mensagem)
			)) {
				$msg_contato = 'Mensagem enviada com sucesso. Obrigado, retornaremos o contato o mais breve poss&iacute;vel! \o/';
				$erro = 0;

				// salvar
				$c = new Contato();
				$c->nome = $_nome;
				$c->email = $_email;
				$c->assunto = $_assunto;
				$c->telefone = $_telefone;
				$c->mensagem = $_mensagem;
				$c->cadastrar();

				// apagar o campo
				$_nome = "";
				$_email = "";
				$_mensagem = "";
				$_assunto = "";
				$_telefone = "";
			
			} else {
				$msg_contato = 'Desculpe, ocorreu um erro ao enviar a mensagem :/';
			}
		}
	}else{
		$msg_contato = utf8_encode('Preencha todos os campos marcados com * por favor...');
	}
}

if( $msg_contato != "" ){
	$footer_extra_scripts .= "<script> $(document).ready( function(){ alert($(\".msg\").text()); } ); </script>";	
}

?>