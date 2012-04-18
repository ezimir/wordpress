<?php global $style_dir; ?>

<?php
	$w = 990;
	$h = 375;

	// PUT IMAGE NAMES HERE
	// use following format for line:
	//  'filename.jpg',
	$slider_images = array(
		'f1.jpg',
		'f2.jpg',
		'f3.jpg',
		'f4.jpg',
		'f5.jpg',
		'f6.jpg',
	);
	// these images have to be in /images/ directory
?>

<?php if (count($slider_images)) : ?>
<div class="slider-wrapper theme-default">
	<div class="ribbon"></div>

	<div id="slider">
		<?php foreach ($slider_images as $slider_image) { ?>
		<img src="<?php echo get_stylesheet_directory_uri(); ?>/inc/thumb.php?src=<?php echo $style_dir; ?>/images/<?php echo $slider_image; ?>&h=<?php echo $h; ?>&w=<?php echo $w; ?>&zc=1" alt="" />
		<?php } ?>
	</div>
</div>
<?php endif; ?>

