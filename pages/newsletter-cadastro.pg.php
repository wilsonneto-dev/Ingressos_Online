
			<section class="default_page contact">
				<?php if( $erro == 1 ){ ?> 
					<header>
						<h1>ocorreu um erro ao tentar cadastrar :(</h1>
					</header>
					<section class="default_page_content">
						<br />
						<div class="msg status<?php echo $erro; ?>">
							<?php echo $msg_contato; ?>
						</div>
						<br />
						<div class="center">
							<img src="/imgs/erro.png" />
						</div>
					</section>
				<?php } else { ?>
					<header>
						<h1>Obrigado por se cadastrar :)</h1>
					</header>
					<section class="default_page_content">
						<br />
						<div class="msg status0">
							<?php echo $msg_contato; ?><br />
							Em breve entraremos em contato com as novidades
						</div>
						<br />
						<div class="center">
							<img src="/imgs/newsletter-form-icon.png" />
						</div>
					</section>
				<?php } ?>
			</section>
