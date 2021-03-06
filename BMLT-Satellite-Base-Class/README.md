DESCRIPTION
-----------

The Basic Meeting List Toolbox (BMLT) is a powerful, database-driven system for tracking NA meetings.
It is NOT an official product of NA ( http://na.org ). Rather, it is a project designed and implemented by
NA members, and meant to be used by official NA Service bodies.

This project is a CMS "base." It has a lot of functionality built into it, such as administration and various
renderers, but in a "CMS agnostic" fashion. The main class is designed to be extended (subclassed) for specific
CMS implementations.

REQUIREMENTS
------------

The project requires a functioning BMLT root server ( http://magshare.org/blog/installing-the-root-server/ ).
It does not implement a root server, but connects to an existing one.
It requires PHP 5.0 or above.

This class uses the BMLT Satellite "Driver" Class, which is available on BitBucket, here:

    https://bitbucket.org/bmlt/bmlt-satellite-driver.git

INSTALLATION
------------

CHANGELIST
----------
*3.0.28*
* August 15, 2015
* Added Portuguese translation (Brazil).

*3.0.27*
* May 25, 2015
* Removed the "0" timeout from the location determination code in the basic BML shortcode handler, in an effort to address an IE11 issue.
* Fixed an issue with the results of nouveau map searches returning unsorted.
* Fixed a few CSS errors in the themes.

*3.0.26*
* January 31, 2015
* Fixed an issue with the extra fields display in the regular shortcode display details.
* Fixed an issue where the arbitrary fields were actually creating too many results.
* Now hide the distance_in_km/miles parameters in the meeting details (these are internal parameters).

*3.0.25*
* November 22, 2014
* Fixed a CSS issue with the admin display map. Some themes (especially responsive ones) declare a global max-width for images. This hoses Google Maps, and has to be compensated for.
* Added full support for arbitrary fields. This was an important capability that was left out after Version 3.X

*3.0.24*
* July 31, 2014
* Added a user agent to the cURL call, as some servers filter out cURL.
* Fixed an annoying admin bug that could cause new options to report an incorrect ID.

*3.0.23*
* July 17, 2014
* Added Danish localization.

*3.0.22*
* February 28, 2014
* Fixed a small bug in the add new settings handler.

*3.0.21*
* February 22, 2014
* Some work to make the code easier to debug, and also to account for non-standard TCP ports.

*3.0.20*
* December 31, 2013
* Fixed a character set issue that affected Internet Exploder.

*3.0.19*
* December 7, 2013
* Added French localization

*3.0.18*
* September 7, 2013
* Minor German localization corrections.
* Removed the useless "New Search URL" text box from the admin.
* Fixed a number of JavaScript issues with the [[bmlt_mobile]] shortcode.

*3.0.17*
* July 1, 2013
* Corrected German localization.
* Added the ability to specify which day weeks begin (in Europe, it is common for weeks to begin on Monday).

*3.0.16*
* May 22, 2013
* Added German localization.

*3.0.15*
* May 19, 2013
* Fixed a small issue with the admin sheet, where entering text into the CSS box would not immediately trigger a "dirty" sheet.

*3.0.14*
* May 18, 2013
* Fixed a possible issue with some initial calls being pooched by ampersands being represented as '&amp;' in the URI.

*3.0.12*
* May 16, 2013
* Added some code to reduce warnings in Drupal 7, if modules use nested arrays in the parameters.

*3.0.11*
* May 13, 2013
* Reduced the number of times that the marker redraw is called in the standard [[bmlt]] shortcode handler.
* Fixed an issue with CSS that caused displayed maps to get funky.

*3.0.10*
* May 5, 2013
* Fixed a bug, in which the first set of results for a search would display too many "red" map icons.

*3.0.9*
* May 4, 2013
* Removed some PHP warnings.

*3.0.8*
* April 28, 2013
* Added support for display of military time.

*3.0.7*
* April 21, 2013
* The string search was being improperly handled. This has been fixed.
* Moved the project to Bitbucket.

*3.0.6*
* April 18, 2013
* Improved the curl call functionality.

*3.0.5*
* April 16, 2013
* Fixed a bug in the Swedish translation.

*3.0.4*
* April 15, 2013
* Fixed a bug caused by the modifications for the new admin browser.

*3.0.3*
* April 15, 2013
* Fixed a bug in the new Swedish localization.

*3.0.2*
* March 30, 2013
* Added a default duration.
* Uncommented the adjustment for grace period.
* Added a link to the Google Maps for each meeting's details.
* Fixed a bug that screwed up localizations.
* Added a new Swedish localization.
* Updated the driver to preserve the session across the curl call.
* Added support for a "logged in" mode. This allows the plugin base class to be used by observers, in the root server.

*3.0.1*
* January 28, 2013
* Fixed an issue that caused conflicts with some installations.

*3.0*
* January 26, 2013
* Substantial rework to support new functionality.
* Decided to make it version 3.0, in order to coincide with the new plugins.

*1.2.4*
* May 13, 2012
* Fixed a nasty bug in the admin interface that could create multiple empty settings.

*1.2.3*
* April 27, 2012
* added some JavaScript "hooks" to allow more precise control of the new map search.

*1.2.2*
* March 28, 2012
* Added an alert to the new map search, if no meetings were found in a clicked search (before, there was no alert).

*1.2.1*
* December 31, 2011
* Removed some errant CSS.
* Now strip out the [[bmlt_mobile]] shortcode if the page is not a mobile page. This allows the shortcode to be used, as the comment version is stripped by "code cleaners."

*1.2*
* November 22, 2011
* Added the ability to have multiple localizations. It's a bit clunky, but this is the best way to get it working.
* Corrected some minor validation issues with the new map search DOM tree.

*1.1.7*
* September 2, 2011
* Fixes a JavaScript Error with the new map search on Internet Explorer.

*1.1.6*
* August 17, 2011
* Minor fixes to the default styles in the themes, in order to make the info windows look better.

*1.1.5*
* August 16, 2011
* Workaround for a Firefox bug that renders the popup menus in the info windows worthless.

*1.1.4*
* August 12, 2011
* Fixes a couple of minor theme/style issues.
* Mitigates a strange Firefox bug that caused weird page loads when closing the location area.

*1.1.3*
* August 8, 2011
* Implements an entirely new, Google Maps API V.3-based map search.

*1.1.2*
* July 16, 2011
* I now check for an ob_level before doing an ob_end_clean(). This is because notices were being posted when there was no ob_level.

*1.1.1*
* July 9, 2011
* Added unit tests for the new change capabilities.

*1.1.0*
* June 25, 2011
* Added the capability to specify changes.

*1.0.8*
* June 20, 2011
* Added a connection to a specific localhost BMLT root as a default, if the server is localhost (specific to the development machine).

*1.0.7*
* June 19, 2011
* First release as a factored project.