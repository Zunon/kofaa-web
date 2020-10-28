<?php
/*
 * Plugin Name: Foloosi for WooCommerce
 * Plugin URI: https://foloosi.com/plugins/woofoloosi.zip
 * Description: Foloosi Payment Gateway Integration for WooCommerce
 * Version: 1.0.3
 * Stable tag: 1.0.0
 * Author: Foloosi Team
 * Author URI: https://www.foloosi.com/
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

add_action('plugins_loaded', 'woocommerce_foloosi_init', 21);

add_action('init', 'foloosiwebhookupdateorder');
function foloosiwebhookupdateorder(){ 
    if(!empty($_POST['transaction_no']) && !empty($_POST['order_id']) && !empty($_POST['secret_key'])){
        $success = false;
        $foloosiPaymentId = sanitize_text_field($_POST['transaction_no']);
        $orderId = sanitize_text_field($_POST['order_id']);
        $foloosisecretKey = sanitize_text_field($_POST['secret_key']);
        $order = new WC_Order($orderId);
        $args = array(
            'headers' => array(
                'secret_key' => $foloosisecretKey
            )
        );
        $response = wp_remote_get('https://foloosi.com/api/v1/api/transaction-detail/' . $foloosiPaymentId, $args);

        if (!is_wp_error($response)) {

            $body = json_decode($response['body'], true);        
            $status = $body['data']['status'];
            $optional1 = $body['data']['optional1'];
            if ($status == 'success' && $optional1 == $orderId){
                $success = true;
                global $woocommerce;
                if ($success === true) {                
                    $messagenew = 'Thank you for shopping with us. Your account has been charged and your transaction is successful. We will be processing your order soon. Order Id: '.$orderId;
                    $order->payment_complete($foloosiPaymentId);
                    $order->add_order_note("Foloosi payment successful <br/>Foloosi Id: $foloosiPaymentId");
                    $order->add_order_note($messagenew);   
                    if (isset($woocommerce->cart) === true) {
                        $woocommerce->cart->empty_cart();
                    }
                    echo json_encode($body);
                    die;
                }
            }
        }
    }
}
function woocommerce_foloosi_init()
{
    if (!class_exists('WC_Payment_Gateway')) {
        return;
    }

    class WC_Foloosi extends WC_Payment_Gateway
    {
        // This one stores the WooCommerce Order Id
        const SESSION_KEY                    = 'foloosi_wc_order_id';
        const foloosi_PAYMENT_ID            = 'foloosi_payment_id';
        const foloosi_ORDER_ID              = 'foloosi_order_id';
        const REFERENCE_ID                   = 'foloosi_reference_id';

        const INR                            = 'INR';
        const CAPTURE                        = 'capture';
        const AUTHORIZE                      = 'authorize';
        const WC_ORDER_ID                    = 'woocommerce_order_id';

        const DEFAULT_LABEL                  = 'Credit Card/Debit Card Payment by Foloosi';
        const DEFAULT_DESCRIPTION            = 'Pay securely by Credit or Debit card or Internet Banking through Foloosi.';

        protected $visibleSettings = array(
            'enabled',
            'title',
            'description',
            'key_id',
            'key_secret'

        );

        public $form_fields = array();

        public $supports = array(
            'products',
            'refunds'
        );

        /**
         * Can be set to true if you want payment fields
         * to show on the checkout (if doing a direct integration).
         * @var boolean
         */
        public $has_fields = false;

        /**
         * Unique ID for the gateway
         * @var string
         */
        public $id = 'foloosi';

        /**
         * Title of the payment method shown on the admin page.
         * @var string
         */
        public $method_title = 'Foloosi';

        /**
         * Icon URL, set in constructor
         * @var string
         */
        public $icon;

        /**
         * TODO: Remove usage of $this->msg
         */
        protected $msg = array(
            'message'   =>  '',
            'class'     =>  '',
        );

        /**
         * Return Wordpress plugin settings
         * @param  string $key setting key
         * @return mixed setting value
         */
        public function getSetting($key)
        {
            return $this->settings[$key];
        }

        /**
         * @param boolean $hooks Whether or not to
         *                       setup the hooks on
         *                       calling the constructor
         */
        public function __construct($hooks = true)
        {
            //$this->icon =  plugins_url('images/logo.png' , _FILE_);

            $this->init_form_fields();
            $this->init_settings();
            // TODO: This is hacky, find a better way to do this
            // See mergeSettingsWithParentPlugin() in subscriptions for more details.
            if ($hooks) {
                $this->initHooks();
            }

            $this->title = $this->getSetting('title');
        }
        protected function initHooks()
        {
            add_action('init', array(&$this, 'check_foloosi_response'));

            add_action('woocommerce_receipt_' . $this->id, array($this, 'receipt_page'));

            add_action('woocommerce_api_' . $this->id, array($this, 'check_foloosi_response'));

            $cb = array($this, 'process_admin_options');

            if (version_compare(WOOCOMMERCE_VERSION, '2.0.0', '>=')) {
                add_action("woocommerce_update_options_payment_gateways_{$this->id}", $cb);
            } else {
                add_action('woocommerce_update_options_payment_gateways', $cb);
            }
        }

        public function init_form_fields()
        {

            $defaultFormFields = array(
                'enabled' => array(
                    'title' => __('Enable/Disable', $this->id),
                    'type' => 'checkbox',
                    'label' => __('Enable this module?', $this->id),
                    'default' => 'yes'
                ),
                'title' => array(
                    'title' => __('Title', $this->id),
                    'type' => 'text',
                    'description' => __('This controls the title which the user sees during checkout.', $this->id),
                    'default' => __(static::DEFAULT_LABEL, $this->id)
                ),
                'description' => array(
                    'title' => __('Description', $this->id),
                    'type' => 'textarea',
                    'description' => __('This controls the description which the user sees during checkout.', $this->id),
                    'default' => __(static::DEFAULT_DESCRIPTION, $this->id)
                ),
                'key_id' => array(
                    'title' => __('Merchant Key', $this->id),
                    'type' => 'text',
                    'description' => __('The Merchant Key, Secret key can be generated from "API Settings" section of Foloosi Dashboard. Use test or live for test or live mode.', $this->id)
                ),
                'key_secret' => array(
                    'title' => __('Secret Key', $this->id),
                    'type' => 'text',
                    'description' => __('The Merchant Key, Secret key  can be generated from "API Settings" section of Foloosi Dashboard. Use test or live for test or live mode.', $this->id)
                )

            );

            foreach ($defaultFormFields as $key => $value) {
                if (in_array($key, $this->visibleSettings, true)) {
                    $this->form_fields[$key] = $value;
                }
            }
        }

        /**
         * Receipt Page
         * @param string $orderId WC Order Id
         **/
        function receipt_page($orderId)
        {
            echo $this->generate_foloosi_form($orderId);
        }
        /**
         * Generate foloosi button link
         * @param string $orderId WC Order Id
         **/
        public function generate_foloosi_form($order_id)
        {
            global $woocommerce;
            $order = wc_get_order($order_id);

            $html = '<p>' . __('Thank you for your order, please click the button below to pay with Foloosi.', $this->id) . '</p>';

            $html .= $this->generateOrderForm($woocommerce->session->get(self::REFERENCE_ID));

            return $html;
        }
        /**
         * Returns redirect URL post payment processing
         * @return string redirect URL
         */
        private function getRedirectUrl()
        {
            return get_site_url() . '/wc-api/' . $this->id;
        }
        /**
         * Generates the order form
         **/
        function generateOrderForm($refToken)
        {
            $redirectUrl = $this->getRedirectUrl();
            $this->enqueueCheckoutScripts($refToken);

            return <<<EOT
<form name='foloosipayform' action="$redirectUrl" method="POST">
    <input type="hidden" name="foloosi_payment_id" id="foloosi_payment_id">
    <input type="hidden" name="foloosi_signature"  id="foloosi_signature" >
    <!-- This distinguishes all our various wordpress plugins -->
    <input type="hidden" name="foloosi_wc_form_submit" value="1">
</form>
<p id="msg-foloosi-success" class="woocommerce-info woocommerce-message" style="display:none">
Please wait while we are processing your payment.
</p>
<p>
    <button class="FoloosiPayApi" id="btn-foloosipay">Pay Now</button>

    <button id="btn-foloosi-submit" onclick="document.foloosipayform.submit()" style="display: none;">Submit</button>
</p>
EOT;
        }
        /**
         * Check for valid foloosi server callback
         **/
        function check_foloosi_response()
        {
            global $woocommerce;

            $orderId = $woocommerce->session->get(self::SESSION_KEY);
            $order = new WC_Order($orderId);

            //
            // If the order has already been paid for
            // redirect user to success page
            //
            if ($order->needs_payment() === false) {
                $this->redirectUser($order);
            }

            $foloosiPaymentId = null;

            if ($orderId  and !empty($_POST[self::foloosi_PAYMENT_ID])) {
                $error = "";
                $success = false;

                try {
                    $success = false;
                    $foloosiPaymentId = sanitize_text_field($_POST[self::foloosi_PAYMENT_ID]);
                    $args = array(
                        'headers' => array(
                            'secret_key' => $this->getSetting('key_secret')
                        )
                    );


                    $response = wp_remote_get('https://foloosi.com/api/v1/api/transaction-detail/' . $foloosiPaymentId, $args);

                    if (!is_wp_error($response)) {

                        $body = json_decode($response['body'], true);
                        if (array_key_exists("data", $body) && array_key_exists("status", $body['data'])) {
                            $status = $body['data']['status'];
                            $optional1 = $body['data']['optional1'];
                            if ($status == 'success' && $optional1 == $orderId)
                                $success = true;
                        }
                    }
                } catch (Errors\SignatureVerificationError $e) {
                    $error = 'WOOCOMMERCE_ERROR: Payment to Foloosi Failed. ' . $e->getMessage();
                }
            } else {
                $success = false;
                $error = 'Customer cancelled the payment';
                $this->handleErrorCase($order);

                $this->updateOrder($order, $success, $error, $foloosiPaymentId);

                wp_redirect(wc_get_checkout_url());
                exit;
            }


            $this->updateOrder($order, $success, $error, $foloosiPaymentId);

            $this->redirectUser($order);
        }
        public function country_code($country){
            $countries=array('AF'=>'AFG','AX'=>'ALA','AL'=>'ALB','DZ'=>'DZA','AS'=>'ASM','AD'=>'AND','AO'=>'AGO','AI'=>'AIA','AQ'=>'ATA','AG'=>'ATG','AR'=>'ARG','AM'=>'ARM','AW'=>'ABW','AU'=>'AUS','AT'=>'AUT','AZ'=>'AZE','BS'=>'BHS','BH'=>'BHR','BD'=>'BGD','BB'=>'BRB','BY'=>'BLR','BE'=>'BEL','BZ'=>'BLZ','BJ'=>'BEN','BM'=>'BMU','BT'=>'BTN','BO'=>'BOL','BQ'=>'BES','BA'=>'BIH','BW'=>'BWA','BV'=>'BVT','BR'=>'BRA','IO'=>'IOT','BN'=>'BRN','BG'=>'BGR','BF'=>'BFA','BI'=>'BDI','KH'=>'KHM','CM'=>'CMR','CA'=>'CAN','CV'=>'CPV','KY'=>'CYM','CF'=>'CAF','TD'=>'TCD','CL'=>'CHL','CN'=>'CHN','CX'=>'CXR','CC'=>'CCK','CO'=>'COL','KM'=>'COM','CG'=>'COG','CD'=>'COD','CK'=>'COK','CR'=>'CRI','CI'=>'CIV','HR'=>'HRV','CU'=>'CUB','CW'=>'CUW','CY'=>'CYP','CZ'=>'CZE','DK'=>'DNK','DJ'=>'DJI','DM'=>'DMA','DO'=>'DOM','EC'=>'ECU','EG'=>'EGY','SV'=>'SLV','GQ'=>'GNQ','ER'=>'ERI','EE'=>'EST','ET'=>'ETH','FK'=>'FLK','FO'=>'FRO','FJ'=>'FIJ','FI'=>'FIN','FR'=>'FRA','GF'=>'GUF','PF'=>'PYF','TF'=>'ATF','GA'=>'GAB','GM'=>'GMB','GE'=>'GEO','DE'=>'DEU','GH'=>'GHA','GI'=>'GIB','GR'=>'GRC','GL'=>'GRL','GD'=>'GRD','GP'=>'GLP','GU'=>'GUM','GT'=>'GTM','GG'=>'GGY','GN'=>'GIN','GW'=>'GNB','GY'=>'GUY','HT'=>'HTI','HM'=>'HMD','VA'=>'VAT','HN'=>'HND','HK'=>'HKG','HU'=>'HUN','IS'=>'ISL','IN'=>'IND','ID'=>'IDN','IR'=>'IRN','IQ'=>'IRQ','IE'=>'IRL','IM'=>'IMN','IL'=>'ISR','IT'=>'ITA','JM'=>'JAM','JP'=>'JPN','JE'=>'JEY','JO'=>'JOR','KZ'=>'KAZ','KE'=>'KEN','KI'=>'KIR','KP'=>'PRK','KR'=>'KOR','KW'=>'KWT','KG'=>'KGZ','LA'=>'LAO','LV'=>'LVA','LB'=>'LBN','LS'=>'LSO','LR'=>'LBR','LY'=>'LBY','LI'=>'LIE','LT'=>'LTU','LU'=>'LUX','MO'=>'MAC','MK'=>'MKD','MG'=>'MDG','MW'=>'MWI','MY'=>'MYS','MV'=>'MDV','ML'=>'MLI','MT'=>'MLT','MH'=>'MHL','MQ'=>'MTQ','MR'=>'MRT','MU'=>'MUS','YT'=>'MYT','MX'=>'MEX','FM'=>'FSM','MD'=>'MDA','MC'=>'MCO','MN'=>'MNG','ME'=>'MNE','MS'=>'MSR','MA'=>'MAR','MZ'=>'MOZ','MM'=>'MMR','NA'=>'NAM','NR'=>'NRU','NP'=>'NPL','NL'=>'NLD','AN'=>'ANT','NC'=>'NCL','NZ'=>'NZL','NI'=>'NIC','NE'=>'NER','NG'=>'NGA','NU'=>'NIU','NF'=>'NFK','MP'=>'MNP','NO'=>'NOR','OM'=>'OMN','PK'=>'PAK','PW'=>'PLW','PS'=>'PSE','PA'=>'PAN','PG'=>'PNG','PY'=>'PRY','PE'=>'PER','PH'=>'PHL','PN'=>'PCN','PL'=>'POL','PT'=>'PRT','PR'=>'PRI','QA'=>'QAT','RE'=>'REU','RO'=>'ROU','RU'=>'RUS','RW'=>'RWA','BL'=>'BLM','SH'=>'SHN','KN'=>'KNA','LC'=>'LCA','MF'=>'MAF','SX'=>'SXM','PM'=>'SPM','VC'=>'VCT','WS'=>'WSM','SM'=>'SMR','ST'=>'STP','SA'=>'SAU','SN'=>'SEN','RS'=>'SRB','SC'=>'SYC','SL'=>'SLE','SG'=>'SGP','SK'=>'SVK','SI'=>'SVN','SB'=>'SLB','SO'=>'SOM','ZA'=>'ZAF','GS'=>'SGS','SS'=>'SSD','ES'=>'ESP','LK'=>'LKA','SD'=>'SDN','SR'=>'SUR','SJ'=>'SJM','SZ'=>'SWZ','SE'=>'SWE','CH'=>'CHE','SY'=>'SYR','TW'=>'TWN','TJ'=>'TJK','TZ'=>'TZA','TH'=>'THA','TL'=>'TLS','TG'=>'TGO','TK'=>'TKL','TO'=>'TON','TT'=>'TTO','TN'=>'TUN','TR'=>'TUR','TM'=>'TKM','TC'=>'TCA','TV'=>'TUV','UG'=>'UGA','UA'=>'UKR','AE'=>'ARE','GB'=>'GBR','US'=>'USA','UM'=>'UMI','UY'=>'URY','UZ'=>'UZB','VU'=>'VUT','VE'=>'VEN','VN'=>'VNM','VG'=>'VGB','VI'=>'VIR','WF'=>'WLF','EH'=>'ESH','YE'=>'YEM','ZM'=>'ZMB','ZW'=>'ZWE',);
            $iso_code=isset($countries[$country])?$countries[$country]:$country;
            return $iso_code;
        }
        public function getCustomerInfo($order)
        {
            if (version_compare(WOOCOMMERCE_VERSION, '2.7.0', '>=')) {
                $args = array(
                    'name'    => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
                    'email'   => $order->get_billing_email(),
                    'contact' => $order->get_billing_phone(),
                    'address'=> $order->get_billing_address_1(),
                    'city'=> $order->get_billing_city(),
                    'country'=> $this->country_code($order->get_billing_country()),
                    'state'=> $order->get_billing_state(),
                    'postal_code'=> $order->get_billing_postcode(),
                    'api_description' => $order->get_customer_note()
                );
            } else {
                $args = array(
                    'name'    => $order->billing_first_name . ' ' . $order->billing_last_name,
                    'email'   => $order->billing_email,
                    'contact' => $order->billing_phone,
                    'address'=> $order->billing_address_1,
                    'city'=> $order->billing_city,
                    'country'=> $this->country_code($order->billing_country),
                    'state'=> $order->billing_state,
                    'postal_code'=> $order->billing_postcode,
                    'api_description'=> $order->customer_note
                    
                );
            }    
            return $args;
        }

        protected function handleErrorCase(&$order)
        {
            $orderId = $this->getOrderId($order);

            $this->msg['class'] = 'error';
            $this->msg['message'] = $this->getErrorMessage($orderId);
        }

        protected function redirectUser($order)
        {
            $redirectUrl = $this->get_return_url($order);

            wp_redirect($redirectUrl);
            exit;
        }

        protected function getErrorMessage($orderId)
        {
            // We don't have a proper order id
            if ($orderId !== null)
            {
                $message = 'An error occured while processing this payment';
            }
            if (isset($_POST['error']) === true)
            {
                $error = $_POST['error'];

                $description = htmlentities($error['description']);
                $code = htmlentities($error['code']);

                $message = 'An error occured. Description : ' . $description . '. Code : ' . $code;

                if (isset($error['field']) === true)
                {
                    $fieldError = htmlentities($error['field']);
                    $message .= 'Field : ' . $fieldError;
                }
            }
            else
            {
                $message = 'An error occured. Please contact administrator for assistance';
            }

            return $message;
        }
        
        /**
         * Modifies existing order and handles success case
         *
         * @param $success, & $order
         */
        public function updateOrder(&$order, $success, $errorMessage, $foloosiPaymentId, $webhook = false)
        {
            global $woocommerce;

            $orderId = $this->getOrderId($order);

            if ($success === true) {
                $this->msg['message'] = "Thank you for shopping with us. Your account has been charged and your transaction is successful. We will be processing your order soon. Order Id: $orderId";
                $this->msg['class'] = 'success';

                $order->payment_complete($foloosiPaymentId);
                $order->add_order_note("Foloosi payment successful <br/>Foloosi Id: $foloosiPaymentId");
                $order->add_order_note($this->msg['message']);

                if (isset($woocommerce->cart) === true) {
                    $woocommerce->cart->empty_cart();
                }
            } else {
                $this->msg['class'] = 'error';
                $this->msg['message'] = $errorMessage;
                if ($foloosiPaymentId) {           
                    try {
                        $success = false;
                        $foloosiPaymentId = sanitize_text_field($_POST[self::foloosi_PAYMENT_ID]);
                        $args = array(
                            'headers' => array(
                                'secret_key' => $this->getSetting('key_secret')
                            )
                        );   
    
                        $response = wp_remote_get('https://foloosi.com/api/v1/api/transaction-detail/' . $foloosiPaymentId, $args);
    
                        if (!is_wp_error($response)) {
    
                            $body = json_decode($response['body'], true);
                            if (array_key_exists("data", $body) && array_key_exists("status", $body['data'])) {
                                $status = $body['data']['status'];
                                $optional1 = $body['data']['optional1'];
                                if ($status == 'success' && $optional1 == $orderId)
                                    $success = true;
                                    $order->payment_complete($foloosiPaymentId);
                                    $order->add_order_note("Foloosi payment successful <br/>Foloosi Id: $foloosiPaymentId");
                                    $order->add_order_note($this->msg['message']);
                    
                                    if (isset($woocommerce->cart) === true) {
                                        $woocommerce->cart->empty_cart();
                                    }
                            }
                        }
                    } catch (Errors\SignatureVerificationError $e) {
                        $error = 'WOOCOMMERCE_ERROR: Payment to Foloosi Failed. ' . $e->getMessage();
                    }
                    
                    $order->add_order_note("Payment Failed. Please check foloosi Dashboard. <br/> Foloosi Id: $foloosiPaymentId");
                }
                else{
                    $order->add_order_note("Transaction Failed: $errorMessage<br/>");
                    $order->update_status('failed');
                }
            }
            if ($webhook === false) {
                $this->add_notice($this->msg['message'], $this->msg['class']);
            }
        }
        /**
         * Add a woocommerce notification message
         *
         * @param string $message Notification message
         * @param string $type Notification type, default = notice
         */
        protected function add_notice($message, $type = 'notice')
        {
            global $woocommerce;
            $type = in_array($type, array('notice', 'error', 'success'), true) ? $type : 'notice';
            // Check for existence of new notification api. Else use previous add_error
            if (function_exists('wc_add_notice')) {
                wc_add_notice($message, $type);
            } else {
                // Retrocompatibility WooCommerce < 2.1
                switch ($type) {
                    case "error":
                        $woocommerce->add_error($message);
                        break;
                    default:
                        $woocommerce->add_message($message);
                        break;
                }
            }
        }

        private function enqueueCheckoutScripts($refToken)
        {
            wp_register_script(
                'foloosi_checkout',
                'https://www.foloosi.com/js/foloosipay.v2.js',
                null,
                null
            );

            $params = array(
                'reference_token'          => $refToken,
                'merchant_key'         => $this->getSetting('key_id'),
                'redirect_url' => get_site_url()
            );

            wp_register_script('foloosi_wc_script', plugin_dir_url(__FILE__)  . 'script.js', array('foloosi_checkout'));
            wp_localize_script('foloosi_wc_script', 'foloosi_params', $params);
            wp_enqueue_script('foloosi_wc_script');
        }

        protected function getOrderId($order)
        {
            if (version_compare(WOOCOMMERCE_VERSION, '2.7.0', '>=')) {
                return $order->get_id();
            }

            return $order->id;
        }
        /**
         * Gets the Order Key from the Order
         * for all WC versions that we suport
         */
        protected function getOrderKey($order)
        {
            $orderKey = null;

            if (version_compare(WOOCOMMERCE_VERSION, '3.0.0', '>=')) {
                return $order->get_order_key();
            }

            return $order->order_key;
        }
        /**
         * @param  WC_Order $order
         * @return string currency
         */
        private function getOrderCurrency($order)
        {
            if (version_compare(WOOCOMMERCE_VERSION, '2.7.0', '>=')) {
                return $order->get_currency();
            }

            return $order->get_order_currency();
        }
        public function process_payment($order_id)
        {

            global $woocommerce;

            // we need it to get any order detailes
            $order = wc_get_order($order_id);
            /*
 	 * Array with parameters for API interaction
	 */
            $customer_info = $this->getCustomerInfo($order);
            $args = array(
                'headers' => array(
                    'merchant_key' => $this->getSetting('key_id')
                ),
                'body' => array(
                    'redirect_url' => get_site_url(),
                    'transaction_amount' => $order->get_total(),
                    'currency' => $this->getOrderCurrency($order),
                    'optional1' => $order_id,
                    'customer_name'=>$customer_info['name'], 
                    'customer_email'=>$customer_info['email'], 
                    'customer_mobile'=>$customer_info['contact'], 
                    'source'=>'woocommerce',
                    'customer_address'=>$customer_info['address'],
                    'customer_city'=>$customer_info['city'],
                    'billing_country'=>$customer_info['country'],
                    'billing_state'=>$customer_info['state'],
                    'billing_postal_code'=>$customer_info['postal_code'],
                    'description'=>$customer_info['api_description'],
                    'optional2' => get_site_url()
                )

            );

            /*
	 * Your API interaction could be built with wp_remote_post()
      */
            //var_dump($args);
            $response = wp_remote_post('https://foloosi.com/api/v1/api/initialize-setup', $args);
            //var_dump($response);
            if (!is_wp_error($response)) {

                $body = json_decode($response['body'], true);
                if (array_key_exists("data", $body) && array_key_exists("reference_token", $body['data'])) {
                    $refToken = $body['data']['reference_token'];
                    //var_dump($refToken);


                    $woocommerce->session->set(self::SESSION_KEY, $order_id);
                    $woocommerce->session->set(self::REFERENCE_ID, $refToken);
                    $orderKey = $this->getOrderKey($order);

                    if (version_compare(WOOCOMMERCE_VERSION, '2.1', '>=')) {
                        return array(
                            'result' => 'success',
                            'redirect' => add_query_arg('key', $orderKey, $order->get_checkout_payment_url(true))
                        );
                    } else if (version_compare(WOOCOMMERCE_VERSION, '2.0.0', '>=')) {
                        return array(
                            'result' => 'success',
                            'redirect' => add_query_arg(
                                'order',
                                $order->get_id(),
                                add_query_arg('key', $orderKey, $order->get_checkout_payment_url(true))
                            )
                        );
                    } else {
                        return array(
                            'result' => 'success',
                            'redirect' => add_query_arg(
                                'order',
                                $order->get_id(),
                                add_query_arg('key', $orderKey, get_permalink(get_option('woocommerce_pay_page_id')))
                            )
                        );
                    }
                }
            }
        }
    }

    /**
     * Add the Gateway to WooCommerce
     **/
    function woocommerce_add_foloosi_gateway($methods)
    {
        $methods[] = 'WC_Foloosi';
        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'woocommerce_add_foloosi_gateway');
}