<?php
/**
 * Template Name: Home Page
 * 
 */
get_header();
?>
<!-- content section start here -->
<div >
<!-- home page banner code start here -->
<?php
	$banner_args = array(
    'post_type'   => 'home_banner',
    'post_status' => 'publish',
    'orderby' =>'date',
	'order' => 'ASC',
	'suppress_filters' => false
    );
    $home_banner = get_posts($banner_args);
?>
		<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
		  <div class="carousel-inner">
			<?php $banner_count=1;
				foreach($home_banner as $banner_slide)
				{
					 $slide_id = $banner_slide->ID ;
					 $show_login_button = get_field('show_login_button', $slide_id); 
					 $show_register_button = get_field('show_register_button', $slide_id); 
					 $register_button_text = get_field('register_button_text', $slide_id); 
					 $login_button_text = get_field('login_button_text', $slide_id); 
					 if($banner_count == 1) {
						  $class = "active";
					  }
					  else
					  {
						  $class = "";
					  }
				  ?>
				  <div class="carousel-item <?php echo $class; ?>">
					<div class="content-overlay"></div>
					<?php 
						
					 if (has_post_thumbnail( $slide_id) ):
						$image = wp_get_attachment_image_src( get_post_thumbnail_id($slide_id), 'single-post-thumbnail' );
						echo '<img src="'.$image[0].'">';
						$banner_count++;
					 endif;
					 ?>
					 <div class="carousel-caption caption-pos d-md-block">
						<?php if($show_register_button == "Yes") { ?>
							<a href="" data-toggle="modal" data-target="#signupModal" ><button type="button" class="menu-font-color color_white login-sign getting-start btn-back-color for-free"><?php echo $register_button_text; ?></button></a>
						<?php } ?>
						<?php if($show_login_button == "Yes") { ?>
							<a href="" data-toggle="modal" data-target="#loginModal"><button type="button" class="menu-font-color login-sign getting-start" tooltip="first login"><?php echo $login_button_text; ?></button></a>
						<?php } ?>
					</div>
				</div>
				<?php
				}
				?>
			</div>
			  <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
				<i class="fa fa-angle-left text-dark prev_ious"></i>
				<span class="sr-only"><?php _e("Previous", "wp-bootstrap-starter");?></span>
			  </a>
			  <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
			  <i class="fa fa-angle-right text-dark ne_xt"></i>
				<span class="sr-only"><?php _e("Next", "wp-bootstrap-starter");?></span>
			  </a>
			 <ol class="carousel-indicators">
				 <?php $slide_num = 0; ?>
				 <?php foreach($home_banner as $banner_slide){ ?>
						<li data-target="#carouselExampleControls" data-slide-to="<?php echo $slide_num; ?>"></li>
						<?php $slide_num++; ?>
				 <?php } ?>
			</ol>
		</div>
		</div>
 
<!-- banner section end here -->

<div id="exploring" ></div>

<!-- Our Popular Courses Section start here -->
<section class="martop50px">
  <div class="container-fluid">
  <!-- section 1 title -->
         <div class="home_section"><?php echo get_field('section_1_title', $_SESSION['current_page_id']); ?> </div>
    
     <div class="row">
		<?php 
		$args    = array(
		  'posts_per_page' => 4,
		  'post_type'      => 'lp_course',
		  'post_status'    => 'publish',
		  'meta_key' => '_lp_students',
		  'orderby' =>'date',
		  'tax_query' => array(
			array(
			 'taxonomy' => 'course_category',
			 'field'    => 'slug',
			 'terms'    => array( 'upcoming-course' ),
			 'operator' => 'NOT IN',
			)
		 ),
		);

		$courses = new WP_Query( $args );
		if($courses)
		{
		  while ( $courses->have_posts() ) : $courses->the_post(); 
		  $course_name = get_the_title();
		  $course_content = get_the_content();
		  $postid = get_the_ID();
		  $price = get_post_meta($postid,"_lp_price");
		  $course_price = $price[0];
		  $author = get_post_meta($postid,"_lp_course_author");
		  $course_author = esc_html( get_the_author_meta( 'display_name', $author[0] ) );
		  $students = get_post_meta($postid,"_lp_students");
		  $duration = get_post_meta($postid,"_lp_duration");
		  $count_items = get_post_meta($postid,"count_items");
		  $terms = get_the_terms( $postid, 'course_category' );
		  foreach($terms as $term){
			$course_category = $term->name;
		  }
		?>
		<div class="col-md-3 col-sm-6">
			<div class="card-section our-course-padding">
				<div class="card course-card">
					<a href="<?php the_permalink(); ?>" style="color:black;">
						<div class="img-cat pos-rel">
							<div class="profile_bckgrnd">
								<p class="font18px"><?php echo $course_category; ?></p>
							</div>
							<img class="card-img-top img-height" src="<?php echo get_the_post_thumbnail_url($postid); ?>">
						</div>
					</a>
					<div class="card-body card-body-height custm_height">
						<a href="<?php the_permalink(); ?>" style="color:black;">
							<p class="card-text font-weight5 gray-colr font16px"><?php echo $course_name; ?></p>
						  <div class="marbtm10 dis_none">
								<span class="profile-name gold_colr font14px gray-colr"><?php if(get_bloginfo("language") == "ar") { echo "طالب علم"; }else { echo "student"; } ?><?php echo $students[0];?></span>
						  </div>  
						  <div class="user-pic user_flex marbotm4 font18px">
							  <img class="card-img-top profile-page"src="<?php echo get_template_directory_uri(); ?>/assets/user.svg">
							  <span class="mar_right gray-color gray-colr font16px"><?php echo $course_author; ?></span>
						  </div>
						</a>
				  
						<div class="dis_none"><div>
						<span class="card_hrs gray-colr"><?php echo $count_items[0]; ?><span class="cls_lev"><?php if(get_bloginfo("language") == "ar") { echo "محاضرات"; }else { echo "Lectures"; } ?></span></span>
						<img src="<?php echo get_template_directory_uri(); ?>/assets/watch.svg" class="hrs_img"/>
						<span class="card_hrs gray-colr"><?php echo $duration[0];?></span><img src="<?php echo get_template_directory_uri(); ?>/assets/play.svg" class="hrs_img"/>
				  </div>
				  
				  <div>
					<ul class="card_ul">
					 <?php echo $course_content; ?>
					<ul>
				  </div>
				  
				</div>
				  
				  <div class="usd_star  marbotm4 font18px">
					  <div class="font16px gray-colr us_d" style="float:left"><?php if($course_price) { echo $course_price.' د.إ'; } else { 
					 if(get_bloginfo("language") == "ar") { 
					  echo 'مجانا'; 
					 }
					 else
					 {
						 echo "Free";
					 }
					  
					  } ?></div>
					  <div class="rat_g" style="float:right">
						<span class="profile-name gold_colr font14px "><?php if(get_bloginfo("language") == "ar") { echo "طالب علم"; }else { echo "
		student"; } ?><?php echo "( ". $students[0]. " )";?></span>
					  </div>
					</div>

				   
				  <div class="user-pic dis_none  marbotm4 font18px">
					  <a class="add_to_cart_button flot_none home_cart_btn" href="<?php the_permalink(); ?>"><?php if(get_bloginfo("language") == "ar") { echo "طالب علم"; } else { echo "Course view"; } ?></a>
					  <div class="rat_g">
					   <div class="font16px gray-colr us_d ">
					   <?php if(get_bloginfo("language") == "ar") {  ?>
								<?php if($course_price) { echo  $course_price.' د.إ'; } else { echo 'مجانا'; }?>
					   <?php } else { ?>
							<?php if($course_price) { echo $course_price.'د.إ'; } else { echo 'Free'; } ?>
					   <?php } ?>
					   </div>
					  </div>
					</div>
			   </div>
			</div>
			</div>
		</div>
 
<?php

endwhile;
} ?>
     </div>
  </div>
</section>


<!-- Our Popular Courses Section end here -->


<!-- Courses By Type section start here -->
<section class="martop50px course-back width100">
  <div class="container-fluid ">
     <div class="home_section"><?php echo get_field('section_2_title', $_SESSION['current_page_id']); ?></div>
    <!-- slider -->
		<div class="container-fluid no-padding">
			<div class="owl-carousel courese-by marbtom40 owl-theme course-type">
			<?php
			$categories = get_categories('taxonomy=course_category&type=lp_course'); 
			foreach($categories as $category)
			{
				$category_link = get_category_link( $category->term_id);
			?>
			<a class="blck-color courses_hv" href="<?php echo $category_link; ?>">
			  <div class="item owl_items">
				<div class="courses-cards">
				  <div class="icon_img_typ"><?php echo  $category->description; ?></div>
				  <p class="marbtm0"><?php echo $category->name; ?></p>
			   </div>
			  </div>
			</a>
			<?php } ?>

			</div>
		</div>
  </div>
</section>


<!-- Courses By Type section end here -->

<!-- Products Section start here -->

<section class="martop50px">
  <div class="container-fluid">
          <div class="home_section"><?php echo get_field('section_3_title', $_SESSION['current_page_id']); ?></div>
        <div class="row">
			<?php
				$args = array(
				'post_type' => 'product',
				'post_status' => 'publish',
				'stock' => 1,
				'posts_per_page' => 4,
				'orderby' =>'date'
				);

				$loop = new WP_Query( $args );
				if($loop)
				{
				while ( $loop->have_posts() ) : $loop->the_post(); 
				global $product; 
				$postid = get_the_ID();
				$terms = get_the_terms( $postid, 'product_cat' );
				$product = wc_get_product( $postid );
				$rating  = $product->get_average_rating();
				$count   = $product->get_rating_count();

			?>
  
			<div class="col-md-3 col-sm-6">
			  <a id="<?php the_id(); ?>" href="<?php the_permalink(); ?>" style="color:black;">
				 <div class="card-section our-course-padding">
					<div class="card course-card">
					  <div class="img-cat pos-rel">
						<div class="profile_bckgrnd">
						  <p class="font20px"><?php echo $terms[0]->name; ?></p>
						 </div>
					   <img class="card-img-top img-height" src="<?php echo get_the_post_thumbnail_url($loop->post->ID); ?>">
					   
					   </div>
					   <div class="card-body card-body-height">
						  <p class="card-text font18px font-weight5"><?php the_title(); ?></p>
						  <div class="rating marbotm4">
						   <span> <?php  echo wc_get_rating_html( $rating, $count ); ?></span>
						  </div>
						  <div class="price marbotm4 home_price">
							<h5 class="display-inline-blk font18px"><?php echo $product->get_price();?>  د.إ</h5>
						  </div>
						 
						  <?php if(get_bloginfo("language") == "ar") { ?>
			 <a class="add_to_cart_button home_cart_btn" href="<?php echo home_url( '/shop/?add-to-cart=' ).''.$postid; ?>">إضافة إلى السلة</a>
						  <?php } else { ?>
						  <a class="add_to_cart_button home_cart_btn" href="<?php echo get_site_url().'/'.apply_filters( 'wpml_current_language', NULL ).'?add-to-cart='.$postid; ?>">Add to cart</a>
						  <?php } ?>
						  </div>
					</div>
				 </div>
			  </a>
			</div>
<?php endwhile; } ?>
        </div>
     </div>
</section>

<!-- Products Section end here -->



<!-- Upcoming Course Section start here -->

<section class="martop50px">
  <div class="container-fluid">
	     <div class="home_section sasa"><?php echo get_field('section_4_title', $_SESSION['current_page_id']); ?></div>
     <div class="div">
     <?php 
		$args    = array(
		  'posts_per_page' => 4,
		  'post_type'      => 'lp_course',
		  'course_category' => 'upcoming-course',
		  'post_status'    => 'publish',
		  'meta_key' => '_lp_students',
		  'orderby' =>'date'
		  );

		$courses = new WP_Query( $args );
		if($courses->have_posts())
		{
		  while ( $courses->have_posts() ) : $courses->the_post(); 
		  $course_name = get_the_title();
		  $course_content = get_the_content();
		  $postid = get_the_ID();
		  $price = get_post_meta($postid,"_lp_price");
		  $course_price = $price[0];
		  $author = get_post_meta($postid,"_lp_course_author");
		  $course_author = esc_html( get_the_author_meta( 'display_name', $author[0] ) );
		  $students = get_post_meta($postid,"_lp_students");
		  $duration = get_post_meta($postid,"_lp_duration");
		  $count_items = get_post_meta($postid,"count_items");
		  $terms = get_the_terms( $postid, 'course_category' );
		  foreach($terms as $term){
			$course_category = $term->name;
		  }

		?>
 <div class="col-md-3 col-sm-6">
      <div class="card-section our-course-padding">
        <div class="card course-card">
          <a href="<?php the_permalink(); ?>" style="color:black;">
          <div class="img-cat pos-rel">
            <div class="profile_bckgrnd">
              <p class="font18px"><?php echo $course_category; ?></p>
             </div>
           <img class="card-img-top img-height" src="<?php echo get_the_post_thumbnail_url($postid); ?>">
           </div>
           </a>
           <div class="card-body card-body-height custm_height">
             <a href="<?php the_permalink(); ?>" style="color:black;">
              <p class="card-text font-weight5 gray-colr font16px"><?php echo $course_name; ?></p>
              <div class="marbtm10 dis_none">
                    <span class="profile-name gold_colr font14px gray-colr">  <?php if(get_bloginfo("language") == "ar") { echo "طالب علم"; }else { echo "student"; } ?><?php echo "student";?><?php echo "( ".$students[0]." )";?></span>
                  </div>  
              <div class="user-pic user_flex marbotm4 font18px">
                  <img class="card-img-top profile-page"src="<?php echo get_template_directory_uri(); ?>/assets/user.svg">
                  <span class="mar_right gray-color gray-colr font16px"><?php echo $course_author; ?></span>
                </div>
              </a>
              
              <div class="dis_none">
              <div>
              <span class="card_hrs gray-colr"><?php echo $count_items[0]; ?> <?php if(get_bloginfo("language") == "ar") { echo "محاضرات"; }else { echo "Lectures"; } ?></span></span>
              <img src="<?php echo get_template_directory_uri(); ?>/assets/watch.svg" class="hrs_img"/>
              <span class="card_hrs gray-colr"><?php echo $duration[0];?></span><img src="<?php echo get_template_directory_uri(); ?>/assets/play.svg" class="hrs_img"/>
              </div>
              
              <div>
                <ul class="card_ul">
                 <?php echo $course_content; ?>
                <ul>
              </div>
              
            </div>
              
              <div class="usd_star  marbotm4 font18px">
                  <div class="font16px gray-colr us_d ">
				  <?php if(get_bloginfo("language") == "ar") {  ?>
						<?php if($course_price) { echo  $course_price.' د.إ'; } else { echo 'مجانا'; }?>
			   <?php } else { ?>
					<?php if($course_price) { echo $course_price.'د.إ'; } else { echo 'Free'; } ?>
			   <?php } ?>
				  </div>
                  <div class="rat_g">
                    <span class="profile-name gold_colr font14px "> <?php if(get_bloginfo("language") == "ar") { echo "طالب علم"; }else { echo "student"; } ?><?php echo $students[0];?></span>
                  </div>
                </div>

               
              <div class="user-pic dis_none  marbotm4 font18px">
                  <a class="add_to_cart_button flot_none home_cart_btn" href="<?php the_permalink(); ?>"><?php if(get_bloginfo("language") == "ar") { echo "عرض الدورة"; }else { echo "Course view"; } ?></a>
                  <div class="rat_g">
                   <div class="font16px gray-colr us_d ">
				   <?php if(get_bloginfo("language") == "ar") {  ?>
						<?php if($course_price) { echo  $course_price.' د.إ'; } else { echo 'مجانا'; }?>
			   <?php } else { ?>
					<?php if($course_price) { echo $course_price.'د.إ'; } else { echo 'Free'; } ?>
			   <?php } ?>
				   </div>
                  </div>
                </div>
           </div>
        </div>
     </div>
</div>
<?php
endwhile;
} 
else {
  echo "<h3>"; ?>
  <?php if(get_bloginfo("language") == "ar") { echo "ليس هناك دورة قادمة حتى الآن"; }else { echo "
There is no upcoming session yet"; } ?>
<?php echo "</h3>";
}
?>
     </div>
  </div>
</section>

<!-- Upcoming course Section end here -->


<!-- Reivew section start here -->
<section class="martop50px course-back width100">
  <div class="">
    <!-- slider -->
    <div class=" no-padding">
    <div class="owl-carousel owl-carousel-review marbtom40 owl-theme course-type">
    <?php
    $users = get_users( [ 'role__in' => [ 'administrator', 'lp_teacher' ] ] );
    // Array of WP_User objects.
    foreach ( $users as $user ) {
        $user_name = esc_html( $user->display_name );
        $user_description = get_user_meta($user->ID, 'description', true);
        $user_profile_url = get_avatar_url($user->ID);
		if(!empty($user_description)) {
     ?>
      <div class="item owl_items">
        <div class="row">
          <div class="col-md-4">
             <div>
                <img class="star-img" src="<?php echo $user_profile_url; ?>">
                <span class="viw_name"><?php echo $user_name; ?></span>
             </div>
          </div>
          <div class="col-md-8">
          <p class="views banner-heading for_instc">
             <i class="fa fa-quote-right right-side green_color" aria-hidden="true"></i>
             <?php echo $user_description; ?><i class="fa fa-quote-left right-side green_color" aria-hidden="true"></i>
          </p>
          </div>
       </div>
      </div>
    <?php } } ?>

    </div>
    </div>
  </div>
</section>
<!-- add footer section -->
<?php get_footer();?>