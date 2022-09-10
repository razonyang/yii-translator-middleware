<?php

namespace RazonYang\Yii\TranslatorMiddleware\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use RazonYang\Yii\TranslatorMiddleware\LocaleParser\CompositeParser;
use RazonYang\Yii\TranslatorMiddleware\LocaleParser\HeaderParser;
use RazonYang\Yii\TranslatorMiddleware\LocaleParser\QueryParamsParser;
use RazonYang\Yii\TranslatorMiddleware\TranslatorService;
use RazonYang\Yii\TranslatorMiddleware\TranslatorServiceInterface;
use Yiisoft\Mutex\File\FileMutexFactory;
use Yiisoft\Mutex\Synchronizer;
use Yiisoft\Translator\Translator;

class TestCase extends BaseTestCase
{
    protected string $translatorAttribute = 'translator';

    protected function createService(): TranslatorServiceInterface
    {
        $mutexFactory = new FileMutexFactory(sys_get_temp_dir(). DIRECTORY_SEPARATOR  .'yii-translator-middleware');
        $synchronizer = new Synchronizer($mutexFactory);
        $localeParser = new CompositeParser(
            new QueryParamsParser(),
            new HeaderParser(),
        );
        $translator = new Translator('en-US');
        $service = new TranslatorService($synchronizer, $localeParser, $translator, $this->translatorAttribute);
        return $service;
    }
}
