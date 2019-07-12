<?php

include_once '../php/config/constantes.php';
include_once '../php/config/thumbs.php';

// iniciar sessão
session_start();

// validar usuário (se não a sessão "usuario" é q não está logado)
if(!isset($_SESSION["admin"])) 
   header("Location: /admin/login");

// passar as sessões para variáveis
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