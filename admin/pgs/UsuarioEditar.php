<?php

// valida permissão
if( !in_array( "pedidos", $permissoes_admin ) ){
	include("pgs/404.pg.php");
	return;
}

$menu_destaque = "usuarios";

$u = Usuario::_get( intval( $_GET["cod"] ) );
if( $u == null ){
	include("pgs/404.pg.php");
	return;
}

/*
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
*/

$page = new StdAdminPage();

$page->title = "Editar Usuário";
$page->page = "Usuario";
$page->back_link = true;
$page->title_back = "Usuários";

$page->form = true;
$page->form_fields = array(
	array( "value" => $p->nome, "name" => "nome", "label" => "Nome *", "required" => true, "autofocus" => true ),
	array( "value" => $p->cpf, "name" => "cpf", "label" => "CPF *", "required" => true ),
	array( "value" => $p->email, "name" => "email", "label" => "E-mail *", "required" => true ),
	array( "name" => "senha", "label" => "Alterar Senha?" ),
);
$page->render();

?>