=== Fourteen Colors ===
Contributors: celloexpressions
Tags: Twenty Fourteen, Custom Colors, Custom, Colors, Theme Customizer, Twenty Fourteen Theme, Default Theme, 2014
Requires at least: 3.8
Tested up to: 3.8
Stable tag: 0.1
Description: Customize the colors of the Twenty Fourteen Theme, directly within the customizer. Extracted from the theme at the late stages of development; plugin is a first-pass.
License: GPLv2

== Description ==
Adds back the Accent Color feature that was removed from the theme right before its release (see http://core.trac.wordpress.org/ticket/26220).

Also adds an experimental "Contrast Color" feature, which will be further developed once Twenty Fourteen is released.

This plugin is currently in development and will be fully polished in time for the release of WordPress 3.8 and the Twenty Fourteen Theme, on December 12, 2013. Please feel free to test it out, but keep in mind that it isn't quite ready for primetime... yet! (and neither is Twenty Fourteen, of course)

By the way, despite the plugin's name, there are only two customizable color fields to streamline the process as much as possible. 

== Installation ==
1. Take the easy route and install through the WordPress plugin adder OR
1. Download the .zip file and upload the unzipped folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to the theme customizer (Appearance -> Customize) and adjust the two new color pickers under the "Colors" heading to your liking


== Frequently Asked Questions ==
= I Tried Using Fourteen Colors with a theme other than Twenty Fourteen and ... =
Don't.

= Child Themes =
Fourteen Colors is a plugin, not a child theme, because it is primarily programmatic (ie, it would only consist of a functions.php file) and for flexibility.

You can use Fourteen Colors with both Twenty Fourteen and child themes. Be aware that the Fourteen Colors settings are stored with the active theme, so if you switch to a child theme or switch child themes, you'll need to re-set your colors.

== Changelog ==
= 0.1 =
* Initial port from the Twenty Fourteen Theme's implementation 
* Initial pass at a "Contrast Color" option

== Upgrade Notice ==
= 0.1 =
* Initial port from the Twenty Fourteen Theme's implementation and initial pass at a "Contrast Color" option