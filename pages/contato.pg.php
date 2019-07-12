
			<section class="default_page contact">
				<header>
					<h1>Estamos aguardando sua mensagem :)</h1>
				</header>
				<section class="default_page_content">
					<form class="contato" method="post">
						<input value="<?php echo $_nome; ?>" type="text" class="txt_m" name="nome" placeholder="Nome *" required />
						<input value="<?php echo $_email; ?>" type="text" class="txt_m" name="email" placeholder="E-mail *" required />
						<input value="<?php echo $_telefone; ?>" type="text" class="txt_m" name="telefone" placeholder="Telefone" />
						<input value="<?php echo $_assunto; ?>" type="text" class="txt_m" name="assunto" placeholder="Assunto *" />
						<textarea name="mensagem" class="text_area txt_m" placeholder="Mensagem *" required><?php echo $_mensagem; ?></textarea>
						<input type="text" name="as" class="txt txt_as" />
						<?php if( $msg_contato != "" ){ ?> 
						<div class="msg status<?php echo $erro; ?>">
							<?php echo $msg_contato; ?>
						</div>
						<?php } ?>
						<div class="a-right">
							<input type="submit" class="btn_default" value="Enviar Mensagem" />
						</div>
					</form>
				</section>
			</section>
