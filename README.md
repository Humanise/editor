# Humanise Editor #


Humanise Editor is a simple CMS system written in PHP using MySQL. 

* [Info and installation guide](http://www.humanise.dk/en/opensource/onlinepublisher/)
* [Product description in danish](http://www.humanise.dk/produkter/onlinepublisher/)

Write me at jonas@humanise.dk for more info.

http://www.humanise.dk/

## Setup

### HUI
The system depends on Humanise User Interface which should be placed in the root folder and be called "hui". Just clone it in the root folder...

    git clone git@github.com:Humanise/hui.git

The "hui" dir is ignored in this repository.

### Grunt

Install Grunt

	npm install -g grunt-cli

Install node modules

	npm install

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