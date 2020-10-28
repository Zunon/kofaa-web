<?php
/**
 * Template for displaying quizzes tab in user profile page.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/tabs/quizzes.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$profile       = learn_press_get_profile();
$filter_status = LP_Request::get_string( 'filter-status' );
$query         = $profile->query_quizzes( array( 'status' => $filter_status ) );
?>

<div class="learn-press-subtab-content">
    <h3 class="profile-heading"><?php
if(get_bloginfo("language") == "ar") { 
				_e( 'اختباراتي', 'learnpress' );
			}else {
				_e( 'My Quizzes', 'learnpress' );
			}
			global $current_user;
			$user_login = $current_user->user_login;
	 ?></h3>

	<?php if ( $filters = $profile->get_quizzes_filters( $filter_status ) ) { ?>
        <ul class="lp-sub-menu">
		
			<?php foreach ( $filters as $class => $link ) { ?>
			
               
				<?php if(get_bloginfo("language") != "ar") { ?>
					<li class="<?php echo $class; ?>"><a href='<?php echo get_site_url().'/'.apply_filters( 'wpml_current_language', NULL )."/profile/".$user_login."/quizzes/?filter-status=".$class; ?>'><?php echo $class; ?></a></li>
				<?php } else { ?>
				 <li class="<?php echo $class; ?>"><a href='<?php echo get_site_url()."/profile/".$user_login."/quizzes/?lang=".get_bloginfo("language")."&filter-status=".$class; ?>'><?php echo $class; ?></a></li>
				<?php } ?>
			<?php } ?>
        </ul>
	<?php } ?>

	<?php if ( $query['items'] ) { ?>
        <table class="lp-list-table profile-list-quizzes profile-list-table">
            <thead>
            <tr>
                <th class="column-course"><?php _e( 'Course', 'learnpress' ); ?></th>
                <th class="column-quiz"><?php _e( 'Quiz', 'learnpress' ); ?></th>
                <th class="column-date"><?php _e( 'Date', 'learnpress' ); ?></th>
                <th class="column-status"><?php _e( 'Progress', 'learnpress' ); ?></th>
                <th class="column-time-interval"><?php _e( 'Interval', 'learnpress' ); ?></th>
            </tr>
            </thead>
            <tbody>
			<?php foreach ( $query['items'] as $user_quiz ) { ?>
				<?php $quiz = learn_press_get_quiz( $user_quiz->get_id() );
				$courses    = learn_press_get_item_courses( array( $user_quiz->get_id() ) ); ?>
                <tr>
                    <td class="column-course">
						<?php if ( $courses ) {
							foreach ( $courses as $course ) {
								$course = LP_Course::get_course( $course->ID ); ?>
                                <a href="<?php echo $course->get_permalink(); ?>">
									<?php echo $course->get_title( 'display' ); ?>
                                </a>
							<?php }
						} ?>
                    </td>
                    <td class="column-quiz column-quiz-<?php echo $user_quiz->get_id();?>">
						<?php if ( $courses ) {
							foreach ( $courses as $course ) {
								$course = LP_Course::get_course( $course->ID ); ?>
                                <a href="<?php echo $course->get_item_link( $user_quiz->get_id() ) ?>">
									<?php echo $quiz->get_title( 'display' ); ?>
                                </a>
							<?php }
						} ?>
                    </td>
                    <td class="column-date"><?php
						echo $user_quiz->get_start_time( 'i18n' ); ?></td>
                    <td class="column-status">
                        <span class="result-percent"><?php echo $user_quiz->get_percent_result(); ?></span>
                        <span class="lp-label label-<?php echo esc_attr( $user_quiz->get_results( 'status' ) ); ?>">
                        <?php echo $user_quiz->get_status_label(); ?>
                    </span>
                    </td>
                    <td class="column-time-interval">
						<?php echo( $user_quiz->get_time_interval( 'display' ) ); ?>
                    </td>
                </tr>

			<?php } ?>
            </tbody>
            <tfoot>
            <tr class="list-table-nav">
                <td colspan="3" class="nav-text">
					<?php echo $query->get_offset_text(); ?>
                </td>
                <td colspan="2" class="nav-pages">
					<?php $query->get_nav_numbers( true ); ?>
                </td>
            </tr>
            </tfoot>
        </table>

	<?php } else { ?>
		<?php
if(get_bloginfo("language") == "ar") { 
				learn_press_display_message( __( 'لا اختبارات', 'learnpress' ) );
			}else {
				learn_press_display_message( __( 'No quizzes!', 'learnpress' ) );
			}
		 ?>
	<?php } ?>
</div>
