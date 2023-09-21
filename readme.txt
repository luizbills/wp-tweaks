=== WP Tweaks ===
Contributors: luizbills
Tags: security, performance, tweaks, optimization, xmlrpc, emoji
Donate link: https://www.luizpb.com/donate/
Requires at least: 4.0
Tested up to: 6.3
Requires PHP: 7.4
Stable tag: 2.2.0
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

= 2.2.0 - 2023-09-21 =

- New security header: "Referrer-Policy" (value: `strict-origin-when-cross-origin`)
- New security header: "Permissions-Policy" (value: `geolocation=(), microphone=(), camera=(), interest-cohort=()`)

= 2.1.0 - 2023-09-19 =

* Option to disable comments (only in pages and blog posts)
* Option to hide annoying messages in admin panel
* Warns when **WP_DEVELOPMENT_MODE** constant is activated

= 2.0.0 - 2023-08-22 =

* The plugin now requires PHP 7.4+
* Clear the plugin options when the plugin is uninstalled.
* Option to display PHP version in admin footer
* Option to disable or limit the Heartbeat API

= 1.9.2 - 2023-01-18 =

* Fix settings link.

= 1.9.1 - 2023-01-11 =

* Removed "Disallow your site in iframes" option. Utilize the "Security Headers" option instead.

= 1.9.0 - 2023-01-10 =

* Feature: Disable jQuery Migrate (enabled by default).
* Feature: Security Headers (disabled by default).
* Tweak: improve some options.

= 1.8.0 - 2022-12-11 =

* Security: Now is possible disallow to render your site in a `<frame>`, `<iframe>`, `<embed>` or `<object>` outside of your site. Useful to avoid **click-jacking** attacks.
* Security: Now is possible disable the `/users` endpoint in the REST API, to prevent users enumeration.

= 1.7.1 - 2022-07-29 =

* Fix: Option 'Remove Dashboard "welcome panel"' was not working.
* Misc: Improve colors on warning about debug constants.

= 1.7.0 - 2022-07-29 =

* Security: Now is possible display a generic error message when a user fails to login (instead of hints). This option is enabled by default.
* Misc: Now is possible change the menu "Posts" to "Blog". This option is disabled by default.
* Security: The plugin now warns administrators that some WordPress constants (like WP_DEBUG) are enabled. This option is ALWAYS enabled.
* Tested with WordPress 6.0

= 1.6.0 - 2022-04-27 =

* Tested with WordPress 5.9
* Now is possible to remove 'Comments' from admin bar (default: Off)
* Now the option "Remove oEmbed support" is Off by default
* Improved and fixed portuguese translation
* Small enchantments

= 1.5.1 - 2021-03-26 =

* Fix disallow file edit setting
* Now is possible to disable author query and author pages separately

= 1.5.0 - 2020-04-19 =

* New Feature: Disallow file edit

= 1.4.0 - 2020-04-18 =

* New Feature: remove "Howdy" from admin bar

= 1.3.2 - 2020-04-18 =

* Fixed: Can not disable some options

= 1.3.1 - 2020-04-18 =

* Fix PHP Warning

= 1.3.0 - 2020-04-18 =

* Removed "jQuery CDN" option

= 1.2.0 - 2018-10-16 =

* New Feature: hide WordPress version in admin footer
* Tweak: jQuery default version now is 2.2.4
* Tweak: added description to informs the jQuery stable versions
* Tweak: informs that jQuery Migrate also is disabled when using jQuery from CDN
* Fix typos

= 1.1.0 - 2018-07-18 =

* New Feature: dashboard page with only one column
* Fix some typos

= 1.0.0 - 2018-05-23 =

* Initial release.

== Upgrade Notice ==

= 2.0.0 =

* The plugin now requires PHP 7.4+

= 1.0.0 =

* Initial release.
