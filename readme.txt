=== BMLT WordPress Plugin ===
Contributors: magblogapi
Tags: na, meeting list, meeting finder, maps, recovery, addiction, webservant
Requires at least: 2.6
Tested up to: 3.1.2
Stable tag: 2.1.12

This is a "satellite" plugin for the Basic Meeting List Toolbox (BMLT).

== Description ==

The <a href="http://magshare.org/bmlt">Basic Meeting List Toolbox (BMLT)</a> is a powerful client/server system for locating NA meetings.
The "root server" is a standalone Web site, but "satellite servers" are set up to point to the "root." This is a "satellite," set up as a WordPress plugin.
It is very easy to install and use. It has an administration panel that lets you choose a map center, designate the root, set up the map zoom, and whether or not older browsers are supported.

<strong>CAUTION:</strong> Be extremely cautious in upgrading to the 2.0 version, as it represents a MAJOR departure from the 1.X versions!
The styling, especially, has been affected. If you have customized your installation, you'll probably need to redo it. We recommend that you create a custom "theme," by modifying one of the supplied themes.

== Installation ==

<a href="http://magshare.org/blog/bmlt-administration/">Go to this Web page to get very detailed instructions on installing and configuring the plugin.</a>

== Screenshots ==

1. Basic Map Search Screen
2. Advanced Search Screen (Text Entry)
3. Results Displayed As A List
4. Results Displayed As A Map
5. Info Window In Map Results
6. Detailed Results For One Meeting
7. Administration Screen
8. Theme Selection Popup (GNYR is A Custom Theme)
9. Settings Selection Popup
10. Popup of Simple "Preset" Searches
11. Preset Search In Progress
12. Preset Search Results
13. Display of Format Codes
14. Mobile Smartphone Screen
15. Mobile Smartphone Map Results
16. Mobile Results for Basic (non-smartphone) Device
17. The Language Selection Popup (Admin Screen)
18. The Distance Units Popup (Admin Screen)

== Changelog ==

= 2.1.12 =
* May 8, 2011
* Fixed an error that interfered with several Advanced Search options.

= 2.1.11 =
* May 7, 2011
* Fixed a JavaScript error that prevented saves.

= 2.1.10 =
* May 6, 2011
* Added changes to the cross-CMS class and styling for the Drupal module. Won't have much effect on WordPress.

= 2.1.9 =
* May 3, 2011
* Fixed a few issues encountered while implementing the Drupal plugin (a lot of the code is cross-CMS).
* Added GPL headers to everything.

= 2.1.8 =
* April 28, 2011
* Fixed a bug, in which the bmlt_mobile shortcode was not being interpreted properly (Affected WordPress only).

= 2.1.7 =
* April 26, 2011
* Fixed a rather severe bug in the shortcode substitution, that prevented multiple shortcodes from working on the same page.

= 2.1.6 =
* April 24, 2011
* Basic code cleanup.
* Fixed a couple of minor cosmetic bugs in the admin JavaScript and CSS.
    
= 2.1.5 =
* April 23, 2011
* Oops. One more warning-spitter snuck through. It's fixed.

= 2.1.4 =
* April 23, 2011
* Fixed a minor JS bug in the option submit. It did not result in errors, per se, but caused extra text to be transmitted.

= 2.1.3 =
* April 21, 2011
* Addressed some issues that could cause problems on some servers (a screwy intval() implementation).
* Made the save settings use POST, as the size of the transaction can be too large for GET, when you have a lot of settings.
    
= 2.1.2 =
* April 20, 2011
* Fixed some warnings that interfered with the operation of 2.1.1

= 2.1.1 =
* April 20, 2011
* Sequestered the mobile stuff into a fieldset in the admin.
* Added the ability to set the mobile "grace period," as well as specify a mobile offset from the server.
* Added a number of fixes and adjustments as we debugged the Joomla plugin.
    
= 2.1 =
* April 11, 2011
* Significant refactoring to make it easier to port the plugin to other CMSes.
* Added the ability to select distance units (Km or Mi). This will only affect the mobile handler (at the moment).
* Added the ability to project a language, via the original interactive search (You can select a different language from the server's default).
    
= 2.0.2 =
* March 3, 2011
* Fixed a critical problem that appeared in 2.0.1 because of a silly error on the coders' part.
    
= 2.0.1 =
* March 3, 2011
* Fixed a JavaScript issue that prevented the options from being displayed in Firefox.
* Added null parameters to some get_page() calls to prevent warnings.

= 2.0.0 =
* February 21, 2011
* Release.
* Added the GNYR theme to the release.
    
= 2.0.0RC1 =
* February 20, 2011
* Fixed a number of issues encountered during beta testing.
    
= 2.0.0B0 =
* February 14, 2011
* Major rewrite. You can now have multiple settings, which can include different servers.
* You can "theme" the displays.
* We no longer support non-JS browsers.
* Mobile content has been woven into the plugin. It now allows the page to be replaced with the fast mobile lookup, if that was requested.
* The administration has been drastically improved.
* This provides an infrastructure for many future improvements. The 2.0 release was aimed primarily at transitioning from the old system into the new.

= 1.5.12 =
* October 2, 2010
* Very, very minor change to a text display to make the plugin easier to localize.
* Changed the CSS so that the plugin will adapt more efficiently to different environments.
	
= 1.5.11 =
* September 18, 2010
* Added support for some new initial screen modes.
	
= 1.5.10 =
* September 4, 2010
* Added a bit of default CSS to make the search specification screen more adaptable.
	
= 1.5.9 =
* August 30, 2010
* Make sure that all cURL calls are GET, as some servers don't like POST.
	
= 1.5.8 =
* July 23, 2010
* Added support for a readable-text meta tag entry ([[BMLT]]).
	
= 1.5.7 =
* July 1, 2010
* Stopped the plugin from croaking the whole shooting match if call_curl fails (Wrap in empty try block).
	
= 1.5.6 =
* June 1, 2010
* Made it so that lookups for individual meetings don't get redirected to the root server.

= 1.5.5 =
* May 30, 2010
* Added JS and Style optimizers for the linked files.
* Fixed a bug in the new selector.

= 1.5.4 =
* May 29, 2010
* Embedded the simple search feature into the plugin.

= 1.5.3 =
* May 28, 2010
* Added provision to allow the CMS direct access to the simple dump.

= 1.5.2 =
* April 23, 2010
* Fixed an old bug that could affect the way the server interaction works (curl).
		
= 1.5.1 =
* April 23, 2010
* Added some code to ensure the root server URI has a trailing slash.
	
= 1.5 =
* April 2, 2010
* Added support for the "simple" inline meeting tables.
	
= 1.4.2 =
* February 21, 2010
* Execute the PDF check earlier, as other plugins can interfere.
	
= 1.4.1 =
* February 16, 2010
* Added support for Android
	
= 1.4 =
* February 14, 2010
* Added support for iPhone
* Fixed a minor issue with the cURL caller.
	
= 1.2.19 =
* December 30, 2009
* Added the ability to switch on a "push in" method of viewing the "More Details" window.
* Added the ability for the admin to insert arbitrary CSS styles.

= 1.2.18 =
* November 24, 2009
* Added a section of documentation for administration. No code changes.
	
= 1.2.17 =
* November 8, 2009
* Fixed a bug, in which the pre-check boxes in the admin would fail to populate if there was only one Region.
	
= 1.2.16 =
* November 4, 2009
* Fixed a bug, in which advanced search criteria were ignored when printing PDFs:
* https://sourceforge.net/tracker/index.php?func=detail&aid=2892019&group_id=228122&atid=1073410
	
= 1.2.15 =
* November 3, 2009
* Added support for direct PDF printing (Requires 1.2.15 root server).
	
= 1.2.5 =
* October 3, 2009
* Fixed a slight warning issue with the way that the options are initialized.
	

= 1.2.3 =
* September 24, 2009
* Added the ability to "pre-check" Service bodies in the Advanced Search tab. This function requires that the root server also be version 1.2.3.
	
= 1.0.2 =
* July 20, 2009
* PHP 5.2.10 seems to be expecting a slightly different interpretation of explode(), so it is now simpler.

= 1.0.1 =
* June 25, 2009
* Made the inter-server communications use POST, which makes it a bit more robust.

== Installing and Administering the Plugin ==

You need to <a href="http://magshare.org/blog/bmlt-administration/">go to this Web page to get very detailed instructions on installing and configuring the plugin.</a>