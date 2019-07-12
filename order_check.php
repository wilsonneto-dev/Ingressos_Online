<?php

	session_start();
	include_once 'php/config/constantes.php';

	Visita::_cadastrar( "Landing", "", $_GET, $_SERVER, "", "" );
	
	$p_cod_pedido = "";
	$p_codigo_seguranca = "";
	$p_email = "";
	
	if( isset( $_GET["order"], $_GET["sec"], $_GET["email"] ) ){
		$p_cod_pedido = anti_sqli( $_GET["order"] );
		$p_codigo_seguranca = anti_sqli( $_GET["sec"] );		
		$p_email = anti_sqli( $_GET["email"] );		
	} 

?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Zé do Ingresso - Verificar Status do Pedido</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="description" content="Compre seu ingresso online para os principais eventos e baladas de Rio Preto! Venda de ingressos em Rio Preto e região." />
		<meta name="keywords" content="venda de ingresso, Rio Preto, São José do Rio Preto, ingressos" />
		<link rel="stylesheet" href="css/style.css"></link>
		<!--[if lte IE 9]>
		    <script src="js/html5shiv.js"></script>
	    <![endif]-->
		<script src="js/jquery-1.11.1.min.js"></script>
		<script src="js/jquery.maskedinput-1.1.4.pack.js"></script>
		<script src="js/script.js"></script>
	</head>
	<body>
		<header>
			<img src="imgs/header-logo.png" />
			<br />
			<span class="soon">SITE COMPLETO EM BREVE, AGUARDE...</span>
		</header>

		<div class="event">
			<section class="image">
				<img src="imgs/banner.jpg"></img>
			</section>
			<section class="content">
				<article>
					<!-- header>
						<h1>Noite do Branco</h1>
					</header>
					<span class="info-text">
						<img src="imgs/event-bullet-date.png" /> S&aacute;bado, 27 de Dezembrode 2014 &agrave;s 22h
					</span>
					<span class="info-text">
						<img src="imgs/event-bullet-location.png" /> &Aacute;guas Claras - Espa&ccedil;o Brilhante ( Rodovia Washington Luis, KM 426 )
					</span>
					<p>
Noite do Branco, uma das festas mais esperadas do ano, em sua quarta edi&ccedil;&atilde;o promete agitar seu fim de ano! &Eacute; obrigat&oacute;rio o uso de peça sobressalente branca. A festa será 100% open bar, com Vodka, Cerveja, Champagne, Jurupinga, Refri e &Aacute;gua.<br />
<br />
As atra&ccedil;&otilde;es ser&atilde;o:<br />
Fabro ( Residente Living Club)<br />
Saxen ( Um dos maiores projetos de musica eletronica do Brasil )<br />
Grupo Loka ( Samba e Pagode )<br />
Victor Hugo e Americano ( Sertanejo do Hit "Ela vai te arrastar" )<br />
<br />
Instagram: @noitedobrancoriopreto @fabianomazza<br />
Facebook: https://www.facebook.com/noitedobrancoriopreto
					</p -->
					<br /><br />
				</article>
				<form action="order_details.php" method="post">
					<br />
					<span class="label"><b>Para verificar seu pedido insira os dados:</b></span>
					<br />
					<div class="fields">
						<div class="field left">
							<label>E-mail cadastrado no Pedido *</label>
							<input type="mail" class="txt txt_email" autofocus required name="txt_email" value="<?php _echo( $p_email ); ?>" />
						</div>
						<div class="field right">
						</div>
						<div class="clear"></div>
					</div>
					<div class="fields">
						<div class="field left">
							<label>C&oacute;digo do Pedido *</label>
							<input type="text" class="txt" required name="txt_cod_pedido" value="<?php _echo( $p_cod_pedido ); ?>" onkeypress="return SomenteNumero(event);" />
						</div>
						<div class="field right">
							<label>C&oacute;digo de Seguran&ccedil;a *</label>
							<input type="text" class="txt" required name="txt_codigo_seguranca" value="<?php _echo( $p_codigo_seguranca ); ?>" onkeypress="return SomenteNumero(event);" />
						</div>
						<div class="clear"></div>
					</div>
					<div class="wrapper_button">
						<input type="submit" class="btn_comprar" value="visualizar pedido" />
						<br />
						<a class="under_back" href="/">clique aqui para voltar a p&aacute;gina de efetuar o pedido</a>
					</div>
					<input type="text" name="as" class="txt txt_as" />
				</form>
			</section>
		</div>
		<section class="pre_footer">
			<img src="imgs/pre-footer-banner-pagseguro.png" />
		</section>
				<footer>
			<div class="img_wrapper">
				<img src="imgs/footer-logo.PNG" />
			</div>
			<div class="right">
				<a href="mailto://contato@zedoingresso.com.br">
					<img src="imgs/footer-icon-mail.png" />
					contato@zedoingresso.com.br
				</a>
				<div style="margin-top: 10px;" class="clear"></div>
				<a href="#" onclick="return false;">
					<img src="imgs/phone.png" />
					17 98813-8299
				</a>
				<center>
					Z&Eacute; DO INGRESSO<br />
					&reg; todos os direitos reservados
				</center>
			</div>
		</footer>
<?php
			/* analitics */
			include_once 'ga.include';
		?>
	</body>
</html>