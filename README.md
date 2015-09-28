Php to Zephir
=============

[![Build Status](https://travis-ci.org/fezfez/php-to-zephir.svg?branch=master)](https://travis-ci.org/fezfez/php-to-zephir)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/fezfez/php-to-zephir/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/fezfez/php-to-zephir/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/fezfez/php-to-zephir/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/fezfez/php-to-zephir/?branch=master)

> Convert PHP to Zephir.

This project is builded on top of nikic/PHP-Parser


Install
=======

```bash
composer require fezfez/php-to-zephir
```


How to use
====

```bash
vendor/bin/php-to-zephir phpToZephir:convert myDirToConvert 
```
    
It converts all files recursivly to [Zephir](https://github.com/phalcon/zephir) language.

Issue
=====

If you find a bug, please report it by new issue (with tested php code to see error).

Fatal error :'(
====

If you find a fatal error during converting, add --debug and identify where the fatal error happen. 
Then open issue !
