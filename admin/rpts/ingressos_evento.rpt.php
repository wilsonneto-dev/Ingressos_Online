<?php

extract($_POST);


/* -------------- QUERIES ----------- */

$mysqli = BaseDAO::_get_mysqli();


$dados_ingressos = $mysqli->query
("
	select 
		date_format(p.data_cadastro, '%d/%m/%Y') as dt,
		p.cod_pedido,
		concat( p.nome, ' ' , p.sobrenome ) as _nome,
		p.cpf,
		p.codigo_seguranca,
		p.cod_pagseguro,
		p.telefone,
		p.email,
		case when p.ingresso_entregue <> 0 then ' ** ingresso ja entregue **' else '' end as entregue,
		p.status,
		group_concat(
			concat(' - ', i.quantidade, ': ', i.descricao, ' R$ ', i.valor_total ,'' )
			separator '<br />'
		) as ingressos
	from 
		temp_pedido p
		inner join temp_pedido_item i on i.cod_pedido = p.cod_pedido
	where
		p.cod_status in ( 3,4 )
		and p.ativo = 1
		and p.evento = '$cod_evento'
	group by
		cod_pedido
	order by
		_nome
");


$dados_ingressos_agrupados = $mysqli->query
("
	select 
		i.codigo,
		i.descricao,
		min(i.valor_ingresso) as valor_ingresso,
		sum(i.quantidade) as quantidade,
		sum(i.valor_ingresso*quantidade) as valor_ingressos
		-- sum(i.valor_total) as valor_total
	from 
		temp_pedido p
		inner join temp_pedido_item i on i.cod_pedido = p.cod_pedido
	where
		p.cod_status in ( 3,4 )
		and p.ativo = 1
		and p.evento = '$cod_evento'
	group by 
		i.codigo
	order by
		codigo
");


$dados_ingressos_total = $mysqli->query
("
	select 
		sum(i.quantidade) as quantidade,
		count(p.cod_pedido) as quantidade_pedidos,
		sum(i.valor_ingresso*quantidade) as valor_ingressos,
		sum(i.valor_total) as valor_total
	from 
		temp_pedido p
		inner join temp_pedido_item i on i.cod_pedido = p.cod_pedido
	where
		p.cod_status in ( 3,4 )
		and p.ativo = 1
		and p.evento = '$cod_evento'
");

$dados_ingressos_nao = $mysqli->query
("
	select 
		date_format(p.data_cadastro, '%d/%m/%Y') as dt,
		p.cod_pedido,
		concat( p.nome, ' ' , p.sobrenome ) as _nome,
		p.cpf,
		p.codigo_seguranca,
		p.cod_pagseguro,
		p.telefone,
		p.email,
		case when p.ingresso_entregue <> 0 then ' ** ingresso ja entregue **' else '' end as entregue,
		p.status,
		group_concat(
			concat(' - ', i.quantidade, ': ', i.descricao, ' R$ ', i.valor_total ,'' )
			separator '<br />'
		) as ingressos
	from 
		temp_pedido p
		inner join temp_pedido_item i on i.cod_pedido = p.cod_pedido
	where
		p.cod_status not in ( 3,4 )
		and p.ativo = 1
		and p.evento = '$cod_evento'
	group by
		cod_pedido
	order by
		_nome
");


/* -------------- QUERIES ----------- */

$pdf = new RelatorioPdf();
$pdf->titulo = (utf8_decode('Zé do Ingresso - #002 - Lista de Ingressos'));

$pdf->AddPage();
$pdf->AliasNbPages();

$pdf->Image('img/rpt-logo.jpg',10,15,50);
$pdf->Ln(25);

$pdf->SubTitle( utf8_decode("Parametros utlizados para gerar este relatório:") );
$pdf->WriteHTML( "Evento: $cod_evento" );
$pdf->Ln( 10 );

$pdf->Title(utf8_decode("Resumo - Pagos e Confirmados"));

while( $row = $dados_ingressos_total->fetch_array() ){
	$pdf->WriteHTML( utf8_decode(
		"Total: R$ ".round( floatval($row["valor_total"]), 2 )."<br />".
		"Valor Ingressos: R$ ".round( floatval($row["valor_ingressos"]), 2 )."<br />".
		 "Quantidade de Pedidos: ".$dados_ingressos->num_rows."<br />".
		"Quantidade de Ingressos: ".( intval($row["quantidade"]) )."<br /><br />"
		)
	);
}

while( $row = $dados_ingressos_agrupados->fetch_array() ){
	$pdf->WriteHTML( (	
		"Ingresso: <b>$row[codigo] - $row[descricao] </b> - Qtd.: <b>$row[quantidade]</b> / Valor Un.: R$ $row[valor_ingresso] / Valor Ingressos: R$ $row[valor_ingressos]<br />"
	) );
}

$pdf->Ln( 10 );

$pdf->Title(utf8_decode("Lista - Pagos e Confirmados"));


while( $row = $dados_ingressos->fetch_array() ){
	$pdf->WriteHTML( (
"<b>$row[_nome]</b> - cpf: $row[cpf] $row[entregue] ( telefone: $row[telefone] )<br />".
"$row[dt] - pedido: $row[cod_pedido] / seg: $row[codigo_seguranca] / pagseguro:$row[cod_pagseguro]
<br />$row[ingressos]
<br /><br /><br />"
	) );
}


if( $confirmados_ou_todos == "Todos" ){

	$pdf->AddPage();

	$pdf->SubTitle( utf8_decode("Parametros utlizados para gerar este relatório:") );
	$pdf->WriteHTML( "Evento: $cod_evento" );
	$pdf->Ln( 10 );

	$pdf->Title(utf8_decode("Lista - NÃO Pagos"));

	while( $row = $dados_ingressos_nao->fetch_array() ){
		$pdf->WriteHTML( (
	"<b>$row[_nome]</b> - cpf: $row[cpf] $row[entregue] ( telefone: $row[telefone] )<br />".
	"$row[dt] - pedido / $row[status]: $row[cod_pedido] / seg: $row[codigo_seguranca] / pagseguro:$row[cod_pagseguro]
	<br />$row[ingressos]
	<br /><br /><br />"
		) );
	}

}

$pdf->Output();


/*

$pdf = new RelatorioPdf();
$pdf->titulo = (utf8_decode('Eventos Rio Preto App - Relatório Analítico de Acessos'));
$pdf->AddPage();
$pdf->AliasNbPages();

$mysqli = new mysqli( BD_HOST , BD_USUARIO, BD_PASS, BD_BANCO );
$result_mensal = $mysqli->query(
"select 
	mes, 
	sum(paginas) as views, 
	-- ifnull(views_anterior,0) as views_anterior,
	( sum(paginas) - views_anterior ) as views_diferenca,
	convert( sum(paginas)/( count(*) * 7 ) , decimal(7,2) ) as avg_view,
	( count(*) * 7 ) as visitas, 
	-- ifnull(visitas_anterior,0) as visitas_anterior,
	( ( count(*) * 7 ) - visitas_anterior ) as visitas_diferenca,
	-- views_total,
	-- visitas_total,
	-- convert(views_total/visitas_total,decimal(7,2) ) as avg_view_total,
	convert( ((( count(*) * 7 )/visitas_total)*100) , decimal(5,2) ) as visitas_percentual,
	convert( (( count(*) * 7 )/count( distinct date_data )) , decimal(10,2) ) as media_visitas_dia,
	(convert( (( count(*) * 7 )/count( distinct date_data )) , decimal(10,2) ) - convert( media_visitas_dia_anterior , decimal(10,2)) ) as media_dia_diferenca
from (
		SELECT 
			DATE_FORMAT(min(data), '%m/%Y') as mes,
			DATE_FORMAT(min(data), '%d/%m/%Y %H:%i') as dt,
			ip,
			DATE(data)  AS date_data,
			DATE_FORMAT(min(data), '%d/%m/%Y') as data_str,
			( count(*) * 7 ) as paginas
			-- ,tb_total.paginas as total
		FROM tbvisita as vi
		  WHERE codprojeto = '23'
		GROUP BY ip, DATE(data)
		ORDER BY data DESC
) as sub_01
inner join 
(
	select 
	sum(paginas) as views_total, 
	( count(*) * 7 ) as visitas_total, 
	convert( sum(paginas)/( count(*) * 7 ) , decimal(7,2) ) as 'avg_view' 
	from (
			SELECT 
				DATE_FORMAT(min(data), '%m/%Y') as mes,
				DATE_FORMAT(min(data), '%d/%m/%Y %H:%i') as dt,
				ip,
				DATE(data)  AS date_data,
				DATE_FORMAT(min(data), '%d/%m/%Y') as data_str,
				( count(*) * 7 ) as paginas
				-- ,tb_total.paginas as total
			FROM tbvisita as vi
			  WHERE codprojeto = '23'
			GROUP BY ip, DATE(data)
		) as sub_sub_02
) as sub_02
left join(
		select 
			max(date_data) as dt_anterior, 
			mes as mes_atual, 
			sum(paginas) as views_anterior, 
			( count(*) * 7 ) as visitas_anterior, 
			convert( sum(paginas)/( count(*) * 7 ) , decimal(7,2) ) as avg_view_anterior,
			(( count(*) * 7 )/count( distinct date_data )) as media_visitas_dia_anterior
		from (
				SELECT 
					DATE_FORMAT(date_add(min(data), interval 1 month), '%m/%Y') as mes,
					DATE_FORMAT(min(data), '%d/%m/%Y %H:%i') as dt,
					ip,
					DATE(data)  AS date_data,
					DATE_FORMAT(min(data), '%d/%m/%Y') as data_str,
					( count(*) * 7 ) as paginas
					-- ,tb_total.paginas as total
				FROM tbvisita as vi
				  WHERE codprojeto = '23'
				GROUP BY ip, DATE(data)
				ORDER BY data DESC
		) as sub_01
		group by mes
)
as sub_03 on sub_01.mes = sub_03.mes_atual
group by mes
order by date_data;
"
);

$result_total = $mysqli->query(
"select 
	sum(paginas) as views, 
	-- ifnull(views_anterior,0) as views_anterior,
	-- ( sum(paginas) - ifnull(views_anterior,0) ) as views_diferenca,
	convert( sum(paginas)/( count(*) * 7 ) , decimal(7,2) ) as avg_view,
	( count(*) * 7 ) as visitas
	-- ifnull(visitas_anterior,0) as visitas_anterior,
	-- ( ( count(*) * 7 ) - ifnull(visitas_anterior,0) ) as visitas_diferenca
	-- views_total,
	-- visitas_total,
	-- convert(views_total/visitas_total,decimal(7,2) ) as avg_view_total,
	-- convert( ((( count(*) * 7 )/visitas_total)*100) , decimal(5,2) ) as visitas_percentual
	,convert( (( count(*) * 7 ) / count( distinct date_data )) , decimal(10,2) ) as media_visitas_dia_total
from (
		SELECT 
			DATE_FORMAT(min(data), '%m/%Y') as mes,
			DATE_FORMAT(min(data), '%d/%m/%Y %H:%i') as dt,
			ip,
			DATE(data)  AS date_data,
			DATE_FORMAT(min(data), '%d/%m/%Y') as data_str,
			( count(*) * 7 ) as paginas
			-- ,tb_total.paginas as total
		FROM tbvisita as vi
		  WHERE codprojeto = ".CODPROJETO."
		GROUP BY ip, DATE(data)
		ORDER BY data DESC
) as sub_01
inner join 
(
	select 
	sum(paginas) as views_total, 
	( count(*) * 7 ) as visitas_total, 
	convert( sum(paginas)/( count(*) * 7 ) , decimal(7,2) ) as 'avg_view' 
	from (
			SELECT 
				DATE_FORMAT(min(data), '%m/%Y') as mes,
				DATE_FORMAT(min(data), '%d/%m/%Y %H:%i') as dt,
				ip,
				DATE(data)  AS date_data,
				DATE_FORMAT(min(data), '%d/%m/%Y') as data_str,
				( count(*) * 7 ) as paginas
				-- ,tb_total.paginas as total
			FROM tbvisita as vi
			  WHERE codprojeto = ".CODPROJETO."
			GROUP BY ip, DATE(data)
		) as sub_sub_02
) as sub_02
left join(
		select 
			max(date_data) as dt_anterior, 
			mes as mes_atual, 
			sum(paginas) as views_anterior, 
			( count(*) * 7 ) as visitas_anterior, 
			convert( sum(paginas)/( count(*) * 7 ) , decimal(7,2) ) as avg_view_anterior
		from (
				SELECT 
					DATE_FORMAT(date_add(min(data), interval 1 month), '%m/%Y') as mes,
					DATE_FORMAT(min(data), '%d/%m/%Y %H:%i') as dt,
					ip,
					DATE(data)  AS date_data,
					DATE_FORMAT(min(data), '%d/%m/%Y') as data_str,
					( count(*) * 7 ) as paginas
					-- ,tb_total.paginas as total
				FROM tbvisita as vi
				  WHERE codprojeto = ".CODPROJETO."
				GROUP BY ip, DATE(data)
				ORDER BY data DESC
		) as sub_01
		group by mes
)
as sub_03 on sub_01.mes = sub_03.mes_atual"
);

// VALORES MENSAIS
$pdf->Title(utf8_decode("Acessos por Mês"));
$pdf->TableFromSqlResult( explode(";", "Mês;Views;Diferença;Views/Visita;Visitas;Diferença;%;M. Dia;Diferença"), $result_mensal );

$pdf->Ln(10);

// VALORES TOTAIS
$pdf->Title("Geral");
$pdf->TableFromSqlResult( explode(";", "Views;Views/Visita;Visitas;Média por Dia"), $result_total );


// GRÁFICO
$pdf->AddPage();
$pdf->Title(utf8_decode("Gráfico de Acessos por Mês (Real)"));

$result_mensal->data_seek(0);
$dados_grafico = array();
$dados_grafico_media = array();
while($row = $result_mensal->fetch_array())
{
	$dados_grafico[$row['mes']] = $row['visitas'];
	$dados_grafico_media[$row['mes']] = $row['media_visitas_dia'];
}
$pdf->BarDiagram(200, 12*count($dados_grafico), $dados_grafico, '%l : %v (%p)', array(100,150,255));
$pdf->Ln(20);
$pdf->Title(utf8_decode("Gráfico de Acessos por Mês (Média por Dia)"));
$pdf->BarDiagram(200, 12*count($dados_grafico_media), $dados_grafico_media, utf8_decode('%l : %v Md. Dia'), array(100,150,255));



// GERAL POR DIA DA SEMANA

$sql_dia_semana = "select 
	case dayofweek(ifnull(date_data,0))
		when 1 then 'Dom'
		when 2 then 'Seg'
		when 3 then 'Ter'
		when 4 then 'Qua'
		when 5 then 'Qui'
		when 6 then 'Sex'
		when 7 then 'Sab'
		else 0
	end as dia, 
	count( distinct data_str ) as qtd,
	sum(paginas) as views,
	convert(((sum(paginas)/views_total)*100),decimal(5,2)) as views_percentual,
	sum(paginas)/( count(*) * 7 ) as avg_views,
	( count(*) * 7 ) as visitas,
	convert(((( count(*) * 7 )/visitas_total)*100),decimal(5,2)) as visitas_percentual,
	convert((( count(*) * 7 )/count( distinct data_str )),decimal(10,2)) as media,
	convert( ((convert((( count(*) * 7 )/count( distinct data_str )),decimal(10,2))/total_media)*100), decimal(6,2)) as media_percentual
from(
		SELECT 
			DATE_FORMAT(min(data), '%m/%Y') as mes,
			DATE_FORMAT(min(data), '%d/%m/%Y %H:%i') as dt,
			ip,
			DATE(data)  AS date_data,
			DATE_FORMAT(min(data), '%d/%m/%Y') as data_str,
			( count(*) * 7 ) as paginas
			-- ,tb_total.paginas as total
		FROM tbvisita as vi
		  WHERE codprojeto = 23 -- and DATE_FORMAT(data, '%m/%Y') = '09/2013'
		GROUP BY ip, DATE(data)
		ORDER BY data DESC
) as tb
inner join(
	select sum(paginas) as views_total, ( count(*) * 7 ) as visitas_total from (
		SELECT 
			DATE_FORMAT(min(data), '%m/%Y') as mes,
			DATE_FORMAT(min(data), '%d/%m/%Y %H:%i') as dt,
			ip,
			DATE(data)  AS date_data,
			DATE_FORMAT(min(data), '%d/%m/%Y') as data_str,
			( count(*) * 7 ) as paginas
			-- ,tb_total.paginas as total
		FROM tbvisita as vi
		  WHERE codprojeto = 23 -- and DATE_FORMAT(data, '%m/%Y') = '09/2013'
		GROUP BY ip, DATE(data)
		ORDER BY data DESC
	) as tb_sub_total
) as tb_total inner join(
select sum(media) as total_media 
from(
		select  
			case dayofweek(ifnull(date_data,0))
				when 1 then 'Dom'
				when 2 then 'Seg'
				when 3 then 'Ter'
				when 4 then 'Qua'
				when 5 then 'Qui'
				when 6 then 'Sex'
				when 7 then 'Sab'
				else 0
			end as dia, 
			sum(paginas) as views,
			convert(((sum(paginas)/views_total)*100),decimal(5,2)) as views_percentual,
			sum(paginas)/( count(*) * 7 ) as avg_views,
			( count(*) * 7 ) as visitas,
			convert(((( count(*) * 7 )/visitas_total)*100),decimal(5,2)) as visitas_percentual,
			(convert((( count(*) * 7 )/count( distinct data_str )),decimal(10,2))) as media
		from(
				SELECT 
					DATE_FORMAT(min(data), '%m/%Y') as mes,
					DATE_FORMAT(min(data), '%d/%m/%Y %H:%i') as dt,
					ip,
					DATE(data)  AS date_data,
					DATE_FORMAT(min(data), '%d/%m/%Y') as data_str,
					( count(*) * 7 ) as paginas
					-- ,tb_total.paginas as total
				FROM tbvisita as vi
				  WHERE codprojeto = 23 -- and DATE_FORMAT(data, '%m/%Y') = '09/2013'
				GROUP BY ip, DATE(data)
				ORDER BY data DESC
		) as tb
		inner join(
			select sum(paginas) as views_total, ( count(*) * 7 ) as visitas_total from (
				SELECT 
					DATE_FORMAT(min(data), '%m/%Y') as mes,
					DATE_FORMAT(min(data), '%d/%m/%Y %H:%i') as dt,
					ip,
					DATE(data)  AS date_data,
					DATE_FORMAT(min(data), '%d/%m/%Y') as data_str,
					( count(*) * 7 ) as paginas
					-- ,tb_total.paginas as total
				FROM tbvisita as vi
				  WHERE codprojeto = 23 -- and DATE_FORMAT(data, '%m/%Y') = '09/2013'
				GROUP BY ip, DATE(data)
				ORDER BY data DESC
			) as tb_sub_total
		) as tb_total
		group by dayofweek(date_data)
)as tb03
) as total_percentual_media
group by dayofweek(date_data)
";

$sql_horarios_geral = "select 
	horas, 
	sum(paginas) as views, 	
	convert(( (sum(paginas)/views_total) * 100 ),decimal(10,2)) as views_percentual,
	convert(sum(paginas)/( count(*) * 7 ), decimal(10,2)) as avg_views,
	( count(*) * 7 ) as visitas,
	convert((( count(*) * 7 )/visitas_total)*100, decimal(10,2)) as visitas_percentual,
	-- datediff( date(now()),str_to_date('07/08/2013','%d/%m/%Y')) as dias,
	convert( (( count(*) * 7 )/datediff( date(now()),str_to_date('07/08/2013','%d/%m/%Y'))) , decimal(10,2) ) as media_real,
	convert( (100*((( count(*) * 7 )/datediff( date(now()),str_to_date('07/08/2013','%d/%m/%Y')))/media_real_total)) , decimal(10,2)) as media_real_percentual
	-- views_total,
	-- visitas_total,
	-- media_real_total
from(
		SELECT 
			DATE_FORMAT(min(data), '%H') as horas,
			DATE_FORMAT(min(data), '%m/%Y') as mes,
			DATE_FORMAT(min(data), '%d/%m/%Y %H:%i') as dt,
			ip,
			DATE(data)  AS date_data,
			DATE_FORMAT(min(data), '%d/%m/%Y') as data_str,
			( count(*) * 7 ) as paginas
			-- ,tb_total.paginas as total
		FROM tbvisita as vi
		  WHERE codprojeto = '23'
		GROUP BY ip, DATE(data)
		ORDER BY data DESC
) as tb_sub
inner join (
	select sum(visitas) as visitas_total, sum(views) as views_total, sum(media_real) as media_real_total from (
		select 
			horas, 
			sum(paginas) as views, 
			convert(sum(paginas)/( count(*) * 7 ), decimal(10,2)) as avg_views,
			( count(*) * 7 ) as visitas,
			datediff( date(now()),str_to_date('07/08/2013','%d/%m/%Y')) as dias,
			(( count(*) * 7 )/datediff( date(now()),str_to_date('07/08/2013','%d/%m/%Y'))) as media_real
		from(
				SELECT 
					DATE_FORMAT(min(data), '%H') as horas,
					DATE_FORMAT(min(data), '%m/%Y') as mes,
					DATE_FORMAT(min(data), '%d/%m/%Y %H:%i') as dt,
					ip,
					DATE(data)  AS date_data,
					DATE_FORMAT(min(data), '%d/%m/%Y') as data_str,
					( count(*) * 7 ) as paginas
					-- ,tb_total.paginas as total
				FROM tbvisita as vi
				  WHERE codprojeto = '23'
				GROUP BY ip, DATE(data)
				ORDER BY data DESC
		) as tb_sub
		group by horas
	) as tb_sub_sub
) as tb_sub_2
group by horas";

$result_total_dia_semana = $mysqli->query($sql_dia_semana);
$result_total_horarios = $mysqli->query($sql_horarios_geral);

$pdf->AddPage();
$pdf->Title("Geral por Dias da Semana");
$pdf->TableFromSqlResult( explode(";", "Dia;Qtd;Views;Views %;V/Visitas;Visitas;Visitas %;Média Dia;Média %"), $result_total_dia_semana );

$pdf->Ln(15);
$pdf->Title(utf8_decode("Geral por Horários"));
$pdf->TableFromSqlResult( explode(";", "Hora;Views;%;V/Visitas;Visitas;%;Média Hora;Média %"), $result_total_horarios );

$result_total_dia_semana->data_seek(0);
$dados_grafico_dias_semana_real = array();
$dados_grafico_dias_semana_media = array();
while($row = $result_total_dia_semana->fetch_array())
{
	$dados_grafico_dias_semana_real[$row['dia']] = $row['visitas'];
	$dados_grafico_dias_semana_media[$row['dia']] = $row['media'];
}
$pdf->AddPage();
$pdf->Title(utf8_decode("Gráfico - Geral por Dias da Semana (Real)"));
$pdf->BarDiagram(200, 10*count($dados_grafico_dias_semana_real), $dados_grafico_dias_semana_real, '%l : %v (%p)', array(100,150,255));

$pdf->Ln(20);
$pdf->Title(utf8_decode("Gráfico - Geral por Dias da Semana (Média por Dia)"));
$pdf->BarDiagram(200, 10*count($dados_grafico_dias_semana_media), $dados_grafico_dias_semana_media, '%l : %v (%p)', array(100,150,255));

$result_total_horarios->data_seek(0);
$dados_grafico_horario_real = array();
$dados_grafico_horario_media = array();
while($row = $result_total_horarios->fetch_array())
{
	$dados_grafico_horario_real[$row['horas']] = $row['visitas'];
	$dados_grafico_horario_media[$row['horas']] = $row['media_real'];
}
*/
?>