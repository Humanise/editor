# Humanise Editor #


Humanise Editor is a simple CMS written in PHP using MySQL.

* [Info and installation guide](http://www.humanise.dk/en/opensource/onlinepublisher/)
* [Product description in danish](http://www.humanise.dk/produkter/onlinepublisher/)

Note: It has only been developed and run on Linux and macOS – it will probably not work on Windows (sorry).

Humanise Editor is developed by [Jonas Brinkmann Munk](http://www.jonasmunk.com/) from [Humanise](http://www.humanise.dk/)

Write me at jonas@humanise.dk for more information.

## Setup

### Get the code

Check out this repository and serve it via Apache HTTPd

	git clone git@github.com:Humanise/editor.git

### HUI
The system depends on Humanise User Interface which should be placed in the root folder and be called "hui". Just clone it in the root folder...

    git clone git@github.com:Humanise/hui.git

The "hui" dir is ignored in this repository.

### Grunt

Install Grunt

	npm install -g grunt-cli

Install node modules

	npm install

### PHP dependencies

* XSL
* OpenSSL
* MySQL
* Mbstring
* GD
* curl
* iconv

**Macports:**

```
sudo port install php72 php72-xsl php72-openssl php72-mysql php72-mbstring php72-gd php72-curl php72-iconv
```


## Development

Install minify CLI

	sudo npm install minifier -g

Install SVGO - tool for minifying SVGs

	sudo npm install -g svgo

## Styling

	/api/prototype?name=humanise/test
	/Editor/Tools/Builder/actions/render.php?path=style/humanise/views/git

## Code formatting

Download PHP-CS-Fixer from [PHP-CS-Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) and place it in the root directory.

	curl -O http://cs.sensiolabs.org/download/php-cs-fixer-v2.phar

Run grunt

	grunt format