<?php

include_once 'php/third_party/PagSeguroLibrary/PagSeguroLibrary.php';

$header_extra_styles = '';
$footer_extra_scripts = '';

$_head_title .= "";
$_meta_keywords .= "";
$_meta_description .= "";

$msg = "";
	
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

try {

	$p = new Pedido();
	$transaction_id = "";

	if ( ! isset($_GET["transaction_id"]) ) {
		throw new Exception("Erro ao processar, parametro invalido. ErrCod.: 0201", 1);
	}

	$transaction_id = $_GET["transaction_id"];
	
	if ( $transaction_id == "" ) {
		throw new Exception("Erro ao processar, parametro invalido. ErrCod.: 0202", 1);
	}

	// recuperando a transação
	$credentials = PagSeguroConfig::getAccountCredentials();
	$transaction = PagSeguroTransactionSearchService::searchByCode( $credentials, $transaction_id );  

	
	$p->codigo = $transaction->getReference();
	if( !$p->get() ){
		throw new Exception("Pedido nao encontrado. ErrCod.: 0203", 1);
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

	$_SESSION[S_MENSAGEM_OK] = "Pedido registrado com sucesso! Assim que o pagamento for confirmado te enviaremos um e-mail. Muito obrigado! :D";

	header("Location: /usuario-pedidos");
	die();

} catch (Exception $e) {
	$msg = "Desculpe, ocorreu um erro: ".$e->getMessage();
	if( $global_usuario == null ){
		LogGeral::_salvar( "erro no chekout: (u:null) ".$e->getMessage(), "Checkout Erro" );
	}else{
		LogGeral::_salvar( "erro no chekout: (u: #$global_usuario->id/$global_usuario->email/$global_usuario->nome ) ".$e->getMessage(), "Checkout Erro" );
	}  
}



?>