<?php
	$upload_dir = wp_upload_dir();
	$upload_url = $upload_dir['baseurl'] . '/';

	// PUT IMAGE NAMES HERE
	// use following format for line:
	//  'yyyy/mm/filename.jpg',
	$slider_images = array(
	);
	// these images have to be in /wp-content/uploads/ directory
	// or wherever is current upload directory
?>

<?php if (count($slider_images)) : ?>
<div class="slider-wrapper theme-default">
	<div class="ribbon"></div>

	<div id="slider">
		<?php foreach ($slider_images as $slider_image) { ?>
		<img src="<?php echo get_stylesheet_directory_uri(); ?>/inc/thumb.php?src=<?php echo $upload_url . $slider_image; ?>&h=350&w=990&zc=1" alt="" />
		<?php } ?>
	</div>
</div>
<?php endif; ?>

