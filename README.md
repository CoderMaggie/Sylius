CJ-MaX
------

CJ-MaX powered by Sylius.

[![Build status...](https://circleci.com/gh/Lakion/CJ-MaX.svg?style=svg&circle-token=8a59c9388cde465be97edf9941954454a76bffab)]
(https://circleci.com/gh/Lakion/CJ-MaX)
[![Code quality...](https://scrutinizer-ci.com/g/Lakion/CJ-MaX/badges/quality-score.png?b=master&s=f837ea39be33b8dbfd0b5e8de1fc8f12dade2ad8)]
(https://scrutinizer-ci.com/g/Lakion/CJ-MaX/)

Requirements
============

* ``PHP 5.5.9 or higher``
* ``Composer``
* ``Bower (NodeJS)``

Installation
============

```bash
$ git clone git@github.com:Lakion/CJ-MaX.git CJ-MaX
$ cd CJ-MaX

$ brew install node
$ npm install -g bower
$ npm install --global gulp-cli
$ bower install
$ npm install
$ gulp

$ wget http://getcomposer.org/composer.phar
$ php composer.phar install

$ php app/console doctrine:database:create
$ php app/console doctrine:schema:create
$ php app/console doctrine:fixtures:load --fixtures src/AppBundle/DataFixtures/ORM/
$ php app/console doctrine:phpcr:repository:init
$ php app/console doctrine:phpcr:fixtures:load --fixtures src/AppBundle/DataFixtures/PHPCR/
$ php app/console sylius:search:index
$ php app/console sylius:install:assets
```

Testing
=======

```bash
$ app/console do:da:cr --env test
$ app/console do:sc:cr --env test
$ bin/behat
$ bin/phpspec run --fpretty
```
