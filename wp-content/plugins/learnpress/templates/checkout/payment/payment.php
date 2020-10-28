<?php
include( plugin_dir_path( __FILE__ ) . 'paytabs.php');
include( plugin_dir_path( __FILE__ ) . 'config.php');

$pt = new paytabs($merchant_email, $secret_key);

$cart = learn_press_get_checkout_cart();
$pay_item = $cart->get_items(); 
foreach ($pay_item as $key_pay_item=>$val_pay_item) {
		$course_id = $val_pay_item['item_id'];
		$price = $val_pay_item['total'];
		$user_id = get_current_user_id();
		$user_email = wp_get_current_user()->user_email;
		$name = wp_get_current_user()->display_name;
		$phone = get_user_meta($user_id,'phone_number',true);
		$lastname = get_user_meta( $user_id, 'last_name', true );
		$firstname = get_user_meta( $user_id, 'first_name', true );
		$_course   = learn_press_get_course( $course_id );
		$course_name = $_course->get_title();
	}
 


$result = $pt->create_pay_page(array(
    //Customer's Personal Information
    'merchant_email' => $merchant_email,
	'secret_key' => $secret_key,
    'cc_first_name' => $firstname,         
    'cc_last_name' => $lastname, 
    'phone_number' => "33333333",
    'cc_phone_number' => "00973",
    'email' => $user_email,
	
	'billing_address' => "manama bahrain",
    'city' => "manama",
    'state' => "manama",
    'postal_code' => "00973",
    'country' => "BHR",
    
    //Customer's Shipping Address (All fields are mandatory)
    'address_shipping' => "null",
    'city_shipping' => "null",
    'state_shipping' => "null",
    'postal_code_shipping' => "00973",
    'country_shipping' => "BHR",
    
    //Product Information
    "products_per_title" => $course_name,   //Product title of the product. If multiple products then add “||” separator
    'quantity' => "1",                                    //Quantity of products. If multiple products then add “||” separator
    'amount' => $price,                                          //Amount of the products and other charges, it should be equal to: amount = (sum of 
    'unit_price' => $price,                                          //Amount of the products and other charges, it should be equal to: amount = (sum of 
    'currency' => "AED",
    'other_charges' => "0.00",
														//Currency of the amount stated. 3 character ISO currency code 
   //Invoice Information
    'title' => $name,               // Customer's Name on the invoice
    "msg_lang" => "en",                 //Language of the PayPage to be created. Invalid or blank entries will default to English.(Englsh/Arabic)
    "reference_no" => "null",        //Invoice reference number in your system
	"site_url" => $site_url,      //The requesting website be exactly the same as the website/URL associated with your PayTabs Merchant Account
    'return_url' => $return_url,
    "cms_with_version" => "API USING PHP",
    "paypage_info" => "1"
));


?>