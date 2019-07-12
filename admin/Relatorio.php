<?php

include_once '../php/config/constantes.php';
include_once '../php/config/thumbs.php';
include_once '../php/third_party/fpdf16/fpdf.php';

// iniciar sessão
session_start();

// validar usuário (se não a sessão "usuario" é q não está logado)
if(!isset($_SESSION["admin"])) 
   header("Location: /admin/login.php");

// passar as sessões para variáveis
$admin = $_SESSION["admin"];
$grupo_admin = $_SESSION["admin_grupo"];
$permissoes_admin = $_SESSION["admin_permissoes"];

$p = new RelatorioAdmin();
if( isset( $_GET["id"] ) ){
    $p = RelatorioAdmin::_get( intval( $_GET["id"] ) );
}

if($p->id == "" && isset( $_GET["rpt"] ) ){
    $p = RelatorioAdmin::_get( intval( $_GET["rpt"] ) );
}

if( $p == null ){
    header("Location: /admin/login.php");
    return;
}

if( !$p->possui_permissao( $grupo_admin->id ) ){
    header("Location: ?pg=404");
    // print_r ($p);
    // echo ' - '. $grupo_admin->id;

    return;
}else{
    include( "rpts/" . $p->url );
}

        
?>
