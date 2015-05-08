Cache
=====

Library for quick and easy caching. Supports multiple drivers.

[![Build Status](https://travis-ci.org/treehouselabs/cache.svg?branch=master)](https://travis-ci.org/treehouselabs/cache)

## Installation

```sh
composer require treehouselabs/cache:~1.0
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
