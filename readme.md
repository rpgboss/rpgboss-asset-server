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