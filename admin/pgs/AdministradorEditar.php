<?php

// valida permissão
if( !in_array( "administradores", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$menu_destaque = "administradores";

$p = new Admin();

if( isset($_GET["id"]) ){
	$p->id = $_GET["id"];
	if($p->get()){
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
		
			$clone = clone $p;

			$p->cod_grupo_admin = $_POST[ "cod_grupo_admin" ];
			$p->nome = $_POST["nome"];
			$p->senha = $_POST["senha"];

			if( $p->senha != "" ){
				if ( Pass::testar( $p->senha ) > 3) {
					$p->atualizar_senha();
				}else{
					addOnloadScript("message('Senha não alterada, a senha digitada é muito fraca, insira digitos e letras maiusculas, e minimo de 8 caracteres.','error');");
				}
			}

			$bloqueado = 0;
			if ( isset( $_POST["bloqueado"] ) ) 
				if( $_POST["bloqueado"] == "ativo" ) 
					$bloqueado = 1; 
			
			$p->bloqueado = $bloqueado;

			if ( $_FILES["imagem"]["error"] == 0 ){
				$img_name = gerar_hash();
				$p->imagem = Upload::salvaArq( "administradores/" . $img_name, $_FILES["imagem"] );
				Imagem::MiniaturaProporcional( "../".$p->imagem, ADMIN_HEIGHT, ADMIN_WIDTH );
			}
			
			if($p->atualizar()){
				LogAdmin::_salvar( "Administrador Topo Editado", "Administrador" , $admin->id, json_encode( $clone ), json_encode( $p ) );
				addOnloadScript("message('Atualizado com sucesso.','sucess');");
			} else {
				LogAdmin::_salvar( "Erro ao editar banner topo", "Administrador" , $admin->id, json_encode( $clone ), json_encode( $p ) );
				addOnloadScript("message('Erro ao atualizar.','error');");
			}
		}
	}
}

$page = new StdAdminPage();

$page->title = "Cadastrar Administrador";
$page->page = "Administrador";
$page->back_link = true;
$page->title_back = "Administradores";

$sel_grupo = new SqlSelect("SELECT id as valor, nome as texto FROM grupo_admin WHERE codprojeto = ".CODPROJETO." AND ativo = 1 ORDER BY nome"); 
$sel_grupo->nome = "cod_grupo_admin";
if( $p != "" ) 
	$sel_grupo->valorSelecionado = $p->cod_grupo_admin; 
$sel_grupo->exec();

$page->form = true;
$page->form_fields = array(
	array( "label" => "Grupo", "type" => "html-field", "html" => $sel_grupo->html ),
	array( "value" => $p->nome, "name" => "nome", "label" => "Nome *", "required" => true, "autofocus" => true ),
	array( "label" => "Imagem Atual", "type" => "image-view", "value" => ( "/" . $p->imagem ) ),
	array( "name" => "imagem", "label" => "Imagem", "type" => "file" ),
	array( "name" => "senha", "label" => "Alterar Senha?" ),
	array( "value" => $p->bloqueado, "name" => "bloqueado", "label" => "Bloqueado", "type" => "checkbox" )
);
$page->render();

?>