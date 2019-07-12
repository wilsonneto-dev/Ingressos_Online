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
			
// print_r($_POST);

if( $_SERVER["REQUEST_METHOD"] == "POST" ){

	extract($_POST);
	if( isset( $nome, $email ) ){
		$_nome = $nome;
		$_email = $email;
		
		if( ! filter_var($email, FILTER_VALIDATE_EMAIL)){
			$msg_contato = "Email inserido inv&aacute;lido :/";
		}
		else if( trim( $nome ) == "" || trim( $email ) == "" ){
			$msg_contato = ( "Preencha todos os campos marcados com * por favor..." );
		}else{

				$n->nome = $nome;
				$n->email = $email;
				$n->chave = gerar_hash( 20 );
				
				if( !$n->getByEmail() ){
					if( $n->cadastrar() ){
						$erro = 0;
						$msg_contato = ( "Cadastro efetuado com sucesso" );
					}
					else{
						$msg_contato = ( "Ocorreu um erro ao tentar cadastrar $n->email." );
					}
				}else{
					$msg_contato = ( "O email \"$n->email\" já consta em nossa base de dados" );
				}			
			}
		}else{
			$msg_contato = ('Preencha todos os campos marcados com * por favor...');

		}
}else{
 	header("Location: /");
	die();
}

if( $msg_contato != "" ) {
	// $footer_extra_scripts .= "<script> $(document).ready( function(){ alert($(\".msg\").text()); } ); </script>";	
	LogGeral::_salvar( "Tentativa de cadastro de newsletter: '$n->nome / $n->email'. Msg: ".$msg_contato, "Erro Newsletter" );
}

?>