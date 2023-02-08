Balero CMS 
==========

We are migrating CMS to PHP 7+. Instead you can try the legacy CMS (PHP 5) with Docker.
Here is a development version through Docker Compose Setup!

DOWNLOAD DOCKER
===============

https://www.docker.com/products/docker-desktop/

    $ docker compose version
    If the output shows the release downloaded in Step 3, you have successfully installed the package.


DOWNLOAD CMS
============

    $ git clone git@github.com:anibalg0mez/balerocms-src.git
    $ git checkout development

SETUP
=====

    $ cd balerocms-src
    $ docker-compose up

Run http://localhost:8002/ on your borwser. Follow installer setup. Done!

CREDENTIALS
===========

go to your ./balerocms-src/build/mysql/DockerFile to view your credentials!

NOTE
====

This is a development version, if you want to download a stable version go to:

https://github.com/anibalg0mez/balerocms-src/releases

Referers
========

https://doc4dev.com/en/create-a-web-site-php-apache-mysql-in-5-minutes-with-docker/

==========

Friendly contact:

balerocms@gmail.com