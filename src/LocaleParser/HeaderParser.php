<?php

declare(strict_types=1);

namespace RazonYang\Yii\TranslatorMiddleware\LocaleParser;

use Psr\Http\Message\ServerRequestInterface;
use RazonYang\Yii\TranslatorMiddleware\LocaleParserInterface;

/**
 * HeaderParser implements the LocaleParserInterface that parse the locale from request header.
 */
final class HeaderParser implements LocaleParserInterface
{
    private string $headerName = 'Accept-Language';

    public function parse(ServerRequestInterface $request): ?string
    {
        return $this->parseHeader($request->getHeaderLine($this->headerName));
    }

    private function parseHeader(string $value): ?string
    {
        $value = trim($value);
        if ($value === '') {
            return null;
        }

        $langs = [];
        $items = explode(',', $value);
        foreach ($items as $item) {
            $parts = explode(';', $item);
            $langs[] = [
                'name' => trim($parts[0]),
                'quality' => isset($parts[1]) ? floatval(str_replace('q=', '', trim($parts[1]))) : 1,
            ];
        }

        usort(
            $langs,
            fn ($a, $b): int =>  $a['quality']==$b['quality'] ? 0 : ($a['quality'] > $b['quality'] ? -1 : 1)
        );

        return $langs[0]['name'];
    }
}
