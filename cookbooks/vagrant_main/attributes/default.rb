default[:app][:server_name]        = "localhost"
default[:app][:server_aliases]     = ["*.localhost"]
default[:app][:docroot]            = "/home/vagrant/web-app/public"
default[:app][:kohana_environment] = "development"
default[:app][:extra_packages]     = []
default[:app][:github_oauth_token] = '0b54bc55f7d942e1b20975e590ab45676dd01af8'

default[:mysql][:database] = "kohana-modules"
default[:mysql][:username] = "kohana-modules"
default[:mysql][:password] = "kohana-modules"
