<?php

namespace RazonYang\Yii\TranslatorMiddleware\Tests\LocaleParser;

use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use RazonYang\Yii\TranslatorMiddleware\LocaleParser\QueryParamsParser;

class QueryParamsParserTest extends TestCase
{
    public function parseProvider(): array
    {
        return [
            ['/', 'lang', null],
            ['/?lang=', 'lang', null],
            ['/?lang=en-US', 'lang', 'en-US'],
            ['/?lang=en-US', 'locale', null],
        ];
    }

    /**
     * @dataProvider parseProvider
     */
    public function testParse(string $url, string $query, string|null $locale): void
    {
        $params = [];
        $queryString = parse_url($url, PHP_URL_QUERY);
        if ($queryString) {
            parse_str($queryString, $params);
        }

        $request = (new ServerRequest('GET', $url))->withQueryParams($params);
        $parser = new QueryParamsParser($query);

        $this->assertSame($locale, $parser->parse($request));
    }
}
