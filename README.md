PhpAirPlay
==========

Mirror your desktop to an Apple TV device using this PHP command line utility.


Usage
-----

Just run this command, but replace the IP address `192.168.0.69` with
whatever the IP address of your Apple TV is.

    $ php airplay stream:to 192.168.0.69


Most of the time, you can simply run.

    $ php airplay stream:to AppleTV.local


Installation
------------

This utility requires the following to be installed on your machine.

  * PHP 5.4
  * cURL
  * Git
  * Shutter

Then run these commands from the terminal to install.

    $ git clone https://github.com/archfizz/phpairplay.git
    $ cd phpairplay
    $ curl -sS https://getcomposer.org/installer | php
    $ php composer.phar install


Add to existing Composer project
--------------------------------

Add to your composer.json

    {
        "require": {
            "archfizz/phpairplay": "dev-master"
        }
    }

Then run from the root of your project

    $ bin/airplay stream:to AppleTV.local


Caution
-------

This was a quick proof-of-concept, so I haven't test-driven this project.
The next update will use testing tools, so the commands may change.


Roadmap
-------

  * Add automated tests (PhpSpec, Behat).
  * Allow any screen capture utility to be used.
  * Allow for advanced configuration.
