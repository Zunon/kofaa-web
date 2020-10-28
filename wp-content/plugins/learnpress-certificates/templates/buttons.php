<?php
/**
 * Template for displaying download button.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/addons/certificates/buttons.php.
 *
 * @author  ThimPress
 * @package LearnPress/Templates/Certificates
 * @cersion 3.0.0
 */

defined( 'ABSPATH' ) or die();

if ( ! isset( $certificate ) ) {
	return;
}
?>

<ul class="certificate-actions">
    <li class="download">
   <?php 
   $cert_image = ( 'image' == LP()->settings->get( 'certificates.cer_type' ));
   if($cert_image) {
	   $cert_file_path = $certificate->get_file_path();
	   $cert_image_url = $cert_file_path['img_url'];
   ?>
           <a href="<?php echo esc_url( $cert_image_url ); ?>"
           download="<?php echo esc_attr( pathinfo( $cert_image_url, PATHINFO_BASENAME ) ); ?>"></a>
   <?php 	
   } else {
   ?>
        <a href=""
           data-cert="<?php echo $certificate->get_uni_id(); ?>" data-type="jpg"></a>
   <?php 
   }
   
   ?>
    </li>
	<?php if ( $socials ) {
		foreach ( $socials as $social ) { ?>
            <li>
				<?php echo $social; ?>
            </li>
		<?php }
	} ?>
</ul>

