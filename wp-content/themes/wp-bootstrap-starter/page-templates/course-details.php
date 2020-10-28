<?php 
/**
 * Template Name: Course Detail Page
 * 
 */
if( empty( get_query_var('course_id') ) ){
  wp_redirect( home_url().'/404' );
}
get_header();
$course_id = (int)get_query_var('course_id')
?>
<!-- content section start here -->

  <!-- banner section start here -->
  <?php 
  // $course_id =284;
  $course_post = get_post($course_id);
  $author = get_post_meta($course_id,"_lp_course_author");
  $course_author = esc_html( get_the_author_meta( 'display_name', $author[0] ) );
  $user_name = esc_html( $user->display_name );
  $students = get_post_meta($course_id,"_lp_students");
  $course_students = $students[0];
  $duration = get_post_meta($course_id,"_lp_duration");
  $course_duration = $duration[0];
  $author_id=$course_post->post_author;
  $user_description = get_user_meta($author_id, 'description', true);
  $user_profile_url = get_avatar_url($author_id);
  $terms = get_the_terms( $course_id, 'course_category' );
  foreach($terms as $term){
    $course_category = $term->name;
  }
  ?>
<section id="page">
  <div class="container-fluid">
     <div class="row">
      <div class="col-sm-6">
          <div class="" >
            <p class="course-title"><?php echo $course_post->post_title; ?></p>
            <p> الفئة <?php echo $course_category; ?></p>
            <div class="course-by">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/user.svg"><span> الطبقة <?php echo $course_author; ?></span>
            </div>
            <p class="description gray-colr">
            <?php echo $course_post->post_content; ?>
            </p>
          </div>
       </div>
        <div class="col-sm-6">
              <div class="course-banner">
                 <img class="banner-image" src="<?php echo get_the_post_thumbnail_url($course_id); ?>" />
              </div>
        </div>
       
     </div>
  </div>
</section>
<!-- banner section end here -->

<!-- 120 Enrollments Done Till Now line start -->

<section>
    <div class="container-fluid">
        <div class="enrollments">
            <p class="font-weight5"><i class="fa fa-user-o" aria-hidden="true"></i><?php echo $course_students; ?>  التسجيلات حتى الآن </p>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="">
                    <p class="font24px marbtm10">Curriculum <span class="timeing"><i class="fa fa-clock-o" aria-hidden="true"></i><?php echo $course_duration; ?></span></p>
                </div>
                <div id="accordion" class="accordion">
                    <div class="card mb-0">
                      <?php
                       global $wpdb;
                       $table_name = $wpdb->prefix . "learnpress_sections";
                       $sections = $wpdb->get_results( "SELECT * FROM $table_name WHERE section_course_id = $course_id" );
                        // var_dump($user);        
                        foreach($sections as $section)       
                        {       
                          // $numberToWord = new Numbers_Words();
                          // echo $numberToWords->toWords(200);
                      ?>
                        <div class="card-header collapsed" data-toggle="collapse" href="#id<?php echo $section->section_id; ?>">
                            <a class="card-title col-sec"><?php echo $section->section_name; ?></a><span class="timeing-day"><i class="fa fa-clock-o day-clock" aria-hidden="true"></i>3h 45m</span>
                        </div>
                        <?php 
                        $table_name = $wpdb->prefix . "learnpress_section_items";
                        $lesson_quizs = $wpdb->get_results( "SELECT * FROM $table_name WHERE section_id = $section->section_id" );
                          // var_dump($lesson_quiz);
                          foreach($lesson_quizs as $lq){
                          $lq_id = $lq->item_id;
                          $lq_type = $lq->item_type;
                          $lq_post = get_post($lq_id);
                          $lq_duration = get_post_meta($lq_id,"_lp_duration");
                        ?>
                        <div id="id<?php echo $section->section_id; ?>" class="card-body collapse" data-parent="#accordion">
                            <div>
                            <p><i class="fa fa-play play-style" aria-hidden="true"></i><span class="font18px blck-color"><?php echo $lq_post->post_title; ?></span>
                                <span class="timeing intro"><i class="fa fa-clock-o day-clock" aria-hidden="true"></i><?php echo $lq_duration[0]; ?></span>
                            </p>
                            </div>
                        </div>
                        <?php } } ?>

                </div>
            </div>
            </div>
            <div class="col-md-6">
                <div class="instrct display_flx">
                    <div class="services_img">
                       <div class="common-home-sprite">
                           <img src="<?php echo $user_profile_url; ?>">
                       </div>
                    </div>
                    <div class="service-content">
                       <div class="service-name">
                          <h4>
                          <?php echo $course_author; ?>
                          </h4>
                       </div>
                       <div class="service_point font18px Font_size_color">
                        <?php echo $user_description; ?>
                       </div>
                       <!-- <div class="display_flx noun_certi">
                           <img src="<?php echo get_template_directory_uri(); ?>/assets/noun_Certificate_2569062.svg">
                           <p class="marbtm0">Lorem ipsum dolor sit amet, consetetur sadipscing elitr lidvh..</p>
                       </div>
                       <div class="display_flx noun_certi">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/noun_Certificate_2569062.svg">
                        <p class="marbtm0">Lorem ipsum dolor sit amet, consetetur sadipscing elitr lidvh..</p>
                       </div> -->
                    </div>
  
                 </div>
            </div>
        </div>
    </div>
</section>

<!-- 120 Enrollments Done Till Now line end-->

<!-- Other courses for you Section start here -->

<section class="martop50px width100">
  <div class="container-fluid">
     <div class="popular-course marbtm25">
        <h3 class="font34px font-weight7 font-weight5">دورات أخرى لك</h3>
        <h5 class="flot-right font24px green-color"><a href="<?php echo get_permalink( get_page_by_path( 'courses' ) ); ?>">عرض الكل</a></h5>
     </div>
     <div class="row">

    <?php
    $args    = array(
      'posts_per_page' => 4,
      'post_type'      => 'lp_course',
      'post_status'    => 'publish',
      'meta_key' => '_lp_students',
      'orderby'   => 'meta_value'
    );    
    $courses = new WP_Query( $args );
    if($courses)
    {
      while ( $courses->have_posts() ) : $courses->the_post(); 
      $course_name = get_the_title(); 
      $postid = get_the_ID();
      $price = get_post_meta($postid,"_lp_price");
      $course_price = $price[0];
      $author = get_post_meta($postid,"_lp_course_author");
      $course_author = esc_html( get_the_author_meta( 'display_name', $author[0] ) );
      $terms = get_the_terms( $postid, 'course_category' );
      foreach($terms as $term){
        $course_category = $term->name;
      }
    
    ?>
     
      <div class="col-md-3 col-sm-6 ">
        <a href="" style="color:black;">
           <div class="card-section our-course-padding">
              <div class="card course-card">
                <div class="img-cat pos-rel">
                  <div class="profile">
                    <p class="font20px"><?php echo $course_category; ?></p>
                   </div>
                 <img class="card-img-top img-height" src="<?php echo get_the_post_thumbnail_url($postid); ?>">
                 
                 </div>
                 <div class="card-body card-body-height">
                    <p class="card-text font16px font-weight5"><?php echo $course_name; ?></p>
                    <div class="user-pic marbotm4">
                    <img class="card-img-top profile-page" src="<?php echo get_template_directory_uri(); ?>/assets/user.svg">
                    <span class="profile-name gray-color font16px"><?php echo $course_author; ?></span>
                    </div>
                    <!-- <div class="rating marbotm4">
                     <span> <img src="<?php echo get_template_directory_uri(); ?>/assets/noun_Star_3350401.svg"><span>5(50,120)</span></span>
                    </div> -->
                    <div class="price marbotm4">
                      <h5 class="display-inline-blk"><?php if($course_price) echo $course_price; else echo 0; ?> د.إ</h5>
                    </div>
                 </div>
              </div>
           </div>
        </a>
     </div>

    <?php endwhile; }?>

     </div>
  </div>
</section>


<!-- Other courses for you Section end here -->

<!-- Newly Added Courses Section start here -->

<section class="martop50px width100">
    <div class="container-fluid">
       <div class="popular-course marbtm25">
          <h3 class="font34px font-weight7 font-weight5">الدورات المضافة حديثا</h3>
          <h5 class="flot-right font24px green-color"><a href="<?php echo get_permalink( get_page_by_path( 'courses' ) ); ?>">عرض الكل</a></h5>
       </div>
       <div class="row">

       <?php
    $args    = array(
      'posts_per_page' => 4,
      'post_type'      => 'lp_course',
      'post_status'    => 'publish'
    );    
    $courses = new WP_Query( $args );
    if($courses)
    {
      while ( $courses->have_posts() ) : $courses->the_post(); 
      $course_name = get_the_title(); 
      $postid = get_the_ID();
      $price = get_post_meta($postid,"_lp_price");
      $course_price = $price[0];
      $author = get_post_meta($postid,"_lp_course_author");
      $course_author = esc_html( get_the_author_meta( 'display_name', $author[0] ) );
      $terms = get_the_terms( $postid, 'course_category' );
      foreach($terms as $term){
        $course_category = $term->name;
      }
    
    ?>
     
      <div class="col-md-3 col-sm-6 ">
        <a href="" style="color:black;">
           <div class="card-section our-course-padding">
              <div class="card course-card">
                <div class="img-cat pos-rel">
                  <div class="profile">
                    <p class="font20px"><?php echo $course_category; ?></p>
                   </div>
                 <img class="card-img-top img-height" src="<?php echo get_the_post_thumbnail_url($postid); ?>">
                 
                 </div>
                 <div class="card-body card-body-height">
                    <p class="card-text font16px font-weight5"><?php echo $course_name; ?></p>
                    <div class="user-pic marbotm4">
                    <img class="card-img-top profile-page" src="<?php echo get_template_directory_uri(); ?>/assets/user.svg">
                    <span class="profile-name gray-color font16px"><?php echo $course_author; ?></span>
                    </div>
                    <!-- <div class="rating marbotm4">
                     <span> <img src="<?php echo get_template_directory_uri(); ?>/assets/noun_Star_3350401.svg"><span>5(50,120)</span></span>
                    </div> -->
                    <div class="price marbotm4">
                      <h5 class="display-inline-blk"><?php if($course_price) echo $course_price; else echo 0; ?> د.إ</h5>
                    </div>
                 </div>
              </div>
           </div>
        </a>
     </div>

    <?php endwhile; }?>
       
       </div>
    </div>
  </section>
<!-- Newly Added Courses Section end here -->


<!-- Footer sectio start here  -->

<?php get_footer(); ?>