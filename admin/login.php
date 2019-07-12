<?php 
    include_once '../php/config/constantes.php';
    
    session_start();
    $alert = "";

    if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
        // verifica o "testar"
        if( $_POST["teste"] != "" ){
            $alert = AdminViews::alert( "Requisi&ccedil;&atilde;o negada!","Verifique os dados inseridos.<br />Seus dados foram registrados para uma poss&iacute;vel auditoria, ip: ".$_SERVER["REMOTE_ADDR"].", data: ".date("d/m/Y H.i.s")."","danger" ); 
        } else {
            // testa o login
            $adm = Admin::_logar( $_POST["email"],  $_POST["pass"] );
            if( $adm == null ){
                // mensagem de acesso negado e salvar log
                $alert = AdminViews::alert( "Acesso negado!","Verifique o e-mail e senha inseridos.<br />Seus dados foram registrados para uma poss&iacute;vel auditoria, ip: ".$_SERVER["REMOTE_ADDR"].", data: ".date("d/m/Y H.i.s")."","danger" ); 
                LogAdmin::_salvar( "Tentativa de login com e-mail \"".$_POST["email"]."\" falhou.", "Login");
            } else {
                if($adm->bloqueado == 1){
                    $alert = AdminViews::alert( "Acesso bloqueado!","Entre em contato com o administrador para mais detalhes.<br />Seus dados foram registrados para uma poss&iacute;vel auditoria, ip: ".$_SERVER["REMOTE_ADDR"].", data: ".date("d/m/Y H.i.s")."","danger" ); 
                    LogAdmin::_salvar( "Usu&aacute;rio \"".$adm->nome."\", e-mail \"".$_POST["email"]."\" tentou efetuar logou, mas está bloqueado.", "Login");
                } else {
                    // logou
                    LogAdmin::_salvar( "Usu&aacute;rio \"".$adm->nome."\", e-mail \"".$_POST["email"]."\" logou.", "Login", "", "", $adm->id );
                    // salvar o admin na sessão
                    $_SESSION["admin"] = $adm;
                    $adm->atualizar_ultimo_acesso();
                    // salvar o grupo
                    $grupo = GrupoAdmin::_get( $adm->cod_grupo_admin );
                    $_SESSION["admin_grupo"] = $grupo;
                    // salvar permissões na sessão
                    $_SESSION["admin_permissoes"] = PaginaAdmin::_getListaPermissoesByGrupoMenu($adm->cod_grupo_admin);
                    // salvar o menu
                    $_SESSION["admin_menu_html"] = AdminViews::gerar_menu( $adm->cod_grupo_admin );
                    // salvar as notificações gerais
                    $_SESSION["admin_notificacoes_gerais_html"] = AdminViews::gerar_notificacoes_gerais( $adm->id );
                    // redirecionar
                    $pg = PaginaAdmin::_get( $grupo->cod_pagina_admin );
                    header( "Location: /" . $pg->url );
                    exit;
                }
            }
        }

    } else {
        // se não foi tentativa de logar, destruir sessões anteriores
        unset( 
            $_SESSION["admin"], 
            $_SESSION["admin_notificacoes_gerais_html"],
            $_SESSION["admin_menu_html"],
            $_SESSION["admin_grupo"],
            $_SESSION["admin_permissoes"]
        ); 
    }
    
     

?>
<!DOCTYPE html>
<html class="bg-black">
    <head>
        <meta charset="UTF-8">
        <title>CMS - login</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link href="/admin/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="/admin/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="/admin/css/AdminLTE.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="/admin/js/html5shiv.js"></script>
          <script src="/admin/js/respond.min.js"></script>
        <![endif]-->
        
    </head>
    <body class="bg-black">

        <div class="login-wrapper">    

            <div class="form-box" id="login-box">
                <div class="header">CMS</div>
                <form action="" method="post">
                    <div class="body bg-gray">
                        <div class="form-group">
                            <input autofocus type="email" name="email" required class="form-control" placeholder="e-mail"/>
                        </div>
                        <div class="form-group">
                            <input type="password" name="pass" required class="form-control" placeholder="pass"/>
                        </div>          
                        <input type="text" name="teste" placeholder="pass" class="some" />
                    </div>
                    <div class="footer">                                                               
                        <button type="submit" class="btn bg-olive btn-block">logar</button>  
                    </div>
                </form>
            </div>
            <br />
            <?php echo $alert; ?>     
        
        </div>

        <script src="/admin/js/jquery.min.js"></script>
        <script src="/admin/js/bootstrap.min.js" type="text/javascript"></script>        

    </body>
</html>