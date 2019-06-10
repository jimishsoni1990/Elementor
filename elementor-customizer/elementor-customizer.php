<?php
/*
   Plugin Name: Elementor Customizer
   Plugin URI: http://themeofy.net/
   description: Customize elementors default font size and colors
   Version: 1.0
   Author: Jimish Soni
   Author URI: http://themeofy.net/
   Text Domain: elementor_customizer
   License: GPL2
*/
class Elementor_Custom_CSS {
   /**
    * This hooks into 'customize_register' (available as of WP 3.4) and allows
    * you to add new sections and controls to the Theme Customize screen.
    * 
    * Note: To enable instant preview, we have to actually write a bit of custom
    * javascript. See live_preview() for more.
    *  
    * @see add_action('customize_register',$func)
    * @param \WP_Customize_Manager $wp_customize
    * @link http://ottopress.com/2012/how-to-leverage-the-theme-customizer-in-your-own-themes/
    * @since 1.0
    */
   public static function register ( $wp_customize ) {

      //0. Define a Panel to the Theme Customizer
      $wp_customize->add_panel( 'elementor_custom_css_panel_buttons', array(
         'priority'       => 500,
         'theme_supports' => '',
         'title'          => __( 'EC - Buttons', 'elementor_customizer' ),
         'description'    => __( '', 'elementor_customizer' ),
      ) );

      $wp_customize->add_panel( 'elementor_custom_css_panel_typography', array(
         'priority'       => 500,
         'theme_supports' => '',
         'title'          => __( 'EC - Typography', 'elementor_customizer' ),
         'description'    => __( '', 'elementor_customizer' ),
      ) );

     self::add_button_settings( $wp_customize );
     self::add_typography_settings( $wp_customize );

   }

   /**
    * This will output the custom WordPress settings to the live theme's WP head.
    * 
    * Used by hook: 'wp_head'
    * 
    * @see add_action('wp_head',$func)
    * @since elementor_customizer 1.0
    */
   public static function header_output() {
      ?>
      <!--Elementor Customizer CSS--> 
      <style type="text/css">
         <?php 

            $selecotrs = array(
              '.elementor-element.elementor-button-info .elementor-button' => array(
                                                                                'background-color' => 'ec_button_info_bgcolor',
                                                                                'color' => 'ec_button_info_textcolor'
                                                                              ),
              '.elementor-element.elementor-button-success .elementor-button' => array(
                                                                                  'background-color' => 'ec_button_success_bgcolor',
                                                                                  'color' => 'ec_button_success_textcolor'
                                                                                ),
              '.elementor-element.elementor-button-danger .elementor-button' => array(
                                                                                  'background-color' => 'ec_button_danger_bgcolor',
                                                                                  'color' => 'ec_button_danger_textcolor'
                                                                                ),
              '.elementor-element.elementor-button-warning .elementor-button' => array(
                                                                                  'background-color' => 'ec_button_warning_bgcolor',
                                                                                  'color' => 'ec_button_warning_textcolor'
                                                                                ),

              '.elementor-button.elementor-size-xs' => array(
                                                          'font-size'     => 'ec_button_xs_font_size',
                                                          'padding'       => 'ec_button_xs_padding',
                                                          'border-radius' => 'ec_button_xs_border_radius'
                                                        ),

              '.elementor-button.elementor-size-sm' => array(
                                                          'font-size'     => 'ec_button_sm_font_size',
                                                          'padding'       => 'ec_button_sm_padding',
                                                          'border-radius' => 'ec_button_sm_border_radius'
                                                        ),

              '.elementor-button.elementor-size-md' => array(
                                                          'font-size'     => 'ec_button_md_font_size',
                                                          'padding'       => 'ec_button_md_padding',
                                                          'border-radius' => 'ec_button_md_border_radius'
                                                        ),

              '.elementor-button.elementor-size-lg' => array(
                                                          'font-size'     => 'ec_button_lg_font_size',
                                                          'padding'       => 'ec_button_lg_padding',
                                                          'border-radius' => 'ec_button_lg_border_radius'
                                                        ),

              '.elementor-button.elementor-size-xl' => array(
                                                          'font-size'     => 'ec_button_xl_font_size',
                                                          'padding'       => 'ec_button_xl_padding',
                                                          'border-radius' => 'ec_button_xl_border_radius'
                                                        ),

              'h1, .elementor-widget-heading h1.elementor-heading-title' => array(
                                                                              'font-size' => 'ec_typography_h1_font_size',
                                                                              'line-height' => 'ec_typography_h1_line_height',
                                                                            ),
              'h2, .elementor-widget-heading h2.elementor-heading-title' => array(
                                                                              'font-size' => 'ec_typography_h2_font_size',
                                                                              'line-height' => 'ec_typography_h2_line_height',
                                                                            ),
              'h3, .elementor-widget-heading h3.elementor-heading-title' => array(
                                                                              'font-size' => 'ec_typography_h3_font_size',
                                                                              'line-height' => 'ec_typography_h3_line_height',
                                                                            ),
              'h4, .elementor-widget-heading h4.elementor-heading-title' => array(
                                                                              'font-size' => 'ec_typography_h4_font_size',
                                                                              'line-height' => 'ec_typography_h4_line_height',
                                                                            ),
              'h5, .elementor-widget-heading h5.elementor-heading-title' => array(
                                                                              'font-size' => 'ec_typography_h5_font_size',
                                                                              'line-height' => 'ec_typography_h5_line_height',
                                                                            ),
              'h6, .elementor-widget-heading h6.elementor-heading-title' => array(
                                                                              'font-size' => 'ec_typography_h6_font_size',
                                                                              'line-height' => 'ec_typography_h6_line_height',
                                                                            )
            );

            foreach( $selecotrs as $selector => $fields ){
              self::generate_css( $selector, $fields );
            }
         ?> 
      </style> 
      <!--/Elementor Customizer CSS-->
      <?php
   }
   
   /**
    * This outputs the javascript needed to automate the live settings preview.
    * Also keep in mind that this function isn't necessary unless your settings 
    * are using 'transport'=>'postMessage' instead of the default 'transport'
    * => 'refresh'
    * 
    * Used by hook: 'customize_preview_init'
    * 
    * @see add_action('customize_preview_init',$func)
    * @since elementor_customizer 1.0
    */
   public static function live_preview() {
      wp_enqueue_script( 
           'elementor_customizer-themecustomizer', // Give the script a unique ID
           get_template_directory_uri() . '/assets/js/theme-customizer.js', // Define the path to the JS file
           array(  'jquery', 'customize-preview' ), // Define dependencies
           '', // Define a version (optional) 
           true // Specify whether to put in footer (leave this true)
      );
   }

    /**
     * This will generate a line of CSS for use in header output. If the setting
     * ($mod_name) has no defined value, the CSS will not be output.
     * 
     * @uses get_theme_mod()
     * @param string $selector CSS selector
     * @param string $style The name of the CSS *property* to modify
     * @param string $mod_name The name of the 'theme_mod' option to fetch
     * @param string $prefix Optional. Anything that needs to be output before the CSS property
     * @param string $postfix Optional. Anything that needs to be output after the CSS property
     * @param bool $echo Optional. Whether to print directly to the page (default: true).
     * @return string Returns a single line of CSS with selectors and a property.
     * @since elementor_customizer 1.0
     */
    public static function generate_css( $selector, $properties = array(), $prefix='', $postfix='', $echo=true ) {
      $return = '';
      $values = array();
      $i = 0;
      foreach ($properties as $property => $field) {
        $field_value = get_theme_mod(trim($field)); 
        //echo '['.$property.':'.$prefix.$field_value.$postfix.']<br/>';
        if ( ! empty( $field_value ) ) {
          $css[] =  $property.':'.$prefix.$field_value.$postfix;
        }
      }

      if ( ! empty( $css ) ) {
        //print_r($values);
        $return = sprintf('%s { %s }',
            $selector,
            implode(';', $css)
         );
        if ( $echo ) {
            echo $return;
        }
      }

      return $return;
    }


    public function add_button_settings( $wp_customize ){

      //1. Define a new section (if desired) to the Theme Customizer
      $wp_customize->add_section( 'ec_button_info', 
         array(
            'title'       => __( 'Info', 'elementor_customizer' ),
            'priority'    => 35, 
            'capability'  => 'edit_theme_options',
            'panel'       => 'elementor_custom_css_panel_buttons',
         ) 
      );

      $wp_customize->add_section( 'ec_button_success', 
         array(
            'title'       => __( 'Success', 'elementor_customizer' ),
            'priority'    => 35, 
            'capability'  => 'edit_theme_options',
            'panel'       => 'elementor_custom_css_panel_buttons',
         ) 
      );

      $wp_customize->add_section( 'ec_button_danger', 
         array(
            'title'       => __( 'Danger', 'elementor_customizer' ),
            'priority'    => 35, 
            'capability'  => 'edit_theme_options',
            'panel'       => 'elementor_custom_css_panel_buttons',
         ) 
      );

      $wp_customize->add_section( 'ec_button_warning', 
         array(
            'title'       => __( 'Warning', 'elementor_customizer' ),
            'priority'    => 35, 
            'capability'  => 'edit_theme_options',
            'panel'       => 'elementor_custom_css_panel_buttons',
         ) 
      );

      $wp_customize->add_section( 'ec_button_xs', 
         array(
            'title'       => __( 'Extra small', 'elementor_customizer' ),
            'priority'    => 35, 
            'capability'  => 'edit_theme_options',
            'panel'       => 'elementor_custom_css_panel_buttons',
         ) 
      );

      $wp_customize->add_section( 'ec_button_xs', 
         array(
            'title'       => __( 'Extra small', 'elementor_customizer' ),
            'priority'    => 35, 
            'capability'  => 'edit_theme_options',
            'panel'       => 'elementor_custom_css_panel_buttons',
         ) 
      );

      $wp_customize->add_section( 'ec_button_sm', 
         array(
            'title'       => __( 'Small', 'elementor_customizer' ),
            'priority'    => 35, 
            'capability'  => 'edit_theme_options',
            'panel'       => 'elementor_custom_css_panel_buttons',
         ) 
      );

      $wp_customize->add_section( 'ec_button_md', 
         array(
            'title'       => __( 'Medium', 'elementor_customizer' ),
            'priority'    => 35, 
            'capability'  => 'edit_theme_options',
            'panel'       => 'elementor_custom_css_panel_buttons',
         ) 
      );

      $wp_customize->add_section( 'ec_button_lg', 
         array(
            'title'       => __( 'Large', 'elementor_customizer' ),
            'priority'    => 35, 
            'capability'  => 'edit_theme_options',
            'panel'       => 'elementor_custom_css_panel_buttons',
         ) 
      );

      $wp_customize->add_section( 'ec_button_xl', 
         array(
            'title'       => __( 'Extra large', 'elementor_customizer' ),
            'priority'    => 35, 
            'capability'  => 'edit_theme_options',
            'panel'       => 'elementor_custom_css_panel_buttons',
         ) 
      );

      //2. Register new settings to the WP database...
      $wp_customize->add_setting( 'ec_button_info_bgcolor', array() );
      $wp_customize->add_setting( 'ec_button_info_textcolor', array() );
      $wp_customize->add_setting( 'ec_button_success_bgcolor', array() );
      $wp_customize->add_setting( 'ec_button_success_textcolor', array() );
      $wp_customize->add_setting( 'ec_button_danger_bgcolor', array() );
      $wp_customize->add_setting( 'ec_button_danger_textcolor', array() );
      $wp_customize->add_setting( 'ec_button_warning_bgcolor', array() );
      $wp_customize->add_setting( 'ec_button_warning_textcolor', array() );

      $wp_customize->add_setting( 'ec_button_xs_font_size', array() );
      $wp_customize->add_setting( 'ec_button_xs_padding', array() );
      $wp_customize->add_setting( 'ec_button_xs_border_radius', array() );

      $wp_customize->add_setting( 'ec_button_sm_font_size', array() );
      $wp_customize->add_setting( 'ec_button_sm_padding', array() );
      $wp_customize->add_setting( 'ec_button_sm_border_radius', array() );

      $wp_customize->add_setting( 'ec_button_md_font_size', array() );
      $wp_customize->add_setting( 'ec_button_md_padding', array() );
      $wp_customize->add_setting( 'ec_button_md_border_radius', array() );

      $wp_customize->add_setting( 'ec_button_lg_font_size', array() );
      $wp_customize->add_setting( 'ec_button_lg_padding', array() );
      $wp_customize->add_setting( 'ec_button_lg_border_radius', array() );

      $wp_customize->add_setting( 'ec_button_xl_font_size', array() );
      $wp_customize->add_setting( 'ec_button_xl_padding', array() );
      $wp_customize->add_setting( 'ec_button_xl_border_radius', array() );


      //3. Finally, we define the control itself (which links a setting to a section and renders the HTML controls)...
      $wp_customize->add_control( new WP_Customize_Color_Control(
         $wp_customize,
         'ec_button_info_bgcolor',
         array(
            'label'      => __( 'Background Color', 'elementor_customizer' ),
            'settings'   => 'ec_button_info_bgcolor', 
            'priority'   => 10,
            'section'    => 'ec_button_info',
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Color_Control(
         $wp_customize,
         'ec_button_info_textcolor',
         array(
            'label'      => __( 'Text Color', 'elementor_customizer' ),
            'settings'   => 'ec_button_info_textcolor', 
            'priority'   => 10,
            'section'    => 'ec_button_info',
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Color_Control(
         $wp_customize,
         'ec_button_success_bgcolor',
         array(
            'label'      => __( 'Background Color', 'elementor_customizer' ),
            'settings'   => 'ec_button_success_bgcolor', 
            'priority'   => 10,
            'section'    => 'ec_button_success',
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Color_Control(
         $wp_customize,
         'ec_button_success_textcolor',
         array(
            'label'      => __( 'Text Color', 'elementor_customizer' ),
            'settings'   => 'ec_button_success_textcolor', 
            'priority'   => 10,
            'section'    => 'ec_button_success',
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Color_Control(
         $wp_customize,
         'ec_button_danger_bgcolor',
         array(
            'label'      => __( 'Background Color', 'elementor_customizer' ),
            'settings'   => 'ec_button_danger_bgcolor', 
            'priority'   => 10,
            'section'    => 'ec_button_danger',
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Color_Control(
         $wp_customize,
         'ec_button_danger_textcolor',
         array(
            'label'      => __( 'Text Color', 'elementor_customizer' ),
            'settings'   => 'ec_button_danger_textcolor', 
            'priority'   => 10,
            'section'    => 'ec_button_danger',
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Color_Control(
         $wp_customize,
         'ec_button_warning_bgcolor',
         array(
            'label'      => __( 'Background Color', 'elementor_customizer' ),
            'settings'   => 'ec_button_warning_bgcolor', 
            'priority'   => 10,
            'section'    => 'ec_button_warning',
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Color_Control(
         $wp_customize,
         'ec_button_warning_textcolor',
         array(
            'label'      => __( 'Text Color', 'elementor_customizer' ),
            'settings'   => 'ec_button_warning_textcolor', 
            'priority'   => 10,
            'section'    => 'ec_button_warning',
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Control(
         $wp_customize,
         'ec_button_xs_font_size',
         array(
            'label'      => __( 'Font size', 'elementor_customizer' ),
            'settings'   => 'ec_button_xs_font_size', 
            'priority'   => 10,
            'section'    => 'ec_button_xs',
            'type'       => 'text',
            'input_attrs' => array(
                'placeholder' => __( '13px', 'elementor_customizer' ),
            )
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Control(
         $wp_customize,
         'ec_button_xs_padding',
         array(
            'label'      => __( 'Padding', 'elementor_customizer' ),
            'settings'   => 'ec_button_xs_padding', 
            'priority'   => 10,
            'section'    => 'ec_button_xs',
            'type'       => 'text',
            'input_attrs' => array(
                'placeholder' => __( '10px 20px', 'elementor_customizer' ),
            )
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Control(
         $wp_customize,
         'ec_button_xs_border_radius',
         array(
            'label'      => __( 'Border radius', 'elementor_customizer' ),
            'settings'   => 'ec_button_xs_border_radius', 
            'priority'   => 10,
            'section'    => 'ec_button_xs',
            'type'       => 'text',
            'input_attrs' => array(
                'placeholder' => __( '2px', 'elementor_customizer' ),
            )
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Control(
         $wp_customize,
         'ec_button_sm_font_size',
         array(
            'label'      => __( 'Font size', 'elementor_customizer' ),
            'settings'   => 'ec_button_sm_font_size', 
            'priority'   => 10,
            'section'    => 'ec_button_sm',
            'type'       => 'text',
            'input_attrs' => array(
                'placeholder' => __( '15px', 'elementor_customizer' ),
            )
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Control(
         $wp_customize,
         'ec_button_sm_padding',
         array(
            'label'      => __( 'Padding', 'elementor_customizer' ),
            'settings'   => 'ec_button_sm_padding', 
            'priority'   => 10,
            'section'    => 'ec_button_sm',
            'type'       => 'text',
            'input_attrs' => array(
                'placeholder' => __( '12px 24px', 'elementor_customizer' ),
            )
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Control(
         $wp_customize,
         'ec_button_sm_border_radius',
         array(
            'label'      => __( 'Border radius', 'elementor_customizer' ),
            'settings'   => 'ec_button_sm_border_radius', 
            'priority'   => 10,
            'section'    => 'ec_button_sm',
            'type'       => 'text',
            'input_attrs' => array(
                'placeholder' => __( '3px', 'elementor_customizer' ),
            )
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Control(
         $wp_customize,
         'ec_button_md_font_size',
         array(
            'label'      => __( 'Font size', 'elementor_customizer' ),
            'settings'   => 'ec_button_md_font_size', 
            'priority'   => 10,
            'section'    => 'ec_button_md',
            'type'       => 'text',
            'input_attrs' => array(
                'placeholder' => __( '16px', 'elementor_customizer' ),
            )
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Control(
         $wp_customize,
         'ec_button_md_padding',
         array(
            'label'      => __( 'Padding', 'elementor_customizer' ),
            'settings'   => 'ec_button_md_padding', 
            'priority'   => 10,
            'section'    => 'ec_button_md',
            'type'       => 'text',
            'input_attrs' => array(
                'placeholder' => __( '15px 30px', 'elementor_customizer' ),
            )
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Control(
         $wp_customize,
         'ec_button_md_border_radius',
         array(
            'label'      => __( 'Border radius', 'elementor_customizer' ),
            'settings'   => 'ec_button_md_border_radius', 
            'priority'   => 10,
            'section'    => 'ec_button_md',
            'type'       => 'text',
            'input_attrs' => array(
                'placeholder' => __( '4px', 'elementor_customizer' ),
            )
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Control(
         $wp_customize,
         'ec_button_lg_font_size',
         array(
            'label'      => __( 'Font size', 'elementor_customizer' ),
            'settings'   => 'ec_button_lg_font_size', 
            'priority'   => 10,
            'section'    => 'ec_button_lg',
            'type'       => 'text',
            'input_attrs' => array(
                'placeholder' => __( '18px', 'elementor_customizer' ),
            )
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Control(
         $wp_customize,
         'ec_button_lg_padding',
         array(
            'label'      => __( 'Padding', 'elementor_customizer' ),
            'settings'   => 'ec_button_lg_padding', 
            'priority'   => 10,
            'section'    => 'ec_button_lg',
            'type'       => 'text',
            'input_attrs' => array(
                'placeholder' => __( '20px 40px', 'elementor_customizer' ),
            )
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Control(
         $wp_customize,
         'ec_button_lg_border_radius',
         array(
            'label'      => __( 'Border radius', 'elementor_customizer' ),
            'settings'   => 'ec_button_lg_border_radius', 
            'priority'   => 10,
            'section'    => 'ec_button_lg',
            'type'       => 'text',
            'input_attrs' => array(
                'placeholder' => __( '5px', 'elementor_customizer' ),
            )
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Control(
         $wp_customize,
         'ec_button_xl_font_size',
         array(
            'label'      => __( 'Font size', 'elementor_customizer' ),
            'settings'   => 'ec_button_xl_font_size', 
            'priority'   => 10,
            'section'    => 'ec_button_xl',
            'type'       => 'text',
            'input_attrs' => array(
                'placeholder' => __( '20px', 'elementor_customizer' ),
            )
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Control(
         $wp_customize,
         'ec_button_xl_padding',
         array(
            'label'      => __( 'Padding', 'elementor_customizer' ),
            'settings'   => 'ec_button_xl_padding', 
            'priority'   => 10,
            'section'    => 'ec_button_xl',
            'type'       => 'text',
            'input_attrs' => array(
                'placeholder' => __( '25px 50px', 'elementor_customizer' ),
            )
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Control(
         $wp_customize,
         'ec_button_xl_border_radius',
         array(
            'label'      => __( 'Border radius', 'elementor_customizer' ),
            'settings'   => 'ec_button_xl_border_radius', 
            'priority'   => 10,
            'section'    => 'ec_button_xl',
            'type'       => 'text',
            'input_attrs' => array(
                'placeholder' => __( '6px', 'elementor_customizer' ),
            )
         ) 
      ) );

    }

    public function add_typography_settings($wp_customize)
    {
       //1. Define a new section (if desired) to the Theme Customizer
      $wp_customize->add_section( 'ec_typography_h1', 
         array(
            'title'       => __( 'H1', 'elementor_customizer' ),
            'priority'    => 35, 
            'capability'  => 'edit_theme_options',
            'panel'       => 'elementor_custom_css_panel_typography',
         ) 
      );

      $wp_customize->add_section( 'ec_typography_h2', 
         array(
            'title'       => __( 'H2', 'elementor_customizer' ),
            'priority'    => 35, 
            'capability'  => 'edit_theme_options',
            'panel'       => 'elementor_custom_css_panel_typography',
         ) 
      );

      $wp_customize->add_section( 'ec_typography_h3', 
         array(
            'title'       => __( 'H3', 'elementor_customizer' ),
            'priority'    => 35, 
            'capability'  => 'edit_theme_options',
            'panel'       => 'elementor_custom_css_panel_typography',
         ) 
      );

      $wp_customize->add_section( 'ec_typography_h4', 
         array(
            'title'       => __( 'H4', 'elementor_customizer' ),
            'priority'    => 35, 
            'capability'  => 'edit_theme_options',
            'panel'       => 'elementor_custom_css_panel_typography',
         ) 
      );

      $wp_customize->add_section( 'ec_typography_h5', 
         array(
            'title'       => __( 'H5', 'elementor_customizer' ),
            'priority'    => 35, 
            'capability'  => 'edit_theme_options',
            'panel'       => 'elementor_custom_css_panel_typography',
         ) 
      );

      $wp_customize->add_section( 'ec_typography_h5', 
         array(
            'title'       => __( 'H5', 'elementor_customizer' ),
            'priority'    => 35, 
            'capability'  => 'edit_theme_options',
            'panel'       => 'elementor_custom_css_panel_typography',
         ) 
      );

      //2. Register new settings to the WP database...
      $wp_customize->add_setting( 'ec_typography_h1_font_size', array() );
      $wp_customize->add_setting( 'ec_typography_h1_line_height', array() );
      $wp_customize->add_setting( 'ec_typography_h2_font_size', array() );
      $wp_customize->add_setting( 'ec_typography_h2_line_height', array() );
      $wp_customize->add_setting( 'ec_typography_h3_font_size', array() );
      $wp_customize->add_setting( 'ec_typography_h3_line_height', array() );
      $wp_customize->add_setting( 'ec_typography_h4_font_size', array() );
      $wp_customize->add_setting( 'ec_typography_h4_line_height', array() );
      $wp_customize->add_setting( 'ec_typography_h5_font_size', array() );
      $wp_customize->add_setting( 'ec_typography_h5_line_height', array() );
      $wp_customize->add_setting( 'ec_typography_h6_font_size', array() );
      $wp_customize->add_setting( 'ec_typography_h6_line_height', array() );
      
            
      //3. Finally, we define the control itself (which links a setting to a section and renders the HTML controls)...
      $wp_customize->add_control( new WP_Customize_Control(
         $wp_customize,
         'ec_typography_h1_font_size',
         array(
            'label'      => __( 'Font Size', 'elementor_customizer' ),
            'settings'   => 'ec_typography_h1_font_size', 
            'priority'   => 10,
            'section'    => 'ec_typography_h1',
            'type'       => 'text'
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Control(
         $wp_customize,
         'ec_typography_h1_line_height',
         array(
            'label'      => __( 'Line Height', 'elementor_customizer' ),
            'settings'   => 'ec_typography_h1_line_height', 
            'priority'   => 10,
            'section'    => 'ec_typography_h1',
            'type'       => 'text'
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Control(
         $wp_customize,
         'ec_typography_h2_font_size',
         array(
            'label'      => __( 'Font Size', 'elementor_customizer' ),
            'settings'   => 'ec_typography_h2_font_size', 
            'priority'   => 10,
            'section'    => 'ec_typography_h2',
            'type'       => 'text'
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Control(
         $wp_customize,
         'ec_typography_h2_line_height',
         array(
            'label'      => __( 'Line Height', 'elementor_customizer' ),
            'settings'   => 'ec_typography_h2_line_height', 
            'priority'   => 10,
            'section'    => 'ec_typography_h2',
            'type'       => 'text'
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Control(
         $wp_customize,
         'ec_typography_h3_font_size',
         array(
            'label'      => __( 'Font Size', 'elementor_customizer' ),
            'settings'   => 'ec_typography_h3_font_size', 
            'priority'   => 10,
            'section'    => 'ec_typography_h3',
            'type'       => 'text'
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Control(
         $wp_customize,
         'ec_typography_h3_line_height',
         array(
            'label'      => __( 'Line Height', 'elementor_customizer' ),
            'settings'   => 'ec_typography_h3_line_height', 
            'priority'   => 10,
            'section'    => 'ec_typography_h3',
            'type'       => 'text'
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Control(
         $wp_customize,
         'ec_typography_h4_font_size',
         array(
            'label'      => __( 'Font Size', 'elementor_customizer' ),
            'settings'   => 'ec_typography_h4_font_size', 
            'priority'   => 10,
            'section'    => 'ec_typography_h4',
            'type'       => 'text'
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Control(
         $wp_customize,
         'ec_typography_h4_line_height',
         array(
            'label'      => __( 'Line Height', 'elementor_customizer' ),
            'settings'   => 'ec_typography_h4_line_height', 
            'priority'   => 10,
            'section'    => 'ec_typography_h4',
            'type'       => 'text'
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Control(
         $wp_customize,
         'ec_typography_h5_font_size',
         array(
            'label'      => __( 'Font Size', 'elementor_customizer' ),
            'settings'   => 'ec_typography_h5_font_size', 
            'priority'   => 10,
            'section'    => 'ec_typography_h5',
            'type'       => 'text'
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Control(
         $wp_customize,
         'ec_typography_h5_line_height',
         array(
            'label'      => __( 'Line Height', 'elementor_customizer' ),
            'settings'   => 'ec_typography_h5_line_height', 
            'priority'   => 10,
            'section'    => 'ec_typography_h5',
            'type'       => 'text'
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Control(
         $wp_customize,
         'ec_typography_h6_font_size',
         array(
            'label'      => __( 'Font Size', 'elementor_customizer' ),
            'settings'   => 'ec_typography_h6_font_size', 
            'priority'   => 10,
            'section'    => 'ec_typography_h6',
            'type'       => 'text'
         ) 
      ) );

      $wp_customize->add_control( new WP_Customize_Control(
         $wp_customize,
         'ec_typography_h6_line_height',
         array(
            'label'      => __( 'Line Height', 'elementor_customizer' ),
            'settings'   => 'ec_typography_h6_line_height', 
            'priority'   => 10,
            'section'    => 'ec_typography_h6',
            'type'       => 'text'
         ) 
      ) );

    }
}

// Setup the Theme Customizer settings and controls...
add_action( 'customize_register' , array( 'Elementor_Custom_CSS' , 'register' ) );

// Output custom CSS to live site
add_action( 'wp_head' , array( 'Elementor_Custom_CSS' , 'header_output' ) );