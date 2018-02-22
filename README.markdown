OrnicarGravatarBundle
=====================

Note: This is the new home for OrnicarGravatarBundle. The package name and namespace will not change. But the code
will be maintained.

[![Build Status](https://travis-ci.org/henrikbjorn/GravatarBundle.svg?branch=master)](https://travis-ci.org/henrikbjorn/GravatarBundle)

Installation
------------

  1. Add this bundle to your projects composer.json

  ```json
  "require": { 
      "ornicar/gravatar-bundle" : "~1.0"
  }
  ```

  2. Run composer update to install the bundle and regenerate the autoloader
  
  ```bash
  $ composer update ornicar/gravatar-bundle
  ```

  3. Add this bundle to your application's kernel:

  ```php
  // application/ApplicationKernel.php
  public function registerBundles()
  {
      return array(
          // ...
          new Ornicar\GravatarBundle\OrnicarGravatarBundle(),
          // ...
      );
  }
  ```

  4. Configure the `gravatar` service, templating helper and Twig extension in your config:

  ```yaml
  # application/config/config.yml
  ornicar_gravatar: ~
  ```

  5. If you always have some default for your gravatars such as size, rating or default it can be configured in your config

  ```yaml
  # application/config/config.yml
  ornicar_gravatar:
    rating: g
    size: 80
    default: mm
  ```

Usage
-----

All you have to do is use the helper like this example:

```html
<img src="<?php echo $view['gravatar']->getUrl('alias@domain.tld') ?>" />
```

Or with parameters:

```html
<img src="<?php echo $view['gravatar']->getUrl('alias@domain.tld', '80', 'g', 'defaultimage.png', true) ?>" />
```

The only required parameter is the email adress. The rest have default values.

If you use twig you can use the helper like this example:

```
<img src="{{ gravatar('alias@domain.tld') }}" />
```

Or if you want to check if a gravatar email exists:

```
{% if gravatar_exists('alias@domain.tld') %}
  The email is an gravatar email
{% endif %}
```

Or with parameters:

```
<img src="{{ gravatar('alias@domain.tld', size, rating, default, secure) }}" />
```

For more information [look at the gravatar implementation pages][gravatar].

[gravatar]: http://en.gravatar.com/site/implement/

Caching
-------

You can cache calls to Gravatar API, using [DoctrineCache](https://symfony.com/doc/current/bundles/DoctrineCacheBundle/index.html).

If you configure a cache provider, you can pass it to bundle options.
For example, suppose that you configured an APC cache provider named "apc_cache", like explained in
[documentation example](https://symfony.com/doc/current/bundles/DoctrineCacheBundle/usage.html#service-aliases).
You can specify such service name in bundle configuration:


```yaml
# application/config/config.yml
ornicar_gravatar:
    cache_service: apc_cache
    ttl: 600
```

The `ttl` option is the cache time to live (in this case, the cache is lasting for ten minutes).
Default value for `ttl` is 300 (correspondig to five minutes).
