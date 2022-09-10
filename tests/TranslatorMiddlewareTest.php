<?php

namespace RazonYang\Yii\TranslatorMiddleware\Tests;

use Nyholm\Psr7\ServerRequest;
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

        $service = $this->createService();
        $middleware = new TranslatorMiddleware($service);

        $response = $middleware->process($request, new FakeHandler(function (ServerRequest $request) use ($service, $locale) {
            $translator = $service->get($request);
            $this->assertSame($locale, $translator->getLocale());
            $this->assertNull($request->getAttribute('mytranslator'));
        }, 200, 'OK'));

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('OK', $response->getBody()->__toString());
    }
}
