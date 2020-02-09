# Anper\CallableAggregate

[![Software License][ico-license]](LICENSE.md)
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Build Status][ico-ga]][link-ga]

Aggregating the same callable types to one invoking object.

## Install

``` bash
$ composer require anper/callable-aggregate
```

## Usage

``` php
use Anper\CallableAggregate\CallableAggregate;

$aggregate = new CallableAggregate();
$aggregate->append(function ($arg) {
    echo $arg;
});

$aggregate('hello');
```

## Test

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/anper/callable-aggregate.svg
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg
[ico-ga]: https://github.com/perevoshchikov/callable-aggregate/workflows/Tests/badge.svg

[link-packagist]: https://packagist.org/packages/anper/callable-aggregate
[link-ga]: https://github.com/perevoshchikov/callable-aggregate/actions
