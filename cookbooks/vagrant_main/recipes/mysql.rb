node.set['mysql']['server_root_password'] = "root"

include_recipe "mysql::server"
package "phpmyadmin"

link "/etc/apache2/sites-enabled/phpmyadmin.conf" do
  to "/etc/phpmyadmin/apache.conf"
  notifies :reload, resources(:service => "apache2"), :delayed
end

# Create database
execute "add-mysql-db" do
  command "/usr/bin/mysql -u root -p#{node[:mysql][:server_root_password]} -e \"" +
      "CREATE DATABASE IF NOT EXISTS \\`kohana-modules\\`;" +
      "GRANT ALL PRIVILEGES ON \\`kohana-modules\\`.* TO 'kohana-modules'@'localhost' IDENTIFIED BY 'kohana-modules';\" " +
      "mysql"
  action :run
  ignore_failure true
end

# Run migrations
execute "migrate" do
  command "cd /home/vagrant/web-app && ./minion migrations:run"
  action :run
  ignore_failure true
end
