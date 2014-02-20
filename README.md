# jQuery.com jquery-wp-content Virtual Machine Environment

`jquery-wp-content` is a **custom replacement** for the `wp-content` directory which contains the plugins, themes and site configuration to run the jQuery multi-site WordPress network.

If you would like instructions for installing this plugin you can find them in the [INSTALL.md](INSTALL.md) file in this directory.

## Prerequisites

This install guide assumes you already have certain prerequisites already configured within your environment.

* Virtualbox >=4.3+
* Vagrant >=1.4.0

## Installation

Assuming you've met the prerequisites the process of starting the VM is simple.

From the root of your `jquery-wp-content` clone run the following command:

	```
	vagrant up
	```

Complete the installation process by pointing your browser at (http://vagrant.jquery.com/). Feel free to use any username/password/email combination that you choose, though keep note of the username and password as you will need to be able to deploy to your local vm.

Yes, it's really that easy!

You do not need to configure your `/etc/hosts` file for `vagrant.*` because `jquery.com`'s DNS handles this for you. However, if you plan to work offline, you can use the following rules:

	```
	172.27.72.27 vagrant.jquery.com vagrant.api.jquery.com vagrant.blog.jquery.com vagrant.books.jquery.com vagrant.codeorigin.jquery.com vagrant.learn.jquery.com vagrant.plugins.jquery.com
	172.27.72.27 vagrant.jqueryui.com vagrant.api.jqueryui.com vagrant.blog.jqueryui.com
	172.27.72.27 vagrant.jquerymobile.com vagrant.api.jquerymobile.com vagrant.blog.jquerymobile.com
	172.27.72.27 vagrant.jquery.org vagrant.brand.jquery.org vagrant.contribute.jquery.org vagrant.events.jquery.org vagrant.irc.jquery.org vagrant.meetings.jquery.org
	172.27.72.27 vagrant.qunitjs.com vagrant.api.qunitjs.com
	172.27.72.27 vagrant.sizzlejs.com
	```

## Stopping the Virtual Machine

When you're not working on `jquery-wp-content` you'll probably want to run `vagrant halt` to turn off the VM to save yourself some system resources. Alternatively you can suspend the VM by using `vagrant suspend`

## Unstallation

Should you need to recover some harddrive space you can safely run the `vagrant destroy` command from within your `jquery-wp-content` clone. This will delete the VM image from your hard drive. **You will not lose any work by doing this.**

## Copyright

Copyright 2014 jQuery Foundation and other contributors. All rights reserved.

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
