<?php

// validar usuário
if( $organizador == null ){
	header("Location: /organizador/login");
	die();
}

$user = $organizador;
$msg = "";
$erro = 1;

if( $_SERVER["REQUEST_METHOD"] == "POST" ){

	if( isset( 
		$_POST["senha_atual"],
		$_POST["senha"],
		$_POST["senha_confirmar"]
	) ){
		
		$user->senha = $_POST["senha_atual"];
		
		if( 
			trim( $_POST["senha"] ) == "" ||
			trim( $_POST["senha_confirmar"] ) == "" ||
			trim( $user->senha ) == "" 
		 ){
			$msg .= utf8_encode( " * Preencha todos os campos marcados com * por favor. \n" );
		}

		$teste = Promoter::_logar( $user->email, $user->senha );

		if( $teste == null ){
			$msg .= "* Senha atual informada incorreta\n";
		}

		if(	trim( $_POST["senha"] ) != trim( $_POST["senha_confirmar"] ) ){
			$msg .= "* A confirma&ccedil;&atilde;o da senha n&atilde;o est&aacute; correta. Digite novamente por favor...\n";
		}

		// não tem erro, vamos cadastrar
		if( $msg == "" ){
			$user->senha = $_POST["senha"];
			if( $user->atualizar_senha() ){
				
				// gravar log com cadastro feito
				LogPromoter::_salvar( "Usuario $user->id/$user->email/$user->razao_social atualizou sua senha.", "Atualizou Dados", $user->id );
				$msg .= "* Dados atualizados com sucesso!\n";
				$erro = "0";

			}else{
				$msg .= "* Houve um erro ao salvar as altera&ccedil;&otilde;es.\n";
			}

		}

	}else{
		$msg .= "* Preencha todos os campos por favor.\n";
	}

	// inserir as quebras html
	if( $msg != "" && $erro == 1 ){
		LogGeral::_salvar( "Tentativa de atualizar a senha de Organizador #$user->id/$user->email/$user->razao_social.", "Erro Cadastro" );
	}
}


?>

<?php if( $msg != "" ){ ?> 
	<div class="msg status<?php echo $erro; ?> signup">
		<?php echo str_replace( "\n", "<br />", $msg ); ?>
	</div>
	<br /><br />
<?php } ?>


<div class="col-xs-11 col-sm-6">

	
	<form method="post">

		<div class="row">
			<div class="input-group">
			  <span class="input-group-addon">Senha Atual</span>
			  <input type="password" class="form-control" name="senha_atual" />
			</div>
		</div>
		<br />

		<div class="row">
			<div class="input-group">
			  <span class="input-group-addon">Senha Nova</span>
			  <input type="password" class="form-control"  name="senha" />
			</div>
		</div>
		<br />
		
		<div class="row">
			<div class="input-group">
			  <span class="input-group-addon">Confirma&ccedil;&atilde;o</span>
			  <input type="password" class="form-control" name="senha_confirmar" />
			</div>
		</div>

		<br />
		<div style="text-align: right">
			<input type="submit" class="btn btn-primary" value="Alterar Senha" />
		</div>

	</form>

</div>

