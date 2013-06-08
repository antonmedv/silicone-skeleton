Silicone Skeleton
=================

Silicone Skeleton is Silex Framework Edition Skeleton.



Install
=======

Open directory
--------------
Open directory using for writing caches, logs, ext. So you must give write permissions to write for www-data user.
Example:
```
sudo chmod +a "www-data allow delete,write,append,file_inherit,directory_inherit" app/open/
sudo chmod +a "[your user name] allow delete,write,append,file_inherit,directory_inherit" app/open/
```

Console
-------
Add permissions to execute console.
Example:
```
chmod +x app/console
```

Database
--------
After configuring console run next commands to create example database.
```
app/console database:create
app/console schema:create
```


TODO
----
* Documentation
* Tests
* SwiftMailer

