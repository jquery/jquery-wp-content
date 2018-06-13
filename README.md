# jquery-wp-content

`jquery-wp-content` is a **custom replacement** for the `wp-content` directory which contains the plugins, themes and site configuration to run the jQuery multi-site WordPress network.

Although we recommend using the pre-built VM, if you would like to manually install `jquery-wp-content` you will find instructions for installing in the [INSTALL.md](INSTALL.md) file in this directory. We strongly recommend using the VM as it will provide the same environment as our production servers.

## Prerequisites

This setup guide assumes you have certain prerequisites installed.

* [Virtualbox >=4.3+](https://www.virtualbox.org/)
* [Vagrant >=1.4.0](https://www.vagrantup.com/)

## Vagrant and Virtualbox

We recommend the combination of Virtualbox and [Vagrant](https://www.vagrantup.com/intro/index.html) for a local development environment. Vagrant simplifies download, installation, and management of the local environment VM with a few commands.

### Setup

From the root of your `jquery-wp-content` clone, run the following command:

```
vagrant up
```

This will start the VM for you, the output should look similar to what you see below.

```
Bringing machine 'default' up with 'virtualbox' provider...
[default] Box 'jquery-wp-content' was not found. Fetching box from specified URL for
the provider 'virtualbox'. Note that if the URL does not have
a box for this provider, you should interrupt Vagrant now and add
the box yourself. Otherwise Vagrant will attempt to download the
full box prior to discovering this error.
Downloading box from URL: http://boxes.jquery.com/jquery-wp-content.box
Calculating and comparing box checksum...ime remaining: 0:00:01)
Extracting box...
Successfully added box 'jquery-wp-content' with provider 'virtualbox'!
[default] Importing base box 'jquery-wp-content'...
[default] Matching MAC address for NAT networking...
[default] Setting the name of the VM...
[default] Clearing any previously set forwarded ports...
[default] Clearing any previously set network interfaces...
[default] Preparing network interfaces based on configuration...
[default] Forwarding ports...
[default] -- 22 => 2222 (adapter 1)
[default] Running 'pre-boot' VM customizations...
[default] Booting VM...
[default] Waiting for machine to boot. This may take a few minutes...
[default] Machine booted and ready!
[default] Setting hostname...
[default] Configuring and enabling network interfaces...
[default] Mounting shared folders...
[default] -- /vagrant
[default] -- /var/www/wordpress/jquery-wp-content
```

Complete the installation process by pointing your browser at http://vagrant.jquery.com/. Feel free to use any username/password/email combination that you choose, though keep note of the username and password as you will need them to deploy to your local VM.

You do not need to configure your `/etc/hosts` file for `vagrant.*` because `jquery.com`'s DNS handles this for you. However, if you plan to work offline, you can use the following rules:

```
172.27.72.27 vagrant.jquery.com vagrant.api.jquery.com vagrant.blog.jquery.com vagrant.books.jquery.com vagrant.codeorigin.jquery.com vagrant.learn.jquery.com vagrant.plugins.jquery.com
172.27.72.27 vagrant.jqueryui.com vagrant.api.jqueryui.com vagrant.blog.jqueryui.com
172.27.72.27 vagrant.jquerymobile.com vagrant.api.jquerymobile.com vagrant.blog.jquerymobile.com
172.27.72.27 vagrant.jquery.org vagrant.brand.jquery.org vagrant.contribute.jquery.org vagrant.events.jquery.org vagrant.irc.jquery.org vagrant.meetings.jquery.org
172.27.72.27 vagrant.qunitjs.com vagrant.api.qunitjs.com
172.27.72.27 vagrant.sizzlejs.com
```

### Stopping and Restarting the Virtual Machine

When you're not working on `jquery-wp-content` you'll probably want to run `vagrant halt` to turn off the VM to save yourself some system resources. Alternatively you can suspend the VM by using `vagrant suspend`. You can always start the VM by running `vagrant up`.

### Clean up

Should you need to recover some harddrive space you can safely run the `vagrant destroy` command from within your `jquery-wp-content` clone. This will delete the VM image from your hard drive. **You will not lose any work by doing this.**

## Auto-Updates

Changes pushed to master are automatically pulled onto the stage domain.
