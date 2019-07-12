<?php

// valida permissão
if( !in_array( "eventos", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$lbl_page = "EventoIngressos";
$p = new ingresso();

if(isset($_GET["id"])){
	$p->id = $_GET["id"];
	$p->get();
	if($p->deletar()){
		addOnloadScript("message('Excluido com sucesso.','sucess');");
		echo '<script type="text/javascript">document.location.href = "/admin/?pg=EventoIngressos&id='.$p->cod_evento.'";</script>';
		LogAdmin::_salvar( "Ingresso excluido", "Ingresso" , $admin->id , "", json_encode( $p ) );
	}else{
		addOnloadScript("message('Erro ao excluir.','error');");
		echo '<script type="text/javascript">document.location.href = "/admin/?pg=EventoIngressos&id='.$p->cod_evento.'";</script>';
		LogAdmin::_salvar( "Erro ao excluir ingresso", "Ingresso" , $admin->id , "", json_encode( $p ) );
	}
} else {
	addOnloadScript("message('Erro ao excluir.','error');");
	echo '<script type="text/javascript">document.location.href = "/admin/?pg='.$lbl_page.'";</script>';
}

die();

?>