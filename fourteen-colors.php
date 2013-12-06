<?php
/**
 * Plugin Name: Fourteen Colors
 * Plugin URI: http://celloexpressions.com/plugins/fourteen-colors
 * Description: Customize the colors of the Twenty Fourteen Theme, directly within the customizer.
 * Version: 0.1
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

/*// only run if theme or parent theme is Twenty fourteen
if ( substr(get_template_directory_uri(),-14) == 'twentyfourteen' ) {
	// add all of the actions and filters

	// add color picker settings to the customizer
	add_action('customize_register','fourteen_colors_customizer_actions');
	
	// add integration with header generator to customizer's header image control
	add_action('customize_render_control_header_image','b25_colors_render_content');
	
	// add plugin output to the <head>
	add_action( 'wp_head', 'fourteen_colors_css');

	// update the editor stylesheet on customizer save
	add_action('customize_save_after','fourteen_colors_regen_editor_styles');

	// add an editor stylesheet that imports the custom colors
	add_filter( 'mce_css', 'plugin_mce_css' );
}*/


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

	add_filter( 'theme_mod_accent_mid',   'fourteen_colors_accent_mid'   );
	add_filter( 'theme_mod_accent_light', 'fourteen_colors_accent_light' );
	
	$wp_customize->add_setting( 'contrast_color', array(
		'default'           => '#000000',
		'sanitize_callback' => 'sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'contrast_color', array(
		'label'    => __( 'Contrast Color', 'fourteen_colors' ),
		'section'  => 'colors',
	) ) );
}
add_action( 'customize_register', 'fourteen_colors_customize_register' );

/**
 * Tweak the brightness of a color by adjusting the RGB values by the given interval.
 *
 * Use positive values of $steps to brighten the color and negative values to darken the color.
 * All three RGB values are modified by the specified steps, within the range of 0-255. The hue
 * is generally maintained unless the number of steps causes one value to be capped at 0 or 255.
 *
 * @since Twenty Fourteen 1.0
 *
 * @param string $color The original color, in 3- or 6-digit hexadecimal form.
 * @param int $steps The number of steps to adjust the color by, in RGB units.
 * @return string $color The new color, in 6-digit hexadecimal form.
 */
function fourteen_colors_adjust_color( $color, $steps ) {
	// Convert shorthand to full hex.
	if ( strlen( $color ) == 3 ) {
		$color = str_repeat( substr( $color, 1, 1 ), 2 ) . str_repeat( substr( $color, 2, 1 ), 2 ) . str_repeat( substr( $color, 3, 1), 2 );
	}

	// Convert hex to rgb.
	$rgb = array( hexdec( substr( $color, 1, 2 ) ), hexdec( substr( $color, 3, 2 ) ), hexdec( substr( $color, 5, 2 ) ) );

	// Adjust color and switch back to 6-digit hex.
	$hex = '#';
	foreach ( $rgb as $value ) {
		$value += $steps;
		if ( $value > 255 ) {
			$value = 255;
		} elseif ( $value < 0 ) {
			$value = 0;
		}
		$hex .= str_pad( dechex( $value ), 2, '0', STR_PAD_LEFT);
	}

	return $hex;
}

/**
 * Calculate the (lightness/darkness) value of a color, to determine whether it should be
 * used with a light or dark text color.
 *
 * @since Twenty Fourteen 1.0
 *
 * @param string $color The color, in 3- or 6-digit hexadecimal form.
 * @return int $value The value of the color, in cumulative RGB units.
 */
function fourteen_colors_color_value( $color ) {
	// Convert shorthand to full hex.
	if ( strlen( $color ) == 3 ) {
		$color = str_repeat( substr( $color, 1, 1 ), 2 ) . str_repeat( substr( $color, 2, 1 ), 2 ) . str_repeat( substr( $color, 3, 1), 2 );
	}

	// Convert hex to rgb.
	$rgb = array( hexdec( substr( $color, 1, 2 ) ), hexdec( substr( $color, 3, 2 ) ), hexdec( substr( $color, 5, 2 ) ) );

	// Sum rgb values.
	$value = 0;
	foreach ( $rgb as $one_value ) {
		$value += $one_value;
	}

	return $value;
}

 /**
 * Returns a slightly lighter color than what is set as the theme's
 * accent color.
 *
 * @since Twenty Fourteen 1.0
 *
 * @return string
 */
function fourteen_colors_accent_mid() {
	return fourteen_colors_adjust_color( get_theme_mod( 'accent_color' ), 29 );
}

/**
 * Returns a lighter color than what is set as the theme's accent color.
 *
 * @since Twenty Fourteen 1.0
 *
 * @return string
 */
function fourteen_colors_accent_light() {
	return fourteen_colors_adjust_color( get_theme_mod( 'accent_color' ), 49 );
}

/**
 * Caches the generated variants of the theme's accent color.
 *
 * @since Twenty Fourteen 1.0
 *
 * @return void
 */
function fourteen_colors_rebuild_accent_colors() {
	set_theme_mod( 'accent_mid',   fourteen_colors_accent_mid()   );
	set_theme_mod( 'accent_light', fourteen_colors_accent_light() );
}
$fourteen_theme = get_stylesheet();
add_action( "update_option_theme_mods_$fourteen_theme", 'fourteen_colors_rebuild_accent_colors' );

/**
 * Output the CSS for the Contrast Color option.
 *
 * @since Fourteen Colors 0.1
 *
 * @return void
 */
function fourteen_colors_contrast_color_styles() {
	$contrast_color = get_theme_mod( 'contrast_color', '#000000' );

	// Don't do anything if the current color is the default.
	if ( '#000000' === $contrast_color ) {
		return;
	}
	
	// Add the CSS for implementing the contrast color.
	$css = '/* Custom Contrast Color */
	.site:before,
	.site-header,
	.site-footer,
	.featured-content,
	.featured-content .entry-header,
	.slider-direction-nav a {
		background-color: ' . $contrast_color . ';
	}
	
	.grid .featured-content .entry-header {
		border-color: ' . $contrast_color . ';
	}
	
	.slider-control-paging a:before {
		background-color: rgba(255,255,255,.33);
	}
	
	.hentry .mejs-mediaelement, .hentry .mejs-container .mejs-controls,
	.content-sidebar .widget_twentyfourteen_ephemera .widget-title:before {
		background: ' . $contrast_color . ';
	}
	
	.content-sidebar .widget .widget-title {
		border-top-color: ' . $contrast_color . ';
	}
	
	.hentry .mejs-controls .mejs-time-rail .mejs-time-total, .hentry .mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-total {
		background: rgba(255,255,255,.8);
	}
	';
	
	/* Adjustents to make lighter Contrast Colors looks just as good. */
	if( fourteen_colors_color_value( $contrast_color ) > 480 ) {
		$css .= '	
			#secondary,
			#secondary a,
			.site-header a,
			.site-footer,
			.site-footer a,
			.featured-content a,
			.featured-content .entry-meta,
			.slider-direction-nav a:before {
				color: #2b2b2b;
			}

			.slider-control-paging a:before {
				background-color: rgba(0,0,0,.33);
			}

			.widget-title, .widget-title a {
				color: #000;
			}

			.secondary-navigation li {
				border-color: rgba(0, 0, 0, .2);
			}

			.secondary-navigation {
				border-color: rgba(0, 0, 0, .2);
			}

			.widget input,
			.widget textarea {
				background-color: rgba(0, 0, 0, .01);
				border-color: rgba(0, 0, 0, .2);
				color: #000;
			}

			.widget input:focus, .widget textarea:focus {
				border-color: rgba(0, 0, 0, 0.4);
			}

			#supplementary + .site-info {
				border-top: 1px solid rgba(0, 0, 0, 0.2);
			}
		';
	}
	echo '<style type="text/css">'.$css.'</style>';
//	wp_add_inline_style( 'twentyfourteen-style', $css );
}
add_action( 'wp_head', 'fourteen_colors_contrast_color_styles' );
//add_action( 'wp_enqueue_scripts', 'fourteen_colors_contrast_color_styles' );

/**
 * Output the CSS for the Accent Color option.
 *
 * Accent color styles should be added after contrast color styles 
 * because these override hover states.
 *
 * @since Twenty Fourteen 1.0
 *
 * @return void
 */
function fourteen_colors_accent_color_styles() {
	$accent_color = get_theme_mod( 'accent_color', '#24890d' );

	// Don't do anything if the current color is the default.
	if ( '#24890d' === $accent_color ) {
		return;
	}

	$accent_mid   = get_theme_mod( 'accent_mid'   );
	$accent_light = get_theme_mod( 'accent_light' );

	$css = '/* Custom accent color. */
		a,
		.content-sidebar .widget a {
			color: ' . $accent_color . ';
		}

		button,
		.contributor-posts-link,
		input[type="button"],
		input[type="reset"],
		input[type="submit"],
		.search-toggle,
		.hentry .mejs-controls .mejs-time-rail .mejs-time-current,
		.widget button,
		.widget input[type="button"],
		.widget input[type="reset"],
		.widget input[type="submit"],
		.widget_calendar tbody a,
		.content-sidebar .widget input[type="button"],
		.content-sidebar .widget input[type="reset"],
		.content-sidebar .widget input[type="submit"],
		.slider-control-paging .slider-active:before,
		.slider-control-paging .slider-active:hover:before,
		.slider-direction-nav a:hover {
			background-color: ' . $accent_color . ';
		}

		::-moz-selection {
			background: ' . $accent_color . ';
		}

		::selection {
			background: ' . $accent_color . ';
		}

		.paging-navigation .page-numbers.current {
			border-color: ' .  $accent_color . ';
		}

		@media screen and (min-width: 782px) {
			.primary-navigation li:hover > a,
			.primary-navigation li.focus > a,
			.primary-navigation ul ul {
				background-color: ' . $accent_color . ';
			}
		}

		@media screen and (min-width: 1008px) {
			.secondary-navigation li:hover > a,
			.secondary-navigation li.focus > a,
			.secondary-navigation ul ul {
				background-color: ' . $accent_color . ';
			}
		}

		/* Generated "mid" variant of custom accent color. */
		button:hover,
		button:focus,
		.contributor-posts-link:hover,
		input[type="button"]:hover,
		input[type="button"]:focus,
		input[type="reset"]:hover,
		input[type="reset"]:focus,
		input[type="submit"]:hover,
		input[type="submit"]:focus,
		.search-toggle:hover,
		.search-toggle.active,
		.search-box,
		.entry-meta .tag-links a:hover,
		.widget input[type="button"]:hover,
		.widget input[type="button"]:focus,
		.widget input[type="reset"]:hover,
		.widget input[type="reset"]:focus,
		.widget input[type="submit"]:hover,
		.widget input[type="submit"]:focus,
		.widget_calendar tbody a:hover,
		.content-sidebar .widget input[type="button"]:hover,
		.content-sidebar .widget input[type="button"]:focus,
		.content-sidebar .widget input[type="reset"]:hover,
		.content-sidebar .widget input[type="reset"]:focus,
		.content-sidebar .widget input[type="submit"]:hover,
		.content-sidebar .widget input[type="submit"]:focus,
		.slider-control-paging a:hover:before {
			background-color: ' . $accent_mid . ';
		}

		a:active,
		a:hover,
		.site-navigation a:hover,
		.entry-title a:hover,
		.entry-meta a:hover,
		.cat-links a:hover,
		.entry-content .edit-link a:hover,
		.page-links a:hover,
		.post-navigation a:hover,
		.image-navigation a:hover,
		.comment-author a:hover,
		.comment-list .pingback a:hover,
		.comment-list .trackback a:hover,
		.comment-metadata a:hover,
		.comment-reply-title small a:hover,
		.widget a:hover,
		.widget-title a:hover,
		.widget_twentyfourteen_ephemera .entry-meta a:hover,
		.content-sidebar .widget a:hover,
		.content-sidebar .widget .widget-title a:hover,
		.content-sidebar .widget_twentyfourteen_ephemera .entry-meta a:hover,
		.site-info a:hover,
		.featured-content a:hover {
			color: ' . $accent_mid . ';
		}

		.page-links a:hover,
		.paging-navigation a:hover {
			border-color: ' . $accent_mid . ';
		}

		.entry-meta .tag-links a:hover:before {
			border-right-color: ' . $accent_mid . ';
		}

		@media screen and (min-width: 782px) {
			.primary-navigation ul ul a:hover,
			.primary-navigation ul ul li.focus > a {
				background-color: ' . $accent_mid . ';
			}
		}

		@media screen and (min-width: 1008px) {
			.secondary-navigation ul ul a:hover,
			.secondary-navigation ul ul li.focus > a {
				background-color: ' . $accent_mid . ';
			}
		}

		/* Generated "light" variant of custom accent color. */
		button:active,
		.contributor-posts-link:active,
		input[type="button"]:active,
		input[type="reset"]:active,
		input[type="submit"]:active,
		.widget input[type="button"]:active,
		.widget input[type="reset"]:active,
		.widget input[type="submit"]:active,
		.content-sidebar .widget input[type="button"]:active,
		.content-sidebar .widget input[type="reset"]:active,
		.content-sidebar .widget input[type="submit"]:active {
			background-color: ' . $accent_light . ';
		}

		.site-navigation .current_page_item > a,
		.site-navigation .current_page_ancestor > a,
		.site-navigation .current-menu-item > a,
		.site-navigation .current-menu-ancestor > a {
			color: ' . $accent_light . ';
		}';

	echo '<style type="text/css">'.$css.'</style>';
//	wp_add_inline_style( 'twentyfourteen-style', $css );
}
add_action( 'wp_head', 'fourteen_colors_accent_color_styles' );
//add_action( 'wp_enqueue_scripts', 'fourteen_colors_accent_color_styles' );




/*
function fourteen_colors_regen_editor_styles() {
	$file = plugin_dir_path( __FILE__ ) . 'editor-style.css';
	$data = '.post-format-quote,.post-format-status,.post-format-audio a,.post-format-video a,.post-format-audio a:hover,.post-format-video a:hover{color:'.get_theme_mod('fourteen_colors_one').'}.post-format-aside,.post-format-link{background-color:'.get_theme_mod('fourteen_colors_one').'}a:active,a:hover,.post-format-status a{color:'.get_theme_mod('fourteen_colors_two').'}.post-format-gallery,.post-format-chat{background-color:'.get_theme_mod('fourteen_colors_two').'}.post-format-audio,.post-format-video{background-color:'.get_theme_mod('fourteen_colors_three').'}a:visited,a,.post-format-quote a{color:'.get_theme_mod('fourteen_colors_four').'}::selection{background-color:'.get_theme_mod('fourteen_colors_five').';}.post-format-chat a,.post-format-gallery a{color:'.get_theme_mod('fourteen_colors_six').'}.post-format-status{background-color:'.get_theme_mod('fourteen_colors_six').'}.wp-caption .wp-caption-text,.wp-caption-dd{color:'.get_theme_mod('fourteen_colors_seven').'}.post-format-quote{background-color:'.get_theme_mod('fourteen_colors_seven').'}body{color:'.get_theme_mod('fourteen_colors_eight').'}';
	file_put_contents( $file, $data );
}

function plugin_mce_css( $mce_css ) {
	if ( ! empty( $mce_css ) )
		$mce_css .= ',';

	$mce_css .= plugins_url( 'editor-style.css', __FILE__ );

	return $mce_css;
}*/