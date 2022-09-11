<?php

namespace RazonYang\Yii\TranslatorMiddleware\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use RazonYang\Yii\TranslatorMiddleware\LocaleParser\CompositeParser;
use RazonYang\Yii\TranslatorMiddleware\LocaleParser\HeaderParser;
use RazonYang\Yii\TranslatorMiddleware\LocaleParser\QueryParamsParser;
use RazonYang\Yii\TranslatorMiddleware\TranslatorMiddleware;
use Yiisoft\Translator\Translator;

class TestCase extends BaseTestCase
{
    protected function createMiddleware(): TranslatorMiddleware
    {
        $parser = new CompositeParser(
            new QueryParamsParser(),
            new HeaderParser(),
        );
        $translator = new Translator('en-US');
        return new TranslatorMiddleware(
            $parser,
            $translator,
        );
    }
}
