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

// only run if theme or parent theme is Twenty Fourteen
if ( ! substr( get_template_directory_uri(), -14 ) === 'twentyfourteen' ) {
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
	//wp_add_inline_style( 'fourteen-colors-mediaelements', get_theme_mod( 'fourteen_colors_css' ) );
}
add_action( 'wp_enqueue_scripts', 'fourteen_colors_styles' );

function fourteen_colors_print_output() {
	echo '<style id="fourteen-colors" type="text/css">' . get_theme_mod( 'fourteen_colors_css' ) . '</style>';
}
add_action( 'wp_head', 'fourteen_colors_print_output' );


// Temporary output of color contrast information:
add_action( 'wp_footer', 'fourteen_colors_temp_footer' );
function fourteen_colors_temp_footer() {
	$contrast = get_theme_mod('contrast_color');
	$accent = get_theme_mod('accent_color');
	
	echo '<div style="position:fixed; top:0; left: 50%; margin:0 0 0 -50px; padding: 12px 16px; background: #222; color:#f33; width:200px; z-index:5;">';
	echo 'Contrast Color: ' . $contrast . '<br>';
//	echo 'Contrast Color: ' . print_r( fourteen_colors_hex2rgb( $contrast ) ) . '<br>';
//	echo 'White: ' . print_r( fourteen_colors_hex2rgb( '#fff' ) ) . '<br>';
//	echo 'Contrast Luminance: ' . fourteen_colors_relative_luminance($contrast) . '<br>';
	echo 'Accent Color: ' . $accent . '<br><br>';
//	echo 'Accent Color: ' . print_r( fourteen_colors_hex2rgb( $accent ) ) . '<br>';
//	echo 'Accent Luminance: ' . fourteen_colors_relative_luminance($accent) . '<br><br>';
	
//	echo 'White Luminance: ' . fourteen_colors_relative_luminance('#fff') . '<br>';
//	echo 'Black Luminance: ' . fourteen_colors_relative_luminance('#000') . '<br>';
//	echo 'White on Black: ' . fourteen_colors_contrast_ratio('#fff','#000') . '<br>';
//	echo 'White on White: ' . fourteen_colors_contrast_ratio('#000','#000') . '<br><br>';
	
	echo 'Contrast on White: ' . fourteen_colors_contrast_ratio($contrast,'#fff') . '<br>';
	echo 'Accent on White: ' . fourteen_colors_contrast_ratio($accent,'#fff') . '<br>';
	echo 'Contrast on Blackish: ' . fourteen_colors_contrast_ratio($contrast,'#2b2b2b') . '<br>';
	echo 'Accent on Black: ' . fourteen_colors_contrast_ratio($accent,'#000') . '<br>';
	echo 'Accent on Contrast: ' . fourteen_colors_contrast_ratio($accent,$contrast) . '<br>';
	echo '</div>';
}

/*
/**
* Outputs the custom accent color CSS for the editor.
/
function twentyfourteen_customizer_editor_styles() {
	$accent_color = get_theme_mod( 'accent_color' );
	$accent_mid = get_theme_mod( 'accent_mid' );
	echo '<style type="text/css">
		a, a:vistited {
			color: ' . $accent_color . ';
		}
		a:hover, a:focus {
			color: ' . $accent_mid . ';
		}
		::-moz-selection {
			background: ' . $accent_color . ';
		}
		::selection {
			background: ' . $accent_color . ';
		}
	</style>';
	}
add_action( 'some_action_in_tinymce_head', 'twentyfourteen_customizer_editor_styles' );
*/