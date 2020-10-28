<?php
/**
 * Template for displaying certificate in user profile.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/addons/certificates/details.php.
 *
 * @package LearnPress/Templates/Certificates
 * @author  ThimPress
 * @version 3.0.0
 */

defined( 'ABSPATH' ) or die();

/**
 * @var LP_User_Certificate $certificate
 */
if ( ! isset( $certificate ) ) {
	return;
}
$cert_image  = ( 'image' == LP()->settings->get( 'certificates.cer_type' ) );
$template_id = $certificate->get_uni_id();

?>
<div class="certificate">
	<?php
	do_action( 'learn-press/certificates/before-certificate-content', $certificate );
	?>
	<?php
	if ( $cert_image ) {
		$cert_file_path = $certificate->get_file_path();

		if ( $cert_file_path ) {
			$cert_image_url = $cert_file_path['img_url'];

			echo '<img class="cert-template" src="' . esc_url( $cert_image_url . '?v=' . uniqid() ) . '" style="width: 100%;"/>';
		}
	} else {
		?>
		<div id="<?php echo $template_id; ?>" class="certificate-preview">
			<div class="certificate-preview-inner">

				<img class="cert-template" src="<?php echo $certificate->get_template(); ?>">
				<canvas></canvas>
			</div>
		</div>
		<?php
	} ?>
	<?php
	do_action( 'learn-press/certificates/after-certificate-content', $certificate );
	?>
	<?php
	if ( ! $cert_image ){
	?>
	<script type="text/javascript">

		document.addEventListener('readystatechange', event => {

			if (event.target.readyState === "complete") {
				jQuery(function ($) {
					window.certificates = window.certificates || [];
					window.certificates.push(new LP_Certificate('#<?php echo $template_id;?>', <?php echo $certificate;?>));
				})
			}

		});
		<?php
		}?>
	</script>
</div>

