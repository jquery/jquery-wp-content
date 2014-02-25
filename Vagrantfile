# -*- mode: ruby -*-
# vi: set ft=ruby :
VAGRANTFILE_API_VERSION = "2"
Vagrant.require_version ">= 1.4.0"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  # use the config key as the vm identifier
  config.vm.box = "jquery-wp-content"
  config.vm.box_download_checksum_type = "sha256"
  config.vm.box_download_checksum = "3182bc50668e86022e7d50bc8c096774b49de40cfaffc77f4fad75ca6cf83d3d"

  config.vm.box_url = "http://boxes.jquery.com/jquery-wp-content.box"

  config.vm.synced_folder "./", "/var/www/wordpress/jquery-wp-content"

  # assign an ip address in the hosts network
  config.vm.network "private_network", ip: "172.27.72.27"

  # set the hostname of the vm so puppet site.pp will react
  # with the right node config
  # config.vm.provision :shell, :inline => "hostname vagrant.jquery.com"
  config.vm.hostname = "vagrant.jquery.com"

  config.vm.provider "virtualbox" do |v|
    # make sure that the name makes sense when seen in the vbox GUI
    v.name = "jQuery Sites"

    # Be nice to our users.
    v.customize ["modifyvm", :id, "--cpuexecutioncap", "50"]
  end
end
