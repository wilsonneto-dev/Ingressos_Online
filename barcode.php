<?php


define('IN_CB', true);

include_once('php/third_party/barcodegen.1d-php5.v5.2.0/html/include/function.php');
include_once ( 'php/config/constantes.php' );

session_start();


function showError(){
    header('Content-Type: image/png');
    // readfile('error.png');
    exit;
}

$global_usuario = null;
if( isset( $_SESSION[S_USUARIO] ) ){
    $global_usuario = $_SESSION[S_USUARIO];
}

// validar usuário
if( $global_usuario == null ){
    // mostrar mensagem na tela de login
    $_SESSION[S_MENSAGEM_ERRO] = "Efetue o login para continuar";
    // voltar para esta página ao logar
    $_SESSION[S_REDIRECIONAR] = $_SERVER["REQUEST_URI"];
    // redirecionar para a tela de login
    header("Location: /404");
    die();
}

$p = null;
if ( isset($_GET["order"], $_GET["email"],  $_GET["sec"] ) ){
    $p = Pedido::_get( intval($_GET["order"]) );
    if( $p == null ){
        throw new Exception( "Pedido não encontrado", "0302" );
    }
    if( $p->cod_usuario != $global_usuario->id ){
        throw new Exception( "Pedido não encontrado #2", "0302" );
    }
}

$COD_BARRAS = ( $p->codigo . "#" . $p->hash );

$requiredKeys = array('code', 'filetype', 'dpi', 'scale', 'rotation', 'font_family', 'font_size' /* , 'text' */);

// Check if everything is present in the request
foreach ($requiredKeys as $key) {
    if (!isset($_GET[$key])) {
        showError();
    }
}

if (!preg_match('/^[A-Za-z0-9]+$/', $_GET['code'])) {
    showError();
}

$code = $_GET['code'];

// Check if the code is valid
if (!file_exists('php/third_party/barcodegen.1d-php5.v5.2.0/html/config' . DIRECTORY_SEPARATOR . $code . '.php')) {
    showError();
}

include_once('php/third_party/barcodegen.1d-php5.v5.2.0/html/config' . DIRECTORY_SEPARATOR . $code . '.php');

$class_dir = 'php/third_party/barcodegen.1d-php5.v5.2.0/' . DIRECTORY_SEPARATOR . 'class';
require_once($class_dir . DIRECTORY_SEPARATOR . 'BCGColor.php');
require_once($class_dir . DIRECTORY_SEPARATOR . 'BCGBarcode.php');
require_once($class_dir . DIRECTORY_SEPARATOR . 'BCGDrawing.php');
require_once($class_dir . DIRECTORY_SEPARATOR . 'BCGFontFile.php');

if (!include_once($class_dir . DIRECTORY_SEPARATOR . $classFile)) {
    showError();
}

include_once('php/third_party/barcodegen.1d-php5.v5.2.0/html/config' . DIRECTORY_SEPARATOR . $baseClassFile);

$filetypes = array('PNG' => BCGDrawing::IMG_FORMAT_PNG, 'JPEG' => BCGDrawing::IMG_FORMAT_JPEG, 'GIF' => BCGDrawing::IMG_FORMAT_GIF);

$drawException = null;
try {
    $color_black = new BCGColor(0, 0, 0);
    $color_white = new BCGColor(255, 255, 255);

    $code_generated = new $className();

    if (function_exists('baseCustomSetup')) {
        // echo "here1";
        baseCustomSetup($code_generated, $_GET);
    }

    if (function_exists('customSetup')) {
        // echo "here2";
        customSetup($code_generated, $_GET);
    }

    $code_generated->setScale(max(1, min(4, $_GET['scale'])));
    $code_generated->setBackgroundColor($color_white);
    $code_generated->setForegroundColor($color_black);

    if ($_GET['text'] !== '') {
        $text = convertText( 
            $COD_BARRAS
        );
        $code_generated->parse($text);
    }
} catch(Exception $exception) {
    $drawException = $exception;
}

$drawing = new BCGDrawing('', $color_white);
if($drawException) {
    $drawing->drawException($drawException);
} else {
    $drawing->setBarcode($code_generated);
    $drawing->setRotationAngle($_GET['rotation']);
    $drawing->setDPI($_GET['dpi'] === 'NULL' ? null : max(72, min(300, intval($_GET['dpi']))));
    $drawing->draw();
}

switch ($_GET['filetype']) {
    case 'PNG':
        header('Content-Type: image/png');
        break;
    case 'JPEG':
        header('Content-Type: image/jpeg');
        break;
    case 'GIF':
        header('Content-Type: image/gif');
        break;
}
$drawing->finish($filetypes[$_GET['filetype']]);
?>