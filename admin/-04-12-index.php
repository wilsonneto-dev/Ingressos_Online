<?php

include_once '../php/config/constantes.php';
include_once '../php/config/thumbs.php';

// iniciar sessão
session_start();

// validar usuário (se não a sessão "usuario" é q não está logado)
if(!isset($_SESSION["admin"])) 
   header("Location: /admin/login.php");

// passar as sessões para variáveis
$admin = $_SESSION["admin"];
$grupo_admin = $_SESSION["admin_grupo"];
$permissoes_admin = $_SESSION["admin_permissoes"];


?><!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Painel</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link href="/admin/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="/admin/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="/admin/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <link href="/admin/css/morris/morris.css" rel="stylesheet" type="text/css" />
        <link href="/admin/css/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
        <!-- link href="/admin/js/datepicker/css/redmond/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" type="text/css" / -->
        <link href="/admin/css/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />
        <!-- link href="/admin/css/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" / -->
        <link href="/admin/css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
        <link href="/admin/css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <!-- DATA TABLES -->
        <link href="/admin/css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
        <header class="header">
            <a href="index.html" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                <img class="icon" src="/admin/img/logo-admin.png" style="height: 40px;" />
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        <!-- Notificações -->
                        <?php if( isset($_SESSION["admin_notificacoes_gerais_html"]) ) echo $_SESSION["admin_notificacoes_gerais_html"]; ?>
                        <!-- dados de usuário -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>
                                <span><?php _echo( $admin->nome ); ?> <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <!-- Menu Body -->
                                <li class="user-body">
                                    <!-- div class="col-xs-8 text-center pull-right">
                                        <a href="#">Alterar Senha</a>
                                    </div -->
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-right">
                                        <a href="/admin/login.php" class="btn btn-default btn-flat">Sair</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="/<?php if($admin->imagem != "") _echo( $admin->imagem ); else echo "admin/img/default-user.png"; ?>" class="img-circle" alt="User Image" />
                        </div>
                        <div class="pull-left info">
                            <p>Olá, <?php _echo( $admin->nome ); ?></p>
                            <small>
                                <i class="fa fa-circle text-success"></i> 
                                <?php _echo( $grupo_admin->nome ); ?>
                            </small>
                        </div>
                    </div>

                    <!-- search form -->
                    <form action="/admin/?pg=ProcurarPedido" method="post" class="sidebar-form">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" placeholder="Buscar Pedido"/>
                            <span class="input-group-btn">
                                <button type='submit' name='seach' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </form>
                    <!-- /.search form -->

                    <!-- menu -->
                    <?php if( isset($_SESSION["admin_menu_html"]) ) echo $_SESSION["admin_menu_html"]; ?>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
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
        if(file_exists("pgs/".$pg.".php")){
            include("pgs/".$pg.".php");
        }
        else{
            // se não encontrar a página chama a apágina de erro   
            include("pgs/404.pg.php");
        }
    } else {
        include("pgs/404.pg.php");
    }
        
?>            
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->

        <!-- jQuery 2.0.2 -->
        <script src="/admin/js/jquery.min.js"></script>
        <!-- jQuery UI 1.10.3 -->
        <script src="/admin/js/jquery-ui-1.10.3.min.js" type="text/javascript"></script>
        <!-- Bootstrap -->
        <script src="/admin/js/bootstrap.min.js" type="text/javascript"></script>
        <!-- Morris.js charts -->
        <script src="/admin/js/raphael-min.js"></script>
        <script src="/admin/js/plugins/morris/morris.min.js" type="text/javascript"></script>
        <!-- Sparkline -->
        <script src="/admin/js/plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
        <!-- jvectormap -->
        <script src="/admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
        <script src="/admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
        <!-- jQuery Knob Chart -->
        <script src="/admin/js/plugins/jqueryKnob/jquery.knob.js" type="text/javascript"></script>
        <!-- daterangepicker -->
        <!-- script src="/admin/js/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script -->
        <!-- datepicker -->
        <!-- script src="/admin/js/datepicker/js/jquery-ui-1.9.2.custom.min.js" type="text/javascript"></script -->
        <script src="/admin/js/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
        
        <script src="/admin/js/plugins/datepicker/locales/bootstrap-datepicker.pt-BR.js" type="text/javascript"></script>
        <!-- Bootstrap WYSIHTML5 -->
        <script src="/admin/js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
        <!-- iCheck -->
        <script src="/admin/js/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
        <!-- num -->
        <script src="/admin/js/jquery.num.js" type="text/javascript"></script>

        <!-- notice -->
        <script type="text/javascript" src="/admin/js/jquery.notice/jquery.notice.js"></script>
  
        <!-- main  -->
        <script type="text/javascript" src="/admin/js/admin/main.js"></script>
  
        <!-- AdminLTE App -->
        <script src="/admin/js/AdminLTE/app.js" type="text/javascript"></script>
        
        <!-- fancybox -->
        <script type="text/javascript" src="/admin/js/fancybox2/jquery.fancybox.pack.js"></script>
        <link rel="stylesheet" href="/admin/js/fancybox2/jquery.fancybox.css" /> 

        <!-- CK Editor -->
        <script src="/admin/js/plugins/ckeditor/ckeditor.js" type="text/javascript"></script>
        
        <!-- AdminLTE dashboard demo (This is only for demo purposes) 
        <script src="/admin/js/AdminLTE/dashboard.js" type="text/javascript"></script>
        -->
        <!-- AdminLTE for demo purposes
        <script src="/admin/js/AdminLTE/demo.js" type="text/javascript"></script>
        -->

        <?php if(isset($menu_destaque)){ ?>
            <script>
                $(document).ready(function() {
                    $("li.<?php echo $menu_destaque; ?>").addClass("active")
                        .parents(".treeview-menu").css("display","block")
                        .parents("li.treeview").addClass("active");
                });
            </script>
        <?php } ?>

        <!-- DATA TABES SCRIPT -->
        <script src="/admin/js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="/admin/js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
        
        <!-- page script -->
        <script type="text/javascript">
            $(function() {
                $('.data-table').dataTable({ "bPaginate": true, "bLengthChange": false, "bFilter": true, "bSort": true, "bInfo": false, "bAutoWidth": false });
                $('.data').datepicker({ language : "pt-BR", format: "dd/mm/yyyy" });
                try{ 
                    CKEDITOR.replace('editor_html');
                }catch(ex){}
            });
        </script>

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