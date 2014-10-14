OrnicarGravatarBundle
=====================

[![Build Status](https://secure.travis-ci.org/ornicar/GravatarBundle.png)](http://travis-ci.org/ornicar/GravatarBundle)

Installation
------------

  1. Add this bundle to your projects composer.json

  ```json
  "require": { 
      "ornicar/gravatar-bundle" : "dev-master"
  }
  ```

  2. Run composer update to install the bundle and regenerate the autoloader
  
  ```bash
  $ composer.phar update
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


Cache configuration
-------------------

You can inject a cache service based on the Doctrine\Common\Cache (e.g. ApcCache)

Add the following configuration to your bundle:

```yaml
services:
    acme_cache_adapter:
    class: "Doctrine\Common\Cache\ApcCache"
cache:
    adapter: acme_cache_adapter
    lifetime: 30
```

The lifetime specifies for how many seconds to cache the Gravatar image.
We use the Guzzle HTTP client to retrieve the gravatar images.

Cache route
-----------

We use the Symfony2 router component.
You should configure the route to serve the cached Gravatar images.
To include the route, you will have to add something like this to your `routing.yml` file.

```yaml
gravatar_image:
    resource: "@OrnicarGravatarBundle/Resources/config/routing.xml"
    prefix:   /gravatar/
```
