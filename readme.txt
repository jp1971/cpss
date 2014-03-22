=== Compatible Post Sharing System ===
Contributors: Jameson Proctor, Athleticsnyc
Tags: sharing, email
Requires at least: 3.0.1
Tested up to: 3.8.1
Stable tag: trunk
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Compatible Post Sharing System allows site vistors to share posts and pages via email.

== Description ==

The Compatible Post Sharing System plugin provides a shortcode that can be used in the post editor or in a template file with the do_shortcode() function. This shortcode outputs a link that opens a modal window on click that contains a form that will allow a site visitor to send an email. The link name and popup heading can be customized with the link and title shortcode attributes. The defaults are 'Email' and 'Email This Page' respectively. The subject and message can be customized via the plugin's settings page.

This plugin works in tandem with Akismet to help ensure that the form is not used to send spam email. It utilizes standard browser validation to ensure that both the To Email Address and From Email Address fields are filled with a valid email address before submitting.

Notes: The modal functionality is built using the Magnific Popup jQuery plugin by Dmitry Semenov. The form fields use standard Bootstrap 3 markup.

== Installation ==

1. Install through the WordPress admin or upload the plugin folder to your /wp-content/plugins/ directory
2. Activate the plugin through the 'Plugins' menu in WordPress

See the Usage section for information on incorporating into your theme.

== Frequently Asked Questions ==

== Screenshots ==

1. The CPSS pop-up form.
2. The CPSS settings pane.

== Changelog ==

= 1.1 =
* Fixed an error in the JavaScript that was causing the popup to fail.

= 1.0 =
* Initial release

== Usage ==

To add a Compatible Post Sharing System link to a post or page, use the [cpss] shortcode. To use in a template, wrap the shortcode in the `do_shortcode()` function. The shortcode allows for the following options:

* link (e.g [cpss link="Email a Friend"]) - This allows you to set the name of the link that opens the CPSS pop-up form. The defualt is 'Email'.

* title (e.g [cpss title="Email a Friend This Page"]) - This allows you to set the name of the title that appears at the top of the CPSS pop-up form. The defualt is 'Email This Page'.

The default values of the Subject and Message fields in the CPSS pop-up form can be set via the CPSS settings pane. These fields support two tags, [page_title] and [url], which will be replaced by the page name and url on the front end.