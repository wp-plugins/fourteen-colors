<?php
/**
 * Plugin Name: Fourteen Colors
 * Plugin URI: http://celloexpressions.com/plugins/fourteen-colors
 * Description: Customize the colors of the Twenty Fourteen Theme, directly within the customizer.
 * Version: 0.5
 * Author: Nick Halsey
 * Author URI: http://celloexpressions.com/
 * Tags: Twenty Fourteen, Colors, Customizer, Custom Colors, Theme Colors
 * License: GPL

=====================================================================================
Copyright (C) 2013 Nick Halsey

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with WordPress; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
=====================================================================================
*/

// Only run if theme or parent theme is Twenty Fourteen.
if ( get_template() != 'twentyfourteen' ) {
	return;
}

function fourteen_colors_customize_register( $wp_customize ) {
	// Add the custom accent color setting and control.
	$wp_customize->add_setting( 'accent_color', array(
		'default'           => '#24890d',
		'sanitize_callback' => 'sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'accent_color', array(
		'label'    => __( 'Accent Color', 'fourteen_colors' ),
		'section'  => 'colors',
	) ) );

	$wp_customize->add_setting( 'contrast_color', array(
		'default'           => '#000000',
		'sanitize_callback' => 'sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'contrast_color', array(
		'label'    => __( 'Contrast Color', 'fourteen_colors' ),
		'section'  => 'colors',
	) ) );
	
	add_filter( 'theme_mod_fourteen_colors_css', 'fourteen_colors_generate_css' );
}
add_action( 'customize_register', 'fourteen_colors_customize_register' );

require( 'color-patterns.php' );

/**
 * Returns the CSS output of Fourteen Colors.
 *
 * @since Fourteen Colors 0.5
 *
 * @return string
 */
function fourteen_colors_generate_css() {
	return fourteen_colors_contrast_css() . fourteen_colors_accent_css() . fourteen_colors_general_css();
}

/**
 * Caches the CSS output of Fourteen Colors.
 *
 * @since Fourteen Colors 0.5
 *
 * @return void
 */
function fourteen_colors_rebuild_color_patterns() {
	set_theme_mod( 'fourteen_colors_css', fourteen_colors_generate_css() );
}
$fourteen_colors_theme = get_stylesheet();
add_action( "update_option_theme_mods_$fourteen_colors_theme", 'fourteen_colors_rebuild_color_patterns' );

function fourteen_colors_styles() {
	wp_enqueue_style( 'fourteen-colors-mediaelements', plugins_url( '/mediaelements-genericons.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'fourteen_colors_styles' );

function fourteen_colors_print_output() {
	echo '<style id="fourteen-colors" type="text/css">' . get_theme_mod( 'fourteen_colors_css' ) . '</style>';
}
add_action( 'wp_head', 'fourteen_colors_print_output' );