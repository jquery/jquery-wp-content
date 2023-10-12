# Contributing

Welcome! Thanks for your interest in contributing to jquery-wp-content. More information on how to contribute to this and other projects is over at [contribute.jquery.org](https://contribute.jquery.org). You'll definitely want to take a look at the articles on contributing [to our websites](https://contribute.jquery.org/web-sites/) and [code](https://contribute.jquery.org/code).

You may also want to take a look at our [commit & pull request guide](https://contribute.jquery.org/commits-and-pull-requests/) and [style guides](https://contribute.jquery.org/style-guide/) for instructions on how to maintain your fork and submit your code. Before we can merge any pull request, we'll also need you to sign our [contributor license agreement](https://contribute.jquery.org/cla).

You can [Chat on Gitter](https://gitter.im/jquery/dev), should you have any questions. If you've never contributed to open source before, we've put together [a short guide with tips, tricks, and ideas on getting started](https://contribute.jquery.org/open-source/).

## Code knowledge

### Protocol-relative URLs

As of 2023, we run with the default WordPress settings to formatting and cleaning URLs. If revisiting this in the future, consider the following constraints:

* When accessing sites in older browsers over HTTP instead of HTTPS, references to theme assets (e.g. stylesheets) must either use the current scheme, or use a protocol-relative URL, or be an absolute path URL without protocol or hostname (`theme_root_uri`).

* Intra-site links to pages and categories should generally use a path or the canonical URL.

* Avoid stripping the protocol from a `clean_url` filter as various uses require a full URL:
  * Server-side requests, such as for `downloads.wordpress.org`, must specify an explicit protocol in the URL.
  * When building `/wp-sitemap.xml`, URLs must be full and with the canonical protocol explicitly set. Sitemaps are invalid if they contain relative URLs.
  * When outputting `<link rel=canonical>` via `wp_head/rel_canonical`, the URL must be full and canonical. Or `rel_canonical` must be remove_action'ed replaced with a custom version that calls `esc_attr()` instead of `esc_url()` to avoid the `clean_url` filter.
