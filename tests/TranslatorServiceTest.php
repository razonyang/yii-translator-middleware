<?php

namespace RazonYang\Yii\TranslatorMiddleware\Tests;

use Nyholm\Psr7\ServerRequest;
use RazonYang\Yii\TranslatorMiddleware\Exception\InvalidTranslatorExcepction;
use RazonYang\Yii\TranslatorMiddleware\Exception\TranslatorNotFoundExcepction;
use Yiisoft\Translator\Translator;

class TranslatorServiceTest extends TestCase
{
    public function getProvider(): array
    {
        return [
            [
                'en-US',
            ],
            [
                'zh-CN',
            ],
            [
                'de',
            ],
        ];
    }

    /**
     * @dataProvider getProvider
     */
    public function testGet(string $locale): void
    {
        $translator = new Translator($locale);
        $request = (new ServerRequest('GET', '/'))
            ->withAttribute($this->translatorAttribute, $translator);
        $service = $this->createService();
        $this->assertSame($locale, $service->get($request)->getLocale());
    }

    public function testGetWithoutMiddleware(): void
    {
        $request = (new ServerRequest('GET', '/'));
        $service = $this->createService();
        $this->expectException(TranslatorNotFoundExcepction::class);
        $service->get($request);
    }

    public function testGetInvalidTranslator(): void
    {
        $translator = new Translator('en-US');
        $request = (new ServerRequest('GET', '/'))
            ->withAttribute($this->translatorAttribute, $translator);
        $request = $request->withAttribute($this->translatorAttribute, 'invalid translator');
        $service = $this->createService();
        $this->expectException(InvalidTranslatorExcepction::class);
        $service->get($request);
    }
}
