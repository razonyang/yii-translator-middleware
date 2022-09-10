<?php

declare(strict_types=1);

namespace RazonYang\Yii\TranslatorMiddleware\Tests\LocaleParser;

use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use RazonYang\Yii\TranslatorMiddleware\LocaleParser\CompositeParser;
use RazonYang\Yii\TranslatorMiddleware\LocaleParser\HeaderParser;
use RazonYang\Yii\TranslatorMiddleware\LocaleParser\QueryParamsParser;

class CompositeParserTest extends TestCase
{
    public function testNoParsers(): void
    {
        $this->expectExceptionMessage('No parser specified.');
        new CompositeParser();
    }

    public function parseProvider(): array
    {
        return [
            [
                '/',
                 '',
                  null,
            ],
            [
                '/?lang=en-US',
                 '',
                 'en-US',
            ],
            [
                '/?lang=en-US',
                 'zh-CN',
                'en-US',
            ],
            [
                '/',
                 'zh-CN',
                 'zh-CN',
            ],
            [
                '/?lang=',
                'zh-CN',
                 'zh-CN',
            ],
            [
                '/?lang=',
                 '',
                 null,
            ],
        ];
    }

    /**
     * @dataProvider parseProvider
     */
    public function testParse(string $url, string $header, string|null $locale): void
    {
        $parser = new CompositeParser(
            new QueryParamsParser('lang'),
            new HeaderParser(),
        );

        $params = [];
        $queryString = parse_url($url, PHP_URL_QUERY);
        if ($queryString) {
            parse_str($queryString, $params);
        }

        $request = (new ServerRequest('GET', $url, ['Accept-Language' => $header]))
            ->withQueryParams($params);

        $this->assertSame($locale, $parser->parse($request));
    }
}
