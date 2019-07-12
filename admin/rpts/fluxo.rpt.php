<?php

extract($_POST);


/* -------------- QUERIES ----------- */

$mysqli = BaseDAO::_get_mysqli();


$dados_gerais = $mysqli->query(
	"select 
		'Todos',
		count( ped.id ),
		sum( valor_pedido ) as valor,
		sum( valor_taxa_gateway ) as gateway,
		sum( valor_ingressos ) as ingressos,
		sum( valor_liquido - valor_ingressos )
	from pedido ped
	inner join evento e on e.id = ped.cod_evento and e.acertado = 0
	WHERE
		ped.cod_status in ( 3, 4 )
		and e.acertado = 0
");

$dados_gerais_eventos = $mysqli->query(
	"select 
		concat( date_format( ev.data, '%d/%m' ) , ' ', SUBSTRING(ev.titulo, 1, 15)  ) as evento,
		count( ped.id ),
		sum( valor_pedido ) as valor,
		sum( valor_taxa_gateway ) as gateway,
		sum( valor_ingressos ) as ingressos,
		sum( valor_liquido - valor_ingressos )
	from pedido as ped
	inner join evento ev on ev.id = ped.cod_evento and ev.acertado = 0
	WHERE
		ped.cod_status in ( 3, 4 )
		and ev.acertado = 0
	group by 
		ev.titulo
	order by
		ev.data desc
");


$medias_gerais = $mysqli->query("
select 
	count( ped.id ),
	sum( valor_pedido ) as valor,
	sum( valor_taxa_gateway ) as gateway,
	sum( valor_ingressos ) as repasse,
	sum( valor_liquido ) as valor_liquido,
	sum( valor_liquido - valor_ingressos ) as lucro
from pedido ped
inner join evento e on e.id = ped.cod_evento and e.acertado = 0
WHERE
	ped.cod_status in ( 3, 4 )
	and e.acertado = 0
");

/* -------------- QUERIES ----------- */

$pdf = new RelatorioPdf();
$pdf->titulo = (utf8_decode('Zé do Ingresso - #007 - Relatório de Fluxo Futuro'));

$pdf->AddPage();
$pdf->AliasNbPages();

$pdf->Image('img/rpt-logo.jpg',10,15,50);
$pdf->Ln(25);

/*
$pdf->SubTitle( utf8_decode("Parametros utlizados para gerar este relatório:") );

if( $id_evento == 0 ){
	$pdf->WriteHTML(
		"Evento: Totos<br />Data: de $data_range_inicial a $data_range_final"
	);
}else{
	$evento_obj = Evento::_get( $id_evento );
	$pdf->WriteHTML(
		"Evento: $evento_obj->titulo<br />Data: de $data_range_inicial a $data_range_final"
	);

}*/

$pdf->Ln(10);

$pdf->Title(utf8_decode("Análise total do período"));

$pdf->TableFromSqlResult( 
	explode(";", "Evento;Qtd. Pedidos;Receita Bruta;Taxa Gateway;Repasse Ingressos;Receita Liquida"), 
	$dados_gerais 
);
$pdf->Ln(10);

$pdf->TableFromSqlResult( 
	explode(";", "Evento;Qtd. Pedidos;Receita Bruta;Taxa Gateway;Repasse Ingressos;Receita Liquida"), 
	$dados_gerais_eventos 
);

while( $row = $medias_gerais->fetch_array() ){
	$pdf->WriteHTML( utf8_decode(
		"Entrada: R$ ".round( floatval($row["valor_liquido"]), 1 )."<br />".
		"Repasse: R$ ".round( floatval($row["repasse"]), 1 )."<br />".
		"Lucro: R$ ".round( floatval($row["lucro"]), 1 )."<br />"
		)
	);
}

$pdf->Output();

?>