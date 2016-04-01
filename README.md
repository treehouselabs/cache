> This package is DEPRECATED use a PSR-6 solution like https://github.com/php-cache/cache

Cache
=====

Library for quick and easy caching. Supports multiple drivers.

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]

## Installation

```sh
composer require treehouselabs/cache:^2.0
```

## Usage

```php
$driver = new RedisDriver($redis);
$serializer = new JsonSerializer();
$cache = new Cache($driver, $serializer);

$cache->set('foo', 'bar', 60);
$cache->get('foo');
$cache->has('foo');
$cache->remove('foo');
```

[ico-version]: https://img.shields.io/packagist/v/treehouselabs/cache.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/treehouselabs/cache/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/treehouselabs/cache.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/treehouselabs/cache.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/treehouselabs/cache.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/treehouselabs/cache
[link-travis]: https://travis-ci.org/treehouselabs/cache
[link-scrutinizer]: https://scrutinizer-ci.com/g/treehouselabs/cache/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/treehouselabs/cache
[link-downloads]: https://packagist.org/packages/treehouselabs/cache
[link-author]: https://github.com/treehouselabs
[link-contributors]: ../../contributors
