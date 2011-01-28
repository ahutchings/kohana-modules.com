# kohana-modules.com

This is the source for [kohana-modules.com](http://kohana-modules.com).

## Installing

* Create a MySQL user and database to use with the application.
* Create the database tables using the schema.sql file located in the project root.
* Modify the application/config files to match your local environment.
* Run available migrations with `./minion db:migrate`.
* Configure your web server to serve the application.

An example Apache VirtualHost entry

    <VirtualHost *>
    	ServerName kohana-modules.com

    	SetEnv KOHANA_ENV development

    	DocumentRoot /srv/www/kohana-modules.com/current/public

    	<Directory "/srv/www/kohana-modules.com/current/public">
    		Options FollowSymLinks
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
