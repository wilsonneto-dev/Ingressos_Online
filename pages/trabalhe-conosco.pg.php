
			<section class="default_page contact">
				<header>
					<h1>Seria um prazer ter voc&ecirc; em nossa equipe ;)</h1>
				</header>
				<section class="default_page_content">
					<form class="contato" method="post" enctype="multipart/form-data">
						<input value="<?php echo $_nome; ?>"  type="text" class="txt_m" name="nome" placeholder="Nome *" required />
						<input value="<?php echo $_email; ?>"  type="text" class="txt_m" name="email" placeholder="E-mail *" required />
						<input value="<?php echo $_telefone; ?>" type="text" class="txt_m" name="telefone" placeholder="Telefone" />
						<input type="file" class="txt_m cv_file" name="cv" placeholder="Curriculum Vitae" required />
						<input class="fakeupload txt_m" placeholder="Arquivo Curriculum Vitae *" type="text" required onfocus="$('.cv_file').trigger('click');" onclick="$('.cv_file').trigger('click');" />
						<input value="<?php echo $_linkedin; ?>" type="text" class="txt_m" name="linkedin" placeholder="LinkedIn"  />
						<input value="<?php echo $_especialidade; ?>" type="text" class="txt_m" name="especialidade" placeholder="Especialidade ou cargo pretendido *" required />
						<textarea name="sobre" class="text_area txt_m" placeholder="Um pouco sobre voc&ecirc; *" required><?php echo $_sobre; ?></textarea>

						<input type="text" name="as" class="txt txt_as" />

						<?php if( $msg_contato != "" ){ ?> 
						<div class="msg status<?php echo $erro; ?>">
							<?php echo $msg_contato; ?>
						</div>
						<?php } ?>
						<div class="a-right">
							<input type="submit" class="btn_default" value="Enviar" />
						</div>
					</form>
				</section>
			</section>
