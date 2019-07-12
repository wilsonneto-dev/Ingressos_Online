
			<section class="default_page signup">
				<header>
					<h1>&Eacute; bom ver voc&ecirc; novamente! :)</h1>
				</header>
				<section class="default_page_content">
					
					<?php if( $msg_erro != "" ){ ?> 
					<div class="msg status<?php echo $erro; ?> signup">
						<?php echo str_replace( "\n", "<br />", $msg_erro ); ?>
					</div>
					<?php } ?>


					<form class="login" method="post">
						<label>E-mail *</label>
						<input type="text" class="txt" name="email" required value="<?php _echo( _post("email") ); ?>" />
						<label>Senha *</label>
						<input type="password" class="txt" name="pass" required />
						
						<input type="text" name="as" class="txt txt_as" />
						
						<!-- label>
							<input type="checkbox" checked /><span>manter conectado</span>
						</label -->

						<input type="submit" class="btn_default btn_cadastrar" value="entrar" />
						<input type="button" class="btn_default btn_cadastrar white" value="Criar Conta" onclick="document.location.href = '/signup';" />
						
						<div class="a-right">
							<a href="/recuperar-senha" class="pass-recover">esqueceu a senha?</a>
						</div>

					</form>
				</section>
			</section>
