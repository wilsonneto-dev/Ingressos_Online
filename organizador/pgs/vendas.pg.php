<?php

$id_url = isset($_GET["url"]) ? $_GET["url"] : "";
$obj = Evento::_getByUrl( $id_url );

if( $obj== null ){
  header("Location: /organizador/404");
  die();
}

if( $obj->cod_promoter != $organizador->id ){
  header("Location: /organizador/404");
  die();
}


$local = Local::_get( $obj->cod_local );
$cidade = Cidade::_get( $local->cod_cidade );


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
        inner join evento ev on ev.id = ped.cod_evento
    WHERE
        ped.cod_status in ( 3, 4 )
        and ev.id = $obj->id
        and ev.cod_promoter = $organizador->id
        and 0 in ( ped.cod_evento, 0 )
";
$r_box_quantidades->txtItem = '
      <div class="col-xs-6 col-sm-3 placeholder">
        <img src="/organizador/imgs/icons/carrinho.png" class="img-responsive" alt="Generic placeholder thumbnail">
        <h4>#qtd_pedidos</h4>
        <span class="text-muted">pedidos</span>
      </div>
      <div class="col-xs-6 col-sm-3 placeholder">
        <img src="/organizador/imgs/icons/tags.png" class="img-responsive" alt="Generic placeholder thumbnail">
        <h4>#qtd_ingressos</h4>
        <span class="text-muted">Ingressos</span>
      </div>
';
$r_box_quantidades->exec();


$r_box_valores = new Repeater();
$r_box_valores->campos = "valor_ingressos";
$r_box_valores->sql = "
select 
    sum(valor_ingressos) as valor_ingressos
from (
    select 
        ped.valor_ingressos
    from 
        pedido ped
        inner join pedido_item item on ped.codigo = item.cod_pedido
        inner join evento ev on ev.id = ped.cod_evento
    WHERE
        ped.cod_status in ( 3, 4 )
        and ev.id = $obj->id
        and ev.cod_promoter = $organizador->id
        and 0 in ( ped.cod_evento, 0 )
    group by 
        ped.codigo
) as tb";
$r_box_valores->txtItem = '
        <div class="col-xs-6 col-sm-3 placeholder">
          <img src="/organizador/imgs/icons/dinheiro.png"  class="img-responsive" alt="Generic placeholder thumbnail">
          <h4>R$ #valor_ingressos</h4>
          <span class="text-muted">Valor dos Ingressos</span>
        </div>
';
$r_box_valores->exec();


$r_tabelas_vendas = new Repeater();
$r_tabelas_vendas->campos = "codigo;descricao;quantidade;valor_ingressos";
$r_tabelas_vendas->sql = "
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
    ing.id";
$r_tabelas_vendas->txtVazio = '<td colspan="5">N&atilde;o h&aacute; ingressos vendidos</td>';
$r_tabelas_vendas->txtItem = '
    <tr>
      <td>#codigo</td>
      <td>#descricao</td>
      <td>#quantidade</td>
      <td>#valor_ingressos</td>
    </tr>';
$r_tabelas_vendas->exec();


$pedidos_ingressos_dias = new Repeater();
$pedidos_ingressos_dias->campos = "sdia;qtd_pedidos;qtd_ingressos";
$pedidos_ingressos_dias->sql = "
select 
    date_format( dias.dia, '%Y-%m-%d' ) as sdia,
    count( distinct ped.id ) as qtd_pedidos,
    ifnull( sum( item.quantidade ) , 0 )as qtd_ingressos
from 
  calendario as dias
left join 
(
  select 
    p.id,
    p.cod_status,
    p.codigo ,
    p.data
  from pedido p 
  inner join evento ev on ev.id = p.cod_evento and ev.cod_promoter = $organizador->id and ev.id = $obj->id
) as ped on dias.dia = date(ped.data) and ped.cod_status in ( 3, 4 )
left join 
  pedido_item item on item.cod_pedido = ped.codigo
WHERE
  dia >= date_add( CURDATE(), interval -1 month  )
  and dia < date_add( CURDATE(), interval 1 day )
group by 
    dia
order by
    dia
";
$pedidos_ingressos_dias->txtItem = ",{y: '#sdia', Pedidos: #qtd_pedidos, Ingressos: #qtd_ingressos}";
$pedidos_ingressos_dias->exec();




?>

          <h3 class="page-header"><small>Vendas:</small> <?php echo htmlentities("$obj->titulo"); ?></h3>

          <div class="row placeholders">
            <?php 
              echo $r_box_quantidades->html." ";
              echo $r_box_valores->html; 
            ?>

          </div>

          <h3 class="sub-header">Ingressos Vendidos</h3>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <td>C&oacute;digo</td>
                  <td>Descri&ccedil;&atilde;o</td>
                  <td>Quantidade</td>
                  <td>Valor</td>
                </tr>
              </thead>
              <tbody>
                <?php echo $r_tabelas_vendas->html; ?>
              </tbody>
            </table>
          </div>

          <div style="text-align: right;">
            <a href="/organizador/reports/resumo/<?php echo $obj->id_url; ?>" target="_blank" type="button" class="btn btn-primary">Resumo</a>
            &nbsp;
            <a href="/organizador/reports/listagem/<?php echo $obj->id_url; ?>" target="_blank" type="button" class="btn btn-primary">Listagem</a>
          </div>

          <h3 class="sub-header">&Uacute;ltimos 30 dias</h3>
          <div id="myfirstchart" style="height: 250px; width: 100%;"></div>
            <script>
              new Morris.Line({
                // ID of the element in which to draw the chart.
                element: 'myfirstchart',
                // Chart data records -- each entry in this array corresponds to a point on
                // the chart.
                data: [
                  <?php echo substr( $pedidos_ingressos_dias->html, 1 ) ?>
                ],
                xkey: 'y',
                ykeys: ['Pedidos', 'Ingressos'],
                labels: ['Pedidos', 'Ingressos'],
                lineColors: ['#bc3c3c', '#3c8dbc'],
                hideHover: 'auto'
              });
            </script>

            <br /><br /><br />