<?php 

// valida permissão
if( !in_array( "analises", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$data_inicial = "";
$data_final = "";
$extra_titulo = "";

if( isset($_POST["data_range_inicial"],$_POST["data_range_final"] ) ){
    $data_inicial = data($_POST["data_range_inicial"]);
    $data_final = data($_POST["data_range_final"]);
}else{
    /* se nao veio nada pega de ontem */
    $extra_titulo = " - &Uacute;timos 7 dias";
    $data_inicial = data(date('d/m/Y'))->sub(new DateInterval('P6D'));
    $data_final = data(date('d/m/Y')) /* ->sub(new DateInterval('P1D')) */;
}

/* sqls */
$r_box_quantidades = new Repeater();
$r_box_quantidades->campos = "qtd_pedidos;qtd_eventos;qtd_ingressos";
$r_box_quantidades->sql = "
    select 
        count( distinct ped.codigo ) as qtd_pedidos,
        count( distinct ped.cod_evento ) as qtd_eventos,
        ifnull( sum( item.quantidade ) , 0)  as qtd_ingressos
    from 
        pedido ped
        inner join pedido_item item on ped.codigo = item.cod_pedido
    WHERE
        ped.cod_status in ( 3, 4 )
        and ped.data > str_to_date( '".$data_inicial->format("d/m/Y")."','%d/%m/%Y' )
        and ped.data < date_add( str_to_date( '".$data_final->format("d/m/Y")."','%d/%m/%Y' ), interval 1 day )
        and 0 in ( ped.cod_evento, 0 )
";
$r_box_quantidades->txtItem = '
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>
                    #qtd_pedidos
                </h3>
                <p>
                    Pedidos
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="/admin/?pg=Pedidos" class="small-box-footer">
                Pedidos <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-teal">
            <div class="inner">
                <h3>
                    #qtd_ingressos
                </h3>
                <p>
                    Ingressos
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-ios7-pricetag-outline"></i>
            </div>
            <a href="/admin/?pg=Pedidos" class="small-box-footer">
                Pedidos <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>
                    #qtd_eventos
                </h3>
                <p>
                    Eventos
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a href="/admin/?pg=Pedidos" class="small-box-footer">
                Pedidos <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div><!-- ./col -->

';
$r_box_quantidades->exec();



$r_tabelas_vendas = new Repeater();
$r_tabelas_vendas->campos = "id;titulo;pedidos;ingressos;bruto;liquido";
$r_tabelas_vendas->sql = "
select 
    ev.id,
    ev.titulo,
    count(distinct codigo) as pedidos,
    sum( quantidade ) as ingressos,
    sum(valor_pedido) as bruto,
    sum(liquido) as liquido
from
(
select 
    ped.codigo,
    ped.cod_evento,
    ped.valor_pedido,
    ped.valor_liquido - ped.valor_ingressos as liquido,
    sum( item.quantidade ) as quantidade
from 
    pedido ped
    inner join pedido_item item on ped.codigo = item.cod_pedido
WHERE
    ped.cod_status in ( 3, 4 )
    and ped.data > str_to_date( '".$data_inicial->format("d/m/Y")."','%d/%m/%Y' )
    and ped.data < date_add( str_to_date( '".$data_final->format("d/m/Y")."','%d/%m/%Y' ), interval 1 day )
    and 0 in ( ped.cod_evento, 0 )
group by 
    ped.codigo
) as tb
inner join evento ev on ev.id = cod_evento
group by 
    ev.id
order by
    liquido desc";
$r_tabelas_vendas->txtItem = '
    <tr>
        <td>#id</td>
        <td>#titulo</td>
        <td>#pedidos</td>
        <td>#ingressos</td>
        <td>#bruto</td>
        <td>#liquido</td>
    </tr>';
$r_tabelas_vendas->exec();


$r_box_valores = new Repeater();
$r_box_valores->campos = "valor_bruto;ticket_medio;valor_liquido";
$r_box_valores->sql = "
select 
    count(codigo) as qtd_pedidos,
    sum(valor_pedido) as valor_bruto,
    cast( sum(valor_pedido) / count(codigo) as decimal(10,2)) as ticket_medio,  
    sum(liquido) as valor_liquido 
from (
    select 
        ped.codigo,
        ped.valor_pedido,
        ped.valor_liquido - ped.valor_ingressos as liquido,
        sum( item.quantidade ) as quantidade
    from 
        pedido ped
        inner join pedido_item item on ped.codigo = item.cod_pedido
    WHERE
        ped.cod_status in ( 3, 4 )
        and ped.data > str_to_date( '".$data_inicial->format("d/m/Y")."','%d/%m/%Y' )
        and ped.data < date_add( str_to_date( '".$data_final->format("d/m/Y")."','%d/%m/%Y' ), interval 1 day )
        and 0 in ( ped.cod_evento, 0 )
    group by 
        ped.codigo
) as tb";
$r_box_valores->txtItem = '
        <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>
                    <sup style="font-size: 20px">R$</sup>#ticket_medio
                </h3>
                <p>
                    Ticket M&eacute;dio
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="/admin/?pg=Pedidos" class="small-box-footer">
                Pedidos <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-purple">
            <div class="inner">
                <h3>
                    <sup style="font-size: 20px">R$</sup>#valor_liquido
                </h3>
                <p>
                    Receita Liq.
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-ios7-briefcase-outline"></i>
            </div>
             <a href="/admin/?pg=Pedidos" class="small-box-footer">
                Pedidos <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>
                    <sup style="font-size: 20px">R$</sup>#valor_bruto
                </h3>
                <p>
                    Receita Bruta
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-ios7-cart-outline"></i>
            </div>
            <a href="/admin/?pg=Pedidos" class="small-box-footer">
                Pedidos <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div><!-- ./col -->


';
$r_box_valores->exec();

$r_box_usuarios = new Repeater();
$r_box_usuarios->campos = "qtd_usuarios";
$r_box_usuarios->sql = "
    select 
        count(*) as qtd_usuarios
    from 
        usuario u
    WHERE
        u.datacadastro > str_to_date( '".$data_inicial->format("d/m/Y")."','%d/%m/%Y' )
        and u.datacadastro < date_add( str_to_date( '".$data_final->format("d/m/Y")."','%d/%m/%Y' ), interval 1 day )
";
$r_box_usuarios->txtItem = '
        <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>
                    #qtd_usuarios
                </h3>
                <p>
                    Usu&aacute;rios Cadastrados
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="/admin/?pg=Usuario" class="small-box-footer">
                Usu&aacute;rios <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div><!-- ./col -->

';
$r_box_usuarios->exec();



$r_receita_semanal = new Repeater();
$r_receita_semanal->campos = "sdia;liquido";
$r_receita_semanal->sql = "
    select 
        concat( yearweek( dia ) , ' - ', date_format( min(dias.dia), '%d/%m' ) ) as sdia,
        count( ped.id ) as qtd_pedidos,
        sum( ifnull(valor_pedido,0) ) as faturamento,
        ifnull( sum( valor_liquido - valor_ingressos ) , 0) as liquido
    from calendario as dias
    left join pedido ped 
        on dias.dia = date(ped.data)
        and ped.cod_status in ( 3, 4 )
    where
        dia >= date_add( CURDATE(), interval -4 month  )
        and dia < date_add( CURDATE(), interval 1 day )
    group by 
        yearweek( dia )
    order by
        dia
";
$r_receita_semanal->txtItem = ",{y: '#sdia', Vendas: #liquido}";
$r_receita_semanal->exec();

$pedidos_ingressos_semanal = new Repeater();
$pedidos_ingressos_semanal->campos = "sdia;qtd_pedidos;qtd_ingressos";
$pedidos_ingressos_semanal->sql = "
select 
    concat( yearweek( dia ) , ' - ', date_format( min(dias.dia), '%d/%m' ) ) as sdia,
    count( distinct ped.id ) as qtd_pedidos,
    ifnull( sum( item.quantidade ) , 0 )as qtd_ingressos
from calendario as dias
left join pedido ped 
    on dias.dia = date(ped.data)
    and ped.cod_status in ( 3, 4 )
left join pedido_item item on item.cod_pedido = ped.codigo
where
    dia >= date_add( CURDATE(), interval -4 month  )
    and dia < date_add( CURDATE(), interval 1 day )
group by 
    yearweek( dia )
order by
    dia
";
$pedidos_ingressos_semanal->txtItem = ",{y: '#sdia', Pedidos: #qtd_pedidos, Ingressos: #qtd_ingressos}";
$pedidos_ingressos_semanal->exec();


$r_faturamento_mensal = new Repeater();
$r_faturamento_mensal->campos = "mes;faturamento";
$r_faturamento_mensal->sql = "
    select 
        concat(year(dia),'.',month( dia )) as mes,
        count( ped.id ) as qtd_pedidos,
        sum( ifnull(valor_pedido,0) ) as faturamento,
        ifnull( sum( valor_liquido - valor_ingressos ) , 0) as liquido
    from calendario as dias
    left join pedido ped 
        on dias.dia = date(ped.data)
        and ped.cod_status in ( 3, 4 )
    where
        dia >= date_add( CURDATE(), interval -6 month  )
        and dia < date_add( CURDATE(), interval 1 day )
    group by 
        year(dia), 
        month( dia )
    order by
        dia
";
$r_faturamento_mensal->txtItem = ",{y: '#mes', Faturamento: #faturamento }";
$r_faturamento_mensal->exec();

$r_receita_mensal = new Repeater();
$r_receita_mensal->campos = "mes;liquido";
$r_receita_mensal->sql = "
    select 
        concat(year(dia),'.',month( dia )) as mes,
        count( ped.id ) as qtd_pedidos,
        sum( ifnull(valor_pedido,0) ) as faturamento,
        ifnull( sum( valor_liquido - valor_ingressos ) , 0) as liquido
    from calendario as dias
    left join pedido ped 
        on dias.dia = date(ped.data)
        and ped.cod_status in ( 3, 4 )
    where
        dia >= date_add( CURDATE(), interval -6 month  )
        and dia < date_add( CURDATE(), interval 1 day )
    group by 
        year(dia), 
        month( dia )
    order by
        dia
";
$r_receita_mensal->txtItem = ",{y: '#mes', Receita: #liquido }";
$r_receita_mensal->exec();


/* fim sqls */

$menu_destaque = "analises";

$page = new StdAdminPage();
$page->page = "DashBoard";

$page->cadastrar = false;

$page->title = "Dashboard $extra_titulo <small>( ".$data_inicial->format("d/m/Y") . " - ". $data_final->format("d/m/Y")." )</small>";

$page->html_content = '

<!-- Small boxes (Stat box) -->
<div class="row">
    
    '.$r_box_quantidades->html.'
    '.$r_box_usuarios->html.'


</div><!-- /.row -->
<div class="row">
    '.$r_box_valores->html.'
</div>

<div class="box-body table-responsive">
    <table class="table table-bordered table-hover data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Evento</th>
                <th>Pedidos</th>
                <th>Ingressos</th>
                <th>Fat.</th>
                <th>Liq.</th>
            </tr>
        </thead>
        <tbody>
            '.$r_tabelas_vendas->html.'
        </tbody>
    <table>
<div>

<div class="row">
    <div class="col-md-6">

        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Ingressos e Pedidos Semanal</h3>
            </div>
            <div class="box-body chart-responsive">
                <div class="chart" id="revenue-chart" style="height: 300px;"></div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

        <div class="box box-danger">
            <div class="box-header">
                <h3 class="box-title">Receita Mensal</h3>
            </div>
            <div class="box-body chart-responsive">
                <div class="chart" id="sales-chart" style="height: 300px; position: relative;"></div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

    </div><!-- /.col (LEFT) -->

    <div class="col-md-6">

        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Receita Semanal</h3>
            </div>
            <div class="box-body chart-responsive">
                <div class="chart" id="line-chart" style="height: 300px;"></div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Faturamento Bruto Mensal</h3>
            </div>
            <div class="box-body chart-responsive">
                <div class="chart" id="bar-chart" style="height: 300px;"></div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

    </div><!-- /.col (RIGHT) -->
</div><!-- /.row -->

<div class="box-body">
    <form method="post">                                
        <div class="form-group">
            <label for="data_range_inicial">Data inicial *</label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input type="text" value="'.$data_inicial->format("d/m/Y").'" class="form-control pull-right obrigatorio data" id="data_range_inicial" required="1" name="data_range_inicial">
            </div><!-- /.input group -->
        </div><!-- /.form group -->
    
        <div class="form-group">
            <label for="data_range_final">Data final *</label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input type="text"  value="'.$data_final->format("d/m/Y").'" class="form-control pull-right obrigatorio data" id="data_range_final" required="1" name="data_range_final">
            </div><!-- /.input group -->
        </div><!-- /.form group -->
    
        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Atualizar P&aacute;gina</button>
        </div>
    </form>
</div>


';
addOnloadScript("
            $(function() {
                'use strict';

                // AREA CHART
                var area = new Morris.Line({
                    element: 'revenue-chart',
                    resize: true,
                    data: [
                        ".substr( $pedidos_ingressos_semanal->html, 1 )."
                    ],
                    xkey: 'y',
                    ykeys: ['Pedidos', 'Ingressos'],
                    labels: ['Pedidos', 'Ingressos'],
                    lineColors: ['#a0d0e0', '#3c8dbc'],
                    hideHover: 'auto'
                });

                // LINE CHART
                var line = new Morris.Line({
                    element: 'line-chart',
                    resize: true,
                    data: [
                        ".substr($r_receita_semanal->html,1)."
                    ],
                    xkey: 'y',
                    ykeys: ['Vendas'],
                    labels: ['Receita'],
                    lineColors: ['#3c8dbc'],
                    hideHover: 'auto'
                });

                var bar2 = new Morris.Bar({
                    element: 'sales-chart',
                    resize: true,
                    data: [
                        ".substr($r_receita_mensal->html,1)."
                    ],
                    barColors: ['#00a65a', '#f56954'],
                    xkey: 'y',
                    ykeys: ['Receita'],
                    labels: ['Receita'],
                    hideHover: 'auto'
                });

                var bar = new Morris.Bar({
                    element: 'bar-chart',
                    resize: true,
                    data: [
                        ".substr($r_faturamento_mensal->html,1)."
                    ],
                    barColors: ['#f56954', '#f56954'],
                    xkey: 'y',
                    ykeys: ['Faturamento'],
                    labels: ['Faturamento'],
                    hideHover: 'auto'
                });
            });
");


$page->render();

/*
$r = new Repeater();
$r->campos = "nome;email;cadastro_t;acesso_t;ultimo_pedido_t;cadastro;id;acesso;ultimo_pedido;faturamento;fat6m;qtd_pedidos;ped6m";
$r->sql = "
SELECT 
	concat( u.nome, ' ', u.sobrenome ) as nome,
	u.email,
	u.id,
	DATE_FORMAT(u.datacadastro, '%d/%m/%y') as cadastro,
	DATE_FORMAT(u.ultimo_acesso, '%d/%m/%y') as acesso,
	DATE_FORMAT(max(p.data), '%d/%m/%y') as ultimo_pedido,
	DATE_FORMAT(u.datacadastro, '%d/%m/%Y %h:%i') as cadastro_t,
	DATE_FORMAT(u.ultimo_acesso, '%d/%m/%Y %h:%i') as acesso_t,
	DATE_FORMAT(max(p.data), '%d/%m/%Y %h:%i') as ultimo_pedido_t,
	ifnull( sum(p.valor_pedido) , 0) as faturamento,
	sum(
		case when p.data > DATE_ADD( CURRENT_TIMESTAMP, interval -6 month ) 
		then p.valor_pedido
		else 0 end
	) as fat6m,
	count(p.id) as qtd_pedidos,
	count(
		case when p.data > DATE_ADD( CURRENT_TIMESTAMP, interval -6 month ) 
		then p.id
		else null end
	) as ped6m
FROM usuario u
	left join pedido p on u.id = p.cod_usuario and p.cod_status in ( 3, 4 )
WHERE 
	u.codprojeto = 1 
	and u.ativo = 1 
group by 
	u.id
order by 
	u.datacadastro DESC;";
$r->txtItem = "
	<tr>
		<td>#id</td>
		<td>#nome</td>
		<td>#email</td>
		<td><span title=\"#cadastro_t\">#cadastro</span></td>
		<td><span title=\"#acesso_t\">#acesso</span></td>
		<td><span title=\"#ultimo_pedido_t\">#ultimo_pedido</span></td>
		<td>#faturamento</td>
		<td>#fat6m</td>
		<td>#qtd_pedidos</td>
		<td>#ped6m</td>
		<td class=\"td-controls\">
			<a class=\"controle\" title=\"Detalhes\" href=\"/admin/?pg=" . $page->page . "Detalhes&cod=#id\"><img heigth=\"18\" src=\"/admin/img/search.png\" /></a>
		</td>
	</tr>
";
$r->exec();

$r_total = new Repeater();
$r_total->campos = "qtd";
$r_total->sql = "
	SELECT 
		count(*) as qtd
	FROM 
		usuario 
	WHERE 
		codprojeto = ".CODPROJETO." 
		and ativo = 1";
$r_total->txtItem = "#qtd";
$r_total->exec();

*/


?>
