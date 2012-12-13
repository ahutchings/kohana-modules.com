# For some reason, a fresh copy of my VM has issues booting up until this runs
execute "initial-sudo-apt-get-update" do
  command "apt-get update"
end

# Making apache run as the vagrant user simplifies things when you ssh in
node.set["apache"]["user"] = "vagrant"
node.set["apache"]["group"] = "vagrant"

include_recipe "apt"
include_recipe "openssl"

include_recipe "mysql"
include_recipe "mysql::server"

include_recipe "php"
include_recipe "php::module_apc"
include_recipe "php::module_curl"
include_recipe "php::module_mysql"

include_recipe "apache2"
include_recipe "apache2::mod_php5"
include_recipe "apache2::mod_rewrite"

execute "disable-default-site" do
  command "a2dissite default"
end

web_app "localhost" do
  server_name node[:app][:server_name]
  server_aliases node[:app][:server_aliases]
  docroot node[:app][:docroot]
  kohana_environment node[:app][:kohana_environment]
end

%w{ cache logs }.each do |path|
  directory "#{node[:app][:docroot]}/../application/#{path}" do
  	mode 0755
  	owner "vagrant"
  	group "vagrant"
  end
end

template "/etc/environment" do
  source "environment.erb"
  mode 0644
  owner "root"
  group "root"
end

bash "source-etc-environment" do
  code "source /etc/environment"
end
