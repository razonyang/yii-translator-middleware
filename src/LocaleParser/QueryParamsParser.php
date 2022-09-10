<?php

declare(strict_types=1);

namespace RazonYang\Yii\TranslatorMiddleware\LocaleParser;

use Psr\Http\Message\ServerRequestInterface;
use RazonYang\Yii\TranslatorMiddleware\LocaleParserInterface;

/**
 * QueryParamsParser implements the LocaleParserInterface that parse the locale from request query params.
 */
final class QueryParamsParser implements LocaleParserInterface
{
    public function __construct(
        private string $query = 'lang',
    ) {
    }

    public function parse(ServerRequestInterface $request): ?string
    {
        $params = $request->getQueryParams();
        return empty($params[$this->query]) ? null : $params[$this->query];
    }
}
