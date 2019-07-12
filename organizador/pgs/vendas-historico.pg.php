<?php


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
        and ev.data < curdate()
        and ev.cod_promoter = $organizador->id
        and 0 in ( ped.cod_evento, 0 )
        and ev.data >= date_add( CURDATE(), interval -18 month  )
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
        and ev.data < curdate()
        and ev.cod_promoter = $organizador->id
        and 0 in ( ped.cod_evento, 0 )
        and ev.data >= date_add( CURDATE(), interval -18 month  )
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
$r_tabelas_vendas->campos = "id_url;id;titulo;pedidos;ingressos;valor;data_str";
$r_tabelas_vendas->sql = "
select 
    ev.id,
    ev.id_url,
    date_format( ev.data, '%d/%m/%y' ) as data_str,
    ev.titulo,
    count(distinct codigo) as pedidos,
    sum( ifnull( quantidade , 0 ) ) as ingressos,
    sum( ifnull( valor_ingressos , 0  ) ) as valor
from evento ev
left join (
select 
    ped.codigo,
    ped.cod_evento,
    ped.valor_ingressos,
    ped.valor_liquido - ped.valor_ingressos as liquido,
    sum( item.quantidade ) as quantidade
from 
    pedido ped
    inner join pedido_item item on ped.codigo = item.cod_pedido
WHERE
    ped.cod_status in ( 3, 4 )
group by 
    ped.codigo
) as tb on ev.id = tb.cod_evento
WHERE
    ev.cod_promoter = $organizador->id
    and ev.data < curdate()
    and ev.data >= date_add( CURDATE(), interval -18 month  )
group by 
    ev.id
order by
    ev.data desc";
$r_tabelas_vendas->txtItem = '
    <tr>
      <td>#data_str</td>
      <td>#titulo</td>
      <td>#pedidos</td>
      <td>#ingressos</td>
      <td>R$ #valor</td>
      <td><a href="/organizador/vendas/#id_url"><img src="/organizador/imgs/icons/detalhes.png" class="btn-image" /></a></td>
    </tr>';
$r_tabelas_vendas->exec();


$pedidos_ingressos_dias = new Repeater();
$pedidos_ingressos_dias->campos = "sdia;qtd_pedidos;qtd_ingressos";
$pedidos_ingressos_dias->sql = "
select 
    ( 
        case (month(dias.dia))
          when 1 then 'Janeiro'
          when 2 then 'Fevereiro'
          when 3 then 'Março'
          when 4 then 'Abril'
          when 5 then 'Maio'
          when 6 then 'Junho'
          when 7 then 'Julho'
          when 8 then 'Agosto'
          when 9 then 'Setembro'
          when 10 then 'Outubro'
          when 11 then 'Novembro'
          when 12 then 'Dezembro'
          else '' end
     ) as sdia,
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
  inner join evento ev on ev.id = p.cod_evento and ev.cod_promoter = $organizador->id
) as ped on dias.dia = date(ped.data) and ped.cod_status in ( 3, 4 )
left join 
  pedido_item item on item.cod_pedido = ped.codigo
WHERE
  dia >= date_add( CURDATE(), interval -6 month  )
  and dia < date_add( CURDATE(), interval 1 day )
group by 
     year(dia), month( dia )
order by
    dia
";
$pedidos_ingressos_dias->txtItem = ",{y: '#sdia', Pedidos: #qtd_pedidos, Ingressos: #qtd_ingressos}";
$pedidos_ingressos_dias->exec();

$menu_destaque = "vendas-historico";

?>

          <h3 class="page-header">Hist&oacute;rico <small>( eventos passados )</small></h3>

          <div class="row placeholders">
            <?php 
              echo $r_box_quantidades->html." ";
              echo $r_box_valores->html; 
            ?>

          </div>

          <h3 class="sub-header">&Uacute;ltimos meses</h3>
          <div id="chart" style="height: 250px;"></div>
            <script>
              new Morris.Bar({
                element: 'chart',
                data: [
                  <?php echo substr( $pedidos_ingressos_dias->html, 1 ) ?>
                ],
                xkey: 'y',
                ykeys: ['Pedidos', 'Ingressos'],
                labels: ['Pedidos', 'Ingressos'],
                BarColors: ['#bc3c3c', '#ffa0a0'],
                hideHover: 'auto'
              });
            </script>

          <h3 class="sub-header">Eventos</h3>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Data</th>
                  <th>Evento</th>
                  <th>Pedidos</th>
                  <th>Ingressos</th>
                  <th>Valor</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php echo $r_tabelas_vendas->html; ?>
              </tbody>
            </table>

          </div>

            <br /><br /><br />