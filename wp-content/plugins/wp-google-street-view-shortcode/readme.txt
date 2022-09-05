=== Shortcode for Google Street View ===
Contributors: AntiochInteractive, pjvolders
Tags: google, maps, street, view, earth, streetview, panorama, api, address
Donate link: http://metroplex360.com/
Requires at least: 4.0.0
Tested up to: 4.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a shortcode for embedding Google Street View content into your Wordpress Site.  Embed directly, or embed a thumbnail that opens a tour as an overlay.

== Description ==

Adds a shortcode for embedding Google Street View content into your Wordpress Site.  Embed directly, or embed a thumbnail that opens a tour as an overlay.

In any editor within WordPress, you'll see the lovely pegman next to insert image.  Click this little guy to open a simple interface to find your tour, set the start position and insert your tour into your site!

= Features =

*   Easily locate your tour and set a start position
*   Adds Click to Play functionality to Google Street View

= Coming Soon =

*   Multi-language support.
*   Admin Interface for building galleries (Pro)

== Frequently Asked Questions ==

**Q: Does it create galleries?**
Not yet!  You can only insert one tour at a time with this (so far).  You will need to use a page builder (I like Elementor), or a column shortcode to pull this off.

**Q: What's the point?**
Google Street View allows embedding in an IFRAME, but immediately loads tours.  this slows page load down and is tedious to setup.  This plugin is not only FASTER and EASIER to use, but it adds the click-to-start functionality that is sorely missing from GSV.

**Q: Will you be adding features like overlays and internal tour enhancements?**
A: Probably not.  This would best be addressed by visiting a robust platform like GoThru, Walkinto or PanoSkin.

**Q: I love the lightbox overlay thing.**
A: Thanks, it's Magnific Popup by Dmitry Semenov.  Go check out his site.  He makes some awesome stuff! 
http://dimsemenov.com/plugins/magnific-popup/


== Screenshots ==
1. Pegman icon above editor to launch shortcode builder
2. Shortcode builder
3. Click-to-Start thumbnails
4. GSV Overlay

== Changelog ==

= 0.5.7 =
* Fixed bug where interactive with modal would scroll down the page on iPad/Safari 

= 0.5.6 =
* Added Google Maps Image API Authentication Checks
* Added id="" parameter for setting a unique id for javascript targetting

= 0.5.5 =
* Added viewport meta tags to pop-up view to prevent UI scaling on mobile.
* Added direct links to enable each Google Maps API from Admin

= 0.5.4 =
* Added admin notice to prompt for entry of Google Maps API Key
* Removed default Google Maps API Key
* Improved API Key validation script

= 0.5.3 =
* Added settings page for entering Google Maps API Key
* Added default Google Maps API Key

= GSV Shortcode 0.5 =
* Forked from Simple Google Street View for Wordpress by PJ Volders
* Conditional loading of Google Maps JS / Built-in Conflict Resolution
* Added - Embed as thumbnail with title that appears in overlay (MagnificPopup)
* Added - Set width / height
* Fixed - Plugin now runs on secure sites (https)
* Fixed - Replaced content filter shortcode processing with native add_shortcode()
* Changed - Replaced icon in editor with pegman.

= Simple Street View 0.4 =
* Searchbox to find your location faster
* Fix some bugs

= Simple Street View 0.3 =
Add the icon for the media button

= Simple Street View 0.2 =
* The plugin now lives with the media buttons in the upload/insert section, where it belongs!
* Fixed a small javascript error.

= Simple Street View 0.1 =
First release
