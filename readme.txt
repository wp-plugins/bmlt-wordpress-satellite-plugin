=== BMLT WordPress Plugin ===
Contributors: magblogapi
Tags: na, meeting list, meeting finder, maps, recovery, addiction, webservant
Requires at least: 2.0
Tested up to: 2.9.2
Stable tag: 1.5

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

== Administering the Plugin ==

<div style="float:right;margin-left:8px;margin-bottom:8px;border:1px solid red"><img src="http://bmlt.magshare.net/stable/docs/PluginAdmin/images/OverallPlain.gif" width="500" height="675" alt="The overall plugin administration page." style="width:500;height:675" /></div>

<p>In order to effectively implement your CMS plugin, you'll need a couple of pieces of data from external sources:</p>

<div style="margin-left:2em">

	<ol style="margin-left:1em">
	
		<li>You'll need to get a <a href="http://code.google.com/apis/maps/signup.html">Google Maps API key</a> for the domain of your CMS server. The trick here, is to get a key for the top domain of your server, not for a path to the plugin. For instance, if your meeting list is at http://bmlt.mydomain.org/meeting-list/, then the API key should be for bmlt.mydomain.org, or mydomain.org (Google sometimes needs you to specify subdomains, like the &quot;bmlt&quot;). Experiment, and you'll know when you get it right. In fact, the first time that you access the options, expect to see a popup that tells you the Google API key is invalid, and the map may not draw.</li>

		<li>You'll need to get the root server URL from the Webservant that administers the root server (probably your RSC). This points to the base server that you log into in order to administer the BMLT database. It also is the URL that your plugin needs to get its data.</li>

	</ol>

</div>

<p>Once you have these, you're ready to start setting up your plugin.</p>

<h2 style="clear:none;margin-top:8px">Initial Search Type</h2>

<p class="first" style="clear:none">

	<div style="float:right;margin-right:8px;border:1px solid red"><img src="http://bmlt.magshare.net/stable/docs/PluginAdmin/images/InitialSearchType.gif" width="291" height="27" alt="Initial Search Type" style="display:block" /><img src="http://bmlt.magshare.net/stable/docs/PluginAdmin/images/InitialSearchTypeMenu.gif" alt="Initial Search Type Menu" width="273" height="73" style="display:block;margin-left:9px;margin-top:4px" /></div>
	
	This is a popup menu that allows you to select how the BMLT Search first appears to visitors to your site.</p>

<p>It has four choices:</p>

<div style="margin-left:2em">

	<ol style="margin-left:1em">

		<li><strong>Root Server Decides</strong><br />In this case, the plugin will present the search as decided by the root server (either Text or Map).</li>

		<li><strong>Map</strong><br />If this is selected, the plugin will always first appear with the &quot;<a href="http://bmlt.magshare.net/stable/docs/PluginAdmin/images/MapSearch.gif">Search By Map</a>&quot; choice shown.</li>

		<li><strong>Text</strong><br />If this is selected, the plugin will always first appear with the &quot;<a href="http://bmlt.magshare.net/stable/docs/PluginAdmin/images/TextSearch.gif">Search for Text</a>&quot; choice shown.</li>

		<li><strong>Advanced</strong><br />Always start with the &quot;<a href="http://bmlt.magshare.net/stable/docs/PluginAdmin/images/AdvancedSearch.gif">Advanced Search</a>&quot; tab showing.</li>

	</ol>

</div>

<h2 style="clear:none;margin-top:8px">Root BMLT Server</h2>

<div><img src="http://bmlt.magshare.net/stable/docs/PluginAdmin/images/RootServer.gif" style="border:1px solid red" width="735" height="25" alt="Root BMLT URL" /></div>

<p class="first">This is a text entry box. You are expected to enter the URL mentioned in Item 2, above.</p>

<p>This is a very important URL. All kinds of strange things can happen if you don't get it right, so make sure that you get the correct URL.</p>

<h2 style="clear:none;margin-top:8px">Google Maps API Key</h2>

<div><img src="http://bmlt.magshare.net/stable/docs/PluginAdmin/images/GoogleMapsAPIKey.gif" style="border:1px solid red" width="752" height="23" alt="Root BMLT URL" /></div>

<p class="first">This is a text entry box. You are expected to enter the API key mentioned in Item 1, above. You <a href="http://code.google.com/apis/maps/signup.html">get this key from Google</a>.</p>

<p>Once you have the correct key, the popup complaining of an incorrect key will go away.</p>

<h2 style="clear:none;margin-top:8px">Specific URL for a New Search</h2>

<div><img src="http://bmlt.magshare.net/stable/docs/PluginAdmin/images/NewSearchURI.gif" style="border:1px solid red" width="991" height="23" alt="New Search URL" /></div>

<p class="first">This is a text entry box. If you want to have a different URL than the one automatically determined by the BMLT plugin, then enter it here. Some CMS systems have &quot;pretty URL&quot; schemes, and the automatic URL might fall foul of this scheme, so you can override it here. In most cases, you can leave this blank.</p>

<h2 style="clear:none;margin-top:8px">Support Non-JavaScript Browsers</h2>

<div><img src="http://bmlt.magshare.net/stable/docs/PluginAdmin/images/NonJSBrowsers.gif" style="border:1px solid red" width="255" height="20" alt="Support Non-JavaScript Browsers Checkbox" /></div>

<p class="first">If this is checked, visitors to the meeting search will be greeted with a brief "refresh." This is how the system detects whether or not the visitor's browser can support the requisite JavaScript to take advantage of the nicer parts of the system (interactive maps and AJAX screens). In many cases, you will leave this blank. If a visitor comes without support for JavaScript, they will be given a link to the root server, which can support it.</p>

<h2 style="clear:none;margin-top:8px">Pre-Checked Service Bodies</h2>

<div style="float:right;clear:right;margin:8px"><img src="http://bmlt.magshare.net/stable/docs/PluginAdmin/images/PreChecked.gif" style="border:1px solid red" width="716" height="364" alt="Support Non-JavaScript Browsers Checkbox" /></div>

<p class="first">The exact content of these checkboxes will vary, depending upon your root server. It is possible to have certain Service Body checkboxes &quot;pre-checked,&quot; when the visitor goes to the Advanced Search tab. This is useful for satellite implementations that serve particular Service Bodies. The main root server may have many, many Service Bodies, and searches may deliver too many results. This helps narrow it down to ones relevant to the visitor, while giving them the option to expand their search to areas outside the Service Body.</p>

<h2 style="clear:right;margin-top:8px">The Map</h2>

<div style="float:right;clear:right;margin:8px"><img src="http://bmlt.magshare.net/stable/docs/PluginAdmin/images/Map.gif" style="border:1px solid red" width="725" height="745" alt="The Initial Position and Zoom Map" /></div>

<p class="first">This is how you choose the initial position and zoom level of the map view in the Basic Search tab Search By Map. Simply grab the center marker, and put it where you like. This is a Google Map, so it will scroll to areas not visible in the initial map. You can also set the zoom level. When the visitor selects &quot;Search By Map,&quot; the map will be shown centered as you choose here, and at the zoom you select.</p>

<p>It should be noted that this map will not be useful until after you have set the correct Google API Key for your server. Set that first, then set your map.</p>

<h2 style="clear:both;margin-top:8px">Setting the Options</h2>

<p class="first">Once you've decided on your settings, press the &quot;Set These Values&quot; button at the top of the page. This will immediately set the values. If you have been getting the complaint alert about the Google Maps API key being wrong, and you set the correct key, then the complaint should stop after this button has been clicked.</p>

<div style="clear:both"></div>
