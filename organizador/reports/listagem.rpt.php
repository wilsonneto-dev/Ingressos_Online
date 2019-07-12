<?php


include_once '../../php/config/constantes.php';
include_once '../../php/third_party/fpdf16/fpdf.php';
include_once '../../php/util/RelatorioPdf.classe.php';

/* --------------------- VALIDAÇÃO ------------------------ */

// iniciar sessão
session_start();

// validar usuário (se não a sessão "usuario" é q não está logado)
if(!isset($_SESSION["organizador"])) 
   header("Location: /organizador/login");

// passar as sessões para variáveis
$organizador = $_SESSION["organizador"];

// verificar se o evento existe
$id_url = isset($_GET["url"]) ? $_GET["url"] : "";
$obj = Evento::_getByUrl( $id_url );
if( $obj== null ){
  header("Location: /organizador/404");
  die();
}

/* verificar se o promoter realmente tem permissão neste evento */
if( $obj->cod_promoter != $organizador->id ){
  header("Location: /organizador/404");
  die();
}

$local = Local::_get( $obj->cod_local );
$cidade = Cidade::_get( $local->cod_cidade );


/* -------------- QUERIES ----------- */

$mysqli = BaseDAO::_get_mysqli();

$dados_ingressos = $mysqli->query
("
	select 
		date_format(p.data, '%d/%m/%Y') as dt,
		p.codigo as cod_pedido,
		concat( u.nome, ' ' , u.sobrenome ) as _nome,
		u.cpf,
		p.transacao cod_pagseguro,
		concat( u.ddd, ' ', u.telefone) as telefone,
		u.email,
		p.status,
		group_concat(
			concat(' - ', i.quantidade, ': ', ing.descricao, ' R$ ', i.valor_total ,'' )
			separator '<br />'
		) as ingressos
	from 
		pedido p
		inner join usuario u on u.id = p.cod_usuario
		inner join pedido_item i on i.cod_pedido = p.codigo
		inner join ingresso ing on ing.id = i.cod_ingresso
	where
		p.cod_status in ( 3,4 )
		and p.cod_evento = $obj->id
	group by
		p.codigo
	order by
		_nome
");


$dados_ingressos_agrupados = $mysqli->query
("
	select 
		ing.id as codigo,
		ing.descricao,
		min(i.valor_ingresso) as valor_ingresso,
		sum(i.quantidade) as quantidade,
		sum(i.valor_ingresso*quantidade) as valor_ingressos
		-- sum(i.valor_total) as valor_total
	from 
		pedido p
		inner join pedido_item i on i.cod_pedido = p.codigo
		inner join ingresso ing on ing.id = i.cod_ingresso
	where
		p.cod_status in ( 3,4 )
		and p.cod_evento = $obj->id
	group by 
		ing.id
	order by
		ing.id
");


$dados_ingressos_total = $mysqli->query
("
	select 
		sum(i.quantidade) as quantidade,
		count(distinct p.codigo) as quantidade_pedidos,
		sum(i.valor_ingresso*quantidade) as valor_ingressos,
		sum(i.valor_total*quantidade) as valor_total
	from 
		pedido p
		inner join pedido_item i on i.cod_pedido = p.codigo
	where
		p.cod_status in ( 3,4 )
		and p.cod_evento = $obj->id
");


/* -------------- QUERIES ----------- */

$pdf = new RelatorioPdf();
$pdf->titulo = (utf8_decode('Zé do Ingresso - #02-002 - Listagem dos Ingressos'));

$pdf->AddPage();
$pdf->AliasNbPages();

$pdf->Image('../imgs/rpt-logo.jpg',10,15,50);
$pdf->Ln(25);

$pdf->SubTitle( utf8_decode("Parametros utlizados para gerar este relatório:") );
$pdf->WriteHTML( utf8_decode( "Evento: $obj->titulo"). "<br />" );
$pdf->WriteHTML( utf8_decode( "Organização: $organizador->razao_social" ) );

/*
$pdf->TableFromSqlResult( 
	explode(";", "Total de Vendas;Taxa Gateway;Retirada PagSeguro;Repasse Ingressos;Liquido da Operação"), 
	$dados_gerais 
);

*/ 
$pdf->Ln( 10 );

$pdf->Title(utf8_decode("Resumo das Vendas"));

while( $row = $dados_ingressos_total->fetch_array() ){
	$pdf->WriteHTML( utf8_decode(
		"Valor Ingressos: R$ ".round( floatval($row["valor_ingressos"]), 2 )."<br />".
		 "Quantidade de Pedidos: ".(intval($row["quantidade_pedidos"]) )."<br />".
		"Quantidade de Ingressos: ".( intval($row["quantidade"]) )."<br /><br />"
		)
	);
}

while( $row = $dados_ingressos_agrupados->fetch_array() ){
	$pdf->WriteHTML( (	
		"Ingresso: <b>$row[codigo] - $row[descricao] </b> - Qtd.: <b>$row[quantidade]</b> / Valor Un.: R$ $row[valor_ingresso] / Valor Ingressos: R$ $row[valor_ingressos]<br />"
	) );
}

/*
$pdf->AddPage();
*/ 


$pdf->Ln( 10 );

$pdf->Title(utf8_decode("Lista - Confirmados"));


while( $row = $dados_ingressos->fetch_array() ){
	$pdf->WriteHTML( (
"<b>$row[_nome]</b> - cpf: $row[cpf] ( telefone: $row[telefone] )<br />".
"$row[dt] - pedido: $row[cod_pedido] / pagseguro:$row[cod_pagseguro]
<br />$row[ingressos]
<br /><br /><br />"
	) );
}

$pdf->Output();

?>