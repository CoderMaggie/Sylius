# CJ-MaX deployment configuration.

# Capifony documentation: http://capifony.org
# Capistrano documentation: https://github.com/capistrano/capistrano/wiki

# Be more verbose by uncommenting the following line.
# logger.level = Logger::MAX_LEVEL

set :domain,        "cjmax.demo.lakion.com"
set :stages,        %w(production staging)
set :default_stage, "production"
set :stage_dir,     'app/config/deploy'

require 'capistrano/ext/multistage'

set :application, "CJ-MaX"
set :user,        "lakion"

role :web, domain, :primary => true
role :app, domain, :primary => true
role :db,  domain, :primary => true

set :scm,        :git
set :repository, "git@github.com:Lakion/CJ-MaX.git"
set :branch,     fetch(:branch, "master")
set :deploy_via, :remote_cache

ssh_options[:forward_agent] = true

set :use_composer,   true
set :update_vendors, false

set :dump_assetic_assets, true
set :cache_warmup,        true
set :assets_install,      true

set :writable_dirs,  ["app/cache", "app/logs", "web/media"]
set :webserver_user, "www-data"
set :permission_method,   :acl
set :use_set_permissions, true
set :controllers_to_clear, ['app_*.php']

set :shared_files,    ["app/config/parameters.yml", "web/.htaccess", "web/robots.txt"]
set :shared_children, ["app/logs", "web/media"]

set :use_sudo, false

set :keep_releases, 3

#before 'symfony:composer:install', 'symfony:copy_vendors'

namespace :symfony do
  desc "Copy vendors from previous release"
  task :copy_vendors, :except => { :no_release => true } do
    capifony_pretty_print "--> Copying vendors from previous release"
    run "cp -a #{previous_release}/vendor #{latest_release}/"
    capifony_puts_ok
  end
end

namespace :bower do
  desc "Install assets via Bower"
  task :install, :except => { :no_release => true } do
    capifony_pretty_print "--> Installing assets via Bower"
    run "cd #{latest_release} && bower install"
    capifony_puts_ok
  end
end

before 'symfony:cache:warmup', 'bower:install'
before "symfony:assetic:dump", "symfony:doctrine:migrations:migrate"
after "deploy:update", "deploy:cleanup"
