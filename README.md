
[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/support-ukraine.svg?t=1" />](https://supportukrainenow.org)

# Some additional features on top of Spatie's [Eloquent Sortable](https://github.com/spatie/eloquent-sortable)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/oddvalue/eloquent-sortable.svg?style=flat-square)](https://packagist.org/packages/oddvalue/eloquent-sortable)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/oddvalue/eloquent-sortable/run-tests?label=tests)](https://github.com/oddvalue/eloquent-sortable/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/oddvalue/eloquent-sortable/Check%20&%20fix%20styling?label=code%20style)](https://github.com/oddvalue/eloquent-sortable/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/oddvalue/eloquent-sortable.svg?style=flat-square)](https://packagist.org/packages/oddvalue/eloquent-sortable)

This package is an extension of Spatie's excellent [Eloquent Sortable](https://github.com/spatie/eloquent-sortable). It adds the following additional features:

* New methods
    - `$model->moveBefore(Sortable $target): void`
    - `$model->moveAfter(Sortable $target): void`
    - `$model->moveBetween(Sortable $before = null, Sortable $after = null): void` 
    - `$model->moveTo(int|Sortable $newPosition): void`
* New features
    - Automatic re-sorting after deleting a model

## Installation

You can install the package via composer:

```bash
composer require oddvalue/eloquent-sortable
```

## Usage

Implement the interface and use the trait.
```php
class MyModel extends Model implements \Oddvalue\EloquentSortable\Sortable
{
    use \Oddvalue\EloquentSortable\SortableTrait;
}
```

```php
# New model automatically sorted to the end of the list upon creation
$model = MyModel::create(['title' => 'foo']);

# Move the model before the 5th item in the list
$model->moveBefore(MyModel::where('order_column', 5)->first());

# Move the model after the 5th item in the list
$model->moveAfter(MyModel::where('order_column', 5)->first());

# Move the model between the 5th and 6th item in the list
$model->moveBetween(
    MyModel::where('order_column', 5)->first(), 
    MyModel::where('order_column', 6)->first()
);
# This is useful when using a javacript library that provides the node before
# and after the location an item is dropped

# Move the model to the 5th item in the list
$model->moveTo(5);
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/oddvalue/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [jim](https://github.com/oddvalue)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
