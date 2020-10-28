<?php 
require('../wp-config.php');
require('../wp-load.php');

if(isset($_POST['fname']) && isset($_POST['email'])){
    $body = 'Hi Kofaa Team,
    <br>
    Someone wants to connect with you.
    <br>
    The detail of person is :
    <br>
    Name : '.$_POST['fname'].'
    <br>
    Email : '.$_POST['email'].'
    <br>
    Comment : '.$_POST['comment'].'
    <br>
    <br>
    Thanks';

    $siteadmin_email = get_option('admin_email');
    $to = $siteadmin_email; //sendto@example.com
    $subject = 'Contact Details of '.$_POST['fname'];
    $headers = array('Content-Type: text/html; charset=UTF-8');

    //print_r(wp_mail( $to, $subject, $body, $headers ));
    if(wp_mail( $to, $subject, $body, $headers )) {
        echo "true";
    }
    else{
        echo "false";
    }
}
?>