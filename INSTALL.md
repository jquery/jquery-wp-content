# jquery-wp-content

`jquery-wp-content` is a **custom replacement** for the `wp-content` directory which contains the plugins, themes and site configuration to run the jQuery multi-site WordPress network.

## Warning

Configuring your own services can result in different configurations from the prodution environment. It is strongly encouraged to use the virtual machine environment as described in [README.md](README.md).

## Prerequisites

This install guide assumes you have certain prerequisites already configured within your environment.

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
	php_value memory_limit 256M
	<Directory /srv/www/jquery>
		Options All
		AllowOverride All
		Order allow,deny
		Allow from all
	</Directory>
</VirtualHost>
```

Make sure that virtual hosts are enabled as well:

```
NameVirtualHost *:80
```

Both blocks of code should be pasted into `extra/httpd-vhosts.conf`.
Be sure to check `httpd.conf` to verify there is a line that includes
`httpd-vhosts.conf`. It may already exist, but be commented out.

Check `httpd.conf` to ensure that the PHP module is enabled as well.

You do not need to configure your `/etc/hosts` file for `local.*` because `jquery.com`'s DNS handles this for you. However, if you plan to work offline, you can use the following rules:

```
127.0.0.1 local.jquery.com local.api.jquery.com local.blog.jquery.com local.books.jquery.com local.codeorigin.jquery.com local.learn.jquery.com local.plugins.jquery.com
127.0.0.1 local.jqueryui.com local.api.jqueryui.com local.blog.jqueryui.com
127.0.0.1 local.jquerymobile.com local.api.jquerymobile.com local.blog.jquerymobile.com
127.0.0.1 local.jquery.org local.brand.jquery.org local.contribute.jquery.org local.events.jquery.org local.irc.jquery.org local.meetings.jquery.org
127.0.0.1 local.qunitjs.com local.api.qunitjs.com
127.0.0.1 local.sizzlejs.com
```

1. Place the WordPress core files **at** the document root you chose. For example, if you used `/srv/www/jquery`, you should unzip or clone WordPress directly into that directory, *not* a directory below it. **Do not install WordPress.** You can do this any number of ways:
	* Download the latest version from https://wordpress.org/latest.zip
	* Check out the latest tag from https://core.svn.wordpress.org/tags/
	* Clone the official WordPress Github mirror at https://github.com/WordPress/WordPress

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

1. Create a MySQL database and user. You can choose any name you want for both. Follow the [WordPress instructions](https://codex.wordpress.org/Installing_WordPress#Step_2:_Create_the_Database_and_a_User) for a step by step guide.

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

1. Restart your web server so the changes above are in use.

1. Go to `http://local.jquery.com` and walk through the standard WordPress installation. `jquery-wp-content` includes a special install script that will initialize the entire network.

1. Be sure to have node >= 0.8 installed on your system.  Some sites, such as download.jqueryui.com, require that version or greater.
