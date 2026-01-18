=== BuddyActivist Group Location ===
Contributors: BuddyActivist
Tags: buddypress, buddyboss, groups, map, location, leaflet, geolocation, osm
Requires at least: 5.8
Tested up to: 6.5
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Adds geolocation, maps, and address fields to BuddyPress/BuddyBoss groups. Includes global map with clustering, search, and REST API.
Fully open‑source, no API keys required.

== Description ==

BuddyActivist Group Location adds a complete geolocation system to BuddyPress and BuddyBoss groups.

Features include:

* **Location tab** inside each group (Street, Number, ZIP, City, Region, Country)
* **Automatic geocoding** (lat/lng)
* **Single group map** with Leaflet
* **Global map** with MarkerCluster
* **Search bar** (groups + places via Nominatim)
* **REST API endpoint** for external integrations
* **Admin settings page** to configure:
  * default map center (lat/lng)
  * default zoom
* **OpenStreetMap tiles** (no API keys, no costs)
* **Compatible with BuddyPress and BuddyBoss**
* **Lightweight, fast, production‑ready**

Everything is fully open‑source and privacy‑respecting.

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate the plugin through the “Plugins” menu in WordPress
3. Go to **Settings → Group Location** to configure map defaults
4. Visit any BuddyPress/BuddyBoss group and open the **Location** tab
5. Use the shortcode `[bagl_group_map]` to display the global map

== Shortcodes ==

`[bagl_group_map]`  
Displays the global map with clustering and search.

== Frequently Asked Questions ==

= Does this plugin require API keys? =  
No. It uses OpenStreetMap, Leaflet, and Nominatim — all open‑source and free.

= Does it work with BuddyBoss? =  
Yes. It is fully compatible with both BuddyPress and BuddyBoss.

= Can I customize the default map center? =  
Yes. Go to **Settings → Group Location**.

= Does it expose private data? =  
No. Only public group information is shown.

= Can I style the map? =  
Yes. You can override the CSS in your theme.

== Screenshots ==

1. Group Location tab
2. Single group map
3. Global map with clustering
4. Search bar (groups + places)
5. Admin settings page

== Changelog ==

= 1.0.0 =
* Initial release
* Group location fields
* Single group map
* Global map with clustering
* Search bar (groups + places)
* Admin settings page
* REST API endpoint
* Full internationalization support

== Upgrade Notice ==

= 1.0.0 =
First stable release.
