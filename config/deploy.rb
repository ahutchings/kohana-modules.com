# --------------------------------------------
# General
# --------------------------------------------
set :application, "kohana-modules"
set :domain,      "kohana-modules.com"
server domain, :app, :web, :db, :primary => true

# --------------------------------------------
# Repository
# --------------------------------------------
set :scm, :git
set :repository, "git@github.com:ahutchings/kohana-modules.com.git"
set :deploy_via, :remote_cache
set :branch, "3.1/master"
set :copy_exclude, [".git", ".gitignore", ".gitmodules"]

# --------------------------------------------
# SSH
# --------------------------------------------
set :user, "andrew"
set :use_sudo, false
ssh_options[:forward_agent] = true # Use local keys

# --------------------------------------------
# Tasks
# --------------------------------------------
namespace :deploy do
    task :start do ; end
    task :restart do ; end
    task :stop do ; end
    task :finalize_update do ; end

    task :migrate do
        run "KOHANA_ENV=#{kohana_env} php #{latest_release}/public/index.php --uri=minion/db:migrate --versions=TRUE"
    end
    
    namespace :shared_files do
        task :setup do
            run "mkdir -m 0777 -p #{shared_path}/cache"
            run "mkdir -m 0777 -p #{shared_path}/logs"
            run "mkdir -p #{shared_path}/config"
        end
        
        task :symlink do
            # Make sure the cache, config, and log directories do not exist.
            run "rmdir #{latest_release}/application/cache"
            run "rm -rf #{latest_release}/application/config"
            run "rmdir #{latest_release}/application/logs"

            # Symlink the shared directories to the directories in your application.
            run "ln -s #{shared_path}/cache #{latest_release}/application/cache"
            run "ln -s #{shared_path}/config #{latest_release}/application/config"
            run "ln -s #{shared_path}/logs #{latest_release}/application/logs"
        end
    end
end

namespace :module do
    task :import do
        run_task("import")
    end

    task :discover do
        run_task("discover")
    end
    
    task :sync do
        run_task("sync")
    end
    
    def run_task(task)
        run "KOHANA_ENV=#{kohana_env} php #{latest_release}/public/index.php --uri=minion/module:#{task}"
    end
end

after "deploy:setup", "deploy:shared_files:setup"
after "deploy:symlink", "deploy:shared_files:symlink"
after :deploy, 'deploy:cleanup' # Remove old releases
after :deploy, 'deploy:migrate' # Run new migrations
