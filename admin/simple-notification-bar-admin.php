<?php

class Simple_Notification_Bar_Admin_PS {

    private static $instance;

    private $page_title = "Simple Notification Bar";
    private $menu_title = "Simple Noti Bar";
    private $menu_slug = "simple-notification-bar";
    private $plugin_capability = "manage_options"; //user access
    private $plugin_icon_url = "dashicons-bell";
    private $plugin_position = 100;

    private $form_structure = null;
    private $option_group = "simple-notification-option-group"; //group name in settings_fields()

    public static function get_instance(){
        // If the single instance hasn't been set, set it now.
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_menu_admin_panel' ) );
        add_action( 'admin_init', array( $this, 'simple_notification_bar_register_options' ) );

    }

    public function add_menu_admin_panel() {

        add_menu_page(
            $this->page_title,
            $this->menu_title,
            $this->plugin_capability, //user access
            $this->menu_slug, //menu slug
            array($this,'simple_notification_display_options_page'), //Optional. This callback outputs the content of the page associated with this menu item.
            $this->plugin_icon_url,
            $this->plugin_position
        );

    }

    public function simple_notification_display_options_page(){
        include_once ('views/simple-notification-bar-admin-template.php');
    }

    public function simple_notification_bar_register_options(){

        $title = null;
        $html = null;

        $this->form_structure = array(
            'Simple Bar Notification' => array(
                'headline' => 'Simple Bar Notification',
                'description' => 'You can edit your header or footer notification bar.',
                'options' => array(
                    'simple_noti_header_text_ps' => array(
                        'type'    => 'text',
                        'title'   => esc_html__( 'Header Notification Text', $this->menu_slug ),
                    ),
                    'simple_noti_header_color_ps' => array(
                        'type'    => 'color',
                        'title'   => esc_html__( 'Header Notification Background Color', $this->menu_slug ),
                    ),
                    'simple_noti_header_enable_ps' => array(
                        'type'    => 'checkbox',
                        'title'   => esc_html__( 'Header Notification Enable', $this->menu_slug ),
                    ),
                    'simple_noti_footer_text_ps' => array(
                        'type'    => 'text',
                        'title'   => esc_html__( 'Footer Notification Text', $this->menu_slug  ),
                    ),
                    'simple_noti_footer_color_ps' => array(
                        'type'    => 'color',
                        'title'   => esc_html__( 'Footer Notification Background Color', $this->menu_slug  ),
                    ),
                    'simple_noti_footer_enable_ps' => array(
                        'type'    => 'checkbox',
                        'title'   => esc_html__( 'Footer Notification Enable', $this->menu_slug  ),
                    )
                )
            )
        );

        foreach ( $this->form_structure as $section_key => $section_values ) {
            //admin sayfası için yeni bir setting section ekleriz.
            add_settings_section(
                // group name in settings_fields()
                $this->option_group,
                $this->form_structure[ $section_key ][ 'headline' ], //title
                array( $this, 'print_section_header' ),
                $this->menu_slug//page (display menu slug),
            );

            foreach ($section_values[ 'options' ] as $option_name => $option_values){
                $option_name_db = get_option($option_name);
                switch ( $option_values[ 'type' ] ) {
                    case 'color':
                        $title = sprintf( '<label for="%s">%s</label>', $option_name, $option_values[ 'title' ] );
                        $value = isset($option_name_db) && $option_name_db!='' ? esc_attr( get_option($option_name) ) : ' ';
                        $html = sprintf( '<input type="color" id="%s" name="%s" value="%s">', $option_name, $option_name, $value );
                        break;
                    case 'checkbox':
                        $title = sprintf( '<label for="%s">%s</label>', $option_name, $option_values[ 'title' ] );
                        $checked = isset($option_name_db) && $option_name_db!='' ? 'checked' : esc_attr( get_option($option_name) );
                        $html = sprintf( '<input type="checkbox" id="%s" name="%s" value="1" %s>', $option_name, $option_name, $checked );
                        break;
                    // else text field
                    default:
                        $title = sprintf( '<label for="%s">%s</label>', $option_name, $option_values[ 'title' ] );
                        $value = isset($option_name_db) && $option_name_db!='' ? esc_attr( get_option($option_name) ) : '';
                        $html = sprintf( '<input style="width: %s" type="text" id="%s" name="%s" value="%s">', "70%" ,$option_name, $option_name, $value );
                } // end switch()

                // register the option, Add a new field to a section of a settings page.
                add_settings_field(
                    // form field name for use in the 'id' attribute of tags
                    $option_name,
                    // title of the form field
                    $title,
                    // callback function to print the form field
                    array( $this, 'print_option' ),
                    // menu page on which to display this field for do_settings_section()
                    $this->menu_slug,
                    // section where the form field appears like settings_fields()
                    $this->option_group,
                    // arguments passed to the callback function
                    array(
                        'html' => $html,
                    )
                );

                //TODO: register ederken alanları sanitize etmeliyim.

                // finally register all options. They will be stored in the database in the wp_options table under the options name.
                register_setting(
                    // group name in settings_fields()
                    $this->option_group,
                    // name of the option to save in the db
                    $option_name,
                    array( $this, 'sanitize_options' ));
            }
        }
    }

    public function print_section_header() {
        printf( "<p>%s</p>\n", $this->form_structure[ 'Simple Bar Notification' ][ 'description' ] );
    }

    public function print_option ( $args ) {
        echo $args[ 'html' ];
    }

    public function sanitize_options($input) {
        $input = sanitize_text_field($input);
        return $input;
    } // end sanitize_options()

}