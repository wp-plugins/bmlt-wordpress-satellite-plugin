=== BMLT WordPress Plugin ===
Contributors: magblogapi
Tags: na, meeting list, meeting finder, maps, recovery, addiction, webservant
Requires at least: 2.0
Tested up to: 2.8.5
Stable tag: 1.2.16

This is a "satellite" plugin for the Basic Meeting List Toolbox (BMLT).

== Description ==

The <a href="http://magshare.org/bmlt">Basic Meeting List Toolbox (BMLT)</a> is a powerful client/server system for locating NA meetings.
The "root server" is a standalone Web site, but "satellite servers" are set up to point to the "root." This is a "satellite," set up as a WordPress plugin.
It is very easy to install and use. It has an administration panel that lets you choose a map center, designate the root, set up the map zoom, and whether or not older browsers are supported.

== Installation ==

1. Upload `the bmlt_plugin` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<!--BMLT-->` in the HTML view of a page. It will be replaced by the plugin.

== Screenshots ==

1. Search by map
2. A map search result
3. The administration screen

== Changelog ==
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