<?php

class Simple_Notification_Bar_PS {

    private static $instance;

    public static function get_instance(){
        // If the single instance hasn't been set, set it now.
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        $this->plugin_version = '1.0.0';
        $this->plugin_name = 'Simple Notification Bar';
        $this->plugin_slug = 'simple-notification-bar';

        add_action('wp_head', array( $this, 'simple_notification_display_styles' ));
        add_action('wp_footer', array($this, 'simple_notification_display_html' ));

        //add_action( 'admin_menu', array( $this, 'add_menu_admin_panel' ) );
        //add_action( 'admin_init', array( $this, 'simple_notification_bar_register_options' ) );

    }

    function simple_notification_display_html(){

        $simple_noti_header_text_ps = get_option('simple_noti_header_text_ps');
        $simple_noti_footer_text_ps = get_option('simple_noti_footer_text_ps');

        $header_content = '<div id="simple-noti-header">
                        <p>' . sanitize_text_field($simple_noti_header_text_ps). '</p>
                        </div>';

        $footer_content = '<div id="simple-noti-footer">
                        <p>' . sanitize_text_field($simple_noti_footer_text_ps) . '</p>
                        </div>';

        echo $header_content . $footer_content;

    }

    function simple_notification_display_styles(){

        $simple_noti_header_enable_ps = get_option('simple_noti_header_enable_ps');
        $simple_noti_header_color_ps = get_option('simple_noti_header_color_ps');

        $simple_noti_footer_enable_ps = get_option('simple_noti_footer_enable_ps');
        $simple_noti_footer_color_ps = get_option('simple_noti_footer_color_ps');

        $content  = '<style type="text/css">';
        $content .= "\n";

        //HEADER NOTI BAR CSS
        if (isset($simple_noti_header_enable_ps) && !empty($simple_noti_header_enable_ps) && $simple_noti_header_enable_ps == 1){
                $content .= '#simple-noti-header {display:block; position: absolute; top:0; left:0; z-index: 999999; width: 100%; height: 23px;';
        }
        else{
            $content .= '#simple-noti-header {display:none;}';
        }
        if (isset($simple_noti_header_color_ps) && !empty($simple_noti_header_color_ps)){
            $content .= sprintf(' background-color: %s;}' , $simple_noti_header_color_ps) ;
        }
        $content .= ' #simple-noti-header p{color: #f2f2f2; text-decoration: none;font-size: 17px;text-align: center;}';

        //FOOTER NOTI BAR CSS
        if (isset($simple_noti_footer_enable_ps) && !empty($simple_noti_footer_enable_ps) && $simple_noti_footer_enable_ps == 1){
            $content .= '#simple-noti-footer {display:block; bottom:0; left:0; z-index: 999999; width: 100%;';
        }
        else{
            $content .= '#simple-noti-footer {display:none;}';
        }

        if (isset($simple_noti_footer_color_ps) && !empty($simple_noti_footer_color_ps)){
            $content .= sprintf(' background-color: %s;}' , $simple_noti_footer_color_ps) ;
        }
        $content .= ' #simple-noti-footer p{color: #f2f2f2; text-decoration: none;font-size: 17px;text-align: center; margin: auto;}';


        $content .= '</style>';
        $content .= "\n";

        echo $content;

    }


}
