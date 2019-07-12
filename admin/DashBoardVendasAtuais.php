<?php 

// valida permissão
if( !in_array( "analises", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$r_eventos = new Repeater();
$r_eventos->campos = "id;data;evento;qtd_pedidos;valor;ingressos;liquido;ingressos";
$r_eventos->sql = "
    select 
        ev.id,
        date_format( ev.data, '%Y.%m.%d' ) as data,
        ev.titulo as evento,
        count( ped.id ) as qtd_pedidos,
        sum( valor_pedido ) as valor,
        sum( valor_ingressos ) as ingressos,
        sum( valor_liquido - valor_ingressos ) as liquido,
        (
            select 
                ifnull( sum( i_item.quantidade ) , 0)
            from 
                pedido i_ped
                inner join pedido_item i_item on i_ped.codigo = i_item.cod_pedido
            WHERE
                i_ped.cod_status in ( 3, 4 )
                and i_ped.cod_evento = ev.id
        ) as ingressos
    from evento ev
    left join pedido ped on 
        ev.id = ped.cod_evento 
        and ped.cod_status in ( 3, 4 ) 
    WHERE
        ev.acertado = 0
        and ev.acertado = 0
        and ev.data_entrar <= curdate()
        and ev.data >= date_add( curdate(), interval 1 day )
        AND ev.ativo = 1 
    group by 
        ev.titulo
    order by
        ev.data desc
";
$r_eventos->txtItem = "
    <tr>
        <td>
            #data
        </td>
        <td>
            #id
        </td>
        <td>
            #evento
        </td>
        <td>
            #qtd_pedidos
        </td>
        <td>
            #ingressos
        </td>
        <td>
            #valor
        </td>
        <td>
            #liquido
        </td>
        <td class=\"td-controls\">
        </td>
    </tr>
";
$r_eventos->exec();

/* fim sqls */

$menu_destaque = "analises";

$page = new StdAdminPage();
$page->page = "DashBoard";

$page->cadastrar = false;

$page->title = "Vendas Atuais";

$page->html_content = '

<div class="box-body table-responsive">
    <table class="table table-bordered table-hover data-table-no-pg">
        <thead>
            <tr>
                <td>
                    Data
                </td>
                <td>
                    #
                </td>
                <td>
                    Evento
                </td>
                <td>
                    Pedidos
                </td>
                <td>
                    Ingressos
                </td>
                <td>
                    Bruto
                </td>
                <td>
                    Liquido
                </td>
                <td class=\"td-controls\">
                </td>
            </tr>
        </thead>
        <tbody>
            '.$r_eventos->html.'
        </tbody>
    <table>
<div>


';

$page->render();

?>
