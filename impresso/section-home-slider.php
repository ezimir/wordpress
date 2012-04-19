<?php
	// PUT IMAGE NAMES HERE
	// use following format for line:
	//  'yyyy/mm/filename.jpg',
	$slider_images = array(
	);
	// these images have to be in /wp-content/uploads/ directory
	// or wherever is current upload directory

	$upload_dir = wp_upload_dir();
	$upload_url = $upload_dir['baseurl'] . '/';
	for ($i = 0; $i < count($slider_images); $i++) {
		$slider_images[$i] = $upload_url . $slider_images[$i];
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

