<?php
/**
 * Template for displaying course meta end within the loop.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/loop/course/meta-end.php.
 *
 * @author  ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();
?>

</div>
<a class="add_to_cart_button home_cart_btn" href="<?php the_permalink(); ?>"><?php if(get_bloginfo("language") == "ar") { 
			_e( 'عرض الدورة', 'learnpress' );
				
			}else {
			_e( 'Course view', 'learnpress' );
			}	 ?></a>