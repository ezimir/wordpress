<?php
	// PUT IMAGE NAMES HERE
	// use following format for line:
	//  'yyyy/mm/filename.jpg',
	$images = array(
	);
	// these images have to be in /wp-content/uploads/ directory
	// or wherever is current upload directory

	$mediatag = 'slideshow';
	$slider_images = array();

	if (function_exists('get_attachments_by_media_tags')) {
		function sort_by_title($a, $b) {
			if ($a->post_title == $b->post_title) return 0;
			return ($a->post_title > $b->post_title) ? 1 : -1;
		}

		$media = get_attachments_by_media_tags('media_tags=' . $mediatag);
		if ($media) {
			usort($media, 'sort_by_title');
			foreach ($media as $image) {
				$slider_images[] = $image->guid;
			}
		}
	}

	if (count($slider_images) == 0 && count($images) > 0) {
		$upload_dir = wp_upload_dir();
		$upload_url = $upload_dir['baseurl'] . '/';
		for ($i = 0; $i < count($images); $i++) {
			$slider_images[$i] = $upload_url . $images[$i];
		}
	}
?>

<?php if (count($slider_images)) : ?>
<div class="slider-wrapper theme-default">
	<div class="ribbon"></div>

	<div id="slider">
		<?php foreach ($slider_images as $slider_image) { ?>
		<img src="<?php echo get_stylesheet_directory_uri(); ?>/inc/thumb.php?src=<?php echo $slider_image; ?>&h=350&w=990&zc=1" alt="" />
		<?php } ?>
	</div>
</div>
<?php endif; ?>

