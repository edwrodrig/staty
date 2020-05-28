edwrodrig\staty
========
A PHP library to generate static generated sites.
This library is the successor of my older library [older library](https://github.com/edwrodrig/static_generator).

[![Latest Stable Version](https://poser.pugx.org/edwrodrig/staty/v/stable)](https://packagist.org/packages/edwrodrig/staty)
[![Total Downloads](https://poser.pugx.org/edwrodrig/staty/downloads)](https://packagist.org/packages/edwrodrig/staty)
[![License](https://poser.pugx.org/edwrodrig/staty/license)](https://packagist.org/packages/edwrodrig/staty)
[![Build Status](https://travis-ci.org/edwrodrig/staty.svg?branch=master)](https://travis-ci.org/edwrodrig/staty)
[![codecov.io Code Coverage](https://codecov.io/gh/edwrodrig/staty/branch/master/graph/badge.svg)](https://codecov.io/github/edwrodrig/staty?branch=master)
[![Code Climate](https://codeclimate.com/github/edwrodrig/staty/badges/gpa.svg)](https://codeclimate.com/github/edwrodrig/staty)

### Why use a static generator instead of write the pages yourself?

Front-end it's just declarative. The only exception is javascript, but it is not a decent way to doing any serious programming. Because the language is bad and the browser incompatibility. So less javascript is the best.
Although, there is a lot of work can be automated, or encapsulated in functions. For example, if you have a blog, every post page shares the same header and footer. So it's very convenient to encapsulate the header and footer in functions, so you can print this in the following way:
```
<?php header() ?>
<div>
  <h1>My post</h1>
  <p>My content</p>
</div>
<?php footer() ?>
```
This is very natural for an old school php programmer.
The static generator allows you to write this file easier.
Generally you have some kind of sources in a folder structured that match and output file, then you traverse the folder and generate every page.
Something like this:
```
foreach ( $files as $file ) {
  ob_start();
  include $file;
  $content = ob_get_clean();
  file_put_contents('output/path/suitable/for/copy/to/httpd/' . $file, $content);
}
```
This is what a static generator does but handling with border cases and easily creating addons. It's not a very complicated system.

## My use cases

 * Easy to migrate a plain static html page to this system.
 * Easily create page templates in pure PHP.
 * I want to maintain the things as [simple as possible](https://en.wikipedia.org/wiki/KISS_principle)  

## Documentation
The source code uses [phpDocumentor](http://docs.phpdoc.org/references/phpdoc/basic-syntax.html) style for the documentation,
so it should pop up nicely if you're using [PhpStorm](https://www.jetbrains.com/phpstorm).

## Composer
```
composer require edwrodrig/staty
```

## My current system information
Output of [system_info.sh](https://github.com/edwrodrig/staty/blob/master/scripts/system_info.sh)
```
  Operating System: Ubuntu 20.04 LTS
            Kernel: Linux 5.4.0-31-generic
PHP 7.4.3 (cli) (built: May  5 2020 12:14:27) ( NTS )
Copyright (c) The PHP Group
Zend Engine v3.4.0, Copyright (c) Zend Technologies
    with Zend OPcache v7.4.3, Copyright (c), by Zend Technologies
    with Xdebug v2.9.2, Copyright (c) 2002-2020, by Derick Rethans
```

## Testing
The tests were build using [PHPUnit](https://phpunit.de/). It generates images and compare the signature with expected ones. Maybe some test fails due metadata of some generated images, but at the moment I haven't any reported issue.

## License
MIT license. Use it as you want at your own risk.

## About language
I'm not a native English writer. So there may be a lot of grammar and orthographical errors in the text, I'm just trying my best. Please, feel free to correct my language, any contribution is welcome. They are a learning instance.
