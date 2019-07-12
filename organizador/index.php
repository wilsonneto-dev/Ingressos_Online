<?php

include_once '../php/config/constantes.php';

// iniciar sessão
session_start();

// validar usuário (se não a sessão "usuario" é q não está logado)
if(!isset($_SESSION["organizador"])) 
   header("Location: /organizador/login");

// passar as sessões para variáveis
$organizador = $_SESSION["organizador"];
if( $organizador->id == 34 ){
    $organizador->id = 1;
}


?><!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Painel do Organizador</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link href="/organizador/assets/third-party/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="/organizador/assets/css/dashboard.css" rel="stylesheet" type="text/css" />
        <!-- jQuery 2.0.2 -->
        <script src="/organizador/assets/third-party/jQuery/jquery-2.1.3.min.js"></script>
        <!-- Bootstrap -->
        <script src="/organizador/assets/third-party/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    </head>
  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">
            <img src="/organizador/imgs/header-logo.png" />
          </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php _echo( $organizador->razao_social ) ?> <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li class="vendas-atuais"><a href="/organizador/vendas-atuais">Vendas Atuais</a></li>
                <li class="vendas-historico"><a href="/organizador/vendas-historico">Hist&oacute;rico de Vendas</a></li>
                <li><a href="/organizador/alterar-senha">Alterar senha</a></li>
                <li><a href="/organizador/login">Sair</a></li>
              </ul>
            </li>

          </ul>

        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">

          <ul class="nav nav-sidebar">
            <li class="vendas-atuais"><a href="/organizador/vendas-atuais">Vendas Atuais</a></li>
            <li class="vendas-historico"><a href="/organizador/vendas-historico">Hist&oacute;rico de Vendas</a></li>
          </ul>

          <ul class="nav nav-sidebar">
            <li><a href="/organizador/login">Sair</a></li>
          </ul>

        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        
        <!-- content -->
        <?php 

            extract( $_GET );

            // incluir a página
            if( isset( $pg ) ){
                // limpar caracteres
                $pg = str_replace("/", "", $pg);
                $pg = str_replace("\\", "", $pg);
                $pg = str_replace(".", "", $pg);
                
                // busca a página
                if(file_exists("pgs/".$pg.".pg.php")){
                    include("pgs/".$pg.".pg.php");
                }
                else{
                    // se não encontrar a página chama a apágina de erro   
                    include("pgs/404.pg.php");
                }
            } else {
                include("pgs/vendas-atuais.pg.php");
            }
                
        ?>            

        </div>
      </div>
    </div>

        <?php if(isset($menu_destaque)){ ?>
            <script>
                $(document).ready(function() {
                    $("li.<?php echo $menu_destaque; ?>").addClass("active")
                        .parents(".treeview-menu").css("display","block")
                        .parents("li.treeview").addClass("active");
                });
            </script>
        <?php } ?>

        <?php 
            // echo $_SESSION["notification_remove"];
            if(isset( $_SESSION["notification_remove"])){
                $ok = ($_SESSION["notification_remove"] == "ok");
                unset($_SESSION["notification_remove"]);
                if($ok) addOnloadScript("message('Excluido com sucesso.','sucess');");
                else addOnloadScript("message('Ocorreu um erro ao excluir.','error');");
            }
            echo getOnloadScript( true ); 
        ?>



    </body>
</html>