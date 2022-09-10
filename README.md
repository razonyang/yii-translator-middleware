# Yii Translator HTTP Middleware

[![Latest Stable Version](https://poser.pugx.org/razonyang/yii-translator-middleware/v/stable.png)](https://packagist.org/packages/razonyang/yii-translator-middleware)
[![Total Downloads](https://poser.pugx.org/razonyang/yii-translator-middleware/downloads.png)](https://packagist.org/packages/razonyang/yii-translator-middleware)
[![Build Status](https://github.com/razonyang/yii-translator-middleware/workflows/build/badge.svg)](https://github.com/razonyang/yii-translator-middleware/actions)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/razonyang/yii-translator-middleware/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/razonyang/yii-translator-middleware/?branch=main)
[![Code Coverage](https://scrutinizer-ci.com/g/razonyang/yii-translator-middleware/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/razonyang/yii-translator-middleware/?branch=main)

A HTTP middleware for [Yii Translator](https://github.com/razonyang/yii-translator-middleware).

## How it works?

1. The `TranslatorMiddleware` parse the locale from incoming request and store the translator instance into request.
1. The subsequent middlewares and handlers can retrieves the translator instance by `TranslatorServiceInterface::get` or `TranslatorServiceInterface::fromAttributes`.

## Installation

The package could be installed with composer:

```shell
composer require razonyang/yii-translator-middleware --prefer-dist
```

## Configurations

```php
<?php

declare(strict_types=1);

use RazonYang\Yii\TranslatorMiddleware\LocaleParser\CompositeParser;
use RazonYang\Yii\TranslatorMiddleware\LocaleParser\HeaderParser;
use RazonYang\Yii\TranslatorMiddleware\LocaleParser\QueryParamsParser;
use RazonYang\Yii\TranslatorMiddleware\LocaleParserInterface;
use RazonYang\Yii\TranslatorMiddleware\TranslatorMiddleware;
use RazonYang\Yii\TranslatorMiddleware\TranslatorService;
use RazonYang\Yii\TranslatorMiddleware\TranslatorServiceInterface;
use Yiisoft\Definitions\Reference;
use Yiisoft\Mutex\Synchronizer;
use Yiisoft\Translator\TranslatorInterface;

return [
    TranslatorMiddleware::class => [
        'class' => TranslatorMiddleware::class,
        '__construct()' => [
            Reference::to(TranslatorServiceInterface::class),
        ],
    ],

    TranslatorServiceInterface::class => TranslatorService::class,
    TranslatorService::class => [
        'class' => TranslatorService::class,
        '__construct()' => [
            Reference::to(Synchronizer::class),
            Reference::to(LocaleParserInterface::class),
            Reference::to(TranslatorInterface::class),
        ],
    ],

    LocaleParserInterface::class => CompositeParser::class,
    CompositeParser::class => [
        'class' => CompositeParser::class,
        '__construct()' => [
            Reference::to(QueryParamsParser::class),
            Reference::to(HeaderParser::class),
        ],
    ],
    QueryParamsParser::class => [
        'class' => QueryParamsParser::class,
        '__construct()' => [
            'lang',
        ],
    ],
];
```

### Locale Parsers

- `CompositeParser`: parse locale from multiple parsers, returns immediately if success.
- `HeaderParser`: parse locale from the `Accept-Language` header.
- `QueryParamsParser`: parse locale from the specified query parameter, default to `lang`.
