
			<section class="default_page signup">
				<header>
					<h1>Cadastre-se e compre ingressos para os melhores eventos! :D</h1>
				</header>
				<section class="default_page_content">
					<form class="signup" method="post">

						<?php if( $msg_erro != "" ){ ?> 
						<div class="msg status<?php echo $erro; ?> signup">
							<?php echo str_replace( "\n", "<br />", $msg_erro ); ?>
						</div>
						<?php } ?>

						<div class="line">
							<div class="field">
								<label>Nome *</label>
								<input type="text" class="txt" name="nome" required value="<?php _echo( _post("nome") ); ?>" />
							</div><div class="field">
								<label>Sobrenome *</label>
								<input type="text" class="txt" name="sobrenome" required value="<?php _echo( _post("sobrenome") ); ?>" />
							</div>
						</div>
						
						<div class="line">
							<div class="field">
								<label><span class="cpf_label">CPF * </span><span class="cpf_label_alerta field_msg"></span></label>
								<input type="text" class="txt cpf cpf_validar" name="cpf" required value="<?php _echo( _post("cpf") ); ?>" />
							</div><div class="field">
								<label>Data de Nascimento *</label>
								<input type="text" class="txt date" name="data_nascimento" required value="<?php _echo( _post("data_nascimento") ); ?>" />
							</div>
						</div>
						
						<div class="line">
							<div class="field">
								<label>Sexo *</label>
								<select class="txt<?php if( _post("sexo") != "" ){ echo ' data-value'; } ?>" name="sexo" data-value="<?php _echo( _post("sexo") ); ?>" >
									<option value="Masculino">Masculino</option>
									<option value="Feminino">Feminino</option>
								</select>
							</div><div class="field">
								<label>DDD * - Telefone *</label>
								<input type="text" class="txt_ddd only_numbers" name="ddd" maxlength="2" required value="<?php _echo( _post("ddd") ); ?>"  />&nbsp;&nbsp;<input type="text" class="txt_telefone only_numbers" name="telefone"  maxlength="9" required value="<?php _echo( _post("telefone") ); ?>" />
							</div>
						</div>
						
						<div class="line">
							<div class="field">
								<label>Estado *</label>
								<select name="uf" class="txt cbo_estados<?php if( _post("uf") != "" ){ echo ' data-value'; } ?>" data-value="<?php _echo( _post("uf") ); ?>">
									<option value="ac">Acre</option>
									<option value="al">Alagoas</option>
									<option value="ap">Amapá</option>
									<option value="am">Amazonas</option>
									<option value="ba">Bahia</option>
									<option value="ce">Ceará</option>
									<option value="df">Distrito Federal</option>
									<option value="es">Espirito Santo</option>
									<option value="go">Goiás</option>
									<option value="ma">Maranhão</option>
									<option value="ms">Mato Grosso do Sul</option>
									<option value="mt">Mato Grosso</option>
									<option value="mg">Minas Gerais</option>
									<option value="pa">Pará</option>
									<option value="pb">Paraíba</option>
									<option value="pr">Paraná</option>
									<option value="pe">Pernambuco</option>
									<option value="pi">Piauí</option>
									<option value="rj">Rio de Janeiro</option>
									<option value="rn">Rio Grande do Norte</option>
									<option value="rs">Rio Grande do Sul</option>
									<option value="ro">Rondônia</option>
									<option value="rr">Roraima</option>
									<option value="sc">Santa Catarina</option>
									<option value="sp" selected>São Paulo</option>
									<option value="se">Sergipe</option>
									<option value="to">Tocantins</option>
								</select>
							</div><div class="field">
								<label>Cidade *</label>
								<select class="txt cbo_cidades hidden<?php if( _post("cidade") != "" ){ echo ' data-value-cidade'; } ?>" name="cidade" data-value="<?php _echo( _post("cidade") ); ?>"></select>
								<img src="/imgs/loading.gif" class="cidades_loading" />	
							</div>
						</div>

						<div class="line">
							<div class="field">
								<label><span class="lbl_email">E-mail *</span><span class="lbl_email_alerta field_msg"></span></label>
								<input type="text" class="txt txt_email" name="email" required value="<?php _echo( _post("email") ); ?>" />
							</div><div class="field">
								<label><span class="como_encontrou_label">Como encontrou o site? *</span></label>
								<select class="txt cbo_encontrou<?php if( _post("como_conheceu") != "" ){ echo ' data-value'; } ?>" name="como_conheceu" data-value="<?php _echo( _post("como_conheceu") ); ?>" />
									<option value="0">Escolha...</option>
									<option value="Google">Google</option>
									<option value="Facebook">Facebook</option>
									<option value="Amigos">Amigos</option>
									<option value="Revistas">Revistas</option>
									<option value="Panfletos">Panfletos</option>
									<option value="Outros">Outros</option>
								</select>
							</div>
						</div>

						<div class="line">
							<div class="field">
								<label>Senha *</label>
								<input type="password" class="txt senha" name="senha" required />
							</div><div class="field">
								<label><span class="confirme_senha_label">Confirme a Senha *</span></label>
								<input type="password" class="txt senha_confirmar" name="senha_confirmar" required />
							</div>
						</div>
						
						<div class="line a-right m10">
							<label>
								<input type="checkbox" checked value="1" class="termos" />
								Concordo com os <a href="/termos-de-uso" target="_blank" class="link_destaque">Termos de Uso</a>
 							</label><br />
						</div>

						<input type="text" name="as" class="txt txt_as" />
						<div class="line a-right responsivo-center">
							<input type="submit" class="btn_default btn_cadastrar" value="Criar Conta" />
						</div>
					</form>
				</section>
			</section>
