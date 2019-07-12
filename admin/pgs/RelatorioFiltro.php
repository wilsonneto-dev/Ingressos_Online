<?php

// valida permissão
if( !in_array( "relatorios_vendas", $permissoes_admin ) ){
	$permitido = false;
	foreach ($permissoes_admin as $v) {
		if(strpos($v, "relatorio") == 0){
			$permitido = true;
			break;
		}
	}
	if(!$permitido){
		include("pgs/404.pg.php");
		return;
	}
}

$menu_destaque = "relatorios_vendas_old";

$p = new RelatorioAdmin();
if( isset( $_GET["rpt"] ) ){
	$p = RelatorioAdmin::_get( intval( $_GET["rpt"] ) );
}
if( $p == null ){
	include("pgs/404.pg.php");
	return;
}

$page = new StdAdminPage();

$page->title = "Filtros: ".$p->descricao;
$page->page = "RelatoriosVenda";
$page->back_link = true;
$page->title_back = "Relatorios de Vendas";


$items = RelatorioFiltro::_getLista( $p->id );
$filtros_html = "";

$page->form = true;
$page->form_fields = array(); 

foreach ($items as $index => $item) {
	if( $item->filtro == "data_range" ){
		$page->form_fields[] = array( "type" => "data", "name" => "data_range_inicial", "label" => "Data inicial *", "required" => true );
		$page->form_fields[] = array( "type" => "data", "name" => "data_range_final", "label" => "Data final *", "required" => true );
	}
	else if( $item->filtro == "eventos" )
	{
		$sel_tipo = new SqlSelect(
			"SELECT distinct evento as valor, evento as texto FROM temp_pedido WHERE codprojeto = ".CODPROJETO." AND ativo = 1 and evento <> '' ORDER BY evento;"
		); 
		$sel_tipo->nome = "cod_evento";
		$sel_tipo->exec();
		$page->form_fields[] = array( "label" => "Evento", "type" => "html-field", "html" => $sel_tipo->html );
	}
	else if( $item->filtro == "eventos_todos" )
	{
		$sel_tipo = new SqlSelect(
			"SELECT distinct evento as valor, evento as texto FROM temp_pedido WHERE codprojeto = ".CODPROJETO." AND ativo = 1 and evento <> '' ORDER BY evento;"
		); 
		$sel_tipo->nome = "cod_evento";
		$sel_tipo->extra = '<option value="Todos">Todos</option>';
		$sel_tipo->exec();
		$page->form_fields[] = array( "label" => "Evento", "type" => "html-field", "html" => $sel_tipo->html );
	}
	else if( $item->filtro == "id_eventos" )
	{
		$sel_tipo = new SqlSelect(
			"SELECT id as valor, concat( date_format( data, '%d/%m/%y' ) , ' - ', titulo ) as texto FROM evento WHERE codprojeto = ".CODPROJETO." AND ativo = 1 and em_filtros = 1 ORDER BY data DESC;"
		); 
		$sel_tipo->nome = "id_evento";
		$sel_tipo->exec();
		$page->form_fields[] = array( "label" => "Evento", "type" => "html-field", "html" => $sel_tipo->html );
	}
	else if( $item->filtro == "id_eventos_todos" )
	{
		$sel_tipo = new SqlSelect(
			"SELECT id as valor, concat( date_format( data, '%d/%m/%y' ) , ' - ', titulo ) as texto FROM evento WHERE codprojeto = ".CODPROJETO." AND ativo = 1 and em_filtros = 1 ORDER BY data DESC;"
		); 
		$sel_tipo->nome = "id_evento";
		$sel_tipo->extra = '<option value="0">Todos</option>';
		$sel_tipo->exec();
		$page->form_fields[] = array( "label" => "Evento", "type" => "html-field", "html" => $sel_tipo->html );
	}
	else if( $item->filtro == "confirmados_ou_todos" )
	{
		$sel_tipo = new SqlSelect("select * from ( select 1 ) as tb  where 1 = 2"); 
		$sel_tipo->nome = "confirmados_ou_todos";
		$sel_tipo->extra = '<option value="Todos">Todos</option><option value="Confirmados">Apenas Confirmados</option>';
		$sel_tipo->exec();
		$page->form_fields[] = array( "label" => "Mostrar", "type" => "html-field", "html" => $sel_tipo->html );
	}
}
$page->form_button_text = "Gerar Relat&oacute;rio";
$page->form_action = "Relatorio.php?id=$p->id";

// $page->botoes_extras = [];

/*
$page->html_content = "
	$filtros_html	
";*/


/*
	array( "value" => $p->razao_social, "name" => "razao_social", "label" => "Empresa *", "required" => true, "autofocus" => true ),
	array( "value" => $p->responsavel, "name" => "responsavel", "label" => "Respons&aacute;vel *", "required" => true ),
	array( "label" => "Imagem Atual", "type" => "image-view", "value" => ( "/" . $p->imagem ) ),
	array( "name" => "imagem", "label" => "Imagem", "type" => "file", "required" => true ),
	array( "value" => $p->email, "name" => "email", "label" => "E-mail", "required" => true ),
	array( "value" => $p->senha, "name" => "senha", "label" => "Pass", "required" => true),
	array( "value" => $p->bloqueado, "name" => "bloqueado", "label" => "Bloqueado", "type" => "checkbox" )
*/

$page->render();

?>