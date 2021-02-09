<?php
	$attachment_meta = mrseo_elated_get_attachment_meta_from_url($logo_image);
	$hwstring = !empty($attachment_meta) ? image_hwstring( $attachment_meta['width'], $attachment_meta['height'] ) : '';

	if(!empty($logo_image_dark)) {
		$attachment_meta_dark = mrseo_elated_get_attachment_meta_from_url($logo_image_dark);
		$hwstring_dark = !empty($attachment_meta_dark) ? image_hwstring($attachment_meta_dark['width'], $attachment_meta_dark['height']) : '';
	}

	if(!empty($logo_image_light)) {
		$attachment_meta_light = mrseo_elated_get_attachment_meta_from_url( $logo_image_light );
		$hwstring_light = !empty($attachment_meta_light) ? image_hwstring($attachment_meta_light['width'], $attachment_meta_light['height']) : '';
	}
?>

<?php do_action('mrseo_elated_before_site_logo'); ?>

<div class="eltdf-logo-wrapper">
    <a itemprop="url" href="<?php echo esc_url(home_url('/')); ?>" <?php mrseo_elated_inline_style($logo_styles); ?>>
        <img itemprop="image" class="eltdf-normal-logo" src="<?php echo get_site_url(); ?>/wp-content/themes/mrseo/assets/img/logo.svg" alt="<?php esc_attr_e('logo','mrseo'); ?>"/>
    </a>
</div>

<?php do_action('mrseo_elated_after_site_logo'); ?>