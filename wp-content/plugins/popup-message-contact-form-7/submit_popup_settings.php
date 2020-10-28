<?php
if (!defined('ABSPATH'))
    exit;

if (!class_exists('OCCF7POPUP_submit_menu')) {

    class OCCF7POPUP_submit_menu {

        protected static $submit_instance;

        function OCCF7POPUP_wpcf7_ajax_json_echo( $items, $result ) { 

            $formid = $_REQUEST['_wpcf7'];

            $items["popup_id"] = get_post_meta( $formid, 'enabled-popup', true );

            $items["failure_popup_id"] = get_post_meta( $formid, 'enabled-popup-failure', true );

            // Check popup message if text is not available then it pass default text
            if( ! empty(get_post_meta( $formid, 'popup_message', true ))) {
                $items["popup_message"] = get_post_meta( $formid, 'popup_message', true );
            }else{
                $items["popup_message"] = "Form has been submitted successfully.";
            }

            // Check popup border radius if border radius is not available then it pass default border radius
            if( ! empty(get_post_meta( $formid, 'm_popup_radius', true ))) {
                $items["m_popup_radius"] = get_post_meta( $formid, 'm_popup_radius', true );
            }else{
                $items["m_popup_radius"] = "10px";
            }

            // Check popup duration time if duration time is not available then it pass default duration time
            if( ! empty(get_post_meta( $formid, 'm_popup_duration', true ))) {
                $items["m_popup_duration"] = get_post_meta( $formid, 'm_popup_duration', true );
            }else{
                $items["m_popup_duration"] = "100000000000";
            }


            // Check popup width if width is not available then it pass default width
            if( ! empty(get_post_meta( $formid, 'm_popup_width', true ))) {
                $items["m_popup_width"] = get_post_meta( $formid, 'm_popup_width', true );
            }else{
                $items["m_popup_width"] = "500px";
            }

            

            // Check popup background option if option is not available then it pass popup background option
            if( ! empty(get_post_meta( $formid, 'popup_background_option', true ))) {
                $items["popup_background_option"] = get_post_meta( $formid, 'popup_background_option', true );
            }else{
                $items["popup_background_option"] = "bg_color";
            }

            // Check popup background color if color is not available then it pass default color
            if( ! empty(get_post_meta( $formid, 'popup_background_color', true ))) {
                $items["popup_background_color"] = get_post_meta( $formid, 'popup_background_color', true );
            }else{
                $items["popup_background_color"] = "#fff";
            }

            // Check popup background image if image is not available then it pass blank value
            if( ! empty(get_post_meta( $formid, 'popup_image_color', true ))) {
                $items["popup_image_color"] = get_post_meta( $formid, 'popup_image_color', true );
            }else{
                $items["popup_image_color"] = plugins_url( '/popup-message-for-contact-form-7-pro/popup_img2.png');
            }

            // Check popup background gradient color if gradient color is not available then it pass gradient color
            if( ! empty(get_post_meta( $formid, 'popup_gradient_color', true ))) {
                $items["popup_gradient_color"] = get_post_meta( $formid, 'popup_gradient_color', true );
            }else{
                $items["popup_gradient_color"] = "#fff";
            }

            // Check popup background gradient color 2 if gradient color 2  is not available then it pass gradient color
            if( ! empty(get_post_meta( $formid, 'popup_gradient_color1', true ))) {
                $items["popup_gradient_color1"] = get_post_meta( $formid, 'popup_gradient_color1', true );
            }else{
                $items["popup_gradient_color1"] = "#FF0000";
            }

            // Check popup button text if text is not available then it pass default text
            if( ! empty(get_post_meta( $formid, 'popup_button_text', true ))) {
                $items["popup_button_text"] = get_post_meta( $formid, 'popup_button_text', true );
            }else{
                $items["popup_button_text"] = "Ok";
            }

            // Check popup text color if color is not available then it pass default color
            if( ! empty(get_post_meta( $formid, 'popup_text_color', true ))) {
                $items["popup_text_color"] = get_post_meta( $formid, 'popup_text_color', true );
            }else{
                $items["popup_text_color"] = "#000";
            }

            
            // Check failure popup text color if color is not available then it pass default color
            if( ! empty(get_post_meta( $formid, 'failure_popup_text_color', true ))) {
                $items["failure_popup_text_color"] = get_post_meta( $formid, 'failure_popup_text_color', true );
            }else{
                $items["failure_popup_text_color"] = "#000";
            }

            // Check popup button background color if color is not available then it pass default color
            if( ! empty(get_post_meta( $formid, 'popup_button_background_color', true ))) {
                $items["popup_button_background_color"] = get_post_meta( $formid, 'popup_button_background_color', true );
            }else{
                $items["popup_button_background_color"] = "#3085d6";
            }

            // Check failure popup border radius if border radius is not available then it pass default border radius
            if( ! empty(get_post_meta( $formid, 'failure_popup_radius', true ))) {
                $items["failure_popup_radius"] = get_post_meta( $formid, 'failure_popup_radius', true );
            }else{
                $items["failure_popup_radius"] = "10px";
            }


            // Check failure popup duration time if duration time is not available then it pass default duration time
            if( ! empty(get_post_meta( $formid, 'failure_popup_duration', true ))) {
                $items["failure_popup_duration"] = get_post_meta( $formid, 'failure_popup_duration', true );
            }else{
                $items["failure_popup_duration"] = "100000000000";
            }

            // Check failure popup width if width is not available then it pass default width
            if( ! empty(get_post_meta( $formid, 'failure_popup_width', true ))) {
                $items["failure_popup_width"] = get_post_meta( $formid, 'failure_popup_width', true );
            }else{
                $items["failure_popup_width"] = "500px";
            }

            
            // Check failure failure popup button background color if color is not available then it pass default color
            if( ! empty(get_post_meta( $formid, 'failure_popup_button_background_color', true ))) {
                $items["failure_popup_button_background_color"] = get_post_meta( $formid, 'failure_popup_button_background_color', true );
            }else{
                $items["failure_popup_button_background_color"] = "#3085d6";
            }
            

            // Check failure popup button text if text is not available then it pass default text
            if( ! empty(get_post_meta( $formid, 'failure_popup_button_text', true ))) {
                $items["failure_popup_button_text"] = get_post_meta( $formid, 'failure_popup_button_text', true );
            }else{
                $items["failure_popup_button_text"] = "Ok";
            }

            // Check failure popup background color if color is not available then it pass default color
            if( ! empty(get_post_meta( $formid, 'failure_popup_background_color', true ))){
                $items["failure_popup_background_color"] = get_post_meta( $formid, 'failure_popup_background_color', true );
            }else{
                $items["failure_popup_background_color"] = "#fff";
            }

            // Check failure popup background option if option is not available then it pass popup background color
            if( ! empty(get_post_meta( $formid, 'failure_popup_background_option', true ))){
                $items["failure_popup_background_option"] = get_post_meta( $formid, 'failure_popup_background_option', true );
            }else{
                $items["failure_popup_background_option"] = "bg_color";
            }

            // Check failure popup background gradient color if gradient color is not available then it pass gradient color
            if( ! empty(get_post_meta( $formid, 'failure_popup_gradient_color', true ))){
                $items["failure_popup_gradient_color"] = get_post_meta( $formid, 'failure_popup_gradient_color', true );
            }else{
                $items["failure_popup_gradient_color"] = "#fff";
            }


            // Check failure popup background image if image is not available then it pass blank value
            if( ! empty(get_post_meta( $formid, 'failure_popup_image_color', true ))){
                $items["failure_popup_image_color"] = get_post_meta( $formid, 'failure_popup_image_color', true );
            }else{
                $items["failure_popup_image_color"] = plugins_url( '/popup-message-for-contact-form-7-pro/popup_img2.png');
            }



            // Check failure popup 2 background gradient color if gradient color 2 is not available then it pass gradient color
            if( ! empty(get_post_meta( $formid, 'failure_popup_gradient_color1', true ))){
                $items["failure_popup_gradient_color1"] = get_post_meta( $formid, 'failure_popup_gradient_color1', true );
            }else{
                $items["failure_popup_gradient_color1"] = "#FF0000";
            }
            return $items; 
        }

        function init() {
           add_filter( 'wpcf7_ajax_json_echo', array( $this, 'OCCF7POPUP_wpcf7_ajax_json_echo'), 10, 2 ); 
        }


        public static function submit_instance() {
            if (!isset(self::$submit_instance)) {
                self::$submit_instance = new self();
                self::$submit_instance->init();
            }
            return self::$submit_instance;
        }
    }
    OCCF7POPUP_submit_menu::submit_instance();
}
