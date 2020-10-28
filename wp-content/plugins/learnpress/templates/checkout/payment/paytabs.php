<?php

require dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))))."/wp-load.php";
echo "<input type='hidden' id='course_name' name='course_name' value='".$_POST["course_name"]."'/>";
echo "<input type='hidden' id='total_course_value' name='total_course_value' value='".$_POST["total_course_value"]."'/>";
echo "<input type='hidden' id='user_id' name='user_id' value='".$_POST["user_id"]."'/>";
echo "<input type='hidden' id='user_email' name='user_email' value='".$_POST["user_email"]."'/>";
echo "<input type='hidden' id='check_trans_url' name='check_trans_url' value='".home_url('/callback.php')."'/>";
echo "<input type='hidden' id='callback_url' name='callback_url' value='".plugin_dir_url(__FILE__)."result.php'/>";
echo "<input type='hidden' id='order_id' name='order_id' value='".rand(100,10000)."'/>";
echo "<input type='hidden' id='course_id' name='course_id' value='".$_POST["course_id"]."'/>";
echo "<input type='hidden' id='curent_lang' name='curent_lang' value='".$_POST["curent_lang"]."'/>";
?>
<div class="PT_express_checkout"></div>
<link rel="stylesheet" href="https://www.paytabs.com/express/express.css">
<script src="https://www.paytabs.com/theme/express_checkout/js/jquery-1.11.1.min.js"></script>
<script src="https://www.paytabs.com/express/express_checkout_v3.js"></script>

<script type="text/javascript">
var price = jQuery("#total_course_value").val();
var course_name = jQuery("#course_name").val();
var callback_url = jQuery("#callback_url").val();
var check_url = jQuery("#check_trans_url").val();
var course_id = jQuery("#course_id").val();
var user_id = jQuery("#user_id").val();
var user_email = jQuery("#user_email").val();
var order_id = jQuery("#order_id").val();
var curent_lang = jQuery("#curent_lang").val();


 Paytabs("#express_checkout").expresscheckout({
                settings: {
                   secret_key: "Y1ZSUIzIw1ros3skCrlgHwMesqDURyAPAAIuihcPiEdLHF5k7qAPXm4pNHkEfUuQuckNtxCMcQxUwDeD4Ps1cwrZeLEuF8kakFXO",
					merchant_id: "10068111",
					amount: price,
					currency: "AED",
					title: course_name,
					product_names: course_name,
					order_id: order_id,
                    url_redirect: callback_url + "?t=redirect&uid=" + user_id + "&prod_id="+ course_id + "&u_email="+ user_email + "&lang="+ curent_lang,
                    display_customer_info: 1,
                    display_billing_fields: 1,
                    display_shipping_fields: 0,
                    language: curent_lang,
                    redirect_on_reject: 1,
					is_self: 1,
					is_iframe: {
						load: "onbodyload",
						show: 1
					},
                    url_cancel: callback_url + "?t=cancel"
                },
				customer_info: {
					email_address: user_email
				}
            });

			
        </script>
