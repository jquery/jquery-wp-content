=== Disable Emojis (GDPR friendly) ===
Contributors: ryanhellyer
Tags: emojis, gdpr, disable
Donate link: https://geek.hellyer.kiwi/donate/
Requires at least: 4.8
Tested up to: 6.8
Stable tag: 1.7.7


This plugin disables the new WordPress emoji functionality. GDPR friendly.


== Description ==

This plugin disables the new WordPress emoji functionality. GDPR friendly.


Note: Emoticons will still work and emojis will still work in browsers which have built in support for them. This plugin simply removes the extra code bloat used to add support for emojis in older browsers.

= GDPR compliancy =

This plugin does not do anything to make your site less GDPR compliant. It disables the DNS prefetching of emojis within WordPress, which should ensure improved privacy. To determine if your site is GDPR compliant, please seek legal advice. I have done my best to ensure the plugin is 100% GDPR compliant, but I am not a lawyer so can not guarantee anything ;)


== Installation ==

After you've downloaded and extracted the files:

1. Upload the complete 'disable-emojis' folder to the '/wp-content/plugins/' directory OR install via the plugin installer
2. Activate the plugin through the 'Plugins' menu in WordPress
3. And yer done!

Visit the <a href="https://geek.hellyer.kiwi/plugins/disable-emojis/">Disable Emojis plugin</a> for more information.


== Changelog ==

= 1.7.7 =
* Confirmed support for newer WordPress versions.

= 1.7.6 =
* Confirmed support for newer WordPress versions.

= 1.7.5 =
* Added Composer support.

= 1.7.4 =
* Fixing typos.

= 1.7.3 =
* Unneeded version bump to shut the WordPress.org notice up.

= 1.7.2 =
* Subtle improvement to code cleanliness.
* Improved documentation regarding GDPR issues.

= 1.7.1 =
* Added GDPR friendly label on advice from Ipstenu.

= 1.7 =
* Removed DNS prefetch URL again.
* This time using simple string check rather than relying on internal WordPress filters.

= 1.6 =
* Removed DNS prefetch URL. Props to Aaron Queen for assisting with this.

= 1.5.3 =
* Catering to new DNS prefetch URL in version 4.7 of core

= 1.5.2 =
* Improved documentation.
* Removed redundant dns prefetching. Thanks to <a href="http://blog.milandinic.com/">Milan Dinic</a> for the pull request.

= 1.5.1 =
* Updating documentation.

= 1.5 =
* Catering for invalid $plugin array.

= 1.4 =
* Updating to use Otto's code.

= 1.3 =
Removing extraneous styles.

= 1.2 =
Bug fix.

= 1.1 =
Updating to work with latest beta.

= 1.0 =
Initial release.
