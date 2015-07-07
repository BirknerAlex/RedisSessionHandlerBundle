Getting Started With RedisSessionHandlerBundle
==============================================

## Prerequisites

+ Symfony 2.1 or newer
+ PHP 5.4 or newer
+ PHP PhpRedis extension

## Installation

Installation is a quick 3 steps process:

1. Install RedisSessionHandlerBundle
2. Enable the bundle
3. Update your config


### Step 1: Install RedisSessionHandlerBundle

The preferred way to install this bundle is to rely on [Composer](http://getcomposer.org).
Just check on [Packagist](http://packagist.org/packages/tyrola/redis-session-handler-bundle) the version you want to install (in the following example, we used "dev-master") and add it to your `composer.json`:

``` js
{
    "require": {
        // ...
        "tyrola/redis-session-handler-bundle": "1.0"
    }
}
```

### Step 2: Enable the bundle

Open up the AppKernel.php file and add the bundle to the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new BirknerAlex\RedisSessionHandlerBundle\RedisSessionHandlerBundle(),
    );
}
```


### Step 3: Update your config

Open the app/config/config.yml File and add these following lines:

    # app/config/config.yml

    framework:
        framework:
            handler_id:  redis.session.handler

    redis_session_handler:
        host: "127.0.0.1" # Your redis hostname
        port: 6379 # Your redis port
        database: 0 # Your redis database id (Default is 0)
        db_options: ~

You're done!

### Optional: More configuration

    # app/config/config.yml
    redis_session_handler:
        password: "MySecretPassword" # Provide a password if requirepass is enabled
        db_options:
            expiretime: 1800 # Session lifetime in seconds
            prefix: "session_" # Custom prefix for sessions
