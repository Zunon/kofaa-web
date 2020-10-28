<?php
if (!defined('ABSPATH'))
    exit;

if (!class_exists('OCCF7POPUP_save_menu')) {

    class OCCF7POPUP_save_menu {

        protected static $save_menuinstance;

        function init() {
           add_action( 'wpcf7_after_save', array( $this, 'OCCF7POPUP_wpcf7_after_save'), 10, 1 ); 
        }

        function OCCF7POPUP_wpcf7_after_save( $instance) { 
    
            $formid = $instance->id;

            if(!empty($_POST['enabled_popup_val'])) {
                $enabled_popup_id = $formid;
            }else{
                $enabled_popup_id = "";
            }

            if(!empty($_POST['enabled_failure_popup_val'])){
                $enabled_failure_popup_id = $formid;
            }else{
                $enabled_failure_popup_id = "";
            }

            

            update_post_meta( $formid, 'enabled-popup', $enabled_popup_id );
            update_post_meta( $formid, 'enabled-popup-failure', $enabled_failure_popup_id );

            $m_popup_message_data = sanitize_text_field($_POST['popup_message']);
            update_post_meta( $formid, 'popup_message', $m_popup_message_data );

            $m_popup_width_data = sanitize_text_field($_POST['m_popup_width']);
            update_post_meta( $formid, 'm_popup_width', $m_popup_width_data );

            $m_popup_radius = sanitize_text_field($_POST['m_popup_radius']);
            update_post_meta( $formid, 'm_popup_radius', $m_popup_radius );

            $m_popup_duration_data = sanitize_text_field($_POST['m_popup_duration']);
            update_post_meta( $formid, 'm_popup_duration', $m_popup_duration_data );

            $m_popup_templet_data = sanitize_text_field($_POST['popup_templet']);
            update_post_meta( $formid, 'popup_templet', $m_popup_templet_data );

            if($m_popup_templet_data == "templet1"){
                update_post_meta( $formid, 'popup_background_option', "bg_color");
                update_post_meta( $formid, 'popup_background_color', "#34495e" );
                update_post_meta( $formid, 'popup_text_color', "#ffffff" );
                update_post_meta( $formid, 'popup_button_background_color', "#27ad5f" );
            }
            if($m_popup_templet_data == "templet2"){
                update_post_meta( $formid, 'popup_background_option', "gradient_color");
                update_post_meta( $formid, 'popup_gradient_color', "#CD5C5C");
                update_post_meta( $formid, 'popup_gradient_color1', "#FFA07A");
                update_post_meta( $formid, 'popup_text_color', "#000000" );
                update_post_meta( $formid, 'popup_button_background_color', "#ffffff" );
            }
            if($m_popup_templet_data == "templet3"){
                update_post_meta( $formid, 'popup_background_option', "image");
                update_post_meta( $formid, 'popup_image_color', OCCF7POPUP_PLUGIN_DIR.'/images/pexels-photo-1191710.jpeg');
                update_post_meta( $formid, 'popup_text_color', "#ffffff" );
                update_post_meta( $formid, 'popup_button_background_color', "#51654e" );                
            }
            if($m_popup_templet_data == "templet4"){
                update_post_meta( $formid, 'popup_background_option', "gradient_color");
                update_post_meta( $formid, 'popup_gradient_color', "#268717");
                update_post_meta( $formid, 'popup_gradient_color1', "#A6EF9B");
                update_post_meta( $formid, 'popup_text_color', "#000000" );
                update_post_meta( $formid, 'popup_button_background_color', "#ffffff" );
            }
            if($m_popup_templet_data == "templet5"){
                update_post_meta( $formid, 'popup_background_option', "image");
                update_post_meta( $formid, 'popup_image_color', OCCF7POPUP_PLUGIN_DIR.'/images/pexels-photo-1191710.jpeg');
                update_post_meta( $formid, 'popup_text_color', "#ffffff" );
                update_post_meta( $formid, 'popup_button_background_color', "#FF9800" );
            }
            if($m_popup_templet_data == "custom_templet"){
                update_post_meta( $formid, 'popup_background_option', 'bg_color' );
                update_post_meta( $formid, 'popup_background_color', "#34495e" );
                update_post_meta( $formid, 'popup_text_color', "#ffffff" );
                update_post_meta( $formid, 'popup_button_background_color', "#51654e" );
            }
            //Failure message Settings
        }


        public static function save_menuinstance() {
            if (!isset(self::$save_menuinstance)) {
                self::$save_menuinstance = new self();
                self::$save_menuinstance->init();
            }
            return self::$save_menuinstance;
        }
    }
    OCCF7POPUP_save_menu::save_menuinstance();
}
