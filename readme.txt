=== BMLT WordPress Plugin ===
Contributors: magblogapi
Tags: na, meeting list, meeting finder, maps, recovery, addiction, webservant
Requires at least: 2.6
Tested up to: 3.1
Stable tag: 1.5.12

This is a "satellite" plugin for the Basic Meeting List Toolbox (BMLT).

== Description ==

The <a href="http://magshare.org/bmlt">Basic Meeting List Toolbox (BMLT)</a> is a powerful client/server system for locating NA meetings.
The "root server" is a standalone Web site, but "satellite servers" are set up to point to the "root." This is a "satellite," set up as a WordPress plugin.
It is very easy to install and use. It has an administration panel that lets you choose a map center, designate the root, set up the map zoom, and whether or not older browsers are supported.

== Installation ==

1. Upload `the bmlt_plugin` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<!--BMLT-->` or `[[BMLT]]` in the HTML view of a page. It will be replaced by the plugin.
1. You can also use <a href="http://magshare.org/welcome-to-magshare/bmlt-the-basic-meeting-list-toolbox/the-bmlt-in-depth/using-the-simple-table-output/">special shortcodes and HTML comments</a> (It's a slow page -have patience) to specify tables and blocks of predetermined search results.

== Screenshots ==

== Changelog ==
2.0 -
    Major rewrite. You can now have multiple settings, which can include different servers.
    You can "theme" the displays.
    We no longer support non-JS browsers.
    Mobile content has been woven into the plugin. It now allows the page to be replaced with the fast mobile lookup, if that was requested.
    The administration has been drastically improved.
    This provides an infrastructure for many future improvements. The 2.0 release was aimed primarily at transitioning from the old system into the new.

1.5.12 -October 2, 2010
	Very, very minor change to a text display to make the plugin easier to localize.
	Changed the CSS so that the plugin will adapt more efficiently to different environments.
	
1.5.11 -September 18, 2010
	Added support for some new initial screen modes.
	
1.5.10 -September 4, 2010
	Added a bit of default CSS to make the search specification screen more adaptable.
	
1.5.9 -August 30, 2010
	Make sure that all cURL calls are GET, as some servers don't like POST.
	
1.5.8 -July 23, 2010
	Added support for a readable-text meta tag entry ([[BMLT]]).
	
1.5.7 -July 1, 2010
	Stopped the plugin from croaking the whole shooting match if call_curl fails (Wrap in empty try block).
	
1.5.6 -June 1, 2010
	Made it so that lookups for individual meetings don't get redirected to the root server.

1.5.5 -May 30, 2010
	Added JS and Style optimizers for the linked files.
	Fixed a bug in the new selector.

1.5.4 -May 29, 2010
	Embedded the simple search feature into the plugin.

1.5.3 -May 28, 2010
	Added provision to allow the CMS direct access to the simple dump.

1.5.2 -April 23, 2010
	Fixed an old bug that could affect the way the server interaction works (curl).
		
1.5.1 -April 23, 2010
	Added some code to ensure the root server URI has a trailing slash.
	
1.5 -April 2, 2010
	Added support for the "simple" inline meeting tables.
	
1.4.2 -February 21, 2010
	Execute the PDF check earlier, as other plugins can interfere.
	
1.4.1 -February 16, 2010
	Added support for Android
	
1.4 -February 14, 2010
	Added support for iPhone
	Fixed a minor issue with the cURL caller.
	
1.2.19 -December 30, 2009
	Added the ability to switch on a "push in" method of viewing the "More Details" window.
	Added the ability for the admin to insert arbitrary CSS styles.

1.2.18 -November 24, 2009
	Added a section of documentation for administration. No code changes.
	
1.2.17 -November 8, 2009
	Fixed a bug, in which the pre-check boxes in the admin would fail to populate if there was only one Region.
	
1.2.16 -November 4, 2009
	Fixed a bug, in which advanced search criteria were ignored when printing PDFs:
		https://sourceforge.net/tracker/index.php?func=detail&aid=2892019&group_id=228122&atid=1073410
	
1.2.15 -November 3, 2009
	Added support for direct PDF printing (Requires 1.2.15 root server).
	
1.2.5 -October 3, 2009
	Fixed a slight warning issue with the way that the options are initialized.
	

1.2.3 -September 24, 2009
	Added the ability to "pre-check" Service bodies in the Advanced Search tab. This function requires that the root server also be version 1.2.3.
	
1.0.2 -July 20, 2009
	PHP 5.2.10 seems to be expecting a slightly different interpretation of explode(), so it is now simpler.

1.0.1 -June 25, 2009
	Made the inter-server communications use POST, which makes it a bit more robust.

== Installing and Administering the Plugin ==

You need to <a href="http://magshare.org/welcome-to-magshare/bmlt-the-basic-meeting-list-toolbox/the-bmlt-in-depth/implementing-the-bmlt/the-wordpress-satellite/">go to this Web page to get very detailed instructions on installing and configuring the plugin.</a>