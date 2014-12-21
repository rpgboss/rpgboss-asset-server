RPGBOSS Asset Server
==============

Pre-requisites
--------

+ apache2
+ composer isntalled
+ php 5.4
+ php-gd
+ php-mysql
+ mod_rewrite enabled

Setting up database
--------
Inside of fuel/app/development/db.php you can change the settings.

Deployment
--------
Put everything on to your server.

Install dependencies through
```
php composer.phar install
```

Use a virtualhost.

Example for apache:
```

Listen 80
<VirtualHost *:80>
    ServerName your-prefered-domain.com
    ErrorLog /www/rpgboss-asset-server/error.log
    DocumentRoot /www/rpgboss-asset-server/public
    <Directory "/www/rpgboss-asset-server/public">
            Order allow,deny
            Allow from all
            Options Indexes FollowSymLinks MultiViews
            AllowOverride all
            # New directive needed in Apache 2.4.3:
    </Directory>
</VirtualHost>
```

To create the database structure 
```
php oil refine migrate:up
```

FuelPHP Links
--------

* Version: 1.7
* [Website](http://fuelphp.com/)
* [Release Documentation](http://docs.fuelphp.com)
* [Release API browser](http://api.fuelphp.com)
* [Development branch Documentation](http://dev-docs.fuelphp.com)
* [Development branch API browser](http://dev-api.fuelphp.com)
* [Support Forum](http://fuelphp.com/forums) for comments, discussion and community support