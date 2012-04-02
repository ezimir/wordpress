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

				<div class="widget">

					<h4>Facebook</h4>
					<div class="tweet">
						<p>Vždy čerstvé informácie a pikošky, samozrejme na našej stránke na <a href="https://www.facebook.com/pages/Arthea-sro/242624912457370">Facebook-u</a>.</p>
					</div>

				</div><!-- .widget -->

				<div class="widget flickr_widget">
					<img src="http://flickholdr.com/51/51/trees/bw" alt="" />
					<img src="http://flickholdr.com/51/51/sea/bw" alt="" />
					<img src="http://flickholdr.com/51/51/sun/bw" alt="" />
					<img src="http://flickholdr.com/51/51/mountain/bw" alt="" />
					<img src="http://flickholdr.com/51/51/flowers/bw" alt="" />
					<img src="http://flickholdr.com/51/51/lake/bw" alt="" />
				</div><!-- .widget -->

				<div class="widget logo_widget">

					<img src="<?php echo $style_dir; ?>/images/impresso-logo-139-dark.png" alt="" />

					<div class="widget widget_social_network">
						<a href="#" title=""><img src="<?php echo $style_dir; ?>/images/facebook_footer.png" alt="" /></a>
						<a href="#" title=""><img src="<?php echo $style_dir; ?>/images/twitter_footer.png" alt="" /></a>
						<a href="#" title=""><img src="<?php echo $style_dir; ?>/images/rss_footer.png" alt="" /></a>
						<a href="#" title=""><img src="<?php echo $style_dir; ?>/images/dribbble_footer.png" alt="" /></a>
					</div>

				</div><!-- .widget -->

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
