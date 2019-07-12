
		<div class="event">
			<section class="image">
				<img src="/<?php _echo( $obj->imagem ) ; ?>"></img>
			</section>
			<section class="content">
				<article>
					<header>
						<h1><?php _echo( $obj->titulo ) ; ?></h1>
					</header>
					<span class="info-text">
						<img src="/imgs/event-bullet-date.png" /> <?php _echo( $obj->data_mostrar ); ?> 
					</span>
					<span class="info-text">
						<img src="/imgs/event-bullet-location.png" /> <?php _echo( $local->nome ) ; ?> - <?php _echo( $cidade->nome ) ; ?> 
					</span>
<?php echo( $obj->descricao ); ?>
					<p>
<?php if($obj->link_facebook != "") { ?> Facebook: <a href="<?php _echo( $obj->link_facebook ); ?>" target="_blank"><?php _echo( str_replace(["http://","https://","www."], "", $obj->link_facebook )); ?></a><br /> <?php } ?> 
<?php if($obj->link_site != "") { ?> Site: <a href="<?php _echo( $obj->link_site ); ?>" target="_blank"><?php _echo( str_replace(["http://","https://"], "", $obj->link_site )); ?></a><br /> <?php } ?> 
					</p>
					
					<span class="label">Retirada dos Ingressos</span>
					<p class="tickets">
						<?php _echo( $obj->retirada ); ?><br />
						Para imprimir seu comprovante acesse: <a href="/usuario-pedidos">Meus Pedidos</a>.
					</p>

<?php if( $obj->data_encerrar_vendas < data(date("d-m-Y")) ){ ?>
					<p class="bold">
						<span class="upper">
							** VENDAS ONLINE ENCERRADAS **<br />
							<small>pagamentos realizados ap&oacute;s <?php echo date_format( $obj->data_encerrar_vendas , 'd/m' ); ?> n&atilde;o ser&atilde;o aceitos</small>
						</span>
					</p>
<?php } else if( $obj->venda_suspensa == 1 ){ ?>
					<p class="bold">
						<span class="upper">
							** VENDAS ONLINE ENCERRADAS **<br />
							<!-- small>Em breve estar&aacute; dispon&iacute;vel novamente...</small -->
						</span>
					</p>
<?php }else if( $r_ingressos->html != "" ) { ?>
				<form class="pedido" action="/pedido" method="post">
					<span class="label">Ingressos</span>
					
					<table class="tb_order">
						<?php echo $r_ingressos->html; ?>
					</table>
					<br />
					<?php if( ( new DateTime() )->add( new DateInterval('P3D') ) > $obj->data_encerrar_vendas ){ ?>
					<small class="payment_limit">* o prazo limite para realizar o pagamento &eacute; dia <?php echo date_format( $obj->data_encerrar_vendas , 'd/m' ) ?>.</small>
					<?php } ?>
					<div class="wrapper_button">
						<span class="label_total"></span>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="submit" class="btn_comprar" value="comprar" />
					</div>
					<input type="text" name="as" class="txt txt_as" />
					<input type="text" name="url" class="txt txt_as" value="<?php echo $obj->id_url; ?>" />
				</form>

<?php } ?>

				</article>

				

			</section>
		</div>