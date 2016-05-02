<?php
new theme_customizer();

class theme_customizer
{
    public function __construct()
    {
        add_action ('admin_menu', array(&$this, 'customizer_admin'));
        add_action( 'customize_register', array(&$this, 'customize_manager_oebrixton' ));
    }

    /**
     * Add the Customize link to the admin menu
     * @return void
     */
    public function customizer_admin() {
        add_theme_page( 'Customize', 'Customize', 'edit_theme_options', 'customize.php' );
    }

    /**
     * Customizer manager demo
     * @param  WP_Customizer_Manager $wp_manager
     * @return void
     */
    public function customize_manager_oebrixton( $wp_manager )
    {
        $this->logos_section( $wp_manager );
    }

    public function logos_section( $wp_manager )
    {
        $wp_manager->add_section( 'customiser_oebrixton_section', array(
            'title'          => 'OE Brixton Options',
            'priority'       => 35,
        ) );

        // WP_Customize_Image_Control add big logo
        $wp_manager->add_setting( 'logo_big_setting', array(
            'default'        => '',
        ) );

        $wp_manager->add_control( new WP_Customize_Image_Control( $wp_manager, 'logo_big_setting', array(
            'label'   => 'Main logo for Site',
            'section' => 'customiser_oebrixton_section',
            'settings'   => 'logo_big_setting',
            'priority' => 8
        ) ) );

        // WP_Customize_Image_Control add big logo
        $wp_manager->add_setting( 'logo_small_setting', array(
            'default'        => '',
        ) );

        $wp_manager->add_control( new WP_Customize_Image_Control( $wp_manager, 'logo_small_setting', array(
            'label'   => 'Small logo for Site',
            'section' => 'customiser_oebrixton_section',
            'settings'   => 'logo_small_setting',
            'priority' => 8
        ) ) );

        // Checkbox control
        $wp_manager->add_setting( 'mimify_setting', array(
            'default'        => '0',
        ) );

        $wp_manager->add_control( 'mimify_setting', array(
            'label'   => 'Mimify HTML, js and css',
            'section' => 'customiser_oebrixton_section',
            'type'    => 'checkbox',
            'priority' => 1
        ) );

}

}

?>
