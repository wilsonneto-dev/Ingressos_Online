
			<section class="default_page signup">
				<header>
					<h1>Qual a nova senha?</h1>
				</header>
				<section class="default_page_content">
					
					<?php if( $msg != "" ){ ?> 
					<div class="msg status<?php echo $erro; ?> signup">
						<?php echo str_replace( "\n", "<br />", $msg ); ?>
					</div>
					<?php } ?>


					<form class="login" method="post">
						<label>E-mail *</label>
						<input type="text" class="txt" name="email" required disabled value="<?php echo $user->email ?>" />
	
						<label>Nova Senha *</label>
						<input type="password" class="txt" name="senha" autofocus required />
						
						<label>Confirme a Senha *</label>
						<input type="password" class="txt" name="senha_confirmar" required />
						
						<input type="text" name="as" class="txt txt_as" />
						
						<input type="submit" class="btn_default btn_cadastrar" value="Recuperar Senha" />

					</form>
				</section>
			</section>
