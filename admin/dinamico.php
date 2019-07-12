<?php

include_once '../php/config/constantes.php';
include_once '../php/config/thumbs.php';

// iniciar sess�o
session_start();

// validar usu�rio (se n�o a sess�o "usuario" � q n�o est� logado)
if(!isset($_SESSION["admin"])) 
   header("Location: /admin/login");

// passar as sess�es para vari�veis
$admin = $_SESSION["admin"];
$grupo_admin = $_SESSION["admin_grupo"];
$permissoes_admin = $_SESSION["admin_permissoes"];

	if(isset($_GET["src"])){
		
		$pg = $_GET["src"];
		$pg = str_replace("/", "", $pg);
        $pg = str_replace("\\", "", $pg);
        $pg = str_replace(".", "", $pg);
        

		if(file_exists("dinamicos/".$pg.".php"))
			include("dinamicos/".$pg.".php");
		else 
			echo 'n/a';
	}

?>