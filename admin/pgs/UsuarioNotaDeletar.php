<?php

// valida permissão
if( !in_array( "usuarios", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$lbl_page = "Usuario";
$p = new UsuarioNota();

if(isset($_GET["id"])){
	$p->id = $_GET["id"];
	$p->get();

	LogAdmin::_salvar( "Nota de Usuario Deletada", "Notas" , $admin->id, json_encode( $p ), "" );
	if($p->deletar()){
		addOnloadScript("message('Excluido com sucesso.','sucess');");
		echo '<script type="text/javascript">document.location.href = "/admin/?pg=UsuarioDetalhes&cod='.$p->cod_usuario.'";</script>';
	}else{
		addOnloadScript("message('Erro ao excluir.','error');");
		echo '<script type="text/javascript">document.location.href = "/admin/?pg=UsuarioDetalhes&cod='.$p->cod_usuario.'";</script>';
	}
	print_r($p);
} else {
	addOnloadScript("message('Erro ao excluir.','error');");
	echo '<script type="text/javascript">document.location.href = "/admin/?pg=Usuario";</script>';
}

die();

?>