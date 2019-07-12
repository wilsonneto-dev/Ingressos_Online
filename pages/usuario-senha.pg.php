
			<section class="default_page signup">
				<header>
					<h1>Alterar Senha</h1>
				</header>
				<section class="default_page_content">
					<section class="voltar"><a href="/usuario-home"><img src="/imgs/icons/ic-back-arrow.png" /><span>Voltar</span></a></section>
					<form class="signup" method="post">

						<?php if( $msg != "" ){ ?> 
						<div class="msg status<?php echo $erro; ?> signup">
							<?php echo str_replace( "\n", "<br />", $msg ); ?>
						</div>
						<?php } ?>

						<div class="line">
							<div class="field">
								<label>Senha Atual*</label>
								<input type="password" class="txt senha_atual" name="senha_atual" required />
							</div>
						</div>

						<div class="line">
							<div class="field">
								<label>Nova Senha *</label>
								<input type="password" class="txt senha" name="senha" required />
							</div><div class="field">
								<label><span class="confirme_senha_label">Confirme a Senha *</span></label>
								<input type="password" class="txt senha_confirmar" name="senha_confirmar" required />
							</div>
						</div>

						<input type="text" name="as" class="txt txt_as" />
						<div class="line a-right responsivo-center">
							<input type="button" class="btn_default cancel" value="Voltar" onclick="document.location = '/usuario-home';" />
							<input type="submit" class="btn_default btn_cadastrar" value="Atualizar Senha" />
						</div>
					</form>
				</section>
			</section>
