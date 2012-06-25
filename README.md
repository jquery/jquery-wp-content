# jQuery.com web-base-template

This is a set of plugins, themes, and configuration files for jQuery's website infrastructure, which is powered by WordPress. It is designed as a custom content directory. So think of `web-base-template` as your `wp-content` directory.

## Installation

1. Configure your local webserver with a virtual host that covers the relevant jQuery domains, such as `*.jquery.com` and `*.jqueryui.com`, all pointing to the same root. For example, in Apache:

    ```
    <VirtualHost *:80>
    ServerName dev.jquery.com
    ServerAlias *.jquery.com *.jqueryui.com *.jquery.org *.qunitjs.com *.sizzlejs.com *.jquerymobile.com
    DocumentRoot "/srv/www/jquery"
    </VirtualHost>
    ```

1. Configure your `/etc/hosts` file to point `dev.jquery.com`, `dev.jqueryui.com`, etc. to your local machine. For example:

    ```
    127.0.0.1 dev.jquery.com dev.api.jquery.com dev.plugins.jquery.com dev.blog.jquery.com dev.learn.jquery.com
    127.0.0.1 dev.jqueryui.com dev.blog.jqueryui.com dev.api.jqueryui.com
    127.0.0.1 dev.jquery.org dev.qunitjs.com dev.api.qunitjs.com dev.sizzlejs.com dev.jquerymobile.com dev.api.jquerymobile.com
    ```

    Be sure to flush your DNS when you are done. On a Mac, that would be `dscacheutil -flushcache`.

1. Place the WordPress core files in the document root you chose. (Don't install it.) You can do this any number of ways:

    <ul>
      <li>Download the latest version from http://wordpress.org/latest.zip</li>
      <li>Check out the latest tag from http://core.svn.wordpress.org/tags/</li>
      <li>Clone the official WordPress Github mirror at http://github.com/wordpress/wordpress/</li>
    </ul>

1. Clone `web-base-template` into place, so you have a file tree that looks like this:

    ```
    web-base-template/
    wp-admin/
    wp-content/
    wp-includes/
    index.php
    ...
    ```

1. Copy `web-base-template/wp-config-sample.php` and move it up one directory, to `wp-config.php`. Fill in your database credentials.

1. Go to `http://dev.jquery.com` and walk through the standard WordPress installation. `web-base-template` includes a special install script that will initialize the entire network.