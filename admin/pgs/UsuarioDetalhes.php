<?php

// valida permissão
if( !in_array( "usuarios", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$menu_destaque = "usuarios";


$u = Usuario::_get( intval( $_GET["cod"] ) );
if( $u == null ){
	include("pgs/404.pg.php");
	return;
}

if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
	$n = new UsuarioNota();
	$n->texto = $_POST["nota"];
	$n->cod_usuario = $u->id;
	$n->cod_admin = $admin->id;
	
	if( $n->cadastrar() ){
		LogAdmin::_salvar( "Nota de Usuario Cadastrada", "Notas" , $admin->id , "", json_encode( $n ) );
		addOnloadScript("message('Cadastrado com sucesso.','sucess');");
	} else {
		LogAdmin::_salvar( "Erro ao cadastrar Nota de Usuario", "Notas" , $admin->id , "", json_encode( $n ) );
		addOnloadScript("message('Ocorreu um erro ao cadastrar.','error');");
	}
}


$r_faturamento = new Repeater();
$r_faturamento->campos = "faturamento;fat6m;qtd_pedidos;ped6m";
$r_faturamento->sql = "
SELECT 
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
	and u.id = $u->id
group by 
	u.id";
$r_faturamento->txtItem = "
	<tr>
		<td>#fat6m</td>
		<td>#ped6m</td>
		<td>#faturamento</td>
		<td>#qtd_pedidos</td>
	</tr>
";

$r_faturamento->exec();

$r_notas = new Repeater();
$r_notas->campos = "id;texto;data";
$r_notas->sql = "
SELECT 
	id, texto, DATE_FORMAT( datacadastro, '%d/%m %H:%i' ) as data
FROM usuario_nota
WHERE 
	ativo = 1 
	and cod_usuario = $u->id
order by
	datacadastro DESC";
$r_notas->txtItem = "
	<b>#data</b><small> ( <a href=\"/admin/?pg=UsuarioNotaDeletar&id=#id\">deletar</a> ) </small><br />
	#nl2br_texto<br/>
	<br/>
";
$r_notas->exec();

$r_pedidos = new Repeater();
$r_pedidos->campos = "id;codigo;data;ref;status;cod_status;evento;valor_pedido;cod_usuario";
$r_pedidos->sql = "
select 
	p.id,
	p.codigo,
	p.ref,
	u.id as cod_usuario,
	DATE_FORMAT( p.data, '%d/%m %H:%i' ) as data,
	ev.titulo as evento,
	p.status,
	p.cod_status,
	p.valor_pedido
from pedido p
inner join usuario u
	on p.cod_usuario = u.id
inner join evento ev
	on ev.id = p.cod_evento
where
	p.cod_usuario = $u->id
order by 
	data desc;
";
$r_pedidos->txtItem = "
	<tr class=\"pedido_row\">
		<td>#id</td>
		<td>#codigo</td>
		<td>#data</td>
		<td>#evento</td>
		<td class=\"status_#cod_status\">#status</td>
		<td>#valor_pedido</td>
		<td>#ref</td>
		<td class=\"td-controls\">
			<a class=\"controle\" title=\"Detalhes\" target=\"_blank\" href=\"/admin/?pg=PedidosDetalhes&cod=#codigo\"><img heigth=\"18\" src=\"/admin/img/search.png\" /></a>
		</td>
	</tr>
";
$r_pedidos->exec();


$cidade_selecionada = BrasilCidade::_get( $u->cod_brasil_cidade );

$page = new StdAdminPage();

$page->title = "Detalhes do Usu&aacute;rio: $u->nome $u->sobrenome ( $u->id )" ;
$page->page = "Usuario";
$page->back_link = true;
$page->title_back = "Usu&aacute;rios";

$page->botoes_extras = [];
$page->html_content = "
	<b>Id</b>: $u->id<br />
	<b>Nome</b>: $u->nome $u->sobrenome<br />
	<b>CPF</b>: $u->cpf<br />
	<b>Sexo</b>: $u->sexo<br />
	<b>Data Nascimento</b>: ".$u->data_nascimento->format("d/m/Y")."<br />
	<b>Cidade</b>: $cidade_selecionada->cidade - $cidade_selecionada->uf<br />
	<b>Como Conheceu</b>: $u->como_conheceu<br />
	<br />
	<b>E-mail</b>: $u->email<br />
	<b>Telefone</b>: $u->ddd $u->telefone<br />
	<br />
	<b>Cadastro em</b>: ".$u->datacadastro->format("d/m/Y H:m")."<br />
	<b>&Uacute;timo Acesso</b>: ".$u->ultimo_acesso->format("d/m/Y H:m")."<br />
	<br />
	<h3>Faturamento</h3>
	<div class=\"box-body table-responsive\">
	    <table class=\"table table-bordered table-hover data-table\">
	        <thead>
	            <tr>
	            	<th>Faturamento 6m</th>
	            	<th>Pedidos 6m</th>
	            	<th>Faturamento</th>
	            	<th>Pedidos</th>
	            </tr>
	        </thead>
	        <tbody>
	            $r_faturamento->html
			</tbody>
		<table>
	<div>
	<br />
	<h3>Pedidos</h3>
	<div class=\"box-body table-responsive\">
	    <table class=\"table table-bordered table-hover data-table\">
	        <thead>
	            <tr>
					<th>#</th>
					<th>C&oacute;d</th>
					<th>Data</th>
					<th>Evento</th>
					<th>Status</th>
					<th>Valor</th>
					<th>Campanha</th>
					<th></th>
	            </tr>
	        </thead>
	        <tbody>
	            $r_pedidos->html
			</tbody>
		<table>
	<div>
	<br />
	<h3>Notas</h3>
	$r_notas->html
".'
	<div class="box box-primary">
        <!-- form start -->
        <form class="f_admin" name="f_post" method="post" enctype="multipart/form-data">
            <div class="box-body">
	            <div class="form-group">
			       <label for="razao_social">Cadastrar Nova Nota *</label>
			       <textarea class="form-control obrigatorio" id="nota" required="1" name="nota"></textarea>
				<div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Cadastrar Nota</button>
            </div>
        </form>
    </div>
';



$page->render();

?>