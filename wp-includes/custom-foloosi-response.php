<?php
require '../wp-load.php';

if($_POST) {

    $user_email = $_POST['user_email'];
    $user_id = $_POST['user_id'];
    $user_name = $_POST['user_name'];
    $payment_status = $_POST['payment_status'];
    $transact_no = $_POST['transact_no'];
    $total_value = $_POST['total_value'];
    $course_id = $_POST['course_id'];
    // $order_id = rand(100,10000)."".$course_id."".$user_id;
    $_course   = learn_press_get_course( $course_id );
    $course_name = $_course->get_title();
    $post_title = "Order on ".date('l dS F Y H:i:s A');
    $post_name = strtolower("order-on-".date('l-dS-F-Y-His-A'));
    $order_key = strtoupper("ORDER".md5(uniqid(rand(), true)));

    global $wpdb;
    $wpdb->insert("wp_posts", array(
        "post_author" => $user_id,
        "post_date" => date('Y-m-d H:i:s'),
        "post_date_gmt" => date('Y-m-d H:i:s'),
        "post_title" => $post_title,
        "post_status" => ($payment_status == 'success' ? 'lp-completed' : 'lp-pending'),
        "comment_status" => 'closed',
        "ping_status" => 'closed',
        "post_name" => $post_name,
        "post_modified" => date('Y-m-d H:i:s'),
        "post_modified_gmt" => date('Y-m-d H:i:s'),
        "post_type" => 'lp_order'
    ));

    $post_id = $wpdb->insert_id;
    $order_id = $wpdb->insert_id;

    $wpdb->insert("wp_postmeta", array(
        "post_id" => $post_id,
        "meta_key" => '_order_currency',
        "meta_value" => 'AED'
    ));
    $wpdb->insert("wp_postmeta", array(
        "post_id" => $post_id,
        "meta_key" => '_user_id',
        "meta_value" => $user_id
    ));
    $wpdb->insert("wp_postmeta", array(
        "post_id" => $post_id,
        "meta_key" => '_order_subtotal',
        "meta_value" => $total_value
    ));
    $wpdb->insert("wp_postmeta", array(
        "post_id" => $post_id,
        "meta_key" => '_order_total',
        "meta_value" => $total_value
    ));
    $wpdb->insert("wp_postmeta", array(
        "post_id" => $post_id,
        "meta_key" => '_order_key',
        "meta_value" => $order_key
    ));
    $wpdb->insert("wp_postmeta", array(
        "post_id" => $post_id,
        "meta_key" => '_payment_method',
        "meta_value" => 'Foloosi Debit/Credit Card'
    ));
    $wpdb->insert("wp_postmeta", array(
        "post_id" => $post_id,
        "meta_key" => '_payment_method_title',
        "meta_value" => 'Foloosi Debit/Credit Card'
    ));
    $wpdb->insert("wp_postmeta", array(
        "post_id" => $post_id,
        "meta_key" => '_user_ip_address',
        "meta_value" => '::1'
    ));
    $wpdb->insert("wp_postmeta", array(
        "post_id" => $post_id,
        "meta_key" => '_created_via',
        "meta_value" => 'checkout'
    ));
    $wpdb->insert("wp_postmeta", array(
        "post_id" => $post_id,
        "meta_key" => '_wp_desired_post_slug',
        "meta_value" => $post_name
    ));


    $wpdb->insert("wp_foloosi_custom_payments", array(
        "order_id" => $order_id,
        "course_id" => $course_id,
        "user_id" => $user_id,
        "user_email" => $user_email,
        "user_name" => $user_name,
        "value" => $total_value,
        "currency" => "AED",
        "transact_no" => $transact_no ,
        "payment_status" => $payment_status
    ));

    $wpdb->insert("wp_learnpress_order_items", array(
        "order_item_name" => $course_name,
        "order_id" => $order_id,
    ));

    $learnpress_order_item_id = $wpdb->insert_id;

    $wpdb->insert("wp_learnpress_order_itemmeta", array(
        "learnpress_order_item_id" => $learnpress_order_item_id,
        "meta_key" => '_course_id',
        "meta_value" => $course_id
    ));
    $wpdb->insert("wp_learnpress_order_itemmeta", array(
        "learnpress_order_item_id" => $learnpress_order_item_id,
        "meta_key" => '_quantity',
        "meta_value" => '1'
    ));
    $wpdb->insert("wp_learnpress_order_itemmeta", array(
        "learnpress_order_item_id" => $learnpress_order_item_id,
        "meta_key" => '_subtotal',
        "meta_value" => $total_value
    ));
    $wpdb->insert("wp_learnpress_order_itemmeta", array(
        "learnpress_order_item_id" => $learnpress_order_item_id,
        "meta_key" => '_total',
        "meta_value" => $total_value
    ));

    $wpdb->insert("wp_learnpress_user_items", array(
        "user_id" => $user_id,
        "item_id" => $course_id,
        "item_type" => 'lp_course',
        "status" => ($payment_status == 'success' ? 'enrolled' : 'pending'),
        "ref_id" => $order_id,
        "ref_type" => 'lp_order'
    ));

    $learnpress_user_item_id = $wpdb->insert_id;

    $wpdb->insert("wp_learnpress_user_itemmeta", array(
        "learnpress_user_item_id" => $learnpress_user_item_id,
        "meta_key" => '_last_status',
        "meta_value" => ($payment_status == 'success' ? 'purchased' : '')
    ));
    $wpdb->insert("wp_learnpress_user_itemmeta", array(
        "learnpress_user_item_id" => $learnpress_user_item_id,
        "meta_key" => '_current_status',
        "meta_value" => ($payment_status == 'success' ? 'enrolled' : 'pending')
    ));
    $wpdb->insert("wp_learnpress_user_itemmeta", array(
        "learnpress_user_item_id" => $learnpress_user_item_id,
        "meta_key" => 'course_results_evaluate_lesson',
        "meta_value" => ''
    ));
    $wpdb->insert("wp_learnpress_user_itemmeta", array(
        "learnpress_user_item_id" => $learnpress_user_item_id,
        "meta_key" => 'grade',
        "meta_value" => 'in-progress'
    ));

    $user_enroll_body = '<table border="0" cellpadding="0" cellspacing="0" width="600" style="background:#f2fbff">
    <thead>
       <tr>
          <td align="center" valign="top">
             <h2 style="background:#00adff;padding:20px;color:#fff;margin:0 0 20px 0;font-weight:lighter;font-size:24px;border-radius:3px">
                You have enrolled course                            
             </h2>
          </td>
       </tr>
    </thead>
    <tbody >
       <tr>
          <td align="center" valign="top" style="padding:0 20px 20px 20px">
             <p style="margin:0 0 20px 0">Congrats! You have enrolled course '.$course_name.'</p>
          </td>
       </tr>
    </tbody>
    <tfoot>
       <tr>
          <td style="text-align:center;padding:20px;border-top:1px solid #ddd">
             Team Kofaa						
          </td>
       </tr>
    </tfoot>
 </table>';

    $user_enroll_to = $user_email; //sendto@example.com
    $user_enroll_subject = '[Kofaa] You have enrolled in course';
    $user_enroll_headers = array('Content-Type: text/html; charset=UTF-8');

    wp_mail( $user_enroll_to, $user_enroll_subject, $user_enroll_body, $user_enroll_headers );


    $admin_enroll_body = '<table border="0" cellpadding="0" cellspacing="0" width="600" style="background:#f2fbff">
    <thead>
       <tr>
          <td align="center" valign="top">
             <h2 style="background:#00adff;padding:20px;color:#fff;margin:0 0 20px 0;font-weight:lighter;font-size:24px;border-radius:3px">
                User has enrolled course                            
             </h2>
          </td>
       </tr>
    </thead>
    <tbody >
       <tr>
          <td align="center" valign="top" style="padding:0 20px 20px 20px">
             <p style="margin:0 0 20px 0">User '.$user_name.'('.$user_email.') has enrolled course '.$course_name.'</p>
          </td>
       </tr>
    </tbody>
    <tfoot>
       <tr>
          <td style="text-align:center;padding:20px;border-top:1px solid #ddd">
             Team Kofaa						
          </td>
       </tr>
    </tfoot>
 </table>';

    $siteadmin_email = get_option('admin_email');
    $admin_enroll_to = $siteadmin_email; //sendto@example.com
    $admin_enroll_subject = $user_name.' has enrolled course';
    $admin_enroll_headers = array('Content-Type: text/html; charset=UTF-8');

    wp_mail( $admin_enroll_to, $admin_enroll_subject, $admin_enroll_body, $admin_enroll_headers );





    $user_pay_body = '<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
    <tbody>
       <tr>
          <td align="center" valign="top">
             <font color="#888888">
             </font><font color="#888888">
             </font>
             <table border="0" cellpadding="0" cellspacing="0" width="600" style="background:#f2fbff">
                <thead>
                   <tr>
                      <td align="center" valign="top">
                         <h2 style="background:#00adff;padding:20px;color:#fff;margin:0 0 20px 0;font-weight:lighter;font-size:24px;border-radius:3px">
                         Thank you for your order                           
                         </h2>
                      </td>
                   </tr>
                </thead>
                <tbody>
                   <tr>
                      <td align="center" valign="top" style="padding:0 20px 20px 20px">
                         <p style="margin:0 0 20px 0">Hi <strong>'.$user_name.'</strong>,</p>
                         <p style="margin:0 0 20px 0">Your recent order at Kofaa has been completed.</p>
                         <p style="margin:0 0 20px 0">
                         </p>
                         <p style="margin:0 0 20px 0">See your order details below:</p>
                         <h3 style="background:#dedede;color:#656565;text-align:center;padding:10px;text-transform:uppercase;font-weight:normal;border-radius:3px">
                            Order summary
                         </h3>
                         <table style="width:100%;margin-bottom:20px">
                            <tbody>
                               <tr>
                                  <th style="text-align:left;font-weight:normal;padding:5px 0">Order Number</th>
                                  <td style="text-align:right;padding:5px 0">#'.$order_id.'</td>
                               </tr>
                               <tr>
                                  <th style="text-align:left;font-weight:normal;padding:5px 0">Purchase Date</th>
                                  <td style="text-align:right;padding:5px 0">'.date("Y-m-d H:i:s").'</td>
                               </tr>
                               <tr>
                                  <th style="text-align:left;font-weight:normal;padding:5px 0">Payment Method</th>
                                  <td style="text-align:right;padding:5px 0">Foloosi Debit/Credit Card</td>
                               </tr>
                               <tr>
                                  <th style="text-align:left;font-weight:normal;padding:5px 0">Status</th>
                                  <td style="text-align:right;padding:5px 0"><span>Completed</span></td>
                               </tr>
                               <tr>
                                  <th style="text-align:left;font-weight:normal;padding:5px 0">User Email</th>
                                  <td style="text-align:right;padding:5px 0"><a href="mailto:karan.singh@oodlestechnologies.com" target="_blank">'.$user_email.'</a></td>
                               </tr>
                            </tbody>
                         </table>
                         <table cellspacing="0" cellpadding="5" style="width:100%;font-family:"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;border:none;font-size:14px">
                            <thead>
                               <tr>
                                  <th style="border-top:1px solid #ddd;text-align:left;vertical-align:middle;border-bottom:1px solid #ddd;padding:8px 0">Course</th>
                                  <th style="border-top:1px solid #ddd;text-align:right;vertical-align:middle;border-bottom:1px solid #ddd;padding:8px 0;width:100px">Quantity</th>
                                  <th style="border-top:1px solid #ddd;text-align:right;vertical-align:middle;border-bottom:1px solid #ddd;padding:8px 0;width:100px">Price</th>
                               </tr>
                            </thead>
                            <tbody>
                               <tr>
                                  <td style="text-align:left;vertical-align:middle;border-bottom:1px solid #ddd;padding:8px 0">
                                     '.$course_name.'           
                                  </td>
                                  <td style="text-align:right;vertical-align:middle;border-bottom:1px solid #ddd;padding:8px 0;width:100px">
                                     1            
                                  </td>
                                  <td style="text-align:right;vertical-align:middle;border-bottom:1px solid #ddd;padding:8px 0;width:100px">
                                     '.$total_value.'  د.إ            
                                  </td>
                               </tr>
                            </tbody>
                            <tfoot>
                               <tr>
                                  <td colspan="2" style="text-align:right;vertical-align:middle;border-bottom:1px solid #ddd;padding:8px 0;width:100px">Total</td>
                                  <td style="text-align:right;vertical-align:middle;border-bottom:1px solid #ddd;padding:8px 0;width:100px">
                                  '.$total_value.'  د.إ 
                                  </td>
                               </tr>
                            </tfoot>
                         </table>
                      </td>
                   </tr>
                </tbody>
                <tfoot id="m_-3431430706859240348m_5601926101683620632email-footer">
                   <tr>
                      <td style="text-align:center;padding:20px;border-top:1px solid #ddd">
                         Team Kofaa						
                      </td>
                   </tr>
                </tfoot>
             </table>
             <font color="#888888">
             </font>
          </td>
       </tr>
    </tbody>
 </table>';

    $user_pay_to = $user_email; //sendto@example.com
    $user_pay_subject = '[Kofaa] Your order placed on '.date("Y-m-d");
    $user_pay_headers = array('Content-Type: text/html; charset=UTF-8');

    wp_mail( $user_pay_to, $user_pay_subject, $user_pay_body, $user_pay_headers );


    $admin_pay_body = '<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
    <tbody>
       <tr>
          <td align="center" valign="top">
             <font color="#888888">
             </font><font color="#888888">
             </font>
             <table border="0" cellpadding="0" cellspacing="0" width="600" style="background:#f2fbff">
                <thead>
                   <tr>
                      <td align="center" valign="top">
                         <h2 style="background:#00adff;padding:20px;color:#fff;margin:0 0 20px 0;font-weight:lighter;font-size:24px;border-radius:3px">
                         User order has been completed                           
                         </h2>
                      </td>
                   </tr>
                </thead>
                <tbody>
                   <tr>
                      <td align="center" valign="top" style="padding:0 20px 20px 20px">
                         <p style="margin:0 0 20px 0">Order placed by <strong>'.$user_name.'</strong> has been completed.</p>
                         <p style="margin:0 0 20px 0">
                         </p>
                         <h3 style="background:#dedede;color:#656565;text-align:center;padding:10px;text-transform:uppercase;font-weight:normal;border-radius:3px">
                            Order summary
                         </h3>
                         <table style="width:100%;margin-bottom:20px">
                            <tbody>
                               <tr>
                                  <th style="text-align:left;font-weight:normal;padding:5px 0">Order Number</th>
                                  <td style="text-align:right;padding:5px 0">#'.$order_id.'</td>
                               </tr>
                               <tr>
                                  <th style="text-align:left;font-weight:normal;padding:5px 0">Purchase Date</th>
                                  <td style="text-align:right;padding:5px 0">'.date("Y-m-d H:i:s").'</td>
                               </tr>
                               <tr>
                                  <th style="text-align:left;font-weight:normal;padding:5px 0">Payment Method</th>
                                  <td style="text-align:right;padding:5px 0">Foloosi Debit/Credit Card</td>
                               </tr>
                               <tr>
                                  <th style="text-align:left;font-weight:normal;padding:5px 0">Status</th>
                                  <td style="text-align:right;padding:5px 0"><span>Completed</span></td>
                               </tr>
                               <tr>
                                  <th style="text-align:left;font-weight:normal;padding:5px 0">User Email</th>
                                  <td style="text-align:right;padding:5px 0"><a href="mailto:karan.singh@oodlestechnologies.com" target="_blank">'.$user_email.'</a></td>
                               </tr>
                            </tbody>
                         </table>
                         <table cellspacing="0" cellpadding="5" style="width:100%;font-family:"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;border:none;font-size:14px">
                            <thead>
                               <tr>
                                  <th style="border-top:1px solid #ddd;text-align:left;vertical-align:middle;border-bottom:1px solid #ddd;padding:8px 0">Course</th>
                                  <th style="border-top:1px solid #ddd;text-align:right;vertical-align:middle;border-bottom:1px solid #ddd;padding:8px 0;width:100px">Quantity</th>
                                  <th style="border-top:1px solid #ddd;text-align:right;vertical-align:middle;border-bottom:1px solid #ddd;padding:8px 0;width:100px">Price</th>
                               </tr>
                            </thead>
                            <tbody>
                               <tr>
                                  <td style="text-align:left;vertical-align:middle;border-bottom:1px solid #ddd;padding:8px 0">
                                     '.$course_name.'           
                                  </td>
                                  <td style="text-align:right;vertical-align:middle;border-bottom:1px solid #ddd;padding:8px 0;width:100px">
                                     1            
                                  </td>
                                  <td style="text-align:right;vertical-align:middle;border-bottom:1px solid #ddd;padding:8px 0;width:100px">
                                     '.$total_value.'  د.إ            
                                  </td>
                               </tr>
                            </tbody>
                            <tfoot>
                               <tr>
                                  <td colspan="2" style="text-align:right;vertical-align:middle;border-bottom:1px solid #ddd;padding:8px 0;width:100px">Total</td>
                                  <td style="text-align:right;vertical-align:middle;border-bottom:1px solid #ddd;padding:8px 0;width:100px">
                                  '.$total_value.'  د.إ 
                                  </td>
                               </tr>
                            </tfoot>
                         </table>
                      </td>
                   </tr>
                </tbody>
                <tfoot id="m_-3431430706859240348m_5601926101683620632email-footer">
                   <tr>
                      <td style="text-align:center;padding:20px;border-top:1px solid #ddd">
                         Team Kofaa						
                      </td>
                   </tr>
                </tfoot>
             </table>
             <font color="#888888">
             </font>
          </td>
       </tr>
    </tbody>
 </table>';

    $siteadmin_email = get_option('admin_email');
    $admin_pay_to = $siteadmin_email; //sendto@example.com
    $admin_pay_subject = 'Order placed on '.date("Y-m-d").' has been completed';
    $admin_pay_headers = array('Content-Type: text/html; charset=UTF-8');

    wp_mail( $admin_pay_to, $admin_pay_subject, $admin_pay_body, $admin_pay_headers );


    echo "true";
}
?>