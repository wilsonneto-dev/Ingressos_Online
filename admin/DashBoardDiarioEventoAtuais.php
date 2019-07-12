<?php 

// valida permissão
if( !in_array( "analises", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$meta_dia_liquido_reais = 80;

$data_inicial = data(date('d/m/Y'))->sub(new DateInterval('P0D'));
$data_final = "";
$extra_titulo = "";

if(  isset($_POST["data_range_inicial"]  ) ){
    $data_inicial = data($_POST["data_range_inicial"]);
}

$extra_titulo = " - ".$data_inicial->format("d/m/Y");

$r = new Repeater();
$r->campos = "id;codigo;data;nome;status;cod_status;evento;valor_pedido;cod_usuario;ref";
$r->sql = "
select 
    p.id,
    p.codigo,
    concat( u.nome , ' ', u.sobrenome ) as nome,
    u.id as cod_usuario,
    DATE_FORMAT( p.data, '%d/%m %H:%i' ) as data,
    ev.titulo as evento,
    p.status,
    p.cod_status,
    p.ref,
    p.valor_pedido
from pedido p
inner join usuario u
    on p.cod_usuario = u.id
inner join evento ev
    on ev.id = p.cod_evento
where
    p.data > str_to_date( '".$data_inicial->format("d/m/Y")."','%d/%m/%Y' )
    and p.data < date_add( str_to_date( '".$data_inicial->format("d/m/Y")."','%d/%m/%Y' ), interval 1 day )
order by asa
    data desc;
";
$r->txtItem = "
    <tr class=\"pedido_row\">
        <td>#id</td>
        <td>#codigo</td>
        <td>#nome <small>( #cod_usuario )</small> </td>
        <td>#data</td>
        <td>#evento</td>
        <td class=\"status_#cod_status\">#status</td>
        <td>#valor_pedido</td>
        <td>#ref</td>
        <td class=\"td-controls\">
            <a class=\"controle\" title=\"Detalhes\" href=\"/admin/?pg=PedidosDetalhes&cod=#codigo\"><img heigth=\"18\" src=\"/admin/img/search.png\" /></a>
        </td>
    </tr>
";
$r->exec();

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
        inner join evento as ev on ev.id = ped.cod_evento and ev.ativo = 1
    WHERE
        ped.cod_status in ( 3, 4 )
        and ped.data > str_to_date( '".$data_inicial->format("d/m/Y")."','%d/%m/%Y' )
        and ped.data < date_add( str_to_date( '".$data_inicial->format("d/m/Y")."','%d/%m/%Y' ), interval 1 day )
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
';
$r_box_quantidades->exec();

$r_box_valores = new Repeater();
$r_box_valores->campos = "valor_bruto;ticket_medio;valor_liquido;percentual";
$r_box_valores->sql = "
select 
    count(codigo) as qtd_pedidos,
    sum(valor_pedido) as valor_bruto,
    cast( sum(valor_pedido) / count(codigo) as decimal(10,2)) as ticket_medio,  
    sum(liquido) as valor_liquido,
    ceil( ( sum(liquido) / $meta_dia_liquido_reais ) * 100 ) as percentual   
from (
    select 
        ped.codigo,
        ped.valor_pedido,
        ped.valor_liquido - ped.valor_ingressos as liquido,
        sum( item.quantidade ) as quantidade
    from 
        pedido ped
        inner join pedido_item item on ped.codigo = item.cod_pedido
        inner join evento as ev on ev.id = ped.cod_evento and ev.ativo = 1
    WHERE
        ped.cod_status in ( 3, 4 )
        and ped.data > str_to_date( '".$data_inicial->format("d/m/Y")."','%d/%m/%Y' )
        and ped.data < date_add( str_to_date( '".$data_inicial->format("d/m/Y")."','%d/%m/%Y' ), interval 1 day )
        and 0 in ( ped.cod_evento, 0 )
    group by 
        ped.codigo
) as tb";
$r_box_valores->txtItem = '
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
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>
                    <sup style="font-size: 20px">%</sup>#percentual
                </h3>
                <p>
                    Meta ( R$ '.$meta_dia_liquido_reais.' )
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


';
$r_box_valores->exec();

/* fim select dos boxes */

$r_tabelas_vendas = new Repeater();
$r_tabelas_vendas->campos = "id;titulo;pedidos;ingressos;bruto;liquido;dia;mes;ano";
$r_tabelas_vendas->sql = "
select 
    ev.id,
    ev.titulo,
    ev.titulo,
    count(distinct tb.codigo) as pedidos,
    sum( tb.quantidade ) as ingressos,
    sum(tb.valor_pedido) as bruto,
    sum(tb.liquido) as liquido,
    YEAR(ev.data) AS ano,
    DAY(ev.data) AS dia,
    CASE MONTH( ev.data )
        WHEN 1 THEN '01'
        WHEN 2 THEN '02'
        WHEN 3 THEN '03'
        WHEN 4 THEN '04'
        WHEN 5 THEN '05'
        WHEN 6 THEN '06'
        WHEN 7 THEN '07'
        WHEN 8 THEN '08'
        WHEN 9 THEN '09'
        WHEN 10 THEN '10'
        WHEN 11 THEN '11'
        WHEN 12 THEN '12'
    END AS mes
from evento ev 
left join
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
        inner join evento as ev on ev.id = ped.cod_evento and ev.ativo = 1
    WHERE
        ped.cod_status in ( 3, 4 )
        and ped.data > str_to_date( '".$data_inicial->format("d/m/Y")."','%d/%m/%Y' )
        and ped.data < date_add( str_to_date( '".$data_inicial->format("d/m/Y")."','%d/%m/%Y' ), interval 1 day )
        and 0 in ( ped.cod_evento, 0 )
    group by 
        ped.codigo
) as tb
on ev.id = tb.cod_evento
WHERE
    ev.data_entrar <= str_to_date( '".$data_inicial->format("d/m/Y")."','%d/%m/%Y' )
    and ev.data >= date_add( str_to_date( '".$data_inicial->format("d/m/Y")."' , '%d/%m/%Y' ), interval 0 day )
    AND ev.ativo = 1 
group by 
    ev.id
order by
    liquido desc";
$r_tabelas_vendas->txtItem = '
    <tr>
        <td>#ano.#mes.#dia</td>
        <td>#id</td>
        <td>#titulo</td>
        <td>#pedidos</td>
        <td>#ingressos</td>
        <td>#bruto</td>
        <td>#liquido</td>
    </tr>';
$r_tabelas_vendas->exec();

$r_receita_semanal = new Repeater();
$r_receita_semanal->campos = "sdia;liquido";
$r_receita_semanal->sql = "
    select 
        concat( dayofyear( dia ) , ' - ', date_format( min(dias.dia), '%d/%m' ) ) as sdia,
        count( ped.id ) as qtd_pedidos,
        sum( ifnull(valor_pedido,0) ) as faturamento,
        ifnull( sum( valor_liquido - valor_ingressos ) , 0) as liquido
    from calendario as dias
    left join pedido ped 
        on dias.dia = date(ped.data)
        and ped.cod_status in ( 3, 4 )
    where
        dia >= date_add( str_to_date( '".$data_inicial->format("d/m/Y")."','%d/%m/%Y' ), interval -15 day  )
        and dia < date_add( str_to_date( '".$data_inicial->format("d/m/Y")."','%d/%m/%Y' ), interval 1 day )
        and year( dia ) = year( str_to_date( '".$data_inicial->format("d/m/Y")."','%d/%m/%Y' ) )
        and yearweek( dia ) like concat( '', year( str_to_date( '".$data_inicial->format("d/m/Y")."','%d/%m/%Y' ) ), '%' )
    group by 
        day( dia )
    order by
        dia
";
$r_receita_semanal->txtItem = ",{y: '#sdia', Vendas: #liquido}";
$r_receita_semanal->exec();

$pedidos_ingressos_semanal = new Repeater();
$pedidos_ingressos_semanal->campos = "sdia;qtd_pedidos;qtd_ingressos";
$pedidos_ingressos_semanal->sql = "
select 
    concat( dayofyear( dia ) , ' - ', date_format( min(dias.dia), '%d/%m' ) ) as sdia,
    count( distinct ped.id ) as qtd_pedidos,
    ifnull( sum( item.quantidade ) , 0 )as qtd_ingressos
from calendario as dias
left join pedido ped 
    on dias.dia = date(ped.data)
    and ped.cod_status in ( 3, 4 )
left join pedido_item item on item.cod_pedido = ped.codigo
where
    dia >= date_add( str_to_date( '".$data_inicial->format("d/m/Y")."','%d/%m/%Y' ), interval -15 day  )
    and dia < date_add( str_to_date( '".$data_inicial->format("d/m/Y")."','%d/%m/%Y' ), interval 1 day )
    and year( dia ) = year( str_to_date( '".$data_inicial->format("d/m/Y")."','%d/%m/%Y' ) )
    and yearweek( dia ) like concat( '', year( str_to_date( '".$data_inicial->format("d/m/Y")."','%d/%m/%Y' ) ), '%' )
group by 
    day( dia )
order by
    dia
";
$pedidos_ingressos_semanal->txtItem = ",{y: '#sdia', Pedidos: #qtd_pedidos, Ingressos: #qtd_ingressos}";
$pedidos_ingressos_semanal->exec();

/* fim sqls */

$menu_destaque = "analises";

$page = new StdAdminPage();
$page->page = "DashBoard";

$page->cadastrar = false;

$page->title = "Dashboard - Hoje" . $extra_titulo;

$page->html_content = '

<!-- Small boxes (Stat box) -->
<div class="row">
    
    '.$r_box_quantidades->html.'
    '.$r_box_valores->html.'


</div>

<div class="box-header">
    <h3 class="box-title">Vendas por evento</h3>
</div>

<div class="box-body table-responsive">
    <table class="table table-bordered table-hover data-table-no-pg">
        <thead>
            <tr>
                <th>Data</th>
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

<div class="box-header">
    <h3 class="box-title">`Pedidos do dia</h3>
</div>

<div class="box-body table-responsive">
    <table class="table table-bordered table-hover data-table-no-pg">
        <thead>
            <tr>
                <th>#</th>
                <th>C&oacute;d</th>
                <th>Nome</th>
                <th>Data</th>
                <th>Evento</th>
                <th>Status</th>
                <th>Valor</th>
                <th>Camp.</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            '.$r->html.'
        </tbody>
    <table>
<div>


<div class="row">
    <div class="col-md-12">

        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Geral: Receita Diária</h3>
            </div>
            <div class="box-body chart-responsive">
                <div class="chart" id="line-chart" style="height: 300px;"></div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

    </div><!-- /.col (RIGHT) -->
</div><!-- /.row -->

<div class="row">
    <div class="col-md-12">

        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Geral: Ingressos e Pedidos - Diários</h3>
            </div>
            <div class="box-body chart-responsive">
                <div class="chart" id="revenue-chart" style="height: 300px;"></div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

    </div><!-- /.col (LEFT) -->
</div>



<div class="box-body">
    <form method="post">                                
        <div class="form-group">
            <label for="data_range_inicial">Data *</label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input type="text" value="'.$data_inicial->format("d/m/Y").'" class="form-control pull-right obrigatorio data" id="data_range_inicial" required="1" name="data_range_inicial">
            </div><!-- /.input group -->
        </div><!-- /.form group -->
    
        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Atualizar P&aacute;gina</button>
        </div>
    </form>
</div>


';

$charts_scripts = '';

$eventos = Evento::_getListaEventosAtivos( $data_inicial );

$page->html_content .= '
        <div class="row">';

foreach ($eventos as $ev) {

    $page->html_content .= '
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header">
                        <span>&nbsp;&nbsp;'.$ev->data->format("d/m/Y").' - '.$ev->titulo.' </span>
                    </div>
                    <div class="box-body chart-responsive">
                        <div class="chart" id="revenue-chart-'.$ev->id.'" style="height: 200px;"></div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

            </div><!-- /.col (LEFT) -->
    ';

    $evento_unico = new Repeater();
    $evento_unico->campos = "sdia;qtd_pedidos;qtd_ingressos";
    $evento_unico->sql = "
        select 
            concat( dayofyear( dia ) , ' - ', date_format( min(dias.dia), '%d/%m' ) ) as sdia,
            count( distinct ped.id ) as qtd_pedidos,
            ifnull( sum( item.quantidade ) , 0 )as qtd_ingressos
        from calendario as dias
        left join pedido ped 
            on dias.dia = date(ped.data)
            and ped.cod_status in ( 3, 4 )
            and ped.cod_evento = " . $ev->id . "
        left join pedido_item item on item.cod_pedido = ped.codigo
        where
            dia >= date_add( str_to_date( '".$data_inicial->format("d/m/Y")."','%d/%m/%Y' ), interval -15 day  )
            and dia < date_add( str_to_date( '".$data_inicial->format("d/m/Y")."','%d/%m/%Y' ), interval 1 day )
            and year( dia ) = year( str_to_date( '".$data_inicial->format("d/m/Y")."','%d/%m/%Y' ) )
            and yearweek( dia ) like concat( '', year( str_to_date( '".$data_inicial->format("d/m/Y")."','%d/%m/%Y' ) ), '%' )
        group by 
            day( dia )
        order by
            dia
    ";
    $evento_unico->txtItem = ",{y: '#sdia', Pedidos: #qtd_pedidos, Ingressos: #qtd_ingressos}";
    $evento_unico->exec();

    $charts_scripts .= "
        // AREA CHART
        var area = new Morris.Line({
            element: 'revenue-chart-".$ev->id."',
            resize: true,
            data: [
                ".substr( $evento_unico->html, 1 )."
            ],
            xkey: 'y',
            ykeys: ['Pedidos', 'Ingressos'],
            labels: ['Pedidos', 'Ingressos'],
            lineColors: ['#a0d0e0', '#3c8dbc'],
            hideHover: 'auto'
        });
    ";

}

$page->html_content .= '
        </div>';


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

                ".$charts_scripts."

            });
");


$page->render();

?>
