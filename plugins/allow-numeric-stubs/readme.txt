=== Allow Numeric Stubs ===
Contributors: Viper007Bond
Donate link: http://www.viper007bond.com/donate/
Tags: page, pages, numeric, number
Requires at least: 3.3
Tested up to: 3.3
Stable tag: trunk

Allows Pages to have a stub that is only a number. Sacrifices the paged content ability in Pages to accomplish it.

== Description ==

It is not possible to have a page slug (the page's name in the URL) that is a number. For example this will not work: `yoursite.com/about/2/`. That URL conflicts with paged content feature where you can posts and pages with multiple pages of content by adding `<!--nextpage-->` within your content.

This plugin allows you to have  Pages with numbers as stubs by giving up the ability to have paged content pages which isn't a big deal as most people don't use paged content pages anyway.

== Installation ==

###Updgrading From A Previous Version###

To upgrade from a previous version of this plugin, delete the entire folder and files from the previous version of the plugin and then follow the installation instructions below.

###Installing The Plugin###

Go to your WordPress administration area and then navigate to Plugins -> Add New in the menu. Search for this plugin's name and then press the install link.

= See Also:** ["Installing Plugins" article on the WP Codex](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins)

== ChangeLog ==

= Version 2.1.0 =

* Update for WordPress 3.3 and it's newer rewrite rules.

= Version 2.0.1 =

* Re-add the `save_post` filter after fixing the slug incase multiple posts are updated in one pageload.

= Version 2.0.0 =

* Recoded for WordPress 3.0+. WordPress now won't let you manually enter a numeric stub -- it will prefix "-2" onto the end of it so that the page is viewable. This new plugin version works around it.

= Version 1.0.0 =

* Initial release.

== Upgrade Notice ==

= 2.1.0 =
WordPress 3.3 compatibility.