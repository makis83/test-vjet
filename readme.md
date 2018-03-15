## Installation
Run all commands inside working dir.

### 1. Install composer

https://getcomposer.org/download/

```
$ php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
$ php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
$ php composer-setup.php
$ php -r "unlink('composer-setup.php');"
```

### 2. Set up classes autloading
```
$ ./composer.phar dump-autoload
```

### 3. Deal with MySQL database
Create a new MySQL database ([repo](db.sql) or [pastebin](https://pastebin.com/AaBT4rHJ)).  
In case if you need the MySQL scheme in mwb format, it can be found at project's root dir ([db.mwb](db.mwb)).

### 4. Edit config file
[Provide your own credentials](components/Config.php) for working with MySQL database.