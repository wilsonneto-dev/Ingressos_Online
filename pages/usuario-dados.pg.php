
			<section class="default_page signup">
				<header>
					<h1>Meus Dados</h1>
				</header>
				<section class="default_page_content">
					<section class="voltar"><a href="/usuario-home"><img src="/imgs/icons/ic-back-arrow.png" /><span>Voltar</span></a></section>
					<form class="signup" method="post">

						<p class="default center">
							* &Eacute; poss&iacute;vel alterar apenas alguns dados
						</p>

						<?php if( $msg != "" ){ ?> 
						<div class="msg status<?php echo $erro; ?> signup">
							<?php echo str_replace( "\n", "<br />", $msg ); ?>
						</div>
						<?php } ?>

						<div class="line">
							<div class="field">
								<label>Nome</label>
								<input type="text" class="txt" name="nome" disabled required value="<?php _echo( $user->nome ); ?>" />
							</div><div class="field">
								<label>Sobrenome</label>
								<input type="text" class="txt" name="sobrenome" disabled required value="<?php _echo( $user->sobrenome ); ?>" />
							</div>
						</div>
						
						<div class="line">
							<div class="field">
								<label><span class="cpf_label">CPF </span><span class="cpf_label_alerta field_msg"></span></label>
								<input type="text" disabled class="txt cpf cpf_validar" name="cpf" required value="<?php _echo( $user->cpf ); ?>" />
							</div><div class="field">
								<label>Data de Nascimento</label>
								<input type="text" class="txt date" disabled name="data_nascimento" required value="<?php _echo( $user->data_nascimento->format('d/m/Y') ); ?>" />
							</div>
						</div>
						
						<div class="line">
							<div class="field">
								<label>Sexo</label>
								<select class="txt data-value" disabled name="sexo" data-value="<?php _echo( $user->sexo ); ?>" >
									<option value="Masculino">Masculino</option>
									<option value="Feminino">Feminino</option>
								</select>
							</div><div class="field">
								<label>DDD * - Telefone *</label>
								<input type="text" class="txt_ddd" name="ddd" maxlength="2" required value="<?php _echo( $user->ddd ); ?>"  />&nbsp;&nbsp;<input type="text" class="txt_telefone only_numbers" name="telefone"  maxlength="9" required value="<?php _echo( $user->telefone ); ?>" />
							</div>
						</div>
						
						<div class="line">
							<div class="field">
								<label>Estado *</label>
								<select name="uf" class="txt cbo_estados data-value" data-value="<?php _echo( strtolower($cidade_selecionada->uf) ); ?>">
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
								<select class="txt cbo_cidades hidden data-value-cidade" name="cidade" data-value="<?php _echo( $user->cod_brasil_cidade ); ?>"></select>
								<img src="/imgs/loading.gif" class="cidades_loading" />	
							</div>
						</div>

						<div class="line">
							<div class="field">
								<label><span class="lbl_email">E-mail</span><span class="lbl_email_alerta field_msg"></span></label>
								<input disabled type="text" class="txt txt_email" name="email" required value="<?php _echo( $user->email ); ?>" />
							</div>
						</div>

						<input type="text" name="as" class="txt txt_as" />
						<div class="line a-right responsivo-center">
							<input type="button" class="btn_default cancel" value="Voltar" onclick="document.location = '/usuario-home';" />
							<input type="submit" class="btn_default btn_cadastrar" value="Atualizar Dados" />
						</div>
					</form>
				</section>
			</section>
