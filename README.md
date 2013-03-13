# kohana-modules.com

This is the source for [kohana-modules.com](http://kohana-modules.com).

## Installing

* Create a MySQL user and database to use with the application.
* Modify the application/config files to match your local environment.
* Create database tables with `./minion migrations:run`.
* Configure your web server to serve the application.
* Generate an OAuth token so that the app can make more than 60 requests/hour:

`curl -u 'YOURUSERNAME' -d '{"scopes":[],"note":"YOURDEVHOSTNAME"}' \
    https://api.github.com/authorizations`

An example Apache VirtualHost entry:

    <VirtualHost *:80>
        ServerName kohana-modules.localhost
        DocumentRoot "/srv/www/kohana-modules.com"

        SetEnv KOHANA_ENV development
        SetEnv DB1_HOST 127.0.0.1
        SetEnv DB1_NAME kohana-modules
        SetEnv DB1_USER kohana-modules
        SetEnv DB1_PASS kohana-modules
        SetEnv GITHUB_OAUTH_TOKEN YOUR_GENERATED_TOKEN

        <Directory "/srv/www/kohana-modules.com">
            Order allow,deny
            Allow from all
            Options FollowSymLinks
            AllowOverride All
        </Directory>
    </VirtualHost>

## Using

To import new modules from the [modules repository on GitHub](https://github.com/ahutchings/kohana-modules),
run `./minion module:import` from the project root.

To update module metadata from GitHub, run `./minion module:sync`.

To add new Kohana-related repositories to the moderation queue, run `./minion module:discover`.
Modules must be manually inspected and added to the modules repository.

## Adding new modules to kohana-modules.com

To add a new module, please fork the [modules repository](https://github.com/ahutchings/kohana-modules)
and add the module as a submodule, then send a pull request.
