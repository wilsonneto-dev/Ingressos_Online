<?php

	session_start();
	include_once 'php/config/constantes.php';
	include_once 'php/third_party/PagSeguroLibrary/PagSeguroLibrary.php';

	$possiveis_status = array(
		0 => "Checkout",
		1 => "Aguardando Pagamento",
		2 => "Em Análise",
		3 => "Pago",
		4 => "Pagamento Concluído",
		5 => "Em Disputa",
		6 => "Devolvida",
		7 => "Cancelado"	
	);

	$p = new Pedido();
	$ptemp = new TempPedido();
	$u = new Usuario();
	
	try{
		
		if ( ! isset( $_POST['notificationType'], $_POST['notificationCode'] ) ) {
			throw new Exception("Erro ao processar, parametro invalido. ErrCod.: 0201", 1);
		}

		$type = $_POST[ 'notificationType' ];  
		$code = $_POST[ 'notificationCode' ];  
		  
		NotificacaoPagSeguro::_salvar( $code , $type );

		if ($type === 'transaction') {  
		      
			$credentials = PagSeguroConfig::getAccountCredentials();
		    $transaction = PagSeguroNotificationService::checkTransaction(  
		        $credentials,  
		        $code   
		    );

		    $transaction_id = $transaction->getCode();
		    $p->codigo = $transaction->getReference();

		    if(!$p->get()){

		    	/* caso não seja o pedido na nova classe, entra aqui */
		    	$ptemp->cod_pedido = $transaction->getReference();
			    if(!$ptemp->get_by_cod_pedido()){
			    	throw new Exception("Pedido nao encontrado.", 1);
			    } 
			    // se é o pedido antio

				if ($type === 'transaction') {  
				      
					if( $ptemp->cod_pagseguro == "" ){

						$ptemp->cod_pagseguro = $transaction_id;

						// atualizar os status
						$ptemp->cod_status = $transaction->getStatus()->getValue();
						$ptemp->status = $possiveis_status[ $ptemp->cod_status ];
						
						// atualizar os valores
						$ptemp->valor_total_pago = $transaction->getGrossAmount();
						$ptemp->valor_taxa_gateway = $transaction->getFeeAmount();
						$ptemp->valor_liquido = $transaction->getNetAmount();

						$ptemp->atualizar_cod_pagseguro();
					
					}


				    if( $ptemp->cod_status != $transaction->getStatus()->getValue() ){
			    		$ptemp->cod_status = $transaction->getStatus()->getValue();
						$ptemp->status = $possiveis_status[ $ptemp->cod_status ];
						$ptemp->atualizar_status();
				    
						if( $ptemp->cod_status == "3" ){ // se foi pago
							@Email::EnviaPedido( 
"
<center><img src=\"http://wwww.zedoingresso.com.br/mail-logo.png\" /></center>
Ol&aacute; novamente $ptemp->nome!
Confirmamos o pagamento de seu pedido em nosso sistema, obrigado.

Gere uma c&oacute;pia do pedido no link abaixo, a c&oacute;pia ser&aacute; necess&aacute;ria para efetuar a troca. 

O c&oacute;digo de seu pedido &eacute;: <b>". $ptemp->cod_pedido ."</b>.
E seu c&oacute;digo de seguran&ccedil;a &eacute;: <b>". $ptemp->codigo_seguranca ."</b> (funciona como a senha de seu pedido).
Os c&oacute;digos acima ser&atilde;o necess&aacute;rios para acompanhar o status de seu pedido.

<a href=\"http://www.zedoingresso.com.br/order_check.php?order=". $ptemp->cod_pedido ."&sec=". $ptemp->codigo_seguranca ."&email=". $ptemp->email ."\">clique aqui para visualizar o pedido no site</a>

Para efetuar a troca pelo ingresso no dia do evento, ser&aacute; necess&aacute;rio: 
Uma c&oacute;pia do pedido e o CPF no qual foi efetuado o pedido.

Qualquer d&uacute;vida entre em contato com nossa equipe.

Att.,
Equipe \"Z&eacute; do Ingresso\"
Contato em: <a href=\"mailto>://".EMAIL."\">".EMAIL."</a> / ".TELEFONE."
"
					, "Pagamento Confirmado - Pedido ".$ptemp->cod_pedido." - Ze do Ingresso" , $ptemp->email 
							);

						}
				    } 
				      
				}
				LogGeral::_salvar( "Notificacao OK ( padrao antigo ): ".$_POST[ 'notificationType' ]."/".$_POST[ 'notificationCode' ].".", "Notification OK" );

		    	throw new Exception("Pedido nao encontrado.", 1);
		    	// se era pedido antigo já sai, nao encontrou pois nao era pedido novo
		    } 

		    // se o pedido da notificação já está no padrao novo...
		    $u = Usuario::_getById( $p->cod_usuario );
		    if($u == null){
		    	throw new Exception("Usuario nao encontrado.", 1);
		    } 

			if( $p->transacao == "" ){

				$p->transacao = $transaction_id;

				// atualizar os status
				$p->cod_status = $transaction->getStatus()->getValue();
				$p->status = $possiveis_status[ $p->cod_status ];
				
				// atualizar os valores
				$p->valor_total_pago = $transaction->getGrossAmount();
				$p->valor_taxa_gateway = $transaction->getFeeAmount();
				$p->valor_liquido = $transaction->getNetAmount();

				$p->atualizar_infos_gateway();
			
			}

			if( $p->cod_status != 4 ){

			    if( $p->cod_status != $transaction->getStatus()->getValue() ){
			    	
		    		$p->cod_status = $transaction->getStatus()->getValue();
					$p->status = $possiveis_status[ $p->cod_status ];
					$p->atualizar_status();
			    
					$email_txt = file_get_contents( "recursos/emails-padroes/email-pedido-pago.txt" );
					$email_txt = str_replace('$[NOME]', "$u->nome", $email_txt);
					$email_txt = str_replace('$[CODIGO_PEDIDO]', "$p->codigo", $email_txt);

					if( $p->cod_status == "3" ){ // se foi pago
						@Email::EnviaPedido( 
							$email_txt
							, "Pagamento Confirmado - Pedido ".$p->cod_pedido." - Ze do Ingresso" , $u->email 
						);

					}
			    }

			}


			LogGeral::_salvar( "Notificacao OK: ".$_POST[ 'notificationType' ]."/".$_POST[ 'notificationCode' ].": $p->codigo => $p->cod_status/$p->status ", "Notification OK" );

		}

	} 
	catch ( Exception $ex ){
		
		LogGeral::_salvar( "erro na notificacao: ".$_POST[ 'notificationType' ]."/".$_POST[ 'notificationCode' ].":  ".$ex->getMessage(), "Notification Erro" );

	}
?>
