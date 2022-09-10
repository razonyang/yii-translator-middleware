<?php

namespace RazonYang\Yii\TranslatorMiddleware\Tests\LocaleParser;

use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use RazonYang\Yii\TranslatorMiddleware\LocaleParser\HeaderParser;

class HeaderParserTest extends TestCase
{
    public function parseProvider(): array
    {
        return [
            [
                '',
                null,
            ],
            [
                ' ',
                null,
            ],
            [
                'zh',
                'zh',
            ],
            [
                'zh ',
                'zh',
            ],
            [
                ' zh ',
                'zh',
            ],
            [
                ' zh',
                'zh',
            ],
            [
                'zh-CN',
                'zh-CN',
            ],
            [
                'zh-CN,en-US',
                'zh-CN',
            ],
            [
                'en-US,en;q=0.5',
                'en-US',
            ],
            [
                'zh-CN;q=0.8,zh;q=0.8',
                'zh-CN',
            ],
            [
                'zh;q=0.8,zh-CN;q=0.8',
                'zh',
            ],
            [
                'zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2',
                'zh-CN',
            ],
            [
                'zh-CN;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.9,en;q=0.2',
                'en-US',
            ],
        ];
    }

    /**
     * @dataProvider parseProvider
     */
    public function testParse(string $langs, string|null $locale): void
    {
        $request = new ServerRequest('GET', '/', ['Accept-Language' => $langs]);
        $parser = new HeaderParser();
        $this->assertSame($locale, $parser->parse($request));
    }
}
