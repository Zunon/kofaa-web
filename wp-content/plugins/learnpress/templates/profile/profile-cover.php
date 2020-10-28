<?php
/**
 * Template for displaying user profile cover image.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/profile/profile-cover.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$profile = LP_Profile::instance();

$user = $profile->get_user();

?>

<div id="learn-press-profile-header" class="lp-profile-header">
    <div class="lp-profile-cover sd">
        <div class="lp-profile-avatar">
			<?php echo $user->get_profile_picture(); ?>
            <span class="profile-name"><?php echo $user->get_display_name(); ?></span>
        </div>
    </div>
	<?php
		if( is_user_logged_in() ) {
		 $user = wp_get_current_user();
		 $roles = $user->roles;
		 if($roles[0] != "lp_teacher") {
	?>
	<div class="request_become_lecture">
	<?php if(get_bloginfo("language") == "ar") { $lang = get_site_url()."/become-a-teacher/"; }else { $lang = get_site_url().'/'.apply_filters( 'wpml_current_language', NULL )."/become-a-teacher/"; } ?>
	<button onclick="location.href = '<?php echo $lang;?>';" id="myButton" class="float-right submit-button lp-button button-recover-order" ><?php if(get_bloginfo("language") == "ar") { echo "طلب أن تصبح مدرسًا"; }else { echo "Request To Become a Teacher"; } ?></button>
	</div>
		<?php }
		if($roles[0] == "lp_teacher") { ?>
			<button onclick="location.href = '<?php echo get_site_url()."/wp-admin/edit.php?post_type=lp_course&lang=".get_bloginfo("language");?>';" id="myButton" class="float-right submit-button lp-button button-recover-order" ><?php if(get_bloginfo("language") == "ar") { echo "أضف دورة"; }else { "Add Course"; } ?></button>
		<?php }
		 }?>
</div>


