
			<section class="default_page signup">
				<header>
					<h1>Recupere sua senha para aproveitar! ;)</h1>
				</header>
				<section class="default_page_content">
					
					<?php if( $msg != "" ){ ?> 
					<div class="msg status<?php echo $erro; ?> signup">
						<?php echo str_replace( "\n", "<br />", $msg ); ?>
					</div>
					<?php } ?>


					<form class="login" method="post">
						<label>E-mail *</label>
						<input type="text" class="txt" name="email" required autofocus value="<?php _post("email") ?>" />
						
						<input type="text" name="as" class="txt txt_as" />
						
						<input type="submit" class="btn_default btn_cadastrar" value="Recuperar Senha" />

					</form>
				</section>
			</section>
