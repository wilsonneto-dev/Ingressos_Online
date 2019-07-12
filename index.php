<?php
	include_once 'php/config/constantes.php';
	$pg = "";

	/* verificar se usuario esta logado */
	session_start();
	$global_usuario = null;
	if( isset( $_SESSION[S_USUARIO] ) )
	{
		$global_usuario = $_SESSION[S_USUARIO];
	}

	if( isset( $_GET["ref"] ) )
	{
		if( !isset( $_SESSION[S_REF] ) )
		{
			$_SESSION[S_REF] = $_GET["ref"];
		}
	}

	if (isset($_GET["pg"])) 
	{
		$pg = ($_GET["pg"]);
		$pg = str_replace("/", "", $pg);
		$pg = str_replace("\\", "", $pg);
		$pg = str_replace(".", "", $pg);
		if (file_exists("pages/".$pg.".cod.php"))
		{
			include("pages/".$pg.".cod.php");
		} 
		else if ( file_exists( "pages/404.cod.php" ) )
		{
			include("pages/404.cod.php");
		}
	} 
	else if( file_exists( "pages/home.cod.php" ) )
	{
		include( "pages/home.cod.php" );
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $_head_title; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1"> 
		<meta name="description" content="<?php echo $_meta_description; ?>" />
		<meta name="keywords" content="<?php echo $_meta_keywords; ?>" />
		<link href="/assets/css/default.css" rel="stylesheet" /><?php echo $header_extra_styles; ?>
		
		<!-- Facebook Pixel Code -->
		<script>
		  !function(f,b,e,v,n,t,s)
		  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
		  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
		  n.queue=[];t=b.createElement(e);t.async=!0;
		  t.src=v;s=b.getElementsByTagName(e)[0];
		  s.parentNode.insertBefore(t,s)}(window, document,'script',
		  'https://connect.facebook.net/en_US/fbevents.js');
		  fbq('init', '2212863855642564');
		  fbq('track', 'PageView');
		</script>
		<noscript><img height="1" width="1" style="display:none"
		  src="https://www.facebook.com/tr?id=2212863855642564&ev=PageView&noscript=1"
		/></noscript>
		<!-- End Facebook Pixel Code -->

	</head>
	<body>

		<header>
			<div class="wrapper">
				<input type="checkbox" id="control-nav" />
				<label for="control-nav" class="control-nav"></label>
				<div class="logo-wrapper"><a href="/"><img src="/imgs/header-logo.png" alt="Ze do Ingresso" /></a></div>
				<nav>
					<a href="/" title="Veja os pr&oacute;ximos eventos">Eventos</a>
					<a href="/duvidas" title="Alguma d&uacute;vida?">D&uacute;vidas</a>
					<a href="/trabalhe-conosco" title="Fa&ccedil;a parte da nossa equipe :)">Trabalhe Conosco</a>
					<a href="https://www.facebook.com/zedoingresso/" target="_blank" title="Entre em contato conosco">Contato</a>
				</nav>
				<?php if( $global_usuario == null ){ ?><section class="user">
					<a class="atention" href="/login" title="Fa&ccedil;a seu login">Logar</a>
					<a href="/signup" title="Cadastre-se">Cadastre-se</a>
				</section><?php } else { ?>
				<section class="user">
					<a href="#" onclick="$(this).toggleClass('menu-ativo'); return false;"><?php _echo($global_usuario->nome) ?> <img src="/imgs/icons/arrow-down.png" height="7" class="down" /><img class="up" src="/imgs/icons/arrow-up.png" height="7" /></a><br />
					<section class="menu">
						<a href="/usuario-home">&nbsp;&nbsp;<img src="/imgs/icons/ic-menu-usuario.png" /> &Aacute;rea do Usu&aacute;rio&nbsp;&nbsp;</a>
						<a href="/usuario-pedidos">&nbsp;&nbsp;<img src="/imgs/icons/ic-menu-pedidos.png" /> Meus Pedidos&nbsp;&nbsp;</a>
						<a href="/logout">&nbsp;&nbsp;<img src="/imgs/icons/ic-menu-sair.png" /> Sair&nbsp;&nbsp;</a>
					</section>
				</section><?php } ?>
			</div>
		</header>

		<!-- content -->
<?php
			if ( $pg != "" ) {
				if ( file_exists( "pages/".$pg.".pg.php" ) ){
					include("pages/".$pg.".pg.php");
				} else {
					include("pages/404.pg.php");
				}
			} else if( file_exists( "pages/home.pg.php" ) ){
				include( "pages/home.pg.php" );
			}
?>

		<section class="fw">
			<a href="https://www.instagram.com/zedoingresso" target="_blank"><img src="/imgs/follow-instagram.png" /> <span>@zedoingresso</span> </a>
			<a href="https://www.facebook.com/zedoingresso" target="_blank"><img src="/imgs/follow-face.png" /> <span>facebook.com/zedoingresso</span> </a>
		</section>

		<section class="newsletter-form">
			<div class="column1">
				<img src="/imgs/newsletter-form-icon.png" />
				<span class="span">
					<b>Receba as Novidades</b><br />
					Cadastre-se e receba nossas novidades, sorteios e promo&ccedil;&otilde;es por <span class="no-wrap">e-mail</span>
				</span>
			</div>
			<div class="column2">
				<form method="post" action="/newsletter-cadastro">
					<div class="field">
						<!-- label>Nome:</label -->
						<input type="text" class="txt" placeholder="Nome" name="nome" required  />
						<input type="text" name="as" class="txt txt_as" />
					</div>
					<div class="field">
						<!--label>E-mail:</label -->
						<input type="mail" class="txt" placeholder="E-mail" name="email" required />
					</div>
					<div class="field button">
						<input type="submit" value="Cadastrar" />
					</div>
				</form>
			</div>
		</section>


		<section class="pre_footer">
			<img src="/imgs/pre-footer-banner-pagseguro.png" />
		</section>

		<footer>
			<div class="img_wrapper">
				<img src="/imgs/footer-logo.PNG" />
			</div>
			<div class="right">
				<a href="mailto://contato@zedoingresso.com.br">
					<img src="/imgs/footer-icon-mail.png" />
					&nbsp;<?php echo EMAIL; ?><br />
				</a>
				<div style="margin-top: 10px;" class="clear"></div>
				<a href="#" onclick="return false;">
					<img src="/imgs/phone.png" />
					17 98800-3027<br />
				</a>
				<center>
					<br />
					Z&eacute; do Ingresso<br />
					<small>
						2014 - 2018 &reg; todos os direitos reservados
						<br />
						<a target="_blank" href="http://www.wilsonneto.com.br" style="font-size: inherit; vertical-align: inherit">desenvolvido por Wilson Neto</a> 
					</small>
				</center>
			</div>
		</footer>

		<!-- javascripts -->
		<!-- jQuery 2.1 --><script src="/assets/third_party/jQuery/jquery-2.1.3.min.js"></script><?php echo $footer_extra_scripts; ?>
		<script src="/assets/js/main.js"></script>
		<!-- end javascripts -->

		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-56326279-1', 'auto');
		  ga('send', 'pageview');

		</script>
		<!--Start of Tawk.to Script-->
		<script type="text/javascript">
			var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
			(function(){
			var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
			s1.async=true;
			s1.src='https://embed.tawk.to/5aba4e134b401e45400e190b/default';
			s1.charset='UTF-8';
			s1.setAttribute('crossorigin','*');
			s0.parentNode.insertBefore(s1,s0);
			})();
		</script>
		<!--End of Tawk.to Script-->

	</body>
</html>