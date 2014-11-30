RPGBOSS Asset Server
==============

Pre-requisites
--------

+  git
+  sbt - http://www.scala-sbt.org/
+  Java 6, 7, or 8.
+  Sass gem installed globally ( http://sass-lang.com/ )
+  Activator usable in command line (https://typesafe.com/activator)

Run the server
--------
    ```
    > activator run
    ```

It can be accessed using http://127.0.0.1:9000

Preventing sass errors (first run)
--------
Copy your
+ sass
+ sass.bat

in your root directory if you are on windows

Setting up database
--------

Inside of conf/application.conf you can change the settings to your environment.
MYSQL as default

	```
	# Database configuration
	# ~~~~~
	# You can declare as many datasources as you want.
	# By convention, the default datasource is named `default`
	#
	# db.default.driver=org.h2.Driver
	# db.default.url="jdbc:h2:mem:play"
	# db.default.user=sa
	# db.default.password=""
	db.default.driver="com.mysql.jdbc.Driver"
	db.default.url="mysql://root:root@127.0.0.1:8889/assetserver" # <-- your connection string
	```

Deployment
--------

First you need to create the standalone package
	```
	sbt dist
	```
then you have there a zip file in /target/universal called rpgboss-asset-server-x.x.zip

Next you have to unzip the file on your server.
Upload the run-asset-server.sh inside of the rpgboss-asset-server-x.x folder.

Edit the application.conf in rpgboss-asset-server-x.x/conf folder
	```
	# Database configuration
	# ~~~~~
	# You can declare as many datasources as you want.
	# By convention, the default datasource is named `default`
	#
	# db.default.driver=org.h2.Driver
	# db.default.url="jdbc:h2:mem:play"
	# db.default.user=sa
	# db.default.password=""
	db.default.driver="com.mysql.jdbc.Driver"
	db.default.url="mysql://root:root@127.0.0.1:3306/assetserver"
	```

Make sure that you have a mysql server installed and a assetserver database created.

Make the run-asset-server.sh executable
	```
	chmod +x run-asset-server.sh
	```

Test you can run the server
	´´´
	./run-asset-server.sh
	´´´

## Running as daemon

#### Edit the run-asset-server.sh

You have to set the path to the bin file as absolute path everywhere.
	```
	/home/root/rpgboss-asset-server-1.0/bin/rpgboss-asset-server -J-server -J-Xms32M -J-Xmx64M -Dhttp.port=80 -Dconfig.file=/home/root/rpgboss-asset-server-1.0/conf/application.conf -DapplyEvolutions.default=true
	```

#### Debian

https://www.digitalocean.com/community/tutorials/how-to-install-and-manage-supervisor-on-ubuntu-and-debian-vps

Install supervisor

Create a file rpgboss.conf with similar content:
	```
	[program:rpgboss]
	command=sh /home/root/rpgboss-asset-server-1.0/run-asset-server.sh
	autostart=true
	autorestart=true
	stderr_logfile=/var/log/long.err.log
	stdout_logfile=/var/log/long.out.log
	```

Follow the tutorial and it should run now permanently.