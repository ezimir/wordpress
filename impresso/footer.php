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

				<div class="widget">
					<h4>Facebook</h4>
					<p id="fb-info">Vždy čerstvé informácie a pikošky, samozrejme na našej stránke na Facebooku:</p>

					<script id="template-fbuser" type="text/x-jquery-tmpl">
						<a href="${link}"><img src="${picture}" /></a>
						<a href="${link}"> ${name} </a>
						<span> ${about} </span>
						<iframe src="https://www.facebook.com/plugins/like.php?href=${link}&layout=button_count&font=trebuchet+ms&locale=sk_SK&appId=114370451982089" allowTransparency="true"></iframe>
					</script>
					<div id="fb-user"></div>
				</div><!-- .widget -->

				<div class="widget" id="facebook">
					<h4>Posledné príspevky</h4>

					<script id="template-fbfeed" type="text/x-jquery-tmpl">
						{{each(i, item) items}}
						<p>
							{{if item.message}}${item.message}{{else}}${item.name}{{/if}}
							<a href="${item.permalink}">${item.date} ${item.time}</a>
						</p>
						{{/each}}
					</script>
					<div id="fb-feed"></div>
				</div><!-- .widget -->

				<script>
					var uid = '242624912457370';

					$(function () {
						$('#fb-user').fbuser({ id: uid });
						$('#fb-feed').fbfeed({ id: uid, token: '114370451982089|Z1bWi06KeQrp_mu-7MgPTaKaeqA' });
					});
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

			<script>

				var _gaq = [['_setAccount', '<?php echo $ga_code; ?>'], ['_trackPageview']];
				(function(d, t) {
					var g = d.createElement(t),
						s = d.getElementsByTagName(t)[0];
					g.async = true;
					g.src = ('https:' == location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
					s.parentNode.insertBefore(g, s);
				})(document, 'script');

			</script>

		<?php endif; ?>

		<?php wp_footer(); ?>

	</body>

</html>
