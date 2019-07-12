<?php

include_once 'php/third_party/PagSeguroLibrary/PagSeguroLibrary.php';

$header_extra_styles = '';
$footer_extra_scripts = '';

$_head_title .= "";
$_meta_keywords .= "";
$_meta_description .= "";

$msg = "";

try {
	
	$post = null;
	
	// se veio da pg de eventos zerar todas a ssessões
	if( $_SERVER["REQUEST_METHOD"] == "POST" ){
		unset( $_SESSION[S_POST] );
		unset( $_SESSION[S_FLAG] );
		if(isset($_POST))
			$post = $_POST;
	}

	if( $post == null && isset( $_SESSION[S_POST] ) ){
		$post = $_SESSION[S_POST];
		unset( $_SESSION[S_POST] );
	}

	$id_url = isset( $post[ "url" ] ) ? $post[ "url" ] : "";

	$evento = Evento::_getByUrl( $id_url );

	if( $evento== null ){
		header("Location: /404");
		die();
	}

	if( $evento->visivel == 0 ){
		header("Location: /404");
		die();
	}

	$flag = "";

	if( 
		$post != null // se é post ou veio de uma "continuação" de login ou cadastro 
	){

		if( $post["as"] != "" ) {
			header( "Location: /404" );
			die();
		}

		if( $global_usuario == null ){
			$_SESSION[S_POST] = $post; // gravar o post em uma sessão
			$_SESSION[S_FLAG] = "LOGIN_PEDIDO";
			header( "Location: /login" );
			die();

		}

		/* pegando apenas os referentes a ingressos */
		$itens_post = $post;
	 	unset( $itens_post["as"] );
	 	unset( $itens_post["url"] );
	 	
	 	$valor_total_pedido = 0;
	 	$valor_total_ingressos = 0;

	 	/* colocando os itens do pedido em um array */
		$pedido_itens = array();

		foreach ($itens_post as $k => $v) {
			
			if( intval( $v ) ){ // se há quantidade

				// pegar o ingresso
				$id_ingresso = str_replace("t", "", $k);
				$ingresso = Ingresso::_get( $id_ingresso );

				if($ingresso == null)
					throw new Exception("Ingresso $id_ingresso não encontrado...", 1);
				
				if( $ingresso->cod_evento != $evento->id )
					throw new Exception("Ingresso selecionado inválido para o evento $evento->titulo", 1);
				
				$pedido_item = new PedidoItem();
				$pedido_item->cod_ingresso = $ingresso->id;
				$pedido_item->quantidade = $v;
				$pedido_item->valor_ingresso = $ingresso->valor;
				$pedido_item->valor_taxa = ( ( $ingresso->valor * $ingresso->taxa_percentual ) / 100 ) + $ingresso->taxa_fixa;
				$pedido_item->valor_total = ( $pedido_item->valor_ingresso + $pedido_item->valor_taxa );

				$valor_total_pedido += ( $pedido_item->valor_total * $pedido_item->quantidade );
				$valor_total_ingressos += ( $pedido_item->valor_ingresso * $pedido_item->quantidade );

				$pedido_item->ingresso = $ingresso;

				$pedido_itens[] = $pedido_item;

			}

		}

		/* cadastrando o pedido */
		$ped = new Pedido();
		$ped->codigo = /*date("y"). "16" . */ $evento->id . str_pad( Pedido::_proximo_codigo(), 6, "0", STR_PAD_LEFT );
		$ped->cod_usuario = $global_usuario->id;
		$ped->cod_evento = $evento->id;
		$ped->ref = isset($_SESSION[S_REF]) ? $_SESSION[S_REF] : "";
		$ped->hash = gerar_hash(11);; 
		$ped->valor_pedido = $valor_total_pedido; 
		$ped->valor_ingressos = $valor_total_ingressos; 
		$ped->status = "Checkout"; 
		$ped->cod_status = 0; 
		$ped->transacao = ""; 
		// print_r(  );

		// gravando o pedido e os itens
		$cadastrou = $ped->cadastrar();
		if($cadastrou == false)
			throw new Exception("Erro ao efetuar o pedido. Tente novamente em instantes.", 1);
			
		foreach ( $pedido_itens as $i ) { 
			$i->cod_pedido = $ped->codigo;
			$i->cadastrar(); 
		}

		/* enviando ao pagseguro */
		
		// enviando ao pagseguro
		$paymentRequest = new PagSeguroPaymentRequest();  
		foreach ( $pedido_itens as $item ) { 
			$paymentRequest->addItem( 
				$item->ingresso->id, 
				$evento->titulo . ' - ' . $item->ingresso->descricao, 
				$item->quantidade, 
				$item->valor_total 	
			); 
		}

		$paymentRequest->setSender(  
		    $global_usuario->nome." ".$global_usuario->sobrenome,   
		    $global_usuario->email,   
		    $global_usuario->ddd,   
		    $global_usuario->telefone  
		);  		

		$paymentRequest->setCurrency("BRL"); // moeda brasileira
		$paymentRequest->setShippingType(1); // tanto faz, nao tera entrega

	   	$paymentRequest->setReference( $ped->codigo ); // 

		$paymentRequest->addParameter( "senderCPF", cpf_num( $global_usuario->cpf ) );  
		$paymentRequest->setRedirectURL( "http://zedoingresso.com.br/checkout_return" );

		// registrando no pagseguro
		$credentials = PagSeguroConfig::getAccountCredentials();
		$url = $paymentRequest->register( $credentials );

		$ped->enviou_ao_gateway();

		// gravar log
		LogUsuario::_salvar( "Usuario #$global_usuario->id/$global_usuario->email/$global_usuario->nome iniciou/checkout pedido $ped->codigo $ped->valor_pedido.", "Checkout", $global_usuario->id );

		// redirecionar a pagina do pagseguro
		header("Location: $url");
		
	}


} 
catch ( PagSeguroServiceException $e) {  
    $msg = "Desculpe, ocorreu um erro: ".$e->getMessage();
	if( $global_usuario == null ){
		LogGeral::_salvar( "erro no chekout: (u:null) ".$e->getMessage(), "Checkout Erro" );
	}else{
		LogGeral::_salvar( "erro no chekout: (u: #$global_usuario->id/$global_usuario->email/$global_usuario->nome ) ".$e->getMessage(), "Checkout Erro" );
	}
}  
catch (Exception $e) {
	$msg = "Desculpe, ocorreu um erro: ".$e->getMessage();
	if( $global_usuario == null ){
		LogGeral::_salvar( "erro no chekout: (u:null) ".$e->getMessage(), "Checkout Erro" );
	}else{
		LogGeral::_salvar( "erro no chekout: (u: #$global_usuario->id/$global_usuario->email/$global_usuario->nome ) ".$e->getMessage(), "Checkout Erro" );
	}  
}



?>