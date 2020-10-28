
<!-- Custom cards start here -->

<section class="martop50px">
  <div class="container-fluid">
    <div class="row">
      <?php
        $args    = array(
      'posts_per_page' => 4,
      'post_type'      => 'lp_course',
      'post_status'    => 'publish',
      'meta_key' => '_lp_students',
      'orderby' =>'date'
      // 'order' => 'DESC'
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
      $duration = get_post_meta($postid,"_lp_duration");
      $count_items = get_post_meta($postid,"count_items");
      $terms = get_the_terms( $postid, 'course_category' );
      foreach($terms as $term){
        $course_category = $term->name;
      }
      if($course_category!="Upcoming Course") {
      ?>
    <div class="col-md-3 col-sm-6">
      <div class="card-section our-course-padding">
        <div class="card course-card">
          <a href="<?php the_permalink(); ?>" style="color:black;">
          <div class="img-cat pos-rel">
            <div class="profile">
              <p class="font18px"><?php echo $course_category; ?></p>
             </div>
           <img class="card-img-top img-height" src="<?php echo get_the_post_thumbnail_url($postid); ?>">
           </div>
           </a>
           <div class="card-body card-body-height custm_height">
             <a href="<?php the_permalink(); ?>" style="color:black;">
              <p class="card-text font-weight5 gray-colr font16px"><?php echo $course_name; ?></p>
              <div class="marbtm10 dis_none">
                    <span class="profile-name gray-color font14px gray-colr"><?php echo $students[0];?> Student</span>
                  </div>  
              <div class="user-pic user_flex marbotm4 font18px">
                  <img class="card-img-top profile-page"src="<?php echo get_template_directory_uri(); ?>/assets/user.svg">
                  <span class="mar_right gray-color gray-colr font16px"><?php echo $course_author; ?></span>
                </div>
              </a>
              
              <div class="dis_none">
              <div>
              <span class="card_hrs gray-colr"> <?php echo $count_items[0]; ?>lectures</span>
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
                  <div class="font16px gray-colr us_d "><?php if($course_price) echo $course_price.' د.إ'; else echo 'مجانا'; ?></div>
                  <div class="rat_g">
                    <span class="profile-name gray-color font14px gray-colr"><?php echo $students[0];?> Student</span>
                  </div>
                </div>

               
              <div class="user-pic dis_none  marbotm4 font18px">
                  <a class="add_to_cart_button flot_none home_cart_btn" href="<?php the_permalink(); ?>">عرض الدورة</a>
                  <div class="rat_g">
                   <div class="font16px gray-colr us_d "><?php if($course_price) echo $course_price.' د.إ'; else echo 'مجانا'; ?></div>
                  </div>
                </div>
           </div>
        </div>
     </div>
</div>
    <?php } 
    endwhile;
    } ?>

    </div>
  </div>
</section>

<!-- Custom cards end here -->

