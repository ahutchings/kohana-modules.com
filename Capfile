load 'deploy' if respond_to?(:namespace) # cap2 differentiator
Dir['vendor/plugins/*/recipes/*.rb'].each { |plugin| load(plugin) }

require 'rubygems'
require 'railsless-deploy'
require 'capistrano/ext/multistage'
require 'capistrano/gitflow'
require 'capistrano/deepmodules'

set :stages, %w(staging production)
set :default_stage, "staging"

load 'config/deploy' # remove this line to skip loading any of the default tasks
