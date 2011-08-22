Installation
============

  1. Add this bundle to your project as Git submodules:

          $ git submodule add git://github.com/ornicar/GravatarBundle.git vendor/bundles/Ornicar/GravatarBundle

  2. Add the Ornicar namespace to your autoloader

          // app/autoload.php

          $loader->registerNamespaces(array(
             'Ornicar' => __DIR__.'/../vendor/bundles',
             // your other namespaces
          );

  3. Add this bundle to your application's kernel:

          // application/ApplicationKernel.php
          public function registerBundles()
          {
              return array(
                  // ...
                  new Ornicar\GravatarBundle\OrnicarGravatarBundle(),
                  // ...
              );
          }

  4. Configure the `gravatar` service, templating helper and Twig extension in your config:

          # application/config/config.yml
          ornicar_gravatar: ~

  5. If you always have some default for your gravatars such as size, rating or default it can be configured in your config

         # application/config/config.yml
         ornicar_gravatar:
            rating: g
            size: 80
            default: mm

Usage
=====

All you have to do is use the helper like this example:

      <img src="<?php echo $view['gravatar']->getUrl('alias@domain.tld') ?>" />

Or with parameters:

      <img src="<?php echo $view['gravatar']->getUrl('alias@domain.tld', '80', 'g', 'defaultimage.png', true) ?>" />

The only required parameter is the email adress. The rest have default values.

If you use twig you can use the helper like this exemple:

      {{ gravatar('alias@domain.tld') }}

Or if you want to check if a gravatar email exists:

      {% if gravatar_exists('alias@domain.tld') %}
            The email is an gravatar email
      {% endif %}

Or with parameters:

      {{ gravatar('alias@domain.tld', size, rating, default, secure) }}

For more information [look at the gravatar implementation pages][gravatar].

[gravatar]: http://en.gravatar.com/site/implement/
