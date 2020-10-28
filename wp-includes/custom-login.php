<?php
require '../wp-load.php';


if(isset($_POST['log']) && isset($_POST['pwd'])) 
{  
    global $wpdb;  
   
    //We shall SQL escape all inputs  
    $username = $wpdb->escape($_REQUEST['log']);  
    $password = $wpdb->escape($_REQUEST['pwd']);  
    // $remember = $wpdb->escape($_REQUEST['rememberme']);  
   
    // if($remember) $remember = "true";  
    // else $remember = "false";  
   
    $login_data = array();  
    $login_data['user_login'] = $username;  
    $login_data['user_password'] = $password;  
    // $login_data['remember'] = $remember;  
   
    $user_verify = wp_signon( $login_data, false );   
   
    if ( is_wp_error($user_verify) )   
    {  
        // echo "Invalid login details"; 
        echo "false"; 
       // Note, I have created a page called "Error" that is a child of the login page to handle errors. This can be anything, but it seemed a good way to me to handle errors.  
     } else
    {    
    //    echo "<script type='text/javascript'>window.location.href='". home_url() ."'</script>";
        echo "true";
       exit();  
     }  
}

if(isset($_POST['forgotlogin'])) {
    global $wpdb;

    $username_email = $wpdb->escape($_REQUEST['forgotlogin']);  

    if (strpos($username_email, '@') !== false) {
        $emailquery = "SELECT `user_email`, `user_nicename` FROM `wp_users` WHERE user_email = '".$username_email."' AND user_status = 0";
        
        $email = $wpdb->get_row($emailquery);
    }
    else{
        $usernamequery = "SELECT `user_email`, `user_nicename` FROM `wp_users` WHERE user_login = '".$username_email."' AND user_status = 0";
        
        $email = $wpdb->get_row($usernamequery);
    }

    if($email!="" && $email!= NULL ) {
		$lang = $_GET["lang"];
		$site_url = $_GET["siteurl"];
        $key = strtotime('now');
        $to = $email->user_email; //sendto@example.com
        $subject = 'Kofaa - Password Reset';
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $body = 
        "Someone requested that the password be reset for the following account:
        <br><br>
        ".home_url('/')."
        <br><br>
        Username: ".$email->user_nicename."
        <br><br>
        If this was a mistake, just ignore this email and nothing will happen.
        <br><br>
        To reset your password, visit the following address:
        <br><br>
        ".$site_url."wp-includes/custom-login.php?action=rp&key=".$key."&login=".$email->user_email."&lang=".$lang."
        <br><br>
        This password reset link activated for 15 minutes after that you have to request new one.";

        //print_r(wp_mail( $to, $subject, $body, $headers ));
        if(wp_mail( $to, $subject, $body, $headers )) {
            echo "true";
        }
    }
    else{
        echo "false";
    }
}

if($_REQUEST['action'] == 'rp') {
    wp_head();?>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/kofaa.css">
    <script src="<?php echo get_template_directory_uri(); ?>/assets/js/kofaa.js"></script>
    <section id="page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 auto-mar">
                    <div class="modal-body">
                        <div class="form-title text-center">
                            <div>
                                <img src="<?php echo esc_url(get_theme_mod( 'wp_bootstrap_starter_logo' ));  ?>">
                                <h4 class="font50px font-electrolize">
							<?php if($_GET['lang'] == "ar") { echo "أهلاً بعودتك"; } else { echo "welcome back"; } ?> 
								</h4>
                                <h6 class="font-chiller font24px"><?php if($_GET['lang'] == "ar") { echo "ترجمة باللغة العربية"; } else { echo "Arabic translation"; } ?> </h6>
                                <p class="font-bukra-regular marbtm50"><?php if($_GET['lang'] == "ar") { echo "نساعدكم في تطوير قدراتكم والإرتقاء في وظائفكم كل يوم"; } else { echo "We help you to develop your capabilities and advance in your jobs every day"; } ?>  </p>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-center">
                    <?php   $diff = strtotime('now')-$_REQUEST['key']; 
                            if ($diff <= 900) { ?>
                            <form class="form-login-modal" id="resetpwdform" name="resetpwdform">
                                <div class="form-group display_flx">
                                    <span class="input-group-text field-icon"><i class="fa fa-lock icons_color lock_icon width_icon" aria-hidden="true"></i></span>
                                <input type="password" class="form-control mail-address" id="resetpassword" name="resetpassword" required placeholder="<?php if($_GET['lang'] == "ar") { echo "كلمة سر جديدة"; } else { echo "New password"; } ?>">
                                <input type="hidden" id="resetemail" name="resetemail" required value="<?php echo $_REQUEST['login'];?>">
                                <input type="hidden" id="lang" name="lang" required value="<?php echo $_GET['lang'];?>">
                                </div>
                                <button type="submit" class="btn login-form btn-block btn-round"><?php if($_GET['lang'] == "ar") { echo "إعادة تعيين كلمة المرور"; } else { echo "Password Reset"; } ?> </button>
                            </form>
                            <em><p style="color:gray; margin:10px;"><?php if($_GET['lang'] == "ar") { echo 'تلميح: يجب أن تتكون كلمة المرور من اثني عشر حرفًا على الأقل. لجعل كلمة المرور أقوى ، استخدم الأحرف الكبيرة والصغيرة والرقم والرموز مثل! "؟ $٪ ^ &).'; } else { echo 'The password should at least twelve character long. To make password stronger, use upper & lower case letters, number & symbols like !"?$%^&)'; } ?> </p></em>
                            <!-- Hint: The password should at least twelve character long. To make password stronger, use upper & lower case letters, number & symbols like !"?$%^&). -->
        
                    <?php } 
                        else { ?>
                            <!--Password reset link expired. Please request for new one. -->
                            <p><?php if($_GET['lang'] == "ar") { echo "إعادة تعيين كلمة المرور. يرجى طلب واحد جديد"; } else { echo "Password Reset. Please order a new one"; } ?></p>
                        <?php } ?>
                        </div>
                        <div class="d-flex flex-column text-center">
                            <a href="<?php echo home_url('/')?>"><?php if($_GET['lang'] == "ar") { echo "Kofaa ارجع إلى"; } else { echo "Return to Kofaa"; } ?> </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php }

if(isset($_REQUEST['resetpassword']) && isset($_REQUEST['resetemail'])) {
    global $wpdb;

    $email = $wpdb->escape($_REQUEST['resetemail']);
    $password = $wpdb->escape($_REQUEST['resetpassword']);

    $updatepwd = $wpdb->update(
        'wp_users',
        array(
            'user_pass' => wp_hash_password($password)
        ),
        array( 'user_email' => $email)
    );

    wp_head();?>
    <link rel="stylesheet" href="<?php echo esc_url( home_url( '/' )); ?>assets/css/kofaa.css">
    <script src="<?php echo esc_url( home_url( '/' )); ?>assets/js/kofaa.js"></script>
    <section id="page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 auto-mar">
                    <div class="modal-body">
                        <div class="form-title text-center">
                            <div>
                                <img src="<?php echo esc_url(get_theme_mod( 'wp_bootstrap_starter_logo' ));  ?>">
                                <h4 class="font50px font-electrolize"><?php if($_GET['lang'] == "ar") { echo "أهلاً بعودتك"; } else { echo "welcome back"; } ?></h4>
                                <h6 class="font-chiller font24px"><?php if($_GET['lang'] == "ar") { echo "ترجمة باللغة العربية"; } else { echo "Arabic translation"; } ?></h6>
                                <p class="font-bukra-regular marbtm50"><?php if($_GET['lang'] == "ar") { echo "نساعدكم في تطوير قدراتكم والإرتقاء في وظائفكم كل يوم"; } else { echo "We help you to develop your capabilities and advance in your jobs every day"; } ?></p>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-center">
                            <?php
                            if($updatepwd == 1){ ?>
                                <p id="resetpwdinfo" class="info form-group text-center"><?php if($_GET['lang'] == "ar") { echo "تم إعادة تعيين كلمة المرور بنجاح."; } else { echo "Password reset was successful"; } ?> <a href='<?php echo home_url(); ?>'>Login</a></p>
                            <?php }
                            else{ ?>
                                <p id="resetpwderror" class="error form-group text-center"><?php if($_GET['lang'] == "ar") { echo "حاول مرة اخرى"; } else { echo "Try again"; } ?></p>
                           <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
}

if(isset($_POST['fullname']) && isset($_POST['username']) && isset($_POST['registeremail']) && isset($_POST['registerpassword'])) {
    global $wpdb;

    $register = 1;
    $fullname = $_REQUEST['fullname'];
    $username = $_REQUEST['username'];
    $registeremail = $_REQUEST['registeremail'];
    $registerpassword = $_REQUEST['registerpassword'];

    if(strpos($username," ")) {
		if($_GET['lang'] == "ar") { echo "أدخل اسم المستخدم بدون مساحة"; } else { echo "Enter username without space"; }
        echo "<br>";
        // Enter username without space
        $register = 0;
    }
    $finduser = "SELECT COUNT(*) FROM wp_users WHERE user_login = '".$username."'";
    $u =$wpdb->get_var($finduser);
    if($u >=1) {
		if($_GET['lang'] == "ar") { echo "اسم المستخدم قيد الاستخدام. حاول مختلفة"; } else { echo "Username is already in use. Try different"; }
        echo "<br>";
        $register = 0;
    }

    $findemail = "SELECT COUNT(*) FROM wp_users WHERE user_email = '".$registeremail."'";
    $e =$wpdb->get_var($findemail);
    if($e >=1) {
		if($_GET['lang'] == "ar") { echo "تم تسجيل البريد بالفعل "; } else { echo "email is already registered"; }
        echo "<br>";
        $register = 0;
    }

    if($register == 1) { 
        $inserted = $wpdb->insert(
            'wp_users',
            array(
                'user_login' => $username,
                'user_pass' => wp_hash_password($registerpassword),
                'user_nicename' => $fullname,
                'user_email' => $registeremail,
                'user_registered' => date('Y-m-d H:i:s'),
                'display_name' => $fullname,
            )
        );
    }

    if($inserted == 1) {
        echo "true";
    }


}
?>