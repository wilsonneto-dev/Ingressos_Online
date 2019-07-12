<?php

	session_start();
	include_once 'php/config/constantes.php';

	$p = new TempPedido();
	$transaction_id = "";

	function pedido_html( TempPedido $pedido ) {
		$items_html = "";
		$itens = TempPedidoItem::_get_by_cod_pedido( $pedido->cod_pedido );
		foreach ($itens as $cod => $item) {
			$items_html .= "
				&nbsp;&nbsp;-
				<b>$item->descricao</b> 
				&nbsp;&nbsp;&nbsp;&nbsp;
				qtd.: $item->quantidade<br />
			";
		}
		$mensagem = "";
		if( $pedido->cod_status != "3" &&  $pedido->cod_status != "4" ){
			$mensagem = '<div><span class="status_1"><center>* Pagamento n&atilde;o confirmado<br />Ap&oacute;s efetuar o pagamento gere novamente esta c&oacute;pia</center></span></div><br /><br />';
		}

		$html = '
<div class="pedido_wrapper">
	'.$mensagem.'
	<section class="dados">
		<div class="a_right">
			<b>Pedido C&oacute;digo: </b>
			<span class="status_'.$pedido->cod_status.'">'.$pedido->cod_pedido.'</span> /
			<span class="status_'.$pedido->cod_status.'">'.$pedido->status.'</span> /
			'.date_format($pedido->data_cadastro, 'd/m/Y').'
		</div>
		<b>Comprador: </b>'.$pedido->nome.' '.$pedido->sobrenome.'<br />
		<b>CPF</b>:'.$pedido->cpf.' <br />
		<b>Cidade: </b>'.$pedido->cidade.'-'.$pedido->uf.' <br />
		<b>C&oacute;digo de Seguran&ccedil;a: </b>'.$pedido->codigo_seguranca.'<br />
		<br />
		<b>Evento: </b><br /> '.$pedido->evento.' <br />
	</section>
	<img style="float: right" src="imgs/logo-black.png">
	<section class="itens">
		<br />Ingressos:<br /><br />
		'.$items_html.'
	</section>
	<div class="clear"><br /></div>
	<center><img width="350" src="barcode.php?filetype=PNG&dpi=72&scale=2&rotation=0&font_family=0&font_size=0&text=123456789012345678&thickness=30&start=NULL&code=BCGcode128&order='.$pedido->cod_pedido.'&sec='.$pedido->codigo_seguranca.'&email='.$pedido->email.'"></center>
</div>
		';
		return $html;
	}
	
	try{
		
		if( isset($_SESSION["cod_pedido"]) ){
			$p->cod_pedido = $_SESSION["cod_pedido"];
			if( !$p->get_by_cod_pedido() ){
				throw new Exception( "Pedido não encontrado", "0301" );
			}
			unset($_SESSION["cod_pedido"]);
		}else if ( isset($_POST["txt_cod_pedido"], $_POST["txt_email"],  $_POST["txt_codigo_seguranca"] ) ){
			$p->cod_pedido = str_replace("'", "", $_POST["txt_cod_pedido"] );
			$p->email = str_replace("'", "", $_POST["txt_email"] );
			$p->codigo_seguranca = str_replace("'", "", $_POST["txt_codigo_seguranca"] );
			if( !$p->get_by_cod_pedido_email() ){
				throw new Exception( "Pedido não encontrado", "0302" );
			}
		}else{
			throw new Exception("Parametros invalidos, pedido nao encontrado. ErrCod.: 0304", 4);
			
		}


?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Zé do Ingresso - Status do Pedido</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="description" content="Compre seu ingresso para as principais baladas de Rio Preto e Região com o Zé! Venda de ingressos em Rio Preto e região." />
		<meta name="keywords" content="venda de ingresso, Rio Preto, São José do Rio Preto, ingressos" />
		<link rel="stylesheet" href="css/style.css"></link>
		<link rel="stylesheet" media="print" href="css/style-print.css"></link>
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

		<!-- content -->
		<div class="message_content" style="margin: 5px 0;">
			<?php  
				echo( pedido_html( $p ) );
			?>
			<br />
			<div class="h">
				<a class="printer" href="#" onclick="window.print(); return false;" target="_blank"> 
					<img src="imgs/printer.png" />
					<small>clique aqui para imprimir uma c&oacute;pia</small>
				</a>
				<br /><br />
				Para efetuar a troca pelo ingresso no dia do evento, ser&aacute; necess&aacute;rio:<br /> 
				Uma c&oacute;pia impressa do pedido e o CPF no qual foi efetuado o pedido.<br />
				Qualquer d&uacute;vida entre em contato com nossa equipe.
				<br />
			</div>
			<br /><br />
			<a href="order_check.php" class="btn_voltar" value="comprar">voltar</a>
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
					17 9 8813-8299
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

<?php

	} 
	catch ( Exception $ex ){

		$erro = "<br />
<b>Erro</b>: ".$ex->getMessage()."<br /><br />
<b>Pedido</b>: ".print_r( $p, true )."<br /><br />
<b>Transaction_id</b>: ".$transaction_id."<br /><br />---
	";

	_log( $erro, "erro" );
	Email::enviaErro( $erro );

?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Noite do Branco - Ingressos</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="description" content="Compre seu ingresso para a Noite do Branco 2014 com o Zé! Venda de ingressos em Rio Preto e região." />
		<meta name="keywords" content="venda de ingresso, Noite do Branco, Rio Preto, São José do Rio Preto, ingressos" />
		<link rel="stylesheet" href="css/style.css"></link>
		<link rel="stylesheet" media="print" href="css/style-print.css"></link>
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
			<span class="soon">SITE EM BREVE, AGUARDE...</span>
		</header>

		<!-- content -->
		<div class="message_content">
			<h2>
				Houve um problema, aguarde alguns instantes e tente novamente.<br />
				Se o erro persistir entre em contato com a nossa equipe.
				<br /><br />
				<?php _echo( $ex->getMessage() ); ?>
				<br /><br />
			</h2>
			<a href="order_check.php" class="btn_voltar">voltar</a>
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
				<center>
					Z&Eacute; DO INGRESSO<br />
					&reg; todos os direitos reservados
				</center>
			</div>
		</footer>

	</body>
</html>

<?php
	}
?>
