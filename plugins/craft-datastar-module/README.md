[![Stable Version](https://img.shields.io/packagist/v/putyourlightson/craft-datastar-module?label=stable)]((https://packagist.org/packages/putyourlightson/craft-datastar-module))
[![Total Downloads](https://img.shields.io/packagist/dt/putyourlightson/craft-datastar-module)](https://packagist.org/packages/putyourlightson/craft-datastar-module)

<p align="center"><img width="150" src="https://putyourlightson.com/assets/logos/datastar.svg"></p>

# Datastar Module for Craft CMS

This module provides the core functionality for the [Datastar plugin](https://github.com/putyourlightson/craft-datastar). If you are developing a Craft plugin/module and would like to use Datastar in the control panel, you can require this package to give you its functionality, without requiring that the Datastar plugin is installed.

First require the package in your plugin/module’s `composer.json` file.

```json
{
  "require": {
    "putyourlightson/craft-datastar-module": "^1.0.0-beta.1"
  }
}
```

Then bootstrap the module from within your plugin/module’s `init` method.

```php
use craft\base\Plugin;
use putyourlightson\datastar\Datastar;

class MyPlugin extends Plugin
{
    public function init()
    {
        parent::init();

        Datastar::bootstrap();
    }
}
```

Then use the Datastar function and tags as normal in your control panel templates.

```twig
<button data-on-click="{{ datastar.sse('_datastar/search') }}">Search</button>
```

Datastar plugin issues should be reported to https://github.com/putyourlightson/craft-datastar/issues

The Datastar plugin changelog is at https://github.com/putyourlightson/craft-datastar/blob/develop/CHANGELOG.md

## Documentation

Learn more and read the documentation at [putyourlightson.com/plugins/datastar »](https://putyourlightson.com/plugins/datastar)

## License

This plugin is licensed for free under the MIT License.

## Requirements

This plugin requires [Craft CMS](https://craftcms.com/) 5.0.0 or later.

## Installation

Install this package via composer.

```shell
composer require putyourlightson/craft-datastar-module:^1.0.0-beta.1
```

---

Created by [PutYourLightsOn](https://putyourlightson.com/).
