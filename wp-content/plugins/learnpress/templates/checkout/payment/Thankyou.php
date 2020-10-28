<?php
get_headers();
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
                    url_redirect: callback_url + "?t=redirect&uid=" + user_id + "&prod_id="+ course_id + "&u_email="+ user_email ,
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
