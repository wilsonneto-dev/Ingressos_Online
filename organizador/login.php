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
            $adm = Promoter::_logar( $_POST["organizador_email"], $_POST["organizador_pass"] );
            if( $adm == null ){
                // mensagem de acesso negado e salvar log
                $alert = AdminViews::alert( "Acesso negado!","Verifique o e-mail e senha inseridos.<br />Seus dados foram registrados para uma poss&iacute;vel auditoria, ip: ".$_SERVER["REMOTE_ADDR"].", data: ".date("d/m/Y H.i.s")."","danger" ); 
                LogPromoter::_salvar( "Tentativa de login com e-mail \"".$_POST["organizador_email"]."\" falhou.", "Login");
            } else {
                if($adm->bloqueado == 1){
                    $alert = AdminViews::alert( "Acesso bloqueado!","Entre em contato com o administrador para mais detalhes.<br />Seus dados foram registrados para uma poss&iacute;vel auditoria, ip: ".$_SERVER["REMOTE_ADDR"].", data: ".date("d/m/Y H.i.s")."","danger" ); 
                    LogPromoter::_salvar( "Usu&aacute;rio \"".$adm->nome."\", e-mail \"".$_POST["organizador_email"]."\" tentou efetuar logou, mas está bloqueado.", "Login");
                } else {
                    // logou
                    LogPromoter::_salvar( "Usu&aacute;rio \"$adm->responsavel\"/\"$adm->razao_social\", e-mail \"".$_POST["organizador_email"]."\" logou.", "Login", "", "", $adm->id );
                    // salvar o admin na sessão
                    $_SESSION["organizador"] = $adm;
                    $adm->atualizar_ultimo_acesso();
                    
                    header( "Location: /organizador/" );
                    exit;
                }
            }
        }

    } else {
        // se não foi tentativa de logar, destruir sessões anteriores
        unset( 
            $_SESSION["organizador"]
        ); 
    }
    
    if($alert != ""){
        $alert .= "<br />";
    }
     

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Organizador - login</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link href="/organizador/assets/css/login.css" rel="stylesheet" type="text/css" />
    </head>
    <body>

        <div class="login-wrapper">    

            <br />
            <br />
            <center>
                <img src="/organizador/imgs/mail-logo.png" />
            </center>
            <br />

            <form action="" method="post">
                <div class="body bg-gray">
                    <div class="form-group">
                        <input autofocus type="email" name="organizador_email" required class="form-control txt" placeholder="E-mail"/>
                    </div>
                    <div class="form-group">
                        <input type="password" name="organizador_pass" required class="form-control txt" placeholder="Senha"/>
                    </div>          
                    <input type="text" name="teste" placeholder="pass" class="some" />
                </div>
                <div class="footer">                                                               
                    <button type="submit" class="btn bg-olive btn-block">acessar</button>  
                </div>
            </form>

            <br />

            <?php echo $alert; ?>     

        </div>

    </body>
</html>