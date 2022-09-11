<?php

namespace RazonYang\Yii\TranslatorMiddleware\Tests;

use Nyholm\Psr7\ServerRequest;
use RazonYang\Yii\TranslatorMiddleware\Exception\InvalidTranslatorExcepction;
use RazonYang\Yii\TranslatorMiddleware\Exception\TranslatorNotFoundExcepction;
use RazonYang\Yii\TranslatorMiddleware\TranslatorMiddleware;

class TranslatorMiddlewareTest extends TestCase
{
    public function processProvider(): array
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
     * @dataProvider processProvider
     */
    public function testProcess(string $locale): void
    {
        $url = '/?lang=' . $locale;
        $params = [];
        parse_str(parse_url($url, PHP_URL_QUERY), $params);

        $request = (new ServerRequest('GET', $url))
            ->withQueryParams($params);

        $middleware = $this->createMiddleware();

        $response = $middleware->process($request, new FakeHandler(function (ServerRequest $request) use ($locale) {
            $this->assertSame($locale, TranslatorMiddleware::getTranslator($request)->getLocale());
            $this->assertSame($locale, TranslatorMiddleware::getTranslatorByAttributes($request->getAttributes())->getLocale());
        }, 200, 'OK'));

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('OK', $response->getBody()->__toString());
    }

    public function testTranslatorNotFound(): void
    {
        $request = (new ServerRequest('GET', '/'));
        $this->expectException(TranslatorNotFoundExcepction::class);
        TranslatorMiddleware::getTranslator($request);
    }

    public function testInvalidTranslator(): void
    {
        $request = (new ServerRequest('GET', '/'));
        // override the translator instance.
        $request = $request->withAttribute(TranslatorMiddleware::class, 'invalid translator');

        $this->expectException(InvalidTranslatorExcepction::class);
        TranslatorMiddleware::getTranslator($request);
    }
}
