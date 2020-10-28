<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WP_Bootstrap_Starter
 */

?>
	</div><!-- .row -->
		</div><!-- .container -->
			</div><!-- #content -->
   <footer id="colophon" class="site-footer foo_ter header-footer-group woo-footer" role="contentinfo">
		<section class="footer-section">
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-3 <?php if(get_bloginfo("language") == "ar") { echo "text-right"; } ?>">
						<div class="footer-section1">
						<!-- footer first section -->
							<?php dynamic_sidebar( 'footer-1' ); ?>
						</div>
					</div>
			        <div class="col-sm-3">
					
          				<div class="section">
						<h4 class="list-title font20px"><?php if(get_bloginfo("language") == "ar") { echo "روابط مفيدة"; }else { echo "Useful Links"; } ?></h4>
						<!-- footer second section -->
							<?php dynamic_sidebar( 'footer-2' ); ?>
            			</div>
					</div>
        			<div class="col-sm-3">
          				<div class="section">
						<h4 class="list-title font20px"><?php if(get_bloginfo("language") == "ar") { echo "أهم الروابط"; }else { echo "The most important links
"; } ?></h4>
						<!-- footer third section -->
						<?php dynamic_sidebar( 'footer-3' ); ?>
						</div>
					</div>
					<div class="col-sm-3">
          				<div class="section">
						<h4 class="list-title font20px"><?php if(get_bloginfo("language") == "ar") { echo "مصادر"; }else { echo "Sources"; } ?></h4>
						<!-- footer fourth section -->
						<?php dynamic_sidebar( 'footer-4' ); ?>
							
							<ul class="footer-list-style">
							   <li>
							   <!-- add social media icon -->
								<div class="d-flex social-buttons">
									
										<a href="https://www.facebook.com/Kofaa-Training-Services-350704598833454" target="_blank" class="fb-link"> <img src="<?php echo get_template_directory_uri(); ?>/assets/flogo_rgb_hex-brc-site-250.png" class="fb-icon"></a>
										<a href="https://www.instagram.com/kofaats/" target="_blank" class=""> <img src="<?php echo get_template_directory_uri(); ?>/assets/insta.png" class="google-icon"></a>
										<a href="https://wa.link/a4vi5a" target="_blank" class=""> <img src="<?php echo get_template_directory_uri(); ?>/assets/whatsapp.png" class="google-icon"></a>
									<div class="visa_img">
									<img src=<?php echo get_template_directory_uri()."/assets/visa_master.png"; ?> />
									</div>
								</div>
								</li>
							</ul>
					  	</div>
					</div>
      			</div>
			</div>
		</section>
	</footer><!-- #colophon -->
<?php
$current_language_code = apply_filters( 'wpml_current_language', null );
if($current_language_code == 'ar') { 
?>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/5f4507f51e7ade5df443c608/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
<?php
} else {
?>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/5f4507f51e7ade5df443c608/1ej9k380h';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->

<?php
}
?>
<?php //endif; ?>
</div><!-- #page -->
<?php wp_footer(); ?>
</body>
</html>