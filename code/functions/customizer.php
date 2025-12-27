<?php
/**
 * Customizer functionality for Suffusion.
 *
 * @package Suffusion
 * @subpackage Functions
 */

add_action('customize_register', 'suffusion_customize_register');

/**
 * Register settings and controls for the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function suffusion_customize_register($wp_customize) {
	// Add a section for Suffusion Layout
	$wp_customize->add_section('suffusion_layout', array(
		'title'    => __('Suffusion Layout', 'suffusion'),
		'priority' => 30,
	));

	// Example Setting: Body Background Color
	$wp_customize->add_setting('suffusion_options[suf_body_background_color]', array(
		'default'           => '#444444',
		'type'              => 'option',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'suf_body_background_color', array(
		'label'    => __('Background Color', 'suffusion'),
		'section'  => 'colors',
		'settings' => 'suffusion_options[suf_body_background_color]',
	)));

	// Example Setting: Wrapper Width
	$wp_customize->add_setting('suffusion_options[suf_wrapper_width]', array(
		'default'           => '1000',
		'type'              => 'option',
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control('suf_wrapper_width', array(
		'label'    => __('Wrapper Width (px)', 'suffusion'),
		'section'  => 'suffusion_layout',
		'settings' => 'suffusion_options[suf_wrapper_width]',
		'type'     => 'text',
	));
}
