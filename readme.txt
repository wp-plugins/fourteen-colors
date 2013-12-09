=== Fourteen Colors ===
Contributors: celloexpressions
Tags: Twenty Fourteen, Custom Colors, Custom, Colors, Theme Customizer, Twenty Fourteen Theme, Default Theme, 2014
Requires at least: 3.8
Tested up to: 3.8
Stable tag: 0.2
Description: Customize the colors of the Twenty Fourteen Theme, directly within the customizer. Currently in development after being extracted from the theme at the late stages of development; plugin will be polished by the final release of WordPress 3.8.
License: GPLv2

== Description ==
Adds back the Accent Color feature that was removed from the theme right before its release (see http://core.trac.wordpress.org/ticket/26220). As noted in the ticket, there are currently some limitations to the colors that can be chosen as Accent Colors; these will be removed before the release of WordPress 3.8.

Also adds a "Contrast Color" feature, which supports any color choice and lets you give Twenty Fourteen a completely different look and feel in seconds.

This plugin is currently in development and will be fully polished in time for the release of WordPress 3.8 and the Twenty Fourteen Theme, on December 12, 2013. Please feel free to test it out, but keep in mind that it isn't quite ready for primetime... yet! (and neither is Twenty Fourteen, of course)

By the way, despite the plugin's name, there are only two customizable color fields to make it easy to customize your site in a matter of seconds!

== Installation ==
1. Take the easy route and install through the WordPress plugin adder OR
1. Download the .zip file and upload the unzipped folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to the Theme Customizer (Appearance -> Customize) and adjust the two new color pickers under the "Colors" heading to your liking


== Frequently Asked Questions ==
= I tried using Fourteen Colors with a theme other than Twenty Fourteen and ... =
Don't.

= Child Themes =
Fourteen Colors is a plugin, not a child theme, because it is primarily programmatic (ie, it would only consist of a functions.php file) and for flexibility.

You can use Fourteen Colors with both Twenty Fourteen and child themes. Be aware that the Fourteen Colors settings are stored with the active theme, so if you switch to a child theme or switch child themes, you'll need to re-set your colors. Child theme compatibility depends on the extent of changes made by the child theme.

== Development Roadmap ==
= 0.3 =
* Adjustments to make any color work as the accent color.

= 0.4 =
* Save the colors CSS to an external stylesheet.
* Apply the accent color to the editor styles.

= 0.5 =
* Code cleanup, inline comments, coding standards to match Twenty Fourteen.

= 0.6 =
* Tweaks post-code-review.

= 0.7 =
* Screenshots, finalized documentation.

= 1.0 =
* Final initial release.
* Target date: 12/12/2013, alongside WordPress 3.8 and Twenty Fourteen 1.0

== Changelog ==
= 0.2 =
* Build out of the contrast color option.

= 0.1 =
* Initial port from the Twenty Fourteen Theme's implementation 
* Initial pass at a "Contrast Color" option

== Upgrade Notice ==
= 0.2 =
* Build out the contrast color option.

= 0.1 =
* Initial port from the Twenty Fourteen Theme's implementation and initial pass at a "Contrast Color" option