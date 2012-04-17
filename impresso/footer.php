		<?php global $style_dir, $data; ?>

		<footer id="main_footer">

			<?php if ( is_active_sidebar( 'footer_widget_area' ) ) : ?>

				<?php dynamic_sidebar( 'footer_widget_area' ); ?>

			<?php else : ?>

				<div class="widget">
					<h4>Kde nás nájdete</h4>
					<p>Hroncova 21<br />
					040 01, Košice</p>

					<p>T: +421 903 318 483</p>

					<p>E: arthea@arthea.sk</p>
				</div><!-- .widget -->

				<script src="http://ajax.microsoft.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js"></script>

				<script id="template-fbwidget" type="text/x-jquery-tmpl">
					<div id="fbuser" class="widget widget_fbuser">
						<h3 class="widget-title">Facebook</h3>
						<p id="fb-info">Vždy čerstvé informácie a pikošky, samozrejme na našej stránke na Facebooku:</p>
						<div id="fb-user"></div>
					</div>
				</script>

				<script id="template-fbuser" type="text/x-jquery-tmpl">
					<div id="fb-user">
						<a href="${link}"><img src="${picture}" /></a>
						<a href="${link}"> ${name} </a>
						<span> ${about} </span>
						<iframe src="https://www.facebook.com/plugins/like.php?href=${link}&layout=button_count&font=trebuchet+ms&locale=sk_SK&appId=114370451982089" allowTransparency="true"></iframe>
					</div>
				</script>

				<script>
					var $sidebar = $('#sidebar-primary');
					if ($sidebar.length) {
						$('#template-fbwidget').tmpl().appendTo($sidebar);

						$(function () {
							$('#fb-user').fbuser({ id: 242624912457370 });
						});
					}
				</script>

			<?php endif; ?>

			<div id="footer_bottom">

				<p><?php echo $data['footer_credits_text']; ?></p>

			</div><!-- #footer_bottom -->

		</footer><!-- #main_footer -->


		</div><!-- #wrap -->

		<!-- |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->

		<!-- ||||||||||||||||||||||||||||||||||||||||||| wp_footer() |||||||||||||||||||||||||||||||||||||||||||| -->

		<script src="<?php echo $style_dir; ?>/js/jquery.nivo.slider.pack.js"></script>
		<script src="<?php echo $style_dir; ?>/js/anythingslider.js"></script>
		<script src="<?php echo $style_dir; ?>/js/jquery.colorbox-min.js"></script>
		<script src="<?php echo $style_dir; ?>/js/jquery.facebook.feed.js"></script>
		<script src="<?php echo $style_dir; ?>/js/site.js"></script>

		<!--[if lt IE 9]>
			<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
			<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
			<script src="<?php echo $style_dir; ?>/js/css3-mediaqueries.js"></script>
		<![endif]-->

		<!--[if lt IE 8]>
			<script src="<?php echo $style_dir; ?>/js/imgSizer.js"></script>
			<script>
				function addLoadEvent(func) {
				  var oldonload = window.onload;
				  if (typeof window.onload != 'function') {
				    window.onload = func;
				  } else {
				    window.onload = function() {
				      if (oldonload) {
				        oldonload();
				      }
				      func();
				    }
				  }
				}

				addLoadEvent(imgSizer.collate());
			</script>
		<![endif]-->

		<!--[if lt IE 7]>
			<script src="<?php echo $style_dir; ?>/js/belatedpng.js"></script>
			<script>
				DD_belatedPNG.fix('img, .png_bg');
			</script>
		<![endif]-->

		<!--[if lt IE 9]>
			<script src="<?php echo $style_dir; ?>/js/css3-mediaqueries.js"></script>
		<![endif]-->

		<?php global $data; $ga_code = $data['google_analytics']; if($ga_code != "") : ?>

			<script type="text/javascript">

			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', '<?php echo $ga_code; ?>']);
			//_gaq.push(['_setDomainName', 'DOMAIN']);
			_gaq.push(['_trackPageview']);

			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();

			</script>

		<?php endif; ?>

		<?php wp_footer(); ?>

	</body>

</html>
