<?php
/**
 * Plugin Name: Fourteen Colors
 * Plugin URI: http://celloexpressions.com/plugins/fourteen-colors
 * Description: Customize the colors of the Twenty Fourteen Theme, directly within the customizer.
 * Version: 0.2
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

require( 'color-calculations.php' );

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

function fourteen_colors_styles() {
	wp_enqueue_style( 'fourteen-colors-mediaelements', plugins_url( '/mediaelements-genericons.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'fourteen_colors_styles' );

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
	#secondary,
	.site-header,
	.site-footer,
	.featured-content,
	.featured-content .entry-header,
	.slider-direction-nav a,
	.slider-control-paging {
		background-color: ' . $contrast_color . ';
	}
	
	.grid .featured-content .entry-header {
		border-color: ' . $contrast_color . ';
	}
	
	.slider-control-paging a:before {
		background-color: rgba(255,255,255,.33);
	}
	
	.hentry .mejs-mediaelement, .hentry .mejs-container .mejs-controls {
		background: ' . $contrast_color . ';
	}
	
	';
	
	// Adjustents to make lighter Contrast Colors looks just as good.
	if( fourteen_colors_contrast_ratio( $contrast_color, '#fff' ) < 4.5 &&
		fourteen_colors_contrast_ratio( $contrast_color, '#fff' ) < fourteen_colors_contrast_ratio( $contrast_color, '#2b2b2b' ) ) {
		$css .= '	
			#secondary,
			#secondary a,
			.widget_calendar caption,
			.site-header a,
			.site-title a,
			.site-title a:hover,
			.menu-toggle:before,
			.site-footer,
			.site-footer a,
			.featured-content a,
			.featured-content .entry-meta,
			.slider-direction-nav a:before,
			.hentry .mejs-container .mejs-controls .mejs-time span,
			.hentry .mejs-controls .mejs-button button {
				color: #2b2b2b;
			}

			.primary-navigation ul ul a,
			#secondary .secondary-navigation ul ul a,
			#secondary .secondary-navigation li:hover > a,
			#secondary .secondary-navigation li.focus > a,
			#secondary .widget_calendar tbody a,
			.site-footer .widget_calendar tbody a,
			.slider-direction-nav a:hover:before {
				color: #fff;
			}

			.hentry .mejs-controls .mejs-time-rail .mejs-time-loaded, 
			.hentry .mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current {
				background-color: #2b2b2b;
			}

			.slider-control-paging a:before {
				background-color: rgba(0, 0, 0, .33);
			}
			
			.featured-content {
				background-image: url(' . plugins_url( '/pattern-dark-inverse.svg', __FILE__ ) . ');
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
			
			.hentry .mejs-controls .mejs-time-rail .mejs-time-total, 
			.hentry .mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-total {
				background: rgba(0,0,0,.3);
			}
			
			.mejs-overlay .mejs-overlay-button {
				background-color: ' . $contrast_color . ';
			}
		';
	}
	else {
		// These only really work well with darker colors.
		$css .= '
			.content-sidebar .widget_twentyfourteen_ephemera .widget-title:before {
				background: ' . $contrast_color . ';
			}

			.paging-navigation,
			.content-sidebar .widget .widget-title {
				border-top-color: ' . $contrast_color . ';
			}

			.content-sidebar .widget .widget-title, 
			.content-sidebar .widget .widget-title a,
			.paging-navigation,
			.paging-navigation a:hover,
			.paging-navigation a {
				color: ' . $contrast_color . ';
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

	$css = '/* Custom accent color. */
		button,
		.contributor-posts-link,
		input[type="button"],
		input[type="reset"],
		input[type="submit"],
		.search-toggle,
		.hentry .mejs-controls .mejs-time-rail .mejs-time-current,
		.mejs-overlay:hover .mejs-overlay-button,
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
	';
	
	// Dark accent color will only be created if needed for visibility on white background.
	$accent_dark = $accent_color;

	// Adjustments for light accent colors, including darkening the color where needed.
	if( fourteen_colors_contrast_ratio( $accent_color, '#fff' ) < 4.5 &&
		fourteen_colors_contrast_ratio( $accent_color, '#fff' ) < fourteen_colors_contrast_ratio( $accent_color, '#2b2b2b' ) ) {

		$css .= '
		.primary-navigation ul ul a,
		#secondary .secondary-navigation ul ul a,
		#secondary .secondary-navigation li:hover > a,
		#secondary .secondary-navigation li.focus > a,
		.contributor-posts-link,
		button,
		input[type="button"],
		input[type="reset"],
		input[type="submit"],
		.search-toggle:before,
		.mejs-overlay:hover .mejs-overlay-button,
		.widget button,
		.widget input[type="button"],
		.widget input[type="reset"],
		.widget input[type="submit"],
		#secondary .widget_calendar tbody a,
		.site-footer .widget_calendar tbody a,
		.content-sidebar .widget input[type="button"],
		.content-sidebar .widget input[type="reset"],
		.content-sidebar .widget input[type="submit"],
		button:hover,
		button:focus,
		.contributor-posts-link:hover,
		.contributor-posts-link:active,
		input[type="button"]:hover,
		input[type="button"]:focus,
		input[type="reset"]:hover,
		input[type="reset"]:focus,
		input[type="submit"]:hover,
		input[type="submit"]:focus,
		.slider-direction-nav a:hover:before {
			color: #2b2b2b;
		}

		@media screen and (min-width: 782px) {
			.primary-navigation li:hover > a,
			.primary-navigation li.focus > a,
			.primary-navigation ul ul {
				color: #2b2b2b;
			}
		}

		@media screen and (min-width: 1008px) {
			.secondary-navigation li:hover > a,
			.secondary-navigation li.focus > a,
			.secondary-navigation ul ul {
				color: #2b2b2b;
			}
		}

		::selection {
			color: #2b2b2b;
		}

		::-moz-selection {
			color: #2b2b2b;
		}
		';
		
		// Darken the accent color, if needed, for adequate contrast against white page background.
		while( fourteen_colors_contrast_ratio( $accent_dark, '#fff' ) < 4.5 ) {
			$accent_dark = fourteen_colors_adjust_color( $accent_dark, -5 );
		}
	}
	
	// Base the color variants off of the potentially darkened color.
	$accent_mid = fourteen_colors_adjust_color( $accent_color, 29);
	$accent_mid_dark = fourteen_colors_adjust_color( $accent_dark, 29);
	$accent_light = fourteen_colors_adjust_color( $accent_color, 49);

	$css .= '
		a,
		.content-sidebar .widget a {
			color: ' . $accent_dark . ';
		}
		
		/* Generated "mid" variant of custom accent color. */
		.contributor-posts-link:hover,
		.slider-control-paging a:hover:before,
		.search-toggle:hover,
		.search-toggle.active,
		.search-box,
		.site-navigation a:hover,
		.widget_calendar tbody a:hover,
		button:hover,
		button:focus,
		input[type="button"]:hover,
		input[type="button"]:focus,
		input[type="reset"]:hover,
		input[type="reset"]:focus,
		input[type="submit"]:hover,
		input[type="submit"]:focus,
		.widget input[type="button"]:hover,
		.widget input[type="button"]:focus,
		.widget input[type="reset"]:hover,
		.widget input[type="reset"]:focus,
		.widget input[type="submit"]:hover,
		.widget input[type="submit"]:focus,
		.content-sidebar .widget input[type="button"]:hover,
		.content-sidebar .widget input[type="button"]:focus,
		.content-sidebar .widget input[type="reset"]:hover,
		.content-sidebar .widget input[type="reset"]:focus,
		.content-sidebar .widget input[type="submit"]:hover,
		.content-sidebar .widget input[type="submit"]:focus {
			background-color: ' . $accent_mid . ';
		}

		.featured-content a:hover,
		.featured-content .entry-title a:hover,
		.widget a:hover,
		.widget-title a:hover,
		.widget_twentyfourteen_ephemera .entry-meta a:hover,
		.hentry .mejs-controls .mejs-button button:hover,
		.site-info a:hover,
		.featured-content a:hover {
			color: ' . $accent_mid . ';
		}

		a:active,
		a:hover,
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
		.content-sidebar .widget a:hover,
		.content-sidebar .widget .widget-title a:hover,
		.content-sidebar .widget_twentyfourteen_ephemera .entry-meta a:hover {
			color: ' . $accent_mid_dark . ';
		}

		.page-links a:hover,
		.paging-navigation a:hover {
			border-color: ' . $accent_mid_dark . ';
		}

		.entry-meta .tag-links a:hover:before {
			border-right-color: ' . $accent_mid_dark . ';
		}

		.entry-meta .tag-links a:hover {
			background-color: ' . $accent_mid_dark . ';
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