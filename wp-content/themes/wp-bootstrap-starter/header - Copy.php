<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WP_Bootstrap_Starter
 */

function is_site_admin(){
    return in_array('administrator',  wp_get_current_user()->roles);
}
function is_teacher(){
    return in_array('lp_teacher',  wp_get_current_user()->roles);
}

global $woocommerce;

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <!-- <link rel="stylesheet" href="assets/css/bootstrap.min.css"> -->
  <!-- <link rel="stylesheet" href="assets/css/font-awesome.min.css"> -->
<?php wp_head(); ?>

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/kofaa.css">
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/OwlCarousel2-2.3.4/dist/assets/owl.carousel.min.css">
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/OwlCarousel2-2.3.4/dist/assets/owl.theme.default.min.css">

<script src="//code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/assets/OwlCarousel2-2.3.4/dist/owl.carousel.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/assets/OwlCarousel2-2.3.4/dist/owl.carousel.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/kofaa.js"></script>
<style>
ul.navbar-nav.ml-auto.desk_drop {
    margin-right: 54px;
}
.footer-section .section a {
	font-size: 18px;
}
.footer-section1 img.image.wp-image-622.attachment-medium.size-medium {
	width: 150px !important;
}
.arconix-faq-wrap {
	border: 2px solid #a2d66e !important;
	padding: 10px !important;
}
.home_section .green_color {
    color: #A2D66E;
    width: auto;
    float: left;
    font-size: 33px;
    margin-top: 4px;
    margin-right: 8px;
}
#content > .container-fluid{
		padding: 0;
	}
	.footer-section .section a {
		font-size: 18px;
	}
	.footer-section1 img.image.wp-image-622.attachment-medium.size-medium {
		width: 150px !important;
	}
	@media (max-width: 768px){
		#content > .container-fluid {
			padding-right: 20px;
			padding-left: 20px;
		}
	}
</style>
</head>

<body <?php body_class(); ?>>
 
<?php 
session_start();
$lang = get_bloginfo("language"); 
$current_page_id = get_the_ID(); 
$_SESSION['current_page_id'] = $current_page_id;

if($lang == "ar")
{
	$search_placeholder = "ابحث عن شغفك..";
	$cat_title = "الفئة";
	$access = "الدخول";
}
else
{
	$search_placeholder = "Find your passion ..";
	$cat_title = "Category";
	$access = "Access";
}
?>
<p id="homeurl" style="display:none;"><?php echo esc_url( home_url( '/' )); ?></p>
<div id="page" class="site">




<?php
    if (is_front_page()) {
    $args = array(
    // 'name'        => $the_slug,
    'post_type'   => 'post',
    'post_status' => 'publish',
    'category_name'  => 'announcement',
    'orderby' =>'date'
    );
    $my_announce = get_posts($args);
  ?>
<!-- announcement slider start here -->
<div id="announce_slide" class="carousel announce_carousel slide" data-ride="carousel">
  <!-- The slideshow -->
  <div class="carousel-inner">
    <div class="carousel-item active">
    <?php $announce_count=1;
          foreach($my_announce as $my_announces) {
            if($announce_count%2==0) {
              echo '</div><div class="carousel-item">';
            }
      ?>
    <section id="clients-edit-wrapper" class="width100 announce_sec">
          <div class="announce text-center">
              <div class="close-wrapper-btn text-right">
                  <a href="#" class="close-div"><span aria-hidden="true">×</span></a>
              </div>
              <?php echo ($my_announces->post_content) ?>
          </div>
      </section>
      <?php $announce_count++;
        } ?>
    </div>
  </div>
</div>
<!-- announcement slider end here -->
<?php } ?>
<!-- Header code start here -->
	<a class="skip-link screen-reader-text desk_drop" href="#content"><?php esc_html_e( 'Skip to content', 'wp-bootstrap-starter' ); ?></a>
    
        <div>
		<!-- logo code start here -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white nav-shadow fixed-top">
                <?php if ( get_theme_mod( 'wp_bootstrap_starter_logo' ) && !is_category('Kids')) { ?>
                <a class="navbar-brand" href="<?php echo esc_url( home_url( '/' )); ?>">
                    <img src="<?php echo esc_url(get_theme_mod( 'wp_bootstrap_starter_logo' )); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
                </a>
                <?php } if(is_category('Kids')) {?>
                <a class="navbar-brand" href="<?php echo esc_url( home_url( '/' )); ?>">
                    <img class="store_svg" src="<?php echo get_template_directory_uri(); ?>/assets/kid_logo (1).jpg">
                </a>
                <?php } ?>
		<!-- logo code end here -->
		<!-- course category menu code start here -->
                <ul class="navbar-nav ml-auto desk_drop">
                    <li class="nav-item dropdown">
                        <a class="nav-link" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="hamburger"></div>
                        <div class="hamburger"></div>
                        <div class="hamburger"></div> 
                        </a>
                        <ul class="dropdown-menu header_drop" id="style-3" aria-labelledby="navbarDropdownMenuLink"> 
                        <?php $categories = get_categories('taxonomy=course_category&type=lp_course'); 
                            foreach($categories as $category)
                            { ?>
                            <li class="dropdown-submenu">
                                <a class="dropdown-item " href="<?php echo home_url('/').'index.php/course-category/'.$category->slug; ?>"><?php echo $category->name; ?></a>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                </ul>
			<!-- course category menu code end here -->
			<!-- search box code satrt here -->
                <div class="input-group serch-section">
                    <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' )); ?>">
                        <label class="pos-rel">
                        <span class="fa fa-search form-control-feedback"></span>
                            <input type="search" class="search-field form-control" placeholder="<?php echo $search_placeholder; ?>" value="" name="s" title="يبحث عن:">
                            <input type="hidden" class="search-field form-control" placeholder="<?php echo $search_placeholder; ?>" value="course" name="ref" title="يبحث عن:">
                        </label>
                   </form>
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <a class="skip-link screen-reader-text mobile_dropdw" href="#content"><?php esc_html_e( 'Skip to content', 'wp-bootstrap-starter' ); ?></a>
              <!-- search box code end here --> 

				<!-- header menu code start here -->
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav all_list_menue mr-auto">
                        <!-- Mobile dropdown  -->
                        <li class="nav-item dropdown mobile_dropdw font18px font-weight5">
							<a class="nav-link dropdown-toggle"  id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <?php echo $cat_title; ?> </a>
							<ul class="dropdown-menu header_drop" aria-labelledby="navbarDropdownMenuLink"> 
							<?php $categories = get_categories('taxonomy=course_category&type=lp_course'); 
								foreach($categories as $category)
								{ ?>
								<li class="dropdown-submenu">
									<a class="dropdown-item " href="<?php echo home_url('/').'course-category/'.$category->slug; ?>"><?php echo $category->name; ?></a>
								</li>
								<?php } ?>
							</ul>
						</li>
						<!-- get nav menu here -->
						<?php
						$menuLocations = get_nav_menu_locations();
						$menuID = $menuLocations['primary'];
						$primaryNav = wp_get_nav_menu_items($menuID);
						foreach($primaryNav as $nav_menu)
						{
						if(!empty($nav_menu->thumbnail_id))
						{
						$image_attributes = wp_get_attachment_image_src( $nav_menu->thumbnail_id);
						if ( $image_attributes ) : ?>
						<li class="nav-item">
							<a class="nav-link" href="<?php echo $nav_menu->url; ?>">
								<?php
									if(!empty($nav_menu->title)) {
										$str_sm = strtolower($nav_menu->title);
										if($str_sm == "cart")
											if(sprintf(_n('%d', $woocommerce->cart->cart_contents_count, 'wp-bootstrap-starter'),$woocommerce->cart->cart_contents_count) >= 1) {
											?>
												<label id="lblCartCount">
													<?php echo sprintf(_n('%d', $woocommerce->cart->cart_contents_count, 'wp-bootstrap-starter'),$woocommerce->cart->cart_contents_count);
													?>
												</label>
											<?php
										}
									}
								?>
								<img src="<?php echo $image_attributes[0]; ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $nav_menu->title; ?>" /> 
							</a>
						</li>
						<?php 
						endif;
						}
						}
						?>
						
                    </ul>
                    <!-- header menu code end here -->
					
					<!-- after login menu code start here -->
                    <?php
					if ( is_user_logged_in() ) {
						
						$current_user = wp_get_current_user();?>
						<h4 class="hello-user"><?php _e("Hello", "wp-bootstrap-starter");?> <?php echo esc_html( $current_user->user_nicename ); ?></h4>
						
						<!-- get user detail code start here -->
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<?php //if( is_super_admin( $USER->id) ) 
										if(is_site_admin() || is_teacher()) { ?>
											<?php if(get_avatar_url($user->ID)) {?>
												<img src="<?php echo get_avatar_url($user->ID);?>" class="rounded-circle" width="40px" height="40px">
											<?php } ?>
									<?php }else{ ?>
											<?php 
											$profile = LP_Profile::instance();
											$lpuser = $profile->get_user();
											if($lpuser->get_profile_picture()) {?>
											<div class="lp-prof">
												<?php echo $lpuser->get_profile_picture(); ?>
											</div>
											<?php } ?>
									<?php } ?>
								</a>
							<!-- after login dropdown menu code start here -->
								<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
									<a class="dropdown-item" href="<?php echo home_url('/profile'); ?>"><?php _e("Profile", "wp-bootstrap-starter");?> <img src="<?php echo get_template_directory_uri(); ?>/assets/pro.svg" class="mar_right drop_imge_wid"></a>
									<a class="dropdown-item" href="<?php echo home_url('/profile')."/".wp_get_current_user()->user_login."/wishlist"; ?>"><?php _e("Wishlist", "wp-bootstrap-starter");?> <img src="<?php echo get_template_directory_uri(); ?>/assets/heart-1.svg" class="mar_right"></a>
									<?php 
										if(is_site_admin() || is_teacher()) { ?>
									<a class="dropdown-item" href= "<?php echo home_url('/').'wp-admin'?>"><?php _e("Account Settings", "wp-bootstrap-starter");?> <img src="<?php echo get_template_directory_uri(); ?>/assets/settings.svg" class="mar_right"></a>
										<?php } ?>
									<a class="dropdown-item topbor" href="<?php echo wp_logout_url( home_url()); ?>"><?php _e("Sign Out", "wp-bootstrap-starter");?> <img src="<?php echo get_template_directory_uri(); ?>/assets/log-out.svg" class="mar_right"></a>
								</div>
                            </li>
                        </ul>
                    <?php } 
                    else { ?>
                    <form class="form-inline my-2 my-lg-0">
                        <a class="btn my-2 my-sm-0 form-control log_mar btn-back-color color_white" type="submit" data-toggle="modal" data-target="#loginModal"><?php echo $access; ?></a>
                    </form>
                    <?php } ?>
                </div>
            </nav>
           <!-- after login menu code end here -->
	</div>
	<!-- </header> -->
    <!-- Login modal start here -->
        <!-- Modal -->
		<?php
			query_posts(array('post_type'=>'popups','posts_per_page'=>1, 'orderby' => 'ID', 'order' => 'ASC' ));
				while ( have_posts() ) : 
					the_post();
					$post_id = $post->ID;
					$register = get_field("register_popup", $post_id);
					$login = get_field("login_popup", $post_id);
					$success = get_field("succesful_popup", $post_id);
					$failed = get_field("failed_popup", $post_id);
		?>
        <div>
            <div class="modal" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <?php echo $login; ?>
				</div>
			</div>
		</div>
<!-- Login modal end here  -->

<!-- Signup modal start here -->
        <!-- Modal -->
        <div>
            <div class="modal" id="signupModal" tabindex="-1" role="dialog" aria-labelledby="signupModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <?php echo $register; ?>  
				</div>
          </div>
          <div>
            <div class="modal" id="succesfullsignupModal" tabindex="-1" role="dialog" aria-labelledby="succesfullsignupModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <?php echo $success; ?> 
                  </div>
          </div>
          </div>
<!-- Signup modal end here  -->

<!-- Forgot passwprd modal start here -->

<!-- Modal -->
<div>
    <div class="modal" id="forgotModal" tabindex="-1" role="dialog" aria-labelledby="forgotModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header border-bottom-0">
                <!-- <h4>Login</h4> -->
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-title text-center">
                    <div>
                        <?php echo $failed; ?> 
                    </div>
                 
                </div>
                <div class="d-flex flex-column text-center">
                  <form class="form-login-modal" name="lostpasswordform" id="lostpasswordform" method="post">
                  <p id="forgoterror" class="error form-group text-center"></p>
                  <p id="forgotinfo" class="info form-group text-center"></p>
                    <div class="form-group display_flx">
                        <span class="input-group-text field-icon"><i class="fa fa-envelope icons_color width_icon" aria-hidden="true"></i></span>
                       <input type="text" class="form-control mail-address" name="forgotlogin" id="forgotlogin" required placeholder="<?php echo $email; ?>">
                       </div>
                       <input type="submit" name="wp-submit" value="submit" id="wp-submit" class="btn login-form btn-block btn-round" value="<?php echo $pwd_reset; ?>" tabindex="100"> 
                   <span>or <a href="" data-toggle="modal" data-target="#loginModal" data-dismiss="modal"><?php echo $access; ?></a></span>
                  </form>
              </div>
            </div>
          </div>
  </div>
  </div>
<?PHP endwhile; ?>
<!-- Forgot passwprd modal End here -->
         
<!-- class="site-content" -->
	<div id="content">
		<div class="container-fluid">
			<div class="row">
                <?php //endif; ?>
