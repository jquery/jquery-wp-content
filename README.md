# jQuery.com jquery-wp-content

This is a set of plugins, themes, and configuration files for jQuery's website infrastructure, which is powered by WordPress. It is designed as a custom content directory. So think of `jquery-wp-content` as your `wp-content` directory.

## Prerequisites

This install guide assumes you already have certain prerequisites already configured within your environment.

* Apache
* Mysql
* PHP

## Installation

1. Configure your local webserver with a virtual host that covers the relevant jQuery domains, such as `*.jquery.com` and `*.jqueryui.com`, all pointing to the same root. For example, in Apache:

	```
	<VirtualHost *:80>
		ServerName local.jquery.com
		ServerAlias *.jquery.com *.jqueryui.com *.jquery.org *.qunitjs.com *.sizzlejs.com *.jquerymobile.com
		DocumentRoot "/srv/www/jquery"
		<Directory /srv/www/jquery>
			Options All
			AllowOverride All
			Order allow,deny
			Allow from all
		</Directory>
	</VirtualHost>
	```

	You do not need to configure your `/etc/hosts` file for `local.*` because `jquery.com`'s DNS handles this for you.

1. Place the WordPress core files **at** the document root you chose. For example, if you used `/srv/www/jquery`, you should unzip or clone WordPress directly into that directory, *not* a directory below it. **Do not install WordPress.** You can do this any number of ways:
	* Download the latest version from http://wordpress.org/latest.zip
	* Check out the latest tag from http://core.svn.wordpress.org/tags/
	* Clone the official WordPress Github mirror at http://github.com/wordpress/wordpress/

1. Clone `jquery-wp-content` inside of the directory where you put WordPress, so you have a file tree that looks like this:

	```
	├── jquery
	│   ├── gw-resources
	│   ├── index.php
	│   ├── jquery-wp-content
	│   ├── license.txt
	│   ├── readme.html
	│   ├── wp-activate.php
	│   ├── wp-admin
	│   ├── wp-blog-header.php
	│   ├── wp-comments-post.php
	│   ├── wp-config-sample.php
	│   ├── wp-content
	│   ├── ...
	│   └── xmlrpc.php
	```

1. Copy `jquery-wp-content/wp-config-sample.php` and move it up one directory, to `wp-config.php`. Fill in your database credentials.

1. Create an .htaccess file with the following content into that same document root:

	```
	RewriteEngine On
	RewriteBase /
	RewriteRule ^index\.php$ - [L]

	RewriteRule ^resources/?$ index.php [L]
	RewriteRule ^resources/(.+) gw-resources/%{HTTP_HOST}/$1 [L]

	# Add a trailing slash to the wp-admin of a subsite.
	RewriteRule ^([_0-9a-zA-Z\.-]+/)?wp-admin$ $1wp-admin/ [R=301,L]

	RewriteCond %{REQUEST_FILENAME} -f [OR]
	RewriteCond %{REQUEST_FILENAME} -d
	RewriteRule ^ - [L]

	# Handle wp-admin, wp-includes, and root PHP files for subsites.
	RewriteRule  ^[_0-9a-zA-Z\.-]+/((wp-admin|wp-includes).*) $1 [L]
	RewriteRule  ^[_0-9a-zA-Z\.-]+/(.*\.php)$ $1 [L]

	RewriteRule . index.php [L]
	```

1. Make sure that you have assigned your WordPress files and directories the correct permissions.
For example, if your WordPress files are in the directory ```wordpress```, and you are running Apache under Mac OS X with the ```_www``` user:

	```
	sudo chown -R _www wordpress
	sudo chmod -R g+w wordpress
	```

1. Go to `http://local.jquery.com` and walk through the standard WordPress installation. `jquery-wp-content` includes a special install script that will initialize the entire network.

1. Be sure to have node >= 0.8 installed on your system.  Some sites, such as download.jqueryui.com, require that version or greater.

## Auto-Updates
Changes pushed to master will be pulled onto the stage domain.

## Copyright

Copyright 2012 jQuery Foundation and other contributors. All rights reserved.

The `jquery-wp-content` repository contains themes for rendering all jQuery Foundation web sites.

### What is licensed

The contents of the web sites that run on top of `jquery-wp-content` are all available under the terms of the MIT license ( http://jquery.org/license ).

Special exception: Code samples are given away for you to freely use, for any purpose. For code samples in API sites
and Learn articles (unlike the source code of jQuery projects) you don't even have to say where you got the code from.
Just use it.

The PHP files in the `jquery-wp-content` repository are a derivative work of WordPress, and available under the
terms of the GPL license ( http://codex.wordpress.org/License ).

### What is not licensed

The design, layout, and look-and-feel of the `jquery-wp-content` repository, including all CSS, images, and
icons, are copyright jQuery Foundation, Inc. and are not licensed for use. Designs and CSS from `jquery-wp-content` may not be used on any site, personal or commerical, without prior written consent from the jQuery Foundation. `jquery-wp-content` contains CSS, images, and icons that are copyrighted by other individuals; those items are licensed under their original terms.

The only permitted (and encouraged) exception to "use" is the case of cloning/forking this repository in order to create a local development environment to facilitate making contributions to jQuery Foundation websites.
