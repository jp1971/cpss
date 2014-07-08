=== Compatible Post Sharing System ===
Contributors: Jameson Proctor, Athleticsnyc
Tags: sharing, email
Requires at least: 3.0.1
Tested up to: 3.9.1
Stable tag: trunk
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Compatible Post Sharing System allows site vistors to share posts and pages via email.

== Description ==

The Compatible Post Sharing System plugin provides a shortcode that can be used in the post editor or in a template file with the do_shortcode() function. This shortcode outputs a link that opens a modal window on click that contains a form that will allow a site visitor to send an email. The link name and popup heading can be customized with the link and title shortcode attributes. The defaults are 'Email' and 'Email This Page' respectively. The subject and message can be customized via the plugin's settings page.

Why Compatible Post Sharing System?

With the introduction of MIT's Compatible Time-Sharing System (CTSS) in 1961 multiple users were able to log into a central system from remote dial-up terminals, and to store and share files on the central disk. Informal methods of using this to pass messages developed and were expanded to create the first system worthy of the name "email".

Notes:  
* This plugin works in tandem with Akismet to help ensure that the form is not used to send spam email. In order to take advantage of spam protection, you'll need to be using Akismet 3.0.0+.      
* The modal functionality is built using the Magnific Popup jQuery plugin by Dmitry Semenov.      
* The form fields use standard Bootstrap 3 markup. The form specific CSS ships with the plugin and can be loaded depending on user input.      
* The plugin utilizes standard browser validation to ensure that both the To Email Address and From Email Address fields are filled with a valid email address before submitting.      
* The banner image is of the operator's console of an IBM 7094, one of the systems at MIT that ran the CTSS. More information on the IBM 7094 and CTSS can be found at [multicians.org](http://www.multicians.org/thvv/7094.html). The original image is by Arnold Reinhold and is licensed under the [CC BY-SA 3.0](http://creativecommons.org/licenses/by-sa/3.0/).      

== Installation ==

1. Install through the WordPress admin or upload the plugin folder to your /wp-content/plugins/ directory
2. Activate the plugin through the 'Plugins' menu in WordPress

See the Usage section for information on incorporating into your theme.

== Frequently Asked Questions ==

== Screenshots ==

1. The CPSS pop-up form.
2. The CPSS settings pane.

== Changelog ==

= 1.2 =
* Bringing over Bootstrap form styles and prefixing them with id. Bootstrap is no longer needed to style form.
* Adding checkbox to plugin settings pane to determine whether or not CSS should be loaded.
* Upgrading Akismet function to work with version 3+.
* Cleaning up readme.

= 1.1 =
* Fixed an error in the JavaScript that was causing the popup to fail.

= 1.0 =
* Initial release

== Usage ==

To add a Compatible Post Sharing System link to a post or page, use the [cpss] shortcode. To use in a template, wrap the shortcode in the `do_shortcode()` function. To use in a [Timber](http://jarednova.github.io/timber/) template, which we highly recommend, use the shortcodes filter like so `{{ '[cpss]' | shortcodes }}`.

The shortcode allows for the following options:

* link (e.g [cpss link="Email a Friend"]) - This allows you to set the name of the link that opens the CPSS pop-up form. The default is 'Email'.

* title (e.g [cpss title="Email a Friend This Page"]) - This allows you to set the name of the title that appears at the top of the CPSS pop-up form. The defualt is 'Email This Page'.

The default values of the Subject and Message fields in the CPSS pop-up form can be set via the CPSS settings pane. These fields support two tags, [page_title] and [url], which will be replaced by the page name and url on the front end.