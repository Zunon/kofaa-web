<?php 
/**
 * Template Name: Profile Page
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
$current_user = wp_get_current_user();
$user_id = $current_user->ID;
$user_profile_url = get_avatar_url($user_id);
$current_user_meta = get_user_meta($user_id);

// print_r($current_user_meta);
?>

<section id="page">
        <div class="container-fluid">
           <div class="row">
             <div class="col-md-4">
                 <div class="side-profile">
                    <div class="pro marbtm10 display_flx">
                        <?php //if($user_profile_url) { ?>
                            <!-- <div><img src="<?php echo $user_profile_url; ?>"></div> -->
                        <?php //} 
                       // else{?>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/user1.png" class="usre-pro">
                        <?php //} ?>
                    <div class="name-sec">
                        <p class="font26px"><?php echo $current_user->display_name; ?></p>
                        <!-- <i class="fa fa-user-o" aria-hidden="true"></i><span class="font18px gray-colr">5 Following</span> -->
                    </div>
                    </div>
                    <div class="my-interest">
                        <!-- <p class="gray-colr">About me</p> -->
                        <div class="interest-cor">
                        <?php echo $current_user_meta['description'][0]; ?>
                         <!-- <span class="course_topic ">painting</span>
                         <span>Art</span>
                         <span>Designing</span>
                         <span>Programing</span> -->
                        </div>
                        <!-- <p class="green_color">Edit Interset</p> -->
                    </div>
                    <div class="my-orders">
                        <p class="display_inline font20px">My Orders </p>
                        <!-- <p class="display_inline order-count">22<p> -->
                        <?php
                        $user_orders = get_user_meta($user_id, 'orders');
                        // var_dump( $user_orders[0]);
                        if($user_orders) {
                        foreach($user_orders as $order){
                            foreach($order as $key => $value){
                            $ordered_course_id = $key;
                            $course_post = get_post($ordered_course_id);
                        ?>
                        <div class="order-list display_flx">
                            <div>
                                <img src="<?php echo get_the_post_thumbnail_url($ordered_course_id, array(62,43)); ?>">
                            </div>
                            <div class="width100 font16px">
                               <p class="display_inline"><?php echo $course_post->post_title; ?></p>
                               <p class="display_inline flot-right">11jul</p>
                            </div>
                        </div>
                        <?php } } }
                        else{ ?>
                            <div class="order-list display_flx">
                                <p class="display_inline text-center">No orders yet.</p>
                            </div>
                            <div class="order-list display_flx text-center">
                                <a class="display_inline" href="<?php echo get_permalink( get_page_by_path( 'shop' ) ); ?>">Shop Now</a>
                            </div>
                        <?php }
                        ?>
                        <!-- <p class=" text-center"><a href="" class="green_color">View More</a></p> -->
                    </div>
                 </div>
             </div>
             <div class="col-md-8">
                 <div class="">
                     <p class="font20px">Currently learning</p>
                     <?php
                        global $wpdb;
                        $table_name = $wpdb->prefix . "learnpress_user_items";
                        $enrolled_courses = $wpdb->get_results( "SELECT * FROM $table_name WHERE user_id = $user_id" );      
                        if($enrolled_courses) {  
                        foreach($enrolled_courses as $course)       
                        {   
                            $course_post = get_post($course->item_id);
                            // var_dump($course_post)
                            $author_id=$course_post->post_author;
                            $course_author = esc_html( get_the_author_meta( 'display_name', $author_id ) );
                            $user_profile_url = get_avatar_url($author_id);
                        ?>
                     <div class="display_flx learning">
                         <div class="currently-img">
                             <img src="<?php echo get_the_post_thumbnail_url($course_post->ID, array(12,4)); ?>">
                         </div>
                         <div class="width100">
                             <p class="display_inline font20px"><?php echo $course_post->post_title; ?></p>
                             <p class="display_inline flot-right font16px">Viewed: 20-10-2020</p>
                             <div class="user1">
                                 <img src="<?php echo $user_profile_url; ?>"><span><?php echo $course_author; ?></span>
                             </div>
                             <div class="pogress-div display_flx ">
                                 <div class="hrs"><p>4h</p></div>
                                <div class="progress width100">
                                    <div class="progress-bar" style="width:80%">70%</div>
                                  </div>
                             </div>

                            <?php } }
                            else{ ?>
                                 <div class="pogress-div display_flx ">
                                    <p class="display_inline">Not Enrolled to any course.</p>
                                 </div>
                                 <div class="pogress-div display_flx ">
                                    <a href="<?php echo get_permalink( get_page_by_path( 'shop' ) ); ?>" class="display_inline">Go to courses.</a>
                                 </div>
                            <?php }?>

                         </div>
                     </div>
                     <div>
                         <!-- <p class="text-right"><a href="" class="green_color">View More</a></p> -->
                     </div>

                     <!-- <div>
                         <p class="font20px display_inline">My Certificates </p>
                         <p class="display_inline order-count">5</p>
                         <div class="my-certi">
                             <div class="global marbtm10">
                                 <img src="assets/profile/free-certificate.png"><span>Global Certificate in Data science</span><span class="flot-right gray-colr">Jun 2020</span>
                             </div>
                             <div>Launch a career in AI or Data Science by upskilling in Data Science...</div>
                         </div>
                         <div class="my-certi">
                            <div class="global marbtm10">
                                <img src="assets/profile/free-certificate.png"><span>Global Certificate in Data science</span><span class="flot-right gray-colr">Jun 2020</span>
                            </div>
                            <div>Launch a career in AI or Data Science by upskilling in Data Science...</div>
                        </div>
                     </div> -->
                 </div>
             </div>
           </div>
        </div>
</section>

<?php get_footer();?>