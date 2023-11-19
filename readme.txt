=== WP Tweaks ===
Contributors: luizbills
Tags: security, performance, tweaks, optimization, xmlrpc, emoji
Donate link: https://www.luizpb.com/donate/
Requires at least: 4.0
Tested up to: 6.3
Requires PHP: 7.4
Stable tag: 2.3.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Several opinionated WordPress tweaks focused in security and performance.

== Description ==

Several opinionated WordPress tweaks focused in security and performance.

= FEATURES =

* Produces clean filenames for uploads
* Change admin footer text
* Disable author page and author search by url
* Disable emoji mess
* Disable "meta widget"
* Disable WP REST API public access
* Disable "website" field from comment form
* Disable wp embed
* Disable xmlrpc.php
* Show admin bar for admin users only
* Hide WordPress update nag to all but admins
* Remove "WordPress logo" from admin bar
* Remove "+ New" button from admin bar
* Remove some dashboard widgets
* Remove query strings from scripts (JavaScript and CSS)
* Remove &lt;link rel="shortlink" &gt;
* Remove dashboard "welcome panel"
* Remove WordPress version number in &lt;head&gt;
* and more...

**Note**: is possible disable any feature.

= Contribuitions =

For bugs, suggestions or contribuitions, open a issue in our [Github Repository](https://github.com/luizbills/wp-tweaks/issues) or create a topic in [WordPress Plugin Forum](https://wordpress.org/support/plugin/wp-tweaks/).

= Donations =

Support this plugin on [https://luizpb.com/donate/](https://luizpb.com/donate/)

== Installation ==

1. Install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the "Plugins" screen in WordPress.
1. Go to Settings > Tweaks to configure the plugin.

== Changelog ==

= 2.3.0 - 2023-11-19 =

- Option to disable RSS feeds
- Option to disable right click in frontend
- Option to remove language switcher from login page
- Option to disable WP CRON (via `DISABLE_WP_CRON` constant). Be sure to create an external cron job when enabling this option.

[See changelog for all versions](https://github.com/luizbills/wp-tweaks/blob/main/changelog.txt)

== Upgrade Notice ==

= 2.0.0 =

* The plugin now requires PHP 7.4+

= 1.0.0 =

* Initial release.
