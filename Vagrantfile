Vagrant::Config.run do |config|
  config.vm.box = "base"
  config.vm.box_url = "http://files.vagrantup.com/lucid32.box"

  config.vm.forward_port 80, 8080

  # Share an additional folder to the guest VM. The first argument is
  # an identifier, the second is the path on the guest to mount the
  # folder, and the third is the path on the host to the actual folder.
  # config.vm.share_folder "v-data", "/vagrant_data", "../data"

  # Enable provisioning with chef solo, specifying a cookbooks path, roles
  # path, and data_bags path (all relative to this Vagrantfile), and adding
  # some recipes and/or roles.
  config.vm.provision :chef_solo do |chef|
    chef.cookbooks_path = "../my-recipes/cookbooks"
    chef.roles_path = "../my-recipes/roles"
    chef.data_bags_path = "../my-recipes/data_bags"

    # chef.add_recipe "apt"
    chef.add_recipe "mysql"
    # chef.add_role "web"

    # You may also specify custom JSON attributes:
    chef.json = { :mysql_password => "kohana-modules" }
  end
end
